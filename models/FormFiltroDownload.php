<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroDownload extends Model
{    
    public $fecha_desde;
    public $fecha_hasta;

    public function rules()
    {
        return [                        
            [['fecha_desde','fecha_hasta'], 'safe'],                       
        ];
    }

    public function attributeLabels()
    {
        return [
            'fecha_desde' => 'Fecha Desde:',   
            'fecha_hasta' => 'Fecha Hasta:',                  
        ];
    }
}