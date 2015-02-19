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
    'extension' => array('php', 'html'),
    'encoding'   => 'UTF-8',
    '404'        => '/404.php',
    'security'   => array(
        'header' => array(
            'X-Powered-By' => true,
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-XSS-Protection' => '1; mode=block',
            'X-Content-Type-Options' => true,
            // サイトへのアクセスを次回以降最初からSSL通信を試す。
            // SSL証明書を使用する場合のみ設定するといいです。
            // コメントで例も書いておきます。
            'Strict-Transport-Security' => false,
            // 'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains', // サブドメインも含む
            // 'Strict-Transport-Security' => 'max-age=31536000',                    // サブドメインは含まない

            // 使用する場合はサイト構成を考える必要がある為デフォルトでは無効化
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