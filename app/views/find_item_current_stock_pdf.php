<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);


$this->pdf->setPrintFooter(true);
$this->pdf->setPrintHeader(true);
$this->pdf->setPrintHeader($header,$type); 

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage("P","A4");   // L or P amd page type A4 or A3

foreach($branch as $ress){
	$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

$this->pdf->Ln();
$this->pdf->SetFont('helvetica', 'B', 7);
$this->pdf->setX(2);
$this->pdf->Cell(25, 6,"Code", '1', 0, 'C', 0);
$this->pdf->Cell(60, 6,"Name", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Model", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6,"Batch", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Purchase Price", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Min Price", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Max Price", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6,"C/Qty", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Total", '1', 0, 'C', 0);
$this->pdf->Ln();


$x=0;
foreach($det as $row){
	
	$this->pdf->SetFont('helvetica','',8);

	$heigh=6*(max(1,$this->pdf->getNumLines($row->description,60)));
	$this->pdf->HaveMorePages($heigh);
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
	$this->pdf->setX(2);	
	$this->pdf->MultiCell(25, $heigh,$row->item,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(60, $heigh,$row->description,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$row->model,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(10, $heigh,$row->batch_no,1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$row->b_cost,1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$row->b_min,1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$row->b_max,1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(10, $heigh,$row->qty,1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,number_format(($row->qty*$row->b_cost),2),1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->Ln();

	$x++;
}






$this->pdf->Output("Find Item current stock_".date('Y-m-d').".pdf", 'I');

?>