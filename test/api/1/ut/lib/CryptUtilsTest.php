<?php

namespace EXAM0098\Test\Lib;

use EXAM0098\Lib\CryptUtils;
use EXAM0098\Testlib\BaseTestCase;

class CryptUtilsTest extends BaseTestCase
{

    public function testEncryptDecrypt()
    {
        // encrypt
        $encryptedData = CryptUtils::encrypt("data", "salt");
        assertThat($encryptedData, not("data"));

        // decrypt
        $decryptedData = CryptUtils::decrypt($encryptedData, "salt");
        assertThat($decryptedData, is("data"));

        $decryptedData = CryptUtils::decrypt($encryptedData, "notsalt");
        assertThat($decryptedData, not("data"));
    }

}

