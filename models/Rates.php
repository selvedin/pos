<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rates".
 *
 * @property string $id_rate
 * @property string $id_product
 * @property string $id_customer
 * @property string $created_date
 * @property string $rate
 *
 * @property Customers $idCustomer
 * @property Products $idProduct
 */
class Rates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_product', 'id_customer'], 'required'],
            [['id_product', 'id_customer'], 'integer'],
            [['rate'], 'number'],
            [['created_date'], 'string', 'max' => 20],
            [['id_customer'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::className(), 'targetAttribute' => ['id_customer' => 'id_customer']],
            [['id_product'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['id_product' => 'id_product']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_rate' => '#',
            'id_product' => 'Product',
            'id_customer' => 'Customer',
            'created_date' => 'Created Date',
            'rate' => 'Rate',
        ];
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
    public function getIdProduct()
    {
        return $this->hasOne(Products::className(), ['id_product' => 'id_product']);
    }
    
    public function getFields(){
	$flds = [[['fld'=>'id_product', 'type'=>'drop','vals'=>  Products::getNames()],['fld'=>'id_customer', 'type'=>'drop','vals'=>  Customers::getNames()],['fld'=>'rate', 'type'=>'text','max'=>0]],];
	return $flds;
    }
    
    public function getAtt(){
	return ['id_rate', 'id_product', 'id_customer', 'rate'];
    }
}
