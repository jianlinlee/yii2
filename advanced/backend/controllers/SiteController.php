<?php

namespace backend\controllers;

use backend\models\Customer;
use backend\models\User;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\check\CheckLogin;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'actions' => ['login', 'error'],
//                        'allow' => true,
//                    ],
//                    [
//                        'actions' => ['logout', 'index'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        if (isset($session['user']) && !empty($session['user'])) {
            $this->redirect(['customers/index']);
            return;
        }
        $this->redirect(['site/login']);
    }

    /**
     * Login action.
     * @return string
     */
    public function actionLogin()
    {
        $session = Yii::$app->session;
        if (isset($session['user']) && !empty($session['user'])) {
            $this->redirect(['customers/index']);
            return;
        }

        $model = new User();
        $data = Yii::$app->request->post();

        if (!empty($data)) {
            // 登陆
            $st = $this->doLogin($data);
            switch ($st['st']) {
                case 0:
                    $this->redirect(['customers/index']);
                    break;
                case 1:
                    break;
                case 2:
                    $model->name = $st['name'];
                    break;
                default:
                    break;
            }
            $model->pwd = '';
            return $this->render('login', [
                'model' => $model,
            ]);
        } else {
            Yii::$app->session->remove('user');
        }
        $model->pwd = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function doLogin($data) {
        $query = new Query();
        $info = $query
            ->select('name,pwd')
            ->from('user')
            ->where('name = :name',[':name'=>$data['User']['name']])
            ->one();
        if (empty($info)) {
            $ret = ['st'=>1];
            return $ret;// 不存在的用户
        }
        if ($info['pwd'] != $data['User']['pwd']) {
            $ret = ['st'=>2,'name'=>$data['User']['name']];
            return $ret;// 密码错误
        }
        $session = Yii::$app->session;
        $session->set('user',$info['name']);
        return ['st'=>0];// 登陆成功
    }

    /**
     * Logout action.
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->session->remove('user');// 这里删除session不生效，为什么？
        $this->redirect(['site/login']);
    }
}
