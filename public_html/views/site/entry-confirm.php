<?php

use yii\helpers\HTML;

?>
<p>Вы ввели следующую информацию</p>
<ul>
    <li><label>Name</label>: <?= Html::encode($model->name) ?></li>
    <li><label>Email</label>: <?= Html::encode($model->email) ?></li>
</ul>