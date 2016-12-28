<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

// If we are developing the library, then the autoload script should be here: ../vendor/autoload.php.
// Otherwise, it should be here: ../../../../autoload.php.

$pathTokens = ['..', 'vendor', 'autoload.php'];
$path = __DIR__ . DIRECTORY_SEPARATOR . join(DIRECTORY_SEPARATOR, $pathTokens);

if (! file_exists($path)) {
    array_unshift($pathTokens, '..', '..', '..');
    $path = __DIR__ . DIRECTORY_SEPARATOR . join(DIRECTORY_SEPARATOR, $pathTokens);
}

require_once $path;

