<?php

namespace app\models;

use Yii;
use \yii\helpers\ArrayHelper;
/**
 * This is the model class for table "suppliers".
 *
 * @property string $id_supplier
 * @property string $name
 * @property integer $contactPerson
 * @property string $address
 * @property string $phone
 * @property string $fax
 * @property string $web
 * @property string $email
 * @property string $bankAccounts
 * @property string $description
 * @property integer $status
 */
class Suppliers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'suppliers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['contactPerson', 'status'], 'integer'],
            [['name', 'email'], 'string', 'max' => 512],
	    ['email', 'email'],
	    ['bankAccounts', 'safe'],
            [['address', 'web', 'description'], 'string', 'max' => 1000],
            [['phone', 'fax'], 'string', 'max' => 128],
        ];
    }
    
        public static function getNames($id=0)
    {
	$all= ArrayHelper::map(Suppliers::find()->all(), "id_supplier", "name");
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
            'id_supplier' => '#',
            'name' => __('Name'),
            'contactPerson' => __('Contact Person'),
            'address' => __('Address'),
            'phone' => __('Phone'),
            'fax' => __('Fax'),
            'web' => __('Web'),
            'email' => __('Email'),
            'bankAccounts' => __('Bank Accounts'),
            'description' => __('Description'),
            'status' => __('Status'),
        ];
    }
    
      public function getFields(){
	$flds = [
		    [
			['fld'=>'name', 'type'=>'text','max'=>512],
			['fld'=>'email', 'type'=>'text','max'=>512],
			['fld'=>'address', 'type'=>'text','max'=>1000],
			['fld'=>'web', 'type'=>'text','max'=>1000],],
		    [
			['fld'=>'phone', 'type'=>'text','max'=>128],
			['fld'=>'fax', 'type'=>'text','max'=>128],
			['fld'=>'bankAccounts', 'type'=>'drop','max'=>1000, 'vals'=>  Settings::getNames('BankAccounts'), "multi"=>true],
			['fld'=>'description', 'type'=>'area','max'=>1000],
		    ]
	    ];
	return $flds;
    }

    public function getAtt(){
	return ['id_supplier', 'name', 'email', 'address', 'web', 'phone', 'fax', 'description'];
    }
    public function getVals($model){
	return ['id_supplier', 'name', 'email', 'address', 'web', 'phone', 'fax',['attribute'=>'bankAccounts', 'value'=> Settings::getNames($model->bankAccounts)], 'description'];
    }
    public function getList($model){
	return ['id_supplier', 'name', 'email', 'address', 'web', 'phone', 'fax',['attribute'=>'bankAccounts', 'value'=> function($model){return Settings::getNames($model->bankAccounts);}], 'description'];
    }
    
    public function beforeSave($insert)
    {
	if (parent::beforeSave($insert)) {
	    $this->bankAccounts = serialize($this->bankAccounts);
	    return true;
	} else {
	    return false;
	}
    }
    
    
    public function afterFind()
    {
	parent::afterFind();
	    $this->bankAccounts = unserialize($this->bankAccounts);

    }
}
