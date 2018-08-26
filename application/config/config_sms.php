<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//短信通过
$config['sms_channel'] = 1;//1.亿美,2.漫道

$config['sms_channel_reg1'] = 1;//注册通道1
$config['sms_channel_reg2'] = 3;//注册通道2
$config['sms_channel_notice'] = 2;//通知通道

$config['day_limit'] = 10;//一天允许发的次数
$config['voice_num'] = 2;//语音次数

//亿美相关配置.验证码
$config['ym_url'] = "http://sdk999ws.eucp.b2m.cn:8080/sdk/SDKService";
$config['ym_sn'] = "9SDK-EMY-0999-JEQMN";
$config['ym_pwd'] = "";
$config['ym_session_key'] = "";
//通知类
$config['ym_url2'] = "http://sdk4report.eucp.b2m.cn:8080/sdk/SDKService";
$config['ym_sn2'] = "6SDK-EMY-6688-KGWUK";
$config['ym_pwd2'] = "";
$config['ym_session_key2'] = "";
   
//漫道相关配置
$config['md_url'] = "http://sdk.entinfo.cn:8060/z_mdsmssend.aspx";
$config['md_sn'] = "SDK-WSS-010-07609";
$config['md_pwd'] = "BF05F6362F989075A36835CDC31C2AEC";

$config['blacknames'] = array(
		'');

//示远
// $config['sy_url'] = "http://send.18sms.com/msg/HttpBatchSendSM";
// $config['sy_sn'] = "wt007f";
// $config['sy_pwd'] = "8m3Hh84k";
// $config['sy_session_key'] = "";

//沃动
$config['wd_url'] = "http://client.movek.net:8888/sms.aspx?action=send";
$config['wd_userid'] = '1491';
$config['wd_account'] = "SDK-A1491-1491";
$config['wd_pwd'] = "556699";
$config['wd_session_key'] = "";