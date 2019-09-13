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
                    ->setCellValue('A1', 'Nombre')
                    ->setCellValue('B1', 'Apellidos')
                    ->setCellValue('C1', 'Tipo Identificación')
                    ->setCellValue('D1', 'Número Identificacion')
                    ->setCellValue('E1', 'Sexo')
                    ->setCellValue('F1', 'Ciudad')                    
                    ->setCellValue('G1', 'Tel Oficina')
                    ->setCellValue('H1', 'Dirección Oficina')
                    ->setCellValue('I1', 'Tel Residencia')
                    ->setCellValue('J1', 'Ciudad Residencia')
                    ->setCellValue('K1', 'Dirección Residencia')
                    ->setCellValue('L1', 'Correo Electrónico')
                    ->setCellValue('M1', 'Celular')
                    ->setCellValue('N1', 'Veh Marca')
                    ->setCellValue('O1', 'Placa')
                    ->setCellValue('P1', 'VIN')
                    ->setCellValue('Q1', 'Tipo Vehiculo')
                    ->setCellValue('R1', 'Versión')
                    ->setCellValue('S1', 'Motor')
                    ->setCellValue('T1', 'Modelo')
                    ->setCellValue('U1', 'Color')
                    ->setCellValue('V1', 'Número Factura')
                    ->setCellValue('W1', 'Fecha Entrega')
                    ->setCellValue('X1', 'Nombre Concensionario')
                    ->setCellValue('Y1', 'Nombre Vendedor')
                    ->setCellValue('Z1', 'Nombre Sala Ventas')
                    ->setCellValue('AA1', 'Mes Venta')
                    ->setCellValue('AB1', 'Nit Empresa')
                    ->setCellValue('AC1', 'Nombre Contacto Empresa')
                    ->setCellValue('AD1', 'Fecha Nacimiento')
                    ->setCellValue('AE1', 'Estrato')
                    ->setCellValue('AF1', 'Estado Civil');        
        $i = 2;
        
        foreach ($model as $val) {                                    
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->nombres)
                    ->setCellValue('B' . $i, $val->apellidos)
                    ->setCellValue('C' . $i, $val->tipoIdentificacion)
                    ->setCellValue('D' . $i, $val->identificacion)
                    ->setCellValue('E' . $i, $val->sexo)
                    ->setCellValue('F' . $i, $val->ciudad)
                    ->setCellValue('G' . $i, $val->telefonoOficina)
                    ->setCellValue('H' . $i, $val->direccionOficina)
                    ->setCellValue('I' . $i, $val->telefonoResidencia)
                    ->setCellValue('J' . $i, $val->ciudadResidencia)
                    ->setCellValue('K' . $i, $val->direccionResidencia)
                    ->setCellValue('L' . $i, $val->correo)
                    ->setCellValue('M' . $i, $val->telefonoCelular)
                    ->setCellValue('N' . $i, $val->vehMarca)
                    ->setCellValue('O' . $i, $val->placa)
                    ->setCellValue('P' . $i, $val->vin)
                    ->setCellValue('Q' . $i, $val->tipoVehiculo)
                    ->setCellValue('R' . $i, $val->version)
                    ->setCellValue('S' . $i, $val->motor)
                    ->setCellValue('T' . $i, $val->modelo)
                    ->setCellValue('U' . $i, $val->color)
                    ->setCellValue('V' . $i, $val->nroFactura)
                    ->setCellValue('W' . $i, date("d-m-Y",strtotime($val->fechaEntrega)))
                    ->setCellValue('X' . $i, $val->nombreConcesionario)
                    ->setCellValue('Y' . $i, $val->nombreVendedor)
                    ->setCellValue('Z' . $i, $val->nombreSalaVenta)
                    ->setCellValue('AA' . $i, $val->mesVenta)
                    ->setCellValue('AB' . $i, $val->nitEmpresa)
                    ->setCellValue('AC' . $i, $val->nom_contact_empresa)
                    ->setCellValue('AD' . $i, date("d-m-Y",strtotime($val->fechaNacimiento)))
                    ->setCellValue('AE' . $i, $val->estrato)
                    ->setCellValue('AF' . $i, $val->estadoCivil);
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
    
        public function actionPostventa() {
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
                    $this->actionExcelpostventa($model);     
                } else {
                    $form->getErrors();
                }
                                
            } else {
                $table = Posventa::find()
                        ->where(['=','id',0]);                                      
                $model = $table->all();                 
            }
            
            return $this->render('postventa', [
                        'model' => $model,
                        'form' => $form,                                                                       
            ]);
        } else {
            return $this->redirect(["site/login"]);
        }
    }
    
        public function actionExcelpostventa($model) {
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

        $objPHPExcel->getActiveSheet()->setTitle('ExportarPostVentas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ExportarPostVentas.xlsx"');
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