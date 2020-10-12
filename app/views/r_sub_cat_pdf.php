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
	 	$this->pdf->Cell(0, 5, 'Sub Category Details   ',0,false, 'L', 0, '', 0, false, 'M', 'M');
		$this->pdf->Ln();
        
        $this->pdf->setY(27);
        $this->pdf->Cell(45, 1,"",'T',0, 'L', 0);
        $this->pdf->Ln(); 
    
	 	$this->pdf->SetY(35);
		$this->pdf->SetX(15);
		$this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(30, 6,"Sub Category Code", '1', 0, 'C', 0);
        $this->pdf->Cell(65, 6,"Sub Category", '1', 0, 'C', 0);
        $this->pdf->Cell(50, 6,"Main Category", '1', 0, 'C', 0);

        $this->pdf->SetY(42);
        $count=0;
        $pre_count=1;
        
       foreach ($sub_cat_det as $value) {
        $pg_count=$this->pdf->PageNo();
        if($pg_count>1){
            if($pg_count!=$pre_count){
                $this->pdf->SetY(35);
                $pre_count=$pg_count;
            }
            
        }else{
            if($pg_count!=$pre_count){
                $this->pdf->SetY(53);
                $pre_count=$pg_count;
            }
        }
        
            $this->pdf->SetX(15);
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1.00, 'color' => array(0, 0, 0)));      
            $aa = $this->pdf->getNumLines($value->description, 60);
            $heigh=5*$aa;
            $this->pdf->SetFont('helvetica','',8);
            $this->pdf->MultiCell(30, $heigh,$value->code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(65, $heigh,$value->description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(50, $heigh,$value->main_category." - ".$value->main, 1, 'L', 0, 1, '', '', true, 0, false, true, 0);
           $count++;
        }

    $this->pdf->Ln();
    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->Cell(80, 6,"Number Of Sub Categories : ". $count, '', 0, 'L', 0);

	$this->pdf->Output("Category Details".date('Y-m-d').".pdf", 'I');
?>