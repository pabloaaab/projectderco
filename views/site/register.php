<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Concesionario;

$this->title = 'Nuevo Usuario';
?>
    <h1>Registro de Usuarios</h1>
    
    <?php if ($tipomsg == "danger") { ?>
    <h3 class="alert-danger"><?= $msg ?></h3>
    <?php } else { ?>
    <h3 class="alert-success"><?= $msg ?></h3>
    <?php } ?>
    
    <?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
]);

?>
<?php
$concesionario = ArrayHelper::map(Concesionario::find()->all(), 'id', 'concesionario');
?>    
    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($model, "username")->input("text") ?>
            <?= $form->field($model, "email")->input("email") ?>
            <?= $form->field($model, "password")->input("password") ?>
            <?= $form->field($model, "password_repeat")->input("password") ?>
            <?= $form->field($model, "nombrecompleto")->input("text") ?>
            <?= $form->field($model, 'perfil')->dropdownList(['1' => 'usuario', '2' => 'Administrador'], ['prompt' => 'Seleccione...']) ?>            
            <?= $form->field($model, 'id_concesionario')->dropDownList($concesionario, ['prompt' => 'Seleccione un concesionario...']) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-4">
            <?= Html::submitButton("Guardar", ["class" => "btn btn-primary"]) ?>
            <a href="<?= Url::toRoute("site/usuarios") ?>" class="btn btn-primary">Regresar</a>
        </div>
    </div>

<?php $form->end() ?>