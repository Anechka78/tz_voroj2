<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\DataForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

$this->title = 'Update data';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
	<p>Please fill out the following fields to update data:</p>

	<div class="row">
		<div class="col-lg-6">

	<?php $form = ActiveForm::begin([
		'action' => '#',
		'options' => ['id' => 'update-data-form'],
		]) ?>
		<?php $model->type_request = 'GET';?>
		<?=	$form->field($model, 'type_request')->radioList(['GET' => 'GET', 'POST' => 'POST']) ?>
		<?=	$form->field($model, 'id')->textInput(['placeholder' => 'Enter data ID', 'required'=> 'true']) ?>
		<?= $form->field($model, 'token')->textInput(['placeholder' => 'Enter authorization key', 'required'=> 'true']) ?>
		<?= $form->field($model, 'data')
		->textarea(['rows' => 6, "id" => "data-textarea"])
		->label(Html::tag("span", 'Enter the code manually', ["id" => "save-data-span"])) ?>
		<div class="form-group">
			<?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
		</div>
	<?php ActiveForm::end() ?>
		</div>
    </div>
</div>

<?php
$js = <<<HERE
$(function() {

    $('body').on('submit', '#update-data-form', function(event) {
		event.preventDefault();
		const url = '/data/update-data';
		var method = $('input[name="DataForm[type_request]"]:checked').val();
		const token = $('#update-data-form input[name="DataForm[token]"]').val();
		const id = $('#update-data-form input[name="DataForm[id]"]').val();
		var data = $('#update-data-form textarea[name="DataForm[data]"]').val();

		data = JSON.stringify(data);

		if(method == 'POST'){
			const headers = {
			  'Authorization': 'Bearer '+token
			};
			var csrfToken = $('meta[name="csrf-token"]').attr("content");
			var formData = new FormData();
			formData.append('id', id);
			formData.append('code', data);
			formData.append('_csrf', csrfToken);

			$.ajax({
			  url: url,
			  type: method,
			  data: formData,
			  processData: false,
			  contentType: false,
			  dataType: 'json',
			  headers: headers,
			  success: function(resp) {
				message('success', resp.message);
				$('#update-data-form input[name="DataForm[id]"]').val('');
				$('#update-data-form textarea[name="DataForm[data]"]').val('');
			  },
			  error: function(xhr, status, error) {
				console.log('Request failed.  Returned status of ' + xhr.status);
				message('error', xhr.responseText);
			  }
			});
		}else if(method == 'GET'){
			const headers = {
			  'Authorization': 'Bearer '+token,
			  'Content-Type': 'application/json'
			};

			$.ajax({
			  url: url +'?id='+id+'&code='+ encodeURIComponent(data),
			  type: method,
			  dataType: 'json',
			  headers: headers,
			  success: function(resp) {
				message('success', resp.message);
				$('#update-data-form input[name="DataForm[id]"]').val('');
				$('#update-data-form textarea[name="DataForm[data]"]').val('');
			  },
			  error: function(xhr, status, error) {
				console.log('Request failed.  Returned status of ' + xhr.status);
				message('error', xhr.responseText);
			  }
			});
		}
	});


});
HERE;

$this->registerJs($js, View::POS_END);
?>
