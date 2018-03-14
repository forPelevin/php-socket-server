<?php

namespace Gukasov\Sockets;

class Socket
{
    /**
     * @var resource
     */
    protected $data;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var int
     */
    protected $port;

    /**
     * Socket constructor.
     *
     * @param string $address
     * @param int $port
     */
    public function __construct(string $address, int $port)
    {
        $this->address = $address;
        $this->port = $port;
    }

    /**
     * Create a socket (endpoint for communication).
     *
     * @return $this
     */
    public function create()
    {
        $this->data = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        return $this;
    }

    /**
     * Accepts a connection on a socket.
     *
     * @return resource
     */
    public function accept()
    {
        return socket_accept($this->data);
    }

    /**
     * Binds a name to a socket.
     *
     * @param $address
     * @param $port
     *
     * @return $this
     */
    public function bind()
    {
        socket_bind($this->data, $this->address, $this->port);

        return $this;
    }

    /**
     * Listens for a connection on a socket.
     *
     * @return $this
     */
    public function listen()
    {
        socket_listen($this->data, 5);

        return $this;
    }

    /**
     * Closes a socket resource.
     *
     * @param null $socket
     */
    public function close($socket = null)
    {
        if ($socket) {
            return socket_close($socket);
        }

        return socket_close($this->data);
    }

    /**
     * Get the master socket.
     *
     * @return bool|resource
     */
    public function get()
    {
        return $this->data;
    }

    /**
     * Write the message to a socket.
     *
     * @param string $message
     */
    public function respond($socket, string $message)
    {
        socket_write($socket, $message, mb_strlen($message));
    }

    /**
     * Reads the string from a socket.
     *
     * @return string
     */
    public function read($socket)
    {
        return trim(socket_read($socket, 2048, PHP_BINARY_READ));
    }
}