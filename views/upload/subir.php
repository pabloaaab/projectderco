<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Subir Archivo';
?>

<h1>Subir Archivo</h1>
<h3 class="alert-success"><?= $msg ?></h3>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
<?= $form->field($model, 'imageFile')->fileInput() ?>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Subir Archivo", ["class" => "btn btn-primary"])?>        
    </div>
</div>

<?php ActiveForm::end() ?>


