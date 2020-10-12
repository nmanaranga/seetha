<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
// $this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(false);
// $this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->SetMargins(0,0,0,true); //L T R
$this->pdf->setPageOrientation("L", true,0);
// $this->pdf->setPageUnit("mm");
$this->pdf->AddPage("L","BARCODE");   // L or P amd page type A4 or A3
$image=FCPATH.'/images/t_barcode_3.png';
$counter = 0;

foreach ($det as $value) {
	if (!empty($value['serials'])) {
		$item=explode(",", $value['serials']);
	}else{
		$item=$value['item_id'];		
	}

	$secCode=array("S","B","L","A","C","K","L","I","O","N");

	
	$itmNm=$value['description'];
	$mxSlPris=$value['sel_pr'];
	$costPrice=$value['purchase_price'];
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

	$cost=number_format($costPrice,0);
	$arra=str_split($cost);
	

	for($x=0; $x<strlen($cost); $x++){
		$price_code.=$secCode[$arra[$x]];
	}
	

// var_dump(is_array($item));exit();
	for($i = 0; $i < $nOLbl ; $i++){

		$pitem=(is_array($item))?$item[$i]:$item;

		if($counter == 2)
		{
			$this->pdf->AddPage("L","BARCODE");
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
	$y = 2;//$this->pdf->GetY()+5;
	$this->pdf->setCellMargins(1,0,15,0);//l t r b

	$this->pdf->write1DBarcode($pitem , 'C128', $x+3, $y+2, 40, 18, 0.4, $style, 'L');

	// $this->pdf->setCellMargins(0,0,0,0);//l t r b

	//$this->pdf->SetXY($x+2,$y+16);

	$this->pdf->SetXY($x+2,$y+16);
	$this->pdf->StartTransform();
	$this->pdf->Rotate(90);
	$this->pdf->SetFont('helvetica', 'B', 8);		
	$this->pdf->Image($image,$x+2,$y+15,15,0,'','','T',false, 72,'',false,false,1,'LT');
	$this->pdf->StopTransform();

	$this->pdf->SetXY($x+16,$y+5);
	$this->pdf->SetFont('helvetica', 'N', 7);		
	$this->pdf->Cell(15, 4, $mdl,'0',0, 'L', "", "", "", false, 'C', 'T');

	$this->pdf->SetXY($x+18,$y+19);
	$this->pdf->SetFont('helvetica', 'N', 7);		
	$this->pdf->Cell(15, 4, $batch,'0',0, 'L', "", "", "", false, 'C', 'T');
	$this->pdf->SetFont('helvetica', 'N', 8);
	$this->pdf->Cell(8, 4, $ccode,'0',0, 'L', "", "", "", false, 'C', 'T');
	// $this->pdf->StopTransform();

	$this->pdf->SetFont('helvetica', 'B', 11);
	$this->pdf->SetXY($x+5,$y+18);
	$this->pdf->Cell(30, 4, ($mxSlPris),'0',0, 'R', "", "","", false, 'C', 'T');

	$this->pdf->SetFont('helvetica', '', 8);
	$this->pdf->SetXY($x+2,$y+19.5);
	$this->pdf->Cell(40, 2, ($cosPris),'0',0, 'L', "", "","", false, 'C', 'T');	

	$this->pdf->SetFont('helvetica', 'N', 6.5);
	$this->pdf->setCellMargins(0,2,15,0);
	//l t r b
	$this->pdf->SetXY($x+5,$y);
	$this->pdf->Cell(40, 4, $itmNm,'0',0, 'C', "", "", "", false, 'C', 'T');
	

}

}


$this->pdf->Output("barcode_print".date('Y-m-d').".pdf", 'I');

?>