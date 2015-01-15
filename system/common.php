<?php
/**
 * User: Tsuguya Touma
 * Date: 15/01/15
 */

if(!function_exists('loadView')) {

    function loadView($filename, $load = false, $vars = null) {

        if(!file_exists(VIEW_PATH . $filename)) {
            load404();
            return;
        }

        if($load) {

            $files = glob(SYS_PATH . '/common/*');

            if($files !== false && !empty($files)) {
                foreach($files as $file) {
                    require $file;
                }

            }

        }

        unset($load);
        if(isset($files)) {
            unset($files);
            if(isset($file)) {
                unset($file);
            }
        }


        require VIEW_PATH . $filename;

    }

}


if(!function_exists('load404')) {

    function load404() {

        global $conf;

        // コンテンツタイプを強制的に書き換える
        setContentsType();

        if(isset($conf['404']) && !empty($conf['404'])) {
            loadView($conf['404']);
        }

        setStatusHeader(404);

    }
}


if(!function_exists('setStatusHeader')) {
    function setStatusHeader($code = 200, $text = '') {
        $status_code = array(
            200	=> 'OK',
            201	=> 'Created',
            202	=> 'Accepted',
            203	=> 'Non-Authoritative Information',
            204	=> 'No Content',
            205	=> 'Reset Content',
            206	=> 'Partial Content',

            300	=> 'Multiple Choices',
            301	=> 'Moved Permanently',
            302	=> 'Found',
            304	=> 'Not Modified',
            305	=> 'Use Proxy',
            307	=> 'Temporary Redirect',

            400	=> 'Bad Request',
            401	=> 'Unauthorized',
            403	=> 'Forbidden',
            404	=> 'Not Found',
            405	=> 'Method Not Allowed',
            406	=> 'Not Acceptable',
            407	=> 'Proxy Authentication Required',
            408	=> 'Request Timeout',
            409	=> 'Conflict',
            410	=> 'Gone',
            411	=> 'Length Required',
            412	=> 'Precondition Failed',
            413	=> 'Request Entity Too Large',
            414	=> 'Request-URI Too Long',
            415	=> 'Unsupported Media Type',
            416	=> 'Requested Range Not Satisfiable',
            417	=> 'Expectation Failed',

            500	=> 'Internal Server Error',
            501	=> 'Not Implemented',
            502	=> 'Bad Gateway',
            503	=> 'Service Unavailable',
            504	=> 'Gateway Timeout',
            505	=> 'HTTP Version Not Supported'
        );

        if(isset($status_code[$code]) && $text == '') {
            $text = $status_code[$code];
        }

        $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : false;

        if(substr(php_sapi_name(), 0, 3) == 'cgi') {
            header("Status: {$code} {$text}", true);
        } elseif ($server_protocol == 'HTTP/1.1' || $server_protocol == 'HTTP/1.0') {
            header($server_protocol." {$code} {$text}", true, $code);
        } else {
            header("HTTP/1.1 {$code} {$text}", true, $code);
        }
    }
}


