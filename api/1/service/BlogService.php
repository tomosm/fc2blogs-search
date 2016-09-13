<?php
namespace EXAM0098\Service;

use EXAM0098\Lib\Service;
use EXAM0098\Vo\BlogVo;

/**
 * Blog Service Interface.
 *
 * @package EXAM0098\Service
 */
interface BlogService extends Service
{

    /**
     * 引数の $condition を検索条件に検索し、
     * 該当するデータを返す。
     *
     * @param BlogVo|null $condition 検索条件
     * @return array BlogEntity
     */
    public function search(BlogVo $condition);

    /**
     * データを保存する。
     *
     * @param array $entities 対象データ
     * @return int 保存データ件数
     */
    public function save(array $entities);

}

