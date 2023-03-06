<?php

namespace common\models;

use yii\db\ActiveRecord;


class Data extends ActiveRecord
{
    public static function tableName()
    {
        return 'data';
    }

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['data'], 'string'],
            [['data'], 'safe'],
            [['user_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data' => 'Data',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}