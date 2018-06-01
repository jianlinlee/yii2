<?php

namespace frontend\modules\v2\controllers;

use frontend\models\Bonuslist;
use frontend\models\Customers;
use yii\rest\ActiveController;
use common\rule\Rule;


class CustomersController extends ActiveController
{
    public $modelClass = 'frontend\models\Customers';
    private $secret = '1c9a6f53-1234-5566-9485-b153ff5bf1h5';

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取优惠券列表
     * @return array
     */
    public function actionGetbonus()
    {
        $getdata = \Yii::$app->request->get();
        $getdata['token'] = '5e293382581558c3937c57c147824452';// lee  ---- 测试用，记得删除
        if (!isset($getdata['phone']) || empty($getdata['phone']) || empty($getdata['token']) || empty($getdata['sig'])) {
            return [
                'code' => 2,
                'msg' => '参数缺失',
            ];
        }
        $rule = new Rule();
        $check = $rule->checkPhone($getdata['phone']);
        if (!$check) {
            return [
                'code' => 2,
                'msg' => '手机号格式错误',
            ];
        }
        if (!$rule->checkSign($getdata, $this->secret)) {
            return [
                'code' => 2,
                'msg' => '验证失败',
            ];
        }
        $customers = new Customers();
        $userinfo = $customers->getCustomersByPhone($getdata['phone']);
        if (!$userinfo) {
            return [
                'code' => 2,
                'msg' => '对不起，您尚未获得此项权益。'
            ];
        }
        if ($userinfo->status) {
            // 首次登陆，更新用户状态
            $customers->updateStatus($userinfo->id);
        }
        $data = $this->getUserBonus($getdata['token'], $userinfo->phone, $userinfo->package);
        if (isset($data['code'])) {
            return $data;// token校验失败
        }
        return [
            'code' => 0,
            'msg' => '获取成功',
            'data' => $data
        ];
    }

    public function getUserBonus($token,  $phone, $package, $st = true)
    {
        $bonuslist = new Bonuslist();
        $bonus = $bonuslist->getBonusByPhone($phone);// 获取所有优惠券
        $edjbonus = new \common\thirdapi\EdjBonus();// 调用第三方API
        $servicetype = (new \common\rule\Rule())->checkServiceSource();// 获取环境
        if (empty($bonus)) {
            // 初始化用户券 加券
            $res = $bonuslist->DefaultPackageInsert($phone, $package);
            if ($res) {
                // 绑定代驾券
                $this->bindEdjBonus($token, $phone);
                // 盛大接口调用
                $this->bindSdBonus($phone,'');// 洗车
                $this->bindSdBonus($phone,'');// 打蜡
                if ($package == 'B') {
                    $this->bindSdBonus($phone,'');// 空调清洗
                }
            }
            // 递归1次
            if ($st) {
                return $this->getUserBonus($token,  $phone, $package, false);
            }
        } else {
            $edjbonuslist = $edjbonus->getBonusInfo($token, $servicetype['edaijia'], $phone, $servicetype['type']);// 从代驾券列表中，获取有效期
            if ($edjbonuslist->code == '0') {
                $edjbonuslist = $edjbonuslist->data;
            }
            foreach ($edjbonuslist as $item) {
                $checkused[$item->sn . '/' . $item->id] = $item->usedTime;
            }

            foreach ($bonus as $key => $item) {
                $result[$key]['type'] = $item->type;
                $result[$key]['code'] = $item->code;
                $result[$key]['pic'] = $item->pic;
                $result[$key]['bindtime'] = $item->bindtime;
                $result[$key]['deadline'] = $item->deadline;
                $result[$key]['usetime'] = $item->usetime;
                if (!empty($checkused[$item->code]) && empty($item->usetime)) {
                    $result[$key]['usetime'] = $checkused[$item->code];
                    // 更新usetime字段
                    $bonuslist = new Bonuslist();
                    $bonuslist->updateEdjUsetime($item->id, $checkused[$item->code]);
                }
            }
            return $result;
        }
    }

    public function bindSdBonus($phone,$service)
    {
        $sdbonus = new \common\thirdapi\SdBonus();// 调用第三方API
        $params = [
            'source'=>'EDJXD',// 渠道号
            'shopCode'=>'',// 商户编码
            'orgSource'=>'EDJXD',// 机构来源
            'order'=>time(),// 订单号
            'randStr'=>time(),// 随机码
            'carType'=>'01',// 类型
            'userInfo'=>$phone,// 用户信息标识
            'userOrderType'=>$phone,// 订单标识类型
            'generationRule'=>'01',// 订单生成规则
            'field1'=>$service,// 字段1 服务类型
        ];
        $res = $sdbonus->bindSdbonus();
    }

    public function bindEdjBonus($token, $phone)
    {
        $bonuslist = new Bonuslist();
        $edjbonus = new \common\thirdapi\EdjBonus();// 调用第三方API
        $servicetype = (new \common\rule\Rule())->checkServiceSource();// 获取环境
//        $token = $edjbonus->getOpenToken($servicetype['edaijia'], '18515886878');// lee 获取token
        // 绑定代驾券
        $bind = $edjbonus->bindbonus($servicetype['edaijia'], $phone, $servicetype['type']);
        if ($bind->code == '0') {
            $bonusinfo = $edjbonus->getBonusInfo($token, $servicetype['edaijia'], $phone, $servicetype['type']);// 从代驾券列表中，获取有效期
            if ($bonusinfo->code == '0') {
                foreach ($bonusinfo->data as $item) {
                    $cup[$item->sn . '/' . $item->id]['limitTime'] = $item->limitTime;
                }
                $code = $bind->bonus_sn . '/' . $bind->bindId;
                $deadline = $cup[$code]['limitTime'];
                $now = date('Y-m-d H:i:s', time());
                $bonuslist->updateEdjBonus($phone, ['bindtime' => $now, 'code' => $code, 'deadline' => $deadline, 'lasttime' => $now]);
                return [
                    'code' => 0,
                    'msg' => '绑定成功',
                ];
            } else {
                return [
                    'code'=>2,
                    'msg'=>'获取券列表失败',
                ];
            }
        } else {
            return [
                'code'=>2,
                'msg'=>'绑券失败'
            ];
        }
    }


    public function actionBonuswriteoff()
    {
        $getdata = \Yii::$app->request->get();
        if (empty($getdata['code']) || empty('usetime') || empty($getdata['sig'])) {
            return [
                'code' => 2,
                'msg' => '参数缺失',
            ];
        }
        $rule = new Rule();
        if (!$rule->checkSign($getdata, $this->secret)) {
            return [
                'code' => 2,
                'msg' => '验证失败',
            ];
        }
        // 执行更新
        $bonuslist = new Bonuslist();
        $res = $bonuslist->bonusWriteoff($getdata['code'], $getdata['usetime']);
        if ($res) {
            return [
                'code' => 0,
                'msg' => '核销成功',
            ];
        } else {
            return [
                'code' => 2,
                'msg' => '核销失败',
            ];
        }
    }

}
