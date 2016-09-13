<?php
namespace EXAM0098\Lib;

/**
 * String ユーティリティクラス。
 * @package EXAM0098\Lib
 */
class StringUtils
{

    private function __construct()
    {
        // nop.
    }

    /**
     * スネークケースに変換して返す。
     * 引数が null の場合は null を返す。
     *
     * @param string $str 文字列
     * @return string スネークケースに変換後の文字列
     */
    public static function snakecase($str)
    {
        if (is_null($str)) {
            return null;
        }
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_\0', $str)), '_');
    }
}

