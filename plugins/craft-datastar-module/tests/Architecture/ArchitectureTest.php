<?php

/**
 * Tests the architecture of the plugin.
 */

test('Source code does not contain any `Craft::dd` statements')
    ->expect(Craft::class)
    ->not->toUse(['dd']);

test('Source code does not contain any `var_dump` or `die` statements')
    ->todo()
    ->expect(['var_dump', 'die'])
    ->not->toBeUsed();
