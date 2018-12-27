<?php
/**
 * Created by PhpStorm.
 * User: Skylar
 * Date: 8/13/2018
 * Time: 8:49 AM
 */

namespace App;
USE PDO;

class MessageController
{
    private $db;
    private $log;


    public function __construct(\PDO $db, \Monolog\Logger $log) {
        $this->db = $db;
        $this->log = $log;

    }

    public function PostMessage($Username, $Message, $IP) {
//        $dsn = "mysql:host=67.205.183.11;dbname=feed_skyolsen";
//        $user = 'skyolsen';
//        $pass = 'changed';
//        $pdo = new \PDO($dsn, $user, $pass);
        $logMsg = "INSERT MESSAGE - Username: " .$Username . " Message: ". $Message . " IP: ".$IP;
        $this->log->info($logMsg);
        $sql = "INSERT INTO ChatHistory (Username, Message, IP) VALUES(?,?,?)";
        $stmt =  $this->db->prepare($sql); //$pdo->prepare($sql);
        $stmt->execute([$Username, $Message, $IP]);

        $last_id = $this->db->lastInsertId();//$pdo->lastInsertID();

        $this->log->info("PostMessage SELECT Last Inserted - LastID: " + $last_id);
        $sql = "SELECT * FROM ChatHistory WHERE idChatHistory =? ";
        $stmt = $this->db->prepare($sql);//$pdo->prepare($sql);
        $stmt->execute([$last_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($data);

    }

    public function getFeed($lastPostTime){

    if($lastPostTime == null || $lastPostTime == 'null'){
        $this->log->info("getFeed SELECT * FROM ChatHistory - New Page Load ");
        $sql = "SELECT * FROM ChatHistory ORDER BY PostTime ASC ";
        $stmt = $this->db->prepare($sql);//$pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($data);
    }
        $this->log->info("getFeed SELECT * FROM ChatHistory WHERE PostTime is new - lastPostTime: ".$lastPostTime);
    $sql = "SELECT * FROM ChatHistory WHERE PostTime > ? ORDER BY PostTime ASC ";
    $stmt = $this->db->prepare($sql);//$pdo->prepare($sql);
    $stmt->execute([$lastPostTime]); //$username, $message, $ip
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return json_encode($data);

    }
}

