<?php

namespace app\controllers;
use app\models\Perms;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class PermsController extends AdminController
{
    /**
     * @inheritdoc
     */
    
    public function actionIndex()
    {
	$model=null;
	$cache = \Yii::$app->cache;
	$data = $cache->get("pos_user_perms");
	if ($data===false) {
	    $all = Perms::find()->all();
	    foreach ($all as $a) {
		$model[$a->object]= $a->perms;
	    }
	    $cache->set("pos_user_perms", $model, 15*60);
	}
	else{
	    $model=$data;
	}
	if(isset($_POST['Perms'])){
	    foreach ($_POST['Perms'] as $k=>$v) {
		$new = Perms::find()->where("object='$k'")->one();
		if(isset($new))
		    $new->perms = serialize ($_POST['Perms'][$k]);
		else{
		    $new = new Perms;
		    $new->object = $k;$new->perms = serialize ($_POST['Perms'][$k]);
		}
		$new->save();
	    }
	    $all = Perms::find()->all();
	    foreach ($all as $a) {
		$model[$a->object]= $a->perms;
	    }
	    $cache->set("pos_user_perms", $model, 15*60);
	}
	return $this->render("index", ['model'=>$model]);
    }
 
}
