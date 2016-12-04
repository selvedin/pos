<?php
use yii\helpers\Html;
use app\models\Settings;
use app\models\Categories;
use app\models\Products;
use app\models\search\ProductsSearch;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
unset($_SESSION['sale']);
/* @var $this yii\web\View */
$this->title = __('POS')."-".__("SALE");
$wat =(int)@Settings::find()->where("n='PDV'")->one()->a1;
$sc=  Categories::findOne((int)@$_POST['subcat']);
$cats=  Categories::find()->where("parent=0")->all();
$cats1=array();
if(isset($_GET['cat']))
$cats1=  Categories::find()->where("parent=" .(int)$_GET['cat'])->all();
if(isset($_POST['subcat'])){
    $cats1=  Categories::find()->where("parent=" .(int)$_POST['subcat'])->all();
}
?>
<style>
    .row{text-align: center;}
   div.part.left form.forms{display:none;}
   div.part.left form.forms.active{display:block;}
</style>
<link href="css/pos.css" rel="stylesheet" type="text/css"/>
<link href="numpad/jquery.numpad.css" rel="stylesheet" type="text/css"/>
<script src="numpad/jquery.numpad.js" type="text/javascript"></script>
<div style="display: none;height: 1px;">
 <?php $form = ActiveForm::begin( [
            'options' => ['id'=>'categories-form']
        ]); ?>
    <input type="text" value="0" name="subcat" id="subcat"/>
<?php
    ActiveForm::end();
?>
</div>
<div class="pos-form">
    <div class="part left">
	<div class="btns-main">
	    <a href="#" class="btn btn-default btn-lg add-form"><i class="fa fa-plus"></i></a>
	    <a href="#" class="btn btn-default btn-lg remove-form"><i class="fa fa-minus"></i></a>
	</div>
	<div class="sepparator"></div>
	<div class="client-title"><h2><?= __("Select Client")?></h2><input type="text" placeholder="<?= __("Scan Client Card") ?>" id="client-scan"/></div>
	<div class="client-search"><input type="text" placeholder="<?= __("Scan Product Barcode") ?>" id="product-scan"/></div>
	<form method="post" id="form-0" target="_blank" data-id="0" class="forms active">
	    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
	    <div class="products-list">
		<table class="table table-responsive">
		    <thead><tr><th><?= __("Product") ?></th><th><?= __("Price") ?></th><th><?= __("Qnt") ?></th><th><?= __("Total") ?></th><th style="width: 5px;"></th></tr></thead>
		    <tbody>
			<tr><td colspan="4"><div id="table-products" class="table-container"  ondrop="drop(event)" ondragover="allowDrop(event)"><table class="table table-responsive"><tbody></tbody></table></div></td></tr>
		    </tbody>
		</table>
	    </div>
	    <div class="summary-list">
		<table class="table table-responsive">
		    <tbody>
			<tr><td><?= __("SubTotal") ?></td><td class="amount"><input class="blank-input" type="text" name="outcome[subtotal]" value="0.00"/></td><td class="items"><span class="num-of-items">0</span> - item</td></tr>
			<tr><td><?= __("OrderTax") ?></td><td class="wat"><?=$wat ?>%</td><td class="watamount"><input class="blank-input" type="text" name="outcome[watamount]" value="0.00"/></td></tr>
			<tr><td><?= __("Discount") ?></td><td class="discount">N/A</td><td class="discountamount">0.00</td></tr>
			<tr><td colspan="2"><?= __("Total") ?></td><td class="total"><input class="blank-input" type="text" name="outcome[total]" value="0.00"/></td></tr>
		    </tbody>
		</table>
	    </div>
	    <div class="payment-btns">
		<a href="#" id="btn-cancel" class="btn btn-danger btn-lg" disabled="true"><?= __("Cancel") ?></a>
		<a href="#" id="btn-pay" class="btn btn-success btn-lg" disabled="true"><?= __("Payment") ?></a>
	    </div>
	</form>
    </div>
    
    <div class="part right">
	<div class="btns-main">
	    <a href="#" data-cat="0" class="btn btn-default btn-lg active m-btn"><i class="fa fa-home"></i></a>
	    <?php
	    foreach ($cats as $c) {
		echo "<a href='#' data-cat='$c->id_category' class='btn btn-default btn-lg m-btn'>".strtoupper(__($c->name))."</a>";
	    }
	    ?>
	</div>
	<div class="sepparator"></div>
	<div class="item-search"><input type="text" placeholder="<?= __("Search") ?>"/><a href="#" class="btn"><i class="fa fa-search"></i></a></div>

	<div class="pos-bc">
	    <ul class="b-crumb">
		<li><a class="bc-btn" href="#" data-cat="0"><?= __("Home") ?></a></li>
	    </ul>
	</div>

	<div class="categories">
	    <?php
