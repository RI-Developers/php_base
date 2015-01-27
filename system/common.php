<?php
/**
 * User: Tsuguya Touma
 * Date: 15/01/15
 */

if(!function_exists('loadView')) {
    function loadView($filename, $load = false, $vars = null) {

        if(!fileCheck(VIEW_PATH . $filename)) {
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

if(!function_exists('loadRange')) {
    function loadRange($filename) {

        if(!fileCheck(VIEW_PATH . $filename)) {
            load404();
            return;
        }

        if (isset($_SERVER['HTTP_RANGE'])) {
            rangeDownload(VIEW_PATH . $filename);
        } else {
            header('Content-Length: ' . filesize(VIEW_PATH . $filename));

            $handle = fopen(VIEW_PATH . $filename, 'rb');
            while(!feof($handle)) {
                echo fread($handle, 4096);
                ob_flush();
                flush();
            }
            fclose($handle);
        }

    }
}

if(!function_exists('load404')) {
    function load404() {

        global $conf;

        // コンテンツタイプを強制的に書き換える
        setContentsType();
        setStatusHeader(404);

        // 無限ループ防止
        if(!empty($conf['404']) && file_exists(VIEW_PATH . $conf['404'])) {
            loadView($conf['404']);
        }

    }
}

if(!function_exists('fileCheck')) {
    function fileCheck($path) {
        $real_path = realpath($path);

        if($real_path === false || !file_exists($path)) {
            return false;
        }

        if(strstr($real_path, realpath(BASE_PATH)) === false) {
            return false;
        }

        return true;
    }
}


if(!function_exists('setStatusHeader')) {
    function setStatusHeader($code = 200, $text = '') {
        $status_code = array(
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',

            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',

            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',

            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
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
            'html'  => array('text/html',                       'text'),
            'htm'   => array('text/html',                       'text'),
            'php'   => array('text/html',                       'text'),
            'tex'   => array('application/x-latex',             'text'),
            'latex' => array('application/x-latex',             'text'),
            'ltx'   => array('application/x-latex',             'text'),
            'pdf'   => array('application/pdf',                 'text'),
            'ps'    => array('application/postscript',          'text'),
            'rtf'   => array('application/rtf',                 'text'),
            'sgm'   => array('text/sgml',                       'text'),
            'sgml'  => array('text/sgml',                       'text'),
            'tab'   => array('text/tab-separated-values',       'text'),
            'tsv'   => array('text/tab-separated-values',       'text'),
            'txt'   => array('text/plain',                      'text'),
            'xml'   => array('application/xml',                 'text'),
            'json'  => array('application/json',                'text'),
            'yaml'  => array('text/x-yaml',                     'text'),
            'js'    => array('application/javascript',          'text'),
            'css'   => array('text/css',                        'text'),
            'dart'  => array('application/dart',                'text'),
            'rss'   => array('application/rss+xml',             'text'),

            // compression
            'jar'   => array('application/java-archiver',       'compression'),
            'cpt'   => array('application/mac-compactpro',      'compression'),
            'gz'    => array('application/x-gzip',              'compression'),
            'hqx'   => array('application/mac-binhex40',        'compression'),
            'sh'    => array('application/x-shar',              'compression'),
            'shar'  => array('application/x-shar',              'compression'),
            'sit'   => array('application/x-stuffit',           'compression'),
            'z'     => array('application/x-compress',          'compression'),
            'zip'   => array('application/zip',                 'compression'),

            // image
            'ai'    => array('application/postscript',          'image'),
            'bmp'   => array('image/x-bmp',                     'image'),
            'rle'   => array('image/x-bmp',                     'image'),
            'dib'   => array('image/x-bmp',                     'image'),
            'cgm'   => array('image/cgm',                       'image'),
            'dwf'   => array('drawing/x-dwf',                   'image'),
            'epsf'  => array('appilcation/postscript',          'image'),
            'eps'   => array('appilcation/postscript',          'image'),
            'fif'   => array('image/fif',                       'image'),
            'fpx'   => array('image/x-fpx',                     'image'),
            'gif'   => array('image/gif',                       'image'),
            'jpg'   => array('image/jpeg',                      'image'),
            'jpeg'  => array('image/jpeg',                      'image'),
            'jpe'   => array('image/jpeg',                      'image'),
            'jfif'  => array('image/jpeg',                      'image'),
            'jfi'   => array('image/jpeg',                      'image'),
            'pcd'   => array('image/pcd',                       'image'),
            'pict'  => array('image/pict',                      'image'),
            'pct'   => array('image/pict',                      'image'),
            'png'   => array('image/png',                       'image'),
            'tga'   => array('image/x-targa',                   'image'),
            'tpic'  => array('image/x-targa',                   'image'),
            'vda'   => array('image/x-targa',                   'image'),
            'vst'   => array('image/x-targa',                   'image'),
            'tiff'  => array('image/tiff',                      'image'),
            'tif'   => array('image/tiff',                      'image'),
            'wrl'   => array('image/vrml',                      'image'),
            'xbm'   => array('image/x-bitmap',                  'image'),
            'xpm'   => array('image/x-xpixmap',                 'image'),
            'ico'   => array('image/vnd.microsoft.icon',        'image'),
            // font(区分は画像に入れておく)
            'woff'   => array('application/font-woff',          'image'),
            'ttf'   =>  array('application/x-font-ttf',         'image'),
            'otf'   =>  array('application/x-font-otf',         'image'),
            'svgf'   => array('image/svg+xml',                  'image'),
            'eot'   =>  array('application/vnd.ms-fontobject',  'image'),

            // sound
            'aiff'  => array('audio/aiff',                       'sound'),
            'aif'   => array('audio/aiff',                       'sound'),
            'au'    => array('audio/basic',                      'sound'),
            'kar'   => array('audio/midi',                       'sound'),
            'm1a'   => array('audio/mpeg',                       'sound'),
            'm2a'   => array('audio/mpeg',                       'sound'),
            'm4a'   => array('audio/aac',                        'sound'),
            'midi'  => array('audio/midi',                       'sound'),
            'mid'   => array('audio/midi',                       'sound'),
            'smf'   => array('audio/midi',                       'sound'),
            'mp2'   => array('audio/mpeg',                       'sound'),
            'mp3'   => array('audio/mpeg',                       'sound'),
            'mpa'   => array('audio/mpeg',                       'sound'),
            'mpega' => array('audio/mpeg',                       'sound'),
            'rpm'   => array('audio/x-pn-realaudio-plugin',      'sound'),
            'snd'   => array('audio/basic',                      'sound'),
            'swa'   => array('application/x-director',           'sound'),
            'vqf'   => array('audio/x-twinvq',                   'sound'),
            'wav'   => array('audio/wav',                        'sound'),
            'ogg'   => array('audio/ogg',                        'sound'),
            'ra'    => array('audio/vnd.rn-realaudio',           'sound'),

            // movie
            'aab'   => array('application/x-authorware-bin',     'movie'),
            'aam'   => array('application/x-authorware-map',     'movie'),
            'aas'   => array('application/x-authorware-seg',     'movie'),
            'asf'   => array('video/x-ms-asf',                   'movie'),
            'avi'   => array('video/x-msvideo',                  'movie'),
            'dcr'   => array('application/x-director',           'movie'),
            'dir'   => array('application/x-director',           'movie'),
            'dxr'   => array('application/x-director',           'movie'),
            'flc'   => array('video/flc',                        'movie'),
            'fli'   => array('video/flc',                        'movie'),
            'mng'   => array('video/mng',                        'movie'),
            'm1s'   => array('video/mpeg',                       'movie'),
            'm1v'   => array('video/mpeg',                       'movie'),
            'm2s'   => array('video/mpeg',                       'movie'),
            'm2v'   => array('video/mpeg',                       'movie'),
            'moov'  => array('video/quicktime',                  'movie'),
            'mov'   => array('video/quicktime',                  'movie'),
            'qt'    => array('video/quicktime',                  'movie'),
            'mpeg'  => array('video/mpeg',                       'movie'),
            'mpg'   => array('video/mpeg',                       'movie'),
            'mpe'   => array('video/mpeg',                       'movie'),
            'mpv'   => array('video/mpeg',                       'movie'),
            'ppt'   => array('application/ppt',                  'movie'),
            'rm'    => array('audio/x-pn-realaudio',             'movie'),
            'spl'   => array('application/futuresplash',         'movie'),
            'swf'   => array('application/x-shockwave-flash',    'movie'),
            'vdo'   => array('video/vdo',                        'movie'),
            'viv'   => array('video/vnd.vivo',                   'movie'),
            'vivo'  => array('video/vnd.vivo',                   'movie'),
            'xdm'   => array('application/x-xdma',               'movie'),
            'xdma'  => array('application/x-xdma',               'movie'),
            'mp4'   => array('video/mp4',                        'movie'),
            'ogv'   => array('video/ogg',                        'movie'),
            'webm'  => array('video/webm',                       'movie'),

            // other
            'cdf'   => array('application/x-netcdf',             'other'),
            'class' => array('application/octet-stream',         'other'),
            'exe'   => array('application/exe',                  'other'),
            'pl'    => array('application/x-perl',               'other'),
            'ram'   => array('audio/x-pn-realaudio',             'other'),
            'vdb'   => array('application/activexdocument',      'other'),
            'vqe'   => array('audio/x-twinvq',                   'other'),
            'vql'   => array('audio/x-twinvq',                   'other'),
            'doc'   => array('application/msword',               'other'),
            'xls'   => array('application/msexcel',              'other')

        );

        if($mime_type == '') {
            if(!isset($mime_code[$extention])) {
                $extention = 'php';
            }

            $mime_type = $mime_code[$extention][0];
        }

        $encoding = $conf['encoding'];

        if($mime_code[$extention][1] === 'text') {
            header("Content-Type: {$mime_type}; charset={$encoding}", true);
        } else {
            header("Content-Type: {$mime_type}", true);
        }

        return $mime_code[$extention][1];

    }

}

// code: http://mobiforge.com/design-development/content-delivery-mobile-devices
if(!function_exists('rangeDownload')) {
    function rangeDownload($file) {

        $fp = @fopen($file, 'rb');

        $size   = filesize($file); // File size
        $length = $size;           // Content length
        $start  = 0;               // Start byte
        $end    = $size - 1;       // End byte
        // Now that we've gotten so far without errors we send the accept range header
        /* At the moment we only support single ranges.
         * Multiple ranges requires some more work to ensure it works correctly
         * and comply with the spesifications: http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.2
         *
         * Multirange support annouces itself with:
         * header('Accept-Ranges: bytes');
         *
         * Multirange content must be sent with multipart/byteranges mediatype,
         * (mediatype = mimetype)
         * as well as a boundry header to indicate the various chunks of data.
         */
        header("Accept-Ranges: 0-$length");
        // header('Accept-Ranges: bytes');
        // multipart/byteranges
        // http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.2
        if (isset($_SERVER['HTTP_RANGE'])) {

            $c_start = $start;
            $c_end   = $end;
            // Extract the range string
            list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
            // Make sure the client hasn't sent us a multibyte range
            if (strpos($range, ',') !== false) {

                // (?) Shoud this be issued here, or should the first
                // range be used? Or should the header be ignored and
                // we output the whole content?
                setStatusHeader(416);
                header("Content-Range: bytes $start-$end/$size");
                // (?) Echo some info to the client?
                exit;
            }
            // If the range starts with an '-' we start from the beginning
            // If not, we forward the file pointer
            // And make sure to get the end byte if spesified
            if ($range[0] == '-') {

                // The n-number of the last bytes is requested
                $c_start = $size - substr($range, 1);
            }
            else {

                $range  = explode('-', $range);
                $c_start = $range[0];
                $c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
            }
            /* Check the range and make sure it's treated according to the specs.
             * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html
             */
            // End bytes can not be larger than $end.
            $c_end = ($c_end > $end) ? $end : $c_end;
            // Validate the requested range and return an error if it's not correct.
            if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                setStatusHeader(416);
                header("Content-Range: bytes $start-$end/$size");
                // (?) Echo some info to the client?
                exit;
            }
            $start  = $c_start;
            $end    = $c_end;
            $length = $end - $start + 1; // Calculate new content length
            fseek($fp, $start);
            setStatusHeader(206);
        }
        // Notify the client the byte range we'll be outputting
        header("Content-Range: bytes $start-$end/$size");
        header("Content-Length: $length");

        // Start buffered download
        $buffer = 1024 * 8;
        while(!feof($fp) && ($p = ftell($fp)) <= $end) {

            if ($p + $buffer > $end) {

                // In case we're only outputtin a chunk, make sure we don't
                // read past the length
                $buffer = $end - $p + 1;
            }
            set_time_limit(0); // Reset time limit for big files
            echo fread($fp, $buffer);
            flush(); // Free up memory. Otherwise large files will trigger PHP's memory limit.
        }
        fclose($fp);

    }
}

if(!function_exists('addCacheControl')) {

    function addCacheeControl($type, $content_type = null) {
        if(empty($type) || $type === 'auto') {
            if(function_exists('header_remove')) {
                header_remove('Pragma');
            } else {
                header('Pragma: ');
            }
            return;
        }

        if(gettype($type) === 'array' && !empty($content_type) && isset($type[$content_type])) {
            addCacheeControl($type[$content_type]);
            return;
        }

        switch($type) {
            case 'no-cache':
                header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                header('Cache-Control: no-store, no-cache, must-revalidate');
                header('Cache-Control: post-check=0, pre-check=0', false);
                header('Pragma: no-cache');
                break;
            default:

        }


    }
}

if(!function_exists('htmlEscape')) {

    function htmlEscape($string, $double = false, $encoding = null) {
        global $conf;
        if(empty($encoding)) {
            $encoding = $conf['encoding'];
        }

        return htmlspecialchars($string, ENT_QUOTES, $encoding, $double);
    }

}

if(!function_exists('isSsl')) {

    function isSsl() {
        if (isset($_SERVER['HTTPS'])) {
            return ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === '1');
        }
        return false;
    }
}