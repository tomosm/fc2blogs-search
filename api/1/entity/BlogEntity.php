<?php

namespace EXAM0098\Entity;

use EXAM0098\Lib\ArrayUtils;
use EXAM0098\Lib\BaseEntity;

/**
 * Blog Entity Class
 * @package EXAM0098\Entity
 */
class BlogEntity extends BaseEntity
{
    // ユーザー名
    private $userName = null;
    // サーバー番号
    private $serverNo = null;
    // エントリーNo
    private $entryNo = null;
    // タイトル
    private $title = null;
    // description
    private $description = null;
    // URL
    private $link = null;
    // 投稿日
    private $postedAt = null;

    /**
     * Entity が保持する変数のデータを連想配列に変換して返す。
     * ただし変数が null 以外の変数のみ連想配列へ格納する。
     * <pre>
     * ex) userName="hoge", serverNo=2, ... others=null
     * return array("userName" => "hoge", "serverNo" => 2);
     * </pre>
     *
     * @return array 連想配列
     */
    public function toArrayAsMap()
    {
        $map = parent::toArrayAsMap();
        ArrayUtils::setIfValueNotNull($map, 'userName', $this->getUserName());
        ArrayUtils::setIfValueNotNull($map, 'serverNo', $this->getServerNo());
        ArrayUtils::setIfValueNotNull($map, 'entryNo', $this->getEntryNo());
        ArrayUtils::setIfValueNotNull($map, 'title', $this->getTitle());
        ArrayUtils::setIfValueNotNull($map, 'description', $this->getDescription());
        ArrayUtils::setIfValueNotNull($map, 'link', $this->getLink());
        ArrayUtils::setIfValueNotNull($map, 'postedAt', $this->getPostedAt());
        return $map;
    }

    /**
     * @return string ユーザー名
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName ユーザー名
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return int サーバー番号
     */
    public function getServerNo()
    {
        return $this->serverNo;
    }

    /**
     * @param int $serverNo サーバー番号
     */
    public function setServerNo($serverNo)
    {
        $this->serverNo = $serverNo;
    }

    /**
     * @return int エントリーNo
     */
    public function getEntryNo()
    {
        return $this->entryNo;
    }

    /**
     * @param int $entryNo エントリーNo
     */
    public function setEntryNo($entryNo)
    {
        $this->entryNo = $entryNo;
    }

    /**
     * @return string タイトル
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title タイトル
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string URL
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link URL
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string 投稿日
     */
    public function getPostedAt()
    {
        return $this->postedAt;
    }

    /**
     * @param string $postedAt 投稿日
     */
    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;
    }


}