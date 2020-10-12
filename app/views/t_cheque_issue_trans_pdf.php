<?php
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	$this->pdf->setPrintHeader($header,$type,$duration);
	$this->pdf->setPrintFooter(true);
	//print_r($det);
	$this->pdf->SetFont('helvetica', 'B', 16);
	$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

	foreach($branch as $ress){
		$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
	}

	$cus_name=$cus_address="";

	foreach($det as $row){
		$description=$row->memo;
		$date=$row->ddate;
		$ref_no=$row->ref_no;
		$amount=$row->amount;
		$cus=$row->name;
	}

	foreach($sum as $s){
		$bank=$s->bank_id;
		$b_name=$s->description;
		$date=$s->ddate;
		$amount=$s->settle;
	}


	foreach($user as $row){
	 	$operator=$row->discription;
	 	$tt=$row->action_date;
	}


	foreach($session as $ses){
		$invoice_no=$session[0].$session[1].$session[2];
	}


	$this->pdf->setY(20);
	$this->pdf->SetFont('helvetica', 'BU', 12);
	$this->pdf->Ln();
 	$this->pdf->Cell(0, 5,'CHEQUE WITHDRAW',0,false, 'C', 0, '', 0, false, 'M', 'M');
 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->SetFont('helvetica', '', 10);

 	$this->pdf->Cell(140, 1, "", '0', 0, 'L', 0);
 	$this->pdf->Cell(20, 1, "No ", '0', 0, 'L', 0);
 	$this->pdf->Cell(20, 1, $invoice_no, '0', 0, 'L', 0);


 	$this->pdf->Ln();
 	$this->pdf->Cell(140, 1, "", '0', 0, 'L', 0);
 	$this->pdf->Cell(20, 1, "Date ", '0', 0, 'L', 0);
 	$this->pdf->Cell(20, 1, $date, '0', 0, 'L', 0);


 	$this->pdf->Ln();
 	if($b_name!=""){
	 	$this->pdf->Cell(25, 1, "Bank Account : ", '0', 0, 'L', 0);
	 	$this->pdf->Cell(20, 1, ucfirst($b_name)." - ( ".$bank." )", '0', 0, 'L', 0);
	 	$this->pdf->Cell(1, 1,'', '0', 0, 'L', 0);
	 	$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0); 		
 	}
 	$this->pdf->Ln();		 
 	$this->pdf->Ln();

 	//$this->pdf->Cell(30, 1, "_________________________________________________________________________________________________________________", '0', 0, 'L', 0);
 	$this->pdf->Ln();
 
	$this->pdf->SetX(8);
	$this->pdf->Cell(25, 5,"Account No", '1', 0, 'L', 0);
 	$this->pdf->Cell(40, 5,"Name", '1', 0, 'L', 0);
 	$this->pdf->Cell(25, 5,"To Acc Code", '1', 0, 'L', 0);
 	$this->pdf->Cell(40, 5,"To Acc Name", '1', 0, 'L', 0);
 	$this->pdf->Cell(25, 5,"Cheque No", '1', 0, 'L', 0);
 	$this->pdf->Cell(20, 5,"Date", '1', 0, 'L', 0);
 	$this->pdf->Cell(20, 5,"Amount", '1', 0, 'R', 0);
 	$this->pdf->Ln();


 	foreach ($det as $d) {

		if(strlen($d->acc_name) > strlen($d->to_acc_name)){
			$data=$d->acc_name;
		}else{
			$data=$d->to_acc_name;
		}

        $aa = $this->pdf->getNumLines($data, 40);
        $heigh=5*$aa;	

	    $this->pdf->SetX(8);
	    $this->pdf->SetFont('helvetica', '', 8);
	    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
	    $this->pdf->MultiCell(25, $heigh, $d->account, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	 	$this->pdf->MultiCell(40, $heigh, $d->acc_name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	 	$this->pdf->MultiCell(25, $heigh, $d->to_acc_code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	 	$this->pdf->MultiCell(40, $heigh, $d->to_acc_name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	 	$this->pdf->MultiCell(25, $heigh, $d->cheque, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	 	$this->pdf->MultiCell(20, $heigh, $d->bank_date, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	 	$this->pdf->MultiCell(20, $heigh, $d->amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	    $this->pdf->Ln();			    
	}

 	$this->pdf->Ln();
 	$this->pdf->SetFont('helvetica', 'B', 10);
 	$this->pdf->SetX(8);
 	$this->pdf->Cell(25, 5,"", '0', 0, 'L', 0);
 	$this->pdf->Cell(40, 5,"", '0', 0, 'L', 0);
 	$this->pdf->Cell(25, 5,"", '0', 0, 'L', 0);
 	$this->pdf->Cell(40, 5,"", '0', 0, 'L', 0);
 	$this->pdf->Cell(25, 5,"", '0', 0, 'L', 0);
 	$this->pdf->Cell(20, 5,"Total", '0', 0, 'L', 0);
 	$this->pdf->Cell(20, 5,number_format($amount,2), 'TB', 0, 'R', 0);

 	$this->pdf->SetFont('helvetica', '', 8);
 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);
 	$this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);
 	$this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);
 	//$this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);
	$this->pdf->Ln();
 	$this->pdf->Cell(50, 1, '       Prepaired By', '0', 0, 'L', 0);
 	$this->pdf->Cell(50, 1, '         Checked By', '0', 0, 'L', 0);
 	$this->pdf->Cell(50, 1, ' Cashier Signature', '0', 0, 'L', 0);
 	//$this->pdf->Cell(50, 1, 'Customer Signature', '0', 0, 'L', 0);
 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->Cell(20, 1, "Operator ", '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1, $operator, '0', 0, 'L', 0);
 	$this->pdf->Ln();

 	//$tt = date("H:i");
 	$this->pdf->Cell(20, 1, "Print Time ", '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1, $tt, '0', 0, 'L', 0);
 	$this->pdf->Ln();
	$this->pdf->Output("Cheque Deposit".date('Y-m-d').".pdf", 'I');

?>