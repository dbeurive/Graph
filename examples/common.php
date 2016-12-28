<?php

// Copyright (c) 2016 Denis BEURIVE
//
// This work is licensed under the Creative Common Attribution-NonCommercial 4.0 International (CC BY-NC 4.0).

define('DIR_IMAGES',  __DIR__ . DIRECTORY_SEPARATOR . 'images'  . DIRECTORY_SEPARATOR);
define('DIR_DOTS',    __DIR__ . DIRECTORY_SEPARATOR . 'dots'    . DIRECTORY_SEPARATOR);
define('DIR_SCRIPTS', __DIR__ . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR);
define('DIR_CSVS',    __DIR__ . DIRECTORY_SEPARATOR . 'csvs'    . DIRECTORY_SEPARATOR);
define('DIR_INPUTS',  __DIR__ . DIRECTORY_SEPARATOR . 'inputs'  . DIRECTORY_SEPARATOR);

function dotCommand($inDotPath, $inImagePath) {
    return implode(' ',array('dot', '-Tgif', '-o', escapeshellarg($inImagePath), escapeshellarg($inDotPath)));
}

function dumpDotScript($inPath, array $inCommands) {
    file_put_contents($inPath, implode(PHP_EOL, $inCommands));
    chmod($inPath, 0777);
}

