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
    $this->pdf->Cell(180, 1,"Invoice Wise Customer Ledger Report ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(27);
    $this->pdf->Cell(65, 1,"",'T',0, 'L', 0);
    $this->pdf->Ln(); 

    $this->pdf->SetFont('helvetica', '',9);
    $this->pdf->Cell(25, 6,"Date From -", '0', 0, 'L', 0);
    $this->pdf->Cell(80, 6,$from." - ".$to, '0', 0, 'L', 0);
   
    if($cus_code !=""){
        $this->pdf->Ln(); 
        $this->pdf->Cell(25, 6,"Customer -", '0', 0, 'L', 0);
        $this->pdf->Cell(80, 6,$cus_code." ( ".$cus_name." ) ( ".$cus_nic." ) ", '0', 0, 'L', 0);
    }
    $this->pdf->Ln();

    $this->pdf->setY(44);
    $this->pdf->setX(15);

    $this->pdf->SetFont('helvetica','B',9);
    $this->pdf->Cell(20, 6," No ", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6," Date ", '1', 0, 'C', 0);
    $this->pdf->Cell(25, 6," Code", '1', 0, 'C', 0);
    $this->pdf->Cell(90, 6," Name ", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6," Balance ", '1', 0, 'C', 0);
    $this->pdf->Ln();
    $this->pdf->SetFont('helvetica','',8);
    $balance =(float)0;
    foreach($cus_det as $row){
        $aa = $this->pdf->getNumLines($row->name, 90);
        $bb = $this->pdf->getNumLines($row->acc_code, 20);
        if($aa>$bb){
            $heigh=4*$aa;
        }else{
            $heigh=4*$bb;            
        }
        $this->pdf->haveMorePages($heigh);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        $this->pdf->SetX(15);
        $this->pdf->MultiCell(20, $heigh,$row->trans_no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$row->ddate, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(25, $heigh,$row->acc_code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(90, $heigh,$row->name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,number_format($row->balance,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
        $balance +=$row->balance;
    } 

    $this->pdf->Ln();
    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->Cell(150, 6,"Balance  ", '0', 0, 'R', 0);
    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->Cell(5, 6,"", '0', 0, 'R', 0);
    $this->pdf->Cell(20, 6,number_format($balance,2), 'TB', 0, 'R', 0);

    $this->pdf->Ln();
    $this->pdf->SetFont('helvetica','B',8);
    
    $this->pdf->Output("Customer Balance".date('Y-m-d').".pdf", 'I');
?>
