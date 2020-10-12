<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->pdf->setPrintHeader(true);
$this->pdf->setPrintHeader($header,$type); 
$this->pdf->AddPage($orientation,$page);

foreach($branch as $ress){
	$this->pdf->headerSet4($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
foreach($jobs as $row){
	$no = $row->nno;
	$date= $row->ddate;
	$ref_no=$row->ref_no;	
	$sup_code=$row->sup_code;
	$sup_name=$row->sup_name;
}



$this->pdf->Ln();
$this->pdf->setY(15);
$this->pdf->SetFont('helvetica','B',10);
$this->pdf->Cell(0,5,'Send To Supplier Note',0,false,'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();
$this->pdf->Cell(0, 1,"", 'T', 1, 'L', 0);
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->Cell(15, 1,'Supplier', '0', 0, 'L', 0);
$this->pdf->Cell(3, 1," - ", '0', 0, 'C', 0);
$this->pdf->SetFont('helvetica','0',9);
$this->pdf->Cell(122, 1,$sup_code.' - '.$sup_name , '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->Cell(15, 1, 'No', '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','',9);
$this->pdf->Cell(30, 1,' - '. $no, '0', 0, 'L', 0);
$this->pdf->Ln(6);
$this->pdf->Cell(140, 1, '', '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->Cell(15, 1, 'Date', '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','',9);
$this->pdf->Cell(30, 1,' - '.$date, '0', 0, 'L', 0);
$this->pdf->Ln(6);
$this->pdf->Cell(140, 1, '', '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->Cell(15, 1, 'Ref No', '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','',9);
$this->pdf->Cell(30, 1,' - '. $ref_no, '0', 0, 'L', 0);
$this->pdf->Ln(8);

$this->pdf->SetFont('helvetica','B',9);
$this->pdf->Cell(10, 1, 'No', '1', 0, 'C', 0);
$this->pdf->Cell(15, 1, 'Job No', '1', 0, 'C', 0);
$this->pdf->Cell(60, 1, 'Item', '1', 0, 'C', 0);
$this->pdf->Cell(20, 1, 'Gur No', '1', 0, 'C', 0);
$this->pdf->Cell(20, 1, 'Serial', '1', 0, 'C', 0);
$this->pdf->Cell(60, 1, 'Fault', '1', 0, 'C', 0);

$this->pdf->Ln();
$x =1;
foreach ($jobs as $row) {
$heigh=6*(max(1,$this->pdf->getNumLines($row->item_code .' - '. $row->item_name,60),$this->pdf->getNumLines($row->fault,60)));
$this->pdf->SetFont('helvetica','',9);
$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
$this->pdf->MultiCell(10, $heigh,$x,1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(15, $heigh,$row->job_no,1, 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(60, $heigh, $row->item_code .' - '. $row->item_name,1, 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(20, $heigh,$row->gur_no,1, 'R',  false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(20, $heigh,$row->serial_no,1, 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(60, $heigh,$row->fault,1, 'L', false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);
$x++;	
}
$this->pdf->Ln(6);
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->Cell(20, 1, 'Comment', '0', 0, 'C', 0);
$this->pdf->Cell(3, 1," - ", '0', 0, 'C', 0);
$this->pdf->SetFont('helvetica','0',9);
$heigh=6*(max(1,$this->pdf->getNumLines($row->memo,60)));
$this->pdf->MultiCell(60, $heigh,$row->memo,0, 'L', false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);

$this->pdf->footer_set_services($user);
$this->pdf->Output("Cash Sales_".date('Y-m-d').".pdf", 'I');
?>