<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,"r_supplier2",$duration);
        $this->pdf->setPrintFooter(true);
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

 		foreach($branch as $ress){
			 $branch_name=$ress->name;
            $this->pdf->setY(10);
            $this->pdf->setX(15);
            $this->pdf->SetFont('helvetica', 'B', 10);
            $this->pdf->Cell(0, 5, 'Nisaco Furniture - '.$ress->name,0, false, 'L', 0, '', 0, false, 'M', 'M');
            $this->pdf->Ln();
            $this->pdf->setY(15);
            $this->pdf->SetFont('helvetica', '', 8);
            $this->pdf->Cell(0, 5,$ress->address." Tel: ".$ress->tp." Fax: ".$ress->fax.".  Email: ".$ress->email,0, false, 'L', 0, '', 0, false, 'M', 'M');
            
		}

		$this->pdf->setY(23);
        $this->pdf->Ln();
        $this->pdf->SetFont('helvetica', 'BI',12);
	 	$this->pdf->Cell(0, 5, 'Supplier Details   ',0,false, 'L', 0, '', 0, false, 'M', 'M');
		$this->pdf->Ln();
        
        $this->pdf->setY(30);
        $this->pdf->Cell(50, 1,"",'T',0, 'L', 0);
        $this->pdf->Ln(); 



       

	 	/*$this->pdf->SetY(35);
		$this->pdf->SetX(10);
		$this->pdf->SetFont('helvetica','B',8);
		$this->pdf->Cell(20, 6,"Code", '1', 0, 'C', 0);
        $this->pdf->Cell(64, 6,"Company Name", '1', 0, 'C', 0);
        $this->pdf->Cell(30, 6,"Cont.Ref", '1', 0, 'C', 0);
        $this->pdf->Cell(22, 6,"Off.No", '1', 0, 'C', 0);
        $this->pdf->Cell(21, 6,"Fax", '1', 0, 'C', 0);
        $this->pdf->Cell(21, 6,"Mobile", '1', 0, 'C', 0);
        $this->pdf->Cell(21, 6,"Res.No", '1', 0, 'C', 0);*/
        
        $sup="";
        $x=1;
       $this->pdf->SetY(42);
       $pre_count=1;
       foreach ($sup_det as $value) {
        $count=$this->pdf->PageNo();

        if($count>1){
            if($count!=$pre_count){
                $this->pdf->SetY(11);
                $pre_count=$count;
            }
            
        }else{
            if($count!=$pre_count){
                $this->pdf->SetY(42);
                $pre_count=$count;
            }
        }
        
        $this->pdf->SetX(10);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1.00, 'color' => array(0, 0, 0)));
        $cont_name="";
        if($value->office!=""){
            $cont_name=$value->office_name;
        }else if($value->mobile!=""){
            $cont_name=$value->mobile_name;
        }else if($value->fax!=""){
            $cont_name=$value->fax_name;
        }else if($value->resident!=""){
            $cont_name=$value->resident_name;
        }
        if($sup!=$value->code){
            $aa = $this->pdf->getNumLines(trim($value->name), 60);
            $heigh=5*$aa;
            $this->pdf->SetFont('helvetica','',8);
            $this->pdf->MultiCell(20, $heigh,trim($value->code), 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(64, $heigh,trim($value->name), 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(30, $heigh,strtoupper(trim($cont_name)),  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(22, $heigh,trim($value->office),  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(21, $heigh,trim($value->fax),  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(21, $heigh,trim($value->mobile),  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(21, $heigh,trim($value->resident),  1, 'L', 0, 1, '', '', true, 0, false, true, 0);
            $x++;
        }else{
            $bb = $this->pdf->getNumLines(trim($value->name), 60);
            $heigh=5*$bb;
    		$this->pdf->SetFont('helvetica','',8);
            $this->pdf->MultiCell(20, $heigh,"",  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(64, $heigh,"",  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(30, $heigh,strtoupper(trim($cont_name)),  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(22, $heigh,trim($value->office),  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(21, $heigh,trim($value->fax),  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(21, $heigh,trim($value->mobile),  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(21, $heigh,trim($value->resident),  1, 'L', 0, 1, '', '', true, 0, false, true, 0);
            
        }
        $sup=$value->code;
}
	$this->pdf->Output("Supplier Details".date('Y-m-d').".pdf", 'I');
?>