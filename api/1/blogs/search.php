<?php

require_once __DIR__ . '/../bootstrap.php';

/**
 * ブログ検索エントリーポイント。
 */

$controller = new \EXAM0098\Controller\BlogController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller->search();
} else {
    http_response_code(404);
}
