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

		$this->pdf->setY(20);

        	$this->pdf->SetFont('helvetica', 'BU',10);
		 	$this->pdf->Cell(0, 5, 'CREDIT SALES SUMMARY',0,false, 'L', 0, '', 0, false, 'M', 'M');
			$this->pdf->Ln();
			$this->pdf->Ln();
			$this->pdf->SetFont('helvetica', '',9);
		 	$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'L', 0, '', 0, false, 'M', 'M');
			$this->pdf->Ln();
			

			if($catgory!="0"){
				$this->pdf->SetX(30);
				$this->pdf->SetFont('helvetica', 'B',8);
				if($catgory=="RSX"){
					$this->pdf->Cell(100, 6,"Category : ".$catgory." - Retail Sales", '0', 0, 'L', 0);
				}else{
					$this->pdf->Cell(100, 6,"Category : ".$catgory." - Whole Sales", '0', 0, 'L', 0);
				}
				$this->pdf->Ln();
				}

			if($custom_name!=""){
				$this->pdf->SetX(30);
				$this->pdf->SetFont('helvetica', 'B',8);
				$this->pdf->Cell(100, 6,"Customer : ".$custom_name, '0', 0, 'L', 0);
				$this->pdf->Ln();
				$this->pdf->Ln();
			}

			 //----check data is available for print ----        
          

		 				$this->pdf->GetY();
		 				$this->pdf->SetX(15);
						$this->pdf->SetFont('helvetica','B',7);
						$this->pdf->Cell(18, 6,"Date", '1', 0, 'C', 0);
                        $this->pdf->Cell(12, 6,"No", '1', 0, 'C', 0);
                        $this->pdf->Cell(12, 6,"Sub No", '1', 0, 'C', 0);
                        $this->pdf->Cell(20, 6,"NIC", '1', 0, 'C', 0);
                        $this->pdf->Cell(80, 6,"Customer", '1', 0, 'C', 0);
                        $this->pdf->Cell(22, 6,"Credit Amount", '1', 0, 'C', 0);
                        $this->pdf->Cell(22, 6,"Credit Card", '1', 0, 'C', 0);
                        $this->pdf->Cell(22, 6,"Credit Note", '1', 0, 'C', 0);
                        $this->pdf->Cell(22, 6,"Gift Voucher", '1', 0, 'C', 0);
                        $this->pdf->Cell(22, 6,"Total Interest", '1', 0, 'C', 0);
                        $this->pdf->Cell(22, 6,"Total Amount", '1', 0, 'C', 0);
                   
                        $this->pdf->Ln();
                        $tot_dis=(float)0;
                        $tot_gross=(float)0;
                        $tot_net=(float)0;

                        foreach ($sum as $value) {
						//$this->pdf->SetY(45);
		 				$this->pdf->SetX(15);
		 				$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
						$this->pdf->SetFont('helvetica','',8);

						$aa = $this->pdf->getNumLines($value->cus_id." - ".$value->name, 80);
               			$heigh=5*$aa;

						$this->pdf->MultiCell(18, $heigh, $value->ddate,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(12, $heigh, $value->nno,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(12, $heigh, $value->sub_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(20, $heigh, $value->nic,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(80, $heigh, $value->cus_id." | ".$value->name,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(22, $heigh, $value->pay_credit,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(22, $heigh, $value->pay_ccard,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(22, $heigh, $value->pay_cnote,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(22, $heigh, $value->pay_gift_voucher,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(22, $heigh, $value->int_amount,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                        $this->pdf->MultiCell(22, $heigh, $value->net_amount,1, 'R', 0, 1, '', '', true, 0, false, true, 0);

                        $crdit_amount+=(float)$value->pay_credit;
                        $credit_card+=(float)$value->pay_ccard;
                        $credit_note+=(float)$value->pay_cnote;
                        $gift_voucher+=(float)$value->pay_gift_voucher;
                        $int_amount+=(float)$value->int_amount;
                        $net_amount+=(float)$value->net_amount;
                        	
                        }

                                    
                        $this->pdf->SetFont('helvetica', 'B', 9);
						$this->pdf->SetX(15);
					 	$this->pdf->Cell(18, 6, "", '0', 0, 'L', 0);
					 	$this->pdf->Cell(12, 6, "", '0', 0, 'R', 0);
					 	$this->pdf->Cell(12, 6, "", '0', 0, 'C', 0);
					 	$this->pdf->Cell(21, 6, "", '0', 0, 'C', 0);
					 	$this->pdf->Cell(80, 6, "Total", '0', 0, 'C', 0);
					 	$this->pdf->Cell(20, 6, number_format($crdit_amount,2), 'TB', 0, 'R', 0);
					 	$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
					 	$this->pdf->Cell(23, 6, number_format($credit_card,2), 'TB', 0, 'R', 0);
					 	$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
					 	$this->pdf->Cell(21, 6, number_format($credit_note,2), 'TB', 0, 'R', 0);
					 	$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
					 	$this->pdf->Cell(21, 6, number_format($gift_voucher,2), 'TB', 0, 'R', 0);
					 	$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
					 	$this->pdf->Cell(21, 6, number_format($int_amount,2), 'TB', 0, 'R', 0);
					 	$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
					 	$this->pdf->Cell(21, 6, number_format($net_amount,2), 'TB', 0, 'R', 0);
					 	

                         /*$this->pdf->Ln();
                        

						$this->pdf->SetX(20);
					 	$this->pdf->Cell(40, 1, "", '0', 0, 'L', 0);
					 	$this->pdf->Cell(80, 1, "", '0', 0, 'L', 0);
					 	$this->pdf->Cell(30, 1, "Additional                Rs", '0', 0, 'L', 0);
					 	$this->pdf->Cell(20, 1,$addi, '0', 0, 'R', 0);
					 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);

					 	
*/



	// $this->pdf->footerSet($employee,$amount,$additional,$discount,$user);
	$this->pdf->Output("Credit Sale Summary".date('Y-m-d').".pdf", 'I');

?>