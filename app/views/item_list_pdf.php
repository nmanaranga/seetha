<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
        $this->pdf->setPrintFooter(true);
        //print_r($customer);
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

 		foreach($branch as $ress){
 				$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
 			}

		
		$this->pdf->setY(25);

        	$this->pdf->SetFont('helvetica', 'IBU',12);
		 	$this->pdf->Cell(0, 5, 'Item List  ',0,false, 'C', 0, '', 0, false, 'M', 'M');
			$this->pdf->Ln();$this->pdf->Ln();



		 				$this->pdf->SetY(35);
		 				$this->pdf->SetX(25);
						$this->pdf->SetFont('helvetica','IB',8);
					
                        $this->pdf->Cell(29, 6,"Code", '1', 0, 'C', 0);
						$this->pdf->Cell(65, 6,"Description", '1', 0, 'C', 0);
						$this->pdf->Cell(20, 6,"Brand", '1', 0, 'C', 0);
						$this->pdf->Cell(25, 6,"Model", '1', 0, 'C', 0);
						$this->pdf->Cell(18, 6,"Max Price", '1', 0, 'C', 0);
						$this->pdf->Cell(18, 6,"Min Price", '1', 0, 'C', 0);
						$this->pdf->Cell(30, 6,"Category ", '1', 0, 'C', 0);
						$this->pdf->Cell(15, 6,"Dep", '1', 0, 'C', 0);
						$this->pdf->Cell(30, 6,"Department Name", '1', 0, 'C', 0);
                        $this->pdf->Ln();

                        foreach ($customer as $value) {
						
		 				$this->pdf->SetX(25);
						$this->pdf->SetFont('helvetica','',8);
						
                        $this->pdf->Cell(29, 6,$value->code, '1', 0, 'L', 0);
						$this->pdf->Cell(65, 6,$value->description,'1', 0, 'L', 0);
						$this->pdf->Cell(20, 6,$value->brand, '1', 0, 'L', 0);
						$this->pdf->Cell(25, 6,$value->model,'1', 0, 'L', 0);
						$this->pdf->Cell(18, 6,$value->max_price, '1', 0, 'R', 0);
						$this->pdf->Cell(18, 6,$value->min_price,'1', 0, 'R', 0);
						$this->pdf->Cell(30, 6,$value->Category , '1', 0, 'L', 0);
						$this->pdf->Cell(15, 6,$value->department,'1', 0, 'L', 0);
						$this->pdf->Cell(30, 6,$value->Department_Name,'1', 0, 'L', 0);
                        $this->pdf->Ln();
                        	
                        }

                   	



	//$this->pdf->footerSet($employee,$amount,$additional,$discount,$user);
	$this->pdf->Output("Cash Sales_".date('Y-m-d').".pdf", 'I');

?>