<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//$this->pdf->setPrintHeader($header,$type,$duration);
//$this->pdf->setPrintHeader(true);

$this->pdf->setPrintHeader(true,$type); 
$this->pdf->setPrintFooter(true);
$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

foreach($branch as $ress){
	$this->pdf->headerSet4($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

$cus_name=$cus_address="";

foreach($customer as $cus){
	$cus_name=$cus->name;
	$cus_address=$cus->address1." ".$cus->address2." ".$cus->address3;
	$cus_id=$cus->code;
}

foreach($session as $ses){
	$invoice_no=$session[0].$session[1].$session[2];
}

foreach($sum as $s){
	$bcc=$s->bc;
	$clc=$s->cl;
	$to_bcc=$s->to_bc;
	$to_clc=$s->to_cl;
}

$this->pdf->setY(20);

$this->pdf->SetFont('helvetica', 'BU', 10);
//$this->pdf->Cell(50, 1, $r_type.' SALES INVOICE', '0', 0, 'L', 0); 
$orgin_print=$_POST['org_print'];
if($orgin_print=="1"){
$this->pdf->Cell(0, 5, $r_type.' GIFT VOUCHER INTERNAL TRANSFER ',0,false, 'C', 0, '', 0, false, 'M', 'M');
}else{
$this->pdf->Cell(0, 5, $r_type.' GIFT VOUCHER INTERNAL TRANSFER (Duplicate) ',0,false, 'C', 0, '', 0, false, 'M', 'M');
}
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->setY(30);
$this->pdf->Cell(20, 1, 'Invoice No.', '0', 0, 'L', 0);
$this->pdf->Cell(80, 1, $invoice_no, '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, 'Invoice Date', '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, $dt, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->Cell(100, 1, "Transfer From", '0', 0, 'L', 0);
$this->pdf->Cell(60, 1, "Transfer To", '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->Cell(20, 1, "    Cluster", '0', 0, 'L', 0);
$this->pdf->Cell(80, 1, $clc, '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, "    Cluster", '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, $to_clc, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->Cell(20, 1, "    Branch", '0', 0, 'L', 0);
$this->pdf->Cell(80, 1, $bcc, '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, "    Branch", '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, $to_bcc, '0', 0, 'L', 0);

$this->pdf->Ln();
$this->pdf->Ln();
$this->pdf->SetFont('helvetica','B',8);

$this->pdf->MultiCell(10, 6, "No", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(25, 6, "Item Code", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(75, 6, "Description", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(20, 6, "Cost ", 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(20, 6, "Max Price ", 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(15, 6, "Qty ", 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(20, 6, "Amount ", 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
		               		
$this->pdf->SetX(10);
$x=1;
$code="default";

foreach($items as $row){
	$all_serial="";
	$this->pdf->SetX(15);
	$this->pdf->GetY();
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
	$this->pdf->SetFont('helvetica','B',7);
	$aa = $this->pdf->getNumLines($row->description, 75);
    $heigh1=5*$aa;
	$this->pdf->MultiCell(10, $heigh1,$x, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, $heigh1,$row->code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(75, $heigh1,$row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(20, $heigh1,number_format($row->item_cost,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(20, $heigh1,number_format($row->price,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh1,$row->qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
   	$this->pdf->MultiCell(20, $heigh1,number_format($row->amount,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);

   	foreach ($serial as $rows) {
		if($row->code==$rows->item){					
 			$all_serial=$all_serial.$rows->serial_no."   ";
		}
	}
	
    $this->pdf->setX(15);
	$this->pdf->SetFont('helvetica','',9);
    $aa = $this->pdf->getNumLines($all_serial, 75);
    $heigh=5*$aa;
	$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, $heigh, "Serial No", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(75, $heigh, $all_serial, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, "",  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
    $x=$x+1;
}

$this->pdf->footer_set_internal_transfer($employee,$amount,$additional,$discount,$user,$credit_card);
$this->pdf->Output("g_t_internal_transfer".date('Y-m-d').".pdf", 'I');

?>