<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require __DIR__.'/../vendor/autoload.php';

class SocketSRV implements MessageComponentInterface{
    protected $clients;
    private $events = [];
    function __construct(){
        $this->clients = new \SplObjectStorage;
    }
    /**
     * Lets you add a event listner
     * @param string $event - Events => "connection", "data", "close"
     * @param function (React\Socket\Connection $conn) $callback - on('connection'or'close', ($conn)) on('data', ($from, $msg))
     */
    function on($event, $callback){
        if(is_callable($callback)) $this->events[$event] = $callback;
    }

    function onOpen(ConnectionInterface $conn){
        //add the new connections to the clients
        $this->clients->attach($conn);

        //run the on message event
        if(!empty($this->events['connection'])) $this->events['connection']($conn);
    }
    function onMessage(ConnectionInterface $from, $msg){
        //Send any incoming messages to all connected clients (except sender)
        var_dump($msg);
        foreach ($this->clients as $client) {
            if ($from != $client) {
                $client->send(($msg));
            }
        }
        if(!empty($this->events['data'])) $this->events['data']($from, $msg);
    }
    function onClose(ConnectionInterface $conn){
        //remove this client form the active clients
        $this->clients->detach($conn);
        if(!empty($this->events['close'])) $this->events['close']($conn);
    }
    /**
     * Broadcasts a message to all the clients
     */
    function broadcast($message){
        foreach($this->clients as $client){
            $client->send($message);
        }
    }
    /**
     * Sends a message to 1 client
     * @param ConnectionInterface $to - The client to send the message to
     */
    function send($to, $message){
        $to->send($message);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

$self = new SocketSRV; //create the class

#region set the events
$self->on('data', function($from, $msg){
    $json = json_decode($msg);
    //$json has => [id] [msg] [user-id]
    //add the json info into the database
});
#endregion

//start the server
$app = new Ratchet\App('localhost', 8080);
$app->route('/chat', $self, array('*'));
$app->run();
