# PHP Socket Server.
The library represents socket server written on PHP. The server can handle multiple connections at the time.

## Getting Started
1) Download the project from github to your host machine.
2) Go to the folder with project

## Prerequisites
For the successful using you must have:
```
php >= 7.1.0
telnet utility (for testing)
```
## Installing
You have to perform these steps to get a development env running. Call the command in the console. 
```
cd to/the/project/folder
composer install
```

## Basic usage
In the folder with the project call the command to start the server.
```
./bin/server -p={port}
```

Instead of {port} specify the port number by which the server will listen for connections.<br>
After that you can connect to the server in another console by command
 ```
 telnet localhost {port}
 ```
If you need your own message handler just make class that implements the "MessageHandlerInterface" with your logic in "handle" method and specify that in the Gukasov\App->__construct() method. 
```php
 /**
  * App constructor. Specify here the message handler.
  */
 public function __construct()
 {
     $this->messageHandler = new MyOwnMessageHandler();
 }
 ```

## License
This project is licensed under the MIT License.