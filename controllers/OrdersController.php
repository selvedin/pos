<?php

namespace app\controllers;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class OrdersController extends AdminController
{
    /**
     * @inheritdoc
     */
    
  public function actionDelete($id)
    {
        $this->findModel($id)->delete();
	\Yii::$app->db->createCommand()->delete('orderitems', ['id_order' => $id])->execute();
        return $this->redirect(['index']);
    }

     
}