//		    $file="";
//		    foreach(array("jpg", "jpeg", "png", "PNG", "gif", "GIF") as $ext){
//			if(file_exists(Yii::$app->basePath . "/uploads/categories/thumbs/categories_$c->id_category.".$ext))
//				$file="../uploads/categories/thumbs/categories_$c->id_category.".$ext;
//		    }
		   // echo "<div class='a-btn' data-cat='$c->id_category'><img src='$file'/><a href='#' class='btn btn-lg'>".strtoupper(__($c->name))."</a></div>";
	    ?>
	    
	</div>
	<div class="products"></div>
    </div>
    <div style="clear:both;"></div>
</div>


<div class="site-index">
    <div class="body-content" style="display: none;">
	<?php
	foreach (Settings::getItems() as $value) {
	    echo Html::a('<i class="'.$value['icon'].' fa-2x"></i><br/>' .$value['title'], $value['link'],['class'=>'btn btn-default btn-item']);
	}
	?>
    </div>
</div>


<form method="post" id="form-blank" target="_blank" class="forms hidden">
	    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
	    <div class="products-list">
		<table class="table table-responsive">
		    <thead><tr><th><?= __("Product") ?></th><th><?= __("Price") ?></th><th><?= __("Qnt") ?></th><th><?= __("Total") ?></th><th style="width: 5px;"></th></tr></thead>
		    <tbody>
			<tr><td colspan="4"><div id="table-products" class="table-container"  ondrop="drop(event)" ondragover="allowDrop(event)"><table class="table table-responsive"><tbody></tbody></table></div></td></tr>
		    </tbody>
		</table>
	    </div>
	    <div class="summary-list">
		<table class="table table-responsive">
		    <tbody>
			<tr><td><?= __("SubTotal") ?></td><td class="amount"><input class="blank-input" type="text" name="outcome[subtotal]" value="0.00"/></td><td class="items"><span class="num-of-items">0</span> - item</td></tr>
			<tr><td><?= __("OrderTax") ?></td><td class="wat"><?=$wat ?>%</td><td class="watamount"><input class="blank-input" type="text" name="outcome[watamount]" value="0.00"/></td></tr>
			<tr><td><?= __("Discount") ?></td><td class="discount">N/A</td><td class="discountamount">0.00</td></tr>
			<tr><td colspan="2"><?= __("Total") ?></td><td class="total"><input class="blank-input" type="text" name="outcome[total]" value="0.00"/></td></tr>
		    </tbody>
		</table>
	    </div>
	    <div class="payment-btns">
		<a href="#" id="btn-cancel" class="btn btn-danger btn-lg" disabled="true"><?= __("Cancel") ?></a>
		<a href="#" id="btn-pay" class="btn btn-success btn-lg" disabled="true"><?= __("Payment") ?></a>
	    </div>
	</form>

