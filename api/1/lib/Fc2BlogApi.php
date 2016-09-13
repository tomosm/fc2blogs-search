<?php
namespace EXAM0098\Lib;

use EXAM0098\Entity\BlogEntity;

/**
 * FC2 Blog API クラス。
 *
 * @package EXAM0098\Lib
 */
class Fc2BlogApi
{
    private static $LINK_REGEXP = '#^https?://([^\.]+)\.blog(\d*).[^/]+/blog-entry-(\d+)#';

    private $parser = null;

    /**
     * 処理機処理。
     */
    public function __construct()
    {
        $this->parser = new Fc2BlogRdfParser();
    }

    /**
     * FC2BLOG の新着情報 RSS のデータを BlogEntry の配列にして返す。
     *
     * @return array BlogEntry
     */
    public function getBlogEntities()
    {
        $fc2Entries = $this->parser->parseFromUrl();

        $entities = array();
        foreach ($fc2Entries as $fc2Entry) {
            $entity = new BlogEntity();

            if (isset($fc2Entry['link'])
                && isset($fc2Entry['title'])
                && isset($fc2Entry['description'])
                && isset($fc2Entry['dc:date'])
            ) {

                if (preg_match(self::$LINK_REGEXP, $fc2Entry['link'], $matches)) {
                    $entity->setLink($fc2Entry['link']);
                    $entity->setUserName($matches[1]);
                    $entity->setServerNo($matches[2] === "" ? 0 : (int)$matches[2]);
                    $entity->setEntryNo((int)$matches[3]);

                    $entity->setTitle($fc2Entry['title']);
                    $entity->setDescription($fc2Entry['description']);
                    $entity->setPostedAt($fc2Entry['dc:date']);

                    $entities[] = $entity;
                }
            }
        }

        return $entities;
    }

}
