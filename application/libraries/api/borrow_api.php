<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once 'base.php';

class Borrow_api extends base
{
    function __construct()
    {
        parent::__construct();
    }

    //投资操作
    function borrow_invest($data = array())
    {
        $api_name = $this->config['api_list']['api_borrow_invest'];

        $this->set_apiparameter("borrow_id", $data['borrow_id']);
        $this->set_apiparameter("money", $data['invest_money']);

        if (isset($data['paypassword']))
            $this->set_apiparameter("paypassword", $this->des_encrypt($data['paypassword']));

        $this->set_apiparameter("reward_id", $data['reward_id']);
        $this->set_apiparameter("coupon_id", $data['coupon_id']);
        $this->set_apiparameter("tender_continue", isset($data['tender_continue']) ? $data['tender_continue'] : "");
        if (isset($data['tender_resource']))
            $this->set_apiparameter("tender_resource", $data['tender_resource']);
        if (isset($data['return_url']))
            $this->set_apiparameter("return_url", $data['return_url']);
        if (isset($data['notify_url']))
            $this->set_apiparameter("notify_url", $data['notify_url']);
        if (isset($data['qifu_url'])) {
            $this->set_apiparameter("qifu_url", $data['qifu_url']);
        }
        $result = $this->send($api_name, $this->apiparameter);
        // if ($this->config['use_new_version'] == 1) {
        //     $result['card_id'] = $this->des_decrypt($result['card_id']);
        // }
        return $result;
    }

    //投资回调(无论是收款还是借款)
    function borrow_invest_back($data = array())
    {
        $api_name = $this->config['api_list']['api_borrow_invest_back'];
        $this->set_apiparameter("outer_trade_no", $data['outer_trade_no']);
        $this->set_apiparameter("inner_trade_no", $data['inner_trade_no']);
        $this->set_apiparameter("trade_status", $data['trade_status']);
        $this->set_apiparameter("notify_url", $data['notify_url']);
        return $this->send($api_name, $this->apiparameter);
    }

    //投资人收款回调
    function borrow_tender_back($data = array())
    {
        $api_name = $this->config['api_list']['api_borrow_tender_back'];
        $this->set_apiparameter("outer_trade_no", $data['outer_trade_no']);
        $this->set_apiparameter("inner_trade_no", $data['inner_trade_no']);
        $this->set_apiparameter("trade_status", $data['trade_status']);
        return $this->send($api_name, $this->apiparameter);
    }

    //借款人还款回调
    function borrow_lender_back($data = array())
    {
        $api_name = $this->config['api_list']['api_borrow_lender_back'];
        $this->set_apiparameter("outer_trade_no", $data['outer_trade_no']);
        $this->set_apiparameter("inner_trade_no", $data['inner_trade_no']);
        $this->set_apiparameter("trade_status", $data['trade_status']);
        $this->set_apiparameter("notify_url", $data['notify_url']);
        return $this->send($api_name, $this->apiparameter);
    }

    //系统还款操作
    function borrow_repay($data = array())
    {
        $api_name = $this->config['api_list']['api_borrow_repayment'];
        $this->set_apiparameter("repay_time", $data['repay_time']);
        // $this->set_apiparameter("notify_url", $data['notify_url']);
        return $this->send($api_name, $this->apiparameter);
    }

    function borrow_repay_single($data = array())
    {
        $api_name = $this->config['api_list']['api_borrow_repay_single'];
        $this->set_apiparameter("borrow_id", $data['borrow_id']);
        // $this->set_apiparameter("notify_url", $data['notify_url']);
        return $this->send($api_name, $this->apiparameter);
    }

    //标的详情页（可用余额，红包，年化券）
    function borrow_userinfo($borrow_id = 0)
    {
        $api_name = $this->config['api_list']['api_borrow_userinfo'];
        $this->set_apiparameter("borrow_id", $borrow_id);
        return $this->send($api_name, $this->apiparameter);
    }

    //标详情页投资记录
    function borrow_tender_log($borrow_id = 0)
    {
        $api_name = $this->config['api_list']['api_borrow_tender_log'];
        $this->set_apiparameter("borrow_id", $borrow_id);
        return $this->send($api_name, $this->apiparameter);
    }

    //标详情页
    function borrow_detail($borrow_id = 0)
    {
        $api_name = $this->config['api_list']['api_borrow_detail'];
        $this->set_apiparameter("borrow_id", $borrow_id);
        return $this->send($api_name, $this->apiparameter);
    }

