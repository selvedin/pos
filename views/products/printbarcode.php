
  <?php
  require_once(dirname(__FILE__) . '/../../tcpdf/tcpdf.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 027');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 027', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins

$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
// set auto page breaks
$pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------


// add a page
$pdf->AddPage();


$pdf->SetFont('dejavusans', '', 8);

// define barcode style
$style = array(
	'position' => '',
	'align' => 'C',
	'stretch' => false,
	'fitwidth' => true,
	'cellfitalign' => '',
	'border' => false,
	'hpadding' => 'auto',
	'vpadding' => 'auto',
	'fgcolor' => array(0,0,0),
	'bgcolor' => false, //array(255,255,255),
	'text' => true,
	'font' => 'helvetica',
	'fontsize' => 8,
	'stretchtext' => 4
);

// EAN 13
$all=[];
$products = app\models\Products::find()->all();
foreach ($products as $p) {
    for($i=0;$i<$p->qnt;$i++)
    {
	$all[]=['id'=>$p->id_product, 'name'=>$p->name, 'bar'=>$p->barcode, 'price'=>$p->price2];
    }
}
$br=0;$ln=0;$ol=0;
$nl=false;
foreach ($all as $pr){
        if($br>64){
	    $br=0;
	    $ln=0;
	    $pdf->AddPage();
	}
    $pdf->SetX($br*40+8);
    $pdf->SetY($ln*15+10);
    $pdf->writeHTMLCell(35, 0, ($ol*40+8), ($ln*22+10), substr($pr['name'],0,20).(strlen($pr['name'])>20?"...":"") . "<br/><b>".number_format($pr['price'],2,",",".") . " KM</b>", 0, 0, 0, true, 'C', true);
    $pdf->write1DBarcode($pr['bar'], 'EAN13', 8+($ol*40), 16+($ln*22), 38, 14, 0.3, $style, 'N');
    $br++;
    $ol++;
    if(!($br%5))
    {
	$ol=0;
	$ln++;
    }

}

ob_get_clean();
//Close and output PDF document
$pdf->Output('example_005.pdf', 'I');
  ?>

