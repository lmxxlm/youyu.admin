<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 网络请求
 * @param $url
 * @param string $method
 * @param array $vars
 * @param array $head
 * @return mixed
 */
function curl_send($url, $method = '', $vars = array(), $head = array())
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if ($head) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    }
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
    }
    $file_contents = curl_exec($ch);
    $response = curl_getinfo($ch);
    if ($response['http_code'] != 200) {
        log_message('error', 'curl_send 28:'.$url . ':' . $vars . ', message:' . curl_error($ch).',code:'.$response['http_code']);
    }
    curl_close($ch);
    return $file_contents;
}

/**
 * 下载
 * @param $url
 * @param array $vars
 * @param array $head
 * @return bool|mixed
 */
function curl_download($url, $vars = array(), $head = array())
{
    function is_success($ch, $str)
    {
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code != 200) {
            show_404();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_close($ch);
            return 0;
        }
        return strlen($str);
    }

    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, 'is_success');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 10);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        curl_exec($ch);
        curl_close($ch);
    } catch (Exception $e) {
        log_message("info", json_encode($e));
    }
}
