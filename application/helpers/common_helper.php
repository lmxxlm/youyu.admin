<?php
defined('BASEPATH') or die('No direct script access allowed');

// 计算中文字符串长度
function utf8_strlen($string = null)
{
// 将字符串分解为单元
    preg_match_all("/./us", $string, $match);
// 返回单元个数
    return count($match[0]);
}

//是否是移动手机号
function is_mobile($str, $is_more = FALSE)
{
    if ($is_more) {
        $_str = explode(",", $str);
        $count = count($_str);
        for ($i = 0; $i < $count; $i++) {
            if (!is_mobile($_str[$i])) return false;
        }
        return true;
    } else {
        return (preg_match('/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}|17[0-9]{9}$|18[0-9]{9}$/', $str) ? true : false);
    }
}

function func_mobile($str)
{
    $exp = "/^13[0-9]{1}[0-9]{8}$|15[012356789]{1}[0-9]{8}$|17[0-9]{9}$|18[012356789]{1}[0-9]{8}$|14[57]{1}[0-9]$/";
    if(preg_match($exp,$str)){
        return true;
    }else{
        return false;
    }
}

//判断是否是邮箱
function is_realname($str)
{
    return (preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $str) ? true : false);
}

//判断是否是邮箱
function is_email($str)
{
    return (preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $str) ? true : false);
}

//金额格式判断
function is_money($str)
{
    return (preg_match('/^([1-9][\d]{0,7}|0)(\.[\d]{1,2})?$/', $str) ? true : false);
}

//手机版截取标题
function mobile_subtitle($title)
{
    $str = $title;
    preg_match("/【(.+?)】/", $str, $matches);
    if (isset($matches[1]) && !empty($matches[1])) {
        return $matches[1];
    }
    return $title;
}

//字符串截长
function substr_cut($str_cut, $length, $sl = '...')
{
    $str_cut = strip_tags($str_cut);
    $return = '';
    if (strlen($str_cut) > $length) {
        $return = mb_substr($str_cut, 0, $length) . $sl;
    } else {
        $return = $str_cut;
    }
    return $return;
}

function substr_mid($str, $begin, $end)
{
    $s = $str;
    $a = $begin;
    $b = $end;
    return substr($s, strpos($s, $a) + strlen($a), strpos($s, $b) - strpos($s, $a) - strlen($a));
}

//隐藏银行卡号
function bank_account_hidden($bank_account)
{
    return substr($bank_account, 0, 4) . "****" . substr($bank_account, strlen($bank_account) - 4);
}


//字符串显示隐藏
function echo_hidden($string, $start, $end)
{
//    return str_replace(mb_substr($string,$start,$end), str_repeat("*", mb_strlen(mb_substr($string,$start,$end))), $string);
    $str = mb_substr($string, 0, $start);
    $i = $end;
    $real_len = mb_strlen($string);
    if ($start + $end > $real_len) {
        $i = $i - 1;
    }
    while ($i > 0) {
        $str = $str . '*';
        $i = $i - 1;
    }
    $str = $str . mb_substr($string, $start + $end, mb_strlen($string));
    return $str;
}

/**
 * 隐藏昵称
 * @param string $nick_name
 * @return string
 */
function nickname_hidden($nick_name = '')
{
    $temp = '';
    if (!empty($nick_name)) {
        $length = mb_strlen($nick_name);
        if ($length < 7) {
            $temp = mb_substr($nick_name, 0, 1) . "****";
        } else {
            $temp = mb_substr($nick_name, 0, 1) . '****' . mb_substr($nick_name, $length - 1, 1);
        }
    }
    return $temp;
}

//金额格式化
function user_money_format($money, $dic = 0)
{
    if (empty($money) || $money == null) {
        $money = 0;
    }
    return number_format($money, $dic, '.', ',');
}

//登录密码处理
function login_pwd_handel($password)
{
    return md5("&*()" . $password . "!@#$%^");
}

//支付密码
function pay_pwd_handel($password)
{
    return md5($password);
}


//正则替换文章中的图片
function pic_replace($str, $pic_url)
{
    $rs = preg_replace('#src="/#is', 'src="' . $pic_url, $str);
    return preg_replace('#href="/#is', 'href="' . $pic_url, $rs);
}

//短链
function url_short($url)
{
    $url = crc32($url);
    $result = sprintf("%u", $url);
    $sUrl = '';
    while ($result > 0) {
        $s = $result % 62;
        if ($s > 35) {
            $s = chr($s + 61);
        } elseif ($s > 9 && $s <= 35) {
            $s = chr($s + 55);
        }
        $sUrl .= $s;
        $result = floor($result / 62);
    }
    return $sUrl;
}

//安全等级计算
function safe_level($data = array())
{
    $speed = 0;
    //计算百分比
    if ($data['real_status']) {
        $speed += 17;
    }
    if ($data['phone_status']) {
        $speed += 17;
    }
    if ($data['email_status']) {
        $speed += 17;
    }

    $ci = &get_instance();
    if ($data['is_payment_password'] == 1) {
        $speed += 17;
    }
    if ($data['paypwd_status']) { //支付密码
        $speed += 17;
    }


    if (!empty($data['nick_name'])) { //昵称
        $speed += 17;
    }
    if ($data['address_exists'] == 1) {
        $speed += 17;
    }
    if ($speed <= 51) {
        $lv = USER_LEVEL_LOW;
    } elseif ($speed <= 85) {
        $lv = USER_LEVEL_MID;
    } else {
        $lv = USER_LEVEL_HEIGH;
    }
    $user['speed'] = $speed;
    $user['level'] = $lv;
    return $user;
}

//总页数
function total_pages($total_rows = 0, $per_page = 0)
{
    if ($total_rows === 0) {
        return 1;
    }
    return ($total_rows % $per_page === 0) ? (floor($total_rows / $per_page)) : (floor($total_rows / $per_page) + 1);
}

