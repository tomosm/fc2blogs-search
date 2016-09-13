<?php
namespace EXAM0098\Testlib;

/**
 * テスト用ヘルパークラス。
 * @package EXAM0098\Testlib
 */
class TestHelper
{
    private function __construct()
    {
        // nop.
    }

    /**
     * 対象オブジェクトのプロパティに値を設定する。
     *
     * @param mixed $object 対象オブジェクト
     * @param string $property プロパティ
     * @param mixed $value 値
     */
    public static function setProperty($object, $property, $value)
    {
        $reflectionProperty = new \ReflectionProperty($object, $property);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
    }

    /**
     * 対象オブジェクトのプロパティの値を取得する。
     *
     * @param mixed $object 対象オブジェクト
     * @param string $property プロパティ
     * @return mixed $value 値
     */
    public static function getProperty($object, $property)
    {
        $reflectionProperty = new \ReflectionProperty($object, $property);
        $reflectionProperty->setAccessible(true);
        return $reflectionProperty->getValue($object);
    }

    /**
     * 対象オブジェクトのメソッドを実行する。
     *
     * @param mixed $object 対象オブジェクト
     * @param string $methodName メソッド
     * @param array|null $invokeArgs メソッド実行時の引数
     * @return mixed|null 実行結果
     */
    public static function invokeMethod($object, $methodName, array $invokeArgs = null)
    {
        $reflectionMethod = new \ReflectionMethod($object, $methodName);
        $reflectionMethod->setAccessible(true);
        return $reflectionMethod->invokeArgs($object, $invokeArgs);
    }

    /**
     * コールバック関数を実行して標準出力からデータを取得する。
     *
     * @param callable $callback コールバック関数
     * @return string 標準出力から取得したデータ
     */
    public static function getStandardOutput(callable $callback)
    {
        ob_start();
        $callback();
        $value = ob_get_contents();
        ob_end_clean();
        return $value;
    }

}

