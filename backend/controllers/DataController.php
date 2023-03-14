<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Data;
use common\models\User;
use yii\helpers\Json;
use yii\web\Response;
use stdClass;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class DataController extends Controller
{

	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['view', 'delete'],
				'rules' => [
					[
						'allow' => true,
						'actions' => ['view', 'delete'],
						'roles' => ['admin'],
					]
				],
			],
		];
	}



	public function actionView()
	{
		$query = Data::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		return $this->render('view', [
			'dataProvider' => $dataProvider,
		]);

	}

	public function actionViewData($id)
	{
		$model = $this->findModel($id);

		return $this->renderAjax('_view_data_modal', ['model' => $model]);

	}

	/**
	 * Deletes an existing Data model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return array
	 */
	public function actionDelete($id)
	{
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			$model = $this->findModel($id);
			if($model->delete()){
				return [
					'status' => 'success',
					'message' => 'Data has been deleted successfully',
				];
			}else{
				return [
					'status' => 'error',
					'message' => 'An error occurred while deleting the data',
					'errors' => $model->getErrors()
				];
			}

		}else{
			throw new BadRequestHttpException('Invalid request format. This action allow only ajaxRequest.');
		}


	}

	/**
	 * Finds the Data model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Data the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Data::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

}
