<?php

require 'vendor/autoload.php';

use \ThomasWormann\Throttle\Store\PDOStore;
use \ThomasWormann\Throttle\Psr7\Throttle;


class ThrottleTest extends \PHPUnit_Framework_TestCase
{
    protected $PDOStore;
    protected $dbh;

    private $middleware;


    public function setUp()
    {
        $this->dbh = new PDO('sqlite:testdb.sqlite');
        $this->dbh->query('CREATE TABLE throttle(key TEXT, request_date TEXT, requests INT)');
        $this->PDOStore = new PDOStore($this->dbh);

        $this->middleware = new Throttle($this->PDOStore, [
            'resetType' => 'daily',
            'maxRequests' => '2'
        ]);
    }


    public function testThrottle()
    {
    }
}



