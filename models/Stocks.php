<?php

namespace app\models;

use Yii;
use \yii\helpers\ArrayHelper;
/**
 * This is the model class for table "stocks".
 *
 * @property string $id_stock
 * @property string $name
 * @property string $address
 * @property string $description
 */
class Stocks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stocks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 512],
            [['address', 'description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_stock' => '#',
            'name' => __('Name'),
            'address' => __('Address'),
            'description' => __('Description'),
        ];
    }
    
        public static function getNames($id=0)
    {
	$all= ArrayHelper::map(Stocks::find()->all(), "id_stock", "name");
	if($id==0)
	    return $all;
	else
	    return $all[$id];
    }
    
     public function getFields(){
	$flds = [
		    [
			['fld'=>'name', 'type'=>'text','max'=>512],
			['fld'=>'address', 'type'=>'text','max'=>1000],
			['fld'=>'description', 'type'=>'area','max'=>1000],
		    ]
	    ];
	return $flds;
    }

    public function getAtt(){
	return ['id_stock', 'name', 'address'];
    }
    public function getVals($model){
	return ['id_stock', 'name', 'address'];
    }
    public function getList($model){
	return ['id_stock', 'name', 'address'];
    }
}
