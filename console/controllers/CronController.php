<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\User;
use frontend\models\Token;
use Yii;

/**
 * Cron controller
 */
class CronController extends Controller {	

	/**
	 * Action for test.
	 *     
	 */
	public function actionIndex() {
		echo "cron service runnning";
	} 
	
	/**
	 * User identification through console with return of token or error
	 * 
	 * @return string
	 */
	public function actionGetToken($username, $pass) {
		/** @var User $user */
		$user = User::find()->where('username = :username', [':username' => $username])->one();
		
		if($user){
			if(!Yii::$app->security->validatePassword($pass, $user->password_hash)){
				echo "Wrong password " . $pass;
			}else{				
				/** @var Token $token */
				$token = Yii::createObject(['class' => Token::className(), 'type' => Token::TYPE_ACCESS]);
				$token->link('user', $user);
				echo $token->code;
			}
		}else{
			echo "No such user in DB ".$username;
		}
		
	} 
		

		

}