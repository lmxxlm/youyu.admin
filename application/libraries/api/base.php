<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Base
{
    public $CI;
    public $config;
    public $apiparameter;
    public $totalcount;
    public $totalpage;
    public $currentpage;
    private $Exception;
    private $code;
    public $access_id = '';
    public $access_key = '';
    public $des_key = '';
    private $msg = '';
    public $update_start_time = 0;
    public $update_end_time = 0;

    function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->config->load('config_rest', TRUE);
        $this->config = $this->CI->config->item('config_rest');

        $this->CI->config->load('config_api', TRUE);
        $config2 = $this->CI->config->item('config_api');
        $this->config = array_merge($this->config, $config2);

        // $this->CI->config->load('config_sina');
        // $config3 = $this->CI->config->item('config_sina');
        // $this->config = array_merge($this->config, $config3);

        $this->apiparameter = array();
        $this->apiparameter['ip'] = $this->get_ip();
    }

    public function set_apiparameter($key, $value)
    {
        $this->apiparameter[$key] = $value;
    }

    public function getException()
    {
        return $this->Exception;
    }

    public function getcode()
    {
        return $this->code;
    }

    public function getmsg()
    {
        return $this->msg;
    }

    public function setException($message = '', $code = 0)
    {
        $this->Exception = new Exception($message, $code);
    }

    public function get_ip()
    {
        $ip = $this->CI->input->ip_address();
        if ($ip == '::1') {
            $ip = '127.0.0.1';
        }
        return $ip;
    }

    public function getaccessid()
    {
        if ($this->access_id) {
            return $this->access_id;
        }
        if (isset($_SESSION['access_id'])) {
            return $_SESSION['access_id'];
        }
        return $this->config['access_id'];
    }

    public function getaccesskey()
    {
        if ($this->access_key) {
            return $this->access_key;
        }
        if (isset($_SESSION['access_key'])) {
            return $_SESSION['access_key'];
        }
        return $this->config['access_key'];
    }

    public function getdeskey()
    {
        if ($this->des_key) {
            return $this->des_key;
        }
        if (isset($_SESSION['des_key'])) {
            return $_SESSION['des_key'];
        }
        return $this->config['des_key'];
    }

    public function get_totalcount()
    {
        return $this->totalcount;
    }

    public function get_totalpage()
    {
        return $this->totalpage;
    }

    public function get_currentpage()
    {
        return $this->currentpage;
    }

    public function set_page_info($result)
    {
        $pageinfo = sub_value('pageinfo', $result);
        $this->totalcount = sub_value('totalcount', $pageinfo);
        $this->totalpage = sub_value('totalpage', $pageinfo);
        $this->currentpage = sub_value('currentpage', $pageinfo);
    }

    public function export($apiname, $contenttag)
    {
        $content = array();
        if ($this->totalpage > 1) {
            for ($i = 2; $i <= $this->totalpage; $i++) {
                $this->apiparameter['currentpage'] = $i;
                $rt = $this->send($apiname, $this->apiparameter);
                $rt = sub_value($contenttag, $rt);
                $content = array_merge($content, $rt);
            }
        }
        return $content;
    }

    //DES加密
    public function des_encrypt($value)
    {
        $this->CI->load->library('lib/crypt_des', 'crypt_des');
        $this->CI->crypt_des->set_key($this->getdeskey());//调用自己的Key
        $this->CI->crypt_des->set_iv(array(0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF));
        $result = $this->CI->crypt_des->encrypt($value);

        return $result;
    }

    //DES解密
    public function des_decrypt($value)
    {
        // log_message('info',$this->getdeskey());
        $this->CI->load->library('lib/crypt_des', 'crypt_des');
        $this->CI->crypt_des->set_key($this->getdeskey());//调用自己的Key
        $this->CI->crypt_des->set_iv(array(0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF));
        $result = $this->CI->crypt_des->decrypt($value);
        return $result;
    }

    /**
     * 向服务器发送请求
     * @param  string $apiname 接口名称
     * @param  array $params 接口参数
     * @param  string $accesskey 签名密钥
     * @param  bool $downloadflg 下载标志
     * @return string            请求结果content数组
     */
    public function send($apiname, $params, $accesskey = '', $downloadflg = false)
    {
        if (!$accesskey) {
            $accesskey = $this->getaccesskey();
        }
        $params['accessid'] = $this->getaccessid();
        add_log("apilog", 'base send key:' . $accesskey);

        $format = $this->config['api_request_format'];
        $url = $this->config['api_server_url'];

        $reqtime = date("YmdHis");
        $ip = $this->CI->input->ip_address();
        if ($ip == '::1') {
            $ip = '127.0.0.1';
        }
        if (isset($params['trackid'])) {
            $trackid = $params['trackid'];
        } else {
            $trackid = get_trackid_1();
        }

        $ci =& get_instance();
        $ci->load->library('lib/system_config');
        $version = $ci->system_config->get_value('con_version');

        $common = array('action' => $apiname, 'reqtime' => $reqtime, 'version' => $version, 'ip' => $ip, 'trackid' => $trackid, 'device_port' => 'pc');
        $request = array('common' => $common, 'content' => $params);
        $arrbody = array('request' => $request);

        $body = json_encode($arrbody);

        add_log("apilog", "请求数据【" . $body . "】");

        $head = array('format:' . $format, 'reqlength:' . strlen($body), 'sign:' . sign($body, $accesskey));

        //如果是下载标志，则下载
        if ($downloadflg) {
            return curl_download($url, $body, $head);
        }

        $data = curl_send($url, $method = 'POST', $body, $head);
        if (!$data) {
            $this->setexception('服务连接失败');
            return false;
        }

        add_log("apilog", 'ip:[' . get_remote_ip() . ']' . (isset($_SESSION['phone']) ? "[" . $_SESSION['phone'] . "]" : "") . ",access_id:[" . $this->getaccessid() . "]---调用【" . $apiname . "】后返回结果：【" . $data . "】");

		$data = json_decode($data, TRUE);
        $code = $data['response']['info']['code'];
        $msg = $data['response']['info']['msg'];

        $this->code = $code;
		$this->msg = $msg;
		
        // $this->update_start_time = $data['response']['info']['maintain_starttime'];
        // $this->update_end_time = $data['response']['info']['maintain_endtime'];

        // $this->access_key = "";
        // $this->access_id = "";

        // $now = time();

        // if ($code != 100000) {
        //     if (!isset($params['is_ajax']) || $params['is_ajax'] == 0) { //ajax请求，直接返回错误代码
        //         if ($code >= 110023 && $code <= 110026) { //登陆了超时，重新登录
        //             redirect(base_url('/user/login.html'));
        //         } else if ($code == 300002) { //未实名认证
        //             redirect(base_url("/mine/user_center/safety?sina-account"));
        //         } else if ($code == 110019) {
        //             unset($_SESSION['access_id']);
        //             unset($_SESSION['access_key']);
        //             unset($_SESSION['des_key']);
        //             unset($_SESSION['user_id']);
        //             unset($_SESSION['username']);
        //             redirect(base_url($_SERVER['REQUEST_URI']));
        //         } else if ($code == 930019) { //新浪服务挂掉
        //             redirect(base_url("/user/sina_error"));//展示错误信息，提醒用户服务不可用
        //         } else if ($code == SYSTEM_UPDATE) {
        //             redirect(base_url('/system/upgrade'));
        //         } else if ($now >= $this->update_start_time && $now <= $this->update_end_time) { //系统维护中...
        //             redirect(base_url('/system/upgrade'));
        //         }
        //     }
        //     $this->setexception($msg, $code);
        //     $this->code = $code;
        //     $this->msg = $msg;
        //     return false;
        // } else {
        //     if ($now >= $this->update_start_time && $now <= $this->update_end_time) { //系统维护中...
        //         redirect(base_url('/system/upgrade'));
        //     }
        // }
        // if (isset($data['response']['content'])) {
        //     return $data['response']['content'];
        // }
        // return true;
    }

    /**
     * 向weixin服务器发送请求
     * @param  string $apiname 接口名称
     * @param  array $params 接口参数
     * @param  string $accesskey 签名密钥
     * @param  bool $downloadflg 下载标志
     * @return string   请求结果content数组
     */
    public function sendweixin($apiname, $params, $accesskey = '', $downloadflg = false)
    {
        if (!$accesskey) {
            $accesskey = $this->getaccesskey();
        }

        add_log("apilog", 'sendweixin base send key:' . $accesskey);
        $format = $this->config['api_request_format'];
        $url = $this->config['api_server_url_weixin'];
        /**
         * 兼容二期API
         */
        if (isset($params['version'])) {
            $version = $params['version'];
        } else {
            $version = $this->config['api_server_version'];
        }

        $reqtime = date("YmdHis");
        $common = array('action' => $apiname, 'reqtime' => $reqtime, 'version' => $version);
        $request = array('common' => $common, 'content' => $params);
        $arrbody = array('request' => $request);

        $body = json_encode($arrbody);

        add_log("apilog", "sendweixin 请求数据【" . $body . "】");

        $head = array('format:' . $format, 'reqlength:' . strlen($body), 'sign:' . sign($body, $accesskey));

        //如果是下载标志，则下载
        if ($downloadflg) {
            return curl_download($url, $body, $head);
        }

        $data = curl_send($url, $method = 'POST', $body, $head);
        if (!$data) {
            $this->setexception('服务连接失败');
            return false;
        }

        add_log("apilog", "sendweixin 调用API后返回数据：【" . $data . "】");

        $data = json_decode($data, TRUE);
        $code = $data['response']['info']['code'];
        $msg = $data['response']['info']['msg'];
        $this->code = $code;
        $this->msg = $msg;

        if ($code != 100000) {
            if (!isset($params['is_ajax']) || $params['is_ajax'] == 0) { //ajax请求，直接返回错误代码
                if ($code >= 110023 && $code <= 110026) { //登陆了超时，重新登录
                    redirect(base_url('/user/login.html'));
                } else if ($code == 300002) { //未实名认证
                    redirect(base_url("/mine/user_center/safety?sina-account"));
                } else if ($code == 110019) {
                    unset($_SESSION['access_id']);
                    unset($_SESSION['access_key']);
                    unset($_SESSION['des_key']);
                    unset($_SESSION['user_id']);
                    unset($_SESSION['username']);
                    redirect(base_url($_SERVER['REQUEST_URI']));
                } else if ($code == 930019) { //新浪服务挂掉
                    redirect(base_url("/user/sina_error"));//展示错误信息，提醒用户服务不可用
                }
            }
            $this->setexception($msg, $code);
            $this->code = $code;
            $this->msg = $msg;
            return false;
        }
        if (isset($data['response']['content'])) {
            return $data['response']['content'];
        }
        return true;
    }
}