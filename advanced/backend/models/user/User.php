<?php

namespace backend\models\user;

use Yii;

/**
 * This is the model class for table "ad_user".
 *
 * @property string $id 主键
 * @property string $name 名称
 * @property string $pwd 密码
 * @property string $phone 手机号
 * @property int $status 状态 0 正常 1 无效 2 禁用
 * @property int $level 级别 0 普通
 * @property string $createtime 创建时间
 * @property string $updatetime 变更时间
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ad_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'level'], 'integer'],
            [['createtime'], 'required'],
            [['createtime', 'updatetime'], 'safe'],
            [['name'], 'string', 'max' => 30],
            [['pwd'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'pwd' => 'Pwd',
            'phone' => 'Phone',
            'status' => 'Status',
            'level' => 'Level',
            'createtime' => 'Createtime',
            'updatetime' => 'Updatetime',
        ];
    }
}
