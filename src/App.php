<?php

namespace Gukasov;

use Gukasov\Handlers\BracketsSequenceMessageHandler;
use Gukasov\Handlers\MessageHandlerInterface;
use Gukasov\Sockets\SocketServer;

class App
{
    /**
     * @var MessageHandlerInterface
     */
    protected $messageHandler;

    /**
     * App constructor. Specify here the message handler.
     */
    public function __construct()
    {
        $this->messageHandler = new BracketsSequenceMessageHandler();
    }

    /**
     * Starts up the application.
     */
    public function run()
    {
        $port = (int) CommandLine::getInput('p');

        if (!$port) {
            die('You must specify a valid port for the socket server. For example: "-p=1024"');
        }

        $server = new SocketServer('127.0.0.1', $port);

        // Set greetings for the new connection
        $server->greetings("\nHi! Just type your brackets sequence and you will see the result\n");

        // Set handler of the incoming messages
        $server->setMessageHandler($this->messageHandler);

        // Start up the server
        $server->run();
    }
}