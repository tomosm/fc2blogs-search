<?php

namespace EXAM0098\Test\Lib;

use EXAM0098\Lib\Fc2BlogRdfParser;
use EXAM0098\Testlib\BaseTestCase;

class Fc2BlogRdfParserTest extends BaseTestCase
{

    private $testee = null;

    protected function setUp()
    {
        parent::setUp();
        $this->testee = new Fc2BlogRdfParser();
    }

    public function testParseFromUrl()
    {
        // empty
        assertThat($this->testee->parseFromUrl(""), is(array()));
        assertThat($this->testee->parseFromUrl(__DIR__ . "/../../textures/fs2blognoentries.rdf"), is(array()));

        // data
        $actual = $this->testee->parseFromUrl(__DIR__ . "/../../textures/fs2blogtestentries.rdf");
        assertThat(count($actual), is(1));
        assertThat($actual[0]["link"], is("http://xxx.xxx/blog-entry-0.html"));
        assertThat($actual[0]["title"], is(" test "));
        assertThat($actual[0]["description"], is(" test desc "));
        assertThat($actual[0]["dc:date"], is("2016-01-01T00:00:00+09:00"));

    }

}
