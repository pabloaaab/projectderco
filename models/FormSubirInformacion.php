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
    public $file;    

    public function rules()
    {
        return [
            ['file', 'required', 'message' => 'Campo requerido'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls,xlsx', 'checkExtensionByMimeType' => false,],            
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => 'Archivo XLSX,XLS:',            
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->file->saveAs('upload/' . $this->file->baseName . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }
    }
}
