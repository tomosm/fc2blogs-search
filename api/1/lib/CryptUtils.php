<?php
namespace EXAM0098\Lib;

/**
 * 暗号化ユーティリティクラス。
 * @package EXAM0098\Lib
 */
class CryptUtils
{

    private function __construct()
    {
        // nop.
    }

    /**
     * 暗号化する。
     *
     * @param string|int $data データ
     * @param string|int $salt ソルト
     * @return string 暗号化文字列
     */
    public static function encrypt($data, $salt)
    {
        return str_rot13(base64_encode($salt . $data));
    }

    /**
     * 非暗号化する。
     *
     * @param string $data 暗号化文字列
     * @param string $salt ソルト
     * @return string 非暗号化文字列
     */
    public static function decrypt($data, $salt)
    {
        return str_replace($salt, '', base64_decode(str_rot13($data)));
    }
}

