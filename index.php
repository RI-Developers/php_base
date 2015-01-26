<?php
/**
 * User: Tsuguya Touma
 * Date: 15/01/14
 */

$base_dir = '/';

if($base_dir === '/') {
    define('BASE_PATH', realpath(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME'])));
} else {
    define('BASE_PATH', realpath(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME'])) . $base_dir);
}

define('VIEW_PATH', BASE_PATH . '/view');
define('SYS_PATH',  BASE_PATH . '/system');

/**
 * 0: production mode
 * 1: developer mode
 */
define('OPERATION_TYPE', 1);

// base code: http://qiita.com/suin/items/e5cfee2f34efedb67b8c
if(!isset($_SERVER['HTTPS'])) {
    if(isset($_SERVER['HTTP_X_SAKURA_FORWARDED_FOR'])) {
        // さくら 共有ssl
        $_SERVER['HTTPS'] = 'on';

    } else if(isset($_SERVER['SSL'])) {
        // IIS
        $_SERVER['HTTPS'] = $_SERVER['SSL'];

    } else if(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
        && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
        // Reverse proxy
        $_SERVER['HTTPS'] = 'on';

    } else if(isset($_SERVER['HTTP_X_FORWARDED_PORT'])
        && $_SERVER['HTTP_X_FORWARDED_PORT'] === '443') {
        // Reverse proxy
        $_SERVER['HTTPS'] = 'on';

    } else if(isset($_SERVER['SERVER_PORT'])
        && $_SERVER['SERVER_PORT'] === '443') {
        $_SERVER['HTTPS'] = 'on';

    }
}

require_once SYS_PATH . '/main.php';
