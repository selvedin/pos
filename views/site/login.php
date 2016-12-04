<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = __('Login');
?>
<style>
    div.login{
	width:50%;
	margin-left: 25%;
    }
    .btn-login{
	width: 98%;
	height: 50px;
	margin:10px;
	border-radius: 0;
    }
</style>
<div class="div-form login">
    <h1 class="title"><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username',['template' => "<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>"])->textInput(['placeholder'=>__("Username")])->label(false) ?>

    <?= $form->field($model, 'password',['template' => "<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>"])->passwordInput(['placeholder'=>__("Password")])->label(false) ?>

    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <div class="form-group">
        <div>
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-login', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
