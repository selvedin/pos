<?php
use yii\helpers\Html;
?>

<div class="view-bottom">
    <ul class="nav nav-tabs">
	<li class="active"><a href="#tab1" data-toggle="tab"><?php echo __("Ulaz") ?></a></li>
	<li><a href="#tab2" data-toggle="tab"><?php echo __("Stanje") ?></a></li>
    </ul>
    <div class="tab-content">
	    <div class="tab-pane active" id="tab1">
		<?php
		    use yii\grid\GridView;
		    if($model->hasMethod("getList"))
			$cols=$model->getList($model);
		    else
			$cols=$model->getAtt();
		    $searchModel = new \app\models\search\IncomesSearch();
		    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		    $dataProvider->pagination->pageSize=15;
		?>
		  <?= GridView::widget([
		    'dataProvider' => $dataProvider,
		    'filterModel' => $searchModel,
		    'columns' => $cols,
		]); ?>
	    </div>
	    <div class="tab-pane" id="tab2">
		
	    </div>
    </div>
</div>
<form id='code-form' method='post'>
<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
    <input type='text' name='code' id='code-search' style='width:300px;hegith:40px;'/> 
    <span class='fa fa-search' style='color:white;cursor:pointer;'></span>
</form>
<script>
    $("div.div-form").before($("form#code-form"));
</script>