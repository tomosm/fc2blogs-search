<?php
namespace EXAM0098\Service\Impl;

use EXAM0098\Domain\Impl\BlogRepositoryImpl;
use EXAM0098\Service\BlogService;
use EXAM0098\Vo\BlogVo;

/**
 * Blog Service Implementation.
 * @package EXAM0098\Service\Impl
 */
class BlogServiceImpl implements BlogService
{

    private $repository;

    /**
     * 初期処理。
     */
    public function __construct()
    {
        $this->repository = new BlogRepositoryImpl();
    }

    /**
     * @inheritdoc
     */
    public function search(BlogVo $condition)
    {
        $entities = array();
        foreach ($this->repository->search($condition) as $domain) {
            $entities[] = $domain->getEntity();
        }

        $totalCount = $this->repository->count($condition);

        return array($entities, $totalCount);
    }

    /**
     * @inheritdoc
     */
    public function save(array $entities)
    {
        $this->repository->save($entities);
    }

}

