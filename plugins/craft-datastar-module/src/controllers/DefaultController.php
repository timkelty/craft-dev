<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\datastar\controllers;

use Craft;
use craft\web\Controller;
use putyourlightson\datastar\Datastar;
use starfederation\datastar\ServerSentEventGenerator;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    protected int|bool|array $allowAnonymous = true;

    /**
     * @inheritdoc
     */
    public function beforeAction($action): bool
    {
        if ($this->request->getIsCpRequest() && !Craft::$app->getUser()->getIdentity()->can('accessCp')) {
            throw new ForbiddenHttpException();
        }

        return parent::beforeAction($action);
    }

    public function actionIndex(): Response
    {
        $config = $this->request->getParam('config');
        $signals = ServerSentEventGenerator::readSignals();

        if (strtolower($this->request->getContentType()) === 'application/json') {
            // Clear out params to prevent them from being processed by controller actions.
            $this->request->setQueryParams([]);
            $this->request->setBodyParams([]);
        }

        // Set the response headers for the event stream.
        $this->response->getHeaders()->set('Content-Type', 'text/event-stream');
        $this->response->getHeaders()->set('Cache-Control', 'no-cache');
        $this->response->getHeaders()->set('Connection', 'keep-alive');

        // Disable buffering for Nginx.
        // https://nginx.org/en/docs/http/ngx_http_proxy_module.html#proxy_buffering
        $this->response->getHeaders()->set('X-Accel-Buffering', 'no');

        $this->response->format = Response::FORMAT_RAW;

        // Stream the response.
        $this->response->stream = function() use ($config, $signals) {
            return Datastar::getInstance()->sse->stream($config, $signals);
        };

        return $this->response;
    }
}
