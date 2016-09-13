<?php
namespace EXAM0098\Domain;

use EXAM0098\Lib\Repository;
use EXAM0098\Vo\BlogVo;

/**
 * Blog Repository Interface.
 *
 * @package EXAM0098\Domain
 */
interface BlogRepository extends Repository
{

    /**
     * 引数の $condition を検索条件に検索し、
     * 該当するデータを返す。
     *
     * @param BlogVo|null $condition 検索条件
     * @return array BlogDomain
     */
    public function search(BlogVo $condition = null);

    /**
     * 引数の $condition を条件検索に検索し、該当するデータ件数を返す。
     *
     * @param BlogVo|null $condition 検索条件
     * @return int データ件数
     */
    public function count(BlogVo $condition = null);

    /**
     * データを保存する。
     *
     * @param array|null $entities 対象データ
     * @return int 保存データ件数
     */
    public function save(array $entities = null);

    /**
     * 指定した日付より古いデータを物理削除する。
     * 削除件数を返す。
     *
     * @param string $datetime 日付
     * @return int 削除件数
     */
    public function destroyByPostedAtBefore($datetime);

}
