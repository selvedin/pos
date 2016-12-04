<style>
    .pos-control{width:100%;height:38px;margin: 0;}
    table.pos-search, table.pos-result{width:100%;}
    table.pos-result tr{cursor: pointer;}
    div.item-search, div.item-result{display: inline-block;width:100%;height:40px;padding: 0;}
    div.item-result{height: auto;}
    td.item-code{width:10%;}
    td.item-search{width:50%;}
    td.item-price{width:10%;}
    td.item-qnt{width:10%;}
    td.item-wat{width:10%;}
    td.item-total{width:10%;}
    div.added-items{margin-top:100px;}
    div.added-items table{border-top:3px solid gray;;}
    tfoot td{font-size: 16px;font-weight: bolder;background-color:lightgray;}
    </style>

<ul class="nav nav-tabs">
	<li class="active"><a href="#tab1" data-toggle="tab"><?php echo __("Info") ?></a></li>
	<li><a href="#tab2" data-toggle="tab"><?php echo __("Items") ?></a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="tab1">
	    
	</div>
	<div class="tab-pane" id="tab2">
	    <div class="item-search"><table class="pos-search"><tr><td class="item-search"><input type="text" id="product-search" class="pos-control"/></td></tr></table></div>
	    <div class="item-result"><table class="table table-bordered pos-result"></table></div>
	</div>
</div>
<hr/>
<div class="added-items">
    <table class="table table-condensed added-items">
	<thead><tr><th class="added-id">#</th><th class="added-item"><?= __("Item") ?></th><th class="added-name"><?= __("Name")?></th><th class="added-unit"><?= __("Unit")?></th><th class="added-price"><?= __("Price")?></th><th class="added-qnt"><?= __("Qnt")?></th><th class="added-total"><?= __("Total")?></th><th class="added-discount"><?= __("Discount")?></th><th class="added-finalprice"><?= __("Final Price")?></th></tr></thead>
	<tbody>
	    <?php
	    $total=0;
	    if(!$model->isNewRecord){
		
		$items = \app\models\Orderitems::find()->where("id_order=$model->id_order")->all();
		foreach ($items as $item) {
		    $product = \app\models\Products::findOne($item->id_product);
		    $unit = \app\models\Units::findOne($product->id_unit);
		    $price =$product->price*$item->qnt; 
		    $total+=$price;
		    echo "<tr><td><input type='text' value='$item->id_item' name='orders[items][$item->id_product][id_item]'/></td><td><input type='text' value='$item->id_product' name='orders[items][$item->id_product][id_product]'/></td><td>$product->name</td><td>$unit->short</td><td>$product->price</td><td><input type='text' value='$item->qnt' name='orders[items][$item->id_product][qnt]' /></td><td><input type='text' value='$price' name='orders[items][$item->id_product][total]' /></td><td><input type='text' value='0' name='orders[items][$item->id_product][discount]' /></td><td><input type='text' class='final-price' value='$price' name='orders[items][$item->id_product][final]' /></td></tr>";
		}
	    }
	    else{
		if(isset($_POST['orders']['items'])){
		    foreach ($_POST['orders']['items'] as $key=>$value) {
		    $product = \app\models\Products::findOne($key);
		    $unit = \app\models\Units::findOne($product->id_unit);
		    $total+=$value['final'];
		    echo "<tr><td><input type='text' value='' name='orders[items][$product->id_product][id_item]'/></td><td><input type='text' value='$product->id_product' name='orders[items][$product->id_product][id_product]'/></td><td>$product->name</td><td>$unit->short</td><td>$product->price</td><td><input type='text' value='".$value['qnt']."' name='orders[items][$product->id_product][qnt]' /></td><td><input type='text' value='".$value['final']."' name='orders[items][$product->id_product][total]' /></td><td><input type='text' value='0' name='orders[items][$product->id_product][discount]' /></td><td><input type='text' class='final-price' value='".$value['final']."' name='orders[items][$product->id_product][final]' /></td></tr>";
		    }
		}
	    }
	    ?>
	    
	    
	</tbody>
	<tfoot><tr><td class="added-id"></td><td class="added-item"></td><td class="added-name"><?= _("Total") ?></td><td class="added-unit"></td><td class="added-price"></td><td class="added-qnt"></td><td class="added-total"></td><td class="added-discount"></td><td class="added-finalprice"><?= $total ?></td></tr></tfoot>
    </table>
