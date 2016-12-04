<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\Settings;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class AdminController extends Controller
{
    /**
     * @inheritdoc
     */
    
    private $c;
    private $s;
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex()
    {
	$c=  ucfirst(Yii::$app->controller->id);
	$cm= "app\models\\$c";
	$sm= "app\models\search\\$c"."Search";
	$model = new $cm();
        $searchModel = new $sm();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('/admin/index', ['searchModel' => $searchModel,'dataProvider' => $dataProvider,"model"=>$model]);
    }

    /**
     * Displays a single Categories model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('/admin/view', ['model' => $this->findModel($id),]);
    }
    public function actionBarcode($id)
    {
        return $this->render('/admin/barcode', ['model' => $this->findModel($id),]);
    }

    /**
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
	$c=  ucfirst(Yii::$app->controller->id);
	$cm= "app\models\\$c";
	$model = new $cm();
	$table=$model->tableName();
	if($c=="Customers")
	    $model->registration_date = date("Y-m-d");
	if($c=="Outcomes")
	    $model->created_date = date("Y-m-d");
	else if($c=="Settings" && isset($_GET['type']))
	    $model->type=$_GET['type'];
	else if($c=="Incomes"){
	    $model->wat=Settings::find()->where("n='PDV'")->one()->a1;
	    $model->create_date = date("Y-m-d");
	    $i=1;
	    $ni = Yii::$app->db->createCommand("select max(id_income) as max from incomes")->queryScalar();
	    if(isset($ni))
		$i=$ni++;
	    $model->num_invoice = date("m")."/$i";
	}
	$k = $model->tableSchema->primaryKey[0];
	  if ($model->load(Yii::$app->request->post())){
		if($model->save()) {
		    if(isset($model->image)){
			$this->Upload($model);
		    }
		    return $this->redirect(['view', 'id' => $model->$k]);
		}
		else{
		    return $this->render('/admin/form', ['model' => $model,]);
		}
	    } else {
		return $this->render('/admin/form', ['model' => $model]);
	    }
    }

    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
	$table=$model->tableName();
	$k = $model->tableSchema->primaryKey[0];
        if ($model->load(Yii::$app->request->post())){
	    if(isset($model->image)){
		$this->Upload($model);
	    }
	    if($model->save()) {
		return $this->redirect(['view', 'id' => $model->$k]);
	    }
	    else{
		return $this->render('/admin/form', ['model' => $model,]);
	    }
        } else {
            return $this->render('/admin/form', ['model' => $model,]);
        }
    }

    
    public function actionClone($id)
    {
	$model = $this->findModel($id);
	$c=  ucfirst(Yii::$app->controller->id);
	$k = $model->tableSchema->primaryKey[0];
	$cm= "app\models\\$c";
	
	$new= new $cm();
	$new->attributes = $model->attributes;
	$new->$k=null;
	$new->save();
	return $this->redirect(['update', 'id' => $new->$k]);
    }
    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
	$c=  ucfirst(Yii::$app->controller->id);
	$cm= "app\models\\$c";
	$model = new $cm();
        if (($model = $cm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    private function makeThumb($updir, $img, $id)
	{
	    $thumbnail_width = 200;

	    $thumb_beforeword = "thumb";
	    $arr_image_details = getimagesize("$updir" . "$img"); // pass id to thumb name
	    $original_width = $arr_image_details[0];
	    $original_height = $arr_image_details[1];

		$new_width = $thumbnail_width;
		$new_height = intval($original_height * $new_width / $original_width);

	    if ($arr_image_details[2] == IMAGETYPE_GIF) {
		$imgt = "ImageGIF";
		$imgcreatefrom = "ImageCreateFromGIF";
	    }
	    if ($arr_image_details[2] == IMAGETYPE_JPEG) {
		$imgt = "ImageJPEG";
		$imgcreatefrom = "ImageCreateFromJPEG";
	    }
	    if ($arr_image_details[2] == IMAGETYPE_PNG) {
		$imgt = "ImagePNG";
		$imgcreatefrom = "ImageCreateFromPNG";
	    }
	    if ($imgt) {
		$old_image = $imgcreatefrom("$updir". "$img");
		$new_image = imagecreatetruecolor($new_width, $new_height);
		imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
		$imgt($new_image, "$updir/thumbs/" . "$img");
	    }
	}
	private function Upload($model)
	{
		
		$table=$model->tableName();
		$name = ucfirst($model->tableName());
		$k = $model->tableSchema->primaryKey[0];
		$path=Yii::$app->basePath .'/uploads/'.$table;
		if (!file_exists($path)) {
		    mkdir($path, 0777, true);
		    mkdir($path."/thumbs", 0777, true);
		}
		$model->image = $_POST[$name]['image'];
		$file=UploadedFile::getInstance($model, 'image');
		if(isset($file)){
		    
		    $file->saveAs($path."/".$table.'_' . $model->$k . '.' . $file->extension);
		    $this->makeThumb($path."/", $table."_".$model->$k .".". $file->extension,"");
		    if(file_exists(Yii::$app->basePath ."/uploads/temp/file.jpg"))
			    unlink(Yii::$app->basePath ."/uploads/temp/file.jpg");
		}
		else{
		    if(file_exists(Yii::$app->basePath ."/uploads/temp/file.jpg")){
			copy(Yii::$app->basePath ."/uploads/temp/file.jpg", $path."/".$table.'_' . $model->$k . '.jpg');
			$this->makeThumb($path."/", $table.'_' . $model->$k . '.jpg', "");
			unlink(Yii::$app->basePath ."/uploads/temp/file.jpg");
		    }
		}
	}
    
}
