
  <?php
  $products = app\models\Products::find()->all();
  require_once(dirname(__FILE__) . '/../../tcpdf/tcpdf.php');
  class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = 'images/logo.png';
		$this->Image($image_file, 15, 2, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('dejavusans', 'B', 14);
		// Title
		$this->SetY(10);
		$this->Cell(0, 0, __("Products List"), 0, false, 'R', 0, '', 0, false, 'M', 'M');
		$this->Line(15, 20, 194, 20);
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('dejavusans', 'I', 8);
		// Page number
		$this->Cell(0, 10, __("Page") ." ".$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
		$this->Line(15, 283, 194, 283);
	}
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Selvedin Haskic');
$pdf->SetTitle('Product List');
$pdf->SetSubject('Products List');
$pdf->SetKeywords('POS, Products, List, Selvedin, Haskic');

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

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


$pdf->SetFont('dejavusans', '', 8.5);

$html=<<<EOF
	<style>
	    th,tr.footer td{background-color:#337AB7;color:#fff;font-size:8;font-weight:bold;height:30px;line-height:2;text-align:center;}
	    td{border-bottom:1px solid gray;height:20px;}
	    tr.even td{background-color:#eee;}
	</style>
EOF;

$html .= '<table><tr><th style="width:3%;">#</th><th style="width:20%;">'. __("Name") .'</th><th style="width:12%;">'.__("Barcode") .'</th><th>'. __("Code") .'</th><th style="width:9%;">'. __("Price") .'</th><th style="width:9%;">'. __("Buy Price") .'</th><th style="width:9%;">'. __("Sel Price") .'</th><th style="width:9%;">'. __("Qnt") .'</th><th style="width:9%;">'.__("Paid") .'</th><th style="width:9%;">'. __("Total").'</th></tr></table>';
$tot=[0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0];
$html.='<table>';
$br=1;
foreach ($products as $p) {
    $tot[0]+=$p->price;
    $tot[1]+=$p->price1;
    $tot[2]+=$p->price1*$p->qnt;
    $tot[3]+=$p->price2;
    $tot[4]+=$p->price2*$p->qnt;
    $tot[5]+=$p->qnt;
    $class=$br%2?"even":"odd";
    $br++;
   $html.='<tr class="'.$class.'"><td style="width:3%;">'.$p->id_product.'</td><td style="width:20%;">'.$p->name.'</td><td style="width:12%;text-align:center;">'.$p->barcode.'</td><td style="text-align:center;">'.$p->code.'</td><td style="width:9%;text-align:right;">'.number_format($p->price, 2, ",", ".").'</td><td style="width:9%;text-align:right;">'.number_format($p->price1, 2, ",", ".").'</td><td  style="width:9%;text-align:right;">'. number_format($p->price2,2, ",", "."). '</td><td  style="width:9%;text-align:right;">'. ($p->qnt). '</td><td style="width:9%;text-align:right;">'.number_format($p->price1*$p->qnt, 2, ",", ".").'</td><td style="width:9%;text-align:right;">'.(number_format($p->qnt*$p->price2,2,",",".")).'</td></tr>';
}
$html.='<tr class="footer"><td colspan="4">'.  __("Total") .'</td><td style="width:9%;text-align:right;">'. number_format($tot[0], 2, ",", ".").'</td><td style="width:9%;text-align:right;">'.number_format($tot[1], 2, ",", ".").'</td><td style="width:9%;text-align:right;">'. number_format($tot[3], 2, ",", ".").'</td><td style="width:9%;text-align:right;">'. $tot[5].'</td><td style="width:9%;text-align:right;">'. number_format($tot[2], 2, ",", ".").'</td><td style="width:9%;text-align:right;">'. number_format($tot[4], 2, ",", ".").'</td></tr>';
$html.='</table>';
$pdf->writeHTML($html, true, false, true, false, '');
ob_get_clean();
//Close and output PDF document
$pdf->Output('Product list.pdf', 'I');
  ?>

