<div class="form">
    <p>
        <div>
            <div style="display: inline-block;width: 16%;margin-left: 20px;">
                <div style="color: red;display: inline-block;">*</div>
                权益包：
            </div>
            <div style="display: inline-block;width: 350px;">
                <select name="package" id="package" style="width: 300px;">
                    <option value="A">A（代驾1次，洗车6次，打蜡1次）</option>
                    <option value="B">B（代驾2次，洗车12次，打蜡1次，空调清洗1次）</option>
                </select>
            </div>
        </div>
    </p>
    <p>
        <div>
            <div style="display: inline-block;width: 16%;margin-left: 20px;top: -10px;">
                备注：
            </div>
            <div style="display: inline-block;width: 350px;">
                <textarea name="bak" id="bak" cols="30" rows="3" style="width: 300px;"></textarea>
            </div>
        </div>
    </p>
    <p>

    </p>

    <div>
        <div style="margin-left: 20px;">
            <div style="color: red;display: inline-block;">*</div>
            请选择要导入的白名单excel文件
        </div>
        <div style="display: inline-block;margin-left: 20px;margin-top: 8px;">
            <input name="file_data" type="file" accept=".xls,.xlsx"/>
            <div class="buttons"></div>
        </div>
    </div>
</div>
<?php
$js = <<<JS
    $("#doupload").click(function () {
        alert(22);
    });
JS;
$this->registerJs($js);
?>
