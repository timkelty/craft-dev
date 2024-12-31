<?php
/**
 * Tests the signals model.
 */

/** @noinspection PhpUndefinedFieldInspection */

/** @noinspection PhpUndefinedMethodInspection */

use putyourlightson\datastar\Datastar;
use putyourlightson\datastar\models\SignalsModel;
use putyourlightson\datastar\services\SseService;

beforeEach(function() {
    $sse = Mockery::mock(SseService::class);
    Datastar::getInstance()->set('sse', $sse);
});

test('Test getting a signal value', function() {
    $signals = new SignalsModel(['a' => 1]);
    expect($signals->get('a'))
        ->toBe(1);
});

test('Test getting a signal value using a magic call', function() {
    $signals = new SignalsModel(['a' => 1]);
    expect($signals->a)
        ->toBe(1);
});

test('Test getting a nested signal value', function() {
    $signals = new SignalsModel(['a' => ['b' => ['c' => 1]]]);
    expect($signals->get('a.b.c'))
        ->toBe(1);
});

test('Test getting a missing signal value', function() {
    $signals = new SignalsModel(['a' => 1]);
    expect($signals->get('x'))
        ->toBeNull();
});

test('Test getting a missing signal value using a magic call', function() {
    $signals = new SignalsModel(['a' => 1]);
    expect($signals->x)
        ->toBeNull();
});

test('Test adding a signal', function() {
    Datastar::getInstance()->sse->shouldReceive('mergeSignals');
    $signals = new SignalsModel([]);
    $signals->set('a', 1);
    expect($signals->get('a'))
        ->toBe(1);
});

test('Test adding a signal using a magic call', function() {
    Datastar::getInstance()->sse->shouldReceive('mergeSignals');
    $signals = new SignalsModel([]);
    $signals->a(1);
    expect($signals->get('a'))
        ->toBe(1);
});

test('Test modifying an existing signal', function() {
    Datastar::getInstance()->sse->shouldReceive('mergeSignals');
    $signals = new SignalsModel(['a' => 1]);
    $signals->set('a', 2);
    expect($signals->get('a'))
        ->toBe(2);
});

test('Test modifying an existing signal using a magic call', function() {
    Datastar::getInstance()->sse->shouldReceive('mergeSignals');
    $signals = new SignalsModel(['a' => 1]);
    $signals->a(2);
    expect($signals->get('a'))
        ->toBe(2);
});

test('Test adding a nested signal', function() {
    Datastar::getInstance()->sse->shouldReceive('mergeSignals');
    $signals = new SignalsModel([]);
    $signals->set('a.b.c', 1);
    expect($signals->get('a.b.c'))
        ->toBe(1);
});

test('Test modifying an existing nested signal', function() {
    Datastar::getInstance()->sse->shouldReceive('mergeSignals');
    $signals = new SignalsModel(['a' => ['b' => ['c' => 1]]]);
    $signals->set('a.b.c', 2);
    expect($signals->get('a.b.c'))
        ->toBe(2);
});

test('Test removing a signal value', function() {
    Datastar::getInstance()->sse->shouldReceive('removeSignals');
    $signals = new SignalsModel(['a' => 1]);
    $signals->remove('a');
    expect($signals->getValues())
        ->toBe([]);
});

test('Test removing a nested signal value', function() {
    Datastar::getInstance()->sse->shouldReceive('removeSignals');
    $signals = new SignalsModel(['a' => ['b' => ['c' => 1]]]);
    $signals->remove('a.b.c');
    expect($signals->getValues())
        ->toBe(['a' => ['b' => []]]);
});
