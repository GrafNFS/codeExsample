<?php

namespace app\daemons;

use app\models\User;
use app\models\Message;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use yii\helpers\Console;

class MyChat implements MessageComponentInterface {

    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        Console::stdout("Connection {$conn->resourceId} Open\n");
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $request = json_decode($msg);
        
        //$msg_info = Message::saveMessage($request->FromUserId, $request->ToUserId, $request->Message);
        $newMessage = new Message();
        $newMessage->msg_from = $request->FromUserId;
        $newMessage->msg_to = $request->ToUserId;
        $newMessage->text_msg = $request->Message;
        $newMessage->date_create = date("Y-m-d H:i:s");
        if ($newMessage->save()) {
            $sendMsg = array(
                "MsgFrom" => false,
                "MsgFromId" => $request->FromUserId,
                "MsgToId" => $request->ToUserId,
                "Msg" => $request->Message,
                "DateMsg" => $newMessage->date_create
            );
            foreach ($this->clients as $client) {
                if ($from !== $client) {
                    $client->send(json_encode($sendMsg));
                }
            }
        }
        else {
            Console::stdout("Errors {$newMessage->errors}\n");
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        Console::stdout("Connection {$conn->resourceId} Close\n");
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        Console::stdout("Connection Error $e\n");
        $conn->close();
    }

    public function findConnection(ConnectionInterface $conn) {
        foreach ($this->clients as $client) {
            if ($client->connection === $conn) {
                return $client;
            }
        }
        return null;
    }

}
