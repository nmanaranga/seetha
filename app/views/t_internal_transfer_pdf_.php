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
	$invoice_no=$session[0].$session[1]."IT".$session[2];
}

foreach($sum as $s){
	$bcc=$s->bc;
	$clc=$s->cl;
	$to_bcc=$s->to_bc;
	$to_clc=$s->to_cl;
	$order_no=$s->order_no;
	$trns_mode=$s->trns_mode;
}

$this->pdf->setY(20);

$this->pdf->SetFont('helvetica', 'BU', 11);
//$this->pdf->Cell(50, 1, $r_type.' SALES INVOICE', '0', 0, 'L', 0); 
$orgin_print=$_POST['org_print'];
if($orgin_print=="1"){
	if($trns_mode=='0'){
		$this->pdf->Cell(0, 5, $r_type.' INTERNAL TRANSFER ',0,false, 'C', 0, '', 0, false, 'M', 'M');
	}else if($trns_mode=='1'){
		$this->pdf->Cell(0, 5, $r_type.' DIRECT TRANSFER ',0,false, 'C', 0, '', 0, false, 'M', 'M');
	}else{
		$this->pdf->Cell(0, 5, $r_type.' RETURN TRANSFER ',0,false, 'C', 0, '', 0, false, 'M', 'M');
	}
}else{
	if($trns_mode=='0'){
		$this->pdf->Cell(0, 5, $r_type.' INTERNAL TRANSFER  (Duplicate) ',0,false, 'C', 0, '', 0, false, 'M', 'M');
	}else if($trns_mode=='1'){
		$this->pdf->Cell(0, 5, $r_type.' DIRECT TRANSFER  (Duplicate) ',0,false, 'C', 0, '', 0, false, 'M', 'M');
	}else{
		$this->pdf->Cell(0, 5, $r_type.' RETURN TRANSFER  (Duplicate) ',0,false, 'C', 0, '', 0, false, 'M', 'M');
	}
}
$this->pdf->SetFont('helvetica', '', 9);
$this->pdf->setY(30);
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1, 'Invoice No.', '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(20, 1, $invoice_no, '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', 'BU', 8);
$this->pdf->Cell(70, 1, "Transfer From", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', 'BU', 8);
$this->pdf->Cell(70, 1, "Transfer To", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1, 'Invoice Date', '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(20, 1, $dt, '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1, "Cluster", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(40, 1, $clc, '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(11, 1, "", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1, "Cluster", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(40, 1, $to_clc, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1, 'Order No', '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(20, 1, $order_no, '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1, "Branch", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(30, 1, $bcc, '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(21, 1, "", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1, "Branch", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(30, 1, $to_bcc, '0', 0, 'L', 0);

$this->pdf->Ln();





$this->pdf->Ln();
$this->pdf->SetY(50);
$this->pdf->SetX(25);

// $this->pdf->SetFont('helvetica','B',8);
// $this->pdf->Cell(10, 6,"Sr. No", '1', 0, 'C', 0);
// $this->pdf->Cell(25, 6,"Item Code", '1', 0, 'C', 0);
// $this->pdf->Cell(55, 6,"Item Description", '1', 0, 'C', 0);
// $this->pdf->Cell(25, 6,"Module", '1', 0, 'C', 0);
// $this->pdf->Cell(10, 6,"QTY", '1', 0, 'C', 0);
// $this->pdf->Cell(18, 6,"Unit Price", '1', 0, 'C', 0);
// $this->pdf->Cell(20, 6,"Net Value", '1', 0, 'C', 0);
$this->pdf->Ln();
$this->pdf->Ln();

$x=1;
$code="default";
$amounts=0;

$this->pdf->SetX(15);
foreach($items as $row){
	
	$this->pdf->SetX(15);
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

	
	if($code!='default' && $code==$row->code)
	{
		if($row->sub_item!="")
		{	
			$this->pdf->SetFont('helvetica','',8);
			$this->pdf->Cell(10, 6,"", '1', 0, 'C', 0);
			$this->pdf->Cell(30, 6,$row->sub_item, '1', 0, 'L', 0);
			$this->pdf->Cell(50, 6,$row->des, '1', 0, 'L', 0);
			$this->pdf->Cell(24, 6,"", '1', 0, 'C', 0);
			$this->pdf->Cell(10, 6,$row->sub_qty, '1', 0, 'R', 0);
			$this->pdf->Cell(16, 6,"", '1', 0, 'R', 0);
			$this->pdf->Cell(16, 6,"", '1', 0, 'R', 0);
			$this->pdf->Cell(16, 6,"", '1', 0, 'R', 0);
			$this->pdf->Cell(16, 6,"", '1', 0, 'R', 0);
			$this->pdf->Ln();
			$x=$x-1;
		}

	}
	else
	{

		$this->pdf->GetY();
		$this->pdf->SetFont('helvetica','',8);
		$heigh=5*(max(1,$this->pdf->getNumLines($row->description,50),$this->pdf->getNumLines($row->code,30),$this->pdf->getNumLines($row->model,24)));
		$this->pdf->HaveMorePages($heigh);
		$this->pdf->MultiCell(10, $heigh,$x,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(30, $heigh,$row->code,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(50, $heigh,$row->description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(24, $heigh,$row->model,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(10, $heigh,$row->qty,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(16, $heigh,number_format($row->item_cost,2),  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(16, $heigh,number_format($row->min_price,2),  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(16, $heigh,number_format($row->max_price,2),  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(16, $heigh,number_format($row->amount,2),  1, 'R', 0, 1, '', '', true, 0, false, true, 0);

		$tot_qty+=$row->qty;

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
				$this->pdf->SetX(15);
				if($row->code==$rows->item)
				{					
					$all_serial=$all_serial.$rows->serial_no."   ";
				}
			}


			$this->pdf->GetY();

			$this->pdf->SetFont('helvetica','',8);
			$aa = $this->pdf->getNumLines($all_serial, 55);
			$heigh=5*$aa;

			$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(30, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(50, $heigh, $all_serial, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(24, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(16, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(16, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(16, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(16, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->Ln();




			if($row->sub_item!="")
			{
				$this->pdf->SetX(25);
				$this->pdf->SetFont('helvetica','',8);
				$this->pdf->Cell(10, 6,"", '1', 0, 'C', 0);
				$this->pdf->Cell(30, 6,$row->sub_item, '1', 0, 'L', 0);
				$this->pdf->Cell(50, 6,$row->des, '1', 0, 'L', 0);
				$this->pdf->Cell(24, 6,"", '1', 0, 'C', 0);
				$this->pdf->Cell(10, 6,$row->sub_qty, '1', 0, 'R', 0);
				$this->pdf->Cell(16, 6,"", '1', 0, 'R', 0);
				$this->pdf->Cell(16, 6,"", '1', 0, 'R', 0);
				$this->pdf->Cell(16, 6,"", '1', 0, 'R', 0);
				$this->pdf->Cell(16, 6,"", '1', 0, 'R', 0);
				$this->pdf->Ln();
			}
		}else{




			if($row->sub_item!="")
			{
				$this->pdf->SetX(25);
				$this->pdf->SetFont('helvetica','',8);
				$this->pdf->Cell(10, 6,"", '1', 0, 'C', 0);
				$this->pdf->Cell(30, 6,$row->sub_item, '1', 0, 'L', 0);
				$this->pdf->Cell(50, 6,$row->des, '1', 0, 'L', 0);
				$this->pdf->Cell(24, 6,"", '1', 0, 'C', 0);
				$this->pdf->Cell(10, 6,$row->sub_qty, '1', 0, 'R', 0);
				$this->pdf->Cell(16, 6,"", '1', 0, 'R', 0);
				$this->pdf->Cell(16, 6,"", '1', 0, 'R', 0);
				$this->pdf->Cell(16, 6,"", '1', 0, 'R', 0);
				$this->pdf->Cell(16, 6,"", '1', 0, 'R', 0);
				$this->pdf->Ln();
			}



		}
	}

	
	$code=$row->code;
	$x++;
	$amounts+=$row->amount;

}

$this->pdf->SetX(15);
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(10, 6,"", '0', 0, 'C', 0);
$this->pdf->Cell(30, 6,"", '0', 0, 'L', 0);
$this->pdf->Cell(50, 6,"", '0', 0, 'L', 0);
$this->pdf->Cell(24, 6,"Tot Qty", '0', 0, 'C', 0);
$this->pdf->Cell(10, 6,$tot_qty, 'TB', 0, 'R', 0);
$this->pdf->Cell(16, 6,"", '0', 0, 'R', 0);
$this->pdf->Cell(16, 6,"", '0', 0, 'R', 0);
$this->pdf->Cell(16, 6,"", '0', 0, 'R', 0);
$this->pdf->Cell(16, 6,"", '0', 0, 'R', 0);


$this->pdf->footer_set_internal_transfer($employee,$amounts,$additional,$discount,$user,$credit_card,$amounts);
$this->pdf->Output("t_internal_transfer".date('Y-m-d').".pdf", 'I');

?>

