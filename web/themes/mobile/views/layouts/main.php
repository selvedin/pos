<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Perms;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico" />
   
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
	div.toolbar div.third{display:none;}
	div.toolbar div.first,div.toolbar div.second{width: 49%;}
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php if(!isset($_GET['dlg']) && !in_array(Yii::$app->controller->action->id, ["barcode", "print"])){
    NavBar::begin([
        'brandLabel' => 'POS::SEL',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
	'encodeLabels' => false,
        'items' => [
            //['label' => __('About'), 'url' => ['/site/about']],
            //['label' => __('Contact'), 'url' => ['/site/contact']],
           
            ['label' =>'<span class="glyphicon glyphicon-cog"></span>'." " . __('Settings'), 'url' => "#", 'visible'=>Perms::getPerms('Settings',1),
		'items'=>[
			    ['label' =>'<span class="fa fa-smile-o"></span>'." " . __('Customers'), 'url' => ['/customers/index'], 'visible'=>Perms::getPerms('Customers',1)],
			    ['label' =>'<span class="fa fa-users"></span>'." " . __('Users'), 'url' => ['/users/index'], 'visible'=>Perms::getPerms('Users',1)],
			    ['label' =>'<span class="fa fa-universal-access"></span>'." " . __('Roles'), 'url' => ['/roles/index'], 'visible'=>Perms::getPerms('Roles',1)],
			    ['label' =>'<span class="fa fa-user-secret"></span>'." " . __('Permissions'), 'url' => ['/perms/index'], 'visible'=>Perms::getPerms('Perms',1)],
			    ['label'=>'', 'url'=>'#', 'options'=>['class'=>'divider-vertical']],
			    ['label' =>'<span class="fa fa-archive"></span>'." " . __('Stocks'), 'url' => ['/stocks/index'], 'visible'=>Perms::getPerms('Stocks',1)],
			    ['label' =>'<span class="glyphicon glyphicon-list-alt"></span>'." " . __('Categories'), 'url' => ['/categories/index'], 'visible'=>Perms::getPerms('Categories',1)],
			    ['label' =>'<span class="fa fa-truck"></span>'." " . __('Suppliers'), 'url' => ['/suppliers/index'], 'visible'=>Perms::getPerms('Suppliers',1)],
			    ['label' =>'<span class="glyphicon glyphicon-scale"></span>'." " . __('Units'), 'url' => ['/units/index'], 'visible'=>Perms::getPerms('Units',1)],
			    ['label'=>'', 'url'=>'#', 'options'=>['class'=>'divider-vertical']],
			    ['label' =>'<span class="glyphicon glyphicon-star"></span>'." " . __('Rates'), 'url' => ['/rates/index'], 'visible'=>Perms::getPerms('Rates',1)],
			    ['label' =>'<span class="glyphicon glyphicon-cog"></span>'." " . __('Other Settings'), 'url' => ['/settings/index'], 'visible'=>Perms::getPerms('Settings',1)],
			    
		    ]
	    ],
            ['label' =>'<span class="fa fa-money"></span>'." " . __('Sales'), 'url' => "#", 'visible'=>Perms::getPerms('Sales',1),
		'items'=>[
			    ['label' =>'<span class="fa fa-tags"></span>'." " . __('Products'), 'url' => ['/products/index'], 'visible'=>Perms::getPerms('Products',1)],
			    ['label' =>'<span class="fa fa-share"></span>'." " . __('Incomes'), 'url' => ['/incomes/index'], 'visible'=>Perms::getPerms('Incomes',1)],
			    ['label' =>'<span class="fa fa-file-text-o"></span>'." " . __('Orders'), 'url' => ['/orders/index'], 'visible'=>Perms::getPerms('Orders',1)],
			    ['label' =>'<span class="fa fa-reply"></span>'." " . __('Output'), 'url' => ['/outcomes/index'], 'visible'=>Perms::getPerms('Outcomes',1)],
			    
		    ]
	    ],
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
	'encodeLabels' => false,
        'items' => [
	    [
		'label' =>'<span class="fa fa-language"></span>'." " . __('Languages'), 'url' => "#", 'visible'=>!Yii::$app->user->isGuest,
		'items'=>[
			    ['label' =>'<span class="flag-icon flag-icon-gb"></span>'." " . __('English'), 'url' => '?lang=en', 'visible'=>true],
			    ['label' =>'<span class="flag-icon flag-icon-ba"></span>'." " . __('Bosnian'), 'url' => '?lang=bs', 'visible'=>true],
			    ['label' =>'<span class="flag-icon flag-icon-sa"></span>'." " . __('Arabic'), 'url' => '?lang=ar', 'visible'=>true],
			    
		    ]
		
	    ],
            Yii::$app->user->isGuest ? (
                ['label' => '<span class="glyphicon glyphicon-log-in"></span>', 'url' => ['/site/login'],]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    '(' . app\models\Users::getNames(Yii::$app->user->identity->id) . ') <span class="glyphicon glyphicon-log-out"></span>',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    }
    if(!isset($_GET['dlg'])&& !in_array(Yii::$app->controller->action->id, ["barcode", "print"])):
    ?>
  <div class="toolbar" <?= isset($_GET['dlg'])?"style='margin-top:0px;'":""; ?>>
	<div class="first block"></div>
	<div class="second block"></div>
	<div class="third block"></div>
    </div>
    <?php
	endif;
    ?>
    <div class="container">
    
        <?php Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
    <div class="div-wait"></div>
    <div id="numcontainer">
    <input class="input-number" readonly="true" type="text" placeholder="1234567890"/>
    <span class="fa fa-check fa-2x"></span>
    <ul class="numpad">
      <li rel="1">1</li>
      <li rel="2">2</li>
      <li rel="3">3</li>
      <li rel="4">4</li>
      <li rel="5">5</li>
      <li rel="6">6</li>
      <li rel="7">7</li>
      <li rel="8">8</li>
      <li rel="9">9</li>
      <li rel=".">.</li>
      <li rel="0">0</li>
      <li rel="delete"><i class="fa fa-remove"></i></li>
    </ul>
  </div>
    
    
</div>

    <?php
	if(!isset($_GET['dlg'])&& Yii::$app->controller->action->id!="barcode"):
    ?>

<?php 
    endif;
    $this->endBody() ?>
  <script>
  $( function() {
    $(".datepicker").datepicker({changeMonth: true,changeYear: true, dateFormat: 'yy-mm-dd'});
    $(".multiselect").chosen({disable_search_threshold: 10});
    
    $("#btn-save").on("click", function(){$(".btn-submit").click();return false;});
    $("#btn-delete").on("click", function(){$(".btn-delete").click();return false;});

     $("div.toolbar div.first").append($("#btn-create"));
     $("div.toolbar div.first").append($("#btn-clone"));
     $("div.toolbar div.first").append($("#btn-index"));
     $("div.toolbar div.first").append($("#btn-save"));
     $("div.toolbar div.first").append($("#btn-view"));
     $("div.toolbar div.first").append($("#btn-print"));
     $("div.toolbar div.first").append($("#btn-update"));
     $("div.toolbar div.first").append($("#btn-delete"));
     
     
  } );
  $(document).ready(function(){
      if(window.innerWidth>767)
	  $("div.row-float").css("width", 100/$("div.row-float").length + "%");
      else
	  $("div.row-float").css("width","98%");
      $("div.chosen-container").css("width","100%");
      $("ul.chosen-choices li input").css("height", "45px");
      $("div.toolbar div.second").append($("h1.title"));
      $("button.ui-dialog-titlebar-close").html("<i class='fa fa-close'></i>");
  });
  window.addEventListener("resize", resizeForm);
   function resizeForm(){
      if(window.innerWidth>767)
	  $("div.row-float").css("width", 100/$("div.row-float").length + "%");
      else
	  $("div.row-float").css("width","98%");
  }
  
   $('#numcontainer ul li').each(function(){
	$(this).click(function(){
	    if($(this).attr('rel') != "delete"){
		var num = $(this).attr('rel');
		var temp = $(".input-number").val() + num;
		$(".input-number").val(temp);
	    }
	    else{
		    var temp = $(".input-number").val();
		    temp = temp.slice(0,temp.length-1);
		    $(".input-number").val(temp);
		}
	});
    });
    
    $('#numcontainer span.fa-check').on("click", function(){if($("input.input-number").val()!=""){$("td.item-qnt input.active").val($("input.input-number").val());}showNumpad(1);$("#product-scan").val("").focus();});

    function showNumpad(i=0)
    {
	if(i==0){
	    $("td.item-qnt input").removeClass("active");
	    $("div.div-wait, div#numcontainer").show();
	    $("#numcontainer input.input-number").val("");
	}
	else{
	    $("td.item-qnt input").removeClass("active");
	    $("div.div-wait, div#numcontainer").hide();
	    calculateTotal($("form.forms.active").attr("id"));
	}
    }  
    
  </script>
</body>
</html>
<?php $this->endPage() ?>
