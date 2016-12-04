<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $mobile
 * @property string $username
 * @property string $password
 * @property string $authKey
 * @property string $accessToken
 * @property integer $status
 *
 * @property Customers[] $customers
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    private $oldpassword;
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['status'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 256],
            [['email'], 'string', 'max' => 512],
	    ['email', 'email'],
	    [['roles'], 'safe'],
            [['mobile', 'username', 'authKey', 'accessToken'], 'string', 'max' => 128],
            [['password'], 'string', 'max' => 1000],
	    [['created_on', 'updated_on'], 'string', 'max' => 20],
	    [['created_on', 'created_by', 'updated_on', 'updated_by'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'first_name' => __('First Name'),
            'last_name' => __('Last Name'),
            'email' => __('Email'),
            'mobile' => __('Mobile'),
            'username' => __('Username'),
            'password' => __('Password'),
            'authKey' => __('Auth Key'),
            'accessToken' => __('Access Token'),
            'status' => __('Status'),
            'roles' => __('Roles'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customers::className(), ['user' => 'id']);
    }
    
           public static function getNames($id=0)
    {
	$users=[];
	$cache = \Yii::$app->cache;
	$data = $cache->get("pos_users");
	if ($data===false) {
	    $all = Users::find()->all();
	    foreach ($all as $a) {
		$users[$a->id]=$a->first_name . " " . $a->last_name;
	    }
	    $cache->set("pos_users", $users, 15*60);
	}
	else{
	    $users=$data;
	}
	if($id>0){
	    if(!isset($users[$id]))
	    {
		$cache->delete('pos_users');
		$all = Users::find()->all();
		foreach ($all as $a) {
		    $users[$a->id]=$a->first_name . " " . $a->last_name;
		}
		$cache->set("pos_users", $users, 15*60);
	    }
	    else
		return $users[$id];
	}
	else
	    return $users;
    }
    
    
    public function getFields(){
	$flds = [
		    [
			['fld'=>'first_name', 'type'=>'text','max'=>256],
			['fld'=>'last_name', 'type'=>'text','max'=>256],
			['fld'=>'username', 'type'=>'text','max'=>128],
			['fld'=>'password', 'type'=>'pass','max'=>1000],
		    ],
		    [
			['fld'=>'mobile', 'type'=>'text','max'=>20],
			['fld'=>'email', 'type'=>'text','max'=>512],
			['fld'=>'status', 'type'=>'drop', 'max'=>0, 'vals'=>  Settings::getStatus()],
			['fld'=>'roles', 'type'=>'drop', 'max'=>0, 'multi'=>true,'vals'=>  [0=>__("Select Role")]+Roles::getRoles()],
		    ]
		];
	return $flds;
    }

    public function getAtt(){
	return ['id', 'first_name', 'last_name', 'username', 'password','email', 'mobile','status'];
    }
    public function getVals($model){
	return ['id', 'first_name', 'last_name',  'username', 'password','email', 'mobile',
		    ['attribute'=>'status', 'value'=>  Settings::getStatus($model->status)],
		    ['attribute'=>'roles', 'value'=>  Roles::getRoles($model->roles)]
		];
    }
    public function getList($model){
	return ['id', 'first_name', 'last_name',  'username','email', 'mobile',
		    ['attribute'=>'status', 'value'=> function($model){return Settings::getStatus($model->status);}],
		    ['attribute'=>'roles', 'value'=> function($model){return $model->roles!=null?Roles::getRoles($model->roles):"";}]
		];
    }
    
    public function beforeSave($insert)
    {
	if (parent::beforeSave($insert)) {
	    if($this->password !=""){
		$this->password = md5($this->password);
	    }
	    else
		$this->password = $this->oldpassword;
	    $this->roles = serialize($this->roles);
	    return true;
	} else {
	    return false;
	}
    }
    public function afterFind()
    {
        $action = Yii::$app->controller->action->id;
	parent::afterFind();
	    if($action=="update"){
		$this->oldpassword = $this->password;
		$this->password="";
	    }
	    $this->roles = unserialize($this->roles);

    }

}
