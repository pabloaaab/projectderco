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
        public function actionVenta()
        {
            if (!Yii::$app->user->isGuest) {
                $model = new FormSubirInformacion();            
                $msg = null;
                $msgerror[] = null;
                $conterror = 0;
                $tipomsg = null;
                $i = 1;
                $j = 1;
                $k = 1;
                if (Yii::$app->request->isPost) {
                    $model->file = UploadedFile::getInstance($model, 'file');
                    if ($model->upload()) {
                        $handle = fopen('upload/'.$model->file, "r");                    
                         while (($fileop = fgetcsv($handle, 1000, ";")) !== false) 
                         {
                            if ($j > 1){ 
                                if ($fileop[0] == '' Or ctype_digit($fileop[0]) ){
                                    $conterror = 1;
                                    $msgerror[] = "Los nombres (".$fileop[0]. ") tienen números ó esta vacio  en la fila " .$i;
                                } 
                                if ($fileop[1] == '' Or ctype_digit($fileop[1]) ){
                                    $conterror = 1;
                                    $msgerror[] = "Los apellidos (".$fileop[1]. ") tienen números ó esta vacio  en la fila " .$i;
                                }
                                if ($fileop[2] == '' Or ctype_digit($fileop[2]) ){
                                    $conterror = 1;
                                    $msgerror[] = "El tipo de identificación (".$fileop[2]. ") tiene números ó esta vacio  en la fila " .$i;
                                } 
                                if ($fileop[3] == '' Or !ctype_digit($fileop[3]) ){
                                    $conterror = 1;
                                    $msgerror[] = "El número de identificación (".$fileop[3]. ") tiene letras ó esta vacio  en la fila " .$i;
                                }
                            }
                            $i = $i + 1;
                            $j = $j + 1;
                         }                     
                         if ($conterror == 0) 
                         {                        
                            $handle2 = fopen('upload/'.$model->file, "r");
                             while (($fileop = fgetcsv($handle2, 1000, ";")) !== false) 
                            {
                                if ($k > 1){ 
                                    $venta = new Venta();
                                    $venta->nombres = utf8_encode($fileop[0]);
                                    $venta->apellidos = utf8_encode($fileop[1]);
                                    $venta->tipoIdentificacion = $fileop[2];                            
                                    $venta->identificacion = $fileop[3];                            
                                    $venta->sexo = $fileop[4];
                                    $venta->ciudad = utf8_encode($fileop[5]);                            
                                    $venta->telefonoOficina = $fileop[6];
                                    $venta->direccionOficina = utf8_encode($fileop[7]);
                                    $venta->telefonoResidencia = $fileop[8];
                                    $venta->ciudadResidencia = utf8_encode($fileop[9]);
                                    $venta->direccionResidencia = utf8_encode($fileop[10]);
                                    $venta->correo = $fileop[11];
                                    $venta->telefonoCelular = $fileop[12];
                                    $venta->vehMarca = $fileop[13];
                                    $venta->placa = $fileop[14];
                                    $venta->vin = $fileop[15];
                                    $venta->tipoVehiculo = $fileop[16];
                                    $venta->version = $fileop[17];
                                    $venta->motor = $fileop[18];
                                    $venta->modelo = $fileop[19];
                                    $venta->color = $fileop[20];
                                    $venta->nroFactura = $fileop[21];
                                    $fechaEntrega = explode('/', $fileop[22]);
                                    $diaEntrega =  $fechaEntrega[0];
                                    $mesEntrega =  $fechaEntrega[1];
                                    $anioEntrega = $fechaEntrega[2];
                                    $venta->fechaEntrega = $anioEntrega.'/'.$mesEntrega.'/'.$diaEntrega;
                                    $venta->nombreConcesionario = utf8_encode($fileop[23]);
                                    $venta->nombreVendedor = utf8_encode($fileop[24]);
                                    $venta->nombreSalaVenta = utf8_encode($fileop[25]);
                                    $venta->mesVenta = $fileop[26];
                                    $venta->nitEmpresa = $fileop[27];
                                    $venta->nom_contact_empresa = utf8_encode($fileop[28]);
                                    $fechaNacimiento = explode('/', $fileop[29]);
                                    $diaNacimiento =  $fechaNacimiento[0];
                                    $mesNacimiento =  $fechaNacimiento[1];
                                    $anioNacimiento = $fechaNacimiento[2];
                                    $venta->fechaNacimiento = $anioNacimiento.'/'.$mesNacimiento.'/'.$diaNacimiento;
                                    $venta->estrato = $fileop[30];
                                    $venta->estadoCivil = utf8_encode($fileop[31]);
                                    //fecha en la que se sube el archivo
                                    $venta->fechaCarga = date('Y-m-d H:i:s');
                                    $venta->save(false);
                                }
                                $k = $k + 1;
                            }                        
                            $msg = "Información importada correctamente";
                            fclose($handle);
                            fclose($handle2);
                            unlink('upload/'.$model->file);
                            return $this->redirect(['upload/venta','msg' => $msg]);                                                                         
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
                return $this->render("venta", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg, "msgerror" => $msgerror]);
            } else {
                return $this->redirect(["site/login"]);
            }    
        }
        
        public function actionPostventa()
        {
            if (!Yii::$app->user->isGuest) {
                $model = new FormSubirInformacion();            
                $msg = null;
                $msgerror[] = null;
                $conterror = 0;
                $tipomsg = null;
                $i = 1;
                $j = 1;
                $k = 1;
                if (Yii::$app->request->isPost) {
                    $model->file = UploadedFile::getInstance($model, 'file');
                    if ($model->upload()) {
                        $handle = fopen('upload/'.$model->file, "r");                    
                         while (($fileop = fgetcsv($handle, 1000, ";")) !== false) 
                         {                                                
                            if ($j > 1){
                                if ($fileop[0] == ''){
                                    $conterror = 1;
                                    $msgerror[] = "El nombre del cliente (".$fileop[0]. ") esta vacio en la fila " .$i;
                                } 
                                if ($fileop[1] == ''){
                                    $conterror = 1;
                                    $msgerror[] = "La empresa (".$fileop[1]. ") esta vacio en la fila " .$i;
                                }
                                if ($fileop[2] == '' Or !ctype_digit($fileop[2]) ){
                                    $conterror = 1;
                                    $msgerror[] = "El número de identificación (".$fileop[2]. ") tiene letras ó esta vacio  en la fila " .$i;
                                }                            
                            }    
                            $i = $i + 1;
                            $j = $j + 1;
                         }                     
                         if ($conterror == 0) 
                         {                        
                            $handle2 = fopen('upload/'.$model->file, "r");
                             while (($fileop = fgetcsv($handle2, 1000, ";")) !== false) 
                            {
                                if ($k > 1){
                                    $postventa = new Posventa();
                                    $postventa->nombresCliente = utf8_encode($fileop[0]);
                                    $postventa->empresa = utf8_encode($fileop[1]);
                                    $postventa->identificacion = $fileop[2];
                                    $postventa->usuario = $fileop[3];
                                    $postventa->marca = utf8_encode($fileop[4]);
                                    $postventa->tipoVehiculo = utf8_encode($fileop[5]);
                                    $postventa->anio = $fileop[6];
                                    $postventa->kilometraje = $fileop[7];
                                    $postventa->placaMatricula = $fileop[8];
                                    $postventa->vin = $fileop[9];
                                    $postventa->nroOrden = $fileop[10];                                
                                    $postventa->fechaOrden = date("Y-m-d",strtotime($fileop[11]));
                                    $postventa->nFactura = $fileop[12];                                
                                    $postventa->fechaFactura = date("Y-m-d",strtotime($fileop[13]));
                                    $postventa->telefono1 = $fileop[14];
                                    $postventa->telefono2 = $fileop[15];
                                    $postventa->extensionOficina = $fileop[16];
                                    $postventa->celular = $fileop[17];
                                    $postventa->ciudadOrigenTelefono = utf8_encode($fileop[18]);
                                    $postventa->direccion = utf8_encode($fileop[19]);
                                    $postventa->motivoIngresoTaller = utf8_encode($fileop[20]);
                                    $postventa->motivoIngresoTaller2 = utf8_encode($fileop[21]);
                                    $postventa->aseguradora = $fileop[22];
                                    $postventa->email = $fileop[23];
                                    $postventa->asesorServicio = utf8_encode($fileop[24]);                                                                
                                    $postventa->autorizacionCliente = $fileop[25];
                                    //fecha en la que se sube el archivo
                                    $postventa->fechaCarga = date('Y-m-d H:i:s');
                                    $postventa->save(false);
                                }    
                                $k = $k + 1;
                            }                        
                            $msg = "Información importada correctamente";
                            fclose($handle);
                            fclose($handle2);
                            unlink('upload/'.$model->file);
                            return $this->redirect(['upload/postventa','msg' => $msg]);                                                                         
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
                return $this->render("postventa", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg, "msgerror" => $msgerror]);
            } else {
                return $this->redirect(["site/login"]);
            }
        }
    }