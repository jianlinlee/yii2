<?php

namespace frontend\models;

use Yii;

class Bonuslist extends \yii\db\ActiveRecord
{
    // 1 代驾 2 洗车 3 打蜡 4 空调清洗
    private $pak_a = ['1' => ['type' => 1, 'num' => 1], '2' => ['type' => 2, 'num' => 6], '3' => ['type' => 3, 'num' => 1]];
    private $pak_b = ['1' => ['type' => 1, 'num' => 2], '2' => ['type' => 2, 'num' => 12], '3' => ['type' => 3, 'num' => 1], '4' => ['type' => 4, 'num' => 1]];

    public static function tableName()
    {
        return 'bonuslist';
    }

    public function rules()
    {
        return [
            [[ 'type', 'bindtime', 'usetime', 'lasttime'], 'required'],
            [[ 'status'], 'integer'],
            [['bindtime', 'deadline', 'usetime', 'lasttime'], 'safe'],
            [['type'], 'string', 'max' => 20],
            [['code'], 'string', 'max' => 30],
            [['pic'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '类型',
            'code' => '优惠券码',
            'pic' => 'Pic',
            'bindtime' => '绑定时间',
            'deadline' => '使用有效日期',
            'usetime' => '使用时间',
            'lasttime' => 'Lasttime',
            'status' => 'Status',
        ];
    }

    public function getBonusByPhone($phone) {
        self::deleteAll();// lee
        return self::find()->where(['phone' => $phone])->all();// 获取所有优惠券
    }

    public function updateEdjUsetime($id, $usetime)
    {
        $res = Yii::$app->db->createCommand()->update(self::tableName(), ['usetime' => $usetime], 'id=:id', [':id' => $id])->execute();
        return $res;
    }

    public function updateEdjBonus($phone, $params)
    {
        $bonuslist = self::find()->where(['phone' => $phone, 'type' => 1, 'status' => 1])->one();
        if (!empty($bonuslist->id)) {
            $res = Yii::$app->db->createCommand()->update(self::tableName(), ['bindtime' => $params['bindtime'], 'code' => $params['code'], 'deadline' => $params['deadline'], 'lasttime' => $params['lasttime'], 'status' => 0], 'id=:id', [':id' => $bonuslist->id])->execute();
            return $res;
        } else {
            return false;
        }
    }

    /**
     * 获取权益包内容
     * @param $package
     * @return array
     */
    public function getPackageInfo($package)
    {
        switch ($package) {
            case 'A':
                return $this->pak_a;
                break;
            case 'B':
                return $this->pak_b;
                break;
            default:
                break;
        }
    }

    /**
     * 初始化加券
     * @param $phone
     * @param $package
     * @return int
     */
    public function DefaultPackageInsert($phone, $package)
    {
        $service = $this->getPackageInfo($package);
        $ii = 0;
        foreach ($service as $item) {
            for ($i = 0; $i < $item['num']; $i++) {
                $insert[$ii]['phone'] = $phone;
                $insert[$ii]['type'] = $item['type'];
                $ii++;
            }
        }
        $res = Yii::$app->db->createCommand()->batchInsert(self::tableName(), ['phone', 'type'], $insert)->execute();// 批量insert
        return $res;
    }

    public function bonusWriteoff($code, $usetime)
    {
        return Yii::$app->db->createCommand()->update(self::tableName(), ['usetime' => $usetime], 'code=:code', [':code' => $code])->execute();
    }
}
