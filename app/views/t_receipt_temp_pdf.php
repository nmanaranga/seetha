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

		foreach($sum as $row){
			$date= $row->date;
			$ref_no=$row->ref_no;
			$cus_id=$row->customer;
			$cus_name=$row->cus_name;
			$emp_id=$row->employee;
			$emp_name=$row->emp_name;
			$total=$row->total;

		}
		foreach($session as $ses){
			$invoice_no=$session[0].$session[1].$session[2];
		}

			$this->pdf->setY(20);
        	$this->pdf->SetFont('helvetica', 'BU', 10);
			$this->pdf->Ln();
		 	$this->pdf->Cell(0, 5, ' CHEQUE ACKNOWLEDGEMENT ',0,false, 'C', 0, '', 0, false, 'M', 'M');

		 	$this->pdf->SetFont('helvetica', '', 8);
		 	$this->pdf->setY(25);
		 	$this->pdf->Ln();
		 	$this->pdf->Cell(30, 1, 'Customer ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(90, 1, $cus_name, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "No ", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $invoice_no, '0', 0, 'L', 0);
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(90, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Date ", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $date, '0', 0, 'L', 0);
		 	
		 	
		 	$this->pdf->Ln();
		 	$this->pdf->Cell(30, 1, 'Officer', '0', 0, 'L', 0);
		 	$this->pdf->Cell(90, 1, $emp_name, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "RefNo", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $ref_no, '0', 0, 'L', 0);
		 	$this->pdf->Ln();


			$this->pdf->SetY(45);
			$this->pdf->SetFont('helvetica','B',8);
            $this->pdf->Cell(15, 6,"Bank", '1', 0, 'L', 0);
			$this->pdf->Cell(40, 6,"Bank Name", '1', 0, 'L', 0);
            $this->pdf->Cell(15, 6,"Branch", '1', 0, 'L', 0);
            $this->pdf->Cell(40, 6,"Branch Name", '1', 0, 'L', 0);
            $this->pdf->Cell(20, 6,"Account", '1', 0, 'L', 0);
            $this->pdf->Cell(20, 6,"Cheque No", '1', 0, 'L', 0);
            $this->pdf->Cell(20, 6,"Realize Date", '1', 0, 'L', 0);
            $this->pdf->Cell(20, 6,"Amount  ", '1', 0, 'R', 0);

            
            $this->pdf->Ln();
	

        
            foreach($det as $row){
	            $this->pdf->GetY();
	            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
				$this->pdf->SetFont('helvetica','',7);
	            $this->pdf->Cell(15, 6,$row->bank, '1', 0, 'L', 0);
				$this->pdf->Cell(40, 6,$row->bank_name, '1', 0, 'L', 0);
	            $this->pdf->Cell(15, 6,$row->branch, '1', 0, 'L', 0);
	            $this->pdf->Cell(40, 6,$row->branch_name, '1', 0, 'L', 0);
	            $this->pdf->Cell(20, 6,$row->account, '1', 0, 'L', 0);
	            $this->pdf->Cell(20, 6,$row->cheque_no, '1', 0, 'L', 0);
	            $this->pdf->Cell(20, 6,$row->realize_date, '1', 0, 'L', 0);
	            $this->pdf->Cell(20, 6,$row->amount, '1', 0, 'R', 0);
	            $this->pdf->Ln();
            }
          
            $this->pdf->Ln();
            $this->pdf->SetFont('helvetica','B',8);
 			$this->pdf->Cell(30, 6,'', '0', 0, 'C', 0);
			$this->pdf->Cell(80, 6,'', '0', 0, 'C', 0);
            $this->pdf->Cell(30, 6,'', '0', 0, 'C', 0);
            $this->pdf->Cell(30, 6,'Net Amount ', '0', 0, 'C', 0);
            $this->pdf->Cell(30, 6,number_format($total,2), '0', 0, 'C', 0);
		
		 	$this->pdf->Ln();
		 	$this->pdf->SetFont('helvetica','',7);
		 	$this->pdf->Cell(20, 1, 'Login Name : ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $user, '0', 0, 'L', 0);

		 	$this->pdf->Ln();
		 	$this->pdf->Ln();
		 	$this->pdf->Ln();
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(80, 1, 'Prepaired By', '0', 0, 'L', 0);	
		 	$this->pdf->Cell(80, 1, 'Authorized By', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Recivied By ", '0', 0, 'L', 0);
		 	$this->pdf->Ln();
		 	$this->pdf->Ln();
		 	$this->pdf->Ln();
		 	$this->pdf->Cell(80, 1, '................................', '0', 0, 'L', 0);
		 	$this->pdf->Cell(80, 1, '................................', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '................................', '0', 0, 'L', 0);
		 	

	$this->pdf->Output("Cheque acknowldgement".date('Y-m-d').".pdf", 'I');

?>