<script>
    $(document).ready(function(){
	checkForms();
    });
    var wat = 100/<?= $wat?>+1;  
    $("div.toolbar").css("display", "none");
    $("div.part.right div.btns-main a.btn").on("click", function(){$("div.part.right div.btns-main a.btn").removeClass("active");$(this).addClass("active");});
    //$("div.a-btn, a.bc-btn, a.m-btn").on("click", function(){$("#subcat").val($(this).data("subcat"));alert($(this).data("cat"))});
    $(document).on("focus", "td.item-qnt input", function(){showNumpad();$(this).addClass("active")});
    $(document).on("click","a#btn-pay", function(){
	window.open('<?= Yii::$app->urlManager->createUrl(["outcomes/pos"])?>&id='+$('form.forms.active').attr("id"), "_new");
	$('form.forms.active').remove();
	$("div.part.left div.btns-main a.active").remove();
	$('form.forms:first').addClass("active");
	$('div.part.left div.btns-main a.btn-forms:last').addClass("active");
	return false;});
    $("#product-scan").on("keydown", function(e){if(e.which==13){
	    var trs = $("div.products-list div.table-container table tbody tr").length;
	$.getJSON( '<?= Yii::$app->urlManager->createUrl(["products/scanproduct"])?>&search='+$(this).val(), function( data ) {
		if(data!=null){
		      var l=Object.keys(data).length;
		      if(l>0){
			$.each( data, function( key, val ) {
			    $("form.forms.active div.products-list div.table-container table tbody").append('<tr data-id="'+ key +'" data-price="'+val['price']+'" data-name="'+val['name']+'"  data-unit="'+val['unit']+'"><td class="item-name"><input type="text" name="sale[items]['+trs+'][id]" value="'+key+'" style="display:none;"/>'+val['name']+'</td><td class="item-price"><input type="text" name="sale[items]['+trs+'][price]" value="'+val['price']+'" style="display:none;"/>'+val['price']+'</td><td class="item-qnt"><input type="text" name="sale[items]['+trs+'][qnt]" value="1" class="blank-input"/></td><td class="item-total"><input type="text" name="sale[items]['+trs+'][total]" value="'+val['price']+'"  class="blank-input"/></td><td><i class="fa fa-remove"></i></td></tr>');
			    calculateTotal($("form.forms.active").attr("id"));
			});
		      }
		  }
	      else{
	      $("#product-scan").val("");
		$("#product-scan").attr("placeholder", "<?= __("Product not found. Please, SCAN again.") ?>")
	      }
	    });
    }});
    $(document).on("click","div.a-btn, a.bc-btn, a.m-btn", function(){
    $("div.categories,ul.b-crumb").empty();
    $("div.products").empty();
	var id= $(this).data("cat");
	if(id>0){
	     $("ul.b-crumb").load('<?= Yii::$app->urlManager->createUrl(["categories/getlist"])?>&id='+id);
	    $.getJSON( '<?= Yii::$app->urlManager->createUrl(["categories/getparent"])?>&id='+id, function( data ) {
		if(data!=null){
		      var l=Object.keys(data).length;
		      if(l>0){
			$.each( data, function( key, val ) {
			    $("div.categories").append("<div class='a-btn' data-cat='"+key+"'><img src='../uploads/categories/thumbs/categories_"+key+".jpg'/><a href='#' class='btn btn-lg'>"+val+"</a></div>");
			    calculateTotal($("form.forms.active").attr("id"));
			});
		      }
		      $("div.products").load('<?= Yii::$app->urlManager->createUrl(["products/getlist"])?>&id='+id);
		  }
	      else{
		      $("div.categories, div.products").empty();
		  }
	    });
	}
	else{
	    $("ul.b-crumb").append('<li><a class="bc-btn" href="#" data-cat="0"><?= __("Home") ?></a></li>');
	}
    });

	function allowDrop(ev) {
	    ev.preventDefault();
	}

	function drag(ev) {
	    ev.dataTransfer.setData("html/text", ev.target.id);
	}

	function drop(ev) {
	    ev.preventDefault();
	    var data = ev.dataTransfer.getData("html/text");
	     var nodeCopy = document.getElementById(data).cloneNode(true);
	     var i=$("#"+newid).find("table tbody tr").length;
		var newid = data + "_" +(i+2);
		var oldid=$("#"+data).data("id");
		var price=$("#"+data).data("price");
		var name=$("#"+data).data("name");
		var unit=$("#"+data).data("unit");
		nodeCopy.id = newid ; /* We cannot use the same ID */
		//ev.target.append(nodeCopy);
		$("form.forms.active #"+ev.target.id + " table tbody").append('<tr data-id="'+ oldid +'" data-price="'+price+'" data-name="'+name+'"  data-unit="'+unit+'"><td class="item-name"><input type="text" name="sale[items]['+i+'][id]" value="'+oldid+'" style="display:none;"/>'+name+'</td><td class="item-price"><input type="text" name="sale[items]['+i+'][price]" value="'+price+'" style="display:none;"/>'+price+'</td><td class="item-qnt"><input type="text" name="sale[items]['+i+'][qnt]" value="1" class="blank-input"/></td><td class="item-total"><input type="text" name="sale[items]['+i+'][total]" value="'+price+'"  class="blank-input"/></td><td><i class="fa fa-remove"></i></td></tr>');
		calculateTotal($("form.forms.active").attr("id"));
	    //ev.target.appendChild(document.getElementById(data));
	}

    function calculateTotal(form)
    {
	var qnttotal=0;
	var amounttotal=0;
	var pricewat=0;
	var watprice=0;
	$("#"+form+" div.summary-list table tr:last td.total input").val(qnttotal.toFixed(2));
	$("#"+form+" div.summary-list table tr:first td.amount input").val(qnttotal.toFixed(2));
	$("#"+form+" div.summary-list table tr:first td.items").text(qnttotal.toFixed(2) + " <?= __("items")?>");
	$("#"+form+" div.summary-list table tr:nth-child(2) td.watamount input").val(qnttotal.toFixed(2));
	$("#"+form+" div.table-container table tbody tr").each(function(i){
	    var price = $(this).find("td.item-price input").val();
	    var qnt = $(this).find("td.item-qnt input").val();
	    var total = parseFloat(price)*parseFloat(qnt);
	    amounttotal+=total;
	    qnttotal+=parseFloat(qnt);
	    var pricewat=amounttotal/wat;
	    var watprice = amounttotal-pricewat;
	    $.post('<?= Yii::$app->urlManager->createUrl("site/setsess")?>&id='+form+"&i="+i+"&pr="+$(this).data("id")+"&qnt="+qnt+"&price="+price+"&total="+total);
	    //console.log(amounttotal + " - " + qnttotal + " - " + pricewat + " - "+ watprice);
	    $(this).find("td.item-total input").val(total.toFixed(2));
	    $("#"+form+" div.summary-list table tr:last td.total input").val(amounttotal.toFixed(2));
	    $("#"+form+" div.summary-list table tr:first td.amount input").val(watprice.toFixed(2));
	    $("#"+form+" div.summary-list table tr:first td.items").text(qnttotal.toFixed(2) + " <?= __("items")?>");
	    $("#"+form+" div.summary-list table tr:nth-child(2) td.watamount input").val(pricewat.toFixed(2));
	});
	var sutotal=$("#"+form+" div.summary-list table tr:nth-child(2) td.watamount input").val();
	$.post('<?= Yii::$app->urlManager->createUrl("site/setsess")?>&id='+form+"&i=null&&pr=null&qnt="+qnttotal+"&price="+sutotal+"&total="+amounttotal);
	$("#btn-cancel, #btn-pay").attr("disabled", true);
	if($("div.table-container table tbody tr").length)
	    $("#btn-cancel, #btn-pay").attr("disabled", false);
    }
    function checkForms()
    {
	var br=0;
	$("div.part.left div.btns-main").find("a.btn-forms").remove();
	$("div.part.left .forms").each(function(i){
	    br=i+1;
	    $("div.part.left div.btns-main").prepend('<a href="#" class="btn btn-default btn-forms btn-lg" data-id="'+i+'">'+br+'</a>');
	});
	 $("div.part.left div.btns-main a.btn-forms").removeClass("active");
	 $("div.part.left div.btns-main a.btn-forms:first").addClass("active");
    }
    $("a.add-form").on("click", function(){
	$("div.part.left form.forms").removeClass("active");
	var obj=$("form#form-blank").clone();
	var fl= $("div.part.left form.forms").length;
	obj.attr("id", "form-"+fl);
	obj.removeClass("hidden");
	obj.addClass("active");
	var dobj= obj.find("div#table-products");
	dobj.attr("id",dobj.attr("id")+"-"+fl);
	$("div.part.left").append(obj);
	
	checkForms();
    });
    $(document).on("click", "div.products-list div.table-container table tbody tr td i.fa-remove", function(){$(this).parent("td").parent("tr").remove();calculateTotal($("form.forms.active").attr("id"));});
    $(document).on("click", "a.btn-forms", function(){$("a.btn-forms").removeClass("active");$(this).addClass("active");
		$("div.part.left form.forms").removeClass("active");$("form#form-"+$(this).data("id")).addClass("active");});
</script>
