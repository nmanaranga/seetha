<?php
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	$this->pdf->setPrintHeader($header,$type,$duration);
	$this->pdf->setPrintFooter(true);
	$this->pdf->SetFont('helvetica', 'B', 16);
	$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

	foreach($branch as $ress){
		$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
	}

	$cus_name=$cus_address="";

	foreach($sum as $s){
		$no 		=$s->nno;
		$date 		=$s->ddate;
		$merchant 	=$s->merchant_id;
		$des 		=$s->description;
		$bank 		=$s->bank_acc;
		$bank_des 	=$s->bank_des;
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
 	$this->pdf->Cell(0, 5,'CREDIT CARD RECONCILLATION',0,false, 'C', 0, '', 0, false, 'M', 'M');
 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->SetFont('helvetica', '', 10);

 	$this->pdf->Cell(25, 1, "Merchant ID : ", '0', 0, 'L', 0);
 	$this->pdf->Cell(113, 1, $merchant, '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1,'', '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0); 
 	$this->pdf->Cell(20, 1, "No ", '0', 0, 'L', 0);
 	$this->pdf->Cell(20, 1, $invoice_no, '0', 0, 'L', 0);


 	$this->pdf->Ln();
 	$this->pdf->Cell(25, 1, "Bank Account : ", '0', 0, 'L', 0);
 	$this->pdf->Cell(113, 1, $bank." - ( ".$bank_des." )", '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1,'', '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0); 
 	$this->pdf->Cell(20, 1, "Date ", '0', 0, 'L', 0);
 	$this->pdf->Cell(20, 1, $date, '0', 0, 'L', 0);

 	$this->pdf->Ln();

 	$this->pdf->Ln();
 	$this->pdf->Cell(25, 1, "Description : ", '0', 0, 'L', 0);
 	$this->pdf->Cell(113, 1, $des, '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1,'', '0', 0, 'L', 0);

 	$this->pdf->Ln();

 	//$this->pdf->Cell(30, 1, "_________________________________________________________________________________________________________________", '0', 0, 'L', 0);
 	$this->pdf->Ln();
 
	$this->pdf->SetX(8);
	$this->pdf->Cell(55, 5,"Trans Type", '1', 0, 'L', 0);
 	$this->pdf->Cell(20, 5,"No", '1', 0, 'R', 0);
 	$this->pdf->Cell(30, 5,"Credit Card No", '1', 0, 'L', 0);
 	$this->pdf->Cell(30, 5,"Amount", '1', 0, 'R', 0);
 	$this->pdf->Cell(30, 5,"Commission", '1', 0, 'R', 0);
 	$this->pdf->Cell(30, 5,"Balance", '1', 0, 'R', 0);

 	$this->pdf->Ln();

 	$tot_amoun = (float)0;
	$tot_com   = (float)0;
	$tot_bal   = (float)0;

 	foreach ($det as $d) {

        $aa = $this->pdf->getNumLines($d->description, 40);
        $heigh=5*$aa;	

	    $this->pdf->SetX(8);
	    $this->pdf->SetFont('helvetica', '', 8);
	    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
	    $this->pdf->MultiCell(55, $heigh, ucfirst(strtolower($d->description)), 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	 	$this->pdf->MultiCell(20, $heigh, $d->no, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	 	$this->pdf->MultiCell(30, $heigh, $d->credit_card_no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	 	$this->pdf->MultiCell(30, $heigh, $d->amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	 	$this->pdf->MultiCell(30, $heigh, $d->commision, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	 	$this->pdf->MultiCell(30, $heigh, $d->balance, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	    $this->pdf->Ln();

	    $tot_amount += (float)$d->amount;
		$tot_com	+= (float)$d->commision;
		$tot_bal	+= (float)$d->balance;		    
	}

 	$this->pdf->Ln();
 	$this->pdf->SetFont('helvetica', 'B', 10);
 	$this->pdf->SetX(8);

 	$this->pdf->Cell(55, 5,"", '0', 0, 'L', 0);
 	$this->pdf->Cell(20, 5,"", '0', 0, 'R', 0);
 	$this->pdf->Cell(30, 5,"Total", '0', 0, 'R', 0);
 	$this->pdf->Cell(30, 5,number_format($tot_amount,2), 'TB', 0, 'R', 0);
 	$this->pdf->Cell(30, 5,number_format($tot_com,2), 'TB', 0, 'R', 0);
 	$this->pdf->Cell(30, 5,number_format($tot_bal,2), 'TB', 0, 'R', 0);

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