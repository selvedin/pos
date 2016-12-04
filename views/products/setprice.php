<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$form = ActiveForm::begin(['options' =>['id' => 'prices-form']]);
$products = app\models\Products::find()->all();
$proc=2;
$this->title=__("Products"). " - " . __("Prices")
?>
<style>
    tfoot td{font-size:14px;color:darkred;font-weight: bold;}
</style>
<h1><?= __("Percent")?>: <?= ($proc-1)*100?> %</h1>
<table class="table table-condensed" style="background-color:#eee;"><thead><tr><th>#</th><th><?= __("Name") ?></th><th><?= __("Barcode") ?></th><th><?= __("Code") ?></th><th><?= __("Price") ?></th><th><?= __("Buy Price") ?></th><th><?= __("Sel Price") ?></th><th><?= __("Qnt") ?></th><th><?= __("Paid") ?></th><th><?= __("Final Price") ?></th></tr></thead><tbody>
<?php
$tot=[0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0];
foreach ($products as $p) {
    $tot[0]+=$p->price;
    $tot[1]+=$p->price1;
    $tot[2]+=$p->price1*$p->qnt;
    $tot[3]+=$p->price2;
    $tot[4]+=$p->price2*$p->qnt;
    $tot[5]+=$p->qnt;
   echo "<tr><td>$p->id_product</td><td>$p->name</td><td>$p->barcode</td><td>$p->code</td><td>".number_format($p->price, 2, ",", ".")."</td><td>".number_format($p->price1, 2, ",", ".")."</td><td class='myprice'>". Html::textInput("prices[$p->id_product][price]", number_format($proc>0?$p->price1*$proc:$p->price2,9)). "</td><td class='qnt'>". Html::textInput("prices[$p->id_product][qnt]", ($p->qnt>0?$p->qnt:@$_POST['prices'][$p->id_product]['qnt'])). "</td><td>".number_format($p->price1*$p->qnt, 2, ",", ".")."</td><td>".(number_format($p->qnt*$p->price2,2,",","."))."</td></tr>";
}

?>
    <tfoot><td></td><td><?=  __("Total") ?></td><td></td><td></td><td><?= number_format($tot[0], 2, ",", ".")?></td><td><?= number_format($tot[1], 2, ",", ".")?></td><td><?= number_format($tot[3], 2, ",", ".")?></td><td class="total"><?= $tot[5]?></td><td><?= number_format($tot[2], 2, ",", ".")?></td><td><?= number_format($tot[4], 2, ",", ".")?></td></tfoot>
</tbody></table>
<input type="submit" value="<?= __("Save") ?>" class="btn btn-success"/>
<a href="<?= Yii::$app->urlManager->createUrl("products/printprice")?>&barcode" class="btn btn-default" target="_new"><?= __("Print Barcode") ?></a>
<a href="<?= Yii::$app->urlManager->createUrl("products/printprice")?>&print" class="btn btn-info" target="_new"><?= __("Print List") ?></a>
<?php
     ActiveForm::end(); 
 ?>
<script>
    $("td.qnt input").keypress(function(e){
	if(e.which == 13){
	    $(this).parent("td").parent("tr").next().find("td.qnt input").focus().select();
	    return false;
	}
    });
    $("td.myprice input").keypress(function(e){
	if(e.which == 13){
	    $(this).parent("td").parent("tr").next().find("td.myprice input").focus().select();
	    return false;
	}
    });
    $("td.qnt input, td.myprice input").on("change", function(){
	var tot=0;
	$("td.qnt input").each(function(){
	    if($(this).val()!="")
		var qnt=$(this).val();
	    else
		var qnt=0;
	    var pr=$(this).parent("td").parent("tr").find("td.myprice input").val();
	    tot+=parseInt(qnt)*parseFloat(pr);
	});
	$("td.total").text(parseFloat(tot).toFixed(2));
    });

</script>