<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\datastar\variables;

use putyourlightson\datastar\Datastar;
use yii\web\Response;

class DatastarVariable
{
    /**
     * Returns a Datastar SSE action.
     */
    public function sse(string $template, array $options = []): string
    {
        $variables = $options['variables'] ?? [];
        unset($options['variables']);
        $method = $options['method'] ?? 'get';
        $includeCsrfToken = strtolower($method) !== 'get';

        $url = Datastar::getInstance()->sse->getUrl($template, $variables, $includeCsrfToken);

        $args = ["'$url'"];
        if (!empty($options)) {
            $args[] = json_encode($options);
        }

        return 'sse(' . implode(', ', $args) . ')';
    }

    /**
     * Runs an action and returns the response.
     */
    public function runAction(string $route, array $params = []): Response
    {
        return Datastar::getInstance()->sse->runAction($route, $params);
    }
}
