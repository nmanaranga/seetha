<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$duration);
        $this->pdf->setPrintFooter(true);
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

 		foreach($branch as $ress){
			 $branch_name=$ress->name;
            $this->pdf->setY(10);
            $this->pdf->SetFont('helvetica', 'B', 10);
            $this->pdf->Cell(0, 5, $ress->name,0, false, 'L', 0, '', 0, false, 'M', 'M');
            $this->pdf->Ln();
            $this->pdf->setY(15);
            $this->pdf->SetFont('helvetica', '', 8);
            $this->pdf->Cell(0, 5,$ress->address." Tel: ".$ress->tp." Fax: ".$ress->fax.".  Email: ".$ress->email,0, false, 'L', 0, '', 0, false, 'M', 'M');
            
		}

		$this->pdf->setY(20);
        $this->pdf->Ln();
        $this->pdf->SetFont('helvetica', 'BI',12);
	 	$this->pdf->Cell(0, 5, 'Item Details   ',0,false, 'L', 0, '', 0, false, 'M', 'M');
		$this->pdf->Ln();
        
        $this->pdf->setY(27);
        $this->pdf->Cell(25, 1,"",'T',0, 'L', 0);
        $this->pdf->Ln(); 
    
	 	$this->pdf->SetY(33);
		$this->pdf->SetX(15);
		$this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(30, 6,"Code", '1', 0, 'C', 0);
        $this->pdf->Cell(70, 6,"Description", '1', 0, 'C', 0);
        $this->pdf->Cell(25, 6,"Min Price", '1', 0, 'C', 0);
        $this->pdf->Cell(25, 6,"Max Price", '1', 0, 'C', 0);
        $this->pdf->Cell(25, 6,"Purchase Price", '1', 0, 'C', 0);

        $this->pdf->SetY(40);
        $count=0;
       foreach ($item_det as $value) {
        
            $this->pdf->SetX(15);
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1.00, 'color' => array(0, 0, 0)));      
            $aa = $this->pdf->getNumLines($value->description, 70);
            $heigh=4*$aa;
            $this->pdf->SetFont('helvetica','',8);
            $this->pdf->MultiCell(30, $heigh,$value->code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(70, $heigh,$value->description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh,$value->min_price,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh,$value->max_price,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh,$value->purchase_price, 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
           $count++;
        }

    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->Cell(80, 6,"Number Of Items : ". $count, '', 0, 'L', 0);

	$this->pdf->Output("Category Details".date('Y-m-d').".pdf", 'I');
?>