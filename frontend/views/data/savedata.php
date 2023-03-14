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
		'action' => '#',
		'options' => ['id' => 'send-data-form'],
		]) ?>
		<?php $model->type_request = 'GET';?>
		<?=  $form->field($model, 'type_request')->radioList(['GET' => 'GET', 'POST' => 'POST']) ?>
		<?= $form->field($model, 'token')->textInput(['placeholder' => 'Enter authorization key', 'required'=> 'true']) ?>
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
					$('#data-textarea').val(res.data);
				}
            }
        });
    });

    $('body').on('submit', '#send-data-form', function(event) {
		event.preventDefault();
		const url = '/data/save-data';
		var method = $('input[name="DataForm[type_request]"]:checked').val();
		const token = $('#send-data-form input[name="DataForm[token]"]').val();
		var data = $('#send-data-form textarea[name="DataForm[data]"]').val();

		try {
			const jsonObject = JSON.parse(data);
		} catch (error) {
			if(!((typeof data == 'string') && (data.length === 0))){
				message('error', 'Invalid data format. The variable does not contain a JSON object');
				$('#data-textarea').removeClass('is-valid');
				$('#data-textarea').val('');
				return false;
			}
		}
		data = JSON.stringify(data);

		const headers = {
		  'Authorization': 'Bearer '+token,
		  'Content-Type': 'application/json'
		};

		if(method == 'POST'){
			$.ajax({
			  url: url,
			  type: method,
			  data: data,
			  dataType: 'json',
			  headers: headers,
			  success: function(resp) {
				message('success', resp.message+' id='+resp.id+' time_usage='+resp.time_usage+' memory_usage='+resp.memory_usage);
			  },
			  error: function(xhr, status, error) {
				console.log('Request failed.  Returned status of ' + xhr.status);
				message('error', xhr.responseText);
			  }
			});
		}else if(method == 'GET'){
			$.ajax({
			  url: url +'?data='+ encodeURIComponent(data),
			  type: method,
			  dataType: 'json',
			  headers: headers,
			  success: function(resp) {
				message('success', resp.message+' id='+resp.id+' time_usage='+resp.time_usage+' memory_usage='+resp.memory_usage);
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
