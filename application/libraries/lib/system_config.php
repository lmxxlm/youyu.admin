<?php

//系统配置
class System_config
{

    private $ci;

    function __construct()
    {
        $this->ci = &get_instance();
    }

    //获得系统名称
    public function get_value($nid = '')
    {
        if (!$nid) {
            return '';
        }
        $system = self::system_list();
        foreach ($system as $sys) {
            if ($sys['nid'] == $nid) {
                return $sys['value'];
            }
        }
        return '';
    }

    //系统邮件参数
    public function email_params()
    {
        $params = array();
        $params['webname'] = self::get_value('con_webname');
        $params['email_auth'] = self::get_value('con_email_auth');
        $params['email_host'] = self::get_value('con_email_host');
        $params['email_email'] = self::get_value('con_email_email');
        $params['email_pwd'] = self::get_value('con_email_pwd');
        $params['email_from'] = self::get_value('con_email_from');
        $params['email_from_name'] = self::get_value('con_email_from_name');
        return $params;
    }

    //站点缓存
    private function system_list()
    {
        $this->ci->load->driver('cache');
        $cache_name = 'system.cache';
        $system_list = $this->ci->cache->file->get($cache_name);
        if (!$system_list) {
            $this->ci->load->model('admin/system_model');
            $system_list = $this->ci->system_model->getSystemParams();
            $this->ci->cache->file->save($cache_name, $system_list, 86400);
        }
        return $system_list;
    }
}