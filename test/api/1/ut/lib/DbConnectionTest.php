<?php

namespace EXAM0098\Test\Lib;

use EXAM0098\Lib\ApplicationException;
use EXAM0098\Lib\DbConnection;
use EXAM0098\Testlib\BaseTestCase;
use EXAM0098\Testlib\IsNotSame;
use EXAM0098\Testlib\SuppressLogErrors;
use EXAM0098\Testlib\TestHelper;

class DbConnectionTest extends BaseTestCase
{
    use SuppressLogErrors;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::suppress();
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        self::express();
    }

    protected function setUp()
    {
        parent::setUp();
        TestHelper::setProperty(DbConnection::class, "instance", null);
    }

    public function testGetInstance()
    {
        // same instance
        $instance = DbConnection::getInstance(array());
        assertThat($instance, notNullValue());
        assertThat(DbConnection::getInstance(array()), sameInstance($instance));

        TestHelper::setProperty(DbConnection::class, "instance", null);
        assertThat(DbConnection::getInstance(array()), IsNotSame::sameNotInstance($instance));
    }

    public function testGetConnection()
    {
        // success
        $instance = new SimpleDbConnection();
        $connection = $instance->getConnection();
        assertThat($connection, notNullValue());
        assertThat($instance->getConnection(), sameInstance($connection));

        // exception
        $instance = new ExceptionDbConnection();
        $this->setExpectedException(ApplicationException::class);
        $instance->getConnection();
    }

}

class SimpleDbConnection extends DbConnection
{

    function __construct()
    {
        // nop.
    }

    protected function generateConnection($params)
    {
        return "connection";
    }

}

class ExceptionDbConnection extends DbConnection
{

    function __construct()
    {
        // nop.
    }

    protected function generateConnection($params)
    {
        throw new \PDOException("test");
    }

}
