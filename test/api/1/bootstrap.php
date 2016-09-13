<?php

/**
 * API コンポーネント初期化のためのスクリプト。
 * API コンポーネントを利用する場合は必ず読み込む必要がある。
 */

require_once __DIR__ . '/conf/settings.php';
require_once __DIR__ . '/../../../api/1/lib/ClassLoader.php';

$loader = new \EXAM0098\Lib\ClassLoader();
$loader->registerDir(__DIR__ . "/../../../api/1/");
$loader->registerDir(__DIR__);
$loader->register();
