<?php

//银行总信息
function bank($bank_code=''){
    $arr = array(
        'ICBC'=>'工商银行',
        'BOC'=>'中国银行',
        'CCB'=>'建设银行',
        'ABC'=>'农业银行',
        'CMB'=>'招商银行',
        'GDB'=>'广发银行',
        'CMBC'=>'民生银行',
        'CEB'=>'光大银行',
        'CIB'=>'兴业银行',
        'PSBC'=>'邮政储蓄银行',
        'HXB'=>'华夏银行',
        'BOCOM'=>'交通银行',
        'CNCB'=>'中信银行',
        'SPDB'=>'浦发银行',
        'PAB'=>'平安银行',
        'BOB'=>'北京银行',
        // 'SRCB'=>'上海农商银行',
        'BOS'=>'上海银行',
        'NBCB'=>'宁波银行',
        'HKBEA'=>'东亚银行',
        'NJCB'=>'南京银行',
        'CBHB'=>'渤海银行',
        'BOCD'=>'成都银行',
        'BCM'=>'交通银行',
        // 'BRCB'=>'北京农商银行'
    );
    if($bank_code){
        $bank_code = strtoupper($bank_code);
        if(array_key_exists($bank_code, $arr))
            return $arr[$bank_code];
        else
            return '银行';
    }
    return $arr;
}

//双钱
/*function bank_shuangqian($bank_code=''){
    $arr = array(
        array('ICBC'=>array('ICBC'=>'工商银行'))
    );
    if($bank_code){
        return $arr[strtoupper($bank_code)];
    }
    return $arr;
}*/

//宝付
function bank_baofu($bank_code=''){
    $arr = array(
        "3002"=>array('ICBC'=>'工商银行'),
        "3005"=>array('ABC'=>'农业银行'),
        "3003"=>array('CCB'=>'建设银行'),
        "3001"=>array('CMB'=>'招商银行'),
        "3026"=>array('BOC'=>'中国银行'),
        "3006"=>array('CMBC'=>'民生银行'),
        "3020"=>array('BOCOM'=>'交通银行'),
        "3038"=>array('PSBC'=>'邮政储蓄银行'),
        "3035"=>array('PAB'=>'平安银行'),
        "3022"=>array('CEB'=>'光大银行'),
        "3004"=>array('SPDB'=>'浦发银行'),
        "3009"=>array('CIB'=>'兴业银行'),
        "3036"=>array('GDB'=>'广发银行'),
        "3039"=>array('CNCB'=>'中信银行'),
        "3050"=>array('HXB'=>'华夏银行'),
        "3032"=>array('BOB'=>'北京银行'),
        "3059"=>array('BOS'=>'上海银行'),
        // "3061"=>array('HZBC'=>'杭州银行'),
        "3062"=>array('QTBC'=>'其他银行'),
        // "3037"=>array('SRCB'=>'上海农商银行'),
        // "3060"=>array('BRCB'=>'北京农商银行')
    );
    if($bank_code){
        return $arr[$bank_code];
    }
    return $arr;
}

//连连
function bank_lianlian($bank_code=''){
    $arr = array(
        "01020000"=>array('ICBC'=>'工商银行'),
        "01030000"=>array('ABC'=>'农业银行'),
        "01040000"=>array('BOC'=>'中国银行'),
        "01050000"=>array('CCB'=>'建设银行'),
        "03080000"=>array('CMB'=>'招商银行'),
        "01000000"=>array('PSBC'=>'邮政储蓄银行'),
        "03030000"=>array('CEB'=>'光大银行'),
        "03040000"=>array('HXB'=>'华夏银行'),
        "03020000"=>array('CNCB'=>'中信银行'),
        "03090000"=>array('CIB'=>'兴业银行'),
        "03070000"=>array('PAB'=>'平安银行'),
        "03100000"=>array('SPDB'=>'浦发银行'),
        "03010000"=>array('COMM'=>'交通银行')
    );
    if($bank_code){
        return $arr[$bank_code];
    }
    return $arr;

        // "03010000"=>array('BOCOM'=>'交通银行'),
        // "03050000"=>array('CMBC'=>'民生银行'),
        // "03060000"=>array('GDB'=>'广发银行'),
        // "04083320"=>array('NBCB'=>'宁波银行'),
        // "03200000"=>array('HKBEA'=>'东亚银行'),
        // "04012900"=>array('BOS'=>'上海银行'),
        // "04243010"=>array('NJCB'=>'南京银行'),
        // "65012900"=>array('SRCB'=>'上海农商银行'),
        // "03170000"=>array('CBHB'=>'渤海银行'),
        // "64296510"=>array('BOCD'=>'成都银行'),
        // "04031000"=>array('BOB'=>'北京银行')
}

