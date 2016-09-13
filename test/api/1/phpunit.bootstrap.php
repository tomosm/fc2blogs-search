<?php

require_once __DIR__ . '/bootstrap.php';

// For composer
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../vendor/hamcrest/hamcrest-php/hamcrest/Hamcrest.php';

// For hamcrest
set_include_path(
    __DIR__ . '/../../../vendor/hamcrest/hamcrest-php/hamcrest' . PATH_SEPARATOR .
    get_include_path()
);

