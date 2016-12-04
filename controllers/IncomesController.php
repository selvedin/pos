<?php

namespace app\controllers;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class IncomesController extends AdminController
{
    /**
     * @inheritdoc
     */
    
  public function actionDelete($id)
    {
        $this->findModel($id)->delete();
	\Yii::$app->db->createCommand()->delete('incomeitems', ['id_income' => $id])->execute();
        return $this->redirect(['index']);
    }

     
}
