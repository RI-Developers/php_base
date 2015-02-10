<?php
/**
 * User: Tsuguya Touma
 * Date: 15/01/15
 */

if(!(isset($conf['security']['header']) && $conf['security']['header'] === false)) {

    $header = $conf['security']['header'];

    /*
     * php情報を表示しないようにする
     * デフォルト値推奨
     */
    if(!(isset($header['X-Powered-By']) && $header['X-Powered-By'] === false)) {
        if(function_exists('header_remove')) {
            header_remove('X-Powered-By');
        } else {
            header('X-Powered-By: Secret');
        }
    }

    /*
     * iframe制限
     * デフォルト値は同一ドメインのみ許可
     */
    if(!(isset($header['X-Frame-Options']) && $header['X-Frame-Options'] === false)) {

        if(!isset($header['X-Frame-Options'])) {
            $header['X-Frame-Options'] = 'SAMEORIGIN';
        }

        if(gettype($header['X-Frame-Options']) === 'string') {

            $allow_from = $header['X-Frame-Options'];

        } else {

            $allow_from = 'ALLOW-FROM ';

            $allow_from .= implode(' ', $header['X-Frame-Options']);
        }

        header('X-Frame-Options: ' . $allow_from);
    }

    /*
     * ブラウザによるファイルの自動判別
     * デフォルトだと無効化する
     */
    if(!(isset($header['X-Content-Type-Options']) && $header['X-Content-Type-Options'] === false)) {
        header('X-Content-Type-Options: nosniff');
    }

}
