<?php

namespace EXAM0098\Controller;

use EXAM0098\Lib\Controller;
use EXAM0098\Lib\HttpResponseTrait;
use EXAM0098\Service\Impl\BlogServiceImpl;
use EXAM0098\Vo\BlogVo;

/**
 * Blog Controller Class.
 * @package EXAM0098\Controller
 */
class BlogController implements Controller
{
    use HttpResponseTrait;

    private static $DEFAULT_SEARCH_LIMIT = 30;
    private static $DEFAULT_SEARCH_OFFSET = 0;
    private static $DEFAULT_SEARCH_ORDER = '-postedAt';

    private $service = null;

    /**
     * 初期処理。
     */
    public function __construct()
    {
        $this->service = new BlogServiceImpl();
    }

    /**
     * URL パラメータで渡された値を検索条件として、検索結果を取得し JSON オブジェクトとして出力する。
     * また、検索条件に該当する全テータ件数を X-Total-Count として http response header に追加する。
     * <pre>
     * # 正常終了の場合:
     * http response code: 200
     * http body: [{key1: ?, ...}, {...}, ...]
     *
     * # エラーが発生した場合
     * http response code: 500
     * http body: {errorCode: ?, errorMessage: ?}
     * </pre>
     */
    public function search()
    {
        $condition = new BlogVo();
        // 並び順は未指定の場合新着から表示
        $condition->setOrder(@$_GET['order'] ? $_GET['order'] : self::$DEFAULT_SEARCH_ORDER);
        $condition->setLink(@$_GET['link']);
        $condition->setPostedAt(@$_GET['postedAt']);
        $condition->setOffset(@$_GET['offset'] ? $_GET['offset'] : self::$DEFAULT_SEARCH_OFFSET);
        $condition->setEntryNo(@$_GET['entryNo']);
        $condition->setServerNo(@$_GET['serverNo']);
        $condition->setUserName(@$_GET['userName']);
        $condition->setLimit(@$_GET['limit'] ? $_GET['limit'] : self::$DEFAULT_SEARCH_LIMIT);

        try {
            list($blogEntities, $totalCount) = $this->service->search($condition);
            $foundBlogs = array();
            foreach ($blogEntities as $blogEntity) {
                $foundBlogs[] = $blogEntity->toArrayAsMap();
            }

            header('X-Total-Count: ' . $totalCount);
            $this->success(array(
                'data' => $foundBlogs,
                'limit' => $condition->getLimit(),
                'offset' => $condition->getOffset()
            ));
        } catch (\Exception $e) {
            $this->error($e);
        }
    }

}