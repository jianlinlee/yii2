<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "bonuslist".
 *
 * @property string $id
 * @property int $cid 用户id
 * @property string $type 券种
 * @property string $code 券码
 * @property string $bindtime 绑定时间
 * @property string $usetime 使用时间
 * @property string $lasttime 最后绑定时间
 * @property int $status 0 本月未绑定 1 本月已绑定
 */
class Bonuslist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bonuslist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cid', 'type', 'code', 'bindtime', 'usetime', 'lasttime'], 'required'],
            [['cid', 'status'], 'integer'],
            [['bindtime', 'usetime', 'lasttime'], 'safe'],
            [['type'], 'string', 'max' => 20],
            [['code'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cid' => 'Cid',
            'type' => 'Type',
            'code' => 'Code',
            'bindtime' => 'Bindtime',
            'usetime' => 'Usetime',
            'lasttime' => 'Lasttime',
            'status' => 'Status',
        ];
    }
}
