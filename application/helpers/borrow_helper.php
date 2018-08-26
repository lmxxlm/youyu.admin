<?php
//审核状态
function borrow_status($status){
    $arr = array(
        '0'=>'待审核',
        '1'=>'投资中',
        '2'=>'审核失败',
        '3'=>'已满标',
        '4'=>'审核失败',
        '5'=>'流标'
    );
    return $arr[$status];
}

//判定是否是专标
function  is_zhuanbiao($tender_user){
    $zhuanbiao = 0;
    $bt = explode(';',$tender_user);
    //为了防止就输入封号的情况
    foreach($bt as $t){
        if($t){
            $zhuanbiao=1;
            break;
        }
    }
    return $zhuanbiao;
}

//还款方式
function repayment_style($style){
    $arr = array(
        REPAYMENT_STYLE_ONCE=>'到期一次性还本付息',
        REPAYMENT_STYLE_LAST_MONTH=>'按日计息,按月付息,到期还本'
    );
    if(isset($arr[$style])){
        return $arr[$style];
    }
    return '';
}

//还款状态
function repay_status($status = ''){
    $arr = array(
        '0'=>'待还款',
        '1'=>'已还款'
    );
    return $arr[$status];
}

//借款时间
function loan_period($isday,$time_limit,$time_limit_day){
    if($time_limit<0){
        $time_limit = 0;
    }
    if($time_limit_day<0){
        $time_limit_day = 0;
    }
    $rt = new stdClass();
    $rt->unit = '个月';
    $rt->num = $time_limit;
    if($isday){
        $rt->unit = '天';
        $rt->num = $time_limit_day;
    }
    return $rt;
}

//操作
function operate($status,$account,$account_yes,$repayment_account,$repayment_yesaccount){
    $status = intval($status);
    if($status===BORROW_STATE_BIDDING){
        if($account_yes>=$account){
            return '已满标';
        }
        return '立即投资';
    }else if($status===BORROW_STATE_BID_FULL){
        

        // if($repayment_yesaccount>=$repayment_account){
        //     return '已还款';
        // }
        return '已满标';
        //TODO:当天：return '还款中';
    }else if ($status===BORROW_STATE_REPAID){
        return '已还款';
    }
    return '';
}

//剩余时间 解释 T+1,之后的时间差。秒
function time_left($verify_time,$valid_time){
    $time_str = '+'.$valid_time.' day';
    $last_time = strtotime($time_str,$verify_time);
    return $last_time-time();
}

//借款天数 a,b之间的天数。取整到天，相减.
function time_limit_day($begin,$end){
    $a = strtotime(date('Y-m-d',$begin));
    $b = strtotime(date('Y-m-d',$end));
    $days=round(($b-$a)/3600/24);
    return $days;
}

//金额转换
// function account_cny($ns) {
//     static $cnums=array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"),
//     $cnyunits=array("圆","角","分"),
//     $grees=array("拾","佰","仟","万","拾","佰","仟","亿");
//     // var_dump(explode(".",$ns,2));exit;
//     list($ns1,$ns2)=explode(".",$ns,2);
//     $ns2=array_filter(array($ns2[1],$ns2[0]));
//     $ret=array_merge($ns2,array(implode("",_cny_map_unit(str_split($ns1),$grees)),""));
//     $ret=implode("",array_reverse(_cny_map_unit($ret,$cnyunits)));
//     return str_replace(array_keys($cnums),$cnums,$ret);
// }
// function _cny_map_unit($list,$units) {
//         log_message("info","_cny:list:".json_encode($list));
//         log_message("info","_cny:units:".json_encode($units));
//     // var_dump($list);exit;
//     $ul=count($units);
//     $xs=array('');
//     foreach (array_reverse($list) as $x) {
//         log_message("info","_cny:".json_encode($xs));
//         $l=count($xs);
//         if ($x!="0" || !($l%4)) $n=($x=='0'?'':$x).($units[($l-1)%$ul]);
//         else $n=is_numeric($xs[0][0])?$x:'';
//         array_unshift($xs,$n);
//     }
//     return $xs;
// }


