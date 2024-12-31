<?php

/**
 * Tests the Datastar variable.
 */

use putyourlightson\datastar\Datastar;
use putyourlightson\datastar\services\SseService;
use putyourlightson\datastar\variables\DatastarVariable;
use Twig\Error\SyntaxError;

beforeEach(function() {
    Datastar::getInstance()->set('sse', SseService::class);
});

test('Test creating an action', function(string $method) {
    $variable = new DatastarVariable();
    $value = $variable->sse('template', ['variables' => ['x' => 1], 'method' => $method]);
    expect($value)
        ->toStartWith('sse(')
        ->toContain('template')
        ->toContain('{"method":"' . $method . '"}')
        ->not->toContain('{"variables":{"x":1}}');

    if ($method === 'get') {
        expect($value)
            ->not->toContain('csrf');
    } else {
        expect($value)
            ->toContain('csrf');
    }
})->with([
    'get',
    'post',
    'put',
    'patch',
    'delete',
]);

test('Test that creating an action containing a reserved variable name throws an exception', function() {
    $variable = new DatastarVariable();
    $variable->sse('template', ['variables' => [Datastar::getInstance()->settings->signalsVariableName => 1]]);
})->throws(SyntaxError::class);

test('Test that creating an action containing an object variable throws an exception', function() {
    $variable = new DatastarVariable();
    $variable->sse('template', ['variables' => ['object' => new stdClass()]]);
})->throws(SyntaxError::class);
