<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Venta;
use app\models\Posventa;
use app\models\Concesionario;
use app\models\Taller;
use yii\web\UploadedFile;


class FormSubirInformacion extends Model
{
    public $imageFile;    

    public function rules()
    {
        return [
            ['imageFile', 'required', 'message' => 'Campo requerido'],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx'],            
        ];
    }

    public function attributeLabels()
    {
        return [
            'imageFile' => 'Archivo de excel:',            
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('upload/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }
}
