<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $name 用户名
 * @property string $password 密码
 * @property int $level 级别
 * @property int $status 0 正常 1 禁用
 * @property string $createtime 创建时间
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_ACTIVE = 0;// 0 正常 1 禁用
    public $_user;

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
            [['name', 'password'], 'required'],
            [['level', 'status'], 'integer'],
            [['createtime'], 'safe'],
            [['name'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 64],
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
            'password' => 'Password',
            'level' => 'Level',
            'status' => 'Status',
            'createtime' => 'Createtime',
        ];
    }

    public function login()
    {
        if ($this->validate()) {
//            var_dump($this->getUser());die;
//            var_dump(Yii::$app->user->login($this->getUser(), 3600 * 8));die;
            return Yii::$app->user->login($this->getUser(), 3600 * 8);
        }
        return false;
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = self::findByUsername($this->name);
        }

        return $this->_user;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['name' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public $authKey;
    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
