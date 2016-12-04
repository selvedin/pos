<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$prname = $model->tableName();
$this->title = __(ucfirst(str_replace("_", " ", $prname)));
$options=['id' => $prname.'-form',];
if($this->title=="Products" || $this->title=="Categories")
	$options['enctype']="multipart/form-data";
if(isset($_GET['type']))
    $this->title = $_GET['type'];
?>
<div class="div-form">

    <h1 class="title"><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('../layouts/buttons', ['model' => $model]); ?>
    <?php $form = ActiveForm::begin( [
            'options' => $options
        ]); ?>

    <?php
    $flds = $model->getFields();
    if(is_array($flds) && count($flds)){
	foreach ($flds as $values) {
	    echo "<div class='row-float'>";
	    foreach ($values as $val) {
		switch ($val['type']){
		    case "text":
			$ar=[];
			$f = $form->field($model, $val['fld']);
			if($val['max']>0)$m=['maxlength' => true];
			if(isset($m)) $ar +=$m;
			$ar += ['readonly' => @$val['readonly']];
			$f = $f->textInput($ar);
			if(isset($val['label']))
			    $f = $f->label($val['label']);
			echo $f;
			break;
		    case "pass":
			$ar=[];
			$f = $form->field($model, $val['fld']);
			if($val['max']>0)$m=['maxlength' => true];
			if(isset($m)) $ar +=$m;
			$ar += ['readonly' => @$val['readonly']];
			$f = $f->passwordInput($ar);
			echo $f;
			break;
		    case "area":
			$ar=[];
			$f = $form->field($model, $val['fld']);
			if($val['max']>0)$m=['maxlength' => true];
			if(isset($m)) $ar +=$m;
			$ar += ['readonly' => @$val['readonly']];
			$f = $f->textArea($ar);
			echo $f;
			break;
		    case "date":
			$ar=[];
			$f = $form->field($model, $val['fld']);
			$d=['class'=>'form-control datepicker'];
			if($val['max']>0)$m=['maxlength' => true];
			if(isset($m)) $ar +=$m;
			if(isset($d)) $ar +=$d;
			$ar += ['readonly' => @$val['readonly']];;
			$f = $f->textInput($ar);
			echo $f;
			break;
		    case "drop":
			$ar=[];
			if(isset($val['multi']))
			    $ar +=['class'=>'form-control multiselect', 'multiple'=>true];
			$ar += ['readonly' => @$val['readonly']];
			echo $form->field($model, $val['fld'])->dropDownList($val['vals'], $ar);
			break;
		    case "check":
			$ar=['data-toggle'=>'toggle'];
			if(isset($val['yes']))$ar+=['data-on' => __("Yes")]+['data-off' => __("No")];
			if(isset($val['off']))$ar+=['data-on' => __("On")]+['data-off' => __("Off")];
			$ar += ['readonly' => @$val['readonly']];
			echo "<label for='".$val['fld']."'>".ucfirst($val['fld'])."</lable>" . $form->field($model, $val['fld'])->checkbox($ar+['label'=>null]);
			break;
		    case "file":
			$ar = ['readonly' => @$val['readonly']];
			echo "<i class='fa fa-camera fa-4x i-capture'></i>".$form->field($model, $val['fld'])->fileInput(['onchange'=>"readURL(this);"])."<div class='capture'></div><img class='form-image' src='../uploads/".$prname."/thumbs/".$prname."_".(int)@$_GET['id'].".jpg?".time()."' width='200px;'/>";
			break;
		    default:
			break;
//		
		}
	    }
	    echo "</div>";
	}
    }
?>
    <div class="form-group" style="display: none;">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-submit']) ?>
	 <?= Html::a('Delete', ['delete', 'id' => (int)@$_GET['id']], [
		'class' => 'btn btn-danger btn-delete',
		'data' => [
		    'confirm' => __('Are you sure you want to delete this item').'?',
		    'method' => 'post',
		],
	    ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php
    if(file_exists(Yii::$app->basePath .'/views/special/forms/'.  ucfirst($model->tableName()).".php"))
	require_once Yii::$app->basePath .'/views/special/forms/'.  ucfirst($model->tableName()).".php";
?>
</div>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('img.form-image')
                    .attr('src', e.target.result)
                    .width("200px");
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>