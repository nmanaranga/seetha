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
    $this->pdf->Cell(180, 1,"Customer Ledger Card Report ",0,false, 'L', 0, '', 0, false, 'M', 'M');
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
    $this->pdf->Cell(20, 6," Date", '1', 0, 'L', 0);
    $this->pdf->Cell(20, 6," Ref No ", '1', 0, 'L', 0);
    $this->pdf->Cell(30, 6," Type ", '1', 0, 'L', 0);
    $this->pdf->Cell(55, 6," Description ", '1', 0, 'L', 0);
    $this->pdf->Cell(20, 6,"Dr Amount ", '1', 0, 'R', 0);
    $this->pdf->Cell(20, 6,"Cr Amount ", '1', 0, 'R', 0);
    $this->pdf->Cell(20, 6,"Balance ", '1', 0, 'R', 0);
    $this->pdf->Ln();
    $this->pdf->SetFont('helvetica','',8);
    $balance =(float)0;
    foreach($cus_det as $row){
        $aa = $this->pdf->getNumLines($row->description, 55);
        $bb = $this->pdf->getNumLines($row->trans_type, 30);
        if($aa>$bb){
            $heigh=4*$aa;
        }else{
            $heigh=4*$bb;            
        }
        $balance +=(float)$row->dr_amount;
        $balance -=(float)$row->cr_amount; 

        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        $this->pdf->SetX(15);
        $this->pdf->MultiCell(20, $heigh,$row->ddate, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$row->ref_no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(30, $heigh,ucwords(strtolower($row->trans_type)), 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(55, $heigh,ucfirst(strtolower($row->description)), 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$row->dr_amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$row->cr_amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,number_format($balance,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
    } 

    $this->pdf->Ln();
    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->Cell(160, 6,"Balance  ", '0', 0, 'R', 0);
    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->Cell(5, 6,"", '0', 0, 'R', 0);
    $this->pdf->Cell(20, 6,number_format($balance,2), 'TB', 0, 'R', 0);

    $this->pdf->Ln();
    $this->pdf->SetFont('helvetica','B',8);
    
    if(!empty($pd_chq)){
        $this->pdf->Cell(100, 6,"LESS - PD Cheques Given", '0', 0, 'R', 0);
        $this->pdf->Cell(10, 6,"", '0', 0, 'L', 0);
        $this->pdf->Cell(30, 6,"Cheque No ", '0', 0, 'L', 0);
        $this->pdf->Cell(20, 6,"Amount ", '0', 0, 'R', 0);
        $this->pdf->Ln();
        $pd_tot=(float)0;
        $this->pdf->SetFont('helvetica','',9);
        foreach($pd_chq as $row){
            $pd_tot+=(float)$row->amount;
            $this->pdf->MultiCell(110, 2,"", 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(30, 2,$row->cheque_no, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, 5,number_format($row->amount,2), 0, 'R', 0, 1, '', '', true, 0, false, true, 0);
        }
        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(160, 6,"PD Cheques Total ", '0', 0, 'R', 0);
        $this->pdf->SetFont('helvetica','',9);
        $this->pdf->Cell(5, 6,"", '0', 0, 'R', 0);
        $this->pdf->Cell(20, 6,number_format($pd_tot,2), '0', 0, 'R', 0);
        $this->pdf->Ln();

        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(160, 6,"Balance After PD Cheques Given ", '0', 0, 'R', 0);
        $this->pdf->SetFont('helvetica','',9);
        $this->pdf->Cell(5, 6,"", '0', 0, 'R', 0);
        $this->pdf->Cell(20, 6,number_format($balance-$pd_tot,2), 'TB', 0, 'R', 0);
        $this->pdf->Ln();
        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(165, 6,"", '0', 0, 'R', 0);
        $this->pdf->Cell(20, 5,"",'T',0, 'L', 0);
    }

    $this->pdf->Output("Customer Balance".date('Y-m-d').".pdf", 'I');
?>
