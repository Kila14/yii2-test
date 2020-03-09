<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="employee-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'surname',
            'position',
            [
                'attribute' => 'chieffullname',
                'value' => '<a href="/?r=employee/view&id=' . $model->chief_id . '">' . $model->chieffullname . '</a>',
                'format' => 'html',
            ],
            [
                'label' => 'Inferiors',
                //'value' => $model->inferiorsstring,

                'value' => function($model) {
                    $inferiors = $model->inferiors;
                    $inferiors = ArrayHelper::getColumn($inferiors, function($inferior) {
                        return '<a href="/?r=employee/view&id=' . $inferior['id'] . '">' . $inferior['name'] . ' ' . $inferior['surname'] . '</a>';
                    });
                    return implode(', ', $inferiors);
                },
                'format' => 'html',
            ],
            [
                'label' => 'Sex',
                'value' => $model->sexname,
            ],
            [
                'label' => 'Birthday',
                'value' => $model->birthdayparsed,
            ],
        ],
    ]) ?>

</div>
