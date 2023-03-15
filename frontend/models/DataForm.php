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
    public $id;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['token', 'trim'],
            ['token', 'required'],
			[['token', 'data'], 'string'],
            [['id', 'data'], 'safe'],
            [['type_request'], 'in', 'range' => ['GET', 'POST']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'token' => 'Token',
            'id' => 'ID',
            'type_request' => 'Type Request'
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

    public function updatedata()
    {
        /** @var Data $model */
        $model = Data::findOne($this->id);
        $code = $this->data;
        $data = json_decode($model->data);
        eval($code);
        $model->data = json_encode($data);
        if($model->update(false, ['data'])){
            return true;
        }
        return false;
    }


}
