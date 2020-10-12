<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page);   

foreach($branch as $ress){
	$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}


$this->pdf->SetFont('helvetica', 'BU',10);
$this->pdf->Cell(0, 5, 'User List',0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();
$this->pdf->Ln();



$this->pdf->SetX(5);
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(30, 6,"User Name", '1', 0, 'C', 0);
$this->pdf->Cell(50, 6,"description", '1', 0, 'C', 0);
$this->pdf->Cell(50, 6,"Cluster", '1', 0, 'C', 0);
$this->pdf->Cell(50, 6,"Branch", '1', 0, 'C', 0);

$this->pdf->Ln();

$tot_dis=(float)0;

foreach ($r_data as $value) {

	$this->pdf->SetFont('helvetica','',7);
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
	$aa = max($this->pdf->getNumLines($value->loginName, 30),$this->pdf->getNumLines($value->discription, 50),$this->pdf->getNumLines($value->cl."-".$value->clus_name, 50),$this->pdf->getNumLines($value->bc."-".$value->branch_name, 50));
	$heigh=6*$aa;
	$this->pdf->haveMorePages($heigh);
	$this->pdf->SetX(5);
	$this->pdf->MultiCell(30, $heigh,$value->loginName,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(50, $heigh,$value->discription,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(50, $heigh,$value->cl."-".$value->clus_name,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(50, $heigh,$value->bc."-".$value->branch_name,  1, 'L', 0,1, '', '', true, 0, false, true, 0);
	
}


$this->pdf->Output("Purchase Summary".date('Y-m-d').".pdf", 'I');

?>