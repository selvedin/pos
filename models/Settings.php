<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property string $type
 * @property string $n
 * @property string $n_ar
 * @property string $a1_ar
 * @property string $a1
 * @property string $a2
 * @property string $a3
 * @property string $a4
 * @property integer $i1
 * @property integer $i2
 * @property integer $i3
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'n'], 'required'],
            [['a4'], 'string'],
            [['i1', 'i2', 'i3'], 'integer'],
            [['type'], 'string', 'max' => 50],
            [['n', 'n_ar'], 'string', 'max' => 5000],
            [['a1_ar'], 'string', 'max' => 100],
            [['a1', 'a2', 'a3'], 'string', 'max' => 2500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'type' => __('Type'),
            'n' => __('Name'),
            'n_ar' => __('Name Ar'),
            'a1_ar' => __('Value 1 Ar'),
            'a1' => __('Value 1'),
            'a2' => __('Value 2'),
            'a3' => __('Value 3'),
            'a4' => __('Value 4'),
            'i1' => __('Int 1'),
            'i2' => __('Int 2'),
            'i3' => __('Int 3'),
        ];
    }

    public static function getNames($id='')
    {
	$names=['Config', 'BankAccounts', 'Colors', 'ClothSizes'];
	if(is_array($id) && count($id)){
	    $all='';
	    foreach ($id as $v) {
		$all .=Settings::findOne($v)->n . ",";
	    }
	    return $all;
	}
	else if($id=='')
	    return $names;
	else{
	    return ArrayHelper::map(Settings::find()->where("type='$id'")->all(), "id", "n");
	}
    }
     public static function getStatus($id=-1){
	$status=[0=>__("Inactive"), 1=>__("Active")];
	if($id==-1)
	    return $status;
	else
	    return $status[$id];
    }
    
    public static function getItems($id=0){
	$items = [
	    ['title'=>__("Categories"), 'icon'=>'glyphicon glyphicon-list-alt', 'link'=>['categories/index']],
	    ['title'=>__("Customers"), 'icon'=>'fa fa-users','link'=>['customers/index']],
	    ['title'=>__("Units"), 'icon'=>'glyphicon glyphicon-scale','link'=>['units/index']],
	];
	return $items;
    }
    
      public function getFields(){

	$flds = [[['fld'=>'n', 'type'=>'text','max'=>5000],],];
	if(@$_GET['type']=="BankAccounts")
	    $flds = [[['fld'=>'n', 'type'=>'text','max'=>5000, 'label'=>__("Name")],['fld'=>'a1', 'type'=>'text','max'=>5000, 'label'=>__("Account")],['fld'=>'a2', 'type'=>'text','max'=>5000, 'label'=>__("Address")],],];
	else if(@$_GET['type']=="Config")
	    $flds = [[['fld'=>'n', 'type'=>'text','max'=>5000, 'label'=>__("Name")],['fld'=>'a1', 'type'=>'text','max'=>5000, 'label'=>__("Value")],],];
	return $flds;
    }

        
     public function getAtt(){
	return ['id', 'type', 'n', 'a1', 'a2', 'a3', 'a4', 'i1', 'i2'];
    }
   
    public function getVals($model){
	return ['id', 'type', 'n', 'a1', 'a2', 'a3', 'a4', 'i1', 'i2'];
    }
    public function getList($model){
	return ['id', 'type', 'n', 'a1', 'a2', 'a3', 'a4', 'i1', 'i2'];
    }
}
