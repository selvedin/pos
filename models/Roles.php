<?php

namespace app\models;

use Yii;
use \yii\helpers\ArrayHelper;
/**
 * This is the model class for table "roles".
 *
 * @property string $id_role
 * @property string $name
 * @property string $description
 */
class Roles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'roles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 256],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_role' => '#',
            'name' => __('Name'),
            'description' => __('Description'),
        ];
    }
      public function getFields(){
	$flds = [[
	    ['fld'=>'name', 'type'=>'text','max'=>256],
	    ['fld'=>'description', 'type'=>'area','max'=>1000],],
	    ];
	return $flds;
    }
    
    public function getAtt(){
	return ['id_role', 'name', 'description'];
    }
    
    public static function getRoles($id=0){
	$roles = ArrayHelper::map(Roles::find()->all(), "id_role", "name");
	if(!is_array($id) && $id)
	    return $roles[$id];
	else if(is_array($id))
	{
	    $r="";
	    foreach ($id as $i) {
		$r .= $roles[$i].",";
	    }
	    return $r;
	}
	else
	    return $roles;
    }
}
