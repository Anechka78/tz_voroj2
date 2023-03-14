<?php

/** @var yii\web\View $this */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\models\Data;

$this->title = 'View data';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>
<?php
Pjax::begin(['id' => 'view-data', 'enablePushState' => false, 'options' => ['url' => Url::to(['/data/view'])]]);
echo GridView::widget([
	'dataProvider' => $dataProvider,
	'layout' => "{items}\n{pager}",
	'showHeader' => true,
	'columns' => [
		'id',
		[
			'attribute' => 'user',
			'value' => function (Data $model) {
				return $model->user->username;
			}
		],
		'data',
		[
			'class' => 'yii\grid\ActionColumn',
			'template' => "{delete}{view}",
			'buttons' => [
				'view' => function ($url, Data $model) {
					return Html::a('<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"></path></svg>'
						, Yii::$app->urlManager->hostInfo.'/data/view-data?id='.$model->id,   [
							'title' => 'View data',
							'class' => 'pjax-modal',
							'data-container' => '#backend___data___view_modal_pjax'
					]);
				},
				'delete' => function ($url) {
					return Html::a('<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"></path></svg>'
						, $url, ['class' => 'pjax-delete text-danger', "data-warning" => "Delete Data?", 'data-pjax' => 0]);
				}
			]

		],
	],
]);

Pjax::end();

