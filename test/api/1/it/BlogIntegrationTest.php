<?php

namespace EXAM0098\Test\Lib;

use EXAM0098\Controller\BlogController;
use EXAM0098\Lib\DbConnection;
use EXAM0098\Testlib\BaseTestCase;
use EXAM0098\Testlib\SuppressLogErrors;
use EXAM0098\Testlib\TestHelper;
use PDO;

/**
 * Class BlogTest
 * @runTestsInSeparateProcesses
 * @package EXAM0098\Test\Lib
 */
class BlogIntegrationTest extends BaseTestCase
{
    use SuppressLogErrors;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        TestHelper::setProperty(DbConnection::class, "instance", null);
        self::express();
    }

    public function testSearchData()
    {
        $controller = new BlogController();

        // empty
        TestHelper::setProperty(DbConnection::class, "instance", new DbConnectionStub());
        $actual = json_decode(TestHelper::getStandardOutput(function () use ($controller) {
            $controller->search();
        }), true);

        assertThat(http_response_code(), is(200));
        assertThat($actual["limit"], is(30));
        assertThat($actual["offset"], is(0));

        // data
        $expected = array(array("link" => "http://", "title" => "title", "description" => "desc", "postedAt" => "2000-01-01 00:00:00"));
        TestHelper::setProperty(DbConnection::class, "instance", new DbConnectionStub($expected));
        $actual = json_decode(TestHelper::getStandardOutput(function () use ($controller) {
            $controller->search();
        }), true);

        assertThat(http_response_code(), is(200));
        assertThat($actual["limit"], is(30));
        assertThat($actual["offset"], is(0));
        assertThat($actual["data"], arrayValue());
        assertThat(count($actual["data"]), is(1));
        foreach ($expected as $i => $expectedArray) {
            assertThat($actual["data"][$i]["link"], is($expected[$i]["link"]));
            assertThat($actual["data"][$i]["title"], is($expected[$i]["title"]));
            assertThat($actual["data"][$i]["description"], is($expected[$i]["description"]));
            assertThat($actual["data"][$i]["postedAt"], is($expected[$i]["postedAt"]));
        }
    }

    public function testFailedToDbConnect()
    {
        self::suppress();

        $controller = new BlogController();

        TestHelper::setProperty(DbConnection::class, "instance", new DbConnectionErrorStub());
        $actual = json_decode(TestHelper::getStandardOutput(function () use ($controller) {
            $controller->search();
        }), true);

        assertThat(http_response_code(), is(500));
        assertThat($actual["errorCode"], is(10000));
        assertThat($actual["errorMessage"], is("Failed to connect db"));
    }

}

// stub classes

class DbConnectionErrorStub extends DbConnection
{

    function __construct()
    {
        // nop.
    }

    protected function generateConnection($params)
    {
        $connection = new \PDO($params['dns'], $params['user'], $params['password'], $params['options']);
        $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $connection;
    }

}

class DbConnectionStub extends DbConnection
{

    private $fetchData = null;

    function __construct(array $fetchData = array())
    {
        $this->fetchData = $fetchData;
    }

    protected function generateConnection($params)
    {
        return new PDOStub($params['dns'], $params['user'], $params['password'], $params['options'], $this->fetchData);
    }
}

class PDOStub extends \PDO
{
    private $fetchData = null;

    function __construct($dsn, $username, $passwd, $options, array $fetchData = array())
    {
        $this->fetchData = $fetchData;
    }

    public function prepare($statement, $driver_options = array())
    {
        return new PDOStatementStub($statement, $this->fetchData);
    }

}

class PDOStatementStub extends \PDOStatement
{
    private $fetchData = null;
    private $fetchCursor = 0;
    private $statement = null;

    function __construct($statement, array $fetchData = array())
    {
        $this->statement = $statement;
        $this->fetchData = $fetchData;
    }

    public function execute($input_parameters = null)
    {
        return true;
    }

    public function fetch($fetch_style = null, $cursor_orientation = PDO::FETCH_ORI_NEXT, $cursor_offset = 0)
    {
        // count
        if (preg_match("#count#i", $this->statement)) {
            if ($this->fetchCursor++ === 0) {
                return array("TOTAL_COUNT" => count($this->fetchData));
            } else {
                $this->fetchCursor = 0;
                return false;
            }
        } else {
            if (@$this->fetchData[$this->fetchCursor]) {
                return $this->fetchData[$this->fetchCursor++];
            } else {
                $this->fetchCursor = 0;
                return false;
            }
        }
    }

    public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR)
    {
        return true;
    }


}