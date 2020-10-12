<?php
		// echo '<pre>'.print_r($det,true).'</pre>';
		//  		exit;

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
		$this->pdf->setPrintHeader(true,$type);
        $this->pdf->setPrintFooter(true);
        //$this->pdf->setPrintHeader(true);
        
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3
// L or P amd page type A4 or A3
		foreach($branch as $ress){
 			$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);	
		}

			foreach($sum as $row){

				$code=$row->CODE;
				$name=$row->NAME;
				$fdate=$row->fdate;
				$tdate=$row->tdate;
				$tot_cr=$row->tot_cr;
				$category=$row->category;
				$description=$row->description;

			}
			$this->pdf->setY(20);
        	$this->pdf->SetFont('helvetica', 'BU', 12);
        	$this->pdf->Ln();

		  	$this->pdf->Cell(0, 5,'Budget Sales  ',0,false, 'L', 0, '', 0, false, 'M', 'M');

		 	$this->pdf->SetFont('helvetica', '',9);
		 	$this->pdf->setY(25);
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(20, 1, 'Code', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(40, 1, $code, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	
		 	$this->pdf->Cell(30, 1, "Name ", '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1, $name, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(20, 1, 'From Date', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(40, 1, $fdate, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	
		 	$this->pdf->Cell(30, 1, "To Date ", '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1, $tdate, '0', 0, 'L', 0);
		 	$this->pdf->Ln();


		 	$this->pdf->Cell(20, 1, 'Catogory ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(40, 1, $category, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	
		 	$this->pdf->Cell(30, 1, "Description  ", '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1, $description, '0', 0, 'L', 0);
		 	$this->pdf->Ln();

			$this->pdf->SetY(50);
			$this->pdf->SetX(20);
			
	        	$this->pdf->SetFont('helvetica','B',9);
				$this->pdf->MultiCell(30, 6, "Account Code", $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()-5), $y=($this->pdf->GetY()), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
	            $this->pdf->MultiCell(80, 6, "Description", $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
	            $this->pdf->MultiCell(30, 6, "DR_Amount", $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
	            $this->pdf->MultiCell(30, 6, "CR_Amount", $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
	            
	            $this->pdf->Ln();
	            

	            foreach($det as $row){
	            	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

		            $this->pdf->GetY(60);
		            $this->pdf->SetX(15);

					$this->pdf->SetFont('helvetica','',9);
		            $this->pdf->MultiCell(30, 6, $row->acc_code, $border=1, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		            $this->pdf->MultiCell(80, 6, $row->description, $border=1, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		            $this->pdf->MultiCell(30, 6, $row->dr_amount, $border=1, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		            $this->pdf->MultiCell(30, 6, $row->cr_amount, $border=1, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		            $this->pdf->Ln();
		            $dr_tot+=$row->dr_amount;
		            $cr_tot+=$row->cr_amount;
		            
	            }
	            	
		        $this->pdf->SetX(20);
	            $this->pdf->SetFont('helvetica','B',9);
				$this->pdf->MultiCell(30, 6, " ", $border=0, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()-5), $y=($this->pdf->GetY()), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
	            $this->pdf->MultiCell(80, 6, "Total", $border=0, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
	            $this->pdf->MultiCell(30, 6, number_format($dr_tot,2), $border=TB, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
	            $this->pdf->MultiCell(30, 6, number_format($cr_tot,2), $border=TB, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
	            
	            
	        

	$this->pdf->Output("Purchase Order".date('Y-m-d').".pdf", 'I');

?>