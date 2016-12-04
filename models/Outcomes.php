<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "outcomes".
 *
 * @property string $id_outcome
 * @property string $invoice_num
 * @property string $created_date
 * @property string $id_user
 * @property integer $executed
 * @property string $noWatAmount
 * @property string $includedWatAmount
 * @property string $id_order
 * @property string $id_stock
 *
 * @property Outcomeitems[] $outcomeitems
 * @property Orders $idOrder
 * @property Stocks $idStock
 * @property Users $idUser
 */
class Outcomes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'outcomes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'executed', 'id_order', 'id_stock'], 'integer'],
            [['noWatAmount', 'includedWatAmount'], 'number'],
            [['invoice_num'], 'string', 'max' => 128],
            [['created_date'], 'string', 'max' => 20,],
            [['id_order'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['id_order' => 'id_order']],
            [['id_stock'], 'exist', 'skipOnError' => true, 'targetClass' => Stocks::className(), 'targetAttribute' => ['id_stock' => 'id_stock']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_outcome' => 'Id Outcome',
            'invoice_num' => 'Invoice Num',
            'created_date' => 'Created Date',
            'executed' => 'Executed',
            'noWatAmount' => 'No Wat Amount',
            'includedWatAmount' => 'Included Wat Amount',
	    'id_user' => 'Id User',
            'id_order' => 'Id Order',
            'id_stock' => 'Id Stock',
        ];
    }

     public function getAtt(){
	return ['id_outcome', 'invoice_num', 'created_date', 'noWatAmount', 'includedWatAmount', 'id_stock', 'id_order' ];
    }
    public function getVals($model){
	return ['id_outcome', 'invoice_num', 'created_date', 'noWatAmount', 'includedWatAmount',
		['attribute'=>'id_stock', 'value'=> $model->id_stock!=null?Stocks::getNames($model->id_stock):""],			
		['attribute'=>'id_user', 'value'=> $model->id_user!=null?Users::getNames($model->id_user):""],		
	    ];
    }
    public function getList($model){
	return ['id_outcome', 'invoice_num', 'created_date', 'noWatAmount', 'includedWatAmount', 'id_stock', 'id_order' ];
    }
    
     public function getFields(){
	$flds = [
		    [
			['fld'=>'invoice_num', 'type'=>'text','max'=>0],],
			[['fld'=>'noWatAmount', 'type'=>'text', 'max'=>0],],
			[['fld'=>'includedWatAmount', 'type'=>'text', 'max'=>0],],
			[['fld'=>'created_date', 'type'=>'date','max'=>20],],
			[['fld'=>'id_order', 'type'=>'drop','vals'=>Orders::getNames()],],
			[['fld'=>'id_stock', 'type'=>'drop','vals'=>Stocks::getNames()],],
			[['fld'=>'executed', 'type'=>'check','yes'=>true],]
	    ];
	return $flds;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOutcomeitems()
    {
        return $this->hasMany(Outcomeitems::className(), ['id_outcome' => 'id_outcome']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdOrder()
    {
        return $this->hasOne(Orders::className(), ['id_order' => 'id_order']);
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
    public function getIdUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'id_user']);
    }
    
    public function beforeSave($insert)
    {
	if (parent::beforeSave($insert)) {
	    $this->id_user = Yii::$app->user->identity->id;
	    return true;
	} else {
	    return false;
	}
    }
    
      public function afterSave($insert, $changedAttributes)
    {
	parent::afterSave($insert, $changedAttributes);

	    if(isset($_POST['outcomes']['items']))
		foreach ($_POST['outcomes']['items'] as $key => $value) {
		if($this->isNewRecord){
		    $ii = new Outcomeitems();
		    $ii->id_outcome = $this->id_outcome;
		    $ii->id_product = $value['id_product'];
		    $ii->qnt = $value['qnt'];
		    $ii->price = $value['final'];
		    $ii->discount = $value['discount'];
		    $ii->save();
		}
		else{
		    $ii = Outcomeitems::findOne($value['id_item']);
		    if(isset($ii)){
			$ii->qnt = $value['qnt'];
			$ii->price = $value['final'];
			$ii->discount = $value['discount'];
			$ii->save();
		    }
		    else{
			$ii = new Outcomeitems();
			$ii->id_outcome = $this->id_outcome;
			$ii->id_product = $value['id_product'];
			$ii->qnt = $value['qnt'];
			$ii->price = $value['final'];
			$ii->discount = $value['discount'];
			$ii->save();
		    }
		}
	    }
    }
}
