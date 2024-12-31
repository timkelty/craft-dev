<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\datastar\services;

use Craft;
use craft\base\Component;
use craft\helpers\Json;
use craft\helpers\UrlHelper;
use putyourlightson\datastar\Datastar;
use putyourlightson\datastar\models\ConfigModel;
use putyourlightson\datastar\models\SignalsModel;
use putyourlightson\datastar\twigextensions\nodes\ExecuteScriptNode;
use putyourlightson\datastar\twigextensions\nodes\FragmentNode;
use starfederation\datastar\ServerSentEventGenerator;
use Throwable;
use Twig\Error\SyntaxError;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class SseService extends Component
{
    /**
     * The server sent event generator.
     */
    private ServerSentEventGenerator|null $sse = null;

    /**
     * The server sent event method currently in process.
     */
    private ?string $sseMethodInProcess = null;

    /**
     * The CSRF token to include in the request.
     */
    private ?string $csrfToken = null;

    /**
     * Returns a Datastar URL endpoint.
     */
    public function getUrl(string $template, array $variables = [], bool $includeCsrfToken = false): string
    {
        $config = new ConfigModel([
            'siteId' => Craft::$app->getSites()->getCurrentSite()->id,
            'template' => $template,
            'variables' => $variables,
            'includeCsrfToken' => $includeCsrfToken,
        ]);

        if (!$config->validate()) {
            throw new SyntaxError(implode(' ', $config->getFirstErrors()));
        }

        return UrlHelper::actionUrl('datastar-module', [
            'config' => $config->getHashed(),
        ]);
    }

    /**
     * Merges HTML fragments into the DOM.
     *
     * @used-by FragmentNode
     */
    public function mergeFragments(string $data, array $options = []): void
    {
        $options = $this->mergeEventOptions(
            Datastar::getInstance()->settings->defaultFragmentOptions,
            $options,
        );

        $this->callSse('mergeFragments', $data, $options);
    }

    /**
     * Removes HTML fragments from the DOM.
     */
    public function removeFragments(string $selector, array $options = []): void
    {
        $options = $this->mergeEventOptions(
            Datastar::getInstance()->settings->defaultFragmentOptions,
            $options,
        );

        $this->callSse('removeFragments', $selector, $options);
    }

    /**
     * Merges signals.
     */
    public function mergeSignals(array $signals, array $options = []): void
    {
        $options = $this->mergeEventOptions(
            Datastar::getInstance()->settings->defaultSignalOptions,
            $options,
        );

        $this->callSse('mergeSignals', $signals, $options);
    }

    /**
     * Removes signal paths.
     */
    public function removeSignals(array $paths, array $options = []): void
    {
        $this->callSse('removeSignals', $paths, $options);
    }

    /**
     * Executes JavaScript in the browser.
     *
     * @used-by ExecuteScriptNode
     */
    public function executeScript(string $script, array $options = []): void
    {
        $options = $this->mergeEventOptions(
            Datastar::getInstance()->settings->defaultExecuteScriptOptions,
            $options,
        );

        $this->callSse('executeScript', $script, $options);
    }

    /**
     * Runs an action and returns the response.
     */
    public function runAction(string $route, array $params = []): Response
    {
        $request = Craft::$app->getRequest();
        $request->getHeaders()->set('Accept', 'application/json');

        if ($this->csrfToken !== null) {
            $params[$request->csrfParam] = $this->csrfToken;
        }

        if ($request->getIsGet()) {
            $requestParams = $request->getQueryParams();
            $request->setQueryParams(array_merge($requestParams, $params));
        } else {
            $requestParams = $request->getBodyParams();
            $request->setBodyParams(array_merge($requestParams, $params));
        }

        $response = Craft::$app->runAction($route);

        if ($request->getIsGet()) {
            $request->setQueryParams($requestParams);
        } else {
            $request->setBodyParams($requestParams);
        }

        return $response;
    }

    /**
     * Sets the server sent event method currently in process.\
     */
    public function setSseMethodInProcess(string $method): void
    {
        $this->sseMethodInProcess = $method;
    }

    /**
     * Streams the response and returns an empty array.
     */
    public function stream(string $config, array $signals): array
    {
        $config = $this->getConfigForResponse($config);
        Craft::$app->getSites()->setCurrentSite($config->siteId);
        $this->csrfToken = $config->csrfToken;

        $signals = new SignalsModel($signals);
        $variables = array_merge(
            [Datastar::getInstance()->settings->signalsVariableName => $signals],
            $config->variables,
        );

        $this->renderTemplate($config->template, $variables);

        return [];
    }

    /**
     * Returns merged event options with null values removed.
     */
    private function mergeEventOptions(array ...$optionSets): array
    {
        $options = Datastar::getInstance()->settings->defaultEventOptions;

        foreach ($optionSets as $optionSet) {
            $options = array_merge($options, $optionSet);
        }

        return array_filter($options, fn($value) => $value !== null);
    }

    /**
     * Returns a server sent event generator.
     */
    private function getSse(): ServerSentEventGenerator
    {
        if ($this->sse === null) {
            $this->sse = new ServerSentEventGenerator();
        }

        return $this->sse;
    }

    /**
     * Calls an SSE method with arguments and cleans output buffers.
     */
    private function callSse(string $method, ...$args): void
    {
        if ($this->sseMethodInProcess && $this->sseMethodInProcess !== $method) {
            $message = 'The SSE method `' . $method . '` cannot be called when `' . $this->sseMethodInProcess . '` is already in process.';
            if (in_array($method, ['mergeSignals', 'removeSignals'])) {
                $message .= ' Ensure that you are not setting or removing signals inside `{% fragment %}` or `{% executescript %}` tags.';
            }
            $this->throwException($message);
        }

        // Clean and end all existing output buffers.
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $this->getSse()->$method(...$args);

        $this->sseMethodInProcess = null;

        // Start a new output buffer to capture any subsequent inline content.
        ob_start();
    }

    /**
     * Returns a validated config model.
     */
    private function getConfigForResponse(string $config): ConfigModel
    {
        $data = Craft::$app->getSecurity()->validateData($config);
        if ($data === false) {
            $this->throwException('Submitted data was tampered.');
        }

        return new ConfigModel(Json::decodeIfJson($data));
    }

    /**
     * Renders a template, catching exceptions.
     */
    private function renderTemplate(string $template, array $variables): void
    {
        if (!Craft::$app->getView()->doesTemplateExist($template)) {
            $this->throwException('Template `' . $template . '` does not exist.');
        }

        try {
            Craft::$app->getView()->renderTemplate($template, $variables);
        } catch (Throwable $exception) {
            $this->throwException($exception);
        }
    }

    /**
     * Throws an exception with the appropriate formats for easier debugging.
     *
     * @phpstan-return never
     */
    private function throwException(Throwable|string $exception): void
    {
        Craft::$app->getRequest()->getHeaders()->set('Accept', 'text/html');
        Craft::$app->getResponse()->format = Response::FORMAT_HTML;

        if ($exception instanceof Throwable) {
            throw $exception;
        }

        throw new BadRequestHttpException($exception);
    }
}
