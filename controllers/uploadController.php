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
            $msgerror[] = null;
            $conterror = 0;
            $tipomsg = null;
            $i = 1;
            if (Yii::$app->request->isPost) {
                $model->file = UploadedFile::getInstance($model, 'file');
                if ($model->upload()) {
                    $handle = fopen('upload/'.$model->file, "r");
                    
                     while (($fileop = fgetcsv($handle, 1000, ";")) !== false) 
                     {                        
                        $venta = new Venta();
                        if ($fileop[0] <> '' And is_numeric($fileop[0]) ){
                            $venta->cliIdent = $fileop[0];
                        } else {
                            $conterror = 1;
                            $msgerror[] = "El número de identificación (".$fileop[0]. ") no es numérico ó esta vacio  en la fila " .$i;
                        }
                        if ($fileop[1] <> ''){
                            $venta->cliTipIdent = $fileop[1];
                        } else {
                            $conterror = 1;
                            $msgerror[] = "El tipo de identificación no puede estar vacia en la fila " .$i;
                        }                        
                        $venta->cliNombres = $fileop[2];
                        $venta->cliApellidos = $fileop[3];
                        $venta->empresa = $fileop[4];
                        $venta->cliSexo = $fileop[5];
                        $venta->cliCiuResidencia = $fileop[6];
                        $venta->cliDepResidencia = $fileop[7];
                        $venta->telefono1 = $fileop[8];
                        $venta->telefono2 = $fileop[9];
                        $venta->cliCelular = $fileop[10];
                        $venta->cliDirResidencia = $fileop[11];
                        $venta->cliEmail = $fileop[12];
                        $venta->vehVin = $fileop[13];
                        $venta->vehMarca = $fileop[14];
                        $venta->vehVersion = $fileop[15];
                        $venta->vehModeloAnio = $fileop[16];
                        $venta->vehColor = $fileop[17];
                        $venta->vehPlaca = $fileop[18];
                        $venta->concNombre = $fileop[19];
                        $venta->nomSala = $fileop[20];
                        $venta->concCiudad = $fileop[21];
                        $venta->nomVendedor = $fileop[22];
                        $venta->fecEntVeh = $fileop[23];
                        $venta->fechaCarga = $fileop[24];
                        $venta->nFactura = $fileop[25];
                        $venta->nOrden = $fileop[26];
                        $venta->fechaNacimiento = $fileop[27];
                        $venta->estrato = $fileop[28];
                        $venta->estadoCivil = $fileop[29];
                        $venta->concatenado = $fileop[30];
                        $venta->codConcesionario = $fileop[31];                                                                        
                        $i = $i + 1;
                     }                     
                     if ($conterror == 0) 
                     {                        
                        $venta->insert();
                        $msg = "Información importada correctamente"; 
                        //return $this->redirect(['upload/subir','msg' => $msg]);                                                                         
                     }else {
                        $msg = "Información no importada, por favor verificar!";
                        $tipomsg = "danger";
                     }
                     fclose($handle);
                     unlink('upload/'.$model->file);
                }else{
                    $msg = "El Archivo no se pudo importar";
                }
            }            
            return $this->render("subir", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg, "msgerror" => $msgerror]);
        }                      
    }