<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "orders".
 *
 * @property string $id_order
 * @property string $order_number
 * @property string $id_customer
 * @property string $created_date
 * @property integer $status
 * @property integer $canceled
 *
 * @property Orderitems[] $orderitems
 * @property Customers $idCustomer
 * @property Outcomes[] $outcomes
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_customer', 'status', 'canceled'], 'integer'],
            [['order_number'], 'string', 'max' => 128],
            [['created_date'], 'string', 'max' => 20],
            [['id_customer'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['id_customer' => 'id_customer']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_order' => '#',
            'order_number' => __('Order Number'),
            'id_customer' => __('Customer'),
            'created_date' => __('Created On'),
            'status' => __('Status'),
            'canceled' => __('Canceled'),
        ];
    }

    public static function getNames($id=0)
    {
	$orders = ArrayHelper::map(Orders::find()->all(), "id_order", "order_number");
	if($id==0)
	    return $orders;
	else
	    return $orders[$id];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderitems()
    {
        return $this->hasMany(Orderitems::className(), ['id_order' => 'id_order']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCustomer()
    {
        return $this->hasOne(Customers::className(), ['id_customer' => 'id_customer']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOutcomes()
    {
        return $this->hasMany(Outcomes::className(), ['id_order' => 'id_order']);
    }
    
    public static function getStatus($id=-1)
    {
	$status = [0=>__("Active"), 1=>__("Inactive")];
	if($id==-1)
	    return $status;
	else
	    return $status[$id];
    }
    
    public static function getCanceled($id=-1)
    {
	$status = [0=>__("No"), 1=>__("Yes")];
	if($id==-1)
	    return $status;
	else
	    return $status[$id];
    }
    
    public function getAtt(){
	return ['id_order', 'order_number', 'created_date', 'status', 'canceled','id_customer'];
    }
    public function getVals($model){
	return ['id_order', 'order_number', 'created_date', 'status', 'canceled',
		['attribute'=>'status', 'value'=> Orders::getStatus($model->status)],		
		['attribute'=>'canceled', 'value'=> Orders::getCanceled($model->canceled)],		
		['attribute'=>'id_customer', 'value'=> Customers::getNames($model->id_customer)],		
	    ];
    }
    public function getList($model){
	return ['id_order', 'order_number', 'created_date',
		['attribute'=>'status', 'value'=>function($model){return Orders::getStatus($model->status);}],				
		['attribute'=>'canceled', 'value'=>function($model){return Orders::getCanceled($model->canceled);}],				
		['attribute'=>'id_customer', 'value'=>function($model){return Customers::getNames($model->id_customer);}],		
	    ];
    }
    
     public function getFields(){
	$flds = [
		    [
			['fld'=>'order_number', 'type'=>'text','max'=>100],],
			[['fld'=>'created_date', 'type'=>'date','max'=>20],],
			[['fld'=>'id_customer', 'type'=>'drop','vals'=>[0=>__("Select Customer")]+Customers::getNames()],],
			[['fld'=>'status', 'type'=>'check','yes'=>true],],
			[['fld'=>'canceled', 'type'=>'check','yes'=>true],],
		    
	    ];
	return $flds;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdStock()
    {
        return $this->hasOne(Stocks::className(), ['id_stock' => 'id_stock']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSupplier()
    {
        return $this->hasOne(Suppliers::className(), ['id_supplier' => 'id_supplier']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'id_user']);
    }
    
    public function beforeSave($insert)
    {
	if (parent::beforeSave($insert)) {
	    //your code goes here
	    return true;
	} else {
	    return false;
	}
    }
    
   public function afterSave($insert, $changedAttributes)
    {
	parent::afterSave($insert, $changedAttributes);

	    if(isset($_POST['orders']['items'])){
		foreach ($_POST['orders']['items'] as $key => $value) {
		    if($this->isNewRecord){
			$ii = new Orderitems();
			$ii->id_order = $this->id_order;
			$ii->id_product = $value['id_product'];
			$ii->qnt = $value['qnt'];
			$ii->save();
		    }
		    else{
			$ii = Orderitems::findOne($value['id_item']);
			if(isset($ii)){
			    $ii->qnt = $value['qnt'];
			    $ii->save();
			}
			else{
			    $ii = new Orderitems();
			    $ii->id_order = $this->id_order;
			    $ii->id_product = $value['id_product'];
			    $ii->qnt = $value['qnt'];
			    $ii->save();
			}
		    }
		}
	    }
    }
}
