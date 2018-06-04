<?php

namespace common\thirdapi;
use common\rule\Tripledes;

/**
 * 盛大接口接入
 * Class bonusbind
 * @package common\rules
 */
class SdBonus
{
    public $SOURCE = 'EDJXD';
    public $SD_KEY = 'b28d5e43423b7cf4618abf6d4f984358';// key
    public $SD_3DES_KEY = 'C205BC5839533270jUN1d77Y';// key3des

    public function bindSdbonus($params)
    {
        $url = 'http://ssdl.auto1768.com.cn:8042/ShengDaAutoPlatform/car!receiveOrder';
//        $params = json_encode($params);
//        $params .= $this->SD_KEY;
//        var_dump($params);
        // 加密
        $scrypt = new \common\rule\Crypt3des();
        $crypt = $scrypt->sdencrypt($params,$this->SD_KEY,$this->SD_3DES_KEY);// 加密
        $data['encryptJsonStr'] = $crypt;
        $apirequest = new \common\rule\ApiRequest();
        $res = json_decode($apirequest->HttpPost($url, $data),true);
        var_dump($res);die;
//        $crypt = $scrypt->encrypt($params);
        var_dump($crypt);
        die;
        $uncrypt = $scrypt->decrypt($crypt);
        var_dump($uncrypt);
        die;



        // 接口调用
        $apirequest = new \common\rule\ApiRequest();
        $res = json_decode($apirequest->HttpPost($url, $params),true);
        var_dump($res);die;
        return $res;
    }
}