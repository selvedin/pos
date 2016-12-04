<style>
    *{background-color: #fff;}
    div{display: block;margin-top:-50px;padding: 0;}
</style>
  <?php
  $code = $model->barcode;
  require_once(dirname(__FILE__) . '/../../tcpdf/tcpdf_barcodes_1d.php');
  $barcodeobj = new TCPDFBarcode($code, 'C128');
  //$barcode = $barcodeobj->getBarcodeHTML(1, 40, 'black');
  $barcode = $barcodeobj->getBarcodeSVG(1, 40, 'black');
  ?>
<div style="width:100px;text-align: center;">
<?php
  echo $barcode;
  echo "<br/><br/><span style='letter-spacing:0.3em;'>" . $code ."</span><br/><span style='font-size:10px;text-transform:uppercase;'>$model->name</span>";
  ?>
</div>
<script>
    window.print();
    </script>