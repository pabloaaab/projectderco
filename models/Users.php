<?php

namespace app\models;
use Yii;
use yii\db\ActiveRecord;

class Users extends ActiveRecord{

    public static function getDb()
    {
        return Yii::$app->db;
    }

    public static function tableName()
    {
        return 'users';
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConcesionario()
    {
        return $this->hasOne(Concesionario::className(), ['id' => 'id_concesionario']);
    }

}