<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//$this->pdf->setPrintHeader($header,$type,$duration);
//$this->pdf->setPrintHeader(true);

//$this->pdf->setPrintHeader(true,$type); 
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
	$invoice_no=$session[0].$session[1]."ITP".$session[2];
}

foreach($sum as $s){
	$bcc=$s->bc;
	$clc=$s->cl;
	$to_bcc=$s->to_bc;
	$to_clc=$s->to_cl;
	$trns_mode=$s->trns_mode;
}

//$this->pdf->setY(20);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'BU', 10);
//$this->pdf->Cell(50, 1, $r_type.' SALES INVOICE', '0', 0, 'L', 0); 
$orgin_print=$_POST['org_print'];
if($orgin_print=="1"){
	if($trns_mode=='0'){
		$this->pdf->Cell(0, 5, $r_type.' INTERNAL TRANSFER RECEIVE ',0,false, 'C', 0, '', 0, false, 'M', 'M');
	}else{
		$this->pdf->Cell(0, 5, $r_type.' RETURN TRANSFER RECEIVE',0,false, 'C', 0, '', 0, false, 'M', 'M');
	}
}else{
	if($trns_mode=='0'){
		$this->pdf->Cell(0, 5, $r_type.' INTERNAL TRANSFER RECEIVE (Duplicate)',0,false, 'C', 0, '', 0, false, 'M', 'M');
	}else{
		$this->pdf->Cell(0, 5, $r_type.' RETURN TRANSFER RECEIVE (Duplicate)',0,false, 'C', 0, '', 0, false, 'M', 'M');
	}

}
$this->pdf->SetFont('helvetica', '', 8);
//$this->pdf->setY(30);
$this->pdf->Ln();

