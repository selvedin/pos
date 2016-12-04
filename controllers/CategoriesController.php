<?php

namespace app\controllers;
use \app\models\Categories;
use yii\helpers\ArrayHelper;
/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends AdminController
{
    /**
     * @inheritdoc
     */
    
    public function actionGetparent($id)
    {
	$cat = ArrayHelper::map(Categories::find()->where("parent=$id")->all(),'id_category', 'name');
	return json_encode($cat);
    }
    
    public function actionGetlist($id)
    {
	$scarr=array();
	$scarr = Categories::getParentar($id, $scarr);
	$li='<li><a class="bc-btn" href="#" data-cat="0">'. __("Home") .'</a></li>';
	foreach ($scarr as $key => $value) {
	    $li.='<li><a class="bc-btn" href="#" data-cat="'.$key.'">'. __($value) .'</a></li>';
	}
	return $li;
    }
 
}
