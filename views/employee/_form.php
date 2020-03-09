<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */
/* @var $employees array */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

    <?php if (! empty($employees)) : ?>
        <?= $form->field($model, 'chief_id')->dropdownList(
            $employees,
            ['prompt' => 'Select chief']
        )->label('Chief'); ?>
    <?php endif; ?>

    <?php if (! empty($employees)) : ?>
        <?= $form->field($model, 'sex')->dropdownList(
            ['1' => 'Man', '0' => 'Woman'],
            ['prompt' => 'Select sex']
        ); ?>
    <?php endif; ?>

    <?//= $form->field($model, 'birthday')->textInput() ?>

    <?= $form->field($model, 'birthday')->textInput([
        'type' => 'date',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
