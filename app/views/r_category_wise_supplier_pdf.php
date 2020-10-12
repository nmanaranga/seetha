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

		
		$this->pdf->setY(23);
            $this->pdf->Ln();
        	$this->pdf->SetFont('helvetica', 'BU',10);
		 	$this->pdf->Cell(0, 5, 'Category wise Supplier   ',0,false, 'C', 0, '', 0, false, 'M', 'M');
			$this->pdf->Ln();
            $this->pdf->Ln();



		 				$this->pdf->SetY(35);
		 				$this->pdf->SetX(22);
						$this->pdf->SetFont('helvetica','B',9);
						$this->pdf->Cell(20, 6,"Catregory", '1', 0, 'C', 0);
                        $this->pdf->Cell(25, 6,"Code", '1', 0, 'C', 0);
						$this->pdf->Cell(60, 6,"Name", '1', 0, 'C', 0);
                        $this->pdf->Cell(70, 6,"Address", '1', 0, 'C', 0);
                        $this->pdf->Cell(20, 6,"Type", '1', 0, 'C', 0);
                        $this->pdf->Cell(40, 6,"Email", '1', 0, 'C', 0);                
                        $this->pdf->Cell(20, 6,"TP", '1', 0, 'C', 0);
                        $this->pdf->Ln();

                        foreach ($customer as $value) {
						//$this->pdf->SetY(45);
		 				$this->pdf->SetX(22);
                        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

						$this->pdf->SetFont('helvetica','',9);
						$this->pdf->Cell(20, 6,$value->Category, '1', 0, 'L', 0);
                        $this->pdf->Cell(25, 6,$value->code, '1', 0, 'L', 0);
						$this->pdf->Cell(60, 6,$value->name,'1', 0, 'L', 0);
                        $this->pdf->Cell(70, 6,$value->address1, '1', 0, 'L', 0);
                        $this->pdf->Cell(20, 6,$value->type, '1', 0, 'L', 0);
                        $this->pdf->Cell(40, 6,$value->email, '1', 0, 'L', 0);
                        $this->pdf->Cell(20, 6,$value->tp, '1', 0, 'R', 0);
                       
                   
                        $this->pdf->Ln();
                        	
                        }

                   	




	$this->pdf->Output("Category Wise".date('Y-m-d').".pdf", 'I');

?>