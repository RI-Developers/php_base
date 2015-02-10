<?php
/**
 * User: Tsuguya Touma
 * Date: 15/01/15
 */

if(!(isset($conf['security']['header']) && $conf['security']['header'] === false)) {

    /*
     * php情報を表示しないようにする
     * デフォルト値推奨
     */
    if(!(isset($conf['security']['header']['X-Powered-By']) && $conf['security']['header']['X-Powered-By'] === false)) {
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
    if(!(isset($conf['security']['header']['X-FRAME-OPTIONS']) && $conf['security']['header']['X-FRAME-OPTIONS'] === false)) {

        if(!isset($conf['security']['header']['X-FRAME-OPTIONS'])) {
            $conf['security']['header']['X-FRAME-OPTIONS'] = 'SAMEORIGIN';
        }

        if(gettype($conf['security']['header']['X-FRAME-OPTIONS']) === 'string') {

            $allow_from = $conf['security']['header']['X-FRAME-OPTIONS'];

        } else {

            $allow_from = 'ALLOW-FROM ';

            $allow_from .= implode(' ', $conf['security']['header']['X-FRAME-OPTIONS']);
        }

        header('X-FRAME-OPTIONS: ' . $allow_from);
    }

    /*
     * ブラウザによるファイルの自動判別
     * デフォルトだと無効化する
     */
    if(!(isset($conf['security']['header']['X-Content-Type-Options']) && $conf['security']['header']['X-Content-Type-Options'] === false)) {
        header('X-Content-Type-Options: nosniff');
    }

}
