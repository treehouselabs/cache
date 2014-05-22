<?php

$loader = new \Composer\Autoload\ClassLoader();
$loader->setPsr4('TreeHouse\\Cache\\Tests\\', __DIR__ . '/TreeHouse/Cache/Tests');
$loader->setPsr4('Doctrine\\Tests\\', __DIR__ . '/../vendor/doctrine/cache/tests/Doctrine/Tests');
$loader->register();
