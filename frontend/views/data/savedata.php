<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\DataForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

$this->title = 'Add data';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
	<p>Please fill out the following fields to save data:</p>

	<div class="row">
		<div class="col-lg-6">

	<?php $form = ActiveForm::begin([
		'action' => '/data/send-data'
		]) ?>
		<?= $form->field($model, 'type_request')->radioList(['GET' => 'GET', 'POST' => 'POST']) ?>
		<?= $form->field($model, 'token')->textInput(['placeholder' => 'Enter authorization key']) ?>
		<?= $form->field($model, 'data')
		->textarea(['rows' => 6, "id" => "data-textarea"])
		->label(Html::tag("span", 'Enter the data manually', ["id" => "save-data-span"]) . ' ' . Html::a('Create a random JSON object', '#', ['class' => 'btn btn-primary', 'id' => 'create-fake-data-btn', 'style' => 'font-weight: bold']), ['id' => 'save-data-label']); ?>
		<div class="form-group">
			<?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
		</div>
	<?php ActiveForm::end() ?>
		</div>
    </div>
</div>

<?php
$js = <<<HERE
$(function() {
    $(document).on("click", "#create-fake-data-btn", function (e) {		
        e.preventDefault(); 
                
        $.ajax({
            url: '/data/create-data',
            type: 'POST',
            cache: false,
            dataType: 'json',            
            success: function(res) {
				if(typeof(res.data) != 'undefined') {
					$('#data-textarea').text(res.data);
				}
            }
        });
    });
});
HERE;

$this->registerJs($js, View::POS_END);
?>
