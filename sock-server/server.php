<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Functions;
use App\Http\Controllers\ChatController;

require __DIR__ . '/../vendor/autoload.php';

class SocketSRV implements MessageComponentInterface
{
    protected $clients;
    private $events = [];

    function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    /**
     * Lets you add a event listner
     * @param string $event - Events => "connection", "data", "close"
     * @param function (React\Socket\Connection $conn) $callback - on('connection'or'close', ($conn)) on('data', ($from, $msg))
     */
    function on($event, $callback)
    {
        if (is_callable($callback)) $this->events[$event] = $callback;
    }

    function onOpen(ConnectionInterface $conn)
    {
        //add the new connections to the clients
        $this->clients->attach($conn);

        //run the on message event
        if (!empty($this->events['connection'])) $this->events['connection']($conn);
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
        //Send any incoming messages to all connected clients (except sender)
        $json_msg = json_decode($msg);
        $json_msg->time_stamp = new DateTime;

        $json_msg->msg = Functions::sanitize($json_msg->msg);

        var_dump($json_msg);
        foreach ($this->clients as $client) {
            if ($from != $client) {
                $client->send(json_encode($json_msg));
            }
        }
        if (!empty($this->events['data'])) $this->events['data']($from, $json_msg);
    }

    function onClose(ConnectionInterface $conn)
    {
        //remove this client form the active clients
        $this->clients->detach($conn);
        if (!empty($this->events['close'])) $this->events['close']($conn);
    }

    /**
     * Broadcasts a message to all the clients
     */
    function broadcast($message)
    {
        foreach ($this->clients as $client) {
            $client->send($message);
        }
    }

    /**
     * Sends a message to 1 client
     * @param ConnectionInterface $to - The client to send the message to
     */
    function send($to, $message)
    {
        $to->send($message);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    function run()
    {
        $app = new Ratchet\App(env('SOCKET_HOST', 'localhost'), env('SOCKET_PORT', 8080));
        $app->route('/chat', $this, array('*'));
        $app->run();
    }
}

$self = new SocketSRV; //create the class

//set events
$self->on('data', function ($from, $msg) {
    //$msg has => [id] [msg] [user-id]
    //add the json info into the database (via the Chat Controller)
//    $chatController = new ChatController();
//    $chatController->sendMessage($msg);
//    ChatController::sendMessage($msg);
    sendViaPost($msg);
});
//start the server
$self->run();

function sendViaPost($data)
{
    /*
     * chatid = id
     * message = msg
     * name = username
     * userid = user-id=>???
     */

    $data = (array)$data;
    $newData = [];
    $newData['chatid'] = $data['id'];
    $newData['message'] = $data['msg'];
    $newData['userid'] = $data['user-id'];

//    error_log('sending data via post');

    $url = env('APP_URL', 'http://localhost') . ':8000/sendMessage';
    $fields_string = "";
    $fields = $newData;

    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . urlencode($value) . '&';
    }
    $fields_string = rtrim($fields_string, '&');

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

    $result = curl_exec($ch);
    curl_close($ch);

}

//function loginWeb()
//{
//
//    $ch = curl_init(env('APP_URL', 'http://localhost') . ':8000/login');
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
//    $response = curl_exec($ch);
//
//    $dom = new DOMDocument;
//    $dom->loadHTML($response);
//    $tags = $dom->getElementsByTagName('input');
//    for ($i = 0; $i < $tags->length; $i++) {
//        $grab = $tags->item($i);
//        if ($grab->getAttribute('name') === '_token') {
//            $token = $grab->getAttribute('value');
//        }
//    }
//
//    $cred = array(
//        "email" => "socket@server.5",
//        "password" => "password",
//        "Login" => "Login",
//        "_token" => $token,
//    );
//
//    curl_setopt($ch, CURLOPT_URL, env('APP_URL', 'http://localhost') . ':8000/login');
//    curl_setopt($ch, CURLOPT_POST, true);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $cred);
//    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
//    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//    $response = curl_exec($ch);
//}
