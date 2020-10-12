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

        	$this->pdf->SetFont('helvetica', 'BU',10);
		 	$this->pdf->Cell(0, 5, 'Customer Town List   ',0,false, 'C', 0, '', 0, false, 'M', 'M');
			$this->pdf->Ln();$this->pdf->Ln();



		 				$this->pdf->SetY(45);
		 				$this->pdf->SetX(60);
						$this->pdf->SetFont('helvetica','B',8);
					
                        $this->pdf->Cell(15, 6,"Code", '1', 0, 'C', 0);
						$this->pdf->Cell(70, 6,"Name", '1', 0, 'C', 0);
                        $this->pdf->Ln();

                        foreach ($customer as $value) {
						
		 				$this->pdf->SetX(60);
						$this->pdf->SetFont('helvetica','',8);
						$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
                        $this->pdf->Cell(15, 6,$value->code, '1', 0, 'L', 0);
						$this->pdf->Cell(70, 6,$value->description,'1', 0, 'L', 0);
                        $this->pdf->Ln();
                        	
                        }

                   	



	//$this->pdf->footerSet($employee,$amount,$additional,$discount,$user);
	$this->pdf->Output("Customer Town List".date('Y-m-d').".pdf", 'I');

?>