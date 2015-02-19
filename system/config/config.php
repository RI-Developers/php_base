<?php
/**
 * User: Tsuguya Touma
 * Date: 15/01/14
 */

/***********************************************************************************
 *
 * デフォルト値を定義しています。
 *
 * 上書きしたいパラメータがある場合、この階層にmy_conf.phpを作成してください。
 * また、ローカルのみに反映させたいものがある場合、dev_conf.phpを作成してください。
 * dev_conf.phpはignoreしているので色々試しやすいと思います。
 *
 ***********************************************************************************/


return array(

    // 拡張子なしでアクセスした場合に試行するタイプ
    'extension' => array('php', 'html'),

    'encoding' => 'UTF-8',

    // TODO:ステータス毎に設定できるように変更
    '404' => '/404.php',
//    'status-code' => array(
//        '400' => '/status/400.php',
//        '401' => '/status/401.php',
//        '403' => '/status/403.php',
//        '404' => '/status/404.php',
//        '405' => '/status/405.php',
//        '410' => '/status/410.php',
//        '415' => '/status/415.php',
//        '418' => '/status/418.php'
//    ),

    /*
     * セキュリティ設定
     * 現在はheaderしかない
     */
    'security' => array(

        /*
         * HTTPヘッダ
         * TODO:対象を設定できるようにする
         */
        'header' => array(

            // trueにするとPHPバージョン情報を出力しない
            'X-Powered-By' => true,

            /*
             * iframe制限
             * TODO:配列で指定できるようにする
             */
            'X-Frame-Options' => 'SAMEORIGIN',

            /*
             * ブラウザのXSSフィルタの設定
             * 有効化: 1; mode=block
             * 無効化: 0
             */
            'X-XSS-Protection' => '1; mode=block',

            // trueにするとブラウザのMIMEタイプの自動補完を無効化する
            'X-Content-Type-Options' => true,

            /*
             * サイトへのアクセスを次回以降最初からSSL通信を試す。
             * SSL証明書を使用する場合のみ設定するといいです。
             * コメントで例も書いておきます。
             */
            'Strict-Transport-Security' => false,
            // 'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains', // サブドメインも含む
            // 'Strict-Transport-Security' => 'max-age=31536000',                    // サブドメインは含まない

            /*
             * CSPの設定
             * 適切な設定を行えばXSS等のリスクが大幅に低減する
             * 使用する場合はサイト構成を見直す必要がある為デフォルトでは無効化
             */
            'Content-Security-Policy' => false
            // フォールバックをデフォルトで有効化すべきか・・・
            // 'Content-Security-Policy' => array(
            //     'policy' => array('nonce'), // nonce以外はそのまま使います。
            //     'nonce-fallback' => true,
            //     'nonce-length' => 16
            // )
        )
    ),
    'production' => array(
        'cache' => array(
            // 指定できるパターンは下の他にtext, compression, sound, other
            // TODO:配列指定に変更
            'default' => 'cache',
            'image' => 'no-cache',
            'movie' => 'no-cache'
        ),
        'debug'           => false, // display_errorsの指定
        'error_reporting' => E_ALL ^ E_DEPRECATED,
        'log'             => '/var/log/php_micro_error.log'
    ),
    'developer' => array(
        'cache' => 'no-cache',
        'debug' => true,
        'error_reporting' => E_ALL | E_STRICT,
        'log'   => false
    )
);