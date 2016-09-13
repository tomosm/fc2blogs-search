<?php

namespace EXAM0098\Test\Lib;

use EXAM0098\Lib\ApplicationException;
use EXAM0098\Lib\HttpResponseTrait;
use EXAM0098\Testlib\BaseTestCase;
use EXAM0098\Testlib\SuppressLogErrors;
use EXAM0098\Testlib\TestHelper;

/**
 * Class HttpResponseTraitTest
 * @runTestsInSeparateProcesses
 * @package EXAM0098\Test\Lib
 */
class HttpResponseTraitTest extends BaseTestCase
{
    use HttpResponseTrait, SuppressLogErrors;

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

    public function testSuccess()
    {
        $self = $this;
        $actual = json_decode(TestHelper::getStandardOutput(function () use ($self) {
            $self->success(array("key" => "success"));
        }), true);

        assertThat(http_response_code(), is(200));
        assertThat($actual["key"], is("success"));

        $actual = json_decode(TestHelper::getStandardOutput(function () use ($self) {
            $self->success(array(), 201);
        }), true);

        assertThat(http_response_code(), is(201));
        assertThat($actual, self::isNull());
    }

    public function testError()
    {
        $self = $this;
        $actual = json_decode(TestHelper::getStandardOutput(function () use ($self) {
            $self->error(new ApplicationException("test"));
        }), true);

        assertThat(http_response_code(), is(500));
        assertThat($actual["errorMessage"], is("test"));

        $actual = json_decode(TestHelper::getStandardOutput(function () use ($self) {
            $self->error(null);
        }), true);

        assertThat(http_response_code(), is(500));
        assertThat($actual, self::isNull());
    }
}

