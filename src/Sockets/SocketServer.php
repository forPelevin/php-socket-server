<?php

namespace Gukasov\Sockets;

use Gukasov\Handlers\MessageHandlerInterface;

class SocketServer
{
    /**
     * The master socket for listening connections.
     *
     * @var Socket
     */
    protected $socket;

    /**
     * The array of clients connected to the server.
     *
     * @var array
     */
    protected $clients;

    /**
     * The message that sends to the fresh connected client.
     *
     * @var string
     */
    protected $greetings = "\nHi! Welcome to the PHP Socket Server. To quit, type 'quit'\n";

    /**
     * The handler of the incoming messages.
     *
     * @var MessageHandlerInterface
     */
    protected $messageHandler;

    /**
     * SocketServer constructor.
     *
     * @param $address
     * @param $port
     */
    public function __construct($address, $port)
    {
        $this->socket = new Socket($address, $port);

        return $this;
    }

    /**
     * Sets the greeting message.
     *
     * @param string $message
     *
     * @return $this
     */
    public function greetings(string $message): self
    {
        $this->greetings = $message;

        return $this;
    }

    /**
     * Starts the server.
     */
    public function run(): void
    {
        // If the property was not assigned
        // then assign it default value
        $this->checkRequired();

        // Create master socket.
        $this->socket->create()->bind()->listen();

        $this->clients = [$this->socket->get()];

        do {
            $handling = $this->handling();
        } while ($handling);

        $this->socket->close();
    }

    /**
     * Check for required attributes.
     * If something isn't assigned then set it default value.
     */
    protected function checkRequired(): void
    {
        if (!$this->messageHandler) {
            $this->messageHandler = function ($item) {
                return $item;
            };
        }
    }

    /**
     * The server is working as long as this returns 'true'.
     *
     * @return bool
     */
    protected function handling(): bool
    {
        // Create a copy, so $clients doesn't get modified by socket_select()
        $readSockets = $this->clients;

        // Get a list of all the clients that have data to be read from.
        // If there are no clients with data, go to next iteration.
        $write = null;
        $except = null;

        if (socket_select($readSockets, $write, $except, 0) < 1) {
            return true;
        }

        // Check if there is a client trying to connect
        if (in_array($this->socket->get(), $readSockets)) {
            $readSockets = $this->handleConnections($readSockets);
        }

        // Loop through all the clients and handle their messages
        foreach ($readSockets as $key => $client) {
            $this->handleMessages($client, $key);
        }

        return true;
    }

    /**
     * Handle all connections to the server
     *
     * @param $readSockets
     *
     * @return array
     */
    protected function handleConnections($readSockets): array
    {
        // Accept the client, and add him to the $clients array
        $this->clients[] = $connection = $this->socket->accept();

        // Greet new connection
        $this->socket->respond($connection, $this->greetings);

        // Remove the listening socket from the clients-with-data array
        $key = array_search($this->socket->get(), $readSockets);
        unset($readSockets[$key]);

        return $readSockets;
    }

    /**
     * Handle all messages of the connected user
     *
     * @param $client
     * @param $key
     */
    protected function handleMessages($client, $key): void
    {
        // Read message from client
        $message = $this->socket->read($client);

        // Check message for STOP signals
        if ($message == 'quit' || $message === false) {
            // Remove client from clients array
            unset($this->clients[$key]);

            // Close client's socket
            $this->socket->close($client);

            return;
        }

        // If there is no message from client then continue
        if (!$message) {
            return;
        }

        // Handle client's message with $this->messageHandler function
        $response = $this->messageHandler->handle($message);

        // Respond to client
        $this->socket->respond($client, $response);
    }

    /**
     * @param MessageHandlerInterface $messageHandler
     *
     * @return SocketServer
     */
    public function setMessageHandler(MessageHandlerInterface $messageHandler): SocketServer
    {
        $this->messageHandler = $messageHandler;

        return $this;
}
}