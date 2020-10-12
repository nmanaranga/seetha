<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->pdf->setPrintFooter(true);
$this->pdf->setPrintHeader(true);
$this->pdf->setPrintHeader($header,$type); 

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3
foreach($branch as $ress){
	$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}




$this->pdf->setY(25);
$this->pdf->SetFont('helvetica', 'BU',12);
$this->pdf->Cell(180, 1,"Account's Analysis Report",0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->setY(30);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Ln();
$this->pdf->Cell(162, 1,"From - ".$dfrom."     To - ".$dto,0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(180, 1,"Branch - ".$s_branch,0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();


$this->pdf->Cell(180, 1,"Account - ".$acc_code." ".$account_det,0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();
$this->pdf->setX(10);
$this->pdf->Cell(20, 4, 'Date', '1', 0, 'C', 0);
$this->pdf->Cell(20, 4, "No", '1', 0, 'C', 0);
$this->pdf->Cell(30, 4, "Transaction", '1', 0, 'C', 0);
$this->pdf->Cell(20, 4, "Cheque No", '1', 0, 'C', 0);
$this->pdf->Cell(70, 4, "Payee Name", '1', 0, 'C', 0);
$this->pdf->Cell(70, 4, "Description", '1', 0, 'L', 0);
$this->pdf->Cell(25, 4, "Dr Amount", '1', 0, 'C', 0);
$this->pdf->Cell(25, 4, "Cr Amount", '1', 0, 'C', 0);
$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

/*$this->pdf->Ln();
$this->pdf->setX(10);
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 4, '', '1', 0, 'L', 0);
$this->pdf->Cell(20, 4, "", '1', 0, 'L', 0);
$this->pdf->Cell(25, 4, "", '1', 0, 'L', 0);
$this->pdf->Cell(25, 4, "", '1', 0, 'C', 0);
$this->pdf->Cell(70, 4, "", '1', 0, 'C', 0);
$this->pdf->Cell(70, 4, "", '1', 0, 'L', 0);
$this->pdf->Cell(25, 4, number_format($dr,2), '1', 0, 'R', 0);
$this->pdf->Cell(25, 4,  number_format($cr,2), '1', 0, 'R', 0);*/

$this->pdf->Ln();

$total=(float)$op;
$cr_toal=$cr;
$dr_toal=$dr;
$period_cr=(float)0;
$period_dr=(float)0;

foreach($all_acc_det as $row){
	$this->pdf->HaveMorePages(4);
	$this->pdf->setX(10);
	
	$this->pdf->SetFont('helvetica', '', 8);

	$heigh=6*(max(1,$this->pdf->getNumLines($row->description, 70),$this->pdf->getNumLines($row->name, 70)));


	$this->pdf->MultiCell(20, $heigh, $row->ddate, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(20, $heigh, $row->tno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(30, $heigh,  ucfirst(strtolower($row->det)), 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(20, $heigh,  $row->cheque_no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(70, $heigh,  $row->name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(70, $heigh, $row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, $heigh, $row->dr_amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, $heigh, $row->cr_amount, 1, 'R', 0, 1, '', '', true, 0, false, true, 0);

$dr_toal+=$row->dr_amount;
$cr_toal+=$row->cr_amount;
}
/*$this->pdf->setX(10);
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 4, '', '1', 0, 'L', 0);
$this->pdf->Cell(20, 4, "", '1', 0, 'L', 0);
$this->pdf->Cell(25, 4, "", '1', 0, 'L', 0);
$this->pdf->Cell(25, 4, "", '1', 0, 'L', 0);
$this->pdf->Cell(70, 4, "", '1', 0, 'L', 0);
$this->pdf->Cell(70, 4, "Period Balance", '1', 0, 'L', 0);
$this->pdf->Cell(25, 4, number_format($period_dr,2), '1', 0, 'R', 0);
$this->pdf->Cell(25, 4, number_format($period_cr,2), '1', 0, 'R', 0);
$this->pdf->Ln();*/

$this->pdf->setX(10);
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 4, '', '1', 0, 'L', 0);
$this->pdf->Cell(20, 4, "", '1', 0, 'L', 0);
$this->pdf->Cell(30, 4, "", '1', 0, 'L', 0);
$this->pdf->Cell(20, 4, "", '1', 0, 'L', 0);
$this->pdf->Cell(70, 4, "", '1', 0, 'L', 0);
$this->pdf->Cell(70, 4, "Ending Balance", '1', 0, 'L', 0);
$this->pdf->Cell(25, 4, number_format($dr_toal,2), '1', 0, 'R', 0);
$this->pdf->Cell(25, 4, number_format($cr_toal,2), '1', 0, 'R', 0);

$this->pdf->Output("Account Report".date('Y-m-d').".pdf", 'I');

?>