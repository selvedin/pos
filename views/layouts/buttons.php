<?php
use yii\helpers\Html;
use app\models\Perms;
$cont = Yii::$app->controller->id;
$cp=  ucfirst($cont);
$act = Yii::$app->controller->action->id;
if($model == null && isset($_GET['id'])){
    $model = $cont::model()->findOne($_GET['id']);
}
$key=$model->tableSchema->primaryKey;
$str= file_get_contents(Yii::$app->basePath ."/controllers/". ucfirst($cont)."Controller.php");
$exists = strpos($str, "Print");
switch ($act){
    case "index":
	if(!in_array($cont, array("site")) && Perms::getPerms($cp,3))
	    echo Html::a("<i class='glyphicon glyphicon-plus'></i>", ['create'], ['class' => 'btn btn-default','id'=>'btn-create', 'title'=>__("Add") ." " . ucfirst ($cont)]);
	break;
    case "create":
	if(Perms::getPerms($cp,1))    
	    echo Html::a("<i class='glyphicon glyphicon-list'></i>", ['index'], ['class' => 'btn btn-default','id'=>'btn-index','title'=>__("List")]);
	if(Perms::getPerms($cp,3) || Perms::getPerms($cp,4))
	    echo Html::a("<i class='glyphicon glyphicon-floppy-disk'></i>", "#", ['class' => 'btn btn-default','id'=>'btn-save','title'=>__("Save")]);
	break;
    case "update":
	if(Perms::getPerms($cp,3) || Perms::getPerms($cp,4))
	    echo Html::a("<i class='glyphicon glyphicon-floppy-disk'></i>", "#", ['class' => 'btn btn-default','id'=>'btn-save','title'=>__("Save")]);
	if(Perms::getPerms($cp,1)) 
	    echo Html::a("<i class='glyphicon glyphicon-list'></i>", ['index'], ['class' => 'btn btn-default','id'=>'btn-index', 'title'=>__("List")]);
	if(Perms::getPerms($cp,2)){
	    echo Html::a("<i class='glyphicon glyphicon-eye-open'></i>", ['view', 'id' => $model->$key[0]], ['class' => 'btn btn-default','id'=>'btn-view','title'=>__("Update")]);
	    echo $exists!=false?Html::a("<i class='glyphicon glyphicon-print'></i>", ['print', 'id' => $model->$key[0]], ['class' => 'btn btn-default','id'=>'btn-print','title'=>__("Print"), 'target' => '_new']):"";
	}
	if(Perms::getPerms($cp,5)) 
	    echo  Html::a("<i class='glyphicon glyphicon-trash'></i>",'#',['class'=>'btn btn-default', 'id'=>'btn-delete']);
	break;
    case "view":
	if(Perms::getPerms($cp,3)){
	    echo Html::a("<i class='glyphicon glyphicon-plus'></i>", ['create'], ['class' => 'btn btn-default','id'=>'btn-create', 'title'=>__("Add") ." " . ucfirst ($cont)]);
	    echo Html::a("<i class='glyphicon glyphicon-copy'></i>", ['clone', 'id' => $model->$key[0]], ['class' => 'btn btn-default','id'=>'btn-clone', 'title'=>__("Clone") ." " . ucfirst ($cont),'data' => [
			    'confirm' => __('Are you sure you want to CLONE this item?'),
			    'method' => 'post',
        ],]);
	}
	if(Perms::getPerms($cp,4)) 
	    echo Html::a("<i class='glyphicon glyphicon-edit'></i>", ['update', 'id' => $model->$key[0]], ['class' => 'btn btn-default','id'=>'btn-update','title'=>__("Update")]);
	if(Perms::getPerms($cp,1)) 
	    echo Html::a("<i class='glyphicon glyphicon-list'></i>", ['index'], ['class' => 'btn btn-default','id'=>'btn-index', 'title'=>__("List")]);
	if(Perms::getPerms($cp,2)){
	    echo $exists!=false?Html::a("<i class='glyphicon glyphicon-print'></i>", ['print', 'id' => $model->$key[0]], ['class' => 'btn btn-default','id'=>'btn-print','title'=>__("Print"), 'target' => '_new']):"";
	    echo $model->tableName()=="products"?Html::a("<i class='glyphicon glyphicon-barcode'></i>", ['barcode', 'id' => $model->$key[0]], ['class' => 'btn btn-default','id'=>'btn-print','title'=>__("Print Barcode"), 'target' => '_new']):"";
	}
	if(Perms::getPerms($cp,5)) 
	    echo  Html::a("<i class='glyphicon glyphicon-trash'></i>",'#',['class'=>'btn btn-default', 'id'=>'btn-delete']);

	break;
    default:
	break;
}
?>

<script>
    
</script>
