<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orderitems".
 *
 * @property string $id_item
 * @property string $id_order
 * @property string $id_product
 * @property string $qnt
 *
 * @property Orders $idOrder
 * @property Products $idProduct
 */
class Orderitems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orderitems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_order', 'id_product'], 'required'],
            [['id_order', 'id_product'], 'integer'],
            [['qnt'], 'number'],
            [['id_order'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['id_order' => 'id_order']],
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
            'id_order' => 'Id Order',
            'id_product' => 'Id Product',
            'qnt' => 'Qnt',
        ];
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
    public function getIdProduct()
    {
        return $this->hasOne(Products::className(), ['id_product' => 'id_product']);
    }
}
