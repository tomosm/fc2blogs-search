<?php
namespace EXAM0098\Lib;

/**
 * アプリ例外クラス。
 *
 * @package EXAM0098\Lib
 */
class ApplicationException extends \Exception
{
    private static $CODE = 10000;

    /**
     * 初期処理。
     *
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, self::$CODE, $previous);
    }

}


