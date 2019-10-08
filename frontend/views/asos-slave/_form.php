<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\AsosSlave */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asos-slave-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tovar_id')->textInput() ?>

    <?= $form->field($model, 'tovar_nom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'izm_id')->textInput() ?>

    <?= $form->field($model, 'izm1')->textInput() ?>

    <?= $form->field($model, 'tulov_id')->textInput() ?>

    <?= $form->field($model, 'seriya')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'polka')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'srok')->textInput() ?>

    <?= $form->field($model, 'turi')->textInput() ?>

    <?= $form->field($model, 'resept')->textInput() ?>

    <?= $form->field($model, 'qrkod')->textInput() ?>

    <?= $form->field($model, 'kol')->textInput() ?>

    <?= $form->field($model, 'kol_in')->textInput() ?>

    <?= $form->field($model, 'sena')->textInput() ?>

    <?= $form->field($model, 'sena_in')->textInput() ?>

    <?= $form->field($model, 'summa')->textInput() ?>

    <?= $form->field($model, 'summa_in')->textInput() ?>

    <?= $form->field($model, 'summa_all')->textInput() ?>

    <?= $form->field($model, 'sotish')->textInput() ?>

    <?= $form->field($model, 'sotish_in')->textInput() ?>

    <?= $form->field($model, 'foiz')->textInput() ?>

    <?= $form->field($model, 'foiz_in')->textInput() ?>

    <?= $form->field($model, 'subkod')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'changedate')->textInput() ?>

    <?= $form->field($model, 'asos_id')->textInput() ?>

    <?= $form->field($model, 'del_flag')->textInput() ?>

    <?= $form->field($model, 'kol_ost')->textInput() ?>

    <?= $form->field($model, 'kol_in_ost')->textInput() ?>

    <?= $form->field($model, 'summa_ost')->textInput() ?>

    <?= $form->field($model, 'summa_in_ost')->textInput() ?>

    <?= $form->field($model, 'summa_all_ost')->textInput() ?>

    <?= $form->field($model, 'sena_d')->textInput() ?>

    <?= $form->field($model, 'sena_in_d')->textInput() ?>

    <?= $form->field($model, 'sotish_d')->textInput() ?>

    <?= $form->field($model, 'sotish_in_d')->textInput() ?>

    <?= $form->field($model, 'zakaz')->textInput() ?>

    <?= $form->field($model, 'zakaz_ok')->textInput() ?>

    <?= $form->field($model, 'zakaz_end')->textInput() ?>

    <?= $form->field($model, 'zakaz_see')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
