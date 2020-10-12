<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//$this->pdf->setPrintHeader($header,$type,$duration);

$this->pdf->setPrintFooter(true);
$this->pdf->setPrintHeader(true);
$this->pdf->setPrintHeader($header,$type); 

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

foreach($cash_sum as $csum){
	$scus_name=$csum->cus_name;
	$scus_address=$csum->cus_address;
	$cust_name=$csum->memo;
}

foreach($session as $ses){
	$invoice_no=$session[0].$session[1].'CA'.$session[2];
}


$customer_name="";
$customer_address="";
$customer_id="";
$cus_status="";

if($cus_id==$cash_customer){

	$customer_name=$scus_name;
	$customer_address=$scus_address;
	$customer_id=$cash_customer;
	$cus_status="Bill To Customer";
}else{
	$customer_name=$cus_name;
	$customer_address=$cus_address;
	$customer_id=$cus_id;
	$cus_status="Customer";
}


$this->pdf->setY(20);

$this->pdf->SetFont('helvetica', 'BU', 10);
$orgin_print=$_POST['org_print'];
if($orgin_print=="1"){
	$this->pdf->Cell(0, 5, $r_type.' INVOICE',0,false, 'C', 0, '', 0, false, 'M', 'M');
}else{
	$this->pdf->Cell(0, 5, $r_type.' INVOICE (Duplicate)',0,false, 'C', 0, '', 0, false, 'M', 'M');	
}
$this->pdf->SetFont('helvetica', 'B', 9);
$this->pdf->setY(30);

$this->pdf->Cell(30, 1, 'Invoice No.', '0', 0, 'L', 0);
$this->pdf->Cell(60, 1, $invoice_no, '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(60, 1, $cus_status, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(30, 1, 'Invoice Date', '0', 0, 'L', 0);
$this->pdf->Cell(60, 1, $dt, '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, "ID No", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $customer_id, '0', 0, 'L', 0);


$this->pdf->Ln();

$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
$this->pdf->Cell(60, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, "Name", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $customer_name, '0', 0, 'L', 0);

$this->pdf->Ln();

$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
$this->pdf->Cell(60, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, "Address", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $customer_address, '0', 0, 'L', 0);

$this->pdf->Ln();

$this->pdf->SetY(56);
$this->pdf->setX(10);
$this->pdf->SetFont('helvetica','B',8);


$x=1;
$amount=(float)0;
$code="default";
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->MultiCell(10, 6,"No",  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(35, 6,"Item", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(85, 6,"Description", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(15, 6,"Qty", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(18, 6,"Price",  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(20, 6,"Total", 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
$this->pdf->SetFont('helvetica','B',8);
foreach($items as $row){
	$this->pdf->setX(10);
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
	if($code!='default' && $code==$row->code && $free==$row->free)
	{						
	}
	else{
		$this->pdf->GetY();
		$this->pdf->setX(10);
		$this->pdf->SetFont('helvetica','B',9);
		$aa = $this->pdf->getNumLines($row->description, 85);
		$heigh=5*$aa;
		$this->pdf->MultiCell(10, $heigh,$x,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(35, $heigh,$row->code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(85, $heigh,$row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(15, $heigh,$row->qty, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		if($row->free=='1'){
			$price='Free';
		}else{
			$price=number_format($row->price,2);
		}
		$this->pdf->MultiCell(18, $heigh,$price,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh,number_format($row->amount,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);

		$ss="";
		foreach ($serial as $rows) {
			if($row->code==$rows->item  && $row->free!='1'){
				$ss=$rows->serial_no;
			}
		}
		if($ss!=""){
			$all_serial="";
			foreach ($serial as $rows) {
				if($row->code==$rows->item){					
					$all_serial=$all_serial.$rows->serial_no."   ";
				}
			}
			$this->pdf->GetY();
			$this->pdf->setX(10);
			$this->pdf->SetFont('helvetica','',9);
			$aa = $this->pdf->getNumLines($all_serial, 85);
			$heigh=5*$aa;
			$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(35, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(85, $heigh, $all_serial, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(15, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(18, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(20, $heigh, "",  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
		}
	}

	$code=$row->code;
	$free=$row->free;
	$x++;
	$amount+=(float)$row->amount;

}

$this->pdf->footer_set_gift_sales($employee,$amount,$additional,$discount,$user,$credit_card,$tot_free_item,$cheque_detail,$credit_card_sum,$other1,$other2,$additional_tot,$cust_name);
$this->pdf->Output("Gift Sales_".date('Y-m-d').".pdf", 'I');

?>