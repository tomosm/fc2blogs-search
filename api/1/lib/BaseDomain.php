<?php
namespace EXAM0098\Lib;

/**
 * Domain の基底クラス。
 * @package EXAM0098\Lib
 */
abstract class BaseDomain implements Domain
{

    private $entity = null;

    /**
     * @return Entity|null
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

}