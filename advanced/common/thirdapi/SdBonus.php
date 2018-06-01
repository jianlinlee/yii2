<?php

namespace common\thirdapi;

/**
 * 盛大接口接入
 * Class bonusbind
 * @package common\rules
 */
class SdBonus
{
    public function bindSdbonus($params)
    {
        $url = 'http://101.231.154.154:8042/ShengDaAutoPlatform/car!receiveOrder';
        $params = json_encode($params);
        $apirequest = new \common\rule\ApiRequest();
//        $res = json_decode($apirequest->HttpPost($url, $params));
        $res = json_decode($apirequest->HttpPost($url, $params),true);
        return $res;
    }
}