<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->excel->setActiveSheetIndex(0);


foreach($branch as $ress){
	$this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

$this->excel->setHeading("TRIAL BALANCE REOPRT");
$this->excel->SetBlank();

if (!empty($dfrom)) {
	$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(), "Date From");
	$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(), $dfrom);
}

if (!empty($dto)) {
	$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(), "Date To");
	$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(), $dto);
}


$t1=0;
$t11=0;
$count=0;
$t2;
$t22;
$bal=0;
$balance=0;
$b=0;
$tbal=0;


$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Code");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Description");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Dr. Amount");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Cr. Amount");
$this->excel->getActiveSheet()->setCellValue('E'.$r," ");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Dr. Amount");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Cr. Amount");        
$this->excel->SetBorders('A'.$r.":".'G'.$r);
$this->excel->SetFont('A'.$r.":".'G'.$r,"BC",12,"","");

// $this->pdf->MultiCell(15, 5, '', '', '1', 0, 0, '', '', false, '', 0);
// $this->pdf->MultiCell(70, 5, '', '', '1', 0, 0, '', '', false, '', 0);        
// $this->pdf->MultiCell(25, 5, 'As at date:', '1', 'C', 0, 0, '', '', false, '', 0);        
// $this->pdf->MultiCell(25, 5, $dto, '1', 'R', 0, 0, '', '', false, '', 0);        
// $this->pdf->MultiCell(5, 5, '', '#', '1', 0, 0, '', '', false, '', 0);         
// $this->pdf->MultiCell(25, 5, 'Dt : '.$dfrom, '1', 'C', 0, 0, '', '', false, '', 0);        
// $this->pdf->MultiCell(25, 5, $dto, '1', 'C', 0, 1, '', '', false, '', 0);        

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("C,D,F,G");
foreach($trial_balance as $r){


	$this->excel->getActiveSheet()->setCellValue('A'.$key, $r->code);
	$this->excel->getActiveSheet()->setCellValue('B'.$key, $r->description);

	// $this->pdf->MultiCell(25, $heigh, $r->code,  0, 'L', 0, 0, '', '', true, 0, false, true, 0);
	// $this->pdf->MultiCell(59, $heigh, $r->description, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);

	$balance=$r->dr_Asat - $r->cr_Asat;
	if($balance>0)
	{
		$this->excel->getActiveSheet()->setCellValue('C'.$key, $balance);
		$this->excel->getActiveSheet()->setCellValue('D'.$key, 0);   

		// $this->pdf->MultiCell(25, $heigh, d($balance), '1', 'R', 0, 0, '', '', '', '', 0, 0);
		// $this->pdf->MultiCell(25, $heigh, d(0), '1', 'R', 0, 0, '', '', '', '', 0, 0);
		$t1 =$balance+$t1;
	}
	else
	{
		$this->excel->getActiveSheet()->setCellValue('C'.$key, 0);
		$this->excel->getActiveSheet()->setCellValue('D'.$key, -$balance);   

		// $this->pdf->MultiCell(25, $heigh, d(0), '1', 'R', 0, 0, '', '', '', '', 0, 0);
		// $this->pdf->MultiCell(25, $heigh, d(-($balance)), '1', 'R', 0, 0, '', '', '', '', 0, 0);
		$t2= -($balance)+$t2;
	}

//     $this->pdf->MultiCell(5, $heigh, '', '#', 'L', 0, 0, '', '', false, '', 0);   
	
	$balance1=$r->dr_range -$r->cr_range;
	if($balance1>0)
	{
		$this->excel->getActiveSheet()->setCellValue('F'.$key, $balance1);
		$this->excel->getActiveSheet()->setCellValue('G'.$key, 0); 
		// $this->pdf->MultiCell(25, $heigh, d($balance1), '1', 'R', 0, 0, '', '', '', '', 0, 0);
		// $this->pdf->MultiCell(25, $heigh, d(0), '1', 'R', 0, 1, '', '', '', '', 0, 0);

		$t11 =$balance1+$t11;
	}
	else
	{
		$this->excel->getActiveSheet()->setCellValue('F'.$key, 0);
		$this->excel->getActiveSheet()->setCellValue('G'.$key, -$balance1);  

		// $this->pdf->MultiCell(25, $heigh, d(0), '1', 'R', 0, 0, '', '', '', '', 0, 0);
		// $this->pdf->MultiCell(25, $heigh, d(-($balance1)), '1', 'R', 0, 1, '', '', '', '', 0, 0);

		$t22= -($balance1)+$t22;
	}

	$count=$count+1;
	$balance=0;
	$balance1=0;

	$this->excel->SetBorders('A'.$key.":".'G'.$key);
	$key++;
}

// $this->pdf->SetFont('', 'B', '');



// $y=$this->pdf->GetY();

// $this->pdf->SetFont('', 'B', '9');
// $this->pdf->MultiCell(25, 1, "", '0', 'R', 0, 0, '', '', '', '', 0, 0);
// $this->pdf->MultiCell(53, 1, "Total", '0', 'C', 0, 0, '', '', '', '', 0, 0);
// $this->pdf->MultiCell(25, 1, d($t1), '1', 'R', 0, 0, '', '', '', '', 0, 0);
// $this->pdf->MultiCell(25, 1, d($t2), '1', 'R', 0, 0, '', '', '', '', 0, 0);
// $this->pdf->MultiCell(5, 1, '', '0', 'R', 0, 0, '', '', '', '', 0, 0);
// $this->pdf->MultiCell(25, 1, d($t11), '1', 'R', 0, 0, '', '', '', '', 0, 0);
// $this->pdf->MultiCell(25, 1, d($t22), '1', 'R', 0, 0, '', '', '', '', 0, 0);


$key=$this->excel->NextRowNum();
$this->excel->SetFont('A'.$key.":".'G'.$key,"B",12,"","");
$this->excel->SetBorders('A'.$key.":".'G'.$key);
$this->excel->getActiveSheet()->setCellValue('B'.$key, "Total");
$this->excel->getActiveSheet()->setCellValue('C'.$key, $t1);  
$this->excel->getActiveSheet()->setCellValue('D'.$key, $t2);

$this->excel->getActiveSheet()->setCellValue('F'.$key, $t11);
$this->excel->getActiveSheet()->setCellValue('G'.$key, $t22);  


$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));





