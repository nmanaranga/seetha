<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);
$this->pdf->SetFont('helvetica', 'B', 16);
	$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

	foreach($branch as $ress){
		$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
	}

	foreach($sum as $row){
		$fdate=$row->date_from;
		$tdate=$row->date_to;
		$acc_id=$row->account_id;
		$acc_name=$row->description;
	}

	foreach($det as $s){
		$op_bal		=$s->opening_bal;
		$col_bal	=$s->closing_bal;
		$acc    	=$s->account;
	}

	foreach($user as $row){
		$operator=$row->loginName;
	}

	$this->pdf->SetFont('helvetica', '', 9);
	$this->pdf->Ln();
	$this->pdf->Cell(20, 1, "As at date : ", '0', 0, 'L', 0);
	$this->pdf->Cell(20, 1, $tdate, '0', 0, 'L', 0);

	$this->pdf->Ln();

	$this->pdf->Cell(20, 1, "Bank : ", '0', 0, 'L', 0);
	$this->pdf->Cell(113, 1, $acc_name, '0', 0, 'L', 0);

	$this->pdf->Ln();

	$this->pdf->Cell(20, 1, "Account : ", '0', 0, 'L', 0);
	$this->pdf->Cell(113, 1, $acc_id, '0', 0, 'L', 0);

	$this->pdf->Ln();
	$this->pdf->Ln();

	$complex_cell_border = array(
		'TB' => array('width' => 0.1, 'color' => array(0,0,0), 'dash' => 2.1, 'cap' => 'butt'),
		'1' => array('width' => 0.1, 'color' => array(0,0,0), 'dash' => 0.1, 'cap' => 'butt'),
	);

	$this->pdf->SetFillColor(206, 206, 206);
	$this->pdf->SetFont('helvetica', 'B', 10);
	$this->pdf->Cell(150, 1, "BALANCE AS PER THE SYSTEM  ", 0, 0, 'L', 1);
	$this->pdf->Cell(30, 3, number_format($op_balance,2), 0, 0, 'R', 1);


	$this->pdf->Ln();
	$this->pdf->Ln();
	$this->pdf->SetFont('helvetica', 'BU', 10);
	$this->pdf->Cell(180, 5,"ADD", '0', 0, 'L', 0);
	$this->pdf->Ln();
	$this->pdf->Ln();
	$u_final=$n_final=$e_final=$d_final=$b_final=$bb_final=$close_bal=0;

	if(!(empty($unidentified))){
		$u_tot=0;
		$this->pdf->SetFont('helvetica', 'B', 8);
		$this->pdf->Cell(20, 5,"", '0', 0, 'L', 0);
		$this->pdf->Cell(180, 5,"UNIDENTIFIEDD DEPOSIT", '0', 0, 'L', 0);
		$this->pdf->Ln();
		foreach($unidentified as $row){
			$this->pdf->SetFont('helvetica', '', 8);
			$aa   = $this->pdf->getNumLines($row->description, 80);
			$heigh=5*$aa;
			$this->pdf->MultiCell(30, $heigh, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(80, $heigh, $row->description, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(25, $heigh, number_format($row->dr_amount,2), 0, 'R', 0, 1, '', '', true, 0, false, true, 0);		
			$u_tot+=(float)$row->dr_amount;
		} 
		$u_final = (float)$u_tot+(float)$op_balance;
		$this->pdf->SetFont('helvetica', 'B', 8);
		$this->pdf->MultiCell(110, $heigh, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh, number_format($u_tot,2), 'T', 'R', 0,0, '', '', true, 0, false, true, 0);	
		$this->pdf->MultiCell(25, $heigh, "", 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh, number_format($u_final,2), 'T', 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->Ln();
		$this->pdf->Ln();
	}
	$u_final = (float)$u_tot+(float)$op_balance;

	if(!(empty($chq_not_presented))){
		$n_tot=0;
		$this->pdf->SetFont('helvetica', 'B', 8);
		$this->pdf->Cell(20, 5,"", '0', 0, 'L', 0);
		$this->pdf->Cell(180, 5,"CHEQUE ISSUED BUT NOT PRESENTED TO THE BANK", '0', 0, 'L', 0);
		$this->pdf->Ln();

		foreach($chq_not_presented as $row){
			$this->pdf->SetFont('helvetica', '', 8);
			$aa   = $this->pdf->getNumLines($row->description, 80);
			$heigh=5*$aa;
			$this->pdf->MultiCell(30, $heigh, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(80, $heigh, $row->description, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(25, $heigh, number_format($row->cr,2), 0, 'R', 0, 1, '', '', true, 0, false, true, 0);		
			$n_tot+=(float)$row->cr;
		} 
		$n_final =(float)$n_tot+(float)$u_final;

		$this->pdf->SetFont('helvetica', 'B', 8);
		$this->pdf->MultiCell(110, $heigh, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh, number_format($n_tot,2), 'T', 'R', 0,0, '', '', true, 0, false, true, 0);	
		$this->pdf->MultiCell(25, $heigh, "", 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh, number_format($n_final,2), 'T', 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->Ln();
		$this->pdf->Ln();
	}
	$n_final =(float)$n_tot+(float)$u_final;

	if(!(empty($error_made_cr))){
		$e_tot=0;
		$this->pdf->SetFont('helvetica', 'B', 8);
		$this->pdf->Cell(20, 5,"", '0', 0, 'L', 0);
		$this->pdf->Cell(180, 5,"ERRORS MADE BY THE BANK", '0', 0, 'L', 0);
		$this->pdf->Ln();

		foreach($error_made_cr as $row){
			$this->pdf->SetFont('helvetica', '', 8);
			$aa   = $this->pdf->getNumLines($row->description, 80);
			$heigh=5*$aa;
			$this->pdf->MultiCell(30, $heigh, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(80, $heigh, $row->description, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(25, $heigh, number_format($row->cr_amount,2), 0, 'R', 0, 1, '', '', true, 0, false, true, 0);		
			$e_tot+=(float)$row->cr_amount;
		} 

		$e_final=(float)$e_tot+(float)$n_final;
		$this->pdf->SetFont('helvetica', 'B', 8);
		$this->pdf->MultiCell(110, $heigh, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh, number_format($e_tot,2), 'T', 'R', 0,0, '', '', true, 0, false, true, 0);	
		$this->pdf->MultiCell(25, $heigh, "", 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh, number_format($e_final,2), 'T', 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->Ln();
		$this->pdf->Ln();
	}

	$e_final=(float)$e_tot+(float)$n_final;

	$this->pdf->SetFont('helvetica', 'BU', 10);
	$this->pdf->Cell(180, 5,"LESS", '0', 0, 'L', 0);
	$this->pdf->Ln();
	$this->pdf->Ln();

	if(!(empty($deposit_not_realized))){
		$d_tot=0;
		$this->pdf->SetFont('helvetica', 'B', 8);
		$this->pdf->Cell(20, 5,"", '0', 0, 'L', 0);
		$this->pdf->Cell(180, 5,"DEPOSIT MADE BUT NOT REALIZED TO THE ACCOUNT", '0', 0, 'L', 0);
		$this->pdf->Ln();

		foreach($deposit_not_realized as $row){
			$this->pdf->SetFont('helvetica', '', 8);
			$aa   = $this->pdf->getNumLines($row->description, 80);
			$heigh=5*$aa;
			$this->pdf->MultiCell(30, $heigh, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(80, $heigh, $row->description, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(25, $heigh, number_format($row->dr,2), 0, 'R', 0, 1, '', '', true, 0, false, true, 0);		
			$d_tot+=(float)$row->dr;
		} 
		$d_final =(float)$e_final-(float)$d_tot;
		$this->pdf->SetFont('helvetica', 'B', 8);
		$this->pdf->MultiCell(110, $heigh, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh, number_format($d_tot,2), 'T', 'R', 0,0, '', '', true, 0, false, true, 0);	
		$this->pdf->MultiCell(25, $heigh, "", 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh, number_format($d_final,2), 'T', 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->Ln();
	}
	$d_final =(float)$e_final-(float)$d_tot;

	if(!(empty($error_made_dr))){
		$b_tot=0;
		$this->pdf->SetFont('helvetica', 'B', 8);
		$this->pdf->Cell(20, 5,"", '0', 0, 'L', 0);
		$this->pdf->Cell(180, 5,"ERRORS MADE BY THE BANK", '0', 0, 'L', 0);
		$this->pdf->Ln();
		foreach($error_made_dr as $row){
			$this->pdf->SetFont('helvetica', '', 8);
			$aa   = $this->pdf->getNumLines($row->description, 80);
			$heigh=5*$aa;
			$this->pdf->MultiCell(30, $heigh, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(80, $heigh, $row->description, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(25, $heigh, number_format($row->dr_amount,2), 0, 'R', 0, 1, '', '', true, 0, false, true, 0);		
			$b_tot+=(float)$row->dr_amount;
		} 
		$b_final=(float)$d_final-(float)$b_tot;
		$this->pdf->SetFont('helvetica', 'B', 8);
		$this->pdf->MultiCell(110, $heigh, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh, number_format($b_tot,2), 'T', 'R', 0,0, '', '', true, 0, false, true, 0);	
		$this->pdf->MultiCell(25, $heigh, "", 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh, number_format($b_final,2)."aa", 'T', 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->Ln();
		$this->pdf->Ln();
	}
	$b_final=(float)$d_final-(float)$b_tot;

	if(!(empty($bank_chargers))){
		$bb_tot=0;
		$this->pdf->SetFont('helvetica', 'B', 8);
		$this->pdf->Cell(20, 5,"", '0', 0, 'L', 0);
		$this->pdf->Cell(180, 5,"BANK CHARGERS", '0', 0, 'L', 0);
		$this->pdf->Ln();
		foreach($bank_chargers as $row){
			$this->pdf->SetFont('helvetica', '', 8);
			$aa   = $this->pdf->getNumLines($row->description, 80);
			$heigh=5*$aa;
			$this->pdf->MultiCell(30, $heigh, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(80, $heigh, $row->description, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(25, $heigh, number_format($row->dr_amount,2), 0, 'R', 0, 1, '', '', true, 0, false, true, 0);		
			$bb_tot+=(float)$row->dr_amount;
		} 
		$bb_final=(float)$b_final-(float)$bb_tot;
		$this->pdf->SetFont('helvetica', 'B', 8);
		$this->pdf->MultiCell(110, $heigh, "", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh, number_format($bb_tot,2), 'T', 'R', 0,0, '', '', true, 0, false, true, 0);	
		$this->pdf->MultiCell(25, $heigh, "", 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh, number_format($bb_final,2), 'T', 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->Ln();
		$this->pdf->Ln();
	}
	$bb_final=(float)$b_final-(float)$bb_tot;
	$close_bal = $bb_final;
	$this->pdf->SetFillColor(206, 206, 206);
	$this->pdf->SetFont('helvetica', 'B', 10);
	$this->pdf->Cell(150, 1, "BALANCE AS PER THE BANK STATEMENT  ", 0, 0, 'L', 1);
	$this->pdf->Cell(30, 3, number_format($close_bal,2), 0, 0, 'R', 1);

	$this->pdf->Ln(20);
	$this->pdf->SetFont('helvetica', 'B', 10);
	$this->pdf->Cell(90, 1, "........................................", 0, 0, 'C', 0);
	$this->pdf->Cell(90, 3, "........................................", 0, 0, 'C', 0);
	$this->pdf->Ln();
	$this->pdf->SetFont('helvetica', 'B', 10);
	$this->pdf->Cell(90, 1, "     Prepared by    ", 0, 0, 'C', 0);
	$this->pdf->Cell(90, 3, "     Checked by     ", 0, 0, 'C', 0);


	$this->pdf->Output("Bank Reconsilation_".date('Y-m-d').".pdf", 'I');

	?>