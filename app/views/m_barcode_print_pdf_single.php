<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
// $this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(false);
// $this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->SetMargins(0,0,0,true); //L T R
$this->pdf->setPageOrientation("L", false,0);
// $this->pdf->setPageUnit("mm");
$this->pdf->AddPage("L","BCODEHF");   // L or P amd page type A4 or A3
// $this->pdf->startPage("L","BARCODE"); 

// $this->pdf->SetFont('helvetica', '', 10);

// // define barcode style
// $style = array(
// 	'position' => '',
// 	'align' => 'C',
// 	'stretch' => false,
// 	'fitwidth' => true,
// 	'cellfitalign' => '',
// 	'border' => false,
// 	'hpadding' => 'auto',
// 	'vpadding' => 'auto',
// 	'fgcolor' => array(0,0,0),
// 		'bgcolor' => false, //array(255,255,255),
// 		'text' => false,
// 		'font' => 'helvetica',
// 		'fontsize' => 8,
// 		'stretchtext' => 1
// 		);

//  $y-6.5, 63.5, 18, 0.4, $style, 'L');
// $counter = 1;

// for($i ='0'; $i < $nOLbl ; $i++)
// {
// 	$this->pdf->HaveMorePages_BARCODE(25.4);

// 	$x =$this->pdf->GetX();
// 	$y = $this->pdf->GetY();

// 	$this->pdf->Cell(50, 4, $itmNm,0,false, 'C', 0, '', 0, false, 'M', 'M');
// 	$this->pdf->Ln(1);	
// 	$this->pdf->write1DBarcode($item , 'C128', "", "", 50, 15, 0.4, $style, 'M');
// 	$this->pdf->Ln(8);
// 	$this->pdf->Cell(50, 5, $mxSlPris,0,false, 'C', 0, '', 0, false, 'M', 'M');

// 	// $this->pdf->Ln();	

// 	$this->pdf->SetXY($x+50,$y);
// 	$this->pdf->Cell(50, 5, $itmNm,0,false, 'C', 0, '', 0, false, 'M', 'M');
// 	$this->pdf->Ln(1);	
// 	$this->pdf->SetXY($x+50,$y);
// 	$this->pdf->write1DBarcode($item , 'C128', "", "", 50, 15, 0.4, $style, 'M');

// 	$this->pdf->SetXY($x+50,$y);
// 	$this->pdf->Ln(8);
// 	$this->pdf->Cell(50, 5, $mxSlPris,'',false, 'C', "", '', 0, false, 'M', 'M');

// 	if($counter == 2)
// 	{
// 		$this->pdf->Ln(50);
// 		$counter = 1;
// 	}else{
// 		$counter++;
// 	}

// }



// $this->pdf->SetFont('helvetica', '', 8);

// // define barcode style
// $style = array(
//     'position' => '',
//     'align' => 'L',
//     'stretch' => true,
//     'fitwidth' => false,
//     'cellfitalign' => '',
//     'border' => true,
//     'hpadding' => 'auto',
//     'vpadding' => 'auto',
//     'fgcolor' => array(0,0,0),
//     'bgcolor' => false, //array(255,255,255),
//     'text' => true,
//     'font' => 'helvetica',
//     'fontsize' => 8,
//     'stretchtext' => 4
// );

// // for ($o = 0; $o < 5; $o++) {
// //     // $this->pdf->AddPage();


//     $y = 2;

//     for ($i = 0; $i < 4; $i++) {
//         $x = 2;
// 	// $this->pdf->HaveMorePages_BARCODE(20);
//         for ($p = 0; $p < 2; $p++) {
//             // UPC-E
//             $this->pdf->write1DBarcode('04210000526', 'UPCE', $x, $y, 50, 20, 0.4, $style);

//             $x = $x + 54;
//         }

//         $y = $y + 22;

//         $this->pdf->Ln();
//     }
// // }
// $this->pdf->endPage();





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
	$cosPris=$value['cos_code'];
	$nOLbl=$value['qty']*2;
	$batch="B.No: ".$value['batch'];
	$ccode=$value['ccode'];
	$mdl=$value['model'];

	$itmNm=($value['pr_name']==1)?$itmNm:"";
	$mxSlPris=($value['pr_price']==1)?$mxSlPris:"";
	$cosPris=($value['pr_coscode']==1)?$cosPris:"";
	$batch=($value['pr_btcno']==1)?$batch:"";
	$ccode=($value['pr_color']==1)?$ccode:"";
	$image=($value['pr_comlogo']==1)?$image:FCPATH.'/images/t_barcode_3.png';
	$prCd=($value['pr_icode']==1)?true:false;
	$md=($value['md']==1)?true:false;

// var_dump(is_array($item));exit();
	for($i = 0; $i < $nOLbl ; $i++){

		$pitem=(is_array($item))?$item[$i]:$item;

		if($counter == 2)
		{
			$this->pdf->AddPage("L","BCODEHF");
			$counter = 1;
		}else{
			$counter++;
		}

		$this->pdf->SetFont('helvetica', 'B', 7);

// define barcode style
		$style = array(
			'position' => '',
			'align' => 'C',
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
			'fontsize' => 8,
			'stretchtext' => 1
		);

		$x = $this->pdf->GetX();
	$y = 4;//$this->pdf->GetY()+5;
	$this->pdf->setCellMargins(1,0,15,0);//l t r b

	$this->pdf->write1DBarcode($pitem , 'C128', $x+5, $y+1, 45, 18, 0.4, $style, 'C');

	// $this->pdf->write2DBarcode($item , 'QRCODE,H', $x+1, $y, 10, 10, 0.4, $style, 'L');

	//Reset X,Y so wrapping cell wraps around the barcode's cell.

	$this->pdf->setCellMargins(0,0,0,0);//l t r b

	// $this->pdf->Cell(63.5, 5, $itmNm, 0, 0, 'L', FALSE, '', 0, FALSE, 'C', 'T');
				// Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
	
	$this->pdf->SetXY($x+15,$y+25);
	$this->pdf->StartTransform();
	$this->pdf->Rotate(90);
	$this->pdf->Image($image,60,-25,15,0,'','','T',false, 72,'',false,false,1,'LT');
	$this->pdf->StopTransform();


	$this->pdf->SetXY($x+16,$y+4);
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
	$this->pdf->SetXY($x+5,$y-1);
	$this->pdf->Cell(40, 4, $itmNm,'0',0, 'C', "", "", "", false, 'C', 'T');
	

}

}


$this->pdf->Output("barcode_print".date('Y-m-d').".pdf", 'I');

?>