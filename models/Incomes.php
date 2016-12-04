<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "incomes".
 *
 * @property string $id_income
 * @property string $num_invoice
 * @property string $create_date
 * @property string $invoice_amount
 * @property string $wat
 * @property string $description
 * @property string $id_stock
 * @property string $id_user
 * @property string $id_supplier
 *
 * @property Stocks $idStock
 * @property Suppliers $idSupplier
 * @property Users $idUser
 */
class Incomes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const SCENARIO_UPDATE= 'update';
    
    private $items;
    public static function tableName()
    {
        return 'incomes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num_invoice'], 'required'],
            [['id_income', 'id_stock', 'id_user', 'id_supplier'], 'integer'],
            [['invoice_amount', 'wat'], 'number'],
            [['num_invoice'], 'string', 'max' => 100],
            [['create_date'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 1000],
            [['id_stock'], 'exist', 'skipOnError' => true, 'targetClass' => Stocks::className(), 'targetAttribute' => ['id_stock' => 'id_stock']],
            [['id_supplier'], 'exist', 'skipOnError' => true, 'targetClass' => Suppliers::className(), 'targetAttribute' => ['id_supplier' => 'id_supplier']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['id_user' => 'id']],
	    [['items'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_income' => '#',
            'num_invoice' => __('Invoice Number'),
            'create_date' => __('Create Date'),
            'invoice_amount' => __('Invoice Amount'),
            'wat' => __('Wat'),
            'description' => __('Description'),
            'id_stock' => __('Stock'),
            'id_user' => __('Created By'),
            'id_supplier' => __('Supplier'),
            'items' => __('Items'),
        ];
    }

    public function getAtt(){
	return ['id_income', 'num_invoice', 'create_date', 'invoice_amount', 'wat', 'id_stock', 'id_user', 'id_supplier'];
    }
    public function getVals($model){
	return ['id_income', 'num_invoice', 'create_date', 'invoice_amount', 'wat',
		['attribute'=>'id_stock', 'value'=> Stocks::getNames($model->id_stock)],		
		['attribute'=>'id_supplier', 'value'=> Suppliers::getNames($model->id_supplier)],		
		['attribute'=>'id_user', 'value'=> Users::getNames($model->id_user)],		
	    ];
    }
    public function getList($model){
	return ['id_income', 'num_invoice', 'create_date', 'invoice_amount', 'wat', 'id_stock', 'id_user', 'id_supplier'];
    }
    
     public function getFields(){
	$flds = [
		    [
			['fld'=>'num_invoice', 'type'=>'text','max'=>100],],
			[['fld'=>'invoice_amount', 'type'=>'text', 'max'=>0],],
			[['fld'=>'create_date', 'type'=>'date','max'=>20],],
			[['fld'=>'wat', 'type'=>'text','max'=>11, 'readonly'=>true],],
			[['fld'=>'id_stock', 'type'=>'drop','vals'=>Stocks::getNames()],],
			[['fld'=>'id_supplier', 'type'=>'drop','vals'=>Suppliers::getNames()],
		    ]
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
	    $this->id_user = Yii::$app->user->identity->id;
	    return true;
	} else {
	    return false;
	}
    }
    
   public function afterSave($insert, $changedAttributes)
    {
	parent::afterSave($insert, $changedAttributes);

	    if(isset($_POST['incomes']['items']))
		foreach ($_POST['incomes']['items'] as $key => $value) {
		if($this->isNewRecord){
		    $ii = new Incomeitems();
		    $ii->id_income = $this->id_income;
		    $ii->id_product = $value['id_product'];
		    $ii->qnt = $value['qnt'];
		    $ii->price = $value['final'];
		    $ii->save();
		}
		else{
		    $ii = Incomeitems::findOne($value['id_item']);
		    if(isset($ii)){
			$ii->qnt = $value['qnt'];
			$ii->price = $value['final'];
			$ii->save();
		    }
		    else{
			$ii = new Incomeitems();
			$ii->id_income = $this->id_income;
			$ii->id_product = $value['id_product'];
			$ii->qnt = $value['qnt'];
			$ii->price = $value['final'];
			$ii->save();
		    }
		}
	    }
    }
    
}
