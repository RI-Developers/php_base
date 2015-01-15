<?php
/**
 * User: Tsuguya Touma
 * Date: 15/01/14
 */
if (!defined('BASE_PATH')) exit();

require_once SYS_PATH . '/config/config.php';
require_once SYS_PATH . '/common.php';
require_once SYS_PATH . '/security.php';

// GETパラメータがある場合、切り離したパスを取得
if(isset($_SERVER['QUERY_STRING'])) {
    $load_path = str_replace('?' . $_SERVER['QUERY_STRING'], "", $_SERVER['REQUEST_URI']);
} else {
    $load_path = $_SERVER['REQUEST_URI'];
}

// スラッシュで終わってたら最後のスラッシュを削除する
if($load_path[mb_strlen($load_path, $conf['encoding']) - 1] === '/') {
    $load_path = mb_substr($load_path, 0, -1, $conf['encoding']);
}

$dot = stripos($load_path, '.');

if($dot === false) {

    $load_path .= '.php';
    $extension = 'php';

} else {

    $extension = mb_substr($load_path, $dot + 1, null, $conf['encoding']);

}

setContentsType($extension);

if($extension === 'php') {

    if(isset($vars)) {
        loadView($load_path, true, $vars);
    } else {
        loadView($load_path, true);
    }

} else {

    // phpでない場合はそのままファイルを読みに行く
    loadView($load_path);

}
