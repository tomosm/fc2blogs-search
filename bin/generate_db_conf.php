#!/usr/bin/env php
<?php

//
// api/1/conf/database.php を作成
//


if ($argc !== 5) {
    $FILE = basename(__FILE__);
    print <<<EOT
Usage:
     {$FILE} host dbname user password

EOT;
} else {
    print '[RUN]     ' . basename(__FILE__) . "\n";
    $host = $argv[1];
    $dbName = $argv[2];
    $user = $argv[3];
    $password = $argv[4];
    print "{$host} {$dbName} {$user} ***\n";

    require_once __DIR__ . '/../api/1/conf/settings.php';
    require_once __DIR__ . '/../api/1/lib/CryptUtils.php';

    $dns = \EXAM0098\Lib\CryptUtils::encrypt("mysql:host=${host};dbname=${dbName}", SALT);
    $user = \EXAM0098\Lib\CryptUtils::encrypt($user, SALT);
    $password = \EXAM0098\Lib\CryptUtils::encrypt($password, SALT);

    $templateFilePath = __DIR__ . '/../api/1/conf/database.php.tmp';
    $template = file_get_contents($templateFilePath);
    $template = str_replace('__DNS__', $dns, $template);
    $template = str_replace('__USER__', $user, $template);
    $template = str_replace('__PW__', $password, $template);
    file_put_contents(str_replace('.tmp', '', $templateFilePath), $template);

    print '[SUCCESS] ' . basename(__FILE__) . "\n";

}
