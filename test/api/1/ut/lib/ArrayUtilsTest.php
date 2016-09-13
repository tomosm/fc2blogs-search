<?php

namespace EXAM0098\Test\Lib;

use EXAM0098\Lib\ArrayUtils;
use EXAM0098\Testlib\BaseTestCase;

class ArrayUtilsTest extends BaseTestCase
{

    public function testSetIfValueNotNull()
    {
        $map = array();
        ArrayUtils::setIfValueNotNull($map, null, null);
        assertThat($map, emptyArray());

        ArrayUtils::setIfValueNotNull($map, "key", null);
        assertThat($map, emptyArray());

        ArrayUtils::setIfValueNotNull($map, null, "value");
        assertThat($map, emptyArray());

        ArrayUtils::setIfValueNotNull($map, "key", "value");
        assertThat($map, is(array("key" => "value")));
    }

}

