<?php
include __DIR__ . '/src/bootstrap.php';

$requestSender = new \App\Steam\RequestSender();

// 1517290

$games = $requestSender->getPlayerCount('1517290');
//$games = $requestSender->getGameDetail('1517290');
//$games = $requestSender->getGameSearch('Battlefield 2042');

var_dump($games);

//$steamBot = new \App\Bot\SteamBot();
//
//$steamBot->run();