<?php

    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    $this->pdf->setPrintHeader($header,$type,$duration);
    $this->pdf->setPrintHeader(true,$type);
    $this->pdf->setPrintFooter(true);
    
    $this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage($orientation,$page); 

    $branch_name="";

    foreach($branch as $ress){
        $branch_name=$ress->name;
        $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    }
      
    $this->pdf->setY(25);
    $this->pdf->SetFont('helvetica', 'BI',12);
    $this->pdf->Cell(180, 1,"Inter Branch Invoice Wise Balance Report ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(27);
    $this->pdf->Cell(85, 1,"",'T',0, 'L', 0);
    $this->pdf->Ln(); 

    $this->pdf->SetFont('helvetica', '',10);
    $this->pdf->Cell(180, 1,"Date Between ".$from." - ".$to,0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(40);
    $this->pdf->setX(10);

    $this->pdf->SetFont('helvetica','B',9);

    $this->pdf->Cell(25, 6," Date", '1', 0, 'C', 0);
    $this->pdf->Cell(15, 6," No", '1', 0, 'C', 0);
    $this->pdf->Cell(15, 6," Sub No", '1', 0, 'C', 0);
    $this->pdf->Cell(30, 6," Code ", '1', 0, 'C', 0);
    $this->pdf->Cell(90, 6," Description ", '1', 0, 'C', 0); 
    $this->pdf->Cell(20, 6," Balance", '1', 0, 'C', 0);
    
    $this->pdf->Ln();

    $this->pdf->GetY(40);
    $this->pdf->SetX(10);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    $this->pdf->SetFont('helvetica','',9);
    
    $customer_code="";
    $customer_name="";
    $total=(float)0;

    foreach($cus_det as $row){
        $aa = $this->pdf->getNumLines($row->description, 90);
        $bb = $this->pdf->getNumLines($row->acc_code, 30);
        if($aa>$bb){
            $heigh=5*$aa;
        }else{
           $heigh=5*$bb; 
        }
        $this->pdf->haveMorePages($heigh);
        $this->pdf->SetX(10);
        $this->pdf->MultiCell(25, $heigh,$row->ddate,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(15, $heigh,$row->trans_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(15, $heigh,$row->sub_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(30, $heigh,$row->acc_code,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(90, $heigh,ucfirst(strtolower($row->description)),  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$row->balance,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
       
        $total+=(float)$row->balance;
    } 

        $this->pdf->SetFont('helvetica','B',9);
        $this->pdf->SetX(10);
        $this->pdf->Cell(165, 6,"", '0', 0, 'L', 0);
        $this->pdf->Cell(11, 6,"Total ", '0', 0, 'l', 0);
        $this->pdf->Cell(19, 6,number_format($total,2), 'TB', 0, 'R', 0);
    $this->pdf->Output("Inter Branch Balance".date('Y-m-d').".pdf", 'I');
?>
