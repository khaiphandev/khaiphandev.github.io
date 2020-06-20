<?php
header("Content-Type: text/plain");
if (isset($_GET['id'])) {
    $source = get_web_page('https://live.90p.tv/' . $_GET['id'] . '.html');
    preg_match('/\[\{file\: "(.*?)"\}\]/', $source['content'], $stream);
    if (!isset($stream['1'])) die('Failed');
    header("Location: " . $stream['1']);
    exit;
}

$source = get_web_page('https://live.90p.tv');

preg_match_all('/\<a href\="\/[a-z0-9]+\.html" class\="item.*?" title\=""\>.*?\<\/script\>/s', $source['content'], $list);


$data = array();
foreach($list['0'] as $row) {
    preg_match('/\<div class\="title"\>(.*?)\<\/div\>/s', $row, $title);
    preg_match('/data\-time\="(.*?)"/', $row, $time);
    preg_match('/href\="\/(.*?)\.html"/', $row, $id);
    $data[] = array (
        'id' => $id['1'],
        'name' => date("H:i - d/m ", strtotime($time['1'])) . remove_line($title['1']),
        );
}

echo "#EXTM3U\r\n";
echo "\r\n# Listed by Khai Phan\r\n";
foreach($data as $m3u) {
    $name = $m3u['name'];
    $link = "http://localhost:8000/90m.php?id=" . $m3u['id'];
    echo "\r\n#EXTINF:-1, $name\r\n$link";
}

function remove_line($str) {
    $str = str_replace("\n", "", $str);
    $str = str_replace("\r", "", $str);
    return trim($str);
}

function get_web_page( $url )
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "Mozilla/5.0 (Linux; Android 9; LGM-V300L) AppleWebKit/537.36 (KHTML, like Gecko) coc_coc_browser/83.0.311 Mobile Chrome/77.0.3865.311 Mobile Safari/537.36", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
        CURLOPT_REFERER        => 'https://live.90p.tv/',
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}