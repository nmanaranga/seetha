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
if($orgin_print=="2"){
$this->pdf->Cell(0, 5, $r_type.'GIFT VOUCHER INTERNAL TRANSFER RECEIVE ',0,false, 'C', 0, '', 0, false, 'M', 'M');
}else{
$this->pdf->Cell(0, 5, $r_type.'GIFT VOUCHER INTERNAL TRANSFER RECEIVE (Duplicate) ',0,false, 'C', 0, '', 0, false, 'M', 'M');

}
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->setY(30);
$this->pdf->Cell(30, 1, 'No.', '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, $invoice_no, '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(60, 1, "Receive From", '0', 0, 'L', 0);

$this->pdf->Ln();

$this->pdf->Cell(30, 1, 'Date', '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, $dt, '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, "Cluster", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $clc, '0', 0, 'L', 0);


$this->pdf->Ln();

$this->pdf->Cell(30, 1, 'Transfer Issue no', '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, $issue_no, '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, "Branch", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $bcc, '0', 0, 'L', 0);

$this->pdf->Ln();

$this->pdf->Ln();
$this->pdf->SetY(50);
$this->pdf->SetX(25);

$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(10, 6,"Sr. No", '1', 0, 'C', 0);
$this->pdf->Cell(25, 6,"Gift Code", '1', 0, 'C', 0);
$this->pdf->Cell(68, 6,"Description", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6,"QTY", '1', 0, 'C', 0);
$this->pdf->Cell(18, 6,"Unit Price", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Net Value", '1', 0, 'C', 0);
$this->pdf->Ln();

$x=1;
$code="default";
$amo =0;
$this->pdf->SetX(25);
foreach($items as $row){
	
$this->pdf->SetX(25);
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

	
	if($code!='default' && $code==$row->code)
	{
	}
	else
	{

			$this->pdf->GetY();
			$this->pdf->SetFont('helvetica','B',7);
	    	$this->pdf->Cell(10, 6,$x, '1', 0, 'C', 0);
	    	$this->pdf->Cell(25, 6,$row->code, '1', 0, 'L', 0);
	    	$this->pdf->Cell(68, 6,$row->description, '1', 0, 'L', 0);
	        $this->pdf->Cell(10, 6,$row->qty, '1', 0, 'R', 0);
	        $this->pdf->Cell(18, 6,number_format($row->item_cost,2), '1', 0, 'R', 0);
	        $this->pdf->Cell(20, 6,number_format($row->amount,2), '1', 0, 'R', 0);
	        $this->pdf->Ln();


		$ss="";
		foreach ($serial as $rows) {
			if($row->code==$rows->item)
		 	{
				$ss=$rows->serial_no;
			}
			
		}

		if($ss!=""){

		$all_serial="";
		
		foreach ($serial as $rows) {
			$this->pdf->SetX(25);
    		if($row->code==$rows->item)
	 		{					
	 			$all_serial=$all_serial.$rows->serial_no."   ";
 			}
		}
		
	
	        $this->pdf->GetY();

			$this->pdf->SetFont('helvetica','',7);
	        $aa = $this->pdf->getNumLines($all_serial, 68);
	        $heigh=5*$aa;

	    	$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	    	$this->pdf->MultiCell(25, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(68, $heigh, $all_serial, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	        $this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	        $this->pdf->MultiCell(18, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	        $this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	        $this->pdf->Ln();

		}else{

    	}
	}
	//var_dump(expression)
	$amo+=$row->amount;
	$code=$row->code;
	$x++;

}
$this->pdf->footer_set_internal_transfer($employee,$amo,$additional,$discount,$user,$credit_card);
$this->pdf->Output("t_internal_transfer".date('Y-m-d').".pdf", 'I');

?>