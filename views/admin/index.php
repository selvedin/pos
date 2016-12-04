<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Perms;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = __(ucfirst(str_replace("_", " ", $model->tableName())));
if($model->hasMethod("getList"))
    $cols=$model->getList($model);
else
    $cols=$model->getAtt();
array_push($cols, ['class' => 'yii\grid\ActionColumn','template'=>((Perms::getPerms(ucfirst($model->tableName()),2)?'{view}':'').(Perms::getPerms(ucfirst($model->tableName()),4)?'{update}':'').(Perms::getPerms(ucfirst($model->tableName()),5)?'{delete}':'')), 'contentOptions'=>['style'=>'width:80px;']])
?>
<div class="div-form">

    <h1 class="title"><?= Html::encode($this->title) ?></h1>
    <?php
    
    echo $this->render('../layouts/buttons', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $cols,
    ]); ?>
</div>
<script>
    $("div.toolbar div.third").append($("div.summary"));
    $("div.toolbar div.third").append($("ul.pagination"));
</script>

