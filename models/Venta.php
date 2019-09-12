<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "venta".
 *
 * @property int $id
 * @property string $nombres
 * @property string $apellidos
 * @property string $tipoIdentificacion
 * @property int $identificacion
 * @property string $sexo
 * @property resource $ciudad
 * @property int $telefonoOficina
 * @property string $direccionOficina
 * @property int $telefonoResidencia
 * @property string $ciudadResidencia
 * @property string $direccionResidencia
 * @property string $correo
 * @property int $telefonoCelular
 * @property string $vehMarca
 * @property string $placa
 * @property string $vin
 * @property string $tipoVehiculo
 * @property string $version
 * @property string $motor
 * @property int $modelo
 * @property string $color
 * @property int $nroFactura
 * @property string $fechaEntrega
 * @property string $nombreConcesionario
 * @property string $nombreVendedor
 * @property string $nombreSalaVenta
 * @property int $mesVenta
 * @property string $nitEmpresa
 * @property string $nom_contact_empresa
 * @property string $fechaNacimiento
 * @property string $estrato
 * @property string $estadoCivil
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
            [['identificacion', 'telefonoOficina', 'telefonoResidencia', 'telefonoCelular', 'modelo', 'nroFactura', 'mesVenta'], 'integer'],
            [['fechaEntrega', 'fechaNacimiento'], 'safe'],
            [['nombres', 'apellidos', 'ciudadResidencia', 'correo', 'vehMarca', 'vin', 'version', 'motor', 'nombreConcesionario', 'nombreVendedor', 'nombreSalaVenta', 'nom_contact_empresa'], 'string', 'max' => 50],
            [['tipoIdentificacion', 'sexo', 'nitEmpresa', 'estadoCivil'], 'string', 'max' => 20],
            [['ciudad', 'color'], 'string', 'max' => 40],
            [['direccionOficina', 'direccionResidencia'], 'string', 'max' => 100],
            [['placa'], 'string', 'max' => 10],
            [['tipoVehiculo'], 'string', 'max' => 30],
            [['estrato'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombres' => 'Nombres',
            'apellidos' => 'Apellidos',
            'tipoIdentificacion' => 'Tipo Identificacion',
            'identificacion' => 'Identificacion',
            'sexo' => 'Sexo',
            'ciudad' => 'Ciudad',
            'telefonoOficina' => 'Telefono Oficina',
            'direccionOficina' => 'Direccion Oficina',
            'telefonoResidencia' => 'Telefono Residencia',
            'ciudadResidencia' => 'Ciudad Residencia',
            'direccionResidencia' => 'Direccion Residencia',
            'correo' => 'Correo',
            'telefonoCelular' => 'Telefono Celular',
            'vehMarca' => 'Veh Marca',
            'placa' => 'Placa',
            'vin' => 'Vin',
            'tipoVehiculo' => 'Tipo Vehiculo',
            'version' => 'Version',
            'motor' => 'Motor',
            'modelo' => 'Modelo',
            'color' => 'Color',
            'nroFactura' => 'Nro Factura',
            'fechaEntrega' => 'Fecha Entrega',
            'nombreConcesionario' => 'Nombre Concesionario',
            'nombreVendedor' => 'Nombre Vendedor',
            'nombreSalaVenta' => 'Nombre Sala Venta',
            'mesVenta' => 'Mes Venta',
            'nitEmpresa' => 'Nit Empresa',
            'nom_contact_empresa' => 'Nom Contact Empresa',
            'fechaNacimiento' => 'Fecha Nacimiento',
            'estrato' => 'Estrato',
            'estadoCivil' => 'Estado Civil',
        ];
    }
}