// function account_cny($ns) { 
//     static $cnums=array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"),
//         $cnyunits=array("圆","角","分"),
//         $grees=array("拾","佰","仟","万","拾","佰","仟","亿");
//     list($ns1,$ns2)=explode(".",$ns,2);
//     $ns2=array_filter(array($ns2[1],$ns2[0]));
//     $ret=array_merge($ns2,array(implode("",_cny_map_unit(str_split($ns1),$grees)),""));
//     $ret=implode("",array_reverse(_cny_map_unit($ret,$cnyunits)));
//     return str_replace(array_keys($cnums),$cnums,$ret); 
//   }
// function _cny_map_unit($list,$units) { 
//     $ul=count($units);
//     $xs=array();
//     foreach (array_reverse($list) as $x) {
//         $l=count($xs);
//         if ($x!="0" || !($l%4)) $n=($x=='0'?'':$x).($units[($l-1)%$ul]);
//         else $n=is_numeric($xs[0][0])?$x:'';
//         array_unshift($xs,$n);
//     }
//     return $xs; 
// }

/**
    * 人民币小写转大写
    *
    * @param string $number 数值
    * @param string $int_unit 币种单位，默认"元"，有的需求可能为"圆"
    * @param bool $is_round 是否对小数进行四舍五入
    * @param bool $is_extra_zero 是否对整数部分以0结尾，小数存在的数字附加0,比如1960.30
    * @return string
    */

    function account_cny($money = 0, $int_unit = '元', $is_round = true, $is_extra_zero = false) {

        // 将数字切分成两段
        $parts = explode ( '.', $money, 2 );
        $int = isset ( $parts [0] ) ? strval ( $parts [0] ) : '0';
        $dec = isset ( $parts [1] ) ? strval ( $parts [1] ) : '';

        // 如果小数点后多于2位，不四舍五入就直接截，否则就处理
        $dec_len = strlen ( $dec );
        if (isset ( $parts [1] ) && $dec_len > 2) {
            $dec = $is_round ? substr ( strrchr ( strval ( round ( floatval ( "0." . $dec ), 2 ) ), '.' ), 1 ) : substr ( $parts [1], 0, 2 );
        }

        // 当number为0.001时，小数点后的金额为0元
        if (empty ( $int ) && empty ( $dec )) {
            return '零';
        }

        // 定义
        $chs = array ('0', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖' );
        $uni = array ('', '拾', '佰', '仟' );
        $dec_uni = array ('角', '分' );
        $exp = array ('', '万' );
        $res = '';

        // 整数部分从右向左找
        for($i = strlen ( $int ) - 1, $k = 0; $i >= 0; $k ++) {
            $str = '';

            // 按照中文读写习惯，每4个字为一段进行转化，i一直在减
            for($j = 0; $j < 4 && $i >= 0; $j ++, $i --) {
                $u = $int {$i} > 0 ? $uni [$j] : ''; // 非0的数字后面添加单位
                $str = $chs [$int {$i}] . $u . $str;
            }

            $str = rtrim ( $str, '0' ); // 去掉末尾的0
            $str = preg_replace ( "/0+/", "零", $str ); // 替换多个连续的0
            if (! isset ( $exp [$k] )) {
                $exp [$k] = $exp [$k - 2] . '亿'; // 构建单位
            }

            $u2 = $str != '' ? $exp [$k] : '';
            $res = $str . $u2 . $res;
        }

        // 如果小数部分处理完之后是00，需要处理下
        $dec = rtrim ( $dec, '0' );

        // 小数部分从左向右找
        if (! empty ( $dec )) {
        $res .= $int_unit;

        // 是否要在整数部分以0结尾的数字后附加0，有的系统有这要求
        if ($is_extra_zero) {
            if (substr ( $int, - 1 ) === '0') {
                $res .= '零';
            }
        }

        for($i = 0, $cnt = strlen ( $dec ); $i < $cnt; $i ++) {
            $u = $dec {$i} > 0 ? $dec_uni [$i] : ''; // 非0的数字后面添加单位
            $res .= $chs [$dec {$i}] . $u;
            if ($cnt == 1)
                $res .= '整';
            }

            $res = rtrim ( $res, '0' ); // 去掉末尾的0
            $res = preg_replace ( "/0+/", "零", $res ); // 替换多个连续的0
        } else {
            $res .= $int_unit . '整';
        }

        return $res;

    }