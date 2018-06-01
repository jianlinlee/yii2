<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\datetime\DateTimePicker;
use yii\bootstrap\Modal;

use kartik\dialog\Dialog;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CustomersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '中国银行优惠券用户名单管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="customers-index">

    <p>
        <?= Html::a('数据导入', ['uploadfile'], [
            'id' => 'uploadfile',
            'data-toggle' => 'modal',
            'data-target' => '#create-modal',
            'class' => 'btn btn-success',
            'style' => 'float:left;width:100px;margin-right:20px;'
        ]) ?>
    </p>
    <?php
    Modal::begin([
        'id' => 'create-modal',
        'header' => '<h4 class="modal-title" style="color:white;">白名单数据导入</h4>',
//        'footer' => '<input type="submit" value="上传" class="btn btn-primary"><a href="#" class="btn btn-primary" id="doupload" data-dismiss="modal">lll</a><a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
        'options'=>[
            'data-backdrop'=>'static',//点击空白处不关闭弹窗
            'data-keyboard'=>false,
        ],
    ]);
    $upurl = Url::toRoute('uploadfile');
    $js = <<<JS
        $(".modal-header").css('backgroundColor','#2e6da4');
        $(".close").css('color','black');
        $.get('{$upurl}', {},
            function (data) {
                $('.modal-body').html(data);
            }
        );
JS;
    $this->registerJs($js);
    Modal::end();
    ?>
    <p>
        <?= Html::button('批量删除', [
            'type' => 'button',
            'id' => 'multidelete',
            'class' => 'btn btn-success',
            'style' => 'width:100px;'
        ]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'common\column\CustomersCheckboxColumn',
                'checkboxOptions' => function ($searchModel, $key, $index, $column) {
                    return ['value' => $searchModel->id];
                }
            ],

            'phone',
            'package',
            'creater',
            [
                'attribute' => 'createtime',
                'filter' => DateTimePicker::widget([
                    'model' => $searchModel,
                    'type' => DateTimePicker::TYPE_INPUT,
                    'attribute' => 'createtime',
                    'options' => ['class' => ''],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'minView' => 'month',
                    ]
                ]),
            ],
            'bak',
            [
                'attribute' => 'status',
                'value' => function ($searchModel) {
                    $status = [
                        '0' => '已绑定',
                        '1' => '未绑定',
                    ];
                    return $status[$searchModel->status];
                },
                'filter' => [
                    '0' => '已绑定',
                    '1' => '未绑定',
                ]
            ],

            [
                'class' => 'common\column\CustomersActionColumn',
                'template' => '{view}{delete}',
            ],
        ],
    ]); ?>
</div>
<?php
$delurl = Url::toRoute('multidelete');
$js = <<<JS
    $("#multidelete").click(function () {
        var list = [];
        $("tbody :input[type='checkbox']:checked").each(function (n, v) {
            list[n] = $(this).val();
        });
        if (list.length > 0) {
            krajeeDialog.confirm("确认要删除选中的名单？",function(st) {
                if (st === true) {
                    $.ajax({
                        type: 'POST',
                        url: '{$delurl}',
                        data: {'ids':list},
                        dataType: 'json',
                        success: function (ret) {
                            if (ret) {
                                krajeeDialog.alert('删除完成！');
                                window.location.reload();
                            } else {
                                krajeeDialog.alert('删除失败！请重试。');
                            }
                        }
                    });
                }
            });
        }
    });
JS;
$this->registerJs($js);
?>