//不支持快捷支付的银行
function get_not_support_quick_pay_bank_list()
{
    return array("BCCB", "COMM", "CZB", "CBHB", "NJCB");
}

//不支持提现的银行
function get_not_support_cash_apply_bank_list()
{
    return array("CBHB", "NJCB", "CZB");//渤海银行，南京银行，浙商银行
}

//连连银行代码
function lianlian_bank_code($bank_code=''){
    $arr = array(
        "ICBC"=>array('01020000'=>'工商银行'),
        "SZPAB"=>array('03070000'=>'平安银行'),
        "CCB"=>array('01050000'=>'建设银行'),
        "ABC"=>array('01030000'=>'农业银行'),
        "CMB"=>array('03080000'=>'招商银行'),
        "BOC"=>array('01040000'=>'中国银行'),
        "CEB"=>array('03030000'=>'光大银行'),
        "CMBC"=>array('03050000'=>'民生银行'),
        "CIB"=>array('03090000'=>'兴业银行'),
        "CNCB"=>array('03020000'=>'中信银行'),
        "GDB"=>array('03060000'=>'广发银行'),
        "SPDB"=>array('03100000'=>'浦发银行'),
        "PAB"=>array('03070000'=>'平安银行'),
        "HXB"=>array('03040000'=>'华夏银行'),
        "NBCB"=>array('04083320'=>'宁波银行'),
        "HKBEA"=>array('03200000'=>'东亚银行'),
        "BOS"=>array('04012900'=>'上海银行'),
        "PSBC"=>array('01000000'=>'邮政储蓄银行'),
        "NJCB"=>array('04243010'=>'南京银行'),
        "SRCB"=>array('65012900'=>'上海农商银行'),
        "CBHB"=>array('03170000'=>'渤海银行'),
        "BOCD"=>array('64296510'=>'成都银行'),
        "CITIC"=>array('03020000'=>'中信银行'),
        "COMM"=>array('03010000'=>'交通银行'),
        "BOB"=>array('04031000'=>'北京银行')
    );
    if($bank_code){
        return $arr[$bank_code];
    }
    return $arr;
}


//富友银行代码
function fuyou_bank_code($bank_code=''){
    $arr = array(
        "ICBC"=>array('0801020000'=>'工商银行'),
        "SZPAB"=>array('0804100000'=>'平安银行'),
        "CCB"=>array('0801050000'=>'建设银行'),
        "ABC"=>array('0801030000'=>'农业银行'),
        "CMB"=>array('0803080000'=>'招商银行'),
        "BOC"=>array('0801040000'=>'中国银行'),
        "CEB"=>array('0803030000'=>'光大银行'),
        "CMBC"=>array('0803050000'=>'民生银行'),
        "CIB"=>array('0803090000'=>'兴业银行'),
        "CNCB"=>array('0803020000'=>'中信银行'),
        "GDB"=>array('0803060000'=>'广发银行'),
        "SPDB"=>array('0803100000'=>'浦发银行'),
        "PAB"=>array('0804100000'=>'平安银行'),
        "HXB"=>array('0803040000'=>'华夏银行'),
        "BOS"=>array('0804010000'=>'上海银行'),
        "PSBC"=>array('0801000000'=>'邮政储蓄银行'),
        "CITIC"=>array('0803020000'=>'中信银行'),
        "COMM"=>array('0803010000'=>'交通银行'),
        "BOB"=>array('0804031000'=>'北京银行')
    );
    if($bank_code){
        return $arr[$bank_code];
    }
    return $arr;
}

function bank_fuyou($bank_code='')
{
    $arr = array(
        "0801020000" => array('ICBC' => '工商银行'),
        "0801030000" => array('ABC' => '农业银行'),
        "0801040000" => array('BOC' => '中国银行'),
        "0801050000" => array('CCB' => '建设银行'),
        "0803080000" => array('CMB' => '招商银行'),
        "0801000000" => array('PSBC' => '邮政储蓄银行'),
        "0803030000" => array('CEB' => '光大银行'),
        "0803040000" => array('HXB' => '华夏银行'),
        "0803020000" => array('CNCB' => '中信银行'),
        "0803090000" => array('CIB' => '兴业银行'),
        "0804100000" => array('PAB' => '平安银行'),
        "0803100000" => array('SPDB' => '浦发银行'),
        "0803010000" => array('COMM' => '交通银行'),
        '0803050000'=>array('CMBC'=>'民生银行'),
        '0804031000'=>array('BCCB'=>'北京银行'),
        '0803060000'=>array('GDB'=>'广发银行'),
    );
    if ($bank_code) {
        return $arr[$bank_code];
    }
    return $arr;
}