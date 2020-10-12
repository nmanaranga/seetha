<?php


		// echo '<pre>'.print_r($det,true).'</pre>';
		//  		exit;

		foreach($det as $row){


		}

		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
		$this->pdf->setPrintHeader(true,$type);
        $this->pdf->setPrintFooter(true);
        //$this->pdf->setPrintHeader(true);
        
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

 		foreach($branch as $ress){
 			$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);	
		}

		$sup_name;
		$sup_address;
		$sup_tp;
		$sup_email;
		$ship_branch_name;
		$ship_branch_add;
		$ship_branch_tp;
		$ship_branch_email;
		$date;
		$branch;

		// foreach($ddate as $dat){
		// 	$date = $dat->ddate;
		// }
		
		foreach($cl as $l){
			$bcc=$l->bc;
			$to_bcc=$l->to_bc;
			$date=$l->ddate;
			
		}

		foreach($suppliers as $sup){
			$sup_name=$sup->name;
			$sup_address=$sup->address1." ".$sup->address2." ".$sup->address3;
			$sup_tp=$sup->tp;
			$sup_email=$sup->email;
		}

		foreach($ship_branch as $sb){
			$ship_branch_name=$sb->name;
			$ship_branch_add=$sb->address1." ".$sb->address2." ".$sb->address3;
			$ship_branch_tp=$sb->tp;
			$ship_branch_email=$sb->email;
		}

		foreach($branch as $b){
			$branch = $b->name;
		}

		foreach($session as $ses){
			$invoice_no=$session[0].$session[1]."ITO".$session[2];
			
		}


			$this->pdf->setY(20);
        	$this->pdf->SetFont('helvetica', 'BU', 10);
        	$this->pdf->Ln();

		  	$this->pdf->Cell(0, 5,'Internal Transfer Order',0,false, 'C', 0, '', 0, false, 'M', 'M');

		 	$this->pdf->SetFont('helvetica', '', 8);
		 	$this->pdf->setY(25);
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(20, 1, 'Order No ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(40, 1, $invoice_no, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);

		 	
		 	$this->pdf->Cell(30, 1, "Order From", '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1,$bcc, '0', 0, 'L', 0);
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(20, 1, 'Date', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(40, 1, $date, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	
		 	$this->pdf->Cell(30, 1, "Order To", '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1, $to_bcc, '0', 0, 'L', 0);
		 	$this->pdf->Ln();


		 	$this->pdf->Cell(20, 1, 'Ref No', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(40, 1, $qno, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	
		 	

			$this->pdf->SetY(69);
		
			foreach($session as $ses){
				$invoice_no=$session[0].$session[1].$session[2];
			}
						$cout = (int)0;
                        $op=1;
                        foreach($det as $row){
                        	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

                        $this->pdf->GetY();

                        $int = (int)$row->qty * $row->purchase_price;

						$this->pdf->SetFont('helvetica','',8);
						$this->pdf->MultiCell(10, 6, $op, $border=1, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()-5), $y=($this->pdf->GetY()), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		                $this->pdf->MultiCell(25, 6, $row->code, $border=1, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		                $this->pdf->MultiCell(45, 6, $row->description, $border=1, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		                $this->pdf->MultiCell(30, 6, $row->model, $border=1, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		                $this->pdf->MultiCell(15, 6, $row->purchase_price, $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		                $this->pdf->MultiCell(15, 6, $row->min_price, $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		                $this->pdf->MultiCell(15, 6, $row->max_price, $border=1, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		                $this->pdf->MultiCell(15, 6, $row->qty, $border=1, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		                $this->pdf->MultiCell(20, 6,  number_format($int,2), $border=1, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		                $this->pdf->Ln();

		               	$cout = $cout + $int;
                        $op++;
                        }
	
    $this->pdf->footerSet5($cout);

	$this->pdf->Output("Purchase Order".date('Y-m-d').".pdf", 'I');

?>