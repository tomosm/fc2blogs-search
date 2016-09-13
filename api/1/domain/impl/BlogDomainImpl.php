<?php

namespace EXAM0098\Domain\Impl;

use EXAM0098\Domain\BlogDomain;
use EXAM0098\Entity\BlogEntity;
use EXAM0098\Lib\BaseDomain;

/**
 * Blog Domain Implementation.
 * @package EXAM0098\Domain\Impl
 */
class BlogDomainImpl extends BaseDomain implements BlogDomain
{
    /**
     * 初期処理.
     *
     * @param BlogEntity $entity
     */
    public function __construct(BlogEntity $entity)
    {
        $this->setEntity($entity);
    }

}