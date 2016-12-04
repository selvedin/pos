<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "customers".
 *
 * @property string $id_customer
 * @property string $first_name
 * @property string $last_name
 * @property string $registration_date
 * @property string $email
 * @property string $user
 * @property integer $status
 *
 * @property Users $user0
 */
class Customers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'status'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 256],
            [['registration_date'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 1000],
            ['email','email'],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_customer' => '#',
            'first_name' => __('First Name'),
            'last_name' => __('Last Name'),
            'registration_date' => __('Registration Date'),
            'email' => __('Email'),
            'user' => __('User'),
            'status' => __('Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'user']);
    }
    
    public function getFields(){
	$flds = [
		[['fld'=>'first_name', 'type'=>'text','max'=>256],
		['fld'=>'last_name', 'type'=>'text','max'=>256],
		['fld'=>'registration_date', 'type'=>'date','max'=>20],],
		[['fld'=>'email', 'type'=>'text','max'=>1000],
		['fld'=>'status', 'type'=>'drop', 'max'=>0, 'vals'=>  Settings::getStatus()],
		['fld'=>'user', 'type'=>'drop', 'max'=>0, 'vals'=>[0=>__("Select") . " " . __("User")]+Users::getNames()],],
	    ];
	return $flds;
    }

       public static function getNames($id=0)
    {
	if($id==0)
	    return ArrayHelper::map(Customers::find()->select(['concat(first_name," ",last_name) as name', 'id_customer as  id'])->asArray()->all(), 'id', 'name');
	else{
	    $c=Customers::findOne($id);
	    return $c->first_name . " " . $c->last_name;
	}
    }
    
    public function getAtt(){
	return ['id_customer', 'first_name', 'last_name', 'registration_date', 'email', 'status', 'user'];
    }
    public function getVals($model){
	return ['id_customer', 'first_name', 'last_name', 'registration_date', 'email', ['attribute'=>'status', 'value'=>  Settings::getStatus($model->status)], ['attribute'=>'user', 'value'=>  Users::getNames($model->user)]];
    }
    public function getList($model){
	return ['id_customer', 'first_name', 'last_name', 'registration_date', 'email', ['attribute'=>'status', 'value'=> function($model){return Settings::getStatus($model->status);}], ['attribute'=>'user', 'value'=> function($model){return Users::getNames($model->user);} ]];
    }
}
