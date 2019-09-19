<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "concesionario".
 *
 * @property int $id
 * @property string $concesionario
 *
 * @property Users[] $users
 */
class Concesionario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'concesionario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concesionario'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'concesionario' => 'Concesionario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['id_concesionario' => 'id']);
    }
}
