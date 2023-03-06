<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\DataForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Add data';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
<p>Please fill out the following fields to save data:</p>

    <div class="row">
        <div class="col-lg-5">

<?php $form = ActiveForm::begin([
	'action' => '/data/send-data'
	]) ?>
    <?= $form->field($model, 'type_request')->radioList(['GET' => 'GET', 'POST' => 'POST']) ?>
    <?= $form->field($model, 'token')->textInput(['placeholder' => 'Enter authorization key']) ?>
    <?= $form->field($model, 'data')->textarea(['rows' => 6])->label(Html::tag("span", 'Enter the data')) ?>
    <div class="form-group">
        <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end() ?>
</div>
    </div>
</div>
