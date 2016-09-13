<?php

namespace EXAM0098\Test\Lib;

use EXAM0098\Lib\ClassLoader;
use EXAM0098\Testlib\BaseTestCase;
use EXAM0098\Testlib\TestHelper;

class ClassLoaderTest extends BaseTestCase
{
    private $testee = null;

    protected function setUp()
    {
        parent::setUp();
        $this->testee = new ClassLoader();
    }

    public function testRegisterDir()
    {
        // null
        assertThat(TestHelper::getProperty($this->testee, "dirs"), self::isNull());
        $this->testee->registerDir(__DIR__);

        // data
        assertThat(TestHelper::getProperty($this->testee, "dirs"), is(array(__DIR__)));

    }

    public function testGetLoadClassPath()
    {
        assertThat(TestHelper::invokeMethod($this->testee, "getLoadClassPath", array(null)), is(""));
        assertThat(TestHelper::invokeMethod($this->testee, "getLoadClassPath", array("EXAM0098\\Testlib\\TestHelper")), is("testlib/TestHelper"));
    }

    public function testLoadClass()
    {
        TestHelper::invokeMethod($this->testee, "loadClass", array(null));

        $this->testee->registerDir(__DIR__);
        TestHelper::invokeMethod($this->testee, "loadClass", array(null));
        TestHelper::invokeMethod($this->testee, "loadClass", array("EXAM0098\\Testlib\\TestHelper"));
    }

}

