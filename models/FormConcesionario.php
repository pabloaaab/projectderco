<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Concesionario;

/**
 * ContactForm is the model behind the contact form.
 */
class FormConcesionario extends Model
{
    public $id;
    public $concesionario;

    public function rules()
    {
        return [

            ['id', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Solo se aceptan números'],
            ['concesionario', 'concesionario_existe'],
            ['concesionario', 'required', 'message' => 'Campo requerido'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '',
            'concesionario' => 'Concesionario:',

        ];
    }

    public function concesionario_existe($attribute, $params)
    {
        //Buscar el proceso en la tabla
        $table = Concesionario::find()->where("concesionario=:concesionario", [":concesionario" => $this->concesionario])->andWhere("id!=:id", [':id' => $this->id]);
        //Si el proceso existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "El concesionario ya existe ".$this->concesionario);
        }
    }
}
