<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
        $this->pdf->setPrintFooter(true);
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

 		foreach($branch as $ress){
 				$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
 			}
foreach ($purchase as $value){
	  $inv_no=$value->nno;
	  $name=$value->name;
	}

	foreach ($category as $cat){
	  $code=$cat->code;
	  $des=$cat->description;
	}
		
		$this->pdf->setY(22);

        	$this->pdf->SetFont('helvetica', 'BU',10);
		 	$this->pdf->Cell(0, 5, 'PAYMENT TYPE WISE CASH SALES SUMMARY',0,false, 'C', 0, '', 0, false, 'M', 'M');
			$this->pdf->Ln();$this->pdf->Ln();

			$this->pdf->SetFont('helvetica', '',9);
		 	$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'C', 0, '', 0, false, 'M', 'M');
			$this->pdf->Ln();
			$this->pdf->Ln();

			if($category_field!="0"){
				$this->pdf->SetX(20);
				$this->pdf->SetFont('helvetica', 'B',9);
				$this->pdf->Cell(45, 6,"Category : ".$code." - ".$des, '0', 0, 'R', 0);
				$this->pdf->Ln();
				$this->pdf->Ln();
			}
			

			 //----check data is available for print ----        
           if($value->nno == "")
           {
           	$this->pdf->SetX(80);
			$this->pdf->Cell(20, 1, "No Records For View ! ! !", '0', 0, 'L', 0);     
           }
           else
           {

		 				$this->pdf->SetY(50);
		 				$this->pdf->SetX(10);
						$this->pdf->SetFont('helvetica','B',9);
                        $this->pdf->Cell(15, 6,"No", '1', 0, 'C', 0);
                       	$this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
                        $this->pdf->Cell(75, 6,"Customer", '1', 0, 'C', 0);
                        $this->pdf->Cell(25, 6,"Net Amount", '1', 0, 'C', 0);
                        $this->pdf->Cell(25, 6,"Cash Amount", '1', 0, 'C', 0);
                        $this->pdf->Cell(25, 6,"Card Amount", '1', 0, 'C', 0);
                       
                   
                        $this->pdf->Ln();

                        $tot_gross=(float)0;
                        $tot_net=(float)0;
                        $tot_dis=(float)0;
                        $tot_cash=(float)0;
                        $tot_card=(float)0;


                        foreach ($purchase as $value) {

                        	$bb = $this->pdf->getNumLines($value->cus_id." | ".$value->name, 75);
	                        $heigh=5*$bb;	
							//$this->pdf->SetY(45);
			 				$this->pdf->SetX(10);
			 				$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
							$this->pdf->SetFont('helvetica','',9);

	                  		/*
	                  		$this->pdf->Cell(15, 6,$value->nno, '1', 0, 'R', 0);
							$this->pdf->Cell(20, 6,$value->ddate,'1', 0, 'L', 0);
	                        $this->pdf->Cell(50, 6,$value->cus_id." | ".$value->name, '1', 0, 'L', 0);
	                        $this->pdf->Cell(20, 6,$value->gross, '1', 0, 'R', 0);
	                        $this->pdf->Cell(20, 6,$value->discount, '1', 0, 'R', 0);
	                        $this->pdf->Cell(20, 6,$value->net_amount, '1', 0, 'R', 0);
	                        $this->pdf->Cell(20, 6,$value->pay_cash, '1', 0, 'R', 0);
	                        $this->pdf->Cell(20, 6,$value->pay_ccard, '1', 0, 'R', 0);
	                        */


	                        $this->pdf->MultiCell(15, $heigh, $value->nno,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			                $this->pdf->MultiCell(20, $heigh, $value->ddate,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			                $this->pdf->MultiCell(75, $heigh, $value->cus_id." | ".$value->name,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			                /*$this->pdf->MultiCell(20, $heigh, $value->gross,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			                $this->pdf->MultiCell(20, $heigh, $value->discount,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);*/
			                $this->pdf->MultiCell(25, $heigh, $value->net_amount,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			                $this->pdf->MultiCell(25, $heigh, $value->pay_cash,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			                $this->pdf->MultiCell(25, $heigh, $value->pay_ccard,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);


	                      	$tot_gross+=(float)$value->gross;
	                        $tot_net+=(float)$value->net_amount;
	                        $tot_disc+=(float)$value->discount;
	                        $tot_cash+=(float)$value->pay_cash;
	                        $tot_card+=(float)$value->pay_ccard;

	                        
                        	
                        }

                        foreach ($sum as $sum) {
                        	$Goss=$sum->gsum;
                        	$net=$sum->nsum;
                        	$addi=$sum->addi;
                        	
                        }

                        $this->pdf->SetFont('helvetica','B',9);
					    $this->pdf->SetX(10);

					    $this->pdf->Cell(15, 6,"", '0', 0, 'R', 0);
						$this->pdf->Cell(20, 6,"",'0', 0, 'L', 0);
                        $this->pdf->Cell(75, 6,"Total", '0', 0, 'R', 0);
                        /*$this->pdf->Cell(19, 6,number_format($tot_gross,2), 'TB', 0, 'R', 0);
                        $this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
                        $this->pdf->Cell(19, 6,number_format($tot_disc,2), 'TB', 0, 'R', 0);
                        $this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);*/
                        $this->pdf->Cell(24, 6,number_format($tot_net,2), 'TB', 0, 'R', 0);
                        $this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
                        $this->pdf->Cell(24, 6,number_format($tot_cash,2), 'TB', 0, 'R', 0);
					    $this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);

					    $this->pdf->Cell(24, 6,number_format($tot_card,2), 'TB', 0, 'R', 0);
					    $this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);

			/*		    $this->pdf->Cell(15, 6,"", '0', 0, 'L', 0);
					    $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
					    $this->pdf->Cell(50, 6,"Total ", '0', 0, 'R', 0);
					    $this->pdf->Cell(20, 6,number_format($Goss,2), 'TB', 0, 'R', 0);
					    $this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
					    $this->pdf->Cell(19, 6,number_format($tot_disc,2), 'TB', 0, 'R', 0);
					    $this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
					    $this->pdf->Cell(20, 6,number_format($net,2), 'TB', 0, 'R', 0);
					    $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);

					    $this->pdf->Cell(20, 6,number_format($tot_cash,2), 'TB', 0, 'R', 0);
					    $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);

					    $this->pdf->Cell(20, 6,number_format($tot_card,2), 'TB', 0, 'R', 0);
					    $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);*/

                        /*$this->pdf->SetX(20);
                         $this->pdf->Ln();
                        $this->pdf->SetFont('helvetica', 'B', 7);
						$this->pdf->SetX(20);
					 	$this->pdf->Cell(40, 1, "", '0', 0, 'L', 0);
					 	$this->pdf->Cell(80, 1, "", '0', 0, 'L', 0);
					 	$this->pdf->Cell(30, 1, "Total Goss               Rs", '0', 0, 'L', 0);
					 	$this->pdf->Cell(20, 1, number_format($Goss,2), '0', 0, 'R', 0);
					 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);

					 	$this->pdf->Ln();
					 	$this->pdf->SetX(20);
					 	$this->pdf->Cell(40, 1, "", '0', 0, 'L', 0);
					 	$this->pdf->Cell(80, 1, "", '0', 0, 'L', 0);
					 	$this->pdf->Cell(30, 1, "Additional                Rs", '0', 0, 'L', 0);
					 	$this->pdf->Cell(20, 1, number_format($addi,2), '0', 0, 'R', 0);
					 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
					 
					 	$this->pdf->Ln();
						$this->pdf->SetX(20);
					 	$this->pdf->Cell(40, 1, "", '0', 0, 'L', 0);
					 	$this->pdf->Cell(80, 1, "", '0', 0, 'L', 0);
					 	$this->pdf->Cell(30, 1, "Total Net Amount    Rs", '0', 0, 'L', 0);
					 	$this->pdf->Cell(20, 1, number_format($net,2), '0', 0, 'R', 0);
					 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);*/




}
	// $this->pdf->footerSet($employee,$amount,$additional,$discount,$user);
	$this->pdf->Output("Cash Sale Summary".date('Y-m-d').".pdf", 'I');

?>