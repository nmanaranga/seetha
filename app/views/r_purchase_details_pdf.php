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
	        $i=0;
		    $a=-1;
			static $j=-1;
			$my_array=array();
			$Goss=array();
			$net=array();

			foreach ($purchase as $value) {
                  $my_array[]=$value->nno;
			}

			foreach ($sum as $sum){
                        	$Goss[]=$sum->gsum;
                        	$net[]=$sum->nsum;
                        	$addi[]=$sum->addi;
                        	$a++;
                        }

		    

		    $this->pdf->setY(23);

        	$this->pdf->SetFont('helvetica', 'BU',10);
		 	$this->pdf->Cell(0, 5, ' PURCHASE DETAILS',0,false, 'C', 0, '', 0, false, 'M', 'M');
			$this->pdf->Ln();
			$this->pdf->Ln();
			$this->pdf->SetFont('helvetica', '',9);
		 	$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'C', 0, '', 0, false, 'M', 'M');
			$this->pdf->Ln();
			$this->pdf->Ln();
			$this->pdf->Ln();

			 //----check data is available for print ----        
           if($value->nno == "")
           {
           	$this->pdf->SetX(80);
			$this->pdf->Cell(20, 1, "No Records For View ! ! !", '0', 0, 'L', 0);     
           }
           else
           {

		 	$this->pdf->SetFont('helvetica', 'B', 8);
		 	foreach ($purchase as $value) 
		 		{	
					if ($i==0 || $my_array[$i]!=$my_array[$i-1]) 
					{
						if ($j>=0) 
						{	
							$this->pdf->Ln();
							$this->pdf->SetX(70);
							$this->pdf->SetFont('helvetica','B',8);
						 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
						 	$this->pdf->Cell(50, 1, "", '0', 0, 'L', 0);
						 	$this->pdf->Cell(35, 1, "Total Goss               Rs", '0', 0, 'L', 0);
						 	$this->pdf->Cell(20, 1, $Goss[$j], '0', 0, 'R', 0);
						 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
			 
			 				$this->pdf->Ln();
			 					
							$this->pdf->SetX(70);
						 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
						 	$this->pdf->Cell(50, 1, "", '0', 0, 'L', 0);
						 	$this->pdf->Cell(35, 1, "Additional                Rs", '0', 0, 'L', 0);
						 	$this->pdf->Cell(20, 1, $addi[$j], '0', 0, 'R', 0);
						 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
			 
			 				$this->pdf->Ln();
							$this->pdf->SetX(70);
						 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
						 	$this->pdf->Cell(50, 1, "", '0', 0, 'L', 0);
						 	$this->pdf->Cell(35, 1, "Total Net Amount    Rs", '0', 0, 'L', 0);
						 	$this->pdf->Cell(20, 1, $net[$j], '0', 0, 'R', 0);
						 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		 					$this->pdf->Ln();
							$this->pdf->Ln();

						}

					$j++;

		 			$this->pdf->SetFont('helvetica', '', 8);
		 			if ($i==0) 
		 			{
		 				$this->pdf->setY(35);
		 			}


				    $this->pdf->Ln();
				 	$this->pdf->SetX(15);
				 	$this->pdf->Cell(30, 1, 'GRAN No  ', '0', 0, 'L', 0);
				 	$this->pdf->Cell(60, 1, $value->nno, '0', 0, 'L', 0);				 					 	
				 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
				 	$this->pdf->Cell(20, 1, "Invoice No", '0', 0, 'L', 0);
				 	$this->pdf->Cell(20, 1, $value->inv_no, '0', 0, 'L', 0);
				 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
				 	
				 	$this->pdf->Ln();
					$this->pdf->SetX(15);

				 	$this->pdf->Cell(30, 1, "Date  ", '0', 0, 'L', 0);
				 	$this->pdf->Cell(50, 1, $value->ddate, '0', 0, 'L', 0);
				 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
				 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
				 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		 
		 			$this->pdf->Ln();
					$this->pdf->SetX(15);

				 	$this->pdf->Cell(30, 1, "Supplier  ", '0', 0, 'L', 0);
				 	$this->pdf->Cell(50, 1,$value->supp_id." | ".$value->name, '0', 0, 'L', 0);
				 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
				 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
				 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		 	
					$this->pdf->Ln();
					$this->pdf->Ln();


	 				//$this->pdf->SetY(45);
	 				$this->pdf->SetX(2);
					$this->pdf->SetFont('helvetica','B',8);
                    $this->pdf->Cell(30, 6,"Item Code", '1', 0, 'C', 0);
					$this->pdf->Cell(55, 6,"Description", '1', 0, 'C', 0);
                    $this->pdf->Cell(10, 6,"Qty", '1', 0, 'C', 0);
                    $this->pdf->Cell(10, 6,"FOC", '1', 0, 'C', 0);
                    $this->pdf->Cell(15, 6,"C Price ", '1', 0, 'C', 0);
                    $this->pdf->Cell(15, 6,"L Price ", '1', 0, 'C', 0);
                    $this->pdf->Cell(15, 6,"S Price ", '1', 0, 'C', 0);
                    $this->pdf->Cell(15, 6,"Dis", '1', 0, 'C', 0);
                    $this->pdf->Cell(10, 6,"L.P.M", '1', 0, 'C', 0);
                    $this->pdf->Cell(10, 6,"S.P.M", '1', 0, 'C', 0);
                    $this->pdf->Cell(20, 6,"Amount", '1', 0, 'C', 0);
                    $this->pdf->Ln();

 				}
				//$this->pdf->SetY(45);
 				$this->pdf->SetX(2);
				$this->pdf->SetFont('helvetica','',8);
				$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
                
                $aa = $this->pdf->getNumLines($value->item_des, 55);
	        	$heigh=5*$aa;

                $this->pdf->MultiCell(30, $heigh,$value->code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(55, $heigh,$value->item_des, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(10, $heigh,$value->qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(10, $heigh,$value->foc, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(15, $heigh,$value->cost, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(15, $heigh,$value->min_price, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(15, $heigh,$value->max_price, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(15, $heigh,$value->discount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(10, $heigh,$value->lpm, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(10, $heigh,$value->spm, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh,$value->amount, 1, 'R', 0, 1, '', '', true, 0, false, true, 0);

                $i++;
                	
            }
        $this->pdf->Ln();
      
		$this->pdf->SetX(75);
		$this->pdf->SetFont('helvetica','B',8);
	 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
	 	$this->pdf->Cell(50, 1, "", '0', 0, 'L', 0);
	 	$this->pdf->Cell(30, 1, "Total Goss               Rs", '0', 0, 'L', 0);
	 	$this->pdf->Cell(20, 1, $Goss[$a], '0', 0, 'R', 0);
	 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		 
		  $this->pdf->Ln();
      
		$this->pdf->SetX(75);
	 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
	 	$this->pdf->Cell(50, 1, "", '0', 0, 'L', 0);
	 	$this->pdf->Cell(30, 1, "Additional                Rs", '0', 0, 'L', 0);
	 	$this->pdf->Cell(20, 1, $addi[$a], '0', 0, 'R', 0);
	 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
	 	 
		$this->pdf->Ln();
		$this->pdf->SetX(75);
	 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
	 	$this->pdf->Cell(50, 1, "", '0', 0, 'L', 0);
	 	$this->pdf->Cell(30, 1, "Total Net Amount    Rs", '0', 0, 'L', 0);
	 	$this->pdf->Cell(20, 1, $net[$a], '0', 0, 'R', 0);
	 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
	 	
		$this->pdf->Ln();

	}
		$this->pdf->Output("Purchase Detail".date('Y-m-d').".pdf", 'I');

?>