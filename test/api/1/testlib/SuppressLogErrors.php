<?php


namespace EXAM0098\Testlib;

/**
 * log_errors の出力を抑制するトレイト。
 * @package EXAM0098\Testlib
 */
trait SuppressLogErrors
{
    private static $logErrors = null;

    private static function suppress()
    {
        // stop writing error into a php log file
        self::$logErrors = ini_get("log_errors");
        ini_set("log_errors", 0);
    }

    private static function express()
    {
        ini_set("log_errors", self::$logErrors);
    }
}

