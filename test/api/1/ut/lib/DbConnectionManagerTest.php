<?php

namespace EXAM0098\Test\Lib;

use EXAM0098\Lib\DbConnection;
use EXAM0098\Lib\DbConnectionManager;
use EXAM0098\Testlib\BaseTestCase;
use EXAM0098\Testlib\IsNotSame;
use EXAM0098\Testlib\TestHelper;

class DbConnectionManagerTest extends BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
        TestHelper::setProperty(DbConnectionManager::class, "instance", null);
        TestHelper::setProperty(DbConnection::class, "instance", null);
    }


    public function testGetInstance()
    {
        // same instance
        $instance = DbConnectionManager::getInstance();
        assertThat($instance, notNullValue());
        assertThat(DbConnectionManager::getInstance(), sameInstance($instance));

        TestHelper::setProperty(DbConnectionManager::class, "instance", null);
        assertThat(DbConnectionManager::getInstance(), IsNotSame::sameNotInstance($instance));
    }

    public function testConnect()
    {
        // mock
        $mockDbConnection = \Phake::mock(DbConnection::class);
        TestHelper::setProperty(DbConnection::class, "instance", $mockDbConnection);
        \Phake::when($mockDbConnection)->getConnection()->thenReturn(\Phake::anyParameters());

        $actual = DbConnectionManager::getInstance()->connect(array());

        assertThat($actual, is(\Phake::anyParameters()));
        \Phake::verify($mockDbConnection)->getConnection();
    }

}

