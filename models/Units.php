<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "units".
 *
 * @property string $id_unit
 * @property string $name
 */
class Units extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'units';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 1000],
            [['short'], 'string', 'max' => 10],
        ];
    }
    
    public static function getNames($id=0)
    {
	$all= ArrayHelper::map(Units::find()->all(), "id_unit", "short");
	if($id==0)
	    return $all;
	else
	    return $all[$id];
	
    }

    /**
     * @inheritdoc
     */
   public function attributeLabels()
    {
        return [
            'id_unit' => '#',
            'name' => __('Name'),
            'short' => __('Abbreviation'),
        ];
    }
    public function getFields(){
	$flds = [[['fld'=>'name', 'type'=>'text','max'=>1000],['fld'=>'short', 'type'=>'text','max'=>10]],];
	return $flds;
    }
    
    public function getAtt(){
	return ['id_unit', 'name', 'short'];
    }
}