    //投资总额
    function borrow_tender_total()
    {
        $api_name = $this->config['api_list']['api_borrow_tender_total'];
        return $this->send($api_name, $this->apiparameter);
    }

    //累计收益
    function get_borrow_interesttotal()
    {
        $api_name = $this->config['api_list']['api_borrow_interesttotal'];
        return $this->send($api_name, $this->apiparameter);
    }

    //新手标
    function borrow_new_hand()
    {
        $api_name = $this->config['api_list']['api_borrow_new_hand'];
        return $this->send($api_name, $this->apiparameter);
    }

    //推荐标
    function borrow_recommend()
    {
        $api_name = $this->config['api_list']['api_borrow_recommend'];
        return $this->send($api_name, $this->apiparameter);
    }

    /**
     * 预告标
     * @param int $borrow_num
     * @return string
     */
    function borrow_prepare($borrow_num = 3)
    {
        $api_name = $this->config['api_list']['api_borrow_prepare'];
        $this->set_apiparameter("borrow_num", $borrow_num);
        return $this->send($api_name, $this->apiparameter);
    }

    //项目列表
    function borrow_list($data = array())
    {
        $api_name = $this->config['api_list']['api_borrow_list'];
        $this->set_apiparameter("cur_page", $data['cur_page']);
        $this->set_apiparameter("page_size", $data['page_size']);
        if (isset($data['borrow_type']))
            $this->set_apiparameter("borrow_type", $data['borrow_type']);
        if (isset($data['borrow_status']))
            $this->set_apiparameter("borrow_status", $data['borrow_status']);
        if (isset($data['borrow_apr']))
            $this->set_apiparameter("borrow_apr", $data['borrow_apr']);
        if (isset($data['order_column']))
            $this->set_apiparameter("order_column", $data['order_column']);
        if (isset($data['order_value']))
            $this->set_apiparameter("order_value", $data['order_value']);
        if (isset($data['borrow_name'])) {
            $this->set_apiparameter("borrow_name", $data['borrow_name']);
        }
        return $this->send($api_name, $this->apiparameter);
    }

    /**
     * 债权协议书下载
     * @param int $borrow_id
     * @param int $tender_id
     * @return string
     */
    function borrow_pdf_download($borrow_id = 0, $tender_id = 0)
    {
        session_write_close();
        header("Content-Type: application/pdf");
        header('Content-Disposition: attachment; filename="' . date('YmdHis', time()) . '.pdf"');

        $api_name = $this->config['api_list']['api_borrow_protdownload'];
        $this->set_apiparameter("borrow_id", $borrow_id);
        $this->set_apiparameter("tender_id", $tender_id);

        return $this->send($api_name, $this->apiparameter, '', true);
    }



  

    /**
     * VIP预约审核
     * @param int $customization_id 预约Id
     * @param int $borrow_id 指定标ID
     * @param string $check_remark 审核备注
     * @param int $check_status 审核状态(1通过 2不通过)
     * @return string
     */
    function vipcustom_check($customization_id = 0, $borrow_id = 0, $check_remark = "", $check_status = 2)
    {
        $api_name = $this->config['api_list']['api_vipcustom_check'];
        $this->set_apiparameter("customization_id", $customization_id);
        $this->set_apiparameter("borrow_ids", $borrow_id);
        $this->set_apiparameter("check_remark", $check_remark);
        $this->set_apiparameter("check_status", $check_status);

        return $this->send($api_name, $this->apiparameter);
    }

    /**
     * vip配对
     * @param array $data
     * @return string
     */
    function vip_match($data = array())
    {
        $api_name = $this->config['api_list']['api_vipcustom_match'];
        $this->set_apiparameter("customization_id", $data['id']);
        $this->set_apiparameter("check_status", $data['check_status']);
        $this->set_apiparameter("match_remark", $data['match_remark']);
        return $this->send($api_name, $this->apiparameter);
    }

    /**
     * 用户投资立即支付操作
     * @param array $data
     * @return string
     */
    function borrow_tenderpay($data = array())
    {
        $api_name = $this->config['api_list']['api_borrow_tenderpay'];
        $this->set_apiparameter("borrow_id", $data['borrow_id']);
        $this->set_apiparameter("coupon_id", $data['coupon_id']);
        $this->set_apiparameter("tender_money", $data['tender_money']);
        $this->set_apiparameter("reward_id", $data['reward_id']);
        $this->set_apiparameter("return_url", $data['return_url']);
        if (isset($data['paypassword']))
            $this->set_apiparameter("paypassword", $this->des_encrypt($data['paypassword']));
        return $this->send($api_name, $this->apiparameter);
    }


