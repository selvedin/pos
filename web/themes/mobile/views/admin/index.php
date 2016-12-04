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
<style>
    thead{display: none;}
    div.grid-view table th,  div.grid-view table td{display: none;}
    div.grid-view table td:nth-child(2),div.grid-view table td:nth-child(3),div.grid-view table td:nth-child(4),div.grid-view table td:nth-child(5){display:block;}
    div.grid-view table tr:nth-child(even) td{background-color: #eee;border:0;}
    div.grid-view table tr:nth-child(odd) td{background-color: #AEAEAE;border:0;}
    div.grid-view table tr td:nth-child(5){border-bottom:1px solid lightgray;}
    div.grid-view table tr td div{display: inline-block;width:49%;}
    div.grid-view table tr td:nth-child(2){background-color: #FF8C00;color:#fff;}
    input.input-search{width: 85%;height: 35px;margin:0 5px 5px 0;border-radius: 5px;border:none;padding:0 10px;}
    div.summary{font-size: 10px;font-weight: bolder;}
    
</style>
<div>
    <form method="post" autocomplete="off" id="search-form">
	<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
	<input type="text" name="code" value="<?= @$_POST['code']?>" class="input-search" placeholder="<?= __("Search") ?> ..."/>
	<a href="#" class="btn btn-default a-search"><span class="fa fa-search"></span></a>
    </form>
</div>
<div class="div-form">

    <h1 class="title"><?= Html::encode($this->title) ?></h1>
    <?php
    if(Yii::$app->controller->id=="products" && isset($_POST['code'])){
	$dataProvider=$searchModel->search(Yii::$app->request->queryParams);
	$dataProvider->query->where("code LIKE '%".@$_POST['code']."%' or name  LIKE '%".@$_POST['code']."%'  or price  LIKE '%".@$_POST['code']."%' or barcode  LIKE '%".@$_POST['code']."%'");
    }
    echo $this->render('../layouts/buttons', ['model' => $searchModel]); 
    $dataProvider->pagination->pageSize=4;
    ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $cols,
	'rowOptions' => function ($model, $key, $index, $grid) {
                return ['id' => $model['id_product'], 'onclick' => 'window.location="'.Yii::$app->urlManager->createUrl(Yii::$app->controller->id."/update").'&id='.$key.';"'];
        },
	'pager' => [
	    'maxButtonCount'=>7,    // Set maximum number of page buttons that can be displayed
        ],
    ]); ?>
    <div class="div-bottom" style="text-align: center;"></div>
</div>

<script>
    $("div.div-bottom").append($("ul.pagination"));
    var th=[];
    for(i=2;i<=5;i++)
    {
	th[i]=$("thead tr th:nth-child("+i+")");
    }
    $("div.grid-view table tr").each(function(){
	var td=[];
	 for(i=2;i<=5;i++)
	{
	    td[i]=$(this).find("td:nth-child("+i+")");
	    td[i].html("<div>"+th[i].text()+"</div><div>"+td[i].text()+"</div>");
	}
    });
    
    //$(document).on("click", "div.grid-view table tr", function(){window.location="<?= Yii::$app->urlManager->createUrl(Yii::$app->controller->id."/update");?>&id="+$(this).data("key");});
    $("a.a-search").on("click", function(){$("form#search-form").submit();});
</script>

