<?php
	
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
		$this->pdf->setPrintHeader(true,$type);
        $this->pdf->setPrintFooter(true);
        //$this->pdf->setPrintHeader(true);
        
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

 		foreach($branch as $ress){
 			$this->pdf->headerSet4($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);	
		}

		foreach($sum as $sup){
			$date=$sup->ddate;
			$inv_no=$sup->inv_no;
			$sup_id=$sup->supp_id;
			$sup_name=$sup->name;
			$sup_address=$sup->address;
			$sup_email=$sup->email;
			$store=$sup->store;
			$store_name=$sup->description;
			$net=$sup->net_amount;
			$memo=$sup->memo;
		}


		foreach($session as $ses){
			$invoice_no=$session[0].$session[1]."GR".$session[2];
		}

		foreach($user as $row){
		 	$operator=$row->loginName;
		}


			$this->pdf->setY(15);
        	$this->pdf->SetFont('helvetica', 'BU', 10);
        	$this->pdf->Ln();
        	$orgin_print=$_POST['org_print'];
		if($orgin_print=="1"){
		  	$this->pdf->Cell(0, 5,'GIFT VOUCHER GOODS RECEIVED NOTE ',0,false, 'C', 0, '', 0, false, 'M', 'M');
		  }else{
		  	$this->pdf->Cell(0, 5,'GIFT VOUCHER GOODS RECEIVED NOTE (Duplicate) ',0,false, 'C', 0, '', 0, false, 'M', 'M');
		  }
		 	$this->pdf->SetFont('helvetica', '', 9);
		 	$this->pdf->setY(25);
		 	$this->pdf->Ln();
		 	$this->pdf->Cell(20, 1, 'GRN No ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(40, 1, $invoice_no, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	
		 	$this->pdf->Cell(30, 1, "Vendor Name ", '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1, $sup_name." - ".$sup_id, '0', 0, 'L', 0);
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(20, 1, 'Date', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(40, 1, $date, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	
		 	$this->pdf->Cell(30, 1, "Address ", '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1, $sup_address, '0', 0, 'L', 0);
		 	$this->pdf->Ln();


		 	$this->pdf->Cell(20, 1, 'Invoice No ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(40, 1, $inv_no, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	
		 	$this->pdf->Cell(30, 1, "Email  ", '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1, $sup_email, '0', 0, 'L', 0);
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(19, 1, 'Store ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(6, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(40, 1, $store." - ".$store_name, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(20, 1, 'PO Date ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(40, 1, $po_dt, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	
			$this->pdf->Ln();
			
			$this->pdf->SetY(69);

$x=1;
$code="default";


foreach($det as $row){

	$hi = $this->pdf->getNumLines($row->description, 55);
	$row_hight=6*$hi;

	$this->pdf->SetX(5);

	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
	
	if($code!='default' && $code==$row->code)
	{
	}
	else
	{

			$this->pdf->GetY();
			$this->pdf->SetFont('helvetica','',9);
			
				$this->pdf->MultiCell(10, $row_hight, $x, 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(35, $row_hight, $row->code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(55, $row_hight, $row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		    	$this->pdf->MultiCell(20, $row_hight, $row->qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		    	$this->pdf->MultiCell(25, $row_hight, number_format($row->price,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		    	$this->pdf->MultiCell(25, $row_hight, number_format($row->max_price,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		    	$this->pdf->MultiCell(25, $row_hight, number_format($row->amount,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
			
	
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
    		if($row->code==$rows->item)
	 		{					
	 			$all_serial=$all_serial.$rows->serial_no."   ";
 			}
		}
		
	        $this->pdf->GetY();
	        $this->pdf->SetX(5);
			$this->pdf->SetFont('helvetica','',8);
	        $aa = $this->pdf->getNumLines($all_serial, 55);
	        $heigh=5*$aa;
	        if($p_serial=="1"){
	        $this->pdf->MultiCell(10, $heigh, "", 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(35, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(55, $heigh, $all_serial, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	    	$this->pdf->MultiCell(20, $heigh, "", 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	    	$this->pdf->MultiCell(25, $heigh, "", 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	    	$this->pdf->MultiCell(25, $heigh, "", 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	    	$this->pdf->MultiCell(25, $heigh, "", 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
			}
		}
	}

	$code=$row->code;
	$x++;
}
			

    $this->pdf->SetFont('helvetica','',9);
	

    $this->pdf->footerSetgrnGift($net);

    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->Ln();
 	$this->pdf->Cell(20, 1, "Note ", '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1,$memo, '0', 0, 'L', 0);

 	$this->pdf->SetFont('helvetica','',8);
 	$this->pdf->Ln();
    $this->pdf->Ln();
 	$this->pdf->Cell(20, 1, "Operator ", '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1, $operator, '0', 0, 'L', 0);
 	$this->pdf->Ln();

 	$tt = date("H:i");

 	
 	$this->pdf->Cell(20, 1, "Print Time ", '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1, $tt, '0', 0, 'L', 0);
 	$this->pdf->Ln();

	$this->pdf->Output("Gift_Voucher_Purchase_".date('Y-m-d').".pdf", 'I');

?>