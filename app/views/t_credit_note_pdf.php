<?php
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
        $this->pdf->setPrintFooter(true);
        //print_r($det);
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

 		foreach($branch as $ress){
 			$this->pdf->headerSet4($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
		}

		$cus_name=$cus_address="";

		foreach($det as $row){
		$description=$row->memo;
		$acc_des = $row->description;
		$date=$row->ddate;
		$ref_no=$row->ref_no;
		$amount=$row->amount;
		$cus=$row->name;
		$is_cancel = $row->is_cancel;
		}

		if($is_cancel==1){
			$this->pdf->image("img/cancel_pdf.png",$x =13,$y = 10,$w = 100,$h = 75,$type = '',$link = '',$align = '',$resize = false,$dpi = 400,$palign = '',$ismask = false,$imgmask = false,$border = 0,$fitbox = false,$hidden = false,$fitonpage = false,$alt = false);
		}

		foreach($session as $ses){
			$invoice_no=$session[0].$session[1]."CN".$session[2];

		}

			$this->pdf->setY(20);
        	$this->pdf->SetFont('helvetica', 'BU', 10);
			$orgin_print=$_POST['org_print'];
			if($duplicate=="1"){
		 	$this->pdf->Cell(0, 5, $pdf_page_type.' Note ',0,false, 'C', 0, '', 0, false, 'M', 'M');
		 	}else{
		 	$this->pdf->Cell(0, 5, $pdf_page_type.' Note (Duplicate) ',0,false, 'C', 0, '', 0, false, 'M', 'M');
		 	}
		 	$this->pdf->SetFont('helvetica', 'B', 8);
		 	$this->pdf->setY(25);
		 	$this->pdf->Cell(80, 1,'', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(12, 1, "No -", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $invoice_no, '0', 0, 'L', 0);
		 	
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(80, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(12, 1, "Date - ", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $date, '0', 0, 'L', 0);
		 	
		 	
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(80, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(12, 1, "RefNo -", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $ref_no, '0', 0, 'L', 0);
		 	$this->pdf->Ln();
		 	$this->pdf->Cell(180, 1, "", 'B', 0, 'L', 0);
		 	$this->pdf->Ln();
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, $cus_or_sup.' -', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, $cus, '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Ln();
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, 'Opposite A/C -' , '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, $acc_des, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Ln();
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, 'Description -' , '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, $description, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Ln();

		 	

		 	$this->pdf->Cell(180, 1, "", 'B', 0, 'L', 0);
		 	$this->pdf->Ln();
		 	$this->pdf->Ln();
		 	

		 	$this->pdf->Cell(80, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(15, 1, 'Amount', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, number_format($amount,2), '0', 0, 'L', 0);

		 	$this->pdf->SetY(45);                        
            $this->pdf->Ln();
		 	
            $this->pdf->Ln();
            $this->pdf->Ln();
            $this->pdf->Ln();
		 	$this->pdf->Ln();

		 	// $this->pdf->Cell(30, 1, 'Officer ', '0', 0, 'L', 0);
		 	// $this->pdf->Cell(30, 1, '........................................', '0', 0, 'L', 0);
		 
		 	$this->pdf->Ln();
		 	$this->pdf->Ln();
		 	$this->pdf->Ln();
		 	$this->pdf->Ln();
		 	$this->pdf->Cell(30, 1, '.........................', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '.........................', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '.........................', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, '..................................', '0', 0, 'L', 0);
			$this->pdf->Ln();
		 	$this->pdf->Cell(36, 1, 'Prepaired By', '0', 0, 'L', 0);
		 	$this->pdf->Cell(24, 1, 'Officer', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, 'Authorized By', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, 'Customer Signature', '0', 0, 'L', 0);
		 
	$this->pdf->Output("Cash Sales_".date('Y-m-d').".pdf", 'I');

?>