</div>
<script>
     $("form").on("submit", function(){
	$("table.added-items").find("input").prop('disabled', false);
	$(this).append($("div.added-items"));
    });
    $(document).ready(function(){
	$("div.tab-content div#tab1").append($("form#orders-form"));
	$("div.item-result").hide();
	$("table.added-items").find("input").prop('disabled', true);
	$("#product-search").on("keyup", function(){
	    if($(this).val().length>3){
		$.getJSON( '<?= Yii::$app->urlManager->createUrl(["products/getproduct"])?>&search='+$(this).val(), function( data ) {
		      var l=Object.keys(data).length;
		      if(l>0){
			$("div.item-result").show();
			$("table.pos-result tr").remove();
			$.each( data, function( key, val ) {
			    $("table.pos-result").append('<tr data-id="'+ key +'" data-price="'+val['price']+'" data-name="'+val['name']+'"  data-unit="'+val['unit']+'"><td class="item-code">'+key+'</td><td class="item-search">'+val['name']+'</td><td class="item-unit">'+val['unit']+'</td><td class="item-price">'+val['price']+'</td><td class="item-qnt"><input type="text" class="item-qnt" value="1"/></td><td class="item-discount"><input type="text" class="item-discount" value="0"/></td></td></tr>');
			});
		      }
		});
	    }
	    else{
	    $("div.item-result").hide();
	    $("table.pos-result tr").remove();
	    }
	});
	
	$(document).on("click", "table.pos-result tr", function(){$(this).find("td input.item-qnt").focus();});
	$(document).on("keydown", "table.pos-result tr td input", function(e){
	    if(e.which==13){
		var obj=$(this).parent("td").parent("tr");
		var qnt = obj.find("td input.item-qnt").val();
		var disc = obj.find("td input.item-discount").val();
		if(disc=="")
		    disc=0;
		var price = obj.data("price");
		var id=obj.data("id");
		var name=obj.data("name");
		var unit=obj.data("unit");
		var total =(parseInt(qnt)*parseFloat(price));
		var final =(parseFloat(total)-parseFloat(disc));
		$("table.added-items tbody").append("<tr><td><input type='text' value='0' name='orders[items]["+id+"][id_item]'/></td><td><input type='text' value='"+id+"' name='orders[items]["+id+"][id_product]'/></td><td>"+name+"</td><td>"+unit+"</td><td>"+price+"</td><td><input type='text' value='"+qnt+"' name='orders[items]["+id+"][qnt]' /></td><td><input type='text' value='"+ total.toFixed(2) +"' name='orders[items]["+id+"][total]' /></td><td><input type='text' value='"+ disc +"' name='orders[items]["+id+"][discount]' /></td><td><input type='text' class='final-price' value='"+final.toFixed(2)+"' name='orders[items]["+id+"][final]' /></td></tr>");
		$("table.added-items").find("input").prop('disabled', true);
		$("table.pos-result tr").remove();
		$("#product-search").val("").focus();
		countFoot();
	    }
	});
    });
    
    function countFoot()
    {
	var finalPrice=0;
	var temp=0;
	$("table.added-items tbody tr").each(function(i){
	    temp = $(this).find("td input.final-price").val();
	    finalPrice +=parseFloat(temp);
	});
	$("table.added-items tfoot tr td.added-finalprice").text(finalPrice.toFixed(2));
    }
</script>

<?php //print_r(@$model->errors);?>