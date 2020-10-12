<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page);   

foreach($branch as $ress){
	$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}


$this->pdf->setY(25);

$this->pdf->SetFont('helvetica', 'BU',10);
$this->pdf->Cell(0, 5, 'CREDIT SALES RETURN SUMMARY',0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', '',9);
$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();
$this->pdf->Ln();
$i=0;
$a=-1;
$j=-1;
$Goss=array();
$net=array();
$my_array=array();
foreach ($purchase as $value) {
	$my_array[]=$value->name;
}
foreach ($sum as $sum){
	$Goss[]=$sum->gsum;
	$net[]=$sum->nsum;
	$a++;
}

if($value->nno == "")
{
	$this->pdf->SetX(80);
	$this->pdf->Cell(20, 1, "No Records For View ! ! !", '0', 0, 'L', 0);     
}
else
{



	$this->pdf->SetFont('helvetica', 'B', 8);
	if ($i==0) {
		$this->pdf->setY(40);
	}
	$this->pdf->Ln();
	
	$this->pdf->SetX(15);
	$this->pdf->SetFont('helvetica','B',8);
	$this->pdf->Cell(10, 6," No", '1', 0, 'L', 0);
	$this->pdf->Cell(20, 6," Date", '1', 0, 'L', 0);
	$this->pdf->Cell(60, 6," Customer", '1', 0, 'L', 0);
	$this->pdf->Cell(22, 6,"Gross Amount ", '1', 0, 'R', 0);
	$this->pdf->Cell(22, 6,"Discount ", '1', 0, 'R', 0);
	$this->pdf->Cell(22, 6,"Net Amount ", '1', 0, 'R', 0);
	$this->pdf->Cell(25, 6," Store", '1', 0, 'L', 0);
	$this->pdf->Ln();
	$tot_dis=(float)0;


	foreach ($purchase as $value) {	
		
		$aa = $this->pdf->getNumLines($value->cus_id." | ".$value->name, 60);
		$bb = $this->pdf->getNumLines($value->store,25);
		if($aa>$bb){
			$heigh=5*$aa;
		}else{
			$heigh=5*$bb;
		}
		$this->pdf->SetX(15);
		$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
		$this->pdf->SetFont('helvetica','',8);
		$this->pdf->MultiCell(10, $heigh,$value->nno,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh,$value->ddate, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(60, $heigh,$value->cus_id." | ".$value->name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(22, $heigh,$value->gross_amount,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(22, $heigh,$value->discount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(22, $heigh,$value->net_amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh,$value->store, 1, 'L', 0, 1, '', '', true, 0, false, true, 0);

		$i++;
		
		$tot_disc+=(float)$value->discount;
		
		
	}
	
	$this->pdf->SetFont('helvetica', 'B', 9);               
	$this->pdf->SetX(15);
	$this->pdf->Cell(10, 6, "", '0', 0, 'L', 0);
	$this->pdf->Cell(20, 6, "", '0', 0, 'R', 0);
	$this->pdf->Cell(60, 6, "Total", '0', 0, 'R', 0);
	$this->pdf->Cell(22, 6, number_format($Goss[$a],2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
	$this->pdf->Cell(21, 6, number_format($tot_disc,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(1, 6, "", '0', 0, 'C', 0);
	$this->pdf->Cell(22, 6, number_format($net[$a],2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(25, 6, "", '0', 0, 'C', 0);



}

$this->pdf->Output("Credit Sale Return Summary".date('Y-m-d').".pdf", 'I');

?>