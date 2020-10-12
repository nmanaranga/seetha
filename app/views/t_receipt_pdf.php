<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);
        //print_r($det);
$this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage("L","A5");   // L or P amd page type A4 or A3

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

		foreach($customer as $cus){
			$cus_name=$cus->name;
			$cus_id=$cus->code;
		}
		foreach($items as $itm){
			$itm_bal=$itm->bal;
			$dueAmount=$itm->balance;
			$memo = $itm->memo;
			$cnote = $itm->pay_cnote;
		}
		foreach($user as $row){
			$operator=$row->discription;
			$tt=$row->action_date;
		}

		foreach($session as $ses){
			$invoice_no=$session[0].$session[1]."RE".$session[2];
		}
		$this->pdf->setY(20);
		$this->pdf->SetFont('helvetica', 'BU', 10);
		$this->pdf->Ln();

		if($duplicate=="1"){
			$this->pdf->Cell(0, 5,'RECEIPT',0,false, 'C', 0, '', 0, false, 'M', 'M');
		}else{
			$this->pdf->Cell(0, 5,'RECEIPT (DUPLICATE)',0,false, 'C', 0, '', 0, false, 'M', 'M');
		}

		$this->pdf->SetFont('helvetica', '', 8);
		$this->pdf->setY(25);
		$this->pdf->Ln();
		$this->pdf->Cell(20, 1, "Receipt No -", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $invoice_no, '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1,'', '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);

		$this->pdf->Ln();


		$this->pdf->Cell(20, 1, "Date - ", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $dt, '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, '', '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, '', '0', 0, 'L', 0);

		$this->pdf->Ln(6);


		$this->pdf->Cell(43, 1, 'Received With Thanks a Sum of  ' , '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $rec, '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, '', '0', 0, 'L', 0);
		$this->pdf->Ln();

		$this->pdf->Cell(8, 1, 'From   ' , '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $cus_name, '0', 0, 'L', 0);
		$this->pdf->Ln();
		$this->pdf->Cell(8, 1, "ID No ", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $cus_id, '0', 0, 'L', 0);
		$this->pdf->Ln(6);
		$this->pdf->Cell(18, 1, "Description ", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $memo, '0', 0, 'L', 0);
		$this->pdf->Ln(6);
		$this->pdf->Cell(1, 1, "Towards Full Payment for In No ", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $payNo, '0', 0, 'L', 0);
		$this->pdf->Ln();

		$this->pdf->Cell(30, 1, "_________________________________________________________________________________________________________________", '0', 0, 'L', 0);
		$this->pdf->Ln();

		$this->pdf->Ln();
		$this->pdf->SetX(140); 
		$this->pdf->Cell(50, 1, "Total Amount Due    Rs", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1,number_format($dueAmount,2), '0', 0, 'R', 0);

		$this->pdf->Ln();
		$this->pdf->SetX(140); 
		$this->pdf->Cell(50, 1, "Amount Received     Rs", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1,number_format($num,2), '0', 0, 'R', 0);

		$this->pdf->Ln();
		$this->pdf->SetX(140); 
		$this->pdf->Cell(50, 1, "Balance Due             Rs", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1,number_format($itm_bal,2), '0', 0, 'R', 0);
		$this->pdf->Ln();

		$this->pdf->SetFont('helvetica', 'B', 8);
		if($pay_cash>0){
			$this->pdf->Cell(30, 1, "Cash     ". number_format($pay_cash,2), '1', 0, 'L', 0);
			$this->pdf->Cell(10, 1,"", '0', 0, 'R', 0);
		}
		if($pay_chq>0){
			$this->pdf->Cell(30, 1, "Cheque     ". number_format($pay_chq,2), '1', 0, 'L', 0);
			$this->pdf->Cell(10, 1,"", '0', 0, 'R', 0);
		}
		if($pay_card>0){
			$this->pdf->Cell(30, 1, "Credit Card     ". number_format($pay_card,2), '1', 0, 'L', 0);
			$this->pdf->Cell(10, 1,"", '0', 0, 'R', 0);
		}
		
		$this->pdf->Ln();

		if($credit_card!=null){
			$this->pdf->GetY();
			$this->pdf->SetFont('helvetica','B',8);
			$this->pdf->Cell(0, 10, 'Credit Card Details','0', 0, 'L', 0);
			$this->pdf->SetFont('helvetica','',8);
			$this->pdf->Ln();
			$this->pdf->Cell(30, 1, "Credit Card No", '1', 0, 'L', 0);
			$this->pdf->Cell(30, 1, "Date", '1', 0, 'L', 0);
			$this->pdf->Cell(30, 1, "Amount", '1', 0, 'R', 0);
			$this->pdf->Ln();
			foreach ($credit_card as $rowss) {
				$this->pdf->Cell(30, 1, $rowss->card_no, '1', 0, 'L', 0);
				$this->pdf->Cell(30, 1, $rowss->ddate, '1', 0, 'L', 0);
				$this->pdf->Cell(30, 1, number_format($rowss->amount,2), '1', 0, 'R', 0);
				$this->pdf->Ln();
			}
			
		}	

		if($cheque!=null){
			$this->pdf->GetY();
			$this->pdf->SetFont('helvetica','B',8);
			$this->pdf->Cell(0, 10, 'Cheque Details','0', 0, 'L', 0);
			$this->pdf->SetFont('helvetica','',8);
			$this->pdf->Ln();
			$this->pdf->Cell(20, 1, "Date", '1', 0, 'L', 0);
			$this->pdf->Cell(35, 1, "Bank", '1', 0, 'L', 0);
			$this->pdf->Cell(65, 1, "Branch", '1', 0, 'L', 0);
			$this->pdf->Cell(20, 1, "Account No", '1', 0, 'L', 0);
			$this->pdf->Cell(20, 1, "Cheque No", '1', 0, 'L', 0);
			$this->pdf->Cell(20, 1, "Amount", '1', 0, 'R', 0);
			$this->pdf->Ln();
			foreach ($cheque as $rowss) {
				$this->pdf->Cell(20, 1, $rowss->cheque_date, '1', 0, 'L', 0);
				$this->pdf->Cell(35, 1, $rowss->bank, '1', 0, 'L', 0);
				$this->pdf->Cell(65, 1, $rowss->branch, '1', 0, 'L', 0);
				$this->pdf->Cell(20, 1, $rowss->account_no, '1', 0, 'L', 0);
				$this->pdf->Cell(20, 1, $rowss->cheque_no, '1', 0, 'L', 0);
				$this->pdf->Cell(20, 1, number_format($rowss->amount,2), '1', 0, 'R', 0);
				$this->pdf->Ln();
			}
		}

		if($pay_pdchq!=0){
			$this->pdf->GetY();
			$this->pdf->SetFont('helvetica','B',8);
			$this->pdf->Cell(0, 10, 'Post Dated Cheque Details','0', 0, 'L', 0);
			$this->pdf->SetFont('helvetica','',8);
			$this->pdf->Ln();
			$this->pdf->Cell(20, 1, "Date", '1', 0, 'L', 0);
			$this->pdf->Cell(25, 1, "Bank", '1', 0, 'L', 0);
			$this->pdf->Cell(40, 1, "Branch", '1', 0, 'L', 0);
			$this->pdf->Cell(20, 1, "Account No", '1', 0, 'L', 0);
			$this->pdf->Cell(20, 1, "Cheque No", '1', 0, 'L', 0);
			$this->pdf->Cell(20, 1, "Amount", '1', 0, 'R', 0);
			$this->pdf->Ln();
			foreach ($pdcheque as $rowss) {
				$this->pdf->Cell(20, 1, $rowss->cheque_date, '1', 0, 'L', 0);
				$this->pdf->Cell(25, 1, $rowss->bank, '1', 0, 'L', 0);
				$this->pdf->Cell(40, 1, $rowss->branch, '1', 0, 'L', 0);
				$this->pdf->Cell(20, 1, $rowss->account_no, '1', 0, 'L', 0);
				$this->pdf->Cell(20, 1, $rowss->cheque_no, '1', 0, 'L', 0);
				$this->pdf->Cell(20, 1, number_format($rowss->amount,2), '1', 0, 'R', 0);
				$this->pdf->Ln();
			}
		}

		/*
			if($pay_gift!=0){
			 	$this->pdf->GetY();
			 	$this->pdf->SetFont('helvetica','B',8);
		 		$this->pdf->Cell(0, 10, 'Gift Voucher Details','0', 0, 'L', 0);
		 		$this->pdf->SetFont('helvetica','',8);
			 	$this->pdf->Ln();
			 	$this->pdf->Cell(25, 1, "Serial No", '1', 0, 'L', 0);
			 	$this->pdf->Cell(25, 1, "Issue Date", '1', 0, 'L', 0);
			 	$this->pdf->Cell(25, 1, "Amount", '1', 0, 'R', 0);
				$this->pdf->Ln();
			 	foreach ($giftvoucher as $rowss) {
					$this->pdf->Cell(25, 1, $rowss->seria, '1', 0, 'L', 0);
				 	$this->pdf->Cell(25, 1, $rowss->branch, '1', 0, 'L', 0);
				 	$this->pdf->Cell(25, 1, number_format($rowss->amount,2), '1', 0, 'R', 0);
			        $this->pdf->Ln();
			    }
			}*/

		if($cnote>0){
			$this->pdf->Ln();
			$this->pdf->SetFont('helvetica','B',8);
			$this->pdf->Cell(20, 1, "Settlement Amount", '0', 0, 'L', 0);
			$this->pdf->Cell(20, 1, number_format($cnote,2), '0', 0, 'R', 0);
		}
		$this->pdf->Ln();
		$this->pdf->Ln();


		$this->pdf->Cell(70, 1, '...................................', '0', 0, 'L', 0);
		$this->pdf->Cell(80, 1, '...................................', '0', 0, 'L', 0);
		$this->pdf->Cell(10, 1, '...................................', '0', 0, 'L', 0);
		$this->pdf->Ln();
		$this->pdf->Cell(70, 1, '       Prepaired By', '0', 0, 'L', 0);
		$this->pdf->Cell(80, 1, ' Cashier Signature', '0', 0, 'L', 0);
		$this->pdf->Cell(10, 1, 'Customer Signature', '0', 0, 'L', 0);



		$this->pdf->Ln();


		$this->pdf->Ln();
		$this->pdf->Cell(20, 1, "Operator ", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $operator, '0', 0, 'L', 0);
		$this->pdf->Ln();

		 	//$tt = date("H:i");


		$this->pdf->Cell(20, 1, "Print Time ", '0', 0, 'L', 0);
		$this->pdf->Cell(1, 1, $tt, '0', 0, 'L', 0);



		$this->pdf->Output("Receipt".date('Y-m-d').".pdf", 'I');

		?>