     /**
     * 云合同操作 add by zy
     * @param array $data
     * @return string
     */
    function borrow_yunHeTong($data = array())
    {
        $api_name = $this->config['api_list']['api_borrow_yunHeTong'];
        $this->set_apiparameter("borrow_id", $data['borrow_id']);
        $this->set_apiparameter("tender_id", $data['tenderId']);
        return $this->send($api_name, $this->apiparameter);
    }

    /**
     * 云合同获取类型  add by zy
     * @param array $data
     * @return string
     */
    function borrow_yunHeJudge($data = array())
    {
        $api_name = $this->config['api_list']['api_borrow_yunHeJudge'];
        return $this->send($api_name, $this->apiparameter);
    }

    /**
     * 云合同下载操作 add by zy 20170630
     * @param array $data
     * @return string
     */
     function borrow_contractDownloading($tender_id = 0)
    {
        $api_name = $this->config['api_list']['api_borrow_contractDownloading'];
        $this->set_apiparameter("tender_id",$tender_id);
        return $this->send($api_name, $this->apiparameter);
    }

    /**
     * 智慧投 无密码支付操作
     * @param array $data
     * @return string
     */
    function borrow_tenderpayNoPayPwd($data = array())
    {
        log_message('info','borrow_tenderpayNoPayPwd 001'.json_encode($data));
        $api_name = $this->config['api_list']['api_borrow_tenderpay_nopaypwd'];
        $this->set_apiparameter("borrow_id", $data['borrow']->id);
        $this->set_apiparameter("user_id", $data['user']->user_id);
        $this->set_apiparameter("tender_money", $data['tender_money']);
        $this->set_apiparameter("reward_id", $data['reward_id']);
        $this->set_apiparameter("coupon_id", '');
        $this->set_apiparameter("return_url", '');
        // if (isset($data['paypassword']))
        //     $this->set_apiparameter("paypassword", $this->des_encrypt($data['paypassword']));
        log_message('info','borrow_tenderpayNoPayPwd 002');
        return $this->send($api_name, $this->apiparameter);
    }

    /**
     * 开启或关闭委托扣款状态
     * @param string $return_url
     * @return string
     */
    function cuser_setwithhold($return_url = '')
    {
        $api_name = $this->config['api_list']['api_cuser_setwithhold_v2'];
        $this->set_apiparameter("return_url", $return_url);
        return $this->send($api_name, $this->apiparameter);
    }

    /**
     * 查询委托扣款状态结果
     * @return string
     */
    function cuser_querywithhold()
    {
        $api_name = $this->config['api_list']['api_cuser_querywithhold_v2'];
        return $this->send($api_name, $this->apiparameter);
    }

    /**
     * 提前还款
     * @param int $borrow_id
     * @param string $notify_url
     * @return string
     */
    function borrow_repayadvance($borrow_id = 0, $notify_url = '')
    {
        $api_name = $this->config['api_list']['api_borrow_repayadvance'];
        $this->set_apiparameter("borrow_id", $borrow_id);
        $this->set_apiparameter("notify_url", $notify_url);
        return $this->send($api_name, $this->apiparameter);
    }

    /**
     * 后台懒人投设置->立即投资
     * @param $borrow_id
     * @param $money
     * @return string
     */
    function borrow_lazyTender_v2($borrow_id, $money)
    {
        $api_name = $this->config['api_list']['api_borrow_lazyTender_v2'];
        $this->set_apiparameter("borrow_id", $borrow_id);
        $this->set_apiparameter("money", $money);
        return $this->send($api_name, $this->apiparameter);
    }

    /**
     * 标id加息
     */
    function borrow_rateHike($borrow_id)
    {
        $api_name = $this->config['api_list']['api_borrow_rateHike'];
        $this->set_apiparameter("borrow_id", $borrow_id);
        return $this->send($api_name, $this->apiparameter);
    }

    /**
     * 首页推荐标
     * @return string
     */
    function borrow_homeinfo()
    {
        $api_name = $this->config['api_list']['api_borrow_pchomeinfo'];
        return $this->send($api_name, $this->apiparameter);
    }
}