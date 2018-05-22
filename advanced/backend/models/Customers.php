<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customers".
 *
 * @property string $id
 * @property string $phone 手机号
 * @property string $package 权益包
 * @property string $creater 录入管理员
 * @property string $createtime 录入时间
 * @property string $bak 备注
 * @property int $status 0 已绑定 1 未绑定
 */
class Customers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'creater', 'createtime'], 'required'],
            [['createtime'], 'safe'],
            [['status'], 'integer'],
            [['phone'], 'string', 'max' => 11],
            [['package'], 'string', 'max' => 30],
            [['creater'], 'string', 'max' => 32],
            [['bak'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'package' => 'Package',
            'creater' => 'Creater',
            'createtime' => 'Createtime',
            'bak' => 'Bak',
            'status' => 'Status',
        ];
    }
}
