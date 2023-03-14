<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Data;
use frontend\models\Token;
use common\models\User;
use frontend\models\DataForm;
use Faker\Factory;
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
	/** @var Token */
	private $_token;

	public function beforeAction($action)
	{
		if ($action->id == 'save-data') {
			$authHeader = Yii::$app->request->headers->get('Authorization');
			if ($authHeader !== null) {
				$authData = explode(' ', $authHeader);
				if (count($authData) === 2 && $authData[0] === 'Bearer') {
					$token = Token::findByToken($authData[1]);
					if($token instanceof Token){
						if (!Yii::$app->getUser()->getIsGuest()) {
							$user = Yii::$app->getUser();
							if($user->id != $token->user_id){
								throw new \yii\web\UnauthorizedHttpException('You do not have permission to use this token. Please request your own.');
							}
						}
						$this->_token = $token;
						return parent::beforeAction($action);
					}
				}
			}

			throw new \yii\web\UnauthorizedHttpException('Your token is invalid or outdated. Please request a new one.');
		}


		return parent::beforeAction($action);
	}


	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['put-data', 'save-data', 'update'],
				'rules' => [
					[
						'allow' => true,
						'actions' => ['put-data', 'save-data'],
						'roles' => ['user', 'guest'],
					],
					[
						'allow' => true,
						'actions' => ['update'],
						'roles' => ['user'],
						'matchCallback' => function ($rule, $action) {
							$id = Yii::$app->request->get('id');
							$model = $this->findModel($id);
							return $model->user_id === Yii::$app->user->id;
						},
					],
				],
			],
		];
	}

	/**
     * Form for user input of data
     *
     * @return DataForm
     */
	public function actionPutData()
    {		
        $data = new DataForm();
				
        return $this->render('savedata', [
            'model' => $data,
        ]);        
        
    }

	public function actionView()
	{
		$user = Yii::$app->getUser();
		if (!$user->can('view')) {
			throw new ForbiddenHttpException('You are not allowed to perform this action.');
		}
		$query = Data::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		return $this->render('viewdata', [
			'dataProvider' => $dataProvider,
		]);

	}

	/**
	 * Form for user input of data
	 *
	 * @return DataForm
	 */
	public function actionSaveData()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$model = new DataForm();

		if(!Yii::$app->request->isAjax){
			throw new BadRequestHttpException('Invalid request format. This action allow only ajaxRequest.');
		}
		$data = '';

		if (Yii::$app->request->isPost) {
			$data = Yii::$app->request->post();
		}elseif(Yii::$app->request->isGet){
			$getData = Yii::$app->request->get();
			$data = isset($getData['data']) ? json_decode($getData['data']) : null;
		}

		/** @var User $user */
		$user = User::find()->where('id = :id', [':id' => $this->_token->id])->one();
		$model->token = $this->_token->code;
		$model->data = $data;

		if ($model->validate()) {
			$info = $model->savedata($user->id);
			Yii::$app->response->statusCode = 201;
			$memory_usage = memory_get_peak_usage(true);
			$time_usage = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
			return [
				'status' => 'success',
				'message' => 'Data has been saved successfully',
				'id' => $info,
				'time_usage' => $time_usage,
				'memory_usage' => $memory_usage,
			];
		} else {
			Yii::$app->response->statusCode = 400;
			return [
				'status' => 'error',
				'message' => 'Data validation failed',
				'errors' => $model->getErrors()
			];
		}
	}
	
	/**
     * Create random JSON obj
     *
     * @return array
     */
	public function actionCreateData()
    {
		Yii::$app->response->format = Response::FORMAT_JSON;
		
        $faker = Factory::create();   
		$res = [];
		// generate the object with nested arrays
		$object = [
			'name' => $faker->name,
			'email' => $faker->email,
			'age' => $faker->numberBetween(18, 99),
			'address' => [
				'street' => $faker->streetAddress,
				'city' => $faker->city,
				'state' => $faker->state,
				'zip' => $faker->postcode,
				'phone' => $faker->phoneNumber,
				'nested_array' => self::generateRandomNestedArray($faker),
			],
		];
		
		// convert the object to JSON
		$json = Json::encode($object);
		
		$res['data'] = $json;
		
        return $res;
    }
	
	public static function generateRandomNestedArray($faker, $depth = 0)
	{
		$result = new stdClass(); // создаем новый пустой объект stdClass

		// generate between 1 and 5 elements for the array
		$numElements = $faker->numberBetween(1, 5);

		for ($i = 0; $i < $numElements; $i++) {
			if ($depth < 2) {
				// generate a nested object with a maximum depth of 2
				$result->{$faker->word} = self::generateRandomNestedArray($faker, $depth + 1);
			} else {
				// generate a random value for the object property
				$result->{$faker->word} = $faker->randomElement([$faker->word, $faker->numberBetween(1, 100)]);
			}
		}

		return $result;
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
