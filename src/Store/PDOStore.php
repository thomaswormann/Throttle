<?php

namespace ThomasWormann\Throttle\Store;

use \PDO;

class PDOStore implements StoreInterface
{
    private $table = 'throttle';

    private $dbh;


    public function __construct(PDO $dbh, $configuration = false)
    {
        $this->dbh = $dbh;
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if(isset($configuration['table'])){
            $this->table = $configuration['table'];
        }
    }


    public function get($key)
    {
        if($result = $this->dbh->query("SELECT * FROM ".$this->table." WHERE request_key='".$key."'")){
            if($result->rowCount()){
                return json_decode($result->fetch(PDO::FETCH_ASSOC)['request_data'],true);
            } else {
                return false;
            }
        } else {
            echo $this->dbh->errorInfo;
        }

        return false;
    }


    public function count($key)
    {
        if($requestData = $this->get($key)){
            if($requestData && isset($requestData[date("Y-m-d")])){
                return count($requestData[date("Y-m-d")]);
            } else {
                return 0;
            }
        }
    }


    public function set($key)
    {
        if($requestData = $this->get($key)){
            $requestData[date("Y-m-d")][] = time();
            $this->dbh->query("UPDATE ".$this->table." SET request_data='".json_encode($requestData)."' WHERE request_key='".$key."'");
        } else {
            $requestData = [];
            $requestData[date("Y-m-d")][] = time();
            $this->dbh->query("INSERT INTO ".$this->table." (request_key,request_data) VALUES ('".$key."','".json_encode($requestData)."')");
        }
        return true;
    }

}

