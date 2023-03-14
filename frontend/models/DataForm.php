<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Data;

/**
 * Data form
 */
class DataForm extends Model
{
    public $token;
    public $type_request;
    public $data;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['token', 'trim'],
            ['token', 'required'],
			[['token', 'data'], 'string'],
            ['data', 'safe'],
            [['type_request'], 'in', 'range' => ['GET', 'POST']],
        ];
    }

    public function savedata($user_id)
     {
        $data = new Data();
        $data->data = $this->data;
        $data->user_id = $user_id;
        $data->save();
        $id = $data->id;

         return $id;
     }

}
