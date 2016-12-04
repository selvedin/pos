<style>
    div.list-items{background-color: #fff;padding: 10px;}
    tfoot td{font-size: 18px;font-weight: bolder;}
</style>
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = __(ucfirst(str_replace("_", " ", $model->tableName())));
if($model->hasMethod("getVals"))
    $cols=$model->getVals($model);
else
    $cols=$model->getAtt();
?>
<div class="div-form">
    <h1 class="title"><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('../layouts/buttons', ['model' => $model]); ?>
    <div style="display: none;">
	 <?= Html::a('Delete', ['delete', 'id' => (int)@$_GET['id']], [
		'class' => 'btn btn-danger btn-delete',
		'data' => [
		    'confirm' => __('Are you sure you want to delete this item').'?',
		    'method' => 'post',
		],
	    ]) ?>
    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $cols,
    ]) ?>
</div>
<?php
    if(file_exists(Yii::$app->basePath .'/views/special/views/'.  ucfirst($model->tableName()).".php"))
	require_once Yii::$app->basePath .'/views/special/views/'.  ucfirst($model->tableName()).".php";
?>

