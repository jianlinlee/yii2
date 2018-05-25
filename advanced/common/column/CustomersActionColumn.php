<?php

namespace common\column;

use Yii;
use yii\helpers\Html;
use yii\grid\ActionColumn;

class CustomersActionColumn extends ActionColumn
{
    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view', 'eye-open');
        $this->initDefaultButton('update', 'pencil');
        $this->initDefaultButton('delete', 'trash', [
            'data-confirm' => Yii::t('yii', '确定删除？'),
            'data-method' => 'post',
        ]);
    }

    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        if ($model->status != 0) {
                            return;
                        } else {
                            $url = '/index.php?r=bonuslist/index&cid='.$key;
                            $title = Yii::t('yii', '查看');
                            $opentype = '_blank';
                        }
                        break;
                    case 'update':
                        $title = Yii::t('yii', '更新');
                        break;
                    case 'delete':
                        if ($model->status != 0) {
                            $title = Yii::t('yii', '删除');
                            $opentype = '_self';
                        } else {
                            return;
                        }
                        break;
                    default:
                        $title = ucfirst($name);
                }
                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                    'target' => $opentype,
                ], $additionalOptions, $this->buttonOptions);
                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                return Html::a($icon, $url, $options);
            };
        }
    }
}
