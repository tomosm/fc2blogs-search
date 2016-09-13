<?php
namespace EXAM0098\Lib;

/**
 * Date ユーティリティクラス。
 * @package EXAM0098\Lib
 */
class DateUtils
{

    private function __construct()
    {
        // nop.
    }

    /**
     * Y-m-d H:i:s にフォーマットされた日時文字列を返す。
     *
     * @param string $datetime 日付
     * @param \DateTimeZone|null $timezone タイムゾーン
     * @return string Y-m-d H:i:s 日時文字列
     */
    public static function formatHyphenYmdHis($datetime = "now", $timezone = null)
    {
        if (!is_null($timezone)) {
            $timezone = new \DateTimeZone($timezone);
        }
        $t = new \DateTime($datetime, $timezone);
        return $t->format('Y-m-d H:i:s');
    }
}

