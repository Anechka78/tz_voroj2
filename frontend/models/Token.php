<?php

namespace frontend\models;


use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use common\models\User;

/**
 * Token Active Record model.
 *
 * @property integer $user_id
 * @property string $code
 * @property integer $created_at
 * @property integer $type 
 * @property bool $isExpired
 * @property User $user
 */
class Token extends ActiveRecord
{
    /** @const int Срок кодности токена */
    const TTL = 8640000; // todo изменить на 300 после написания обвязки  

    /** @const int Тип токена "Аутентификация" */
    const TYPE_ACCESS = 0;
    

    /** @inheritdoc */
    public static function tableName()
    {
        return 'token';
    }

    /** @inheritdoc */
    public static function primaryKey()
    {
        return ['user_id', 'code', 'type'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }



    /**
     * Finds a token model by the specified token code.
     *
     * @param string $token the token code to look for.
     * @return Token|false the token model, or false if not found or expired.
     *
     */
    public static function findByToken($token)
    {
        $tokenModel = static::findOne(['code' => $token]);
        if (!$tokenModel || ($tokenModel->created_at + Token::TTL) < time()) {
            return false;
        }
        return $tokenModel;

    }
    

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if ($insert) {
            static::deleteAll(['user_id' => $this->user_id, 'type' => $this->type]);
            $this->setAttribute('created_at', time());
            $this->setAttribute('code', Yii::$app->security->generateRandomString());
        }

        return parent::beforeSave($insert);
    }
}
