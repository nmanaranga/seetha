<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->excel->setActiveSheetIndex(0);


foreach($branch as $ress){
	$this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

$this->excel->setHeading("TRIAL BALANCE REOPRT (Period)");
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

     
$this->excel->SetBorders('A'.$r.":".'D'.$r);
$this->excel->SetFont('A'.$r.":".'G'.$r,"BC",12,"","");

// $this->pdf->MultiCell(15, 5, '', '', '1', 0, 0, '', '', false, '', 0);
// $this->pdf->MultiCell(70, 5, '', '', '1', 0, 0, '', '', false, '', 0);        
// $this->pdf->MultiCell(25, 5, 'As at date:', '1', 'C', 0, 0, '', '', false, '', 0);        
// $this->pdf->MultiCell(25, 5, $dto, '1', 'R', 0, 0, '', '', false, '', 0);        
// $this->pdf->MultiCell(5, 5, '', '#', '1', 0, 0, '', '', false, '', 0);         
// $this->pdf->MultiCell(25, 5, 'Dt : '.$dfrom, '1', 'C', 0, 0, '', '', false, '', 0);        
// $this->pdf->MultiCell(25, 5, $dto, '1', 'C', 0, 1, '', '', false, '', 0);        

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("C,D");
foreach($trial_balance as $r){


	$this->excel->getActiveSheet()->setCellValue('A'.$key, $r->code);
	$this->excel->getActiveSheet()->setCellValue('B'.$key, $r->description);

	$balance=$r->dr_Asat - $r->cr_Asat;
/*	if($balance>0)
	{
		$this->excel->getActiveSheet()->setCellValue('C'.$key, $balance);
		$this->excel->getActiveSheet()->setCellValue('D'.$key, 0);   

		$t1 =$balance+$t1;
	}
	else
	{
		$this->excel->getActiveSheet()->setCellValue('C'.$key, 0);
		$this->excel->getActiveSheet()->setCellValue('D'.$key, -$balance);   

			$t2= -($balance)+$t2;
	}*/
 
	
	$balance1=$r->dr_range -$r->cr_range;
	if($balance1>0)
	{
		$this->excel->getActiveSheet()->setCellValue('C'.$key, $balance1);
		$this->excel->getActiveSheet()->setCellValue('D'.$key, 0); 

		$t11 =$balance1+$t11;
	}
	else
	{
		$this->excel->getActiveSheet()->setCellValue('C'.$key, 0);
		$this->excel->getActiveSheet()->setCellValue('D'.$key, -$balance1);  

	

		$t22= -($balance1)+$t22;
	}

	$count=$count+1;
	$balance=0;
	$balance1=0;

	$this->excel->SetBorders('A'.$key.":".'D'.$key);
	$key++;
}




$key=$this->excel->NextRowNum();
$this->excel->SetFont('A'.$key.":".'G'.$key,"B",12,"","");
$this->excel->SetBorders('A'.$key.":".'D'.$key);
$this->excel->getActiveSheet()->setCellValue('B'.$key, "Total");
/*$this->excel->getActiveSheet()->setCellValue('C'.$key, $t1);  
$this->excel->getActiveSheet()->setCellValue('D'.$key, $t2);*/

$this->excel->getActiveSheet()->setCellValue('C'.$key, $t11);
$this->excel->getActiveSheet()->setCellValue('D'.$key, $t22);  


$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));





