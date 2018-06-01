<?php

namespace common\rule;

use Yii;

class Rule
{
    private $serviceApi = 'api.cbank.local';// 测试环境域名  接口
    private $serviceAdmin = 'admin.cbank.local';// 测试环境域名  后台

    /**
     * sig验证
     * @param $params
     * @param $secret
     * @return bool|string
     */
    public function getOpensign($params, $secret)
    {
        ksort($params);// 按key排序
        $str = '';
        foreach ($params as $key => $value) {
            $str .= trim($key) . trim($value);
        }
        $str = substr(md5($secret . $str . $secret), 0, 30);
        return $str;
    }


    /**
     * 校验sig
     * @param $params
     * @param $secret
     * @return bool
     */
    public function checkSign($params, $secret)
    {
        $csign = $params['sig'];
        unset($params['sig']);
        $gsign = $this->getOpensign($params, $secret);
//        var_dump($gsign);die;
        if ($csign === $gsign) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 手机号验证
     * @param $phone
     * @return false|int
     */
    public function checkPhone($phone)
    {
        $res = preg_match('/^1[34578]\d{9}$/', $phone);
        return $res;
    }

    /**
     * 身份证号验证
     * @param $idcard
     * @return false|int
     */
    public function checkIdcard($idcard)
    {
        $res = preg_match('/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/', $idcard);
        return $res;
    }

    /**
     * 返回运行环境对应的host
     * @return array
     */
    public function checkServiceSource() {
        $host = $_SERVER['HTTP_HOST'];
        if ($host == $this->serviceApi || $host == $this->serviceAdmin) {
            return [
                'type'=>'test',
                'edaijia'=>'http://open.d.api.edaijia.cn',
                'third'=>'',
            ];
        } else {
            return [
                'type'=>'online',
                'edaijia'=>'http://open.api.edaijia.cn',
                'third'=>'',
            ];
        }
    }

}