//当地IP对应的地区数组
function local_area_array($ipaddress)
{
    $curl_post = '&format=json&ip=' . $ipaddress;
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://int.dpool.sina.com.cn/iplookup/iplookup.php');
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, 500);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_post);
    $data = trim(curl_exec($ch));
    curl_close($ch);

    if (strlen($data) <= 1) {
        return '';
    }
    //地区返回
    $area = json_decode($data);
    $result = array();
    if (!empty($area->country)) {
        $result['country'] = $area->country;
    }
    if (!empty($area->province)) {
        $result['province'] = $area->province;
    }
    if (!empty($area->city)) {
        $result['city'] = $area->city;
    }
    return $result;
}

//验证码
function valicode()
{
    $width = 70;
    $height = 40;
    $rand_str = "";
    for ($i = 0; $i < 4; $i++) {
        $rand_str .= chr(mt_rand(48, 57));
    }

    $_SESSION['valicode'] = $rand_str;
    // 设置图片大小
    $img = imagecreate($width, $height);//生成图片
    imagecolorallocate($img, 255, 255, 255);  //图片底色，ImageColorAllocate第1次定义颜色PHP就认为是底色了

    // 设置颜色
    $black = imagecolorallocate($img, 127, 157, 185);

    for ($i = 1; $i <= 50; $i++) { //背景显示雪花的效果
        imagestring($img, 1, mt_rand(1, $width), mt_rand(1, $height), "#", imagecolorallocate($img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255)));
    }

    for ($i = 0; $i < 4; $i++) { //加入文字
        imagestring($img, mt_rand(30, 35), $i * 10 + 15, mt_rand(10, 15), $rand_str[$i], imagecolorallocate($img, mt_rand(0, 100), mt_rand(0, 150), mt_rand(0, 200)));
    }

    imagerectangle($img, 0, 0, $width - 1, $height - 1, $black);//先成一黑色的矩形把图片包围

    if (function_exists("imagejpeg")) {
        header("content-type:image/jpeg\r\n");
        imagejpeg($img);
    } else {
        header("content-type:image/png\r\n");
        imagepng($img);
    }
    imagedestroy($img);
}

/*/
# CopyRight: zxing
# Document: 检查符合 GB11643-1999 标准的身份证号码的正确性
# File:gb11643_1999.func.php Fri Mar 28 09:42:41 CST 2008 zxing
# Updated:Fri Mar 28 09:42:41 CST 2008
# Note: 调用函数 check_id();
#/*///

#/*/
/*/
# 函数功能：计算身份证号码中的检校码
# 函数名称：idcard_verify_number
# 参数表 ：string $idcard_base 身份证号码的前十七位
# 返回值 ：string 检校码
# 更新时间：Fri Mar 28 09:50:19 CST 2008
/*/
function idcard_verify_number($idcard_base)
{
    if (strlen($idcard_base) != 17) {
        return false;
    }
    $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); //debug 加权因子
    $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); //debug 校验码对应值
    $checksum = 0;
    for ($i = 0; $i < strlen($idcard_base); $i++) {
        $checksum += substr($idcard_base, $i, 1) * $factor[$i];
    }
    $mod = $checksum % 11;
    $verify_number = $verify_number_list[$mod];
    return $verify_number;
}

//获得身份证的有效位数
function idcard_digits($card_id, $card_id_num)
{
    $cid = '';
    if (strlen($card_id) == $card_id_num) {
        $cid = $card_id;
    } else if ($card_id_num == 15) {
        $cid = idcard_18to15($card_id);
    } else {
        $cid = idcard_15to18($card_id);
    }
    return $cid;
}

/*/
# 函数功能：将15位身份证升级到18位
# 函数名称：idcard_15to18
# 参数表 ：string $idcard 十五位身份证号码
# 返回值 ：string
# 更新时间：Fri Mar 28 09:49:13 CST 2008
/*/
function idcard_15to18($idcard)
{
    if (strlen($idcard) != 15) {
        return false;
    } else {// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
        if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false) {
            $idcard = substr($idcard, 0, 6) . '18' . substr($idcard, 6, 9);
        } else {
            $idcard = substr($idcard, 0, 6) . '19' . substr($idcard, 6, 9);
        }
    }
    $idcard = $idcard . idcard_verify_number($idcard);
    return $idcard;
}

/**
 * 身份证18位转15位
 */
function idcard_18to15($idcard)
{
    if (strlen($idcard) != 18) {
        return false;
    } else {
        $idcard = substr($idcard, 0, 6) . substr($idcard, 8, 9);
    }
    return $idcard;
}

/*/
# 函数功能：18位身份证校验码有效性检查
# 函数名称：idcard_checksum18
# 参数表 ：string $idcard 十八位身份证号码
# 返回值 ：bool
# 更新时间：Fri Mar 28 09:48:36 CST 2008
/*/
function idcard_checksum18($idcard)
{
    if (strlen($idcard) != 18) {
        return false;
    }
    $idcard_base = substr($idcard, 0, 17);
    if (idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))) {
        return false;
    } else {
        return true;
    }
}

