<?php
defined('BASEPATH') or die('No direct script access allowed');

//登录签名
function sign_trj_login($param,$key){
    ksort($param);
    $param = http_build_query($param);
    return md5($param.'&'.$key);
}

//api签名
function sign_api($param,$key){
    ksort($param);
    $param = http_build_query($param);
    return md5($param.'&'.$key);
}

//前面方法
function sign($input, $key='')
{
    $output = md5($input);
    return md5($output.$key);
    /*if (!empty($key)) {
        $output = get_sha1($output,$key);
    }
    return urlencode($output);*/
}
