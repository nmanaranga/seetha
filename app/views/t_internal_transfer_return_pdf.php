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
	$invoice_no=$session[0].$session[1]."ITR".$session[2];
}

foreach($sum as $s){
	$t_from =$s->from_store." - ".$s->from_store_name;
	$t_to	=$s->to_store." - ".$s->to_store_name;
	$to_bc=$s->to_bc;
	$name=$s->name;
	$issue_no=$s->issue_no;
}

$this->pdf->setY(20);

$this->pdf->SetFont('helvetica', 'BU', 10);
$orgin_print=$_POST['org_print'];
if($orgin_print=="1"){
	$this->pdf->Cell(0, 5, $r_type.' INTERNAL TRANSFER RETURN',0,false, 'C', 0, '', 0, false, 'M', 'M');
}else{
	$this->pdf->Cell(0, 5, $r_type.' INTERNAL TRANSFER RETURN (Duplicate) ',0,false, 'C', 0, '', 0, false, 'M', 'M');
}
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->setY(30);
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1, 'Invoice No.', '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(20, 1, $invoice_no, '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(10, 1, "", '0', 0, 'L', 0);

$this->pdf->SetFont('helvetica', 'BU', 8);
$this->pdf->Cell(70, 1, "Transfer From", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', 'BU', 8);
$this->pdf->Cell(60, 1, "Transfer To", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1, 'Invoice Date', '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(20, 1, $dt, '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(10, 1, "", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(10, 1, "Store", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(45, 1, $t_from, '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(11, 1, "", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(10, 1, "Store", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(40, 1, $t_to, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1, "Issue No", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(40, 1, $issue_no, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1, "Branch", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(40, 1, $name, '0', 0, 'L', 0);
$this->pdf->Ln();
$this->pdf->Ln();





$this->pdf->Ln();
$this->pdf->SetY(50);
$this->pdf->SetX(25);

$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(10, 6,"Sr. No", '1', 0, 'C', 0);
$this->pdf->Cell(25, 6,"Item Code", '1', 0, 'C', 0);
$this->pdf->Cell(55, 6,"Item Description", '1', 0, 'C', 0);
$this->pdf->Cell(25, 6,"Module", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"QTY", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Unit Price", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Net Value", '1', 0, 'C', 0);
$this->pdf->Ln();

$x=1;
$code="default";

$this->pdf->SetX(25);
$total_amount =0;
foreach($items as $row){
	
	$this->pdf->SetX(25);
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

	
	if($code!='default' && $code==$row->code)
	{
		if($row->sub_item!="")
		{	
			$this->pdf->SetFont('helvetica','',7);
			$aa = $this->pdf->getNumLines($row->des, 55);
			$heigh=5*$aa;
			$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(25, $heigh, $row->sub_item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(55, $heigh, $row->des, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(25, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(15, $heigh, $row->sub_qty, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->Ln();
			$x=$x-1;
		}

	}
	else
	{

		$this->pdf->GetY();
		$this->pdf->SetFont('helvetica','B',7);
		$aa = $this->pdf->getNumLines($row->description, 55);
		$heigh=5*$aa;
		$this->pdf->MultiCell(10, $heigh, $x, 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh, $row->code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(55, $heigh, $row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh, $row->model, 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(15, $heigh, $row->qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh, number_format($row->item_cost,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh, number_format($row->amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->Ln();

		$total_amount +=$row->amount;
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
			$aa = $this->pdf->getNumLines($all_serial, 55);
			$heigh=5*$aa;

			$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(25, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(55, $heigh, $all_serial, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(25, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(18, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->Ln();




			if($row->sub_item!="")
			{
				$this->pdf->SetX(25);
				$this->pdf->SetFont('helvetica','',7);
				$aa = $this->pdf->getNumLines($row->des, 55);
				$heigh=5*$aa;
				$this->pdf->MultiCell(10, $heigh,"", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(35, $heigh,$row->sub_item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(55, $heigh,$row->des, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(25, $heigh,"", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(10, $heigh,$row->sub_qty, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(18, $heigh,"", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(17, $heigh,"",1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(20, $heigh,"",  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
			}
		}else{




			if($row->sub_item!="")
			{
				$this->pdf->SetX(25);
				$this->pdf->SetFont('helvetica','',7);
				$aa = $this->pdf->getNumLines($row->des, 55);
				$heigh=5*$aa;
				$this->pdf->MultiCell(10, $heigh,"", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(35, $heigh,$row->sub_item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(55, $heigh,$row->des, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(25, $heigh,"", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(10, $heigh,$row->sub_qty, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(18, $heigh,"", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(17, $heigh,"",1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(20, $heigh,"",  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
			}



		}
	}

	$code=$row->code;
	$x++;

}
$this->pdf->footer_set_internal_transfer($employee,$amount,$additional,$discount,$user,$credit_card,$total_amount);
$this->pdf->Output("t_internal_transfer".date('Y-m-d').".pdf", 'I');

?>