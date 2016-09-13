<?php

namespace EXAM0098\Lib;

/**
 * Vo の基底クラス。
 * @package EXAM0098\Lib
 */
abstract class BaseVo
{
    // 最大件数
    private $limit = null;
    // 取得件数の位置
    private $offset = null;
    // 並び順
    // +A or A は昇順
    // -A は降順
    private $order = null;

    /**
     * @return int 最大件数
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit 最大件数
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return int 取得件数の位置
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset 取得件数の位置
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return string 並び順
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $order 並び順
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

}