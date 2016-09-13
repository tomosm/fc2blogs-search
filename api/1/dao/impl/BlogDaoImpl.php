<?php

namespace EXAM0098\Dao\Impl;

use EXAM0098\Dao\BlogDao;
use EXAM0098\Entity\BlogEntity;
use EXAM0098\Lib\DateUtils;
use EXAM0098\Lib\DbDaoTrait;
use EXAM0098\Vo\BlogVo;

/**
 * Blog Dao Implementation.
 * @package EXAM0098\Dao\Impl
 */
class BlogDaoImpl implements BlogDao
{
    use DbDaoTrait;

    private static $SELECT_SQL = 'SELECT `link`, `title`, `description`, `postedAt` FROM `blogs`';
    private static $COUNT_SQL = 'SELECT COUNT(`id`) AS `TOTAL_COUNT` FROM `blogs`';
    private static $BUlK_INSERT_OR_UPDATE_SQL = 'INSERT INTO `blogs`(`userName`, `serverNo`, `entryNo`, `title`, `description`, `link`, `postedAt`, `createdAt`) VALUES%s ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `description`=VALUES(`description`), `link`=VALUES(`link`), `postedAt`=VALUES(`postedAt`)';
    private static $DELETE_BY_POSTEDAT_SQL = 'DELETE FROM `blogs` WHERE `postedAt` <= :postedAt';

    /**
     * @inheritdoc
     */
    public function bulkInsertOrUpdate(array $entities)
    {
        $entitiesParamTypes = array();
        $entitiesParams = array();
        $valueStrings = array();
        $bulkInsertMaxCount = $this->getBulkInsertMaxCount();
        $affectedRow = 0;

        foreach ($entities as $i => $entity) {
            // 1行分のPreparedStatement用param設定
            $entityParams = array();
            $entityParams[":userName{$i}"] = $entity->getUserName();
            $entityParams[":serverNo{$i}"] = $entity->getServerNo();
            $entityParams[":entryNo{$i}"] = $entity->getEntryNo();
            $entityParams[":title{$i}"] = $entity->getTitle();
            $entityParams[":description{$i}"] = $entity->getDescription();
            $entityParams[":link{$i}"] = $entity->getLink();
            $entityParams[":postedAt{$i}"] = DateUtils::formatHyphenYmdHis($entity->getPostedAt(), 'Asia/Tokyo');
            $entityParams[":createdAt{$i}"] = DateUtils::formatHyphenYmdHis();

            // 1行分のPreparedStatement用dataType設定、デフォルトは文字列なので文字列以外のカラム
            $entitiesParamTypes[":serverNo{$i}"] = \PDO::PARAM_INT;
            $entitiesParamTypes[":entryNo{$i}"] = \PDO::PARAM_INT;

            $valueStrings[] = sprintf('(%s)', implode(',', array_keys($entityParams)));
            $entitiesParams = array_merge($entitiesParams, $entityParams);

            // 一度に実行可能な最大のバルクインサート数に達したら実行する
            if (($i + 1) % $bulkInsertMaxCount === 0) {
                $affectedRow += $this->execute(
                    sprintf(self::$BUlK_INSERT_OR_UPDATE_SQL, implode(',', $valueStrings)),
                    $entitiesParams,
                    $entitiesParamTypes
                );
                $entitiesParamTypes = array();
                $entitiesParams = array();
                $valueStrings = array();
            }
        }

        if (!empty($entitiesParams)) {
            $affectedRow += $this->execute(
                sprintf(self::$BUlK_INSERT_OR_UPDATE_SQL, implode(',', $valueStrings)),
                $entitiesParams,
                $entitiesParamTypes
            );
        }

        return $affectedRow;
    }

    /**
     * @inheritdoc
     */
    public function findAllWith(BlogVo $vo = null)
    {
        list($whereClause, $whereBindParams, $whereBindParamTypes) = $this->createFindWhereClauseAndBindParams($vo);
        $sql = self::$SELECT_SQL . $whereClause . $this->createOrderByClause($vo) . $this->createLimitClause($vo);
        $found = $this->query($sql, $whereBindParams, $whereBindParamTypes);

        $entities = array();
        foreach ($found as $row) {
            $entity = new BlogEntity();
            $entity->setLink($row['link']);
            $entity->setTitle($row['title']);
            $entity->setDescription($row['description']);
            $entity->setPostedAt($row['postedAt']);
            $entities[] = $entity;
        }
        return $entities;
    }

    /**
     * @inheritdoc
     */
    public function count(BlogVo $vo = null)
    {
        list($whereClause, $whereBindParams, $whereBindParamTypes) = $this->createFindWhereClauseAndBindParams($vo);
        $sql = self::$COUNT_SQL . $whereClause;
        $found = $this->query($sql, $whereBindParams, $whereBindParamTypes);
        if (count($found) === 0) {
            return 0;
        }
        return $found[0]['TOTAL_COUNT'];
    }

    private function createFindWhereClauseAndBindParams(BlogVo $vo = null)
    {
        if (is_null($vo)) {
            return array('', array(), array());
        }

        $conditions = array();
        $bindParams = array();
        $bindParamTypes = array();

        if (!is_null($vo->getPostedAt())) {
            $conditions[] = ' DATE_FORMAT(`postedAt`, \'%Y-%m-%d\') = :postedAt ';
            $bindParams[':postedAt'] = $vo->getPostedAt();
        }
        if (!is_null($vo->getLink())) {
            $conditions[] = ' `link` = :link ';
            $bindParams[':link'] = $vo->getLink();
        }
        if (!is_null($vo->getUserName())) {
            $conditions[] = ' `userName` = :userName ';
            $bindParams[':userName'] = $vo->getUserName();
        }
        if (!is_null($vo->getServerNo())) {
            $conditions[] = ' `serverNo` = :serverNo ';
            $bindParams[':serverNo'] = $vo->getServerNo();
            $bindParamTypes[':serverNo'] = \PDO::PARAM_INT;
        }
        if (!is_null($vo->getEntryNo())) {
            $conditions[] = ' `entryNo` >= :entryNo ';
            $bindParams[':entryNo'] = $vo->getEntryNo();
            $bindParamTypes[':entryNo'] = \PDO::PARAM_INT;
        }

        if (count($bindParams) === 0) {
            return array('', array(), array());
        }

        return array(' WHERE ' . implode(' AND ', $conditions), $bindParams, $bindParamTypes);
    }

    /**
     * @inheritdoc
     */
    public function deleteByPostedAtBefore($datetime)
    {
        return $this->execute(
            self::$DELETE_BY_POSTEDAT_SQL,
            array(':postedAt' => $datetime)
        );
    }


}