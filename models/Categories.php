<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "categories".
 *
 * @property string $id_category
 * @property string $name
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    
    public $image;
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 1000],
            [['parent'], 'integer'],
	    [['image'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_category' => '#',
            'name' => __('Name'),
            'parent' => __('Parent'),
        ];
    }
    public function getFields(){
	$flds = [
		    [['fld'=>'parent', 'type'=>'drop','vals'=>  Categories::getNames()],['fld'=>'name', 'type'=>'text','max'=>1000],],
		    [['fld'=>'image', 'type'=>'file'],],
	    ];
	return $flds;
    }

    public function getAtt(){
	return ['id_category', 'name', 'parent'];
    }
    
    public function getVals($model){
	$file="";
	foreach(array("jpg", "jpeg", "png", "PNG", "gif", "GIF") as $ext){
	    if(file_exists(Yii::$app->basePath . "/uploads/categories/thumbs/categories_$model->id_category.".$ext))
		    $file="../uploads/categories/thumbs/categories_$model->id_category.".$ext;
	}
	return ['id_category', 'name', ['attribute'=>'parent','value'=>$model->parent?Categories::getNames($model->parent):__("No Parent")],
		['attribute'=>'image', 'value'=> $file,'format' => ['image',['width'=>'100','height'=>'100']]],
	    ];
    }
    
    public function getList($model){
	return ['id_category', 'name', ['attribute'=>'parent','value'=>function($model){return $model->parent?Categories::getNames($model->parent):__("No Parent");}]];
    }
    
    public static function getName($id=0)
    {
       return @Categories::findOne($id)->name;
    }
    public static function getNames($id=0)
    {
	    if($id==0)
		$all=[0=> __("Select Parent")]+ ArrayHelper::map(Categories::find()->where("parent=0")->all(), "id_category", "name");
	    else
		$all=[0=>__("Select Parent")]+ ArrayHelper::map(Categories::find()->all(), "id_category", "name");
	    if($id)
		return $all[$id];
	    else
		return $all;
	}
     public static function getParent($id, $ar)
     {
	$next=Categories::findOne($id);
	if(count($next)){
	    $ar.= Categories::getParent($next->parent, $ar);
	    $ar.=$next->name . "->";
	}
	return $ar;
    }
     public static function getParentar($id, $ar)
     {
	$next=Categories::findOne($id);
	if(count($next)){
	    $ar+= Categories::getParentar($next->parent, $ar);
	    $ar[$id]=$next->name;
	}
	return $ar;
    }

}
