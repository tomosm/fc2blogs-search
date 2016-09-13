<?php

namespace EXAM0098\Test\Lib;

use EXAM0098\Lib\StringUtils;
use EXAM0098\Testlib\BaseTestCase;

class StringUtilsTest extends BaseTestCase
{

    public function testSnakecase()
    {
        $actual = StringUtils::snakecase(null);
        assertThat($actual, nullValue());

        $actual = StringUtils::snakecase("");
        assertThat($actual, notNullValue());
        assertThat($actual, isEmptyString());

        $actual = StringUtils::snakecase("StringUtilsTest");
        assertThat($actual, is("string_utils_test"));
    }

}

