<?php

use Rakit\Blade\Blade;
use Emsifa\Block;

if ('cli' != php_sapi_name()) {
    throw new InvalidArgumentException("Must be CLI", 1);
}

if (count($argv) < 3) {
    throw new InvalidArgumentException("Require at least 3 arguments. ".count($argv).' arguments given', 1);
}

require 'vendor/autoload.php';

// Variable yg dibutuhkan
$name = $argv[1];
$view = $argv[2];
$data = !isset($argv[3])? [] : unserialize(base64_decode($argv[3]));

if (false === $data) {
    throw new InvalidArgumentException("Data is not valid serialized array", 1);
}

// Inisialisasi instance dari library berdasarkan $name
switch($name) {
    case 'block': $lib = new Block(__DIR__.'/views/block', 'block.php'); break;
    case 'blade': $lib = new Blade([__DIR__.'/views/blade'], __DIR__.'/views/blade-cache'); break;
    default: throw new InvalidArgumentException("Name '$name' is not allowed", 1);
}

// render
echo $lib->render($view, $data);
