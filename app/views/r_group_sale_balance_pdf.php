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

			
			$this->pdf->setY(20);
        	$this->pdf->SetFont('helvetica', 'BU', 12);
        	$this->pdf->Ln();

		  	$this->pdf->Cell(0, 5,'Group Sale Balance  ',0,false, 'L', 0, '', 0, false, 'M', 'M');
		 			 	
			$this->pdf->SetY(30);
			$this->pdf->SetX(20);
			
	        	$this->pdf->SetFont('helvetica','B',9);
				$this->pdf->MultiCell(30, 6, "Code", $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()-5), $y=($this->pdf->GetY()), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
	            $this->pdf->MultiCell(60, 6, "Description", $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
	            $this->pdf->MultiCell(30, 6, "Issued", $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
	            $this->pdf->MultiCell(30, 6, "Sales", $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
	           	$this->pdf->MultiCell(30, 6, "Balance", $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);


	            $this->pdf->Ln();
	            

	            foreach($det as $row){
	            	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

		            $this->pdf->GetY(60);
		            $this->pdf->SetX(15);
		            $aa = $this->pdf->getNumLines($row->description, 60);
	        		$heigh=5*$aa;
					$this->pdf->SetFont('helvetica','',9);
		            $this->pdf->MultiCell(30, $heigh, $row->item, $border=1, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		            $this->pdf->MultiCell(60, $heigh, $row->description, $border=1, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		            $this->pdf->MultiCell(30, $heigh, $row->QtyIn, $border=1, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		            $this->pdf->MultiCell(30, $heigh, $row->QtyOut, $border=1, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
		            $this->pdf->MultiCell(30, $heigh, $row->Balance, $border=1, $align='R', $fill=false, $ln=1, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);   
		            
	            }
	            	
		        

	$this->pdf->Output("Purchase Order".date('Y-m-d').".pdf", 'I');

?>