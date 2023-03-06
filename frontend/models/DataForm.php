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
			[['token', 'data'], 'string'],
            ['data', 'safe'],
            [['type_request'], 'in', 'range' => ['GET', 'POST']],
        ];
    }

}
