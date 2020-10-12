<?php

		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		//$this->pdf->setPrintHeader($header,$type,$duration);
		//$this->pdf->setPrintHeader(true,$type);
        $this->pdf->setPrintFooter(true);
                
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

 		foreach($branch as $ress){
 			$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);	
		}

		
		foreach($session as $ses){
			$invoice_no=$session[0].$session[1].$session[2];
		}

		foreach($user as $row){
		 	$operator=$row->loginName;
		}


		foreach($cl as $c){
			$cl_name 		=$c->description;
			$bc_name		=$c->name;
		}

		foreach($sum as $row){
		 	$cl  			= $row->cl;
		 	$bc  			= $row->bc;
		 	$date 			=$row->ddate;
		 	$cash_account 	=$row->cash_acc;
		 	$cash_account_d =$row->description;
		 	$opb 			=$row->opb;
		 	$cash_float 	=$row->cash_float;
		 	$cash_s 		=$row->cash_sales_system;
		 	$cash_m 		=$row->cash_sales_manual;

		 	$rcp_transport	=$row->rcp_transport;
		 	$rcp_advance 	=$row->rcp_advance;
		 	$rcp_other 		=$row->rcp_others;
		 	$rcp_cancel 	=$row->rcp_cancel;
		 	$cash_voucher 	=$row->cash_voucher;
		 	$cash_manual 	=$row->rcp_manual;
		 	$dn5000 		=$row->dn_5000;
		 	$dn2000 		=$row->dn_2000;
		 	$dn1000 		=$row->dn_1000;
		 	$dn500 			=$row->dn_500;
		 	$dn100 			=$row->dn_100;
		 	$dn50 			=$row->dn_50;
		 	$dn20 			=$row->dn_20;
		 	$dn10 			=$row->dn_10;
		 	$i_cash			=$row->inv_cash;
		 	$i_credit		=$row->inv_credit;
		 	$i_cccard		=$row->inv_card;
		 	$i_finance		=$row->inv_finance;
		 	$i_return		=$row->inv_return;
		 	$dn_coin 		=$row->dn_coints;
		 	$rcp_cash		=$row->rcp_cash;
		 	$rcp_card		=$row->rcp_card;
		 	$rcp_cheque		=$row->rcp_cheque;
		 	$shortage		=$row->shortage;
		 }

	

			$this->pdf->setY(20);
        	$this->pdf->SetFont('helvetica', 'BU', 12);
        	$this->pdf->Ln();
		  	$this->pdf->Cell(0, 5,'DAILY SUMMERY',0,false, 'C', 0, '', 0, false, 'M', 'M');

		 	$this->pdf->SetFont('helvetica', 'B', 10);
		 	$this->pdf->setY(25);
		 	$this->pdf->Ln();
		 	$this->pdf->Cell(20, 1, 'No ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->SetFont('helvetica', '', 10);
		 	$this->pdf->Cell(40, 1, $invoice_no, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->SetFont('helvetica', 'B', 10);
		 	$this->pdf->Cell(30, 1, "Cluster", '0', 0, 'L', 0);
		 	$this->pdf->SetFont('helvetica', '', 10);
		 	$this->pdf->Cell(40, 1, $cl." - ".$cl_name , '0', 0, 'L', 0);
		 	
		 	$this->pdf->Ln();
		 	$this->pdf->SetFont('helvetica', 'B', 10);
		 	$this->pdf->Cell(20, 1, 'Date', '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->SetFont('helvetica', '', 10);
		 	$this->pdf->Cell(40, 1, $date, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->SetFont('helvetica', 'B', 10);
		 	$this->pdf->Cell(30, 1, "Branch", '0', 0, 'L', 0);
		 	$this->pdf->SetFont('helvetica', '', 10);
		 	$this->pdf->Cell(40, 1, $bc." - ".$bc_name, '0', 0, 'L', 0);		 	
		 	
		 	$this->pdf->Ln(); 
		 	$this->pdf->Ln();
		 	
		 	$this->pdf->SetFont('helvetica', 'B', 10);
		 	$this->pdf->Cell(20, 1, 'Cash Account - ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->SetFont('helvetica', '', 10);
		 	$this->pdf->Cell(40, 1, $cash_account." - ".$cash_account_d, '0', 0, 'L', 0);
		 	$this->pdf->Ln();
			$this->pdf->SetFont('helvetica', 'B', 10);
			$this->pdf->Cell("", 1, "___________________________________________________________________________________________", '0', 0, 'c', 0);
			$this->pdf->Ln();
			$this->pdf->Ln();
			$this->pdf->SetFont('helvetica', '', 9);
			$this->pdf->Cell(60, 1, 'a) 	Cash in Hand Opening Balance as per the Ledger', '0', 0, 'L', 0);
		 	$this->pdf->Cell(100, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $opb, '0', 0, 'R', 0);

		 	$this->pdf->Ln();
			$this->pdf->Ln();

		 	$this->pdf->Cell(60, 1, 'b) 	Cash in Hand Float', '0', 0, 'L', 0);
		 	$this->pdf->Cell(100, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $cash_float, '0', 0, 'R', 0);

		 	$this->pdf->Ln();
			$this->pdf->Ln();
			$this->pdf->HaveMorePages(6);
			if(empty($bank_entry)){
			 	$this->pdf->Cell(60, 1, 'c) 	Bank Entries', '0', 0, 'L', 0);
			 	$this->pdf->Cell(100, 1, '', '0', 0, 'L', 0);
			 	$this->pdf->Cell(20, 1, "0.00", '0', 0, 'R', 0);
			}else{
				$this->pdf->Cell(60, 1, 'c) 	Bank Entries', '0', 0, 'L', 0);
			 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
			 	$this->pdf->Cell(20, 1, "", '0', 0, 'R', 0);
			} 	
		 	$this->pdf->Ln();
			$this->pdf->Ln();
			$this->pdf->HaveMorePages(6);
			if(!empty($bank_entry)){
				$this->pdf->Cell(20, 1, '', '0', 0, 'R', 0);
				$this->pdf->Cell(30, 1, 'Bank Entry No', '0', 0, 'L', 0);
			 	$this->pdf->Cell(40, 1, 'Bank A/C No', '0', 0, 'L', 0);
			 	$this->pdf->Cell(30, 1,"Amount" , '0', 0, 'R', 0);
				$this->pdf->Ln();
			}
			

			$entry_tot=0;
			foreach($bank_entry as $b){
				$this->pdf->Cell(20, 1, '', '0', 0, 'R', 0);
				$this->pdf->Cell(30, 1, $b->entry_no, '0', 0, 'L', 0);
			 	$this->pdf->Cell(40, 1, $b->bank_acc, '0', 0, 'L', 0);
			 	$this->pdf->Cell(30, 1, $b->amount , '0', 0, 'R', 0);
			 	$entry_tot+=(float)$b->amount;
				$this->pdf->Ln();
			}
			if(!empty($bank_entry)){

				$this->pdf->Cell(19, 1, '', '0', 0, 'L', 0);
				$this->pdf->Cell(19, 1, '---------------------------------------------------------------------------------------------------------------------------------------', '0', 0, 'L', 0);
			 	$this->pdf->Ln();

				$this->pdf->Cell(19, 1, '', '0', 0, 'L', 0);
			 	$this->pdf->Cell(50, 1, ' 	Total', '0', 0, 'L', 0);
			 	$this->pdf->Cell(90, 1, '', '0', 0, 'L', 0);
			 	$this->pdf->Cell(20, 1,  number_format((float)$entry_tot,2), '0', 0, 'R', 0);
			 	$this->pdf->Ln();
		 	}
		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		 	$this->pdf->Cell(60, 1, 'd) 	Cash Sale', '0', 0, 'L', 0);
		 	$this->pdf->Cell(100, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	System', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $cash_s, '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		
			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Manual', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $cash_m, '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

		 	$this->pdf->Cell(19, 1, '', '0', 0, 'L', 0);
			$this->pdf->Cell(19, 1, '----------------------------------------------------------------------------------------------------------------------------------------', '0', 0, 'L', 0);
			$this->pdf->Ln();
		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Total', '0', 0, 'L', 0);
		 	$this->pdf->Cell(90, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1,  number_format((float)$cash_s+(float)$cash_m,2), '0', 0, 'R', 0);

		 	$this->pdf->Ln();
			$this->pdf->Ln();
			$this->pdf->HaveMorePages(6);
		

		 	$this->pdf->Cell(60, 1, 'e) 	Receipts', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Receipts', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	//$this->pdf->Cell(20, 1, number_format((float)$rcp_cash+(float)$rcp_card+(float)$rcp_cheque,2), '0', 0, 'R', 0);
		 	$this->pdf->Cell(20, 1, number_format((float)$rcp_cash,2), '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Transport', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $rcp_transport, '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		
			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Advances', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $rcp_advance, '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		
			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Others', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $rcp_other, '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

		 	$this->pdf->Cell(19, 1, '', '0', 0, 'L', 0);
			$this->pdf->Cell(19, 1, '----------------------------------------------------------------------------------------------------------------------------------------', '0', 0, 'L', 0);
		 	$this->pdf->Ln();
		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Total', '0', 0, 'L', 0);
		 	$this->pdf->Cell(90, 1, '', '0', 0, 'L', 0);
		 	//$this->pdf->Cell(20, 1,  number_format((float)$rcp_cash+(float)$rcp_card+(float)$rcp_cheque+(float)$rcp_transport+(float)$rcp_advance+(float)$rcp_other,2), '0', 0, 'R', 0);
		 	$this->pdf->Cell(20, 1,  number_format((float)$rcp_cash+(float)$rcp_transport+(float)$rcp_advance+(float)$rcp_other,2), '0', 0, 'R', 0);


		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(90, 1, '', '0', 0, 'L', 0);
		 	//$this->pdf->Cell(20, 1,  number_format((float)$cash_s+(float)$cash_m+(float)$rcp_cash+(float)$rcp_card+(float)$rcp_cheque+(float)$rcp_transport+(float)$rcp_advance+(float)$rcp_other,2), 'TB', 0, 'R', 0);
		 	$this->pdf->Cell(20, 1,  number_format((float)$cash_s+(float)$cash_m+(float)$rcp_cash+(float)$rcp_transport+(float)$rcp_advance+(float)$rcp_other,2), 'TB', 0, 'R', 0);


		 	$this->pdf->Ln();
			$this->pdf->Ln();
			$this->pdf->HaveMorePages(6);

		 	$this->pdf->Cell(60, 1, 'f) 	Payments and Reversals', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Receipt Cancellation', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $rcp_cancel, '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		
			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Cash Payment Voucher', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $cash_voucher, '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		
			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	System Receipts raised against the Manual Receipts', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $cash_manual, '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

		 	$this->pdf->Cell(19, 1, '', '0', 0, 'L', 0);
			$this->pdf->Cell(19, 1, '----------------------------------------------------------------------------------------------------------------------------------------', '0', 0, 'L', 0);
		 	$this->pdf->Ln();
		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Total', '0', 0, 'L', 0);
		 	$this->pdf->Cell(90, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, number_format((float)$rcp_cancel+(float)$cash_voucher+(float)$cash_manual,2), '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		 	//$ct=(float)$cash_s+(float)$cash_m+(float)$rcp_cash+(float)$rcp_card+(float)$rcp_cheque+(float)$rcp_transport+(float)$rcp_advance+(float)$rcp_other;
		 	$ct=(float)$cash_s+(float)$cash_m+(float)$rcp_cash+(float)$rcp_transport+(float)$rcp_advance+(float)$rcp_other;
		 	$rt=(float)$rcp_cancel+(float)$cash_voucher+(float)$cash_manual;
		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(90, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1,  number_format((float)$ct-(float)$rt,2), 'TB', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
			$this->pdf->Ln();
			$this->pdf->HaveMorePages(6);

		 	$this->pdf->Cell(60, 1, 'g) 	Cash in Hand Closing Balance as per the Ledger', '0', 0, 'L', 0);
		 	$this->pdf->Cell(35, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'R', 0);


		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
			$this->pdf->Ln();
			$this->pdf->HaveMorePages(6);

		 	$this->pdf->Cell(60, 1, 'h) 	', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, ' 	5000', '0', 0, 'R', 0);
		 	$this->pdf->Cell(25, 1, '*', '0', 0, 'C', 0);
		 	$this->pdf->Cell(5, 1, $dn5000/5000, '0', 0, 'R', 0);
		 	$this->pdf->Cell(40, 1, '=', '0', 0, 'C', 0);
		 	$this->pdf->Cell(20, 1, number_format($dn5000,2) , '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		
			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, ' 	2000', '0', 0, 'R', 0);
		 	$this->pdf->Cell(25, 1, '*', '0', 0, 'C', 0);
		 	$this->pdf->Cell(5, 1, $dn2000/2000, '0', 0, 'R', 0);
		 	$this->pdf->Cell(40, 1, '=', '0', 0, 'C', 0);
		 	$this->pdf->Cell(20, 1, number_format($dn2000,2) , '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, ' 	1000', '0', 0, 'R', 0);
		 	$this->pdf->Cell(25, 1, '*', '0', 0, 'C', 0);
		 	$this->pdf->Cell(5, 1, $dn1000/1000, '0', 0, 'R', 0);
		 	$this->pdf->Cell(40, 1, '=', '0', 0, 'C', 0);
		 	$this->pdf->Cell(20, 1, number_format($dn1000,2) , '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, ' 	500', '0', 0, 'R', 0);
		 	$this->pdf->Cell(25, 1, '*', '0', 0, 'C', 0);
		 	$this->pdf->Cell(5, 1, $dn500/500, '0', 0, 'R', 0);
		 	$this->pdf->Cell(40, 1, '=', '0', 0, 'C', 0);
		 	$this->pdf->Cell(20, 1, number_format($dn500,2) , '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		 	
		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, ' 	100', '0', 0, 'R', 0);
		 	$this->pdf->Cell(25, 1, '*', '0', 0, 'C', 0);
		 	$this->pdf->Cell(5, 1, $dn100/100, '0', 0, 'R', 0);
		 	$this->pdf->Cell(40, 1, '=', '0', 0, 'C', 0);
		 	$this->pdf->Cell(20, 1, number_format($dn100,2) , '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		 	
		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, ' 	50', '0', 0, 'R', 0);
		 	$this->pdf->Cell(25, 1, '*', '0', 0, 'C', 0);
		 	$this->pdf->Cell(5, 1, $dn50/50, '0', 0, 'R', 0);
		 	$this->pdf->Cell(40, 1, '=', '0', 0, 'C', 0);
		 	$this->pdf->Cell(20, 1, number_format($dn50,2) , '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		 	
		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, ' 	20', '0', 0, 'R', 0);
		 	$this->pdf->Cell(25, 1, '*', '0', 0, 'C', 0);
		 	$this->pdf->Cell(5, 1, $dn20/20, '0', 0, 'R', 0);
		 	$this->pdf->Cell(40, 1, '=', '0', 0, 'C', 0);
		 	$this->pdf->Cell(20, 1, number_format($dn20,2) , '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		 	
		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, ' 	10', '0', 0, 'R', 0);
		 	$this->pdf->Cell(25, 1, '*', '0', 0, 'C', 0);
		 	$this->pdf->Cell(5, 1, $dn10/10, '0', 0, 'R', 0);
		 	$this->pdf->Cell(40, 1, '=', '0', 0, 'C', 0);
		 	$this->pdf->Cell(20, 1, number_format($dn10,2) , '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		 	
		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, ' 	Conis', '0', 0, 'R', 0);
		 	$this->pdf->Cell(25, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'R', 0);
		 	$this->pdf->Cell(40, 1, '=', '0', 0, 'C', 0);
		 	$this->pdf->Cell(20, 1, number_format($dn_coin,2) , '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		 	
		 	$total=(float)$dn5000+(float)$dn2000+(float)$dn1000+(float)$dn500+(float)$dn100+(float)$dn50+(float)$dn20+(float)$dn10+(float)$dn_coin;
		 	
		 	$this->pdf->Cell(19, 1, '', '0', 0, 'L', 0);
			$this->pdf->Cell(19, 1, '----------------------------------------------------------------------------------------------------------------------------------------', '0', 0, 'L', 0);
		 	$this->pdf->Ln();
		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, ' 	Total', '0', 0, 'R', 0);
		 	$this->pdf->Cell(25, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(40, 1, '', '0', 0, 'R', 0);
		 	$this->pdf->Cell(65, 1, '', '0', 0, 'C', 0);
		 	$this->pdf->Cell(20, 1, number_format($total,2), 'TB', 0, 'R', 0);
		 	$this->pdf->Ln(5);
		 	$this->pdf->HaveMorePages(5);
			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
			$this->pdf->Cell(10, 1, 'Execess/Shotage', '0', 0, 'L', 0);
			$this->pdf->Cell(25, 1, '', '0', 0, 'L', 0);
			$this->pdf->Cell(40, 1, '', '0', 0, 'R', 0);
			$this->pdf->Cell(65, 1, '', '0', 0, 'C', 0);
			$this->pdf->Cell(20, 1, number_format($shortage,2), 'B', 0, 'R', 0);

			//--------------------------Section I -------------------------------	
		 	$this->pdf->Ln();
			$this->pdf->Ln();
			$this->pdf->HaveMorePages(6);

		 	$this->pdf->Cell(60, 1, 'i) 	Sales', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Cash Sales', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, number_format($i_cash,2), '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		
			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Credit Sales', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, number_format($i_credit,2), '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		
			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Finance Companies', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, number_format($i_finance,2), '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Credit Card Sales', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, number_format($i_cccard,2), '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);		 	

		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Less - Sales Returns', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, number_format($i_return,2), '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

		 	$this->pdf->Cell(19, 1, '', '0', 0, 'L', 0);
			$this->pdf->Cell(19, 1, '----------------------------------------------------------------------------------------------------------------------------------------', '0', 0, 'L', 0);
		 	$this->pdf->Ln();
		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Net Sales', '0', 0, 'L', 0);
		 	$this->pdf->Cell(90, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, number_format(((float)$i_cash+(float)$i_credit+(float)$i_cccard)-(float)$i_return,2), 'TB', 0, 'R', 0);


		 	//--------------------------Section J -------------------------------	
		 	$this->pdf->Ln();
			$this->pdf->Ln();
			$this->pdf->HaveMorePages(6);

		 	$this->pdf->Cell(60, 1, 'j) 	Receipts', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Cash', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, number_format($rcp_cash,2), '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		
			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Credit Cards', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, number_format($rcp_card,2), '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		
			$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Cheques', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, number_format($rcp_cheque,2), '0', 0, 'R', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);

		 	$this->pdf->Cell(19, 1, '', '0', 0, 'L', 0);
			$this->pdf->Cell(19, 1, '----------------------------------------------------------------------------------------------------------------------------------------', '0', 0, 'L', 0);
		 	$this->pdf->Ln();
		 	$this->pdf->HaveMorePages(6);
		 	$this->pdf->Cell(20, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, ' 	Total', '0', 0, 'L', 0);
		 	$this->pdf->Cell(90, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, number_format((float)$rcp_cash+(float)$rcp_card+(float)$rcp_cheque,2), 'TB', 0, 'R', 0);

			//$this->pdf->SetY(69);




$this->pdf->Ln();
$this->pdf->Ln();
$this->pdf->Ln();
$this->pdf->HaveMorePages(6);


	$this->pdf->Output("Daily Summery_".date('Y-m-d').".pdf", 'I');

?>