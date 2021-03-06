<?php

namespace backend\controllers;

use frontend\models\Bonuslist;
use Yii;
use backend\models\Customers;
use backend\models\CustomersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\rule\Rule;

/**
 * CustomersController implements the CRUD actions for Customers model.
 */
class CustomersController extends Controller
{
    public $enableCsrfValidation = false;

    public function actions()
    {
        $session = Yii::$app->session;
        if (!isset($session['user']) || empty($session['user'])) {
            $this->redirect(['site/login']);
            return;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Customers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customers model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Customers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customers();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Customers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Customers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Customers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Customers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customers::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUploadfile()
    {
        return $this->renderpartial('uploadfile');
    }

    public function actionDoupload()
    {
        echo '<pre/>';
        $post = Yii::$app->request->post();
        if ($_FILES['file_data']['error'] <= 0) {
            // 上传文件到服务器
            $filename = "D:\\Edj\\proj_cbank\\upload\\tmp\\" . md5(time()) . $_FILES['file_data']['name'];
            move_uploaded_file($_FILES['file_data']['tmp_name'], $filename);
        } else {
            $this->redirect('customers/index');
            return;
        }
        if (file_exists($filename)) {
            $phonelist = \moonland\phpexcel\Excel::import($filename, [
                'setFirstRecordAsKeys' => false,
                'setIndexSheetByName' => true,
                'getOnlySheet' => 'Sheet1',
            ]);
            if (!empty($phonelist)) {
                // 获取手机号
                $rule = new Rule();
                $now = date('Y-m-d H:i:s',time());
//                var_dump($phonelist);
                foreach ($phonelist as $key => $item) {
                    if ($rule->checkPhone($item['A'])) {
                        $params[(string)$item['A']]['phone'] = (string)$item['A'];
                        $params[(string)$item['A']]['package'] = $post['package'];
                        $params[(string)$item['A']]['creater'] = Yii::$app->session->get('user');
                        $params[(string)$item['A']]['createtime'] = $now;
                        $params[(string)$item['A']]['bak'] = $post['bak'];
                        $params[(string)$item['A']]['status'] = '1';
                    }
                }
                if (!empty($params)) {
                    $customers = new Customers();
                    // 排除已入库的手机号
                    $phones = array_keys($params);
                    $existphones = $customers->searchExistPhone($phones);
                    if (!empty($existphones)) {
                        foreach ($existphones as $item) {
                            unset($params[$item['phone']]);
                        }
                    }
                    // 批量插入手机号
                    if (!empty($params)) {
                        $customers->loadUploadCustomers($params);
                    }
                    $this->redirect('customers/index');
                }
            }

        }
    }

    public function actionMultidelete()
    {
        $ajaxdata = Yii::$app->request->post()['ids'];
        $ids = '(' . implode(',', $ajaxdata) . ')';
        $del = Customers::deleteAll("id in {$ids}");
        if ($del) {
            return 1;// 删除成功
        }
        return 0;// 删除失败
    }
}
