<?php

namespace EXAM0098\Vo;

use EXAM0098\Lib\BaseVo;

/**
 * Blog 用 value Object.
 * @package EXAM0098\BaseVo
 */
class BlogVo extends BaseVo
{
    // ユーザー名
    private $userName = null;
    // サーバー番号
    private $serverNo = null;
    // エントリーNo
    private $entryNo = null;
    // URL
    private $link = null;
    // 投稿日
    private $postedAt = null;


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