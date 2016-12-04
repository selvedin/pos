<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\Html;
use \yii\helpers\ArrayHelper;
/**
 * This is the model class for table "products".
 *
 * @property string $id_product
 * @property string $name
 * @property string $code
 * @property string $price
 * @property string $barcode
 * @property string $id_category
 * @property string $id_unit
 * @property string $image
 * @property string $imageThump
 * @property integer $status
 *
 * @property Categories $idCategory
 * @property Units $idUnit
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['price', 'color', 'size'], 'number'],
            [['id_category', 'id_unit', 'status'], 'integer'],
            [['name'], 'string', 'max' => 1000],
	    [['image'], 'safe'],
            [['code'], 'string', 'max' => 512],
            [['barcode'], 'string', 'max' => 100],
            [['id_category'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['id_category' => 'id_category']],
            [['id_unit'], 'exist', 'skipOnError' => true, 'targetClass' => Units::className(), 'targetAttribute' => ['id_unit' => 'id_unit']],
        ];
    }

    public static function getNames($id=0)
    {
	if($id==0)
	    return ArrayHelper::map(Products::find()->all(), 'id_product', 'name');
	else
	    return Products::findOne ($id)->name;
    }
    
    public static function getStatus($id=-1){
	$status=[0=>__("Out of Stock"), 1=>__("In Stock")];
	if($id>-1)
	    return $status[$id];
	else
	    return $status;
    }
    
       public function getFields(){
	$flds = [
		    [
			['fld'=>'name', 'type'=>'text','max'=>1000],
			['fld'=>'price', 'type'=>'text','max'=>512],
			['fld'=>'code', 'type'=>'text','max'=>512],
			['fld'=>'barcode', 'type'=>'text','max'=>100],
			['fld'=>'image', 'type'=>'file'],
			
		    ],
		    [
			['fld'=>'status', 'type'=>'check','vals'=>  Products::getStatus(), 'yes'=>true],
			['fld'=>'id_unit', 'type'=>'drop', 'vals'=>  Units::getNames(), "single"=>true],
			['fld'=>'color', 'type'=>'drop', 'vals'=>  Settings::getNames('Colors'), "single"=>true],
			['fld'=>'size', 'type'=>'drop', 'vals'=>  Settings::getNames('ClothSizes'), "single"=>true],
			['fld'=>'id_category', 'type'=>'drop', 'vals'=>  Categories::getNames(), "single"=>true],
			
		    ]
	    ];
	return $flds;
    }

    public function getAtt(){
	return ['id_product', 'name', 'code', 'price', 'barcode', 'imageThump', 'id_category', 'status'];
    }
    public function getVals($model){
	$file="";
	foreach(array("jpg", "jpeg", "png", "PNG", "gif", "GIF") as $ext){
	    if(file_exists(Yii::$app->basePath . "/uploads/products/thumbs/products_$model->id_product.".$ext))
		    $file="../uploads/products/thumbs/products_$model->id_product.".$ext;
	}
	return ['id_product', 'name','price', 
		['attribute'=>'id_category', 'value'=>Categories::getNames($model->id_category)],
		['attribute'=>'status', 'value'=> Products::getStatus($model->status)],
		['attribute'=>'color', 'value'=> Settings::getNames('Colors')[$model->color]],
		['attribute'=>'size', 'value'=> Settings::getNames('ClothSizes')[$model->size]],
		['attribute'=>'imageThump', 'value'=> $file,'format' => ['image',['width'=>'100','height'=>'100']]],
	    ];
    }
    public function getList($model){
	return ['id_product', 'name', 'code', 'price', 'barcode', 'imageThump', 'id_category', 'status'];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_product' => '#',
            'name' => __('Name'),
            'code' => __('Code'),
            'price' => __('Price'),
            'barcode' => __('Barcode'),
            'id_category' => __('Category'),
            'id_unit' => __('Unit'),
            'image' => __('Image'),
            'imageThump' => __('Image'),
            'status' => __('In Stock'),
            'color' => __('Color'),
            'size' => __('Size'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCategory()
    {
        return $this->hasOne(Categories::className(), ['id_category' => 'id_category']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUnit()
    {
        return $this->hasOne(Units::className(), ['id_unit' => 'id_unit']);
    }
    
      public function beforeSave($insert)
    {
	if (parent::beforeSave($insert)) {
	    $max=(int)Yii::$app->db->createCommand("select max(id_product) from products")->queryScalar();
	    $max++;
	    if($this->barcode==''){
		
		if($this->id_product)
		    $this->barcode = str_pad ($this->id_product,10-strlen($this->id_product),"0", STR_PAD_LEFT);
		else
		    $this->barcode = str_pad ($max,10-strlen($max),"0", STR_PAD_LEFT);
	    }
	    return true;
	} else {
	    return false;
	}
    }
}
