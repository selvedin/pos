<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "outcomeitems".
 *
 * @property string $id_item
 * @property string $id_outcome
 * @property string $id_product
 * @property string $qnt
 * @property string $price
 * @property string $discount
 *
 * @property Outcomes $idOutcome
 * @property Products $idProduct
 */
class Outcomeitems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'outcomeitems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_outcome', 'id_product'], 'required'],
            [['id_outcome', 'id_product'], 'integer'],
            [['qnt', 'price', 'discount'], 'number'],
            [['id_outcome'], 'exist', 'skipOnError' => true, 'targetClass' => Outcomes::className(), 'targetAttribute' => ['id_outcome' => 'id_outcome']],
            [['id_product'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['id_product' => 'id_product']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_item' => 'Id Item',
            'id_outcome' => 'Id Outcome',
            'id_product' => 'Id Product',
            'qnt' => 'Qnt',
            'price' => 'Price',
            'discount' => 'Discount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdOutcome()
    {
        return $this->hasOne(Outcomes::className(), ['id_outcome' => 'id_outcome']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProduct()
    {
        return $this->hasOne(Products::className(), ['id_product' => 'id_product']);
    }
}
