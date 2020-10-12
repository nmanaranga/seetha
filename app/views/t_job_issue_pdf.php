<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->pdf->setPrintHeader(true);
$this->pdf->setPrintHeader($header,$type); 
$this->pdf->AddPage($orientation,$page);


foreach ($branch as $ress) {
	$this->pdf->headerSet4($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach($sum as $row){
	$cus_id = $row->cus_id;
	$cus_name = $row->name;
	$ref_no = $row->ref_no;
	$memo = $row->memo;
	$date=$row->ddate;
	$drn = $row->drn_no;
	$inv_no2 = $row->inv_no2;
	$tp = $row->tp;
	$amount=$row->amount;
	$advance=$row->advance;
	$balance=$row->balance;
	$discount=$row->discount;
}

$align_h=$this->utility->heading_align();

$this->pdf->setY(15);
$this->pdf->SetFont('helvetica', 'BU', 10);
$this->pdf->Cell(0, 5,'ISSUE TO CUSTOMER',0,false, $align_h, 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();


$this->pdf->SetFont('helvetica', 'B', 9);
$this->pdf->Cell(140, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(15, 1, "Date" , '0', 0, 'L', 0);
$this->pdf->Cell(5, 1," - ", '', 0, 'C', 0);
$this->pdf->SetFont('helvetica', '0', 9);
$this->pdf->Cell(20, 1,$date, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 9);
$this->pdf->Cell(140, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(15, 1, "TP" , '0', 0, 'L', 0);
$this->pdf->Cell(5, 1," - ", '', 0, 'C', 0);
$this->pdf->SetFont('helvetica', '0', 9);
$this->pdf->Cell(20, 1,$tp, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 9);
$this->pdf->Cell(20, 1, "Customer", '0', 0, 'L', 0);
$this->pdf->Cell(3, 1," - ", '', 0, 'C', 0);
$this->pdf->SetFont('helvetica', '0', 9);
$this->pdf->Cell(112, 1,$cus_id." - ".$cus_name, '0', 0, 'L', 0);
$this->pdf->Ln(8);

$this->pdf->setX(15);
$this->pdf->SetFont('helvetica', 'B', 9);
$this->pdf->Cell(15, 6,"Job No" , '1', 0, 'C', 0);
$this->pdf->Cell(90, 6,"Item" , '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Model" , '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Price" , '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Discount" , '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Amount" , '1', 0, 'C', 0);

$this->pdf->Ln();
$this->pdf->SetFont('helvetica', '0', 9);
$x =1;
$this->pdf->setX(15);
foreach($det as $dt){
	$heigh=6*(max(1,$this->pdf->getNumLines($dt->Item_name,90)));
	$this->pdf->SetFont('helvetica','',9);
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
	$this->pdf->MultiCell(15, $heigh,$dt->job_no,1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(90, $heigh,$dt->Item_name,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$dt->model,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$dt->price,1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$dt->discount,1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$dt->job_amt,1, 'R',false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);

	$x++;	
}

$this->pdf->Ln(6);

$this->pdf->SetFont('helvetica','B',9);
$this->pdf->Cell(130, 6,"" , '0', 0, 'R', 0);
$this->pdf->Cell(30, 6,"Total" , '0', 0, 'L', 0);
$this->pdf->Cell(25, 6,number_format($amount,2) , 'TB', 0, 'R', 0);
$this->pdf->Ln();
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->Cell(130, 6,"" , '0', 0, 'R', 0);
$this->pdf->Cell(30, 6,"Discount Amount" , '0', 0, 'L', 0);
$this->pdf->Cell(25, 6,number_format($discount,2) , 'TB', 0, 'R', 0);
$this->pdf->Ln();
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->Cell(130, 6,"" , '0', 0, 'R', 0);
$this->pdf->Cell(30, 6,"Advance Amount" , '0', 0, 'L', 0);
$this->pdf->Cell(25, 6,number_format($advance,2) , 'TB', 0, 'R', 0);
$this->pdf->Ln();
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->Cell(130, 6,"" , '0', 0, 'R', 0);
$this->pdf->Cell(30, 6,"Balance Amount" , '0', 0, 'L', 0);
$this->pdf->Cell(25, 6,number_format($balance,2) , 'TB', 0, 'R', 0);

$this->pdf->Ln(6);
$this->pdf->footer_set_services($user);
$this->pdf->Output("Service Reject_".date('Y-m-d').".pdf", 'I');
?>