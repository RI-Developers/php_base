<?php
/**
 * User: Tsuguya Touma
 * Date: 15/01/14
 */

$base_dir = '/';

if($base_dir === '/') {
    define('BASE_PATH', str_replace($_SERVER['SCRIPT_NAME'], "", $_SERVER['SCRIPT_FILENAME']));
} else {
    define('BASE_PATH', str_replace($base_dir . $_SERVER['SCRIPT_NAME'], "", $_SERVER['SCRIPT_FILENAME']));
}

define('VIEW_PATH', BASE_PATH . '/view');
define('SYS_PATH',  BASE_PATH . '/system');

require_once SYS_PATH . '/main.php';
