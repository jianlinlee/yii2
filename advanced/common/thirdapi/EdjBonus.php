<?php

namespace common\thirdapi;

/**
 * 绑券规则类
 * Class bonusbind
 * @package common\rules
 */
class EdjBonus
{
    // 线上环境参数
    private $serviceOnline = [
        'appkey'=>'',
        'secret'=>'',
        'bonusNumber'=>'',
        'ver'=>'3.4.2',
        'from'=>'01051580',
    ];

    // 测试环境参数
    private $serviceTest = [
        'appkey'=>'61000161',// appkey
        'secret'=>'1c9a6f53-1234-5566-9485-b153ff5bf139',// secret
        'bonusNumber'=>'922550119181',// 优惠券固定码  sn
        'ver'=>'3.4.2',// version
        'from'=>'01051580',// from
    ];

    private function getBonusParams($type) {
        if ($type == 'test') {
            return $this->serviceTest;
        } else {
            return $this->serviceOnline;
        }
    }

    public function bindbonus($url,$phone,$type)
    {
        $url .= '/customer/coupon/recharge/bind';
        $params = $this->getBonusParams($type);
        $params['phone'] = $phone;
        $params['timestamp'] = date('Y-m-d H:i:s', time());
        $params['bsn'] = time();
        $rule = new \common\rule\Rule();
        $sig = $rule->getOpensign($params, $params['secret']);// 获取sig
        $params['sig'] = $sig;
        $apirequest = new \common\rule\ApiRequest();
        $res = json_decode($apirequest->HttpPost($url, $params));
        $res->bonus_sn = $params['bonusNumber'];
        return $res;
    }

    /**
     * 根据sn获取优惠券信息
     * @param $url
     * @param $phone
     * @param $type
     */
    public function getBonusInfo($token,$url,$phone,$type) {
        $url .= '/customer/coupon/list';
        $params = $this->getBonusParams($type);
        $params['token'] = $token;
        $params['phone'] = $phone;
        $params['timestamp'] = date('Y-m-d H:i:s', time());
        $rule = new \common\rule\Rule();
        $sig = $rule->getOpensign($params, $params['secret']);// 获取sig
        $params['sig'] = $sig;
        $apirequest = new \common\rule\ApiRequest();
        $res = json_decode($apirequest->HttpPost($url, $params));
        return $res;
    }

    // 获取token的方法，上半部分获取验证码，下半部分获取token
    public function getOpenToken($url,$phone) {
        // 登陆
//        $url1 = $url.'/customer/loginpre';
//        $params = [
//            'appkey'=>'61000161',
//            'ver'=>'3.4.2',
//            'from'=>'01051580',
//            'timestamp'=>date('Y-m-d H:i:s',time()),
//            'phone'=>'18515886878',
//            'udid'=>'20000001',
//            'type'=>'1',
//            'secret'=>'1c9a6f53-1234-5566-9485-b153ff5bf139',
////            'sig'=>''
//        ];
//        $rule = new \common\rule\Rule();
//        $sig = $rule->getOpensign($params, $params['secret']);// 获取sig
//        $params['sig'] = $sig;
////        var_dump($params);die;
//        $apirequest = new \common\rule\ApiRequest();
//        $res = json_decode($apirequest->HttpPost($url1, $params));
//        var_dump($res);die;


//        $url2 = $url.'/customer/login';
//        $params = [
//            'appkey'=>'61000161',
//            'ver'=>'3.4.2',
//            'from'=>'01051580',
//            'timestamp'=>date('Y-m-d H:i:s',time()),
//            'phone'=>'18515886878',
//            'udid'=>'20000001',
//            'type'=>'1',
//            'secret'=>'1c9a6f53-1234-5566-9485-b153ff5bf139',
//            'passwd'=>'1637',
//        ];
//        $rule = new \common\rule\Rule();
//        $sig = $rule->getOpensign($params, $params['secret']);// 获取sig
//        $params['sig'] = $sig;
//        $apirequest = new \common\rule\ApiRequest();
//        $token = json_decode($apirequest->HttpPost($url2, $params));
//        var_dump($token);die;
    }
}