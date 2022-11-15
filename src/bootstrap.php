<?php
include __DIR__.'/../vendor/autoload.php';

$loader = new \Nette\Loaders\RobotLoader();

$loader->addDirectory(__DIR__ . '/App');
$loader->setTempDirectory(__DIR__ . '/../temp');
$loader->register();