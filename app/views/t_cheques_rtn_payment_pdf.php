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
		$tcode	 	=$s->t_des;
		$tno 		=$s->trans_no;
		$ref 		=$s->ref_no;
		$chq_no		=$s->cheque_no;
		$acc 		=$s->account;
		$bank       =$s->bank;
		$bank_des   =$s->bank_name;
		$des 	  	=$s->description;
		$dr 		=$s->dr_acc;
		$dr_des 	=$s->dr_des;
		$cr 		=$s->cr_acc;
		$cr_des 	=$s->cr_des;
		$chq_rtn 	=$s->chq_return_amount;
		$other 		=$s->other_amount;
		$tot 		=$s->amount;
		
	}

	foreach($user as $row){
	 	$operator=$row->loginName;
	}

	foreach($session as $ses){
		$invoice_no=$session[0].$session[1].$session[2];
	}

	$this->pdf->setY(20);
	$this->pdf->SetFont('helvetica', 'BU', 12);
	$this->pdf->Ln();
 	$this->pdf->Cell(0, 5,'CHEQUE PAYMENT RETURN ',0,false, 'C', 0, '', 0, false, 'M', 'M');
 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->SetFont('helvetica', '', 10);

 	$this->pdf->SetFont('helvetica', 'B', 10);
 	$this->pdf->Cell(45, 1, "Trans Code : ", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 10);
 	$this->pdf->Cell(93, 1, $tcode, '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1,'', '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', 'B', 10); 
 	$this->pdf->Cell(20, 1, "No ", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 10);
 	$this->pdf->Cell(20, 1, $invoice_no, '0', 0, 'L', 0);

 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->SetFont('helvetica', 'B', 10);
 	$this->pdf->Cell(45, 1, "Trans No ", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 10);
 	$this->pdf->Cell(93, 1, $tno, '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1,'', '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0); 
 	$this->pdf->SetFont('helvetica', 'B', 10);
 	$this->pdf->Cell(20, 1, "Date ", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 10);
 	$this->pdf->Cell(20, 1, $date, '0', 0, 'L', 0);

 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->SetFont('helvetica', 'B', 10);
 	$this->pdf->Cell(45, 1, "Cheque No ", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 10);
 	$this->pdf->Cell(93, 1, $chq_no, '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1,'', '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0); 
 	$this->pdf->SetFont('helvetica', 'B', 10);
 	$this->pdf->Cell(20, 1, "Ref No ", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 10);
 	$this->pdf->Cell(20, 1, $ref, '0', 0, 'L', 0);

 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->SetFont('helvetica', 'B', 10);
 	$this->pdf->Cell(45, 1, "Account No ", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 10);
 	$this->pdf->Cell(113, 1, $acc, '0', 0, 'L', 0);

 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->SetFont('helvetica', 'B', 10);
 	$this->pdf->Cell(45, 1, "Bank ", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 10);
 	$this->pdf->Cell(113, 1, $bank." - ".$bank_des, '0', 0, 'L', 0);
 	

 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->SetFont('helvetica', 'B', 10);
 	$this->pdf->Cell(45, 1, "Description ", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 10);
 	$this->pdf->Cell(113, 1, $des, '0', 0, 'L', 0);
 	
 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->SetFont('helvetica', 'B', 10);
 	$this->pdf->Cell(45, 1, "Dr Account ", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 10);
 	$this->pdf->Cell(113, 1, $dr." - ".$dr_des, '0', 0, 'L', 0);

 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->SetFont('helvetica', 'B', 10);
 	$this->pdf->Cell(45, 1, "Cr Account ", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 10);
 	$this->pdf->Cell(113, 1, $cr." - ".$cr_des, '0', 0, 'L', 0);
 	
 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->SetFont('helvetica', 'B', 10);
 	$this->pdf->Cell(45, 1, "Cheque Return Amount ", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 10);
 	$this->pdf->Cell(113, 1, $chq_rtn, '0', 0, 'L', 0);

	$this->pdf->Ln();
	$this->pdf->Ln();
	$this->pdf->SetFont('helvetica', 'B', 10);
 	$this->pdf->Cell(45, 1, "Other Amount ", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 10);
 	$this->pdf->Cell(113, 1, $other, '0', 0, 'L', 0);

 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->Ln();
 	$this->pdf->Cell(113, 1, "", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', 'B', 15);
 	$this->pdf->Cell(80, 5, "Amount     ". number_format($tot,2)." /=", '1', 0, 'C', 0);
 	
 	$this->pdf->Ln();

 	//$this->pdf->Cell(30, 1, "_________________________________________________________________________________________________________________", '0', 0, 'L', 0);
 	$this->pdf->Ln();
 
	
 	$this->pdf->SetFont('helvetica', '', 8);

 	$this->pdf->Ln();
 	$this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);
 	$this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);
 	$this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);
 	//$this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);
	$this->pdf->Ln();
 	$this->pdf->Cell(50, 1, '       Cashier', '0', 0, 'L', 0);
 	$this->pdf->Cell(50, 1, '         Manager', '0', 0, 'L', 0);
 	$this->pdf->Cell(50, 1, ' Supplier Signature', '0', 0, 'L', 0);
 	//$this->pdf->Cell(50, 1, 'Customer Signature', '0', 0, 'L', 0);
 	$this->pdf->Ln();
  	$this->pdf->Ln();
 	$this->pdf->Cell(20, 1, "Operator ", '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1, $operator, '0', 0, 'L', 0);
 	$this->pdf->Ln();

 	$tt = date("H:i");
 	$this->pdf->Cell(20, 1, "Print Time ", '0', 0, 'L', 0);
 	$this->pdf->Cell(1, 1, $tt, '0', 0, 'L', 0);
 	$this->pdf->Ln();
	$this->pdf->Output("Cheque Payment Return".date('Y-m-d').".pdf", 'I');

?>