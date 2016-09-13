<?php
namespace EXAM0098\Dao;

use EXAM0098\Lib\Dao;
use EXAM0098\Vo\BlogVo;

/**
 * Blog Dao Interface.
 * @package EXAM0098\Dao
 */
interface BlogDao extends Dao
{

    /**
     * 引数の $entities を一括登録、データが重複している場合は更新する。
     * 登録件数、および更新件数の合計件数を返す。
     *
     * @param array $entities BlogEntity 一覧
     * @return int 登録件数、および更新件数の合計件数
     */
    function bulkInsertOrUpdate(array $entities);

    /**
     * 引数の $condition を検索条件に検索し、
     * 該当するデータを返す。
     *
     * @param BlogVo|null $condition 検索条件
     * @return array BlogEntity
     */
    function findAllWith(BlogVo $condition = null);

    /**
     * 引数の $condition を条件検索に検索し、該当するデータ件数を返す。
     *
     * @param BlogVo|null $condition 検索条件
     * @return int データ件数
     */
    function count(BlogVo $condition = null);

    /**
     * 指定した日付より古いデータを物理削除する。
     * 削除件数を返す。
     *
     * @param string $datetime 日付
     * @return int 削除件数
     */
    function deleteByPostedAtBefore($datetime);

}