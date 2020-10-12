<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);
        //print_r($det);
$this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage("L",'A5');   // L or P amd page type A4 or A3

		foreach($branch as $ress){
			$this->pdf->headerSet4($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
		}

		$cus_name=$cus_address="";

		foreach($det as $row){
			$description=$row->memo;
			$date=$row->ddate;
			$ref_no=$row->ref_no;
			$amount=$row->amount;
			$cus=$row->name;
		}

		foreach ($sum as $r) {
			$types=$r->type;
			$note=$r->note;
			$chq_amount =$r->cheque_amount;
			$cash_amount =$r->cash_amount;
			$card_amount =$r->pay_card;

		}

		foreach($supplier as $sup){
			$sup_name=$sup->name;
			$sup_id=$sup->code;
		}
		foreach($items as $itm){
			$itm_bal=$itm->bal;
			$dueAmount=$itm->balance;
		}
		foreach($user as $row){
			$operator=$row->discription;
			$tt=$row->action_date;
		}

		foreach($session as $ses){
			$invoice_no=$session[0].$session[1]."GR".$session[2];
		}
		;
		foreach($session2 as $ses){
			$invoice_no2=$session2[2];
		}


		$this->pdf->setY(15);
		$this->pdf->SetFont('helvetica', 'BU', 10);
		$this->pdf->Ln();
		$orgin_print=$_POST['org_print'];
		if($duplicate=="1"){
			$this->pdf->Cell(0, 5,'GENERAL RECEIPT',0,false, 'C', 0, '', 0, false, 'M', 'M');
		}else{
			$this->pdf->Cell(0, 5,'GENERAL RECEIPT (Duplicate) ',0,false, 'C', 0, '', 0, false, 'M', 'M');
		}
		$this->pdf->Ln();
		$this->pdf->SetFont('helvetica', '', 10);
		 	//$this->pdf->setY(25);

		$this->pdf->Cell(30, 1, "Receipt Type -", '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, ucfirst($types), '0', 0, 'L', 0);
		$this->pdf->Cell(2, 1,'', '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);

		$this->pdf->Cell(20, 1, "Date - ", '0', 0, 'L', 0);
		$this->pdf->Cell(20, 1, $ddate, '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, '', '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, '', '0', 0, 'L', 0);
		

		$this->pdf->Ln();
		$this->pdf->Cell(30, 1, "Receipt No -", '0', 0, 'L', 0);
		$this->pdf->Cell(33, 1, $invoice_no, '0', 0, 'L', 0);
		$this->pdf->Cell(20, 1,'No-', '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $invoice_no2, '0', 0, 'L', 0);
		$this->pdf->Ln();		 
		

		$this->pdf->Cell(30, 1, "_________________________________________________________________________________________________________________", '0', 0, 'L', 0);
		$this->pdf->Ln();
		$this->pdf->Cell(15, 1, 'Note  : ' , '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $note, '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, '', '0', 0, 'L', 0);
		$this->pdf->Ln();
		
		$this->pdf->Cell(50, 1, 'Received With Thanks a Sum of  ' , '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $rec, '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, '', '0', 0, 'L', 0);
		$this->pdf->Ln();
		
		

		$this->pdf->Cell(10, 1, 'To   ' , '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $acc_code." | ".$acc_des, '0', 0, 'L', 0);

		$this->pdf->Ln();
		$this->pdf->Ln();

		$this->pdf->HaveMorePages(6);
		$this->pdf->SetX(35);
		$this->pdf->Cell(30, 1,"Account Code", '1', 0, 'L', 0);
		$this->pdf->Cell(70, 1,"Name", '1', 0, 'L', 0);
		$this->pdf->Cell(30, 1,"Amount", '1', 0, 'R', 0);
		$this->pdf->Ln();


		foreach ($dets as $value) {			   
			$this->pdf->SetX(35);
			$this->pdf->SetFont('helvetica', '', 9);
			$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
			$this->pdf->Cell(30, 5, $value->acc_code, '1', 0, 'L', 0);
			$this->pdf->Cell(70, 5, $value->description, '1', 0, 'L', 0);
			$this->pdf->Cell(30, 5, $value->amount, '1', 0, 'R', 0);
			$this->pdf->Ln();			    
		}
		
		$this->pdf->HaveMorePages(6);

		if(!empty($cheque)){	
			$this->pdf->SetX(15);
			$this->pdf->SetFont('helvetica', 'B', 8);
			$this->pdf->Cell(10, 1, 'Cheque Details   ' , '0', 0, 'L', 0);
			$this->pdf->Ln();
			$this->pdf->SetFont('helvetica', '', 8);
			$this->pdf->Cell(20, 1,"Cheque No", '1', 0, 'L', 0);
			$this->pdf->Cell(50, 1,"Bank", '1', 0, 'L', 0);
			$this->pdf->Cell(20, 1,"Cheque Date", '1', 0, 'L', 0);
			$this->pdf->Cell(20, 1,"Amount", '1', 0, 'R', 0);
			$this->pdf->Ln();

			foreach ($cheque as $chq) {			   
				$this->pdf->SetX(15);
				$this->pdf->SetFont('helvetica', '', 8);
				$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
				$this->pdf->Cell(20, 5, $chq->cheque_no, '1', 0, 'L', 0);
				$this->pdf->Cell(50, 5, $chq->bank." | ".$chq->description, '1', 0, 'L', 0);
				$this->pdf->Cell(20, 5, $chq->cheque_date, '1', 0, 'L', 0);
				$this->pdf->Cell(20, 5, $chq->amount, '1', 0, 'R', 0);
				$this->pdf->Ln();			    
			}

		}
		$this->pdf->HaveMorePages(6);
		if(!empty($card)){	
			$this->pdf->SetX(15);
			$this->pdf->SetFont('helvetica', 'B', 8);
			$this->pdf->Cell(10, 1, 'Credit Card Details   ' , '0', 0, 'L', 0);
			$this->pdf->Ln();
			$this->pdf->Cell(30, 1, "Credit Card No", '1', 0, 'L', 0);
			$this->pdf->Cell(30, 1, "Date", '1', 0, 'L', 0);
			$this->pdf->Cell(30, 1, "Amount", '1', 0, 'R', 0);
			$this->pdf->Ln();
			foreach ($card as $rowss) {
				$this->pdf->Cell(30, 1, $rowss->card_no, '1', 0, 'L', 0);
				$this->pdf->Cell(30, 1, $rowss->date, '1', 0, 'L', 0);
				$this->pdf->Cell(30, 1, number_format($rowss->amount,2), '1', 0, 'R', 0);
				$this->pdf->Ln();
			}

		}

		
		$this->pdf->Ln(); 
		$this->pdf->HaveMorePages(6);
		if($chq_amount>0){ 
			$this->pdf->SetFont('helvetica', 'B', 8);
			$this->pdf->Cell(25, 1,"Cheque Amount", '0', 0, 'L', 0);
			$this->pdf->Cell(30, 1,number_format($chq_amount,2), '1', 0, 'R', 0);
			$this->pdf->Cell(10, 1,'', '0', 0, 'L', 0);
		}
		if($cash_amount>0){
			$this->pdf->Cell(25, 1,"Cash", '0', 0, 'L', 0);
			$this->pdf->Cell(30, 1,number_format($cash_amount,2), '1', 0, 'R', 0);
			$this->pdf->Cell(10, 1,'', '0', 0, 'L', 0);
		}
		if($card_amount>0){
			$this->pdf->Cell(25, 1,"Credit Card", '0', 0, 'L', 0);
			$this->pdf->Cell(30, 1,number_format($card_amount,2), '1', 0, 'R', 0);
		}
		
		$this->pdf->Ln();
		$this->pdf->Ln(); 
		$this->pdf->SetFont('helvetica', 'B', 15);
		$this->pdf->Cell(50, 1, "Rs. ".number_format($tot,2), '1', 0, 'L', 0);
		$this->pdf->Ln();

		$this->pdf->SetFont('helvetica', '', 8);
		
		$this->pdf->Ln();
		$this->pdf->HaveMorePages(5);
		$this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);
		$this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);
		$this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);
		$this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);
		$this->pdf->Ln();
		$this->pdf->Cell(50, 1, '       Prepaired By', '0', 0, 'L', 0);
		$this->pdf->Cell(50, 1, '         Checked By', '0', 0, 'L', 0);
		$this->pdf->Cell(50, 1, ' Cashier Signature', '0', 0, 'L', 0);
		$this->pdf->Cell(50, 1, 'Customer Signature', '0', 0, 'L', 0);
		
		
		$this->pdf->Ln();
		$this->pdf->HaveMorePages(4);
		 	//$this->pdf->Ln();
		$this->pdf->Cell(20, 1, "Operator ", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $operator, '0', 0, 'L', 0);
		$this->pdf->Ln();

		 	//$tt = date("H:i");
		$this->pdf->HaveMorePages(4);
		
		$this->pdf->Cell(20, 1, "Print Time ", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $tt, '0', 0, 'L', 0);
		$this->pdf->Ln();



	//$this->pdf->footerSet($employee,$amount,$additional,$discount,$user);

		$this->pdf->Output("Voucher".date('Y-m-d').".pdf", 'I');

		?>