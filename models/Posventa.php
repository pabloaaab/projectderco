<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "posventa".
 *
 * @property int $id
 * @property int $cliIdent
 * @property string $cliTipIdent
 * @property string $cliNombres
 * @property string $cliApellidos
 * @property string $empresa
 * @property string $cliSexo
 * @property string $cliCiuResidencia
 * @property string $cliDepResidencia
 * @property int $telefono1
 * @property int $telefono2
 * @property int $cliCelular
 * @property string $cliDirResidencia
 * @property string $cliEmail
 * @property string $vehVin
 * @property string $vehMarca
 * @property string $vehVersion
 * @property int $vehModeloAnio
 * @property string $vehColor
 * @property string $vehPlaca
 * @property string $concNombre
 * @property string $nomSala
 * @property string $concCiudad
 * @property string $nomVendedor
 * @property string $fecEntVeh
 * @property string $fechaCarga
 * @property int $nFactura
 * @property int $nOrden
 * @property int $kilometraje
 * @property string $motivoIngreso
 * @property int $tipificacionIngreso
 * @property string $fechaNacimiento
 * @property string $estrato
 * @property string $estadoCivil
 * @property string $concatenado
 * @property int $codConcesionario
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
            [['cliIdent', 'telefono1', 'telefono2', 'cliCelular', 'vehModeloAnio', 'nFactura', 'nOrden', 'kilometraje', 'tipificacionIngreso', 'codConcesionario'], 'integer'],
            [['fecEntVeh', 'fechaCarga', 'fechaNacimiento'], 'safe'],
            [['cliTipIdent', 'cliSexo', 'estrato', 'estadoCivil'], 'string', 'max' => 20],
            [['cliNombres', 'cliApellidos', 'empresa', 'cliEmail', 'vehMarca', 'nomVendedor'], 'string', 'max' => 50],
            [['cliCiuResidencia', 'cliDepResidencia'], 'string', 'max' => 25],
            [['cliDirResidencia'], 'string', 'max' => 70],
            [['vehVin', 'vehPlaca'], 'string', 'max' => 10],
            [['vehVersion'], 'string', 'max' => 30],
            [['vehColor', 'concNombre', 'nomSala', 'concCiudad'], 'string', 'max' => 40],
            [['motivoIngreso'], 'string', 'max' => 150],
            [['concatenado'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cliIdent' => 'Cli Ident',
            'cliTipIdent' => 'Cli Tip Ident',
            'cliNombres' => 'Cli Nombres',
            'cliApellidos' => 'Cli Apellidos',
            'empresa' => 'Empresa',
            'cliSexo' => 'Cli Sexo',
            'cliCiuResidencia' => 'Cli Ciu Residencia',
            'cliDepResidencia' => 'Cli Dep Residencia',
            'telefono1' => 'Telefono1',
            'telefono2' => 'Telefono2',
            'cliCelular' => 'Cli Celular',
            'cliDirResidencia' => 'Cli Dir Residencia',
            'cliEmail' => 'Cli Email',
            'vehVin' => 'Veh Vin',
            'vehMarca' => 'Veh Marca',
            'vehVersion' => 'Veh Version',
            'vehModeloAnio' => 'Veh Modelo Anio',
            'vehColor' => 'Veh Color',
            'vehPlaca' => 'Veh Placa',
            'concNombre' => 'Conc Nombre',
            'nomSala' => 'Nom Sala',
            'concCiudad' => 'Conc Ciudad',
            'nomVendedor' => 'Nom Vendedor',
            'fecEntVeh' => 'Fec Ent Veh',
            'fechaCarga' => 'Fecha Carga',
            'nFactura' => 'N Factura',
            'nOrden' => 'N Orden',
            'kilometraje' => 'Kilometraje',
            'motivoIngreso' => 'Motivo Ingreso',
            'tipificacionIngreso' => 'Tipificacion Ingreso',
            'fechaNacimiento' => 'Fecha Nacimiento',
            'estrato' => 'Estrato',
            'estadoCivil' => 'Estado Civil',
            'concatenado' => 'Concatenado',
            'codConcesionario' => 'Cod Concesionario',
        ];
    }
}
