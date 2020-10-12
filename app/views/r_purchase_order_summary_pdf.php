<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
        $this->pdf->setPrintFooter(true);
       //$this->pdf->setPrintHeader(true);
        //print_r($purchase);
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

 		foreach($branch as $ress){
 				$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
 			}

		
		$this->pdf->setY(25);

        	$this->pdf->SetFont('helvetica', 'BU',10);
		 	$this->pdf->Cell(0, 5, 'PURCHASE ORDER SUMMARY',0,false, 'C', 0, '', 0, false, 'M', 'M');
		 	$this->pdf->Ln();
		 	$this->pdf->Ln();

		 	$this->pdf->SetFont('helvetica', '',9);
		 	$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'C', 0, '', 0, false, 'M', 'M');
			$this->pdf->Ln();
			$this->pdf->Ln();
			$i=0;
			$a=-1;
			$j=-1;
		 	$Goss=array();
			$net=array();
		 	$my_array=array();
		 	foreach ($purchase as $value) {
                  $my_array[]=$value->name;
			}
			foreach ($sum as $sum){
                        	$Goss[]=$sum->gsum;
                        	$net[]=$sum->nsum;
                        	$a++;
                        }

          

		 	$this->pdf->SetFont('helvetica', 'B', 8);

		 	$this->pdf->Ln();
				//$this->pdf->SetY(45);
		 				$this->pdf->SetX(20);
						$this->pdf->SetFont('helvetica','B',8);
                        $this->pdf->Cell(10, 6,"No", '1', 0, 'C', 0);
                        $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
                         $this->pdf->Cell(20, 6,"Deliver Date", '1', 0, 'C', 0);
						$this->pdf->Cell(65, 6,"Supplier", '1', 0, 'C', 0);
                       //$this->pdf->Cell(22, 6,"Gross Amount", '1', 0, 'C', 0);
                       // $this->pdf->Cell(20, 6,"Additional", '1', 0, 'C', 0);
                       //$this->pdf->Cell(22, 6,"Discount", '1', 0, 'C', 0);
                        $this->pdf->Cell(22, 6,"Net Amount", '1', 0, 'C', 0);
                        $this->pdf->Cell(35, 6,"Ship To", '1', 0, 'C', 0);
                        $this->pdf->Ln();


	foreach ($purchase as $value) {	

				
		 		$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        
                $aa = $this->pdf->getNumLines($value->ship, 35);
               
                
                    $heigh=5*$aa;
                

          
                $this->pdf->SetX(20);
                $this->pdf->SetFont('helvetica','',8);
                $this->pdf->MultiCell(10, $heigh, $value->nno,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $value->ddate,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $value->deliver_date,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(65, $heigh, $value->supplier." | ".$value->name,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                //$this->pdf->MultiCell(22, $heigh, $value->gross_amount,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                //$this->pdf->MultiCell(20, $heigh, $value->other,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               //$this->pdf->MultiCell(22, $heigh, $value->discount,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(22, $heigh, $value->net_amount,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(35, $heigh, $value->ship,  1, 'L', 0, 1, '', '', true, 0, false, true, 0);

				//if ($i==0 || $my_array[$i]!=$my_array[$i-1]) {
						//$this->pdf->SetY(45);
		 				//$this->pdf->SetX(20);
		 				//$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

						//$this->pdf->SetFont('helvetica','',8);
                        //$this->pdf->Cell(10, 6,$value->nno, '1', 0, 'L', 0);
                        //$this->pdf->Cell(20, 6,$value->ddate, '1', 0, 'L', 0);
                         //$this->pdf->Cell(20, 6,$value->deliver_date, '1', 0, 'L', 0);
						//$this->pdf->Cell(60, 6,$value->supplier." | ".$value->name,'1', 0, 'L', 0);
                        //$this->pdf->Cell(22, 6,$value->gross_amount, '1', 0, 'R', 0);
                        //$this->pdf->Cell(20, 6,$value->other, '1', 0, 'R', 0);
                        //$this->pdf->Cell(22, 6,$value->discount, '1', 0, 'R', 0);
                        //$this->pdf->Cell(22, 6,$value->net_amount, '1', 0, 'R', 0);
                        //$this->pdf->Cell(35, 6,$value->ship, '1', 0, 'L', 0);
                        //$this->pdf->Ln();
                        //$i++;
                        	
                   //     }
}
 
		 	$this->pdf->SetFont('helvetica','B',9);
			$this->pdf->SetX(20);
            $this->pdf->MultiCell(10, 6, "",  0, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, 6, "",  0, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, 6, "",  0, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(65, 6, "Total",  0, 'R', 0, 0, '', '', true, 0, false, true, 0);
            //$this->pdf->MultiCell(22, $heigh, "",  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            //$this->pdf->MultiCell(20, $heigh, "",  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
           //$this->pdf->MultiCell(22, $heigh, "",  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(22, 6, number_format($net[$a],2),  "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(35, 6, "",  0, 'L', 0, 1, '', '', true, 0, false, true, 0);		 	
			$this->pdf->Ln();              
		 

	//$this->pdf->footerSet($employee,$amount,$additional,$discount,$user);
	$this->pdf->Output("Purchase Order Summary".date('Y-m-d').".pdf", 'I');

?>