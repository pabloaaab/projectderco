<?php

namespace app\controllers;

use Codeception\Lib\HelperModule;
use yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Venta;
use app\models\Posventa;
use app\models\Concesionario;
use app\models\Taller;
use app\models\FormSubirInformacion;
use yii\helpers\Url;
use yii\web\UploadedFile;

    class UploadController extends Controller
    {              
        public function actionSubir()
        {
            $model = new FormSubirInformacion();
            $msg = null;            
            if (Yii::$app->request->isPost) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->upload()) {                                        
                    $msg = "El registro seleccionado se importó correctamente";                    
                }else{
                    $msg = "El registro seleccionado no se pudo importar";
                }
            }            
            return $this->render("subir", ["model" => $model, "msg" => $msg]);
        }                      
    }