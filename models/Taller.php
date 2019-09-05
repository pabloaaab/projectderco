<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "taller".
 *
 * @property int $id
 * @property string $taller
 */
class Taller extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'taller';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['taller'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'taller' => 'Taller',
        ];
    }
}
