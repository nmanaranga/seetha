<?php
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
        $this->pdf->setPrintFooter(true);
       // print_r($det);
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

 		foreach($branch as $ress){
 		 			$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
 				}

		$cus_name=$cus_address="";

		foreach($det as $row){
		$store=$row->store;
		$date=$row->ddate;
		$officer=$row->name;
		$ref_no=$row->ref_no;
		$memo=$row->memo;
		$nno=$row->nno;
		$amount=$row->amount;
		$sname=$row->sname;
		}
		foreach($session as $ses){
			$invoice_no=$session[0].$session[1].$session[2];
		}

			$this->pdf->setY(20);
        	$this->pdf->SetFont('helvetica', 'BU', 10);
			$this->pdf->Ln();
		 	$this->pdf->Cell(0, 5, ' DAMAGE LIST ',0,false, 'C', 0, '', 0, false, 'M', 'M');

		 	$this->pdf->SetFont('helvetica', '', 8);
		 	$this->pdf->setY(25);
		 	$this->pdf->Ln();
		 	$this->pdf->Cell(30, 1, 'Store ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(90, 1, $sname, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "No ", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $invoice_no, '0', 0, 'L', 0);
		 	
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(90, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Date ", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $date, '0', 0, 'L', 0);
		 	
		 	
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, 'Remark', '0', 0, 'L', 0);
		 	$this->pdf->Cell(90, 1, $memo, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "RefNo", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $ref_no, '0', 0, 'L', 0);
		 	


		 	$this->pdf->Ln();


		 				$this->pdf->SetY(45);
						$this->pdf->SetFont('helvetica','B',8);
                        $this->pdf->Cell(30, 6,"Code", '1', 0, 'C', 0);
						$this->pdf->Cell(90, 6,"Description", '1', 0, 'C', 0);
                        $this->pdf->Cell(20, 6,"Batch No", '1', 0, 'C', 0);
                        $this->pdf->Cell(20, 6,"Qty", '1', 0, 'C', 0);
                        $this->pdf->Cell(30, 6,"Amount", '1', 0, 'C', 0);

                        
                        $this->pdf->Ln();
		 	

                    
                        foreach($det as $row){
                        $this->pdf->GetY();
                        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
						$this->pdf->SetFont('helvetica','',7);
                        $this->pdf->Cell(30, 6,$row->item, '1', 0, 'L', 0);
						$this->pdf->Cell(90, 6,$row->description, '1', 0, 'L', 0);
                        $this->pdf->Cell(20, 6,$row->batch_no, '1', 0, 'C', 0);
                        $this->pdf->Cell(20, 6,number_format($row->dqty,2), '1', 0, 'C', 0);
                        $this->pdf->Cell(30, 6,number_format($row->cost,2), '1', 0, 'R', 0);
                        $this->pdf->Ln();
                   
                        }


            $this->pdf->Ln();
            $this->pdf->Ln();
            $this->pdf->SetFont('helvetica','B',7);
 			$this->pdf->Cell(30, 6,'', '0', 0, 'C', 0);
			$this->pdf->Cell(60, 6,'', '0', 0, 'C', 0);
            $this->pdf->Cell(30, 6,'', '0', 0, 'C', 0);
            $this->pdf->Cell(30, 6,'Net Amount ', '0', 0, 'C', 0);
            $this->pdf->Cell(30, 6,number_format($amount,2), '0', 0, 'C', 0);

		 	
		 	$this->pdf->Ln();
		 	$this->pdf->SetFont('helvetica','',7);
		 	$this->pdf->Cell(30, 1, 'Officer ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $officer, '0', 0, 'L', 0);
		 	$this->pdf->Ln();
		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, '................................', '0', 0, 'L', 0);

		 
		 
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
		 	




	

	//$this->pdf->footerSet($employee,$amount,$additional,$discount,$user);

	$this->pdf->Output("Damage List".date('Y-m-d').".pdf", 'I');

?>