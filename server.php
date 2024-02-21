<?php
// server.php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Socketss\webSocketController;

require  './vendor/autoload.php';


$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new webSocketController()
        )
    ),
    3222
);

$server->run();
?>