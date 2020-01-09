<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "venta".
 *
 * @property int $id
 * @property string $identificacion
 * @property string $tipoIdentificacion
 * @property string $nombres
 * @property string $apellidos
 * @property string $empresa
 * @property string $sexo
 * @property string $ciudadResidencia
 * @property string $dptoResidencia
 * @property int $telefono1
 * @property int $telefono2
 * @property int $celular
 * @property string $direccionResidencia
 * @property string $email
 * @property string $vehVin
 * @property string $vehMarca
 * @property string $vehVersion
 * @property int $vehModelo
 * @property string $vehColor
 * @property string $vehPlaca
 * @property string $nombreConcesionario
 * @property string $nombreSalaVenta
 * @property string $concCiudad
 * @property string $nombreVendedor
 * @property string $fechaEntrega
 * @property string $fechaCarga
 * @property string $nroFactura
 * @property string $nroOrden
 * @property string $fechaNacimiento
 * @property string $estrato
 * @property string $estadoCivil
 * @property string $concatenado
 * @property string $codConcesionario
 */
class Venta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['telefono1', 'telefono2', 'celular', 'vehModelo'], 'integer'],
            [['fechaEntrega', 'fechaCarga', 'fechaNacimiento'], 'safe'],
            [['identificacion'], 'string', 'max' => 15],
            [['tipoIdentificacion', 'sexo', 'nroFactura', 'nroOrden', 'estadoCivil'], 'string', 'max' => 20],
            [['nombres', 'apellidos', 'ciudadResidencia', 'dptoResidencia', 'email', 'vehVin', 'vehMarca', 'vehVersion', 'nombreConcesionario', 'nombreSalaVenta', 'concCiudad', 'nombreVendedor'], 'string', 'max' => 50],
            [['empresa'], 'string', 'max' => 12],
            [['direccionResidencia'], 'string', 'max' => 100],
            [['vehColor'], 'string', 'max' => 40],
            [['vehPlaca', 'estrato'], 'string', 'max' => 10],
            [['concatenado', 'codConcesionario'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'identificacion' => 'Identificacion',
            'tipoIdentificacion' => 'Tipo Identificacion',
            'nombres' => 'Nombres',
            'apellidos' => 'Apellidos',
            'empresa' => 'Empresa',
            'sexo' => 'Sexo',
            'ciudadResidencia' => 'Ciudad Residencia',
            'dptoResidencia' => 'Dpto Residencia',
            'telefono1' => 'Telefono1',
            'telefono2' => 'Telefono2',
            'celular' => 'Celular',
            'direccionResidencia' => 'Direccion Residencia',
            'email' => 'Email',
            'vehVin' => 'Veh Vin',
            'vehMarca' => 'Veh Marca',
            'vehVersion' => 'Veh Version',
            'vehModelo' => 'Veh Modelo',
            'vehColor' => 'Veh Color',
            'vehPlaca' => 'Veh Placa',
            'nombreConcesionario' => 'Nombre Concesionario',
            'nombreSalaVenta' => 'Nombre Sala Venta',
            'concCiudad' => 'Conc Ciudad',
            'nombreVendedor' => 'Nombre Vendedor',
            'fechaEntrega' => 'Fecha Entrega',
            'fechaCarga' => 'Fecha Carga',
            'nroFactura' => 'Nro Factura',
            'nroOrden' => 'Nro Orden',
            'fechaNacimiento' => 'Fecha Nacimiento',
            'estrato' => 'Estrato',
            'estadoCivil' => 'Estado Civil',
            'concatenado' => 'Concatenado',
            'codConcesionario' => 'Cod Concesionario',
        ];
    }
}
