<?php
/**
 * User: Tsuguya Touma
 * Date: 15/01/15
 */

global $conf;

if(!(isset($conf['security']['header']) && $conf['security']['header'] === false)) {

    if(function_exists('header_remove')) {
        header_remove('X-Powered-By');
    } else {
        header('X-Powered-By: Secret');
    }

}