<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Subir Archivo';
?>

<h1>Subir Información Postventa</h1>
<?php if ($tipomsg == "danger") { ?>
    <h3 class="alert-danger"><?= $msg ?></h3>
<?php } else{
    if (isset($_REQUEST['msg'])) { $dato = $_REQUEST['msg']; }else {$dato = $msg;}?>   
       <h3 class="alert-success"><?= $dato ?></h3>          
<?php } ?>
    
<?php foreach ($msgerror as $val){ ?>
    <?php echo "<h5 class='alert-danger'>".$val."</h5>" ?>          
<?php } ?>    
    
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
<br>    
<?= $form->field($model, 'file')->fileInput() ?>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Subir Archivo", ["class" => "btn btn-primary"])?>        
    </div>
</div>

<?php ActiveForm::end() ?>


