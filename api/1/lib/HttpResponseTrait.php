<?php
namespace EXAM0098\Lib;

/**
 * Http Response トレイト。
 *
 * @package EXAM0098\Lib
 */
trait HttpResponseTrait
{
    /**
     * 共通の response header を追加する。
     * 主に、セキュリティ強化、キャッシュ不可など。
     */
    private function addUsefulHeaders()
    {
        header('X-Frame-Options: deny');
        header('X-XSS-Protection: 1; mode=block');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        header('Expires: -1');
        header('Pragma: no-cache');
    }

    /**
     * JSON オブジェクトとして出力する。
     * http response code: 200
     *
     * @param array $array 出力する値
     */
    private function outputAsJson(array $array)
    {
        $this->addUsefulHeaders();

        header("Content-Type: application/json; charset=utf-8");
        if (count($array)) {
            print json_encode($array);
        }
    }

    /**
     * 成功時の内容を出力する。
     *
     * @param array|null $array 出力する値
     * @param int $statusCode HTTP Response Code
     */
    private function success(array $array = null, $statusCode = 200)
    {
        http_response_code($statusCode);
        $this->outputAsJson($array);
    }

    /**
     * エラー時の内容を出力する。
     * http response code: 500
     * http body: {errorCode: ?, errorMessage: ?}
     *
     * @param \Exception|null $e 例外
     */
    private function error(\Exception $e = null)
    {
        http_response_code(500);
        if (!is_null($e)) {
            Logger::getInstance()->error($e, false);
            $this->outputAsJson(array(
                'errorCode' => $e->getCode(),
                'errorMessage' => $e->getMessage()
            ));
        }
    }

}