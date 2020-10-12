<?php

		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
        $this->pdf->setPrintFooter(true);
       //$this->pdf->setPrintHeader(true);
        
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

 		foreach($branch as $ress){
 				$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
 			}

		
		foreach($customer as $cus){
			$cus_name=$cus->name;
			$cus_address=$cus->address1." ".$cus->address2." ".$cus->address3;
			$cus_tp=$cus->tp;
			$cus_email=$cus->email;
			$cus_card=$cus->code;
			$cus_issue=$cus->email;
			$cus_expire=$cus->email;	


		}
			$this->pdf->setY(20);

        	$this->pdf->SetFont('helvetica', 'IBU',8);
		 	$this->pdf->Cell(0, 5, ' PRIVILEGE CARD ISSUE',0,false, 'C', 0, '', 0, false, 'M', 'M');
			$this->pdf->Ln();$this->pdf->Ln();
		 	$this->pdf->SetFont('helvetica', 'IB', 8);
		 	$this->pdf->setY(25);
		 	$this->pdf->SetX(45);
		 	$this->pdf->Cell(30, 1, 'Card No :', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $card_no, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Name :", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $cus_name, '0', 0, 'L', 0);
		 	$this->pdf->Ln();
$this->pdf->SetX(45);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $ddate, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Address :", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $cus_address, '0', 0, 'L', 0);
		 	$this->pdf->Ln();
$this->pdf->SetX(45);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $edate, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "TP :", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $cus_tp, '0', 0, 'L', 0);
			$this->pdf->Ln();
$this->pdf->SetX(45);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $edate, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Email :", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $cus_email, '0', 0, 'L', 0);
		 	$this->pdf->Ln();
$this->pdf->SetX(45);
		    $this->pdf->Cell(30, 1, "Point History", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);


		 				$this->pdf->SetY(45);
		 				$this->pdf->SetX(45);
						$this->pdf->SetFont('helvetica','B',8);
                        $this->pdf->Cell(20, 6,"TR Code", '1', 0, 'C', 0);
						$this->pdf->Cell(20, 6,"TR No", '1', 0, 'C', 0);
                        $this->pdf->Cell(30, 6,"Date", '1', 0, 'C', 0);
                        $this->pdf->Cell(15, 6,"+Points", '1', 0, 'C', 0);
                        $this->pdf->Cell(15, 6,"-Points", '1', 0, 'C', 0);
                        $this->pdf->Ln();
                       
		 	

                        foreach ($a as  $p) {
                        	$this->pdf->SetX(45);
                        $this->pdf->SetFont('helvetica','B',8);
                        $this->pdf->Cell(20, 6,$p->trans_type, '1', 0, 'C', 0);
						$this->pdf->Cell(20, 6,$p->trans_no, '1', 0, 'C', 0);
                        $this->pdf->Cell(30, 6,$p->ddate, '1', 0, 'C', 0);
                        $this->pdf->Cell(15, 6,$p->dr, '1', 0, 'C', 0);
                        $this->pdf->Cell(15, 6,$p->cr, '1', 0, 'C', 0);
                        $this->pdf->Ln();
                        		
                        	}
                        $this->pdf->Ln();
                        $this->pdf->Ln();	
                        $this->pdf->SetX(45);
                        $this->pdf->Cell(30, 1, "Invoice History", '0', 0, 'L', 0);
		 				$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 				$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 				$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 				$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 				$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
						$this->pdf->Ln();
                        $this->pdf->Ln();
$this->pdf->SetX(45);
                        $this->pdf->SetFont('helvetica','B',8);
                        $this->pdf->Cell(20, 6,"Inv No", '1', 0, 'C', 0);
						$this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
                        $this->pdf->Cell(30, 6,"Amount", '1', 0, 'C', 0);
                        $this->pdf->Ln();

                        foreach ($ai as $i ){
                        	$this->pdf->SetX(45);
                        $this->pdf->SetFont('helvetica','B',8);
                        $this->pdf->Cell(20, 6,$i->nno, '1', 0, 'C', 0);
						$this->pdf->Cell(20, 6,$i->ddate, '1', 0, 'C', 0);
                        $this->pdf->Cell(30, 6,$i->net_amount, '1', 0, 'C', 0);
                        $this->pdf->Ln();
                        }
                        $this->pdf->SetX(45);
                        $this->pdf->Ln();
                        $this->pdf->SetX(45);
                        $this->pdf->Cell(30, 1, "Points", '0', 0, 'L', 0);
		 				$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 				$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 				$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 				$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 				$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
						$this->pdf->Ln();
                        $this->pdf->Ln();

                        foreach ($b as $b) {
                        	$b=$b->sdr;
                        }
                          foreach ($c as $c) {
                        	$c=$c->scr;
                        }$this->pdf->SetX(45);

                        	   $this->pdf->SetFont('helvetica','B',8);
                        $this->pdf->Cell(20, 6,"Earned", '1', 0, 'C', 0);
						$this->pdf->Cell(20, 6,$b, '1', 0, 'C', 0);
                        
                        $this->pdf->Ln();
$this->pdf->SetX(45);
                              $this->pdf->SetFont('helvetica','B',8);
                        $this->pdf->Cell(20, 6,"Used", '1', 0, 'C', 0);
						$this->pdf->Cell(20, 6,$c, '1', 0, 'C', 0);
                
                        $this->pdf->Ln();
$this->pdf->SetX(45);
                              $this->pdf->SetFont('helvetica','B',8);
                        $this->pdf->Cell(20, 6,"Balance", '1', 0, 'C', 0);
						$this->pdf->Cell(20, 6,$b-$c, '1', 0, 'C', 0);
                       
                        $this->pdf->Ln();



	$this->pdf->footerSet($employee,$amount,$additional,$discount,$user);
	$this->pdf->Output("Cash Sales_".date('Y-m-d').".pdf", 'I');

?>