<?php
namespace EXAM0098\Lib;

/**
 * DB 用 Dao トレイト
 * @package EXAM0098\Lib
 */
trait DbDaoTrait
{

    private function getDbConnectionManager()
    {
        return DbConnectionManager::getInstance();
    }

    /**
     * コネクションのインスタンスを返す。
     *
     * @return \PDO コネクション
     */
    private function getConnection()
    {
        return $this->getDbConnectionManager()->connect(array(
            'dns' => CryptUtils::decrypt(DNS, SALT),
            'user' => CryptUtils::decrypt(USER, SALT),
            'password' => CryptUtils::decrypt(PASSWORD, SALT),
            'options' => array(),
        ));
    }

    /**
     * 更新処理の SQL を実行して実行件数を返す。
     *
     * @param string $sql SQL
     * @param array $params PreparedStatement のパラメータ
     * @param array $dataType PreparedStatement のパラメータの型
     * @return int 実行件数
     * @throws ApplicationException PDOException の例外が発生した場合
     */
    private function execute($sql, $params = array(), $dataType = array())
    {
        try {
            $stmt = $this->getPreparedStatement($sql, $params, $dataType);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            $paramsString = '';
            foreach ($params as $key => $value) {
                $paramsString .= "{$key}={$value}\n";
            }
            throw new ApplicationException("Failed to query => \nsql: {$sql}\nparams: {$paramsString}", $e);
        }
    }

    /**
     * @return int 一括登録の最大数
     */
    private function getBulkInsertMaxCount()
    {
        return defined("BULK_INSERT_MAX_COUNT") ? BULK_INSERT_MAX_COUNT : 50;
    }

    /**
     * 検索処理の SQL を実行して検索結果を返す。
     *
     * @param string $sql SQL
     * @param array $params PreparedStatement のパラメータ
     * @param array $dataType PreparedStatement のパラメータの型
     * @return array 検索結果
     * @throws ApplicationException PDOException の例外が発生した場合
     */
    private function query($sql, $params = array(), $dataType = array())
    {
        $rows = array();
        try {
            $stmt = $this->getPreparedStatement($sql, $params, $dataType);
            if ($stmt->execute()) {
                while ($row = $stmt->fetch()) {
                    $rows[] = $row;
                }
            }
            return $rows;
        } catch (\PDOException $e) {
            $paramsString = '';
            foreach ($params as $key => $value) {
                $paramsString .= "{$key}={$value}\n";
            }
            throw new ApplicationException("Failed to query => \nsql: {$sql}\nparams: {$paramsString}", $e);
        }
    }

    private function getPreparedStatement($sql, $params, $dataType)
    {
        $stmt = $this->getConnection()->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, empty($dataType[$key]) ? \PDO::PARAM_STR : $dataType[$key]);
        }

        return $stmt;
    }

    private function createLimitClause(BaseVo $vo = null)
    {
        if (is_null($vo) || empty($vo->getLimit())) {
            return '';
        }
        $limit = empty($vo->getOffset()) ? $vo->getLimit() : "{$vo->getOffset()},{$vo->getLimit()}";
        return " LIMIT $limit ";
    }

    private function createOrderByClause(BaseVo $vo = null)
    {
        if (is_null($vo) || empty($vo->getOrder())) {
            return '';
        }

        $order = $vo->getOrder();
        $orderType = null;
        switch ($order[0]) {
            case '-':
                $orderType = ' DESC';
                $order = ltrim($order, '-');
                break;
            case '+':
                $orderType = ' ASC';
                $order = ltrim($order, '+');
                break;
            default:
                $orderType = '';
                break;
        }

        return " ORDER BY {$order}{$orderType} ";
    }
}