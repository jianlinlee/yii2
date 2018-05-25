<?php

namespace common\column;

use Closure;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\grid\CheckboxColumn;

class CustomersCheckboxColumn extends CheckboxColumn
{
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($model->status != 0) {
            if ($this->checkboxOptions instanceof Closure) {
                $options = call_user_func($this->checkboxOptions, $model, $key, $index, $this);
            } else {
                $options = $this->checkboxOptions;
            }
            if (!isset($options['value'])) {
                $options['value'] = is_array($key) ? Json::encode($key) : $key;
            }
            if ($this->cssClass !== null) {
                Html::addCssClass($options, $this->cssClass);
            }
            return Html::checkbox($this->name, !empty($options['checked']), $options);
        }
    }
}
