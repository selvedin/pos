<?php

namespace app\controllers;
use app\models\Products;
use app\models\Categories;
use app\models\Settings;
use app\models\Units;
use yii\helpers\ArrayHelper;
/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class ProductsController extends AdminController
{
    /**
     * @inheritdoc
     */
     public function actionGetproduct($search)
    {
	 $prod=array();
	$all=Products::find()->where("code like '%$search%' or name like '%$search%'")->all();
	foreach ($all as $a) {
	    $ar='';
	    $ar = Categories::getParent($a->id_category, $ar);
	    $color = Settings::getNames('Colors')[$a->color];
	    $size = Settings::getNames('ClothSizes')[$a->size];
	    $prod[$a->id_product]=[ "code"=>$a->code,"name"=>"<b>".$a->name . ":</b> <u>" .strtoupper(__("Category")) .":</u> ". rtrim($ar,"->"). ", <u>".strtoupper(__("Color")) .":</u> $color, <u>".strtoupper(__("Size")) .":</u> $size" , "unit"=>Units::getNames($a->id_unit),"price"=>$a->price,];
	}
	return json_encode($prod);
    }
    
     public function actionScanproduct($search)
    {
	$a=Products::find()->where("barcode ='$search'")->one();
	if(isset($a))
	    $prod[$a->id_product]=["name"=>$a->name,"price"=>$a->price,];
	else
	    $prod=null;
	return json_encode($prod);
	
    }
    
     public function actionGetlist($id)
    {
	$a=Products::find()->where("id_category =$id")->all();
	if(count($a)){
	    echo "<table class='table table-responsive products'><thead><tr><th>#</th><th>".__("Name")."</th><th>".__("Init")."</th><th>".__("Price")."</th></tr></thead><tbody>";
	    foreach ($a as $b) {
		echo "<tr id='tr-$b->id_category"."-"."$b->id_product' data-id='$b->id_product' data-price='$b->price' data-name='$b->name' data-unit='". $b->idUnit->name ."' draggable='true' ondragstart='drag(event)'><td>$b->id_product</td><td>$b->name</td><td>". $b->idUnit->name ."</td><td>$b->price</td></tr>";
	    }
	    echo "</tbody></table>";
	}

    }
    
    public function actionPrintprice()
    {
	if(isset($_POST['prices']) && is_array($_POST['prices'])){
	    foreach ($_POST['prices'] as $k=>$v){
		$prod=  Products::findOne($k);
		$prod->qnt=$v['qnt'];
		$prod->price2=$v['price'];
		$prod->save();
	    }
	}
	if(isset($_GET['barcode']))
	    return $this->render("printbarcode");
	else if(isset($_GET['print']))
	    return $this->render("printlist");
	else
	    return $this->render("setprice");
    }
 
}
