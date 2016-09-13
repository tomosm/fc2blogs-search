<?php

namespace EXAM0098\Lib;
abstract class BaseEntity implements Entity
{
    private $id = null;

    /**
     * @return int ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id ID
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * 値がnull以外の値を連想配列として取得する。
     *
     * @return array 連想配列オブジェクト
     */
    public function toArrayAsMap()
    {
        $map = array();
        ArrayUtils::setIfValueNotNull($map, 'id', $this->getId());
        return $map;
    }
}