if(!function_exists('setContentsType')) {
    function setContentsType($extention = 'html', $mime_type = '') {

        global $conf;

        $mime_code = array(
            // text
            'html'	=> 'text/html',
            'htm'	=> 'text/html',
            'php'   => 'text/html',
            'tex'	=> 'application/x-latex',
            'latex'	=> 'application/x-latex',
            'ltx'	=> 'application/x-latex',
            'pdf'	=> 'application/pdf',
            'ps'	=> 'application/postscript',
            'rtf'	=> 'application/rtf',
            'sgm'	=> 'text/sgml',
            'sgml'	=> 'text/sgml',
            'tab'	=> 'text/tab-separated-values',
            'tsv'	=> 'text/tab-separated-values',
            'txt'	=> 'text/plain',
            'xml'	=> 'application/xml',
            'json'  => 'application/json',
            'yaml'  => 'text/x-yaml',
            'js'    => 'application/javascript',
            'css'   => 'text/css',
            'dart'  => 'application/dart',

            // compression
            'jar'	=> 'application/java-archiver',
            'cpt'	=> 'application/mac-compactpro',
            'gz'	=> 'application/x-gzip',
            'hqx'	=> 'application/mac-binhex40',
            'sh'	=> 'application/x-shar',
            'shar'	=> 'application/x-shar',
            'sit'	=> 'application/x-stuffit',
            'z'	    => 'application/x-compress',
            'zip'	=> 'application/zip',

            // image
            'ai'	=> 'application/postscript',
            'bmp'	=> 'image/x-bmp',
            'rle'	=> 'image/x-bmp',
            'dib'	=> 'image/x-bmp',
            'cgm'	=> 'image/cgm',
            'dwf'	=> 'drawing/x-dwf',
            'epsf'	=> 'appilcation/postscript',
            'eps'	=> 'appilcation/postscript',
            'fif'	=> 'image/fif',
            'fpx'	=> 'image/x-fpx',
            'gif'	=> 'image/gif',
            'jpg'	=> 'image/jpeg',
            'jpeg'	=> 'image/jpeg',
            'jpe'	=> 'image/jpeg',
            'jfif'	=> 'image/jpeg',
            'jfi'	=> 'image/jpeg',
            'pcd'	=> 'image/pcd',
            'pict'	=> 'image/pict',
            'pct'	=> 'image/pict',
            'png'	=> 'image/png',
            'tga'	=> 'image/x-targa',
            'tpic'	=> 'image/x-targa',
            'vda'	=> 'image/x-targa',
            'vst'	=> 'image/x-targa',
            'tiff'	=> 'image/tiff',
            'tif'	=> 'image/tiff',
            'wrl'	=> 'image/vrml',
            'xbm'	=> 'image/x-bitmap',
            'xpm'	=> 'image/x-xpixmap',

            // sound
            'aiff'	=> 'audio/aiff',
            'aif'	=> 'audio/aiff',
            'au'	=> 'audio/basic',
            'kar'	=> 'audio/midi',
            'm1a'	=> 'audio/mpeg',
            'm2a'	=> 'audio/mpeg',
            'midi'	=> 'audio/midi',
            'mid'	=> 'audio/midi',
            'smf'	=> 'audio/midi',
            'mp2'	=> 'audio/mpeg',
            'mp3'	=> 'audio/mpeg',
            'mpa'	=> 'audio/mpeg',
            'mpega'	=> 'audio/mpeg',
            'rpm'	=> 'audio/x-pn-realaudio-plugin',
            'snd'	=> 'audio/basic',
            'swa'	=> 'application/x-director',
            'vqf'	=> 'audio/x-twinvq',
            'wav'	=> 'audio/wav',

            // movie
            'aab'	=> 'application/x-authorware-bin',
            'aam'	=> 'application/x-authorware-map',
            'aas'	=> 'application/x-authorware-seg',
            'asf'	=> 'video/x-ms-asf',
            'avi'	=> 'video/x-msvideo',
            'dcr'	=> 'application/x-director',
            'dir'	=> 'application/x-director',
            'dxr'	=> 'application/x-director',
            'flc'	=> 'video/flc',
            'fli'	=> 'video/flc',
            'mng'	=> 'video/mng',
            'm1s'	=> 'video/mpeg',
            'm1v'	=> 'video/mpeg',
            'm2s'	=> 'video/mpeg',
            'm2v'	=> 'video/mpeg',
            'moov'	=> 'video/quicktime',
            'mov'	=> 'video/quicktime',
            'qt'	=> 'video/quicktime',
            'mpeg'	=> 'video/mpeg',
            'mpg'	=> 'video/mpeg',
            'mpe'	=> 'video/mpeg',
            'mpv'	=> 'video/mpeg',
            'ppt'	=> 'application/ppt',
            'rm'	=> 'audio/x-pn-realaudio',
            'spl'	=> 'application/futuresplash',
            'swf'	=> 'application/x-shockwave-flash',
            'vdo'	=> 'video/vdo',
            'viv'	=> 'video/vnd.vivo',
            'vivo'	=> 'video/vnd.vivo',
            'xdm'	=> 'application/x-xdma',
            'xdma'	=> 'application/x-xdma',

            // other
            'cdf'	=> 'application/x-netcdf',
            'class'	=> 'application/octet-stream',
            'exe'	=> 'application/exe',
            'pl'	=> 'application/x-perl',
            'ram'	=> 'audio/x-pn-realaudio',
            'vdb'	=> 'application/activexdocument',
            'vqe'	=> 'audio/x-twinvq',
            'vql'	=> 'audio/x-twinvq'

        );

        if($mime_type == '') {
            $mime_type = isset($mime_code[$extention]) ? $mime_code[$extention] : 'text/html';
        }

        $encoding = isset($conf['encoding']) ? $conf['encoding'] : 'UTF-8';

        header("Content-Type: {$mime_type}; charset={$encoding}", true);

    }
}