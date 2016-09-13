<?php
namespace EXAM0098\Lib;

/**
 * Repository の基底クラス。
 *
 * @package EXAM0098\Lib
 */
abstract class BaseRepository implements Repository
{
    private $domains = null;

    /**
     * @return array|null Domain
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * @param array $domains Domain
     */
    public function setDomains(array $domains)
    {
        $this->domains = $domains;
    }

    /**
     * @return array of Entity
     */
    protected function convertDomainsToEntities()
    {
        $entities = array();
        foreach ($this->getDomains() as $domain) {
            $entities[] = $domain->getEntity();
        }
        return $entities;
    }

}