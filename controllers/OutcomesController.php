<?php

namespace app\controllers;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class OutcomesController extends AdminController
{
    /**
     * @inheritdoc
     */
    
  public function actionDelete($id)
    {
        $this->findModel($id)->delete();
	\Yii::$app->db->createCommand()->delete('outcomeitems', ['id_outcome' => $id])->execute();
        return $this->redirect(['index']);
    }
    
    public function actionPos($id){
	$outcome=new \app\models\Outcomes();
	if(isset($_SESSION['sale'][$id])){
	    //$outcome = new \app\models\Outcomes();
	    $outcome->created_date=date("Y-m-d");
	    $outcome->invoice_num = date("m")."/" . count(\app\models\Outcomes::find()->all());
	    $outcome->noWatAmount = $_SESSION['sale'][$id]['final']['price'];
	    $outcome->includedWatAmount = $_SESSION['sale'][$id]['final']['total'];
	    $outcome->executed=1;
	    $outcome->save();
	    foreach ($_SESSION['sale'][$id]['items'] as $key=>$value) {
		    $sale = new \app\models\Outcomeitems();
		    $sale->id_outcome = $outcome->id_outcome;
		    $sale->id_product = $value['product'];
		    $sale->qnt = $value['qnt'];
		    $sale->price = $value['total'];
		    $sale->save();
	    }
	}
	unset($_SESSION['sale'][$id]);
	return $this->redirect(['print', 'id'=>$outcome->id_outcome]);
    }
    
    public function actionPrint($id)
    {
	$model = $this->findModel($id);
	return $this->render('/special/prints/print', ['model'=>$model]);
    }

     
}
