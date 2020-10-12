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
			$is_cancel = $row->is_cancel;
			$account=$row->description;
			$acc_code=$row->acc_code;
		}

		if($is_cancel==1){
			$this->pdf->image("img/cancel_pdf.png",$x =13,$y = 10,$w = 100,$h = 75,$type = '',$link = '',$align = '',$resize = false,$dpi = 400,$palign = '',$ismask = false,$imgmask = false,$border = 0,$fitbox = false,$hidden = false,$fitonpage = false,$alt = false);
		}

		
		foreach($session as $ses){
			$invoice_no=$session[0].$session[1]."DR".$session[2];
		}
		
		$this->pdf->setY(20);
		$this->pdf->SetFont('helvetica', 'BU', 8);
		
		if($duplicate=="1"){
			$this->pdf->Cell(0, 5, $pdf_page_type.' NOTE ',0,false, 'C', 0, '', 0, false, 'M', 'M');
		}else{
			$this->pdf->Cell(0, 5, $pdf_page_type.' NOTE (DUPLICATE)',0,false, 'C', 0, '', 0, false, 'M', 'M');
		}

		$this->pdf->SetFont('helvetica', 'B', 8);
		$this->pdf->setY(25);
		$this->pdf->Cell(80, 1,'', '0', 0, 'L', 0);
		$this->pdf->Cell(50, 1, "", '0', 0, 'L', 0);
		$this->pdf->Cell(20, 1, "No -", '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, $invoice_no, '0', 0, 'L', 0);
		
		$this->pdf->Ln();

		$this->pdf->Cell(80, 1, '', '0', 0, 'L', 0);
		$this->pdf->Cell(50, 1, '', '0', 0, 'L', 0);
		$this->pdf->Cell(20, 1, "Date - ", '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, $date, '0', 0, 'L', 0);
		
		
		$this->pdf->Ln();

		$this->pdf->Cell(80, 1, '', '0', 0, 'L', 0);
		$this->pdf->Cell(50, 1, "", '0', 0, 'L', 0);
		$this->pdf->Cell(20, 1, "RefNo -", '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, $ref_no, '0', 0, 'L', 0);
		$this->pdf->Ln();
		$this->pdf->Cell(180, 1, "", 'B', 0, 'L', 0);
		$this->pdf->Ln();
		$this->pdf->Ln();
		$this->pdf->Ln();

		$this->pdf->Cell(30, 1, $cus_or_sup.' -', '0', 0, 'L', 0);
		$this->pdf->Cell(50, 1, $cus, '0', 0, 'L', 0);
		$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		$this->pdf->Ln();
		$this->pdf->Ln();

		if($acc_code!=""){
			$this->pdf->Cell(30, 1, 'Opposite A/C -', '0', 0, 'L', 0);
			$this->pdf->Cell(50, 1, $account, '0', 0, 'L', 0);
			$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
			$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
			$this->pdf->Ln();
			$this->pdf->Ln();

		}

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
		$this->pdf->Cell(50, 1, '.........................', '0', 0, 'L', 0);
		$this->pdf->Cell(40, 1, '.........................', '0', 0, 'L', 0);
		$this->pdf->Cell(90, 1, '.........................', '0', 0, 'L', 0);
		$this->pdf->Ln();
		$this->pdf->Cell(50, 1, 'Prepaired By', '0', 0, 'L', 0);
		$this->pdf->Cell(40, 1, 'Officer', '0', 0, 'L', 0);
		$this->pdf->Cell(90, 1, 'Authorized By', '0', 0, 'L', 0);
		
		




		

	//$this->pdf->footerSet($employee,$amount,$additional,$discount,$user);

		$this->pdf->Output("Cash Sales_".date('Y-m-d').".pdf", 'I');

		?>