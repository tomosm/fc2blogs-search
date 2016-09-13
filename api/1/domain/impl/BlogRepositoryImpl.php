<?php
namespace EXAM0098\Domain\Impl;

use EXAM0098\Dao\Impl\BlogDaoImpl;
use EXAM0098\Domain\BlogRepository;
use EXAM0098\Lib\BaseRepository;
use EXAM0098\Vo\BlogVo;

/**
 * Blog Repository Implementation.
 * @package EXAM0098\Domain\Impl
 */
class BlogRepositoryImpl extends BaseRepository implements BlogRepository
{

    private $dao = null;

    /**
     * 初期処理。
     */
    public function __construct()
    {
        $this->dao = new BlogDaoImpl();
    }

    /**
     * @inheritdoc
     */
    public function search(BlogVo $condition = null)
    {
        $entities = $this->dao->findAllWith($condition);
        $domains = array();
        foreach ($entities as $entity) {
            $domains[] = new BlogDomainImpl($entity);
        }
        $this->setDomains($domains);
        return $domains;
    }

    /**
     * @inheritdoc
     */
    public function count(BlogVo $condition = null)
    {
        return $this->dao->count($condition);
    }

    /**
     * @inheritdoc
     */
    public function save(array $entities = null)
    {
        if (!is_null($entities)) {
            $this->setDomains($this->convertFromEntities($entities));
        }
        if (count($this->getDomains()) === 0) {
            return 0;
        }
        return $this->dao->bulkInsertOrUpdate($this->convertDomainsToEntities());
    }

    private function convertFromEntities(array $entities)
    {
        $domains = array();
        foreach ($entities as $entity) {
            $domains[] = new BlogDomainImpl($entity);
        }
        return $domains;
    }

    /**
     * @inheritdoc
     */
    public function destroyByPostedAtBefore($datetime)
    {
        return $this->dao->deleteByPostedAtBefore($datetime);
    }


}
