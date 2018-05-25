<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $name 用户名
 * @property string $pwd 密码
 * @property int $level 级别
 * @property int $status 0 正常 1 禁用
 * @property string $createtime 创建时间
 */
class User extends \yii\db\ActiveRecord
{
    const ACTIVE_STATUS = 0;// 有效用户
    const FORBID_STATUS = 1;// 禁用用户

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'pwd'], 'required'],
            [['level', 'status'], 'integer'],
            [['createtime'], 'safe'],
            [['name'], 'string', 'max' => 32],
            [['pwd'], 'string', 'max' => 64],
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
            'level' => 'Level',
            'status' => 'Status',
            'createtime' => 'Createtime',
        ];
    }
}
