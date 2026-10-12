<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Symfony's FileBag version used by this application expects PHP upload
// metadata to use the key "sizes". PHP provides it as "size". Normalize the
// raw superglobal before Symfony converts it into UploadedFile instances.
$normalizeUploadMetadata = static function (array $metadata) use (&$normalizeUploadMetadata): array {
    foreach ($metadata as $key => $value) {
        if (is_array($value)) {
            $metadata[$key] = $normalizeUploadMetadata($value);
        }
    }

    if (
        array_key_exists('name', $metadata)
        && array_key_exists('tmp_name', $metadata)
        && array_key_exists('error', $metadata)
        && array_key_exists('size', $metadata)
        && ! array_key_exists('sizes', $metadata)
    ) {
        $metadata['sizes'] = $metadata['size'];
        unset($metadata['size']);
    }

    return $metadata;
};

foreach ($_FILES as $field => $metadata) {
    if (is_array($metadata)) {
        $_FILES[$field] = $normalizeUploadMetadata($metadata);
    }
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
