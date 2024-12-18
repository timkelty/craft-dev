<?php
/**
 * Craft web bootstrap file
 */

// Load shared bootstrap
require dirname(__DIR__) . '/bootstrap.php';

// header('Surrogate-Control: content="ESI/1.0"');

// Load and run Craft
/** @var craft\web\Application $app */
$app = require CRAFT_VENDOR_PATH . '/craftcms/cms/bootstrap/web.php';
$app->run();
