<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Roles;
use app\models\Settings;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
echo Html::a("<i class='glyphicon glyphicon-floppy-disk'></i>", "#", ['class' => 'btn btn-default','id'=>'btn-save','title'=>__("Save")]);
$this->title = __("Permissions");

$Models = array();
$roles = Roles::getRoles();
unset($roles[1]);
foreach(glob('../models/*.php') as $filename){
   $tempM=  str_replace("../models/","",str_replace(".php", "", $filename));
   if(strpos($tempM, "_")==false)
	    $Models[$tempM]=$tempM;
}
$th="";
foreach ($roles as $r) {
    $th.="<th>$r</th>";
}
?>
<div class="div-form">
    <?php $form = ActiveForm::begin(); ?>
    <table class="table table-bordered"><thead><tr><th><?= __("Object")?></th><?= $th ?></tr></thead>
	<tbody>
	<?php
	    foreach ($Models as $value):
		$td="";
		foreach ($roles as $k=>$r) {
		    $td.="<td>". Html::dropDownList("Perms[$value][$k]",@$model[$value][$k],[0=>__("None"),"Index", "View", "Create", "Update", "Delete"], ["class"=>"form-control multiselect", "multiple"=>true])."</td>";
		}
	?>
	    <tr><td><?= __("$value") ?></td><?= $td?></tr>
	<?php
	    endforeach;
	?>
	</tbody>
    </table>
        <div class="form-group" style="display: none;">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-submit']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>