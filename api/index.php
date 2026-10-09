<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$storagePath = sys_get_temp_dir().'/laravel-storage';

foreach (['app', 'framework/cache/data', 'framework/sessions', 'framework/views', 'logs'] as $directory) {
    $path = $storagePath.'/'.$directory;

    if (! is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

$_ENV['LARAVEL_STORAGE_PATH'] = $storagePath;
$_SERVER['LARAVEL_STORAGE_PATH'] = $storagePath;

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());