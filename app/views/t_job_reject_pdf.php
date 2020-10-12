<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->pdf->setPrintHeader(true);
$this->pdf->setPrintHeader($header,$type); 
$this->pdf->AddPage($orientation,$page);


foreach ($branch as $ress) {
	$this->pdf->headerSet4($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach($reject as $row){
  $cus_id = $row->cus_id;
  $cus_name = $row->name;
  $no = $row->nno;
  $ref_no = $row->ref_no;
  $memo = $row->memo;
  $t_date=$row->t_date;
}



$this->pdf->setY(15);
$this->pdf->SetFont('helvetica', 'B', 10);
$this->pdf->Cell(0, 5,'Service Reject Note',0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();
$this->pdf->Cell(0, 1,"", 'T', 1, 'L', 0);
$this->pdf->SetFont('helvetica', 'B', 9);
$this->pdf->Cell(20, 1, "Customer", '0', 0, 'L', 0);
$this->pdf->Cell(3, 1," - ", '', 0, 'C', 0);
$this->pdf->SetFont('helvetica', '0', 9);
$this->pdf->Cell(112, 1,$cus_id." - ".$cus_name, '0', 0, 'L', 0);


$this->pdf->SetFont('helvetica', 'B', 9);
$this->pdf->Cell(15, 1, "No" , '0', 0, 'L', 0);
$this->pdf->Cell(3, 1," - ", '0', 0, 'C', 0);
$this->pdf->SetFont('helvetica', '0', 9);
$this->pdf->Cell(22, 1,$no, '0', 0, 'L', 0);
$this->pdf->Ln(5);
$this->pdf->SetFont('helvetica', 'B', 9);
$this->pdf->Cell(135, 1," ", '0', 0, 'L', 0);
$this->pdf->Cell(15, 1, "Date" , '0', 0, 'L', 0);
$this->pdf->Cell(3, 1," - ", '', 0, 'C', 0);
$this->pdf->SetFont('helvetica', '0', 9);
$this->pdf->Cell(22, 1,$t_date, '0', 0, 'L', 0);
$this->pdf->Ln(5);
$this->pdf->SetFont('helvetica', 'B', 9);
$this->pdf->Cell(135, 1," ", '0', 0, 'L', 0);
$this->pdf->Cell(15, 1, "Ref No" , '0', 0, 'L', 0);
$this->pdf->Cell(3, 1," - ", '', 0, 'C', 0);
$this->pdf->SetFont('helvetica', '0', 9);
$this->pdf->Cell(22, 1,$ref_no, '0', 0, 'L', 0);
$this->pdf->Ln(6);
$this->pdf->SetFont('helvetica', 'B', 9);
$this->pdf->Cell(10, 6,"No" , '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"Job No" , '1', 0, 'C', 0);
$this->pdf->Cell(22, 6,"Receive Date" , '1', 0, 'C', 0);
$this->pdf->Cell(68, 6,"Item" , '1', 0, 'C', 0);
$this->pdf->Cell(60, 6,"Reject Reason" , '1', 0, 'C', 0);
$this->pdf->Ln();
$this->pdf->SetFont('helvetica', '0', 9);
$x =1;

foreach($reject as $rj){

$heigh=6*(max(1,$this->pdf->getNumLines($rj->item_code." - ".$rj->Item_name,68)));
$this->pdf->HaveMorePages($heigh);
$this->pdf->SetFont('helvetica','',9);
$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
$this->pdf->MultiCell(10, $heigh,$x,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(15, $heigh,$rj->job_no,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(22, $heigh,$rj->r_date,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(68, $heigh,$rj->item_code ." - ". $rj->Item_name,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(60, $heigh,$rj->reason,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);

 $x++;	
}

$this->pdf->Ln(6);
$this->pdf->footer_set_services($user);
$this->pdf->Output("Service Reject_".date('Y-m-d').".pdf", 'I');
?>