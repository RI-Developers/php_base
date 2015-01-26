<?php
/**
 * User: Tsuguya Touma
 * Date: 15/01/14
 */
if (!defined('BASE_PATH')) exit();

require_once SYS_PATH . '/config/config.php';
// 独自の定義で上書きしたい場合
if(file_exists(SYS_PATH . 'config/my_conf.php')) {
    require_once SYS_PATH . 'config/my_conf.php';
}
// ローカルのみに反映させたいものがある場合
if(file_exists(SYS_PATH . 'config/dev_conf.php')) {
    require_once SYS_PATH . 'config/dev_conf.php';
}

require_once SYS_PATH . '/common.php';
require_once SYS_PATH . '/security.php';

if(empty($conf['encoding'])) {
    $conf['encoding'] = 'UTF-8';
}

/*
 * phpのコンフィグ周りの設定
 */

if(OPERATION_TYPE === 0) {
    $behavior = $conf['production'];
} else {
    $behavior = $conf['developer'];
}

if($behavior['debug']) {
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}

error_reporting($behavior['error_reporting']);

if($behavior['log'] !== false && !empty($behavior['log'])) {
    ini_set('error_log', $behavior['log']);
}

/*
 * ルーティング
 */

// GETパラメータがある場合、切り離したパスを取得
if(isset($_SERVER['QUERY_STRING'])) {
    $load_path = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
} else {
    $load_path = $_SERVER['REQUEST_URI'];
}

$replace_array = array(
    // ディレクトリトラバーサル対策
    '../', '..\\', '\0',
    // たまに問題起きるので
    (isSsl() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']
);

if($base_dir !== '/') {
    $replace_array[] = preg_replace('/^' . str_replace('/', '\/', $base_dir) . '/', '', $load_path);
}

$load_path = str_replace($replace_array, '', $load_path);

// スラッシュで終わってたら最後のスラッシュを削除する
if($load_path[mb_strlen($load_path, $conf['encoding']) - 1] === '/') {
    $load_path = mb_substr($load_path, 0, -1, $conf['encoding']);
}

$dot = strripos($load_path, '.');

if($dot === false) {

    if(file_exists(VIEW_PATH . $load_path . '/index.php')) {
        $load_path .= '/index.php';
    } else {
        $load_path .= '.php';
    }

    $extension = 'php';

} else {

    $extension = mb_substr($load_path, $dot + 1/*, null, $conf['encoding']*/);

}

$content_type = setContentsType($extension);

if($content_type === 'movie' || $content_type == 'audio') {

    loadRange($load_path);

} else if($extension === 'php') {

    if(isset($vars)) {
        loadView($load_path, true, $vars);
    } else {
        loadView($load_path, true);
    }

} else {

    // phpでない場合はそのままファイルを読みに行く
    loadView($load_path);

}
