<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Concesionarios';
?>

    <h1>Concesionarios</h1>
<?php $f = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("concesionario/index"),
    "enableClientValidation" => true,
]);
?>

<div class="form-group">
    <?= $f->field($form, "buscar")->input("search") ?>
</div>

<div class="row" >
    <div class="col-lg-4">
        <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
        <a align="right" href="<?= Url::toRoute("concesionario/index") ?>" class="btn btn-primary">Actualizar</a>
    </div>
</div>
<?php $f->end() ?>

<h3><?= $search ?></h3>

    <div class = "form-group" align="right">
        <a align="right" href="<?= Url::toRoute("concesionario/nuevo") ?>" class="btn btn-primary">Nuevo Proceso</a>
    </div>

<div class="alert alert-info">Registros: <?= $pagination->totalCount ?></div>    

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Concesario</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>
                <th scope="row"><?= $val->id ?></th>
                <td><?= $val->concesionario ?></td>
                <td><a href="<?= Url::toRoute(["concesionario/editar", "id" => $val->id])?>" ><span class="glyphicon glyphicon-pencil"></span></a></td>
                <td>
                    <?= Html::a('', ['eliminar', 'id' => $val->id], [
                        'class' => 'glyphicon glyphicon-trash',
                        'data' => [
                            'confirm' => 'Esta seguro de eliminar el registro?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </td>
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>

        <div class = "form-group" align="right">
            <a href="<?= Url::toRoute("concesionario/nuevo") ?>" class="btn btn-primary">Nuevo Concesionario</a>
        </div>
        <div class = "form-group" align="left">
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </div>
