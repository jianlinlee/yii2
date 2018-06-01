<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
//        'errorHandler' => [
//            'errorAction' => 'site/error',
//        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
//            'enableStrictParsing' =>true,
            'rules' => [
                'GET <module:(v)\d+>/<controller:\w+>/search' => '<module>/<controller>/search',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v2/customers'],
                    // 由于 resetful 风格规定 URL 保持格式一致并且始终使用复数形式
                    // 所以如果你的 controller 是单数的名称比如 UserController
                    // 设置 pluralize 为 true （默认为 true）的话，url 地址必须是 users 才可访问
                    // 如果 pluralize 设置为 false, url 地址必须是 user 也可访问
                    // 如果你的 controller 本身是复数名称 UsersController ，此参数没用，url 地址必须是 users
                    'pluralize' => false,
                ],
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            //设置 api 返回格式,错误码不在 header 里实现，而是放到 body里
            'as resBeforeSend' => [
                'class' => 'frontend\extensions\ResBeforeSendBehavior',
                'defaultCode' => 500,
                'defaultMsg' => 'error',
            ],
            //ps：components 中绑定事件，可以用两种方法
            //'on eventName' => $eventHandler,
            //'as behaviorName' => $behaviorConfig,
            //参考 http://www.yiiframework.com/doc-2.0/guide-concept-configurations.html#configuration-format
        ],
    ],
    'modules' => [
        'v2' => [
            'class' => 'frontend\modules\v2\Module',
        ],
    ],
    'params' => $params,
];
