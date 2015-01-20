<?php
/**
 * User: Tsuguya Touma
 * Date: 15/01/14
 */
$conf = array(
    'encoding' => 'UTF-8',
    '404'      => '/404.php',
    'security' => array(
        'header' => array(
            'X-Powered-By' => true,
            'X-FRAME-OPTIONS' => 'SAMEORIGIN'
        )
    )
);