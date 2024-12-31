<?php

/**
 * Tests the SSE service.
 */

use putyourlightson\datastar\Datastar;
use putyourlightson\datastar\services\SseService;
use yii\web\BadRequestHttpException;

beforeEach(function() {
    Datastar::getInstance()->set('sse', SseService::class);
});

test('Test that calling an SSE method when another one is in process throws an exception', function() {
    Datastar::getInstance()->sse->setSseMethodInProcess('mergeFragments');
    Datastar::getInstance()->sse->mergeSignals([]);
})->throws(BadRequestHttpException::class);
