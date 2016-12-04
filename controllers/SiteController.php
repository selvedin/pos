<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Perms;
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
	if(Perms::getPerms('POS',1))
	    return $this->render('index_pos');
	else
	    return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

	 /**
     * Register action.
     *
     * @return string
     */
	
    public function actionRegister()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post())) {
            $u = \app\models\Users::find()->where("username='".$model->username."'")->one();
            if(isset($u)){
                Yii::$app->session->setFlash('error', 'User is already registered.');
                return $this->refresh();
            }
            else{
                $u = New \app\models\Users();
                $u->username = $model->username;
                $u->pass = md5($model->password);
                $u->first_name=$model->firstname;
                $u->last_name = $model->lastname;
                $u->email = $model->email;
                if($u->save()){
                    $model->login();
                    Yii::$app->session->setFlash('success', 'User is registered.');
                    return $this->goHome();
                }
                else{
                    Yii::$app->session->setFlash('error', 'Error appeared. User is not registered.');
                    return $this->refresh();
                }
            }
        } else {
            return $this->render('register', [
                'model' => $model,
            ]);
        }
    }

	
	
    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    public function actionTest()
    {
        return $this->render('test');
    }
    
    public function actionSettings()
    {
	return $this->render("../settings/index");
    }
    
    public function actionSetsess($id,$i,$pr,$qnt, $price, $total)
    {
	if($i!="null")
	    $_SESSION['sale'][$id]['items'][$i]=['product'=>$pr, 'qnt'=>$qnt, 'price'=>$price, 'total'=>$total];
	else
	    $_SESSION['sale'][$id]['final']=['qnt'=>$qnt, 'price'=>$price, 'total'=>$total];
    }
}
