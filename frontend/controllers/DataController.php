<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Data;
use frontend\models\Token;
use common\models\User;
use frontend\models\DataForm;

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

}
