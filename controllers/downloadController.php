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
use app\models\FormFiltroDownload;
use yii\helpers\Url;
use yii\web\UploadedFile;

    class DownloadController extends Controller
    {              
        public function actionVenta() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroDownload;                        
            $fecha_enviado_desde = null;
            $fecha_enviado_hasta = null;                       
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {                                        
                    $fecha_desde = Html::encode($form->fecha_desde);
                    $fecha_hasta = Html::encode($form->fecha_hasta);                    
                    if ($fecha_desde <> null or $fecha_hasta <> null){
                        $dato1 = $fecha_desde.' 00:00:00';
                        $dato2 = $fecha_hasta.' 23:59:59';
                    }else{
                        $dato1 = null;
                        $dato2 = null;
                    }
                    $table = Venta::find()                                                                                    
                            ->andFilterWhere(['>=', 'fechaCarga', $dato1])
                            ->andFilterWhere(['<=', 'fechaCarga', $dato2])                                                        
                            ->orderBy('fechaCarga desc');                    
                    $model = $table->all();
                    $this->actionExcelventa($model);     
                } else {
                    $form->getErrors();
                }
                                
            } else {
                $table = Venta::find()
                        ->where(['=','id',0]);                                      
                $model = $table->all();                 
            }
            
            return $this->render('venta', [
                        'model' => $model,
                        'form' => $form,                                                                       
            ]);
        } else {
            return $this->redirect(["site/login"]);
        }
    }
    
        public function actionExcelventa($model) {
        //$costoproducciondiario = CostoProduccionDiaria::find()->all();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        //establecer autosize de la columna
        foreach (range('A','Z') as $col)
        {
            $objPHPExcel->getActiveSheet() ->getColumnDimension($col) ->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Id')
                    ->setCellValue('B1', 'Número Identificacion')
                    ->setCellValue('C1', 'Tipo Identificación')
                    ->setCellValue('D1', 'Nombres')
                    ->setCellValue('E1', 'Apellidos')
                    ->setCellValue('F1', 'Empresa')
                    ->setCellValue('G1', 'Sexo')                                        
                    ->setCellValue('H1', 'Ciudad Residencia')       
                    ->setCellValue('I1', 'Departamento Residencia')       
                    ->setCellValue('J1', 'Telefono 1')
                    ->setCellValue('K1', 'Telefono 2')
                    ->setCellValue('L1', 'Celular')
                    ->setCellValue('M1', 'Dirección Residencia')
                    ->setCellValue('N1', 'Email')
                    ->setCellValue('O1', 'Veh Vin')
                    ->setCellValue('P1', 'Veh Marca')
                    ->setCellValue('Q1', 'Veh Versión')
                    ->setCellValue('R1', 'Veh Modelo Año')
                    ->setCellValue('S1', 'Veh Color')
                    ->setCellValue('T1', 'Veh Placa')
                    ->setCellValue('U1', 'Concensionario Nombre')
                    ->setCellValue('V1', 'Nombre Sala')
                    ->setCellValue('W1', 'Concensionario Ciudad')
                    ->setCellValue('X1', 'Nombre Vendedor')
                    ->setCellValue('Y1', 'Fecha Entrega')                                        
                    ->setCellValue('Z1', 'Fecha Carga')
                    ->setCellValue('AA1', 'Número Factura')
                    ->setCellValue('AB1', 'Número Orden')
                    ->setCellValue('AC1', 'Fecha Nacimiento')                                                            
                    ->setCellValue('AD1', 'Estrato')
                    ->setCellValue('AE1', 'Estado Civil')   
                    ->setCellValue('AF1', 'Concatenado')   
                    ->setCellValue('AG1', 'Cod Concesionario');   
        $i = 2;
        
        foreach ($model as $val) {
            if ($val->fechaNacimiento == "" or is_null($val->fechaNacimiento)){
                $fechanac = "";        
            }else{
                $fechanac = date("d-m-Y",strtotime($val->fechaNacimiento));        
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id)
                    ->setCellValue('B' . $i, $val->identificacion)
                    ->setCellValue('C' . $i, $val->tipoIdentificacion)
                    ->setCellValue('D' . $i, $val->nombres)
                    ->setCellValue('E' . $i, $val->apellidos)
                    ->setCellValue('F' . $i, $val->empresa)                    
                    ->setCellValue('G' . $i, $val->sexo)
                    ->setCellValue('H' . $i, $val->ciudadResidencia)
                    ->setCellValue('I' . $i, $val->dptoResidencia)                    
                    ->setCellValue('J' . $i, $val->telefono1)
                    ->setCellValue('K' . $i, $val->telefono2)
                    ->setCellValue('L' . $i, $val->celular)                    
                    ->setCellValue('M' . $i, $val->direccionResidencia)
                    ->setCellValue('N' . $i, $val->email)
                    ->setCellValue('O' . $i, $val->vehVin)
                    ->setCellValue('P' . $i, $val->vehMarca)                                        
                    ->setCellValue('Q' . $i, $val->vehVersion)
                    ->setCellValue('R' . $i, $val->vehModelo)
                    ->setCellValue('S' . $i, $val->vehColor)
                    ->setCellValue('T' . $i, $val->vehPlaca)
                    ->setCellValue('U' . $i, $val->nombreConcesionario)
                    ->setCellValue('V' . $i, $val->nombreSalaVenta)
                    ->setCellValue('W' . $i, $val->concCiudad)
                    ->setCellValue('X' . $i, $val->nombreVendedor)
                    ->setCellValue('Y' . $i, date("d-m-Y",strtotime($val->fechaEntrega)))
                    ->setCellValue('Z' . $i, date("d-m-Y",strtotime($val->fechaCarga)))                                        
                    ->setCellValue('AA' . $i, $val->nroFactura)
                    ->setCellValue('AB' . $i, $val->nroOrden)                    
                    ->setCellValue('AC' . $i, $fechanac)                                        
                    ->setCellValue('AD' . $i, $val->estrato)
                    ->setCellValue('AE' . $i, $val->estadoCivil)
                    ->setCellValue('AF' . $i, $val->concatenado)
                    ->setCellValue('AG' . $i, $val->codConcesionario);            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ExportarVentas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ExportarVentas.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        //return $model;
        exit;
        
    }
    
        public function actionPosventa() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroDownload;                        
            $fecha_enviado_desde = null;
            $fecha_enviado_hasta = null;                       
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {                                        
                    $fecha_desde = Html::encode($form->fecha_desde);
                    $fecha_hasta = Html::encode($form->fecha_hasta);                    
                    if ($fecha_desde <> null or $fecha_hasta <> null){
                        $dato1 = $fecha_desde.' 00:00:00';
                        $dato2 = $fecha_hasta.' 23:59:59';
                    }else{
                        $dato1 = null;
                        $dato2 = null;
                    }
                    $table = Posventa::find()                                                                                    
                            ->andFilterWhere(['>=', 'fechaCarga', $dato1])
                            ->andFilterWhere(['<=', 'fechaCarga', $dato2])                                                        
                            ->orderBy('fechaCarga desc');                    
                    $model = $table->all();
                    $this->actionExcelposventa($model);     
                } else {
                    $form->getErrors();
                }
                                
            } else {
                $table = Posventa::find()
                        ->where(['=','id',0]);                                      
                $model = $table->all();                 
            }
            
            return $this->render('posventa', [
                        'model' => $model,
                        'form' => $form,                                                                       
            ]);
        } else {
            return $this->redirect(["site/login"]);
        }
    }
    
        public function actionExcelposventa($model) {
        //$costoproducciondiario = CostoProduccionDiaria::find()->all();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        //establecer autosize de la columna
        foreach (range('A','Z') as $col)
        {
            $objPHPExcel->getActiveSheet() ->getColumnDimension($col) ->setAutoSize(true);
        }                
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Nombre del Cliente')
                    ->setCellValue('B1', 'Empresa')
                    ->setCellValue('C1', 'Identificación')
                    ->setCellValue('D1', 'Usuario')
                    ->setCellValue('E1', 'Mazda')
                    ->setCellValue('F1', 'Tipo Vehiculo')                    
                    ->setCellValue('G1', 'Año')
                    ->setCellValue('H1', 'Kilometraje')
                    ->setCellValue('I1', 'Placa/Matricula')
                    ->setCellValue('J1', 'VIN')
                    ->setCellValue('K1', 'N° Orden Reparación')
                    ->setCellValue('L1', 'Fecha Orden')
                    ->setCellValue('M1', 'N° Factura')
                    ->setCellValue('N1', 'Fecha Factura')
                    ->setCellValue('O1', 'Teléfono 1 /Oficina')
                    ->setCellValue('P1', 'Teléfono 2 /Residencia')
                    ->setCellValue('Q1', 'Ext Teléfono Ofic')
                    ->setCellValue('R1', 'Telefono / Móvil')
                    ->setCellValue('S1', 'Ciudad Origen Teléfono')
                    ->setCellValue('T1', 'Dirección')
                    ->setCellValue('U1', 'Motivo Ing al Taller 1')
                    ->setCellValue('V1', 'Motivo Ing al Taller 2')
                    ->setCellValue('W1', 'Aseguradora')
                    ->setCellValue('X1', 'Email')
                    ->setCellValue('Y1', 'Asesor Servicio')
                    ->setCellValue('Z1', 'Autorización Cliente');
        $i = 2;
        
        foreach ($model as $val) {                                    
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->nombresCliente)
                    ->setCellValue('B' . $i, $val->empresa)
                    ->setCellValue('C' . $i, $val->identificacion)
                    ->setCellValue('D' . $i, $val->usuario)
                    ->setCellValue('E' . $i, $val->marca)
                    ->setCellValue('F' . $i, $val->tipoVehiculo)
                    ->setCellValue('G' . $i, $val->anio)
                    ->setCellValue('H' . $i, $val->kilometraje)
                    ->setCellValue('I' . $i, $val->placaMatricula)
                    ->setCellValue('J' . $i, $val->vin)
                    ->setCellValue('K' . $i, $val->nroOrden)
                    ->setCellValue('L' . $i, date("d-m-Y",strtotime($val->fechaOrden)))
                    ->setCellValue('M' . $i, $val->nFactura)
                    ->setCellValue('N' . $i, date("d-m-Y",strtotime($val->fechaFactura)))
                    ->setCellValue('O' . $i, $val->telefono1)
                    ->setCellValue('P' . $i, $val->telefono2)
                    ->setCellValue('Q' . $i, $val->extensionOficina)
                    ->setCellValue('R' . $i, $val->celular)
                    ->setCellValue('S' . $i, $val->ciudadOrigenTelefono)
                    ->setCellValue('T' . $i, $val->direccion)
                    ->setCellValue('U' . $i, $val->motivoIngresoTaller)
                    ->setCellValue('V' . $i, $val->motivoIngresoTaller2)
                    ->setCellValue('W' . $i, $val->aseguradora)
                    ->setCellValue('X' . $i, $val->email)
                    ->setCellValue('Y' . $i, $val->asesorServicio)
                    ->setCellValue('Z' . $i, $val->autorizacionCliente);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ExportarPosVentas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ExportarPosVentas.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        //return $model;
        exit;
        
    }
    }