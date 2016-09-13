<?php

namespace EXAM0098\Test\Lib;

use EXAM0098\Lib\DateUtils;
use EXAM0098\Testlib\BaseTestCase;

class DateUtilsTest extends BaseTestCase
{

    public function testFormatHyphenYmdHis()
    {
        $expected = new \DateTime("2011-01-01T11:11:11+0900");
        $actual = DateUtils::formatHyphenYmdHis("2011-01-01T11:11:11+0900");
        assertThat($actual, is($expected->format('Y-m-d H:i:s')));

        $actual = DateUtils::formatHyphenYmdHis("2011-01-01T11:11:11+0900", "UTC");
        assertThat($actual, is($expected->format('Y-m-d H:i:s')));
    }

}

