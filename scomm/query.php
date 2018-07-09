<?php
if (!(defined('WICHAT_WEB_ROOT'))) //Fatal Error
{
    exit(0);
}
require_once(WICHAT_WEB_ROOT.'/scomm/config.php');

function queryAccountServer($action, $data)
{
    include_once(WICHAT_WEB_ROOT.'/include/lib/enc.php');
    $url='http://'.ACCOUNT_WEB_ROOT.'/scomm/web.php';
    $params=array('a' => $action,
                  'c' => aes_encrypt(crc32sum($data).$data, ACCOUNT_SCOMM_WEB_KEY));
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($params)
    ));
    $sf=file_get_contents($url,false,stream_context_create($options));
    if (substr($sf,0,SERVER_RESPONSE_HEADER_LEN)!=SERVER_RESPONSE_HEADER) return '';
    
    // Pharse response from account server
    $content=aes_decrypt(substr($sf,SERVER_RESPONSE_HEADER_LEN),ACCOUNT_SCOMM_WEB_KEY);
    if (substr($content,0,4)!=crc32sum(substr($content,4))) return '';
    else return substr($content,4);
}
?>
