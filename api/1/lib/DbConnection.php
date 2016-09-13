<?php
namespace EXAM0098\Lib;

/**
 * DB コネクションシングルトンクラス。
 *
 * @package EXAM0098\Lib
 */
class DbConnection
{
    private static $instance = null;
    private static $params = null;
    private $connection = null;

    private function __construct()
    {
        // nop.
    }

    /**
     * 当クラスの唯一のインスタンスを返す。
     *
     * @param array $params 接続時のパラメータ
     * @return DbConnection 唯一のインスタンス
     */
    public static function getInstance(array $params)
    {
        if (!self::$instance) {
            self::$params = $params;
            self::$instance = new DbConnection();
        }
        return self::$instance;
    }

    /**
     * 破棄処理。
     */
    public function __destruct()
    {
        unset($this->connection);
    }

    /**
     * DB へ接続する。
     *
     * @throws ApplicationException PDOException が発生した場合
     */
    private function connect()
    {
        $params = array(
            'dns' => null,
            'user' => '',
            'password' => '',
            'options' => array(),
        );
        if (!is_null(self::$params)) {
            $params = array_merge($params, self::$params);
        }

        $params['options'] += array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`"
        );

        try {
            $this->connection = $this->generateConnection($params);
        } catch (\PDOException $e) {
            Logger::getInstance()->error("Failed to connect db({$params['dns']}, {$params['user']})", false);
            throw new ApplicationException("Failed to connect db", $previous = $e);
        }
    }

    /**
     * コネクションのインスタンスを返す。
     * 未接続の場合は接続してインスタンスを生成して返す。
     * 接続済みの場合は接続済みのインスタンスを返す。
     *
     * @return \PDO コネクション
     */
    public function getConnection()
    {
        if (is_null($this->connection)) {
            $this->connect();
        }
        return $this->connection;
    }

    /**
     * コネクションを生成して自身のインスタンスに設定する。
     *
     * @param array $params 接続時のパラメータ
     * @return \PDO コネクション
     */
    protected function generateConnection($params)
    {
        $connection = new \PDO($params['dns'], $params['user'], $params['password'], $params['options']);
        $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $connection;
    }

}