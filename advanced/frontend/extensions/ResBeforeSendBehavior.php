<?php

namespace frontend\extensions;

use Yii;
use yii\web\Response;
use yii\base\Behavior;

class ResBeforeSendBehavior extends Behavior
{

    public $defaultCode = 500;

    public $defaultMsg = 'error';

    // 重载events() 使得在事件触发时，调用行为中的一些方法
    public function events()
    {
        // 在 EVENT_BEFORE_SEND 事件触发时，调用成员函数 beforeSend
        return [
            Response::EVENT_BEFORE_SEND => 'beforeSend',
        ];
    }

    /**
     * 更改数据输出格式
     * 默认情况下输出Json数据
     * 如果客户端请求时有传递$_GET['callback']参数，输入Jsonp格式
     * 请求正确时数据为  {"success":true,"data":{...}}
     * 请求错误时数据为  {"success":false,"data":{"name":"Not Found","message":"页面未找到。","code":0,"status":404}}
     * @param \yii\base\Event $event
     */
    public function beforeSend($event)
    {
        /* @var $response \yii\web\Response */
        $response = $event->sender;

        $isSuccessful = $response->isSuccessful;
        if ($response->statusCode >= 400) {
            //异常处理
            if (true && $exception = Yii::$app->getErrorHandler()->exception) {
                $response->data = $this->convertExceptionToArray($exception);
            }
            //Model出错了
            if ($response->statusCode == 422) {
                $messages = [];
                foreach ($response->data as $v) {
                    $messages[] = $v['message'];
                }
                //请求错误时数据为  {"success":false,"data":{"name":"Not Found","message":"页面未找到。","code":0,"status":404}}
                $response->data = [
                    'name' => 'valide error',
                    'message' => implode("  ", $messages),
                    'info' => $response->data
                ];
            }
            $response->statusCode = 200;
        } elseif ($response->statusCode >= 300) {
            $response->statusCode = 200;
            $response->data = $this->convertExceptionToArray(new ForbiddenHttpException(Yii::t('yii', 'Login Required')));
        }
        //请求正确时数据为  {"success":true,"data":{...}}
        $response->data = [
            'success' => $isSuccessful,
            'data' => $response->data,
        ];

        $response->format = Response::FORMAT_JSON;
        \Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Origin', '*');
        \Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Credentials', 'true');
        //jsonp 格式输出
        if (isset($_GET['callback'])) {
            $response->format = Response::FORMAT_JSONP;
            $response->data = [
                'callback' => $_GET['callback'],
                'data' => $response->data,
            ];
        }
    }

    /**
     * 将异常转换为array输出
     * @see \yii\web\ErrorHandle
     * @param \Exception $exception
     * @return multitype:string NULL Ambigous <string, \yii\base\string> \yii\web\integer \yii\db\array multitype:string NULL Ambigous <string, \yii\base\string> \yii\web\integer \yii\db\array
     */
    protected function convertExceptionToArray($exception)
    {
        if (!YII_DEBUG && !$exception instanceof UserException && !$exception instanceof HttpException) {
            $exception = new HttpException(500, Yii::t('yii', 'An internal server error occurred.'));
        }
        $array = [
            'name' => ($exception instanceof Exception || $exception instanceof ErrorException) ? $exception->getName() : 'Exception',
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ];
        if ($exception instanceof HttpException) {
            $array['status'] = $exception->statusCode;
        }
        if (YII_DEBUG) {
            $array['type'] = get_class($exception);
            if (!$exception instanceof UserException) {
                $array['file'] = $exception->getFile();
                $array['line'] = $exception->getLine();
                $array['stack-trace'] = explode("\n", $exception->getTraceAsString());
                if ($exception instanceof \yii\db\Exception) {
                    $array['error-info'] = $exception->errorInfo;
                }
            }
        }
        if (($prev = $exception->getPrevious()) !== null) {
            $array['previous'] = $this->convertExceptionToArray($prev);
        }
        return $array;
    }
}