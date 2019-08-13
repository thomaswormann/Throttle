<?php

require 'vendor/autoload.php';

use \ThomasWormann\Throttle\Store\PDOStore;


class PDOStoreTest extends \PHPUnit_Framework_TestCase
{
    protected $PDOStore;
    protected $dbh;


    public function setUp()
    {
        $this->dbh = new PDO('sqlite:testdb.sqlite');
        $this->dbh->query('CREATE TABLE throttle(key TEXT, request_date TEXT, requests INT)');
        $this->PDOStore = new PDOStore($this->dbh);
    }


    public function tearDown()
    {
        unlink('testdb.sqlite');
    }


    public function testIfPDOStoreIsInitialized()
    {
        $this->assertTrue($this->PDOStore instanceof PDOStore);
    }


    public function testIfPDOStoreCanBeSet()
    {
        $this->assertTrue($this->PDOStore->set('test@test.de'));
    }


    public function testIfPDOStoreCanBeGet()
    {
        $this->assertTrue($this->PDOStore->set('test@test.de'));
        $this->assertArrayHasKey('key',$this->PDOStore->get('test@test.de'));
    }

}

