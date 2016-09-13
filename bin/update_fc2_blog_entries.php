#!/usr/bin/env php
<?php

//
// FC2 ブログの新しい記事を取得し検索用にデータを保存するスクリプト
//

print '[RUN]     ' . basename(__FILE__) . "\n...\n";

require_once __DIR__ . '/../api/1/bootstrap.php';

try {
    $fc2BlogEntities = (new \EXAM0098\Lib\Fc2BlogApi())->getBlogEntities();
    $blogService = new \EXAM0098\Service\Impl\BlogServiceImpl();
    $blogService->save($fc2BlogEntities);

    print '[SUCCESS] ' . basename(__FILE__) . "\n";
} catch (Exception $e) {
    print '[FAILURE] ' . basename(__FILE__) . "\n";
    print "{$e->getMessage()}\n";
    throw $e;
}

