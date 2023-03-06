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

class DataController extends Controller
{
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

}
