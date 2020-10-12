<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->pdf->setPrintFooter(true);
$this->pdf->setPrintHeader(true);
$this->pdf->setPrintHeader($header,$type); 

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page);  
foreach($branch as $ress){
	$this->pdf->headerSet4($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
$cus_name=$cus_address="";
foreach($customer as $cus){
	$cus_name=$cus->name;
	$cus_address=$cus->address1." ".$cus->address2." ".$cus->address3;
	$cus_id=$cus->code;
}

foreach($vender as $ven){
	$v_name=$ven->name;
	$v_address=$ven->address1." ".$cus->address2." ".$cus->address3;
}

foreach($session as $ses){
	$invoice_no=$session[0].$session[1]."PR".$session[2];
}


foreach($items as $add){
	$additional = $add->addi;
	$amount = $add->gross_amount;
	$net = $add->net_amount;
	$grn_no = $add->grn_no;
	$memo = $add->memo;
}

$this->pdf->setY(15);

$this->pdf->SetFont('helvetica', 'BU', 10);
$this->pdf->Ln();
$orgin_print=$_POST['org_print'];

if($duplicate=="1"){
	$this->pdf->Cell(0, 5,'GIFT VOUCHER PURCHASE RETURN NOTE',0,false, 'C', 0, '', 0, false, 'M', 'M');
}else{
	$this->pdf->Cell(0, 5,'GIFT VOUCHER PURCHASE RETURN NOTE (DUPLICATE)',0,false, 'C', 0, '', 0, false, 'M', 'M');
}

$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->setY(25);
$this->pdf->Ln();
$this->pdf->Cell(30, 1, 'Invoice No.', '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, $invoice_no, '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, "DRN No", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $drn, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->Cell(30, 1, 'Date', '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, $dt, '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, "Vendor's Name", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $v_name, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->Cell(30, 1, 'GRN No.', '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, $grn_no, '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, "Address", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $v_address, '0', 0, 'L', 0);

$this->pdf->Ln();
$this->pdf->Ln();
$this->pdf->SetY(51);

$x=1;
$code="default";

foreach($items as $row){
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
		$this->pdf->GetY();
		$this->pdf->SetFont('helvetica','B',7);
		$aa = $this->pdf->getNumLines($row->description, 85);
		$heigh=5*$aa;
		$this->pdf->MultiCell(10, $heigh, $x, 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(30, $heigh, $row->code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(85, $heigh, $row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(15, $heigh, $row->qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh, number_format($row->price,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh, number_format($row->price*$row->qty,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->Ln();

		$ss="";
		foreach ($serial as $rows){
			if($row->code==$rows->item){
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
			$this->pdf->SetFont('helvetica','',7);
			$aa = $this->pdf->getNumLines($all_serial, 85);
			$heigh=5*$aa;
			$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(30, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(85, $heigh, $all_serial, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(15, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->Ln();
			
		}

	$code=$row->code;
	$x++;
}

$this->pdf->footerSetGftRtn($memo,$employee,$amount,$additional,$net,$user,$netString);
$this->pdf->Output("Cash Sales_".date('Y-m-d').".pdf", 'I');

?>