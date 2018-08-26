<?php

$config['api_request_format'] = "JSON";

$apilist = array(
    'api_user_registerNums'           => 'user_registerNums',			    //用户注册数
    'api_user_infocfg'             => 'user_infocfg',                       //我的资料设置参数列表
    'api_user_infodtl'             => 'user_infodtl',                       //我的资料
    'api_borrow_protdownload'      => 'borrow_protdownload',                //债券协议书下载
    'api_ppoint_aucinfo'           => 'ppoint_aucinfo',                     //竞拍信息
    'api_ppoint_auction'           => 'ppoint_auction',                     //出价操作
    'api_user_behavior'            => 'user_behavior',                      //用户行为
    'api_user_token'               => 'user_token',                         //用户登录信息共享
    'api_syscfg_time'              => 'syscfg_time',                        //java系统时间
    'api_pgoods_commentinfo'       => 'pgoods_commentinfo',                 //商品评论信息
    'api_pgoods_comment'           => 'pgoods_comment',                     //商品评论
    'api_user_infoedit'            => 'user_infoedit',                      //我的资料编辑
    'api_ppoint_index'             => 'ppoint_index',                       //积分回馈页面
    'ppoint_present'               => 'ppoint_present',                     //积分回馈领取积分
    'api_puser_home'               => 'puser_home',                         //积分与等级
    'api_exchange'                 => 'pgoods_exchange',                    //商品兑换
    'api_sign_in'                  => 'puser_insign',                       //用户签到
    'api_user_login'               => 'user_login',                         //用户登录
    'api_user_reward_info'         => 'user_rewardinfo',                    //红包和红包券
    'api_user_coupon_info'         => 'user_coupon',                        //年化券
    'api_user_invite_code'         => 'user_invitecode',                    //邀请码
    'api_user_reg'                 => 'user_reg',                           //注册
    'api_user_exists'              => 'user_exists',                        //用户是否存在
    'api_user_fgtpwd'              => 'user_fgtpwd',                        //忘记密码
    'api_user_upbirthdayRemid'     => 'user_upbirthdayRemid',               //更新生日提醒
    'api_invite_info'              => 'user_inviteinfo',                    //邀请信息
    'api_invite_list'              => 'user_invitelist',                    //邀请好友链接
    'api_user_reward_list'         => 'user_rewardlist',                    //红包列表
    'api_user_coupon_list'         => 'user_coupon',                        //我的年化券
    'api_user_update_pwd'          => 'user_uppwd',                         //会员中心修改登录密码
    'api_user_update_pay_pwd'      => 'user_uppaypwd',                      //支付密码
    'api_user_update_nick_name'    => 'user_nickname',                      //昵称
    'api_user_bind_sina_account'   => 'user_realname',                      //新浪存钱罐认证
    'api_show_member_info_sina'    => 'user_sinamember',                    //新浪存钱罐信息(跳转到新浪页面)
    'api_bank_list'                => 'bank_list',                          //银行卡列表
    'api_bank_delete'              => 'bank_unbindCard',                     //删除银行卡
    'api_bank_add'                 => 'bank_binding',                       //添加银行卡
    'api_bank_add_step2'           => 'bank_bindingpush',                   //添加银行卡第二步（输入短信验证码）
    'api_bank_detail'              => 'bank_detail',                        //银行卡详情
    'api_open_quick_pay'           => 'bank_verify',                        //开通快捷支付
    'api_account_recharge_list'    => 'account_rechargelist',               //充值记录
    'api_account_cash_list'        => 'account_cashlist',                   //提现记录
    'api_account_cash_cancel'      => 'account_cashcancel',                 //取消提现
    'api_maccount_cashcheck'       => 'maccount_cashcheck',                 //提现审核
    'api_maccount_rechargecheck'   => 'maccount_rechargecheck', //线下充值审核
    'api_maccount_sina'            => 'maccount_sina',          //新浪实时账户信息
    'api_muser_account'            => 'muser_account',                      //用户账户总揽
    'api_muser_unverify'           => 'muser_unverify',                     //用户手机认证解绑
    'api_muser_verify'             => 'muser_verify',                       //用户手机认证绑定
    'api_muser_username_change'    => 'muser_phonechange',                  //修改登录手机号
    'api_saving_pot'               => 'account_sinapay',                    //存钱罐信息
    'api_quick_bank_list'          => 'bank_quickpay',                      //可快捷充值银行卡
    'api_recharge'                 => 'account_rechargeapply',              //充值
    'api_recharge_step2'           => 'account_rechargepush',               //充值第二步（输入短信验证码，快捷充值使用）
    'api_recharge_success_count'   => 'account_rechargesuccess',            //充值成功统计
    'api_cash_success_count'       => 'account_cashsuccess',                //提现成功统计
    'api_cash_apply'               => 'account_cashapply',                  //提现申请
    'api_cash_bank_list'           => 'bank_withdraw',                      //可提现银行卡和金额
    'api_account_home'             => 'account_home',                       //账户纵览
    'api_account_company'          => 'account_company',                    //公司账户余额
    'api_invest_total'             => 'borrow_investtotal',                 //投资详情纵览
    'api_invest_list'              => 'borrow_investdetail',                //投资详情记录
    'api_borrow_invest'            => 'borrow_usertender',                  //投资操作
    'api_account_trade_log'        => 'account_tradelog',                   //交易记录
    'api_account_tradelogtotal'    => 'account_tradelogtotal',              //交易记录概览
    'api_user_logout'              => 'user_logout',                        //退出
    'api_borrow_repayment'         => 'borrow_repayment',                   //系统还款操作
    'api_borrow_repay_single'      => 'borrow_repaymentsingle',             //系统还款操作
    'api_borrow_full_single'      => 'borrow_successfailBorrow',             //满标审核操作
    'api_bank_account_is_bind'     => 'bank_isbinded',                      //银行卡是否绑定
    'api_borrow_userinfo'          => 'borrow_userinfo',                    //标详情页面的用户信息（可用余额、红包和年化券）
    'api_nick_name_allow'          => 'user_nickallow',                     //用户名是否有效
    'api_saving_pot_allow'         => 'user_cardallow',                     //是否允许开通新浪存钱罐（身份证）
    'api_borrow_tender_log'        => 'borrow_tenderlog',                   //标详情页投资记录
    'api_borrow_detail'            => 'borrow_detail',                      //标详情
    'api_register_numbers'         => 'user_registerNums',                  //实际注册总人数
    'api_borrow_tender_total'      => 'borrow_tendertotal',                 //实际投资总额
    'api_sms_exists'               => 'sms_exists',                         //验证码是否存在
    'api_borrow_recommend'         => 'borrow_recommend',                   //推荐标
    'api_borrow_prepare'           => 'borrow_prepare',                     //预告标
    'api_borrow_new_hand'          => 'borrow_newhand',                     //新手标
    'api_borrow_list'              => 'borrow_list',                        //项目列表
    'api_account_freeze'           => 'account_freeze',                     //获取冻结金额
    'api_thank_timesummary'        => 'thank_timesummary',                  //感恩活动起止时间
    'api_thank_winninginfo'        => 'thank_winninginfo',                  //待抽奖号码
    'api_thank_lotterylist'        => 'thank_lotterylist',                  //中奖列表(时间轴调用)
    'api_thank_luckylist'          => 'thank_luckylist',                    //中奖列表(时间轴调用)
    'api_thank_nums'               => 'thank_nums',                         //所有幸运券数量(未中奖、已中奖、已过期)
    'api_thank_mylist'             => 'thank_mylist',                       //用户奖券搜索
    'api_user_novicetask'          => 'user_novicetask',                    //新手大礼包
    'api_wap_account_loglist'      => 'account_loglist',                    //wap交易记录
    'api_user_rewardexchange'      => 'user_rewardexchange',                //兑换红包券
    'api_email_binding'            => 'email_binding',                      //email绑定
    'api_email_active'             => 'email_active',                       //邮件激活
    'api_bank_limit'               => 'bank_limit',                         //银行卡信息维护
    'api_sms_send'                 => 'sms_send',                           //短信验证码
    'api_message_options'          => 'message_options',                    //消息设置-选项
    'api_message_settings'         => 'message_settings',                   //消息设置-查询用户设置
    'api_message_postsettings'     => 'message_postsettings',               //消息设置                                       -                            提交用户设置信息
    'api_message_timeOptions'      => 'message_timeOptions',                //消息设置                                       -                            消息发送时间选项
    'api_message_timeSettings'     => 'message_timeSettings',               //消息设置                                       -                            获取消息发送时间用户选择项
    'api_message_postTimeSettings' => 'message_postTimeSettings',//消息设置 - 提交消息发送时间用户设置信息
    'api_borrow_tenderpay'         => 'borrow_tenderpay',//用户投资立即支付操作
    'api_borrow_tenderpay_nopaypwd'      => 'borrow_tenderpayNoPayPwd',      //智慧投，无秘支付
    'api_bank_list_v2'             => 'bank_list_v2',         //银行卡列表

    'api_borrow_yunHeTong'         => 'borrow_yunHeTong', //云合同操作 add by zy 21070623
    'api_borrow_yunHeJudge'         => 'borrow_yunHeJudge', //云合同用户类型接口 add by zy 21070623
    'api_borrow_contractDownloading'         => 'borrow_contractDownloading', //云合同下载操作 add by zy 21070708

    'api_borrow_informationDisclosure'         => 'borrow_informationDisclosure', //信息披露 add by zy 21070724
    'api_gift_oldUserUpgradeWelfare'  => 'gift_oldUserUpgradeWelfare',      //用户升级福利红包
    'api_activity_queryAmountOfYear'=>'activity_queryAmountOfYear',//双十一查询年华投资
    'api_activity_lakeBeansExchange'=>'activity_lakeBeansExchange',//双十一湖豆兑换

    /**
     *  缓存清理
     */
    'api_cache_clearredisenum'      => 'cache_clearredisenum' ,  //缓存清理
    'api_cache_clear'               => 'cache_clear',  //borrow_list列表缓存
    'api_puser_repayinfo'           => 'puser_repayinfo',    //查询是否有待收信息
    'api_cache_appVersion'          => 'cache_appVersion',   //APP版本信息缓存
    'api_cache_addRathHike'         => 'cache_addRathHike',  //全场加息缓存
    'api_cache_addRedis'            => 'cache_addRedis',    //添加进redis

    'api_borrow_pchomeinfo'        =>  'borrow_pchomeinfo',     //首页标信息

    'api_user_addresslist'         => 'user_addresslist',                   //用户地址列表
    'api_user_couponinfo'       =>  'user_couponinfo',//年化券账户总览
    'api_corder_list_v2'           => 'corder_list_v2',           //订单列表
    'api_user_modifyMoblie' => 'user_modifyMoblie',               //修改注册手机号

    'api_user_taskparcel'       => 'user_taskparcel',       //任务大礼包
    'api_turntable_mygifts'        => 'gift_mylist',                        //快乐抽奖我的礼品（会员中心）
    'api_user_addressadd'        => 'user_addressadd',          //添加收货地址
    'api_user_addressdetail'     => 'user_addressdetail',       //收货地址详情
    'api_user_addressdefault'    => 'user_addressdefault',      //设置为默认地址
    'api_user_addressupdate'     => 'user_addressupdate',       //地址更新
    'api_user_addressdelete'     => 'user_addressdelete',       //地址删除

    /**
    * 合并红包券
    */
    'api_reward_mergePreview'      =>  'reward_mergePreview',   //合并红包券预览
    'api_reward_merge'             =>  'reward_merge',          //合并红包券操作
    'api_reward_mergeDetail'       =>  'reward_mergeDetail',    //合并红包券详情
    'api_borrow_pchomeinfo'        =>  'borrow_pchomeinfo',     //首页标信息

    /**
     * 站内信
     */
    'api_webMessage_list'       =>  'webMessage_list',//站内信消息列表
    'api_webMessage_readMark'   =>  'webMessage_readMark',//标为已读
    'api_webMessage_delete'     =>  'webMessage_delete',//删除
    'api_webMessage_amountOfWithoutRead'    =>  'webMessage_amountOfWithoutRead',//未读消息数量

    /**
     * 兑吧生成url
     */
    'api_duiBa_url' =>  'duiBa_url',    //兑吧生成url

    'api_statistics_userRegisterLog' => 'statistics_userRegisterLog',        //用户注册统计

    /**
     *  wap
     */
    'api_article_list'           => 'article_list',             //公告列表
    'api_article_detail'         => 'article_detail',           //公告详情
    'api_article_sortList'      => 'article_sortList',          //资讯分类

    'api_borrow_interesttotal'   => 'borrow_interesttotal',     //累计收益

    'api_account_tenderinfo'     => 'account_tenderinfo',       //app投资记录账户总览

    'api_appAccount_setLazyInfo'    =>  'appAccount_setLazyInfo',//懒人投设置信息
    'api_appAccount_getLazyInfo'    =>  'appAccount_getLazyInfo',//懒人投信息
    'api_cuser_querywithhold_v2'    =>  'cuser_querywithhold_v2',//查询用户新浪委托扣款开关信息
    'api_cuser_openWithhold4User_v2'    =>  'cuser_openWithhold4User_v2',//跳转到新浪设置委托扣款
    'api_appAccount_openLazy'           =>  'appAccount_openLazy',//初始化用户懒人投开关数据
    /**
     *  第三方统计api
     */
    'api_app_oMyGreen'           => 'borrow_oMyGreen',             //公告列表


    'api_bank_enterprisebinding'=>'bank_enterprisebinding', //企业添加银行卡
	
	 'api_account_rechargeFuiOu'=>'account_rechargeFuiOu',   //富友短信验证码发送 3.1

    'api_account_rechargeApplyFuiOu'=>'account_rechargeApplyFuiOu',//富友支付接口 3.2

    'api_account_getVerificationCodeWithProtocol'=>'account_getVerificationCodeWithProtocol',//富友短信验证码发送 3.4

    'api_account_fyrecharge'=>'account_fyrecharge',             //富友支付接口3.6
    
    'api_sms_batchsend' => 'sms_batchsend',                   //批量发送短信

	'api_bank_youDunSelect' => 'bank_youDunSelect',   //有盾查询

	'api_realNameAuthentication' => 'bank_realNameAuthentication'   //富友+有盾查询

);
$config['api_list'] = $apilist;