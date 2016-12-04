<?php
use app\models\Outcomes;
use app\models\Outcomeitems;
    $company=['name'=>'SH DIZAJN', 'place'=>'Vlastita kuca', 'address'=>'Donja Veceriska b.b.']
?>

<style>
    html,body{background: none;color:#000;margin-top:-25px;}
    table tr td.center{text-align: center;}
    td.top-border{border-top: 1px solid gray;}
    td.bottom-border{border-bottom: 1px solid gray;}
    td.item{font-weight: bold;}
    td.right{text-align: right;}
</style>
<table>
    <tr><td colspan="2" class="center top-border"></td></tr>
    <tr><td colspan="2" class="center"><?= $company['name']?></td></tr>
    <tr><td colspan="2" class="center"><?= $company['place']?></td></tr>
    <tr><td colspan="2" class="center"><?= $company['address']?></td></tr>
    <tr><td colspan="2" class="bottom-border"></td></tr>
    <tr><td colspan="2"></td></tr>
    <tr><td colspan="2">JIB:<?= rand(100000000,999999999)?></td></tr>
    <tr><td colspan="2">PIB:<?= rand(100000000,999999999)?></td></tr>
    <tr><td colspan="2"></td></tr>
    <tr><td colspan="2">IBFM:<?= rand(100000000,999999999)?></td></tr>
     <tr><td colspan="2" class="top-border"></td></tr>
     <tr><td colspan="2" class="center"><h4>FISKALNI RACUN</h4></td></tr>
     <tr><td colspan="2">BF:<?= rand(100000,999999)?></td></tr>
     <tr><td colspan="2"><?= date("d.m.Y. H:i") ?></td></tr>
      <tr><td colspan="2"></td></tr>
       <tr><td colspan="2"></td></tr>
       <tr><td colspan="2" class="center top-border"></td></tr>

<?php
$items = Outcomeitems::find()->where("id_outcome=$model->id_outcome")->all();
foreach ($items as $i) {
    echo"<tr><td colspan='2' class='item'>".$i->idProduct->name."</td></tr>";
    echo"<tr><td>".$i->qnt."x".$i->idProduct->price . "</td><td class='right'>". number_format($i->price,2) ."</td></tr>";
}
?>
       <tr><td colspan="2" class="bottom-border"></td></tr>
       <?php
       echo "<tr><td>VE:17.00%</td><td></td></tr>";
       echo "<tr><td>OSNOVA</td><td class='right'>".($model->includedWatAmount - $model->noWatAmount)."</td></tr>";
       echo "<tr><td>PDV</td><td class='right'>$model->noWatAmount</td></tr>";
       echo'<tr><td colspan="2""></td></tr>';
       echo'<tr><td colspan="2" class="bottom-border"></td></tr>';
       echo"<tr><td><h4>TOTAL</h4></td><td class='right'>$model->includedWatAmount</td></tr>";
       
       ?>
 </table>

<script>
    window.print();
    window.close();
</script>