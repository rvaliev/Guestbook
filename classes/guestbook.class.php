<?php

require_once("classes/dbc.class.php");

class Guestbook
{
    private $handler;
    private $sql;
    private $query;
    private $result;

    private function connectToDB()
    {
        /**
         * Подключение к БД
         */
        $this->handler = new Dbc();
        $this->handler = $this->handler->startConnection();
    }

    public function getAllMessages()
    {
        self::connectToDB();
        $this->sql = "SELECT * FROM gastenboek ORDER BY datum DESC";

        try{
            $this->query = $this->handler->query($this->sql);
            $this->result = $this->query->fetchAll(PDO::FETCH_ASSOC);

            $this->query->closeCursor();
            $this->handler = null;
            return $this->result;
        }
        catch(Exception $e){
            echo "Error: Ошибка с запросом";
            return false;
        }
    }

    public function insertPost($author, $message)
    {
        self::connectToDB();
        $this->sql = "INSERT INTO gastenboek (auteur, datum, boodschap) VALUES (?, now(), ?)";

        try{
            $this->query = $this->handler->prepare($this->sql);
            $this->query->execute(array($author, $message));

            $this->handler = null;
            $this->query->closeCursor();
            return true;
        }
        catch(Exception $e){
            echo "Error: Ошибка с запросом";
            return false;
        }
    }
}