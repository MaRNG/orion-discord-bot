<?php
include __DIR__.'/../vendor/autoload.php';

printf('Setup robot loader...' . PHP_EOL);

$loader = new \Nette\Loaders\RobotLoader();

$loader->addDirectory(__DIR__ . '/App');
$loader->setTempDirectory(__DIR__ . '/../temp');

printf('Register robot loader...' . PHP_EOL);

$loader->register();

printf('Setup debugger...' . PHP_EOL);

\Tracy\Debugger::enable(true, __DIR__ . '/../log');
\Tracy\Debugger::$showBar = false;

printf('Setup entity handler...' . PHP_EOL);

\App\Model\Database\Handler\EntityHandler::getInstance();