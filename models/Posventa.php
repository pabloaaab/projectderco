<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "posventa".
 *
 * @property int $id
 * @property string $nombresCliente
 * @property string $empresa
 * @property int $identificacion
 * @property string $usuario
 * @property string $marca
 * @property string $tipoVehiculo
 * @property int $anio
 * @property int $kilometraje
 * @property string $placaMatricula
 * @property string $vin
 * @property int $nroOrden
 * @property string $fechaOrden
 * @property int $nFactura
 * @property string $fechaFactura
 * @property int $telefono1
 * @property int $telefono2
 * @property int $extensionOficina
 * @property string $celular
 * @property string $ciudadOrigenTelefono
 * @property string $direccion
 * @property string $motivoIngresoTaller
 * @property string $motivoIngresoTaller2
 * @property string $aseguradora
 * @property string $email
 * @property string $asesorServicio
 * @property string $autorizacionCliente
 */
class Posventa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posventa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['identificacion', 'anio', 'kilometraje', 'nroOrden', 'nFactura', 'telefono1', 'telefono2', 'extensionOficina'], 'integer'],
            [['fechaOrden', 'fechaFactura'], 'safe'],
            [['nombresCliente', 'empresa', 'usuario', 'marca', 'tipoVehiculo', 'vin', 'ciudadOrigenTelefono', 'email', 'asesorServicio'], 'string', 'max' => 50],
            [['placaMatricula'], 'string', 'max' => 30],
            [['celular'], 'string', 'max' => 25],
            [['direccion', 'motivoIngresoTaller', 'motivoIngresoTaller2'], 'string', 'max' => 100],
            [['aseguradora'], 'string', 'max' => 20],
            [['autorizacionCliente'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombresCliente' => 'Nombres Cliente',
            'empresa' => 'Empresa',
            'identificacion' => 'Identificacion',
            'usuario' => 'Usuario',
            'marca' => 'Marca',
            'tipoVehiculo' => 'Tipo Vehiculo',
            'anio' => 'Anio',
            'kilometraje' => 'Kilometraje',
            'placaMatricula' => 'Placa Matricula',
            'vin' => 'Vin',
            'nroOrden' => 'Nro Orden',
            'fechaOrden' => 'Fecha Orden',
            'nFactura' => 'N Factura',
            'fechaFactura' => 'Fecha Factura',
            'telefono1' => 'Telefono1',
            'telefono2' => 'Telefono2',
            'extensionOficina' => 'Extension Oficina',
            'celular' => 'Celular',
            'ciudadOrigenTelefono' => 'Ciudad Origen Telefono',
            'direccion' => 'Direccion',
            'motivoIngresoTaller' => 'Motivo Ingreso Taller',
            'motivoIngresoTaller2' => 'Motivo Ingreso Taller2',
            'aseguradora' => 'Aseguradora',
            'email' => 'Email',
            'asesorServicio' => 'Asesor Servicio',
            'autorizacionCliente' => 'Autorizacion Cliente',
        ];
    }
}