$this->pdf->Cell(30, 1, 'No.', '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, $invoice_no, '0', 0, 'L', 0);

$this->pdf->Cell(35, 1, "", '0', 0, 'L', 0);

$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(55, 1, "Receive From", '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);

$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Ln();

$this->pdf->Cell(30, 1, 'Date', '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, $dt, '0', 0, 'L', 0);

$this->pdf->Cell(40, 1, "", '0', 0, 'L', 0);

$this->pdf->Cell(20, 1, "Cluster", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $clc, '0', 0, 'L', 0);


$this->pdf->Ln();

$this->pdf->Cell(30, 1, 'Transfer Issue no', '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, $issue_no, '0', 0, 'L', 0);

$this->pdf->Cell(40, 1, "", '0', 0, 'L', 0);

$this->pdf->Cell(20, 1, "Branch", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $bcc, '0', 0, 'L', 0);



$this->pdf->Ln();
$this->pdf->Ln();
//$this->pdf->SetY(50);

$this->pdf->SetX(2);
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(5, 9,"No", '1', 0, 'C', 0);
$this->pdf->Cell(30, 9,"Item Code", '1', 0, 'C', 0);
$this->pdf->Cell(40, 9,"Item Description", '1', 0, 'C', 0);
$this->pdf->Cell(20, 9,"ModelModel", '1', 0, 'C', 0);
$this->pdf->Cell(10, 9,"QTY", '1', 0, 'C', 0);
$this->pdf->Cell(20, 9,"Unit Price", '1', 0, 'C', 0);
$this->pdf->Cell(20, 9,"Last Price", '1', 0, 'C', 0);
$this->pdf->Cell(20, 9,"Sales Price", '1', 0, 'C', 0);
$this->pdf->Cell(10, 9,"L.P.M", '1', 0, 'C', 0);
$this->pdf->Cell(10, 9,"S.P.M", '1', 0, 'C', 0);
$this->pdf->Cell(20, 9,"Amount", '1', 0, 'C', 0);
/*$val="";

foreach ($serial as $value) {
	if ($val!="") {$val.=",";}
	$val.=$value->serial_no;
}*/
//var_dump($val);exit();

$this->pdf->Ln();



$x=1;
$code="default";
$tot=0;
//var_dump($itemsGR);exit();
foreach($itemsGR as $row){

	$lpm=(float)0;
	$lpm=round(((float)$row->min_price-(float)$row->item_cost)/(float)$row->min_price*100,2);

	$spm=(float)0;
	$spm=round(((float)$row->max_price-(float)$row->item_cost)/(float)$row->max_price*100,2);


	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));


//

	$aa = $this->pdf->getNumLines($row->code, 30);
	$bb = $this->pdf->getNumLines($row->description, 40);
	$cc = $this->pdf->getNumLines($row->model, 15);

	$maxH=max($aa,$bb,$cc);
	$heigh=$maxH*(($maxH>1)?5:6);



	$this->pdf->Setx(2);
	$this->pdf->SetFont('helvetica','N',8);


	$this->pdf->MultiCell(5, $heigh,$x, '1', 'C', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(30, $heigh,$row->code, '1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(40, $heigh,$row->description, '1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$row->model, '1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(10, $heigh,$row->qty, '1',  'C', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$row->item_cost, '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$row->min_price, '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$row->max_price, '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(10, $heigh,$lpm, '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(10, $heigh,$spm, '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$row->amount, '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->Ln();

	$all_serial="";

	foreach ($serial as $rows) {
		$this->pdf->SetX(25);
		if($row->code==$rows->item)
		{					
			$all_serial=$all_serial.$rows->serial_no."   ";
		}
	}
	if($all_serial!="")
	{
		$heigh = 3*$this->pdf->getNumLines($all_serial, 40);   
		$this->pdf->Setx(2);
		$this->pdf->SetFont('helvetica','B',8);
		$this->pdf->MultiCell(5, $heigh,"", '1', 'C', false, 0, '', '', true, 0, false, true, $heigh,'M' ,true);
		$this->pdf->MultiCell(30, $heigh,"", '1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,true);
		$this->pdf->MultiCell(40, $heigh,$all_serial, '1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,true);
		$this->pdf->MultiCell(20, $heigh,"", '1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,true);
		$this->pdf->MultiCell(10, $heigh,"", '1',  'C', false, 0, '', '', true, 0, false, true, $heigh,'M' ,true);
		$this->pdf->MultiCell(20, $heigh,"", '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,true);
		$this->pdf->MultiCell(20, $heigh,"", '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,true);
		$this->pdf->MultiCell(20, $heigh,"", '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,true);
		$this->pdf->MultiCell(10, $heigh,"", '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,true);
		$this->pdf->MultiCell(10, $heigh,"", '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,true);
		$this->pdf->MultiCell(20, $heigh,"", '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,true);
		$this->pdf->Ln();

	}

	if($row->sub_item!=""){
		foreach($items as $Srow){
			if($row->code==$Srow->code)

				$heigh = 3*$this->pdf->getNumLines($val, 40);   
			$this->pdf->Setx(2);
			$this->pdf->SetFont('helvetica','N',8);
			$this->pdf->MultiCell(5, $heigh,"", '1', 'C', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
			$this->pdf->MultiCell(30, $heigh,$Srow->sub_item, '1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
			$this->pdf->MultiCell(40, $heigh,$Srow->des, '1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
			$this->pdf->MultiCell(20, $heigh,"", '1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
			$this->pdf->MultiCell(10, $heigh,$Srow->sub_qty, '1',  'C', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
			$this->pdf->MultiCell(20, $heigh,"", '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
			$this->pdf->MultiCell(20, $heigh,"", '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
			$this->pdf->MultiCell(20, $heigh,"", '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
			$this->pdf->MultiCell(10, $heigh,"", '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
			$this->pdf->MultiCell(10, $heigh,"", '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
			$this->pdf->MultiCell(20, $heigh,"", '1',  'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
			$this->pdf->Ln();


		}

	}



	
/*	if($code!='default' && $code==$row->code)
	{
				if($row->sub_item!="")
	     		{	
		        $this->pdf->SetFont('helvetica','',7);
				$this->pdf->Cell(10, 6,"", '1', 0, 'C', 0);
				$this->pdf->Cell(25, 6,$row->sub_item, '1', 0, 'L', 0);
		        $this->pdf->Cell(55, 6,$row->des, '1', 0, 'L', 0);
		        $this->pdf->Cell(25, 6,"", '1', 0, 'C', 0);
		        $this->pdf->Cell(10, 6,$row->sub_qty, '1', 0, 'R', 0);
		        $this->pdf->Cell(18, 6,"", '1', 0, 'R', 0);
		        $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
		        $this->pdf->Ln();
		        $x=$x-1;
		    }
			
	}
	else
	{

			$this->pdf->GetY();
			$this->pdf->SetFont('helvetica','B',7);
	    	$this->pdf->Cell(10, 6,$x, '1', 0, 'C', 0);
	    	$this->pdf->Cell(25, 6,$row->code, '1', 0, 'L', 0);
	    	$this->pdf->Cell(55, 6,$row->description, '1', 0, 'L', 0);
	        $this->pdf->Cell(25, 6,$row->model, '1', 0, 'C', 0);
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
				$this->pdf->Cell(10, 6,"", '1', 0, 'C', 0);
				$this->pdf->Cell(25, 6,$row->sub_item, '1', 0, 'L', 0);
		        $this->pdf->Cell(55, 6,$row->des, '1', 0, 'L', 0);
		        $this->pdf->Cell(25, 6,"", '1', 0, 'C', 0);
		        $this->pdf->Cell(10, 6,$row->sub_qty, '1', 0, 'R', 0);
		        $this->pdf->Cell(18, 6,"", '1', 0, 'R', 0);
		        $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
		        $this->pdf->Ln();
	     	}
		}else{




			  if($row->sub_item!="")
	     	{
	     		$this->pdf->SetX(25);
		        $this->pdf->SetFont('helvetica','',7);
				$this->pdf->Cell(10, 6,"", '1', 0, 'C', 0);
				$this->pdf->Cell(25, 6,$row->sub_item, '1', 0, 'L', 0);
		        $this->pdf->Cell(55, 6,$row->des, '1', 0, 'L', 0);
		        $this->pdf->Cell(25, 6,"", '1', 0, 'C', 0);
		        $this->pdf->Cell(10, 6,$row->sub_qty, '1', 0, 'R', 0);
		        $this->pdf->Cell(18, 6,"", '1', 0, 'R', 0);
	            $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
		        $this->pdf->Ln();
	     	}


	
    	}
    }*/
    $tot+=$row->amount;
    $code=$row->code;
    $x++;

}
//var_dump($tot);
$this->pdf->footer_set_internal_transfer($employee,$amount,$additional,$discount,$user,$credit_card,$tot);
$this->pdf->Output("t_internal_transfer".date('Y-m-d').".pdf", 'I');

?>