<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "incomeitems".
 *
 * @property string $id_item
 * @property string $id_income
 * @property string $id_product
 * @property string $qnt
 * @property string $price
 *
 * @property Incomes $idIncome
 * @property Products $idProduct
 */
class Incomeitems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'incomeitems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_income', 'id_product'], 'required'],
            [['id_income', 'id_product'], 'integer'],
            [['qnt', 'price', 'price2'], 'number'],
            [['id_income'], 'exist', 'skipOnError' => true, 'targetClass' => Incomes::className(), 'targetAttribute' => ['id_income' => 'id_income']],
            [['id_product'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['id_product' => 'id_product']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_item' => '#',
            'id_income' => __('Income'),
            'id_product' => __('Product'),
            'qnt' => __('Qnt'),
            'price' => __('Buy Price'),
            'price' => __('Sell Price'),
        ];
    }

     public function getAtt(){
	return ['id_income', 'id_product', 'qnt', 'invoice_amount', 'price', 'price2'];
    }
    public function getVals($model){
	return ['id_income', 'id_product', 'qnt', 'invoice_amount', 'price', 'price2'];
    }
    public function getList($model){
	return ['id_income', 'id_product', 'qnt', 'invoice_amount', 'price', 'price2'];
    }
    
     public function getFields(){
	$flds = [
		    [
			['fld'=>'id_product', 'type'=>'drop','vals'=>Products::getNames()],
			['fld'=>'qnt', 'type'=>'text','max'=>0],
			['fld'=>'price', 'type'=>'text', 'max'=>0],
			['fld'=>'price2', 'type'=>'text', 'max'=>0],
		    ]
	    ];
	return $flds;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdIncome()
    {
        return $this->hasOne(Incomes::className(), ['id_income' => 'id_income']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProduct()
    {
        return $this->hasOne(Products::className(), ['id_product' => 'id_product']);
    }
}
