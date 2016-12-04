<?php
use \app\models\search\SettingsSearch;
use app\models\Settings;
use yii\helpers\Html;
use yii\grid\GridView;
$cols =[	//['class' => 'yii\grid\SerialColumn'],
		'id','n',
		['class' => 'yii\grid\ActionColumn','template'=>'{view}{update}{delete}', 'buttons'=>['view'=>function($model){return Html::a('<i class="fa fa-eye"></i>', ['settings/view', 'id'=>$model->id]);}]],
	  ];
?>
<style>
    div.div-form{
	padding:10px;
	margin: 10px;
    }
    table th:last-child{width:80px;}
</style>
<h1 class="title"><?= isset($_GET['type'])?$_GET['type']:__("Settings") ?></h1>

    <?php
	foreach (app\models\Settings::getNames() as $value) {
	    $searchModel = new SettingsSearch();
	    $dataProvider = $searchModel->search(Yii::$app->request->queryParams + ['SettingsSearch'=>['type'=>$value]]);
	    $dataProvider->pagination->pageSize=5;
	    echo "<div class='div-form $value'><h4 class='settings-title'>". ucfirst($value) ."</h4>";
	      echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		  
		'columns' => [	//['class' => 'yii\grid\SerialColumn'],
		'id','n',
		['class' => 'yii\grid\ActionColumn','template'=>'{view}{update}{delete}'],
	  ],
	    ]);
	      echo "<a href='#' data-type='$value' class='btn btn-default dlg-button'><i class='fa fa-plus'></i></a>";
	      echo "</div>";
	}
    ?>
<div id="dlgSettings" title="<?= __("Settings") ?>" ><iframe id="dlgSframe"  src=""></iframe></div>
<script>
    //setting up dialog boxes
    $(function(){
	$("#dlgSettings").dialog({
	autoOpen: false,show: "fade",hide: "fade",modal: true,height: 'auto', width: 'auto', resizable: false,
	create:function(){var i = $(this).find("iframe"); i.css('width', ($(window).width() - 300) + 'px'); i.css('height', ($(window).height() - 200) + 'px'); },
	});
    });
    
    $("a.dlg-button").on("click", function(){
	$("#dlgSettings").dialog("open");
	document.getElementById('dlgSframe').contentDocument.write('učitava se...');
	$('#dlgSframe').attr('src', '<?php echo Yii::$app->urlManager->createUrl(['settings/create']) ?>&type=' + $(this).data("type") + '&dlg=0');
	return false;
    });
</script>

<script>
    //on dialg close functions
    $('#dlgSettings').on('dialogclose', function(event) {
	window.location.reload();
    });
    $('#dlgSettings').on('dialogopen', function(event) {
	
    });
    
    $("h4.settings-title").on("click", function(){$(this).next("div.grid-view").toggle("fast");});
</script>