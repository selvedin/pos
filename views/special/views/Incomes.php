<div class="list-items">
     <table class="table table-condensed added-items">
	<thead><tr><th class="added-id">#</th><th class="added-name"><?= __("Product")?></th><th class="added-unit"><?= __("Unit")?></th><th class="added-price"><?= __("Price")?></th><th class="added-qnt"><?= __("Qnt")?></th><th class="added-total"><?= __("Total")?></th></tr></thead>
	<tbody>
	    <?php
	    $items = \app\models\Incomeitems::find()->where("id_income=$model->id_income")->all();
	    $total=0;
	    foreach ($items as $item) {
		$product = \app\models\Products::findOne($item->id_product);
		$unit = \app\models\Units::findOne($product->id_unit);
		$total+=$item->price;
		echo "<tr><td>$item->id_product</td><td>$product->name</td><td>".$product->idUnit->short."</td><td>$product->price</td><td>$item->qnt</td><td>$item->price</td></td></tr>";
	    }
	    ?>
	    
	</tbody>
	<tfoot><tr><td class="added-id"></td><td class="added-name"><?= _("Total") ?></td><td class="added-unit"></td><td class="added-price"></td><td class="added-qnt"></td><td class="added-total"><?= number_format($total,2)?></td></tr></tfoot>
    </table>
</div>
