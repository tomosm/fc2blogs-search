<?php

@include_once __DIR__ . '/database.php';

//
// For Common
//
// 一度に実行するバルクインサートの最大数
@define("BULK_INSERT_MAX_COUNT", 2);
// 暗号化のソルト
@define("SALT", '__SALT__');

//
// For Application
//
// 何週間のデータを保持するか
@define("HOW_MANY_WEEKS_DATA_HAS", 1);
