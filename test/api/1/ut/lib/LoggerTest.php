<?php

namespace EXAM0098\Test\Lib;

use EXAM0098\Lib\Logger;
use EXAM0098\Testlib\BaseTestCase;
use EXAM0098\Testlib\SuppressLogErrors;
use EXAM0098\Testlib\TestHelper;

class LoggerTest extends BaseTestCase
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

    public function testDefault()
    {
        $testee = Logger::getInstance();

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->fatal("__test__");
        });
        assertThat($actual, isNonEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->error("__test__");
        });
        assertThat($actual, isNonEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->info("__test__");
        });
        assertThat($actual, isEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->debug("__test__");
        });
        assertThat($actual, isEmptyString());

    }

    public function testFatal()
    {
        $testee = Logger::getInstance();
        $testee->setLogLevel("FATAL");

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->fatal("__test__");
        });
        assertThat($actual, isNonEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->error("__test__");
        });

        assertThat($actual, isEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->info("__test__");
        });
        assertThat($actual, isEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->debug("__test__");
        });
        assertThat($actual, isEmptyString());

    }

    public function testError()
    {
        $testee = Logger::getInstance();
        $testee->setLogLevel("ERROR");

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->fatal("__test__");
        });
        assertThat($actual, isNonEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->error("__test__");
        });
        assertThat($actual, isNonEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->info("__test__");
        });
        assertThat($actual, isEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->debug("__test__");
        });
        assertThat($actual, isEmptyString());

    }

    public function testInfo()
    {
        $testee = Logger::getInstance();
        $testee->setLogLevel("INFO");

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->fatal("__test__");
        });
        assertThat($actual, isNonEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->error("__test__");
        });
        assertThat($actual, isNonEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->info("__test__");
        });
        assertThat($actual, isNonEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->debug("__test__");
        });
        assertThat($actual, isEmptyString());

    }

    public function testDebug()
    {
        $testee = Logger::getInstance();
        $testee->setLogLevel("DEBUG");

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->fatal("__test__");
        });
        assertThat($actual, isNonEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->error("__test__");
        });
        assertThat($actual, isNonEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->info("__test__");
        });
        assertThat($actual, isNonEmptyString());

        $actual = TestHelper::getStandardOutput(function () use ($testee) {
            $testee->debug("__test__");
        });
        assertThat($actual, isNonEmptyString());

    }

}

