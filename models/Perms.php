<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "perms".
 *
 * @property string $id
 * @property string $object
 * @property string $perms
 */
class Perms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'perms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object'], 'required'],
            [['perms'], 'string'],
            [['object'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object' => 'Object',
            'perms' => 'Perms',
        ];
    }
    
    public static function getPerms($obj="", $act="", $id=0){
	if(Yii::$app->user->isGuest)
	    return 0;
	if($id>0)
	    $user = Users::findOne($id);
	else
	    $user = Users::findOne(Yii::$app->user->identity->id);
	if(is_array($user->roles) && in_array(1, $user->roles))
		return 1;
	else{
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
	    if(isset($model[$obj]) && is_array($user->roles)){
		foreach ($user->roles as $k) {
		    if(array_key_exists($k, $model[$obj]) && in_array($act, $model[$obj][$k]))
			    return 1;
		    else
			return 0;
		}
	    }
	    else return 0;
	}
    }
    
        public function afterFind()
    {
	parent::afterFind();
	    $this->perms = unserialize($this->perms);

    }
    
    /*
     * Action values for perms
     * 0 = none
     * 1 = index
     * 2 = view
     * 3 = create
     * 4 = update
     * 5 = delete
     */
}
