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
use PHPExcel_Shared_Date;

    class UploadController extends Controller
    {              
        public function actionVentacsv()
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
                        //$handle = fopen('upload/'.$model->file, "r");                    
                        $inputFile = 'upload/'.$model->file;
                        try{
                            $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
                            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                            $objPHPExcel = $objReader->load($inputFile);
                        } catch (Exception $e) {
                            die('Error');
                        }

                        $sheet = $objPHPExcel->getSheet(0);
                        $highestRow = $sheet->getHighestRow();
                        $highestColumn = $sheet->getHighestColumn();
                        for($row2=1; $row2 <= $highestRow; $row2++)
                        {
                          $rowData = $sheet->rangeToArray('A'.$row2.':'.$highestColumn.$row2,NULL,TRUE,FALSE);
                            if($row2==1)
                            {
                                continue;
                            }
                            if ($rowData[0][1] != ""){
                                if (!ctype_alpha($rowData[0][1])){
                                    $conterror = 1;
                                    $msgerror[] = "El tipo de identificación (".$rowData[0][1]. ") tienen números en la fila " .$row2;
                                }
                            }                            
                            if ($rowData[0][2] == ""){
                                $conterror = 1;
                                $msgerror[] = "El campo Nombres (".$rowData[0][2]. ") no puede estar vacio, en la fila " .$row2;
                            }
                            $empresa = strtoupper($rowData[0][4]);
                            if ($empresa != 'SI' and $empresa != 'NO'){
                                $conterror = 1;
                                $msgerror[] = "El campo empresa (".$rowData[0][4]. ") solo acepta SI/NO, en la fila " .$row2;
                            }
                            if ($rowData[0][5] != ""){
                                $sexo = strtoupper($rowData[0][5]);
                                if ($sexo != 'MASCULINO' and $sexo != 'FEMENINO' and $sexo != 'INDETERMINADO'){
                                    $conterror = 1;
                                    $msgerror[] = "El campo sexo (".$rowData[0][5]. ") solo acepta MASCULINO/FEMENINO/INDETERMINADO, en la fila " .$row2;
                                }
                            }                            
                            if ($rowData[0][6] == "" or !ctype_alpha($rowData[0][6])){
                                $conterror = 1;
                                $msgerror[] = "El campo CiuResidencia (".$rowData[0][6]. ") no debe contener numero y/o no puede estar vacio, en la fila " .$row2;
                            }
                            if (ctype_alpha($rowData[0][8])){
                                $conterror = 1;
                                $msgerror[] = "El campo telefono1 (".$rowData[0][8]. ") tiene letras y/o carecteres diferentes a numeros en la fila " .$row2;
                            }
                            if (ctype_alpha($rowData[0][9])){
                                $conterror = 1;
                                $msgerror[] = "El campo telefono2 (".$rowData[0][9]. ") tiene letras y/o carecteres diferentes a numeros en la fila " .$row2;
                            }                            
                            if (ctype_alpha($rowData[0][10]) ){
                                $conterror = 1;
                                $msgerror[] = "El campo celular (".$rowData[0][10]. ") tiene letras en la fila " .$row2;                                
                            }else{
                                if (strlen($rowData[0][10]) < 10 or strlen($rowData[0][10]) > 10){
                                    $conterror = 1;
                                    $msgerror[] = "El campo celular (".$rowData[0][10]. ") tiene menos de 10 o mas de 10 caracteres en la fila " .$row2;
                                }
                            }
                            if ($rowData[0][13] != ""){
                                if ($rowData[0][13] == ""){
                                    $conterror = 1;
                                    $msgerror[] = "El campo vehvin (".$rowData[0][13]. ") no puede estar vacio, en la fila " .$row2;
                                }
                            }                            
                            if ($rowData[0][14] == "" or !ctype_alpha($rowData[0][14])){
                                $conterror = 1;
                                $msgerror[] = "El campo vehmarca (".$rowData[0][14]. ") no debe tener numeros y/o estar vacio, en la fila " .$row2;
                            }
                            if ($rowData[0][15] == ""){
                                $conterror = 1;
                                $msgerror[] = "El campo vehversion (".$rowData[0][15]. ") no puede estar vacio, en la fila " .$row2;
                            }
                            if (ctype_alpha($rowData[0][16]) ){
                                $conterror = 1;
                                $msgerror[] = "El campo vehmodeloanio (".$rowData[0][16]. ") tiene letras en la fila " .$row2;                                
                            }else{
                                if (strlen($rowData[0][16]) < 4 or strlen($rowData[0][16]) > 4){
                                    $conterror = 1;
                                    $msgerror[] = "El campo vehmodeloanio (".$rowData[0][16]. ") tiene menos de 1 o mas de 4 caracteres en la fila " .$row2;
                                }
                            }
                            if ($rowData[0][17] != ""){
                                if (!ctype_alpha($rowData[0][17])){
                                    $conterror = 1;
                                    $msgerror[] = "El campo vehcolor (".$rowData[0][17]. ") no debe tener numeros en la fila " .$row2;
                                }
                            }                            
                            $arr1 = str_split($rowData[0][18]);
                            $letras = "";
                            $numeros = "";
                            $m = "";
                            $er = 0;                            
                            foreach ($arr1 as &$valor) {
                                if (ctype_alpha($valor)){
                                    $letras = $letras.$valor;
                                }else{
                                    $numeros = $numeros.$valor;
                                }                                                                
                            }
                            $totalreg = $letras.$numeros;
                            if (strlen($totalreg) > 6 or strlen($totalreg) < 6){
                                $m = "no coincide el numero de caracteres ed la placa";
                                $er = 1;
                            }
                            if (strlen($letras) > 3 or strlen($letras) < 3){
                                $m = "no coincide el numero de caracteres en las letras de la placa";
                                $er = 1;
                            }
                            if (strlen($numeros) > 3 or strlen($numeros) < 3){
                                $m = "no coincide el numero de caracteres en los numeros de la placa";
                                $er = 1;
                            }
                            if ($er == 1){
                                if ($rowData[0][18] != ""){
                                    $conterror = 1;
                                    $msgerror[] = "El campo vehplaca (".$rowData[0][18].") ".$m.", en la fila " .$row2;
                                }                                
                            }
                            if ($rowData[0][19] == ""){
                                $conterror = 1;
                                $msgerror[] = "El campo concnombre (".$rowData[0][19]. ") no puede estar vacio, en la fila " .$row2;
                            }
                            if ($rowData[0][21] != ""){
                                if (!ctype_alpha($rowData[0][21])){
                                    $conterror = 1;
                                    $msgerror[] = "El campo concCiudad (".$rowData[0][21]. ") solo debe tener letras no numeros, en la fila " .$row2;
                                }
                            }                            
                            if (!ctype_alpha($rowData[0][22]) Or strlen($rowData[0][22]) == ""){
                                $conterror = 1;
                                $msgerror[] = "El campo nomvendedor (".$rowData[0][22]. ") solo debe tiene letras y/o no puede estar vacio, en la fila " .$row2;
                            }
                            //fecha de entrega
                            if (is_numeric($rowData[0][23])){
                                if ($rowData[0][23] <> ""){
                                    $timestamp = PHPExcel_Shared_Date::ExcelToPHP($rowData[0][23]);
                                    $timestamp = strtotime("+1 day",$timestamp);
                                    $dia = date("d",$timestamp);
                                    $mes = date("m",$timestamp);
                                    $anio = date("Y",$timestamp);
                                    $fecha_php = date("Y-m-d",$timestamp);
                                    $valido = checkdate($mes, $dia, $anio);
                                    if ($valido == false){                                
                                        $conterror = 1;
                                        $msgerror[] = "El campo fecEntVeh (".$rowData[0][23]. ") no tiene una fecha valida, en la fila " .$row2;
                                    }
                                }else{
                                    $conterror = 1;
                                    $msgerror[] = "El campo fecEntVeh (".$rowData[0][23]. ") no puede estar vacio en la fila " .$row2;
                                }    
                            }else{
                                $conterror = 1;
                                $msgerror[] = "El campo fecEntVeh (".$rowData[0][23]. ") tiene caracteres no validos o fecha no valida en la fila " .$row2;
                            }
                            if (strlen($rowData[0][23]) > 10){
                                $conterror = 1;
                                $msgerror[] = "El campo fecEntVeh (".$rowData[0][23]. ") tiene inconsistencias en el numero de caracteres, solo se aceptan 10 caracteres incluyendo / o -, ejemplo(10-12-2019), en la fila " .$row2;
                            }
                            //fecha nacimiento
                            if (is_numeric($rowData[0][27])){
                                $timestamp = PHPExcel_Shared_Date::ExcelToPHP($rowData[0][27]);
                                $timestamp = strtotime("+1 day",$timestamp);
                                $dia = date("d",$timestamp);
                                $mes = date("m",$timestamp);
                                $anio = date("Y",$timestamp);
                                $fecha_php = date("Y-m-d",$timestamp);
                                $valido = checkdate($mes, $dia, $anio);
                                if ($valido == false){                                
                                    $conterror = 1;
                                    $msgerror[] = "El campo Fecha Nacimiento (".$rowData[0][27]. ") no tiene una fecha valida, en la fila " .$row2;
                                }
                            }else{
                                if ($rowData[0][27] != ""){
                                    $conterror = 1;
                                    $msgerror[] = "El campo Fecha Nacimiento (".$rowData[0][27]. ") tiene caracteres no validos o fecha no valida en la fila " .$row2;
                                }                                
                            }
                            if (strlen($rowData[0][27]) > 10){
                                $conterror = 1;
                                $msgerror[] = "El campo Fecha Nacimiento (".$rowData[0][27]. ") tiene inconsistencias en el numero de caracteres, solo se aceptan 10 caracteres incluyendo / o -, ejemplo(10-12-2019), en la fila " .$row2;
                            }
                            if (is_numeric($rowData[0][28])){
                                if (strlen($rowData[0][28]) > 1){
                                    $conterror = 1;
                                    $msgerror[] = "El campo estrato (".$rowData[0][28]. ") no puede tener mas de un caracter, en la fila " .$row2;
                                }
                                
                            }else{
                                if ($rowData[0][28] != ""){
                                    $conterror = 1;
                                    $msgerror[] = "El campo estrato (".$rowData[0][28]. ") solo acepta numeros la fila " .$row2;
                                }
                            }
                            if ($rowData[0][29] != ""){
                                if (!ctype_alpha($rowData[0][29])){
                                    $conterror = 1;
                                    $msgerror[] = "El campo estadoCivil (".$rowData[0][29]. ") solo debe tener letras no numeros, en la fila " .$row2;
                                }
                            }                            
                        }
                        if ($conterror == 0) 
                        {
                            for($row=1; $row <= $highestRow; $row++)
                            {
                                $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL,TRUE,FALSE);
                                if($row==1)
                                {
                                    continue;
                                }                                                        
                                $venta = new Venta();                                                                                                                                                                                                                                                      
                                $venta->identificacion = $rowData[0][0];                            
                                $venta->tipoIdentificacion = $rowData[0][1];                            
                                $venta->nombres = utf8_encode($rowData[0][2]);
                                $venta->apellidos = utf8_encode($rowData[0][3]);
                                $venta->empresa = $rowData[0][4];                            
                                $venta->sexo = $rowData[0][5];
                                $venta->ciudadResidencia = utf8_encode($rowData[0][6]);                            
                                $venta->dptoResidencia = utf8_encode($rowData[0][7]);                            
                                $venta->telefono1 = $rowData[0][8];
                                $venta->telefono2 = $rowData[0][9];
                                $venta->celular = $rowData[0][10];
                                $venta->direccionResidencia = utf8_encode($rowData[0][11]);                            
                                $venta->email = utf8_encode($rowData[0][12]);
                                $venta->vehVin = $rowData[0][13];
                                $venta->vehMarca = $rowData[0][14];
                                $venta->vehVersion = $rowData[0][15];
                                $venta->vehModelo = $rowData[0][16];
                                $venta->vehColor = $rowData[0][17];
                                $venta->vehPlaca = $rowData[0][18];
                                $venta->nombreConcesionario = utf8_encode($rowData[0][19]);
                                $venta->nombreSalaVenta = utf8_encode($rowData[0][20]);
                                $venta->concCiudad = utf8_encode($rowData[0][21]);
                                $venta->nombreVendedor = utf8_encode($rowData[0][22]);
                                $timestamp = PHPExcel_Shared_Date::ExcelToPHP($rowData[0][23]);
                                $timestamp = strtotime("+1 day",$timestamp);                                
                                $fecha_php = date("Y-m-d",$timestamp);
                                $venta->fechaEntrega = $fecha_php;
                                $venta->fechaCarga = date('Y-m-d');
                                $venta->nroFactura = utf8_encode($rowData[0][25]);
                                $venta->nroOrden = utf8_encode($rowData[0][26]);
                                if ($rowData[0][27] != ""){
                                    $timestamp = PHPExcel_Shared_Date::ExcelToPHP($rowData[0][27]);
                                    $timestamp = strtotime("+1 day",$timestamp);                                
                                    $fecha_phpn = date("Y-m-d",$timestamp);
                                }else{
                                    $fecha_phpn = "";
                                }                                
                                $venta->fechaNacimiento = $fecha_phpn;
                                $venta->estrato = $rowData[0][28];
                                $venta->estadoCivil = utf8_encode($rowData[0][29]);                            
                                $venta->concatenado = $rowData[0][30];
                                $venta->codConcesionario = $rowData[0][31];                                                                                                                                                                        
                                $venta->save(false);
                                $msg = "Información importada correctamente";
                            }
                        }else {
                            $msg = "Información no importada, por favor verificar!";
                            $tipomsg = "danger";
                        }    
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
        
        public function actionPosventa()
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
                        //$handle = fopen('upload/'.$model->file, "r");                    
                        $inputFile = 'upload/'.$model->file;
                        try{
                            $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
                            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                            $objPHPExcel = $objReader->load($inputFile);
                        } catch (Exception $e) {
                            die('Error');
                        }
                        $sheet = $objPHPExcel->getSheet(0);
                        $highestRow = $sheet->getHighestRow();
                        $highestColumn = $sheet->getHighestColumn();
                        for($row2=1; $row2 <= $highestRow; $row2++)
                        {
                          $rowData = $sheet->rangeToArray('A'.$row2.':'.$highestColumn.$row2,NULL,TRUE,FALSE);
                            if($row2==1)
                            {
                                continue;
                            }
                            if ($rowData[0][1] != ""){
                                if (!ctype_alpha($rowData[0][1])){
                                    $conterror = 1;
                                    $msgerror[] = "El tipo de identificación (".$rowData[0][1]. ") tienen números en la fila " .$row2;
                                }
                            }                            
                            if ($rowData[0][2] == ""){
                                $conterror = 1;
                                $msgerror[] = "El campo Nombres (".$rowData[0][2]. ") no puede estar vacio, en la fila " .$row2;
                            }
                            $empresa = strtoupper($rowData[0][4]);
                            if ($empresa != 'SI' and $empresa != 'NO'){
                                $conterror = 1;
                                $msgerror[] = "El campo empresa (".$rowData[0][4]. ") solo acepta SI/NO, en la fila " .$row2;
                            }
                            if ($rowData[0][5] != ""){
                                $sexo = strtoupper($rowData[0][5]);
                                if ($sexo != 'MASCULINO' and $sexo != 'FEMENINO' and $sexo != 'INDETERMINADO'){
                                    $conterror = 1;
                                    $msgerror[] = "El campo sexo (".$rowData[0][5]. ") solo acepta MASCULINO/FEMENINO/INDETERMINADO, en la fila " .$row2;
                                }
                            }                            
                            if ($rowData[0][6] == "" or !ctype_alpha($rowData[0][6])){
                                $conterror = 1;
                                $msgerror[] = "El campo CiuResidencia (".$rowData[0][6]. ") no debe contener numero y/o no puede estar vacio, en la fila " .$row2;
                            }
                            if (ctype_alpha($rowData[0][8])){
                                $conterror = 1;
                                $msgerror[] = "El campo telefono1 (".$rowData[0][8]. ") tiene letras y/o carecteres diferentes a numeros en la fila " .$row2;
                            }
                            if (ctype_alpha($rowData[0][9])){
                                $conterror = 1;
                                $msgerror[] = "El campo telefono2 (".$rowData[0][9]. ") tiene letras y/o carecteres diferentes a numeros en la fila " .$row2;
                            }
                            if (ctype_alpha($rowData[0][10]) ){
                                $conterror = 1;
                                $msgerror[] = "El campo celular (".$rowData[0][10]. ") tiene letras en la fila " .$row2;                                
                            }else{
                                if (strlen($rowData[0][10]) < 10 or strlen($rowData[0][10]) > 10){
                                    $conterror = 1;
                                    $msgerror[] = "El campo celular (".$rowData[0][10]. ") tiene menos de 10 o mas de 10 caracteres en la fila " .$row2;
                                }
                            }
                            if ($rowData[0][13] != ""){
                                if ($rowData[0][13] == ""){
                                    $conterror = 1;
                                    $msgerror[] = "El campo vehvin (".$rowData[0][13]. ") no puede estar vacio, en la fila " .$row2;
                                }
                            }                            
                            if ($rowData[0][14] == "" or !ctype_alpha($rowData[0][14])){
                                $conterror = 1;
                                $msgerror[] = "El campo vehmarca (".$rowData[0][14]. ") no debe tener numeros y/o estar vacio, en la fila " .$row2;
                            }
                            if ($rowData[0][15] == ""){
                                $conterror = 1;
                                $msgerror[] = "El campo vehversion (".$rowData[0][15]. ") no puede estar vacio, en la fila " .$row2;
                            }
                            if (ctype_alpha($rowData[0][16]) ){
                                $conterror = 1;
                                $msgerror[] = "El campo vehmodeloanio (".$rowData[0][16]. ") tiene letras en la fila " .$row2;                                
                            }else{
                                if (strlen($rowData[0][16]) < 4 or strlen($rowData[0][16]) > 4){
                                    $conterror = 1;
                                    $msgerror[] = "El campo vehmodeloanio (".$rowData[0][16]. ") tiene menos de 1 o mas de 4 caracteres en la fila " .$row2;
                                }
                            }
                            if ($rowData[0][17] != ""){
                                if (!ctype_alpha($rowData[0][17])){
                                    $conterror = 1;
                                    $msgerror[] = "El campo vehcolor (".$rowData[0][17]. ") no debe tener numeros en la fila " .$row2;
                                }
                            }                            
                            $arr1 = str_split($rowData[0][18]);
                            $letras = "";
                            $numeros = "";
                            $m = "";
                            $er = 0;                            
                            foreach ($arr1 as &$valor) {
                                if (ctype_alpha($valor)){
                                    $letras = $letras.$valor;
                                }else{
                                    $numeros = $numeros.$valor;
                                }                                                                
                            }
                            $totalreg = $letras.$numeros;
                            if (strlen($totalreg) > 6 or strlen($totalreg) < 6){
                                $m = "no coincide el numero de caracteres ed la placa";
                                $er = 1;
                            }
                            if (strlen($letras) > 3 or strlen($letras) < 3){
                                $m = "no coincide el numero de caracteres en las letras de la placa";
                                $er = 1;
                            }
                            if (strlen($numeros) > 3 or strlen($numeros) < 3){
                                $m = "no coincide el numero de caracteres en los numeros de la placa";
                                $er = 1;
                            }
                            if ($er == 1){
                                if ($rowData[0][18] != ""){
                                    $conterror = 1;
                                    $msgerror[] = "El campo vehplaca (".$rowData[0][18].") ".$m.", en la fila " .$row2;
                                }                                
                            }
                            if ($rowData[0][19] == ""){
                                $conterror = 1;
                                $msgerror[] = "El campo concnombre (".$rowData[0][19]. ") no puede estar vacio, en la fila " .$row2;
                            }
                            if ($rowData[0][21] != ""){
                                if (!ctype_alpha($rowData[0][21])){
                                    $conterror = 1;
                                    $msgerror[] = "El campo concCiudad (".$rowData[0][21]. ") solo debe tener letras no numeros, en la fila " .$row2;
                                }
                            }                            
                            if (!ctype_alpha($rowData[0][22]) Or strlen($rowData[0][22]) == ""){
                                $conterror = 1;
                                $msgerror[] = "El campo nomvendedor (".$rowData[0][22]. ") solo debe tiene letras y/o no puede estar vacio, en la fila " .$row2;
                            }
                            //fecha de entrega
                            if ($rowData[0][23] == ""){
                                $conterror = 1;
                                $msgerror[] = "El campo fecEntVeh (".$rowData[0][23]. ") no puede estar vacio, en la fila " .$row2;
                            }
                            if (is_numeric($rowData[0][23])){
                                if ($rowData[0][23] <> ""){
                                    $timestamp = PHPExcel_Shared_Date::ExcelToPHP($rowData[0][23]);
                                    $timestamp = strtotime("+1 day",$timestamp);
                                    $dia = date("d",$timestamp);
                                    $mes = date("m",$timestamp);
                                    $anio = date("Y",$timestamp);
                                    $fecha_php = date("Y-m-d",$timestamp);
                                    $valido = checkdate($mes, $dia, $anio);
                                    if ($valido == false){                                
                                        $conterror = 1;
                                        $msgerror[] = "El campo fecEntVeh (".$rowData[0][23]. ") no tiene una fecha valida, en la fila " .$row2;
                                    }
                                }else{
                                    $conterror = 1;
                                    $msgerror[] = "El campo fecEntVeh (".$rowData[0][23]. ") no puede estar vacio en la fila " .$row2;
                                }    
                            }else{
                                $conterror = 1;
                                $msgerror[] = "El campo fecEntVeh (".$rowData[0][23]. ") tiene caracteres no validos o fecha no valida en la fila " .$row2;
                            }
                            if (strlen($rowData[0][23]) > 10){
                                $conterror = 1;
                                $msgerror[] = "El campo fecEntVeh (".$rowData[0][23]. ") tiene inconsistencias en el numero de caracteres, solo se aceptan 10 caracteres incluyendo / o -, ejemplo(10-12-2019), en la fila " .$row2;
                            }
                            if (ctype_alpha($rowData[0][27]) Or strlen($rowData[0][27]) == ""){
                                $conterror = 1;
                                $msgerror[] = "El campo kilometraje (".$rowData[0][27]. ") tiene letras o esta vacio" .$row2;
                            }
                            if (!ctype_alpha($rowData[0][28]) Or strlen($rowData[0][28]) == ""){
                                $conterror = 1;
                                $msgerror[] = "El campo motivo de ingreso (".$rowData[0][28]. ") solo debe tiene letras y/o no puede estar vacio, en la fila " .$row2;
                            }
                            if ($rowData[0][29] != ""){
                                $tipIngreso = ucfirst(strtolower($rowData[0][29]));
                                if ($tipIngreso != 'Mantención' and $tipIngreso != 'Reparación' and $tipIngreso != 'Colisión' and $tipIngreso != 'Garantía'){
                                    $conterror = 1;
                                    $msgerror[] = "El campo tipificación ingreso (".$tipIngreso. ") solo acepta Mantención/Reparación/Colisión/Garantía, en la fila " .$row2;
                                }
                            }
                            //fecha nacimiento
                            if (is_numeric($rowData[0][30])){
                                $timestamp = PHPExcel_Shared_Date::ExcelToPHP($rowData[0][30]);
                                $timestamp = strtotime("+1 day",$timestamp);
                                $dia = date("d",$timestamp);
                                $mes = date("m",$timestamp);
                                $anio = date("Y",$timestamp);
                                $fecha_php = date("Y-m-d",$timestamp);
                                $valido = checkdate($mes, $dia, $anio);
                                if ($valido == false){                                
                                    $conterror = 1;
                                    $msgerror[] = "El campo fecha Nacimiento (".$rowData[0][30]. ") no tiene una fecha valida, en la fila " .$row2;
                                }
                            }else{
                                if ($rowData[0][30] != ""){
                                    $conterror = 1;
                                    $msgerror[] = "El campo fecha Nacimiento (".$rowData[0][30]. ") tiene caracteres no validos o fecha no valida en la fila " .$row2;
                                }                                
                            }
                            if (strlen($rowData[0][30]) > 10){
                                $conterror = 1;
                                $msgerror[] = "El campo Fecha Nacimiento (".$rowData[0][30]. ") tiene inconsistencias en el numero de caracteres, solo se aceptan 10 caracteres incluyendo / o -, ejemplo(10-12-2019), en la fila " .$row2;
                            }
                            if (is_numeric($rowData[0][31])){
                                if (strlen($rowData[0][31]) > 1){
                                    $conterror = 1;
                                    $msgerror[] = "El campo estrato (".$rowData[0][31]. ") no puede tener mas de un caracter, en la fila " .$row2;
                                }
                                
                            }else{
                                if ($rowData[0][31] != ""){
                                    $conterror = 1;
                                    $msgerror[] = "El campo estrato (".$rowData[0][31]. ") solo acepta numeros la fila " .$row2;
                                }
                            }
                            if ($rowData[0][32] != ""){
                                if (!ctype_alpha($rowData[0][32])){
                                    $conterror = 1;
                                    $msgerror[] = "El campo estadoCivil (".$rowData[0][32]. ") solo debe tener letras no numeros, en la fila " .$row2;
                                }
                            }                            
                        }
                        if ($conterror == 0) 
                        {
                            for($row=1; $row <= $highestRow; $row++)
                            {
                                $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL,TRUE,FALSE);
                                if($row==1)
                                {
                                    continue;
                                }                                                        
                                $posventa = new Posventa();                                                                                                                                                                                                                                                      
                                $posventa->identificacion = $rowData[0][0];                            
                                $posventa->tipoIdentificacion = $rowData[0][1];                            
                                $posventa->nombres = utf8_encode($rowData[0][2]);
                                $posventa->apellidos = utf8_encode($rowData[0][3]);
                                $posventa->empresa = $rowData[0][4];                            
                                $posventa->sexo = $rowData[0][5];
                                $posventa->ciudadResidencia = utf8_encode($rowData[0][6]);                            
                                $posventa->dptoResidencia = utf8_encode($rowData[0][7]);                            
                                $posventa->telefono1 = $rowData[0][8];
                                $posventa->telefono2 = $rowData[0][9];
                                $posventa->celular = $rowData[0][10];
                                $posventa->direccionResidencia = utf8_encode($rowData[0][11]);                            
                                $posventa->email = utf8_encode($rowData[0][12]);
                                $posventa->vehVin = $rowData[0][13];
                                $posventa->vehMarca = $rowData[0][14];
                                $posventa->vehVersion = $rowData[0][15];
                                $posventa->vehModelo = $rowData[0][16];
                                $posventa->vehColor = $rowData[0][17];
                                $posventa->vehPlaca = $rowData[0][18];
                                $posventa->nombreConcesionario = utf8_encode($rowData[0][19]);
                                $posventa->nombreSalaVenta = utf8_encode($rowData[0][20]);
                                $posventa->concCiudad = utf8_encode($rowData[0][21]);
                                $posventa->nombreVendedor = utf8_encode($rowData[0][22]);
                                $timestamp = PHPExcel_Shared_Date::ExcelToPHP($rowData[0][23]);
                                $timestamp = strtotime("+1 day",$timestamp);                                
                                $fecha_php = date("Y-m-d",$timestamp);
                                $posventa->fechaEntrega = $fecha_php;
                                $posventa->fechaCarga = date('Y-m-d');
                                $posventa->nroFactura = utf8_encode($rowData[0][25]);
                                $posventa->nroOrden = utf8_encode($rowData[0][26]);                                
                                $posventa->kilometraje = $rowData[0][27];
                                $posventa->motivoIngreso = $rowData[0][28];
                                $posventa->tipificacionIngreso = $rowData[0][29];                                
                                if ($rowData[0][30] != ""){
                                    $timestamp = PHPExcel_Shared_Date::ExcelToPHP($rowData[0][30]);
                                    $timestamp = strtotime("+1 day",$timestamp);                                
                                    $fecha_phpn = date("Y-m-d",$timestamp);
                                }else{
                                    $fecha_phpn = "";
                                }                                                                                                
                                $posventa->fechaNacimiento = $fecha_phpn;                                
                                $posventa->estrato = $rowData[0][31];
                                $posventa->estadoCivil = utf8_encode($rowData[0][32]);                            
                                $posventa->concatenado = $rowData[0][33];
                                $posventa->codConcesionario = $rowData[0][34];                                                                                                                                                                        
                                $posventa->save(false);
                                $msg = "Información importada correctamente";
                            }
                        }else {
                            $msg = "Información no importada, por favor verificar!";
                            $tipomsg = "danger";
                        }    
                        unlink('upload/'.$model->file);                                                                          
                    }else{
                        $msg = "El Archivo no se pudo importar";
                    }
                }            
                return $this->render("posventa", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg, "msgerror" => $msgerror]);
            } else {
                return $this->redirect(["site/login"]);
            }    
        }
    }