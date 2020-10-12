<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintFooter(false);
$this->pdf->SetMargins(0,0,0,true); //L T R
$this->pdf->setPageOrientation("L", false,0);
$this->pdf->AddPage("L","BCODESM");   // L or P amd page type A4 or A3

$image=FCPATH.'/images/t_barcode_3.png';

$counter = 0;

foreach ($det as $value) {
	if (!empty($value['serials'])) {
		$item=explode(",", $value['serials']);
	}else{
		$item=$value['item_id'];		
	}

	$itmNm=$value['description'];
	$mxSlPris=$value['sel_pr'];
	$nOLbl=$value['qty'];
	$batch="B.No: ".$value['batch'];
	$ccode=$value['ccode'];
	$mdl=$value['model'];

	$itmNm=($value['pr_name']==1)?$itmNm:"";
	$mxSlPris=($value['pr_price']==1)?$mxSlPris:"";
	$batch=($value['pr_btcno']==1)?$batch:"";
	$ccode=($value['pr_color']==1)?$ccode:"";
	$image=($value['pr_comlogo']==1)?$image:FCPATH.'/images/t_barcode_blnk.png';
	$prCd=($value['pr_icode']==1)?true:false;
	$md=($value['md']==1)?true:false;

	for($i = 0; $i < $nOLbl ; $i++){

		$pitem=(is_array($item))?$item[$i]:$item;

		if($counter == 3)
		{
			$this->pdf->AddPage("L","BCODESM");
			$counter = 1;
		}else{
			$counter++;
		}

		$this->pdf->SetFont('helvetica', 'B', 7);

// define barcode style
		$style = array(
			'position' => '',
			'align' => 'R',
			'stretch' => true,
			'fitwidth' => true,
			'cellfitalign' => '',
			'border' => 0,
			'hpadding' => 5,
			'vpadding' => 15,
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, 
		// 'bgcolor' => false, //array(255,255,255),
			'text' => $prCd,
			'font' => 'helvetica',
			'fontsize' => 6,
			'stretchtext' => 1
			);

		$x = $this->pdf->GetX();
		$y = 0;


	//Reset X,Y so wrapping cell wraps around the barcode's cell.

	$this->pdf->setCellMargins(1.5,4,0,0);//l t r b

	$this->pdf->SetXY($x,$y);
	$this->pdf->Cell(30, 4, $item,'0',0, 'C', "", "", "", false, 'C', 'T');

	$this->pdf->SetFont('helvetica', '', 6);
	$this->pdf->SetXY($x,$y+3);
	$this->pdf->Cell(30, 4, substr($itmNm,0,20),'0',0, 'C', "", "", "", false, 'C', 'T');

	$this->pdf->SetFont('helvetica', 'B',8);
	$this->pdf->SetXY($x,$y+7);
	$this->pdf->Cell(30, 4, "Rs.".$mxSlPris,'0',0, 'C', "", "", "", false, 'C', 'T');
	

}

}


$this->pdf->Output("barcode_print_small".date('Y-m-d').".pdf", 'I');

?>