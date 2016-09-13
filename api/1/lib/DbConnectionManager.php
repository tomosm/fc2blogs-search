<?php
namespace EXAM0098\Lib;

/**
 * DB コネクションマネージャークラス。
 *
 * @package EXAM0098\Lib
 */
class DbConnectionManager
{
    private static $instance = null;

    private function __construct()
    {
        // nop.
    }

    /**
     * 当クラスの唯一のインスタンスを返す。
     *
     * @return DbConnectionManager 唯一のインスタンス
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DbConnectionManager();
        }
        return self::$instance;
    }

    /**
     * コネクションのインスタンスを返す。
     *
     * @param array $params 接続時のパラメータ
     * @return \PDO コネクション
     */
    public function connect(array $params)
    {
        return DbConnection::getInstance($params)->getConnection();
    }

}