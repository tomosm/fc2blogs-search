<?php
namespace EXAM0098\Lib;

/**
 * Array ユーティリティクラス。
 * @package EXAM0098\Lib
 */
class ArrayUtils
{

    private function __construct()
    {
        // nop.
    }

    /**
     * 引数全てが null 以外の場合のみ 連想配列に追加する。
     *
     * @param array $map 連想配列
     * @param string|int $key キー
     * @param mixed $value 値
     */
    public static function setIfValueNotNull(array &$map, $key, $value)
    {
        if (!is_null($map) && !is_null($key) && !is_null($value)) {
            $map[$key] = $value;
        }
    }

}

