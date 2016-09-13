#!/usr/bin/env php
<?php

//
// 指定した日時より古いデータを削除するスクリプト
//

print '[RUN]     ' . basename(__FILE__) . "\n...\n";

require_once __DIR__ . '/../api/1/bootstrap.php';

try {
    $blogRepository = new \EXAM0098\Domain\Impl\BlogRepositoryImpl();
    $blogRepository->destroyByPostedAtBefore(date('Y-m-d H:i:s', strtotime(sprintf('-%s weeks', HOW_MANY_WEEKS_DATA_HAS))));

    print '[SUCCESS] ' . basename(__FILE__) . "\n";
} catch (Exception $e) {
    print '[FAILURE] ' . basename(__FILE__) . "\n";
    print "{$e->getMessage()}\n";
    throw $e;
}