/*/
# 函数功能：身份证号码检查接口函数
# 函数名称：check_id
# 参数表 ：string $idcard 身份证号码
# 返回值 ：bool 是否正确
# 更新时间：Fri Mar 28 09:47:43 CST 2008
/*/
function is_idcard($idcard)
{
    if (strlen($idcard) == 15 || strlen($idcard) == 18) {
        if (strlen($idcard) == 15) {
            $idcard = idcard_15to18($idcard);
        }
        if (idcard_checksum18($idcard)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//年龄判定
function how_old($str)
{
    if (!$str) {
        return 0;
    }
    $len = strlen($str);

    if ($len == 18) {
        $str = substr($str, 6, 8);//获得出生年月日的时间戳
    } else if ($len == 15) {
        $str = '19' . substr($str, 6, 6);//获得出生年月日的时间戳
    }
    $old_year = substr($str, 0, 4);
    $old_month = substr($str, 4, 4);
    $now_year = date('Y');
    $now_month = date('md');
    $age = intval($now_year) - intval($old_year);
    if (intval($old_month) - intval($now_month) > 0) {
        $age = $age - 1;
    }
    return $age;
}

//写日志
function add_log($dir, $content)
{
    // $content = iconv('utf-8','gbk',$content);
    // $content = iconv('gbk','utf-8',$content);
    $log_path = APPPATH . 'logs/' . $dir;
    !is_dir($log_path) && mkdir($log_path, 0777, TRUE);
    file_put_contents($log_path . '/' . date('Ymd') . '.log', date('Y-m-d H:i:s') . '=>' . str_replace("\n", "", str_replace("\r", "", $content)) . "\r\n", FILE_APPEND);
}

//获取字符串中两个指定子字符串之间子字符串的方法
//eg: get_between("www.163.com","www.",".com");
function get_between($input, $start, $end)
{
    $substr = substr($input, strlen($start) + strpos($input, $start),
        (strlen($input) - strpos($input, $end)) * (-1));
    return $substr;
}

//获取trackid
function get_trackid()
{
    $trackid = $fromurl = '';
    if (isset($_COOKIE['Xtrack'])) {
        $tracker = $_COOKIE['Xtrack'];
        list($trackid, $fromurl) = explode('|T|', $tracker);
    }
    $fromurl = urldecode($fromurl);
    $trackid = ($trackid && preg_match('~^([a-z:0-9]{0,50})$~i', $trackid)) ? $trackid : '';
    return array(
        "trackid" => $trackid,
        "fromurl" => $fromurl
    );
}

//获取trackid
function get_trackid_1()
{
    $trackid = $fromurl = '';
    if (isset($_COOKIE['Xtrack'])) {
        $tracker = $_COOKIE['Xtrack'];
        list($trackid, $fromurl) = explode('|T|', $tracker);
    }
    //$fromurl = urldecode($fromurl);
    $trackid = ($trackid && preg_match('~^([a-z:0-9]{0,50})$~i', $trackid)) ? $trackid : '';
    return $trackid;
}

//获得编号
function reward_no()
{
    $rand_arr = array('1', '2', '3', '4', '5', '6', '7', '8', '9',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $c = count($rand_arr) - 1;
    $result = 'QTYD';
    for ($i = 0; $i < 10; $i++) {
        $result .= $rand_arr[rand(0, $c)];
    }
    return $result;
}

//获取不重复的编号
function reward_uniq_no()
{
    $rand_arr = array('1', '2', '3', '4', '5', '6', '7', '8', '9',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $c = count($rand_arr) - 1;
    $result = 'QTYD';
    $ti = date('H:i:s', time());
    $t_2 = substr($ti, 1, 1);
    $t_3 = substr($ti, 3, 1);
    $t_4 = substr($ti, 4, 1);
    $t_5 = substr($ti, 6, 1);
    $t_6 = substr($ti, 7, 1);
    for ($i = 0; $i < 10; $i++) {
        if ($i == 0) {
            $result .= $t_2;
            continue;
        }
        if ($i == 2) {
            $result .= $t_3;
            continue;
        }
        if ($i == 4) {
            $result .= $t_4;
            continue;
        }
        if ($i == 6) {
            $result .= $t_5;
            continue;
        }
        if ($i == 8) {
            $result .= $t_6;
            continue;
        }
        $result .= $rand_arr[rand(0, $c)];
    }
    return $result;
}

function get_rand($length = 4)
{
    $rand_arr = array('1', '2', '3', '4', '5', '6', '7', '8', '9',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
    $c = count($rand_arr) - 1;
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $result .= $rand_arr[rand(0, $c)];
    }
    return $result;
}

function get_number_rand($length = 4)
{
    $rand_arr = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
    $c = count($rand_arr) - 1;
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $result .= $rand_arr[rand(0, $c)];
    }
    return $result;
}

/**
 * 搜索条件生成
 * post,get-> where xxx
 */
if (!function_exists('spell_get')) {
    function spell_get()
    {
        $sql = "";
        foreach ($_GET as $key => $search_value) {
            $sql .= "$key=$search_value&";
        }
        if (strlen($sql) > 0) {
            $sql = substr($sql, 0, strlen($sql) - 1);
        }
        return $sql;
    }
}

function get_posttime($time)
{  //返回发布时间状态

    $now = mktime();  //获取当前时间

    if ($now - $time < 60) {
        $timeContent = "刚刚";
    } elseif ($now - $time < 60 * 60) {
        $timeContent = floor(($now - $time) / 60) . "分钟前";
    } elseif ($now - $time < 60 * 60 * 24) {
        $timeContent = floor(($now - $time) / (60 * 60)) . "小时前";
    } elseif ($now - $time < 60 * 60 * 24 * 30) {
        $timeContent = floor(($now - $time) / (60 * 60 * 24)) . "天前";
    } elseif ($now - $time < 60 * 60 * 24 * 30 * 12) {
        $timeContent = floor(($now - $time) / (60 * 60 * 24 * 30)) . "个月前";
    } elseif ($now - $time > 60 * 60 * 24 * 30 * 12) {
        //$timeContent = floor(($now-$time)/(60*60*24*30*12))."年前";
        $timeContent = '-';
    } else {
        $timeContent = date("Y-m-d H:i:s", $uid['created']);
    }

    return $timeContent;

}

function get_children_prize_name($prize_level = 0)
{
    switch ($prize_level) {
        case 1:
            $prize_name = "小朋友要变大土豪啦";
            break;
        case 2:
            $prize_name = "拿去买棒棒糖吧";
            break;
        case 3:
            $prize_name = "天热了吃根小雪糕";
            break;
        case 4:
            $prize_name = "加油，还差一点";
            break;
        case 5:
            $prize_name = "来袋酸酸甜甜的酸梅粉";
            break;
        case 6:
            $prize_name = "把电动飞机收入囊中";
            break;
        case 7:
            $prize_name = "可以拥有手摇卷笔刀啦";
            break;
        case 8:
            $prize_name = "买个小皮球嗨起来";
            break;
        case 9:
            $prize_name = "买洋娃娃送给隔壁小妹妹";
            break;
        default:
            $prize_name = "";
            break;
    }
    return $prize_name;
}

function get_children_prize_level($prize_name = '')
{
    $arr = array("1%年化券" => 1, "2元红包券" => 2, "3元红包券" => 3, "加油，还差一点" => 4, "5元红包券" => 5, "61元红包券" => 6, "7元红包券" => 7, "8元红包券" => 8, "9元红包券" => 9);
    return $arr[$prize_name];
}


function isInString($haystack, $needle)
{
    if ($needle == '') return false;
    return false !== strpos($haystack, $needle);
}

//获取礼品来源名称
function get_gift_source_name($type = 0)
{
    $arr = array(GIFT_SOURCE_THANK => "感恩活动", GIFT_SOURCE_LOTTERY => '快乐赚盘', GIFT_SOURCE_BIRTHDAY => "生日礼品", GIFT_SOURCE_KMRACE => "极限滑雪");
    return $arr[$type];
}

//获取访问ip
function get_remote_ip()
{
    $ci =  &get_instance();
    return $ci->input->ip_address();
}

/**
 * 解析url参数，转换为数组
 * @param $query
 * @return array
 */
function convertUrlQuery($query)
{
    $queryParts = explode('&', $query);
    $params = array();
    foreach ($queryParts as $param) {
        $item = explode('=', $param);
        $params[$item[0]] = $item[1];
    }
    return $params;
}


//第一个是原串,第二个是 部份串
function startWith($str, $needle)
{
    return strpos($str, $needle) === 0;
}

//第一个是原串,第二个是 部份串
function endWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}

/**
 * 获取商品类型
 * @param int $type
 * @return string
 */
function getGoodsTypeDes($type = 0)
{
    switch ($type) {
        case GOODS_TYPE_EXCHANGE:
            return "兑换商品";
        case GOODS_TYPE_AUCTION:
            return "拍卖商品";
        default:
            return "未知类型";
    }
}

/**
 * 获取订单类型
 * @param int $type
 * @return string
 */
function getOrdersTypeDes($type = 0)
{
    switch ($type) {
        case GOODS_ORDERS_TYPE_AUCTION:
            return "商品拍卖";
        case GOODS_ORDERS_TYPE_EXCHANGE:
            return "商品兑换";
        case GOODS_ORDERS_TYPE_GOLD:
            return "周年庆金币";
        case GOODS_ORDERS_TYPE_PRIZE:
            return "奖品";
        case GOODS_ORDERS_TYPE_GIFT:
            return "礼品";
        case GOODS_ORDERS_TYPE_SILVER:
            return "周年庆银牌";
        case GOODS_ORDERS_TYPE_MONOPOLY:
            return "大富翁";
        case GOODS_ORDERS_TYPE_LOTTERY_2016_09_30:
            return "大转盘";
        default:
            return "未知来源";
    }
}

function expressCompanyList()
{
    $arr = array(
        "圆通快递" => "yuantong",
        "申通快递" => "shentong",
        "韵达快递" => "yunda",
        "中通快递" => "zhongtong",
        "顺丰快递" => "shunfeng",
        "天天快递" => "tiantian",
        "EMS" => "ems",
        "全峰快递" => "quanfengkuaidi",
        "汇通" => "huitongkuaidi",
        "E邮宝" => "ems",
        "UPS" => "ups",
        "宅急送" => "zhaijisong",
        "邮政包裹" => "youzhengguonei",
        "AAE" => "aae",
        "安信达" => "anxindakuaixi",
        "BHT" => "bht",
        "百福东方" => "aae",
        "COE" => "coe",
        "CCES（希伊艾斯）" => "cces",
        "DHL" => "dhl",
        "大田" => "datianwuliu",
        "德邦" => "debangwuliu",
        "D速" => "dsukuaidi",
        "递四方" => "disifang",
        "飞康达" => "feikangda",
        "FedEx（国际）" => "fedex",
        "联邦快递（国内）" => "lianbangkuaidi",
        "全日通" => "quanritongkuaidi",
        "申通E物流" => "shentong",
        "邮政国内包裹" => "youzhengguonei",
        "邮政国际包裹" => "youzhengguoji",
        "中铁快运" => "zhongtiewuliu",
        "中邮物流" => "zhongyouwuliu"
    );
    return $arr;
}

function getExpressCompany($type = 0)
{
    $arr = expressCompanyList();
    $type = $type - 1;
    if ($type >= 0 && $type <= count($arr) - 1) {
        $index = 0;
        foreach ($arr as $key => $val) {
            if ($type == $index) {
                return $key;
            }
            $index++;
        }
    }
    return "";
}

/**
 * @param int $type
 * @return string
 */
function getExpressCompanyValue($type = 0)
{
    $arr = expressCompanyList();
    $type = $type - 1;
    if ($type >= 0 && $type <= count($arr) - 1) {
        $index = 0;
        foreach ($arr as $key => $val) {
            if ($type == $index) {
                return $val;
            }
            $index++;
        }
    }
    return "";
}

function getExpress($type = 0, $express_no = '')
{
    $result = array();
    $times = 0;
    $cookie_name = 'express_no_' . $express_no;
    //读取cookie
    if (isset($_COOKIE[$cookie_name]) && !empty($_COOKIE[$cookie_name])) {
        $times = intval($_COOKIE[$cookie_name]);
        if ($times >= 10) {
            $result[RETURN_CODE] = FAILURE;
            $result[RETURN_MSG] = "24小时之内最多查询10次物流信息";
            return $result;
        }
    }
    $type = getExpressCompanyValue($type);
    $url = sprintf('http://www.kuaidi100.com/query?type=%s&postid=%s&id=1&valicode=&temp=%s', $type, $express_no, sprintf("%.16f", rand(1000000000000000, 9999999999999999) / 10000000000000000));
    $content = curl_send($url, "GET", '', array("Referer" => "http://www.kuaidi100.com/"));

    set_cookie($cookie_name, $times + 1, 86400);//24小时过期

    if (!empty($content)) {
        $jsonObject = json_decode($content);
        if ($jsonObject->status != 200) {
            $result[RETURN_CODE] = FAILURE;
            $result[RETURN_MSG] = $jsonObject->message;
        } else {
            $result[RETURN_CODE] = SUCCESS;
            $result[RETURN_MSG] = "";
            $result['data'] = $jsonObject->data;
        }
    } else {
        $result[RETURN_CODE] = FAILURE;
        $result[RETURN_MSG] = "查询物流异常，请稍后再试";
    }
    return $result;
}

/**
 * 用户等级百分比
 * @param array $data
 * @return float|int
 */
function get_my_info_level($data = array())
{
    $speed = 0;
    if (isset($data['nick_name']) && !empty($data['nick_name'])) {
        $speed += 7.7;
    } else {
        if (isset($_SESSION['nick_name']) && !empty($_SESSION['nick_name'])) {
            $speed += 7.7;
        }
    }
    if (isset($data['litpic']) && !empty($data['litpic'])) {
        $speed += 7.7;
    }
    if (isset($data['marry']) && !empty($data['marry'])) {
        $speed += 7.7;
    }
    if (isset($data['child']) && !empty($data['child'])) {
        $speed += 7.7;
    }
    if (isset($data['yanglao']) && !empty($data['yanglao'])) {
        $speed += 7.7;
    }
    if (isset($data['renshou']) && !empty($data['renshou'])) {
        $speed += 7.7;
    }
    if (isset($data['education']) && !empty($data['education'])) {
        $speed += 7.7;
    }
    if (isset($data['id_address']) && !empty($data['id_address'])) {
        $speed += 7.7;
    }
    if (isset($data['address']) && !empty($data['address'])) {
        $speed += 7.7;
    }
    if (isset($data['income']) && !empty($data['income'])) {
        $speed += 7.7;
    }
    if (isset($data['professional']) && !empty($data['professional'])) {
        $speed += 7.7;
    }
    if (isset($data['linkman']) && !empty($data['linkman'])) {
        $speed += 7.7;
    }
    if (isset($data['phone']) && !empty($data['phone'])) {
        $speed += 7.7;
    }
    return $speed;
}

/**
 * 去除html空白字符
 * @param $string
 * @return string
 */
function remove_html($string)
{
    $str = trim($string); //清除字符串两边的空格
    $str = preg_replace("/\t/", "", $str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
    $str = preg_replace("/\r\n/", "", $str);
    $str = preg_replace("/\r/", "", $str);
    $str = preg_replace("/\n/", "", $str);
    $str = preg_replace("/  /", "", $str);  //匹配html中的空格
    return trim($str); //返回字符串
}

function get_level_number($user_level = '')
{
    $patten = "(\\d+)";
    $result = array();
    if (!empty($user_level)) {
        if (preg_match($patten, $user_level, $result)) {
            return $result[0];
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

/**
 * 生成订单号
 * @return string
 */
function generateOrderNo()
{
    list($usec, $sec) = explode(" ", microtime());
    $order_no = date("YmdHis", $sec) . floor($usec * 1000000);
    return substr($order_no, 2, strlen($order_no) - 2);
}

/**
 * 平台类型描述
 * @param int $type
 * @return string
 */
function getPlatType($type = 0)
{
    switch ($type) {
        case PLAT_TYPE_PC:
            return "PC";
        case PLAT_TYPE_IOS:
            return "iOS";
        case PLAT_TYPE_WAP:
            return "wap";
        case PLAT_TYPE_ANDROID:
            return "安卓";
        case PLAT_TYPE_WECHAT:
            return "微信";
        case PLAT_TYPE_OTHER:
            return "其它";
        default:
            return "未知";
    }
}

/**
 * 根据url区分微信和wap
 * @return string
 */
function getPlat()
{
    $plat = 'wap';
    $_url = base_url();
    if (strpos($_url, 'weixin.hrcfu.com') !== false || strpos($_url, 'wx.hrcfu.com') !== false) {
        $plat = 'weixin';
    }
    return $plat;
}

function get_os()
{
    $user_OSagent = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($user_OSagent, "NT 10.0") || strpos($user_OSagent, "NT 6.4")) {
        if (strpos($user_OSagent, "WOW64")) {
            $visitor_os = "Windows 10 x64";
        } else {
            $visitor_os = "Windows 10";
        }
    } else if (strpos($user_OSagent, "NT 6.3")) {
        if (strpos($user_OSagent, "WOW64")) {
            $visitor_os = "Windows 8.1 x64";
        } else {
            $visitor_os = "Windows 8.1";
        }
    } else if (strpos($user_OSagent, "NT 6.2")) {
        if (strpos($user_OSagent, "WOW64")) {
            $visitor_os = "Windows 8 x64";
        } else {
            $visitor_os = "Windows 8";
        }
    } else if (strpos($user_OSagent, "NT 6.1")) {
        if (strpos($user_OSagent, "WOW64")) {
            $visitor_os = "Windows 7 x64";
        } else {
            $visitor_os = "Windows 7";
        }
    } else if (strpos($user_OSagent, "NT 5.1")) {
        $visitor_os = "Windows XP(2600)";
    } else if (strpos($user_OSagent, "NT 5.2") && strpos($user_OSagent, "WOW64")) {
        $visitor_os = "Windows XP 64-bit Edition";
    } else if (strpos($user_OSagent, "NT 5.2")) {
        $visitor_os = "Windows XP(3790)";
    } else if (strpos($user_OSagent, "NT 6.0")) {
        if (strpos($user_OSagent, "WOW64")) {
            $visitor_os = "Windows Vista x64";
        } else {
            $visitor_os = "Windows Vista";
        }
    } else if (strpos($user_OSagent, "NT 5.0")) {
        $visitor_os = "Windows 2000";
    } else if (strpos($user_OSagent, "4.9")) {
        $visitor_os = "Windows ME";
    } else if (strpos($user_OSagent, "NT 4")) {
        $visitor_os = "Windows NT 4.0";
    } else if (strpos($user_OSagent, "98")) {
        $visitor_os = "Windows 98";
    } else if (strpos($user_OSagent, "95")) {
        $visitor_os = "Windows 95";
    } else if (strpos($user_OSagent, "NT")) {
        $visitor_os = "Windows UnKnown";
    } else if (preg_match('/android/i', strtolower($user_OSagent))) {
        $visitor_os = 'android';
    } else if (preg_match('/iphone/i', strtolower($user_OSagent))) {
        $visitor_os = 'iphone';
    } else if (preg_match('/ipad/i', strtolower($user_OSagent))) {
        $visitor_os = 'ipad';
    } else if (preg_match('/ipod/i', strtolower($user_OSagent))) {
        $visitor_os = 'ipod';
    } else if (strpos($user_OSagent, "Mac")) {
        $visitor_os = "Mac";
    } else if (strpos($user_OSagent, "Ubuntu")) {
        $visitor_os = "Ubuntu";
    } else if (strpos($user_OSagent, "Linux")) {
        $visitor_os = "Linux";
    } else if (strpos($user_OSagent, "Unix")) {
        $visitor_os = "Unix";
    } else if (strpos($user_OSagent, "FreeBSD")) {
        $visitor_os = "FreeBSD";
    } else if (strpos($user_OSagent, "SunOS")) {
        $visitor_os = "SunOS";
    } else if (strpos($user_OSagent, "BeOS")) {
        $visitor_os = "BeOS";
    } else if (strpos($user_OSagent, "OS/2")) {
        $visitor_os = "OS/2";
    } else if (preg_match('/Mac/i', $user_OSagent) && preg_match('/PC/i', $user_OSagent)) {
        $visitor_os = "Macintosh";
    } else if (preg_match('/PowerPC/i', $user_OSagent)) {
        $visitor_os = 'PowerPC';
    } else if (strpos($user_OSagent, "AIX")) {
        $visitor_os = "AIX";
    } else if (strpos($user_OSagent, "IBM OS/2")) {
        $visitor_os = "IBM OS/2";
    } else if (strpos($user_OSagent, "BSD")) {
        $visitor_os = "BSD";
    } else if (strpos($user_OSagent, "NetBSD")) {
        $visitor_os = "NetBSD";
    } else if (preg_match('/OSF1/i', $user_OSagent)) {
        $visitor_os = 'OSF1';
    } else if (preg_match('/IRIX/i', $user_OSagent)) {
        $visitor_os = 'IRIX';
    } else if (preg_match('/teleport/i', $user_OSagent)) {
        $visitor_os = 'teleport';
    } else if (preg_match('/flashget/i', $user_OSagent)) {
        $visitor_os = 'flashget';
    } else if (preg_match('/webzip/i', $user_OSagent)) {
        $visitor_os = 'webzip';
    } else if (preg_match('/offline/i', $user_OSagent)) {
        $visitor_os = 'offline';
    } else {
        $ci = &get_instance();
        $ci->load->library("user_agent");
        if ($ci->agent->is_mobile()) {
            $visitor_os = $ci->agent->platform();
        } else {
            $visitor_os = "UnKnown";
        }
    }
    return $visitor_os;
}

function get_browser_version()
{
    $sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串
    $exp = array();
    if (stripos($sys, "Firefox/") > 0) {
        preg_match("/Firefox\/([\d\.]+)/", $sys, $fix);
        $exp[0] = "火狐浏览器";
        $exp[1] = floatval($fix[1]);
    } else if (stripos($sys, "MQQBrowser")) {
        preg_match("/MQQBrowser\/([\d\.]+)/", $sys, $qq1);
        $exp[0] = "QQ浏览器手机版";
        $exp[1] = floatval($qq1[1]);
    } else if (stripos($sys, "QQBrowser")) {
        preg_match("/QQBrowser\/([\d\.]+)/", $sys, $qq);
        $exp[0] = "QQ浏览器";
        $exp[1] = floatval($qq[1]);
    } else if (stripos($sys, "LBBROWSER")) {
        preg_match("/LBBROWSER\/([\d\.]+)/", $sys, $lb);
        if (count($lb) > 1) {
            $exp[0] = "猎豹浏览器";
            $exp[1] = $lb[1];
        } else {
            $exp[0] = "猎豹浏览器";
            $exp[1] = "";
        }
    } else if (stripos($sys, "LieBaoFast")) {
        preg_match("/LieBaoFast\/([\d\.]+)/", $sys, $lb1);
        $exp[0] = "猎豹浏览器手机版";
        $exp[1] = $lb1[1];
    } else if (stripos($sys, "baidubrowser")) {
        preg_match("/baidubrowser\/([\d\.]+)/", $sys, $bai1);
        $exp[0] = "百度浏览器手机版";
        $exp[1] = $bai1[1];
    } else if (stripos($sys, "BIDUBrowser")) {
        preg_match("/BIDUBrowser\/([\d\.]+)/", $sys, $bai);
        $exp[0] = "百度浏览器";
        $exp[1] = $bai[1];
    } else if (stripos($sys, "Opera")) {
        preg_match("/Opera\/([\d\.]+)/", $sys, $opera1);
        $exp[0] = "Opera手机版";
        $exp[1] = $opera1[1];  //获取Opera浏览器的版本号
    } else if (stripos($sys, "UBrowser")) {
        preg_match("/UBrowser\/([\d\.]+)/", $sys, $uc);
        $exp[0] = "UC浏览器PC版";
        $exp[1] = $uc[1];
    } else if (stripos($sys, "UCBrowser")) {
        preg_match("/UCBrowser\/([\d\.]+)/", $sys, $uc1);
        $exp[0] = "UC浏览器手机版";
        $exp[1] = $uc1[1];
    } else if (stripos($sys, "SogouMobileBrowser")) {
        preg_match("/SogouMobileBrowser\/([\d\.]+)/", $sys, $sogou);
        $exp[0] = "搜狗浏览器手机版";
        $exp[1] = $sogou[1];//
    } else if (stripos($sys, "MetaSr")) {
        preg_match("/MetaSr\s{1}([\d\.]+)/", $sys, $sogou);
        $exp[0] = "搜狗浏览器PC版本";
        $exp[1] = $sogou[1];
    } else if (stripos($sys, "Mb2345Browser")) {
        preg_match("/Mb2345Browser\/([\d\.]+)/", $sys, $_2345);
        $exp[0] = "2345浏览器手机版";
        $exp[1] = $_2345[1];
    } else if (stripos($sys, "2345Explorer")) {
        preg_match("/2345Explorer\/([\d\.]+)/", $sys, $_2345);
        $exp[0] = "2345浏览器";
        $exp[1] = $_2345[1];
    } else if (stripos($sys, "Maxthon") > 0) {
        preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);
        $exp[0] = "傲游浏览器";
        $exp[1] = $aoyou[1];
    } else if (stripos($sys, "MSIE") > 0) {
        preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
        $exp[0] = "IE";
        $exp[1] = $ie[1];
    } else if (stripos($sys, "OPR") > 0) {
        preg_match("/OPR\/([\d\.]+)/", $sys, $opera);
        $exp[0] = "Opera";
        $exp[1] = $opera[1];
    } else if (stripos($sys, "Edge") > 0) { //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
        preg_match("/Edge\/([\d\.]+)/", $sys, $edge);
        $exp[0] = "Edge";
        $exp[1] = $edge[1];
    } else if (stripos($sys, "TheWorld") > 0) {
        preg_match("/TheWorld\s{1}(\d+)/", $sys, $world);
        $exp[0] = "世界之窗浏览器";
        $exp[1] = $world[1];
    } else if (stripos($sys, "Chrome") > 0) {
        preg_match("/Chrome\/([\d\.]+)/", $sys, $chrome);
        $exp[0] = "Chrome";
        $exp[1] = $chrome[1];
    } else if (stripos($sys, "Safari")) {
        preg_match("/Safari\/([^;)]+)+/i", $sys, $safari);
        $exp[0] = "Safari";
        $exp[1] = $safari[1];
    } else if (stripos($sys, 'rv:') > 0 && stripos($sys, 'Gecko') > 0) {
        preg_match("/rv:([\d\.]+)/", $sys, $other);
        $exp[0] = "IE";
        $exp[1] = $other[1];
    } else {
        $ci = &get_instance();
        $ci->load->library("user_agent");
        if ($ci->agent->is_mobile()) {
            $exp[0] = $ci->agent->browser();
            $exp[1] = $ci->agent->version();
        } else {
            $exp[0] = "UnKnown Browser";
            $exp[1] = "";
        }
    }
    if (!empty($exp[1])) {
        return $exp[0] . '(' . $exp[1] . ')';
    } else {
        return $exp[0];
    }
}

/**
 * 随机生成随机码
 * @param int $length
 * @return string
 */
function generate_password($length = 8)
{
    // 密码字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组 $chars 的任意元素
        // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        $password .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $password;
}

/**
 * 获取vip预约信息状态
 * @param int $status
 * @return mixed
 */
function getVipStatusText($status = 0)
{
    $arr = array(
        VIP_TYPE_CANCEL => "主动取消",
        VIP_TYPE_CHECK_FAIL => "审核失败",
        VIP_TYPE_WAITING_CHECK => "待审核",
        VIP_TYPE_NO_PAIR => "审核通过未配对",
        VIP_TYPE_PAIR_FAIL => "审核通过配对失败",
        VIP_TYPE_PAIR_SUCCESS => "审核通过配对成功"
    );
    return $arr[$status];
}

/**
 * 格式化年化券限额(万)
 * @param int $num
 * @return int|string
 */
function format_coupon_money_limit($num = 0)
{
    if (empty($num)) {
        return $num;
    }
    $num_text = $num / 10000;
    $num_text = explode('.', $num_text);
    if (count($num_text) > 1) {
        return $num_text[0] . '.' . substr($num_text[1], 0, 2);
    }
    return $num / 10000;
}

/**
 * 获取提现状态
 * @param int $status
 * @return string
 */
function getCashApplyStatusText($status = 0)
{
    switch ($status) {
        case CASH_STATUS_DEFAULT:
            return "提现处理中";
        case CASH_STATUS_SUCCESS:
            return "提现成功";
        case CASH_STATUS_FAILURE:
            return "提现失败";
        case CASH_STATUS_CANCEL:
            return "提现取消";
        case CASH_STATUS_HANDLE:
            return "订单处理中";
        default:
            return "";
    }
}

/**
 * 获取两个日期之间的天数差
 * @param $start_date
 * @param $end_date
 * @return float
 */
function getDays($start_date, $end_date)
{
    $start_date = strtotime($start_date);
    $end_date = strtotime($end_date);
    $days = round(($start_date - $end_date) / 3600 / 24);
    return abs($days);
}

function getUserLevel($growth_value = 0)
{
    if ($growth_value > 100000) {
        return "VIP7";
    } else if ($growth_value > 50000) {
        return "VIP6";
    } else if ($growth_value > 20000) {
        return "VIP5";
    } else if ($growth_value > 10000) {
        return "VIP4";
    } else if ($growth_value > 5000) {
        return "VIP3";
    } else if ($growth_value > 3000) {
        return "VIP2";
    } else if ($growth_value > 0) {
        return "VIP1";
    } else {
        return "VIP0";
    }
}

function getUserLevelNum($growth_value = 0)
{
    if ($growth_value > 100000) {
        return 7;
    } else if ($growth_value > 50000) {
        return 6;
    } else if ($growth_value > 20000) {
        return 5;
    } else if ($growth_value > 10000) {
        return 4;
    } else if ($growth_value > 5000) {
        return 3;
    } else if ($growth_value > 3000) {
        return 2;
    } else if ($growth_value > 0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * 阿米巴类型
 * @param int $code_type
 * @return string
 */
function getAmoebaCodeTypeText($code_type = 0)
{
    switch ($code_type) {
        case 1:
            return "消费";
        case 2:
            return "收入";
        case 3:
            return "工时";
        default:
            return "未知";
    }
}

/**
 * 生成树
 * @param $arr
 * @param int $parent_id
 * @return array
 */
function make_tree($arr, $parent_id = 0)
{
    $new_arr = array();
    foreach ($arr as $k => $v) {
        if ($v->parent_id == $parent_id) {
            $new_arr[] = $v;
            unset($arr[$k]);
        }
    }
    foreach ($new_arr as &$a) {
        $a->children = make_tree($arr, $a->id);
    }
    return $new_arr;
}

function make_tree_with_namepre($arr)
{
    $arr = make_tree($arr);
    return add_namepre1($arr);
}

function add_namepre1($arr, $pre_str = '')
{
    $new_arr = array();
    foreach ($arr as $v) {
        if ($pre_str) {
            if ($v == end($arr)) {
                $v->name = $pre_str . '└─ ' . $v->name;
            } else {
                $v->name = $pre_str . '├─ ' . $v->name;
            }
        }

        if ($pre_str == '') {
            $pre_str_for_children = '　  ';
        } else {
            if ($v == end($arr)) {
                $pre_str_for_children = $pre_str . '　　';
            } else {
                $pre_str_for_children = $pre_str . '│　 ';
            }
        }
        $v->children = add_namepre1($v->children, $pre_str_for_children);

        $new_arr[] = $v;
    }
    return $new_arr;
}

/**
 * @param $arr
 * @param int $depth ，当$depth为0的时候表示不限制深度
 * @param int $selected_id 选中的项
 * @return string
 */
function make_option_tree_for_select($arr, $depth = 0, $selected_id = 0)
{
    $arr = make_tree_with_namepre($arr);
    return make_options1($arr, $depth, $selected_id);
}

function make_options1($arr, $depth, $selected_id, $recursion_count = 0, $ancestor_ids = '')
{
    $recursion_count++;
    $str = '';
    foreach ($arr as $v) {
        $selected = '';
        if ($v->id == $selected_id) {
            $selected = ' selected ';
        }
        $str .= "<option value='{$v->id}' " . $selected . " data-depth='{$recursion_count}' data-ancestor_ids='" . ltrim($ancestor_ids, ',') . "'>{$v->name}</option>";
        if ($v->parent_id == 0) {
            $recursion_count = 1;
        }
        if ($depth == 0 || $recursion_count < $depth) {
            $str .= make_options1($v->children, $depth, $selected_id, $recursion_count, $ancestor_ids . ',' . $v->id);
        }
    }
    return $str;
}

function token(){
	return md5(uniqid(md5(microtime(true)),true));
}

function unlimitedForLayer ($cate, $id_name = 'id', $pid_name = 'pid', $name = 'child', $pid = 0) {
	$arr = array();
	foreach ($cate as $v) {
		if ($v[$pid_name] == $pid) {
			$v[$name] = unlimitedForLayer($cate, $id_name, $pid_name, $name, $v[$id_name]);
			$arr[] = $v;
		}
	}
	return $arr;
}

/**
 *    身份证验证
 *
 *    @param    string    $id
 *    @return   boolean
 */
// function is_idcard( $id )
// {
//     $id = strtoupper($id);
//     $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
//     $arr_split = array();
//     if(!preg_match($regx, $id))
//     {
//         return FALSE;
//     }
//     if(15==strlen($id)) //检查15位
//     {
//         $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";

//         @preg_match($regx, $id, $arr_split);
//         //检查生日日期是否正确
//         $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
//         if(!strtotime($dtm_birth))
//         {
//             return FALSE;
//         } else {
//             return TRUE;
//         }
//     }
//     else           //检查18位
//     {
//         $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
//         @preg_match($regx, $id, $arr_split);
//         $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
//         if(!strtotime($dtm_birth))  //检查生日日期是否正确
//         {
//             return FALSE;
//         }
//         else
//         {
//             //检验18位身份证的校验码是否正确。
//             //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
//             $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
//             $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
//             $sign = 0;
//             for ( $i = 0; $i < 17; $i++ )
//             {
//                 $b = (int) $id{$i};
//                 $w = $arr_int[$i];
//                 $sign += $b * $w;
//             }
//             $n  = $sign % 11;
//             $val_num = $arr_ch[$n];
//             if ($val_num != substr($id,17, 1))
//             {
//                 return FALSE;
//             }
//             else
//             {
//                 return TRUE;
//             }
//         }
//     }
// }

function is_empty($v){
	return $v == ''?true:false;
}