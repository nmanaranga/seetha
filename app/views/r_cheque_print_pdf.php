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
            $this->pdf->SetFont('helvetica', 'BU',12);
            $this->pdf->Cell(180, 1,$r_type." Cheques Report  ",0,false, 'C', 0, '', 0, false, 'M', 'M');

            $this->pdf->Ln();
            $this->pdf->SetFont('helvetica', '', 8);
            $this->pdf->Cell(180, 1,"Date From - ".$from." To ".$to,0,false, 'C', 0, '', 0, false, 'M', 'M');

            $this->pdf->Ln();
            $this->pdf->setY(46);
            $this->pdf->setX(25);
            $amount=(float)0;

          
        foreach($chq_det as $row){
            
            
                $this->pdf->GetY(50);
                $this->pdf->SetX(10);
                $this->pdf->SetFont('helvetica','',9);
                $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        
                $aa = $this->pdf->getNumLines($row->acc_name, 40);
                $bb = $this->pdf->getNumLines($row->payee_name, 40);
                if($aa>$bb){
                    $heigh=5*$aa;
                }else{
                    $heigh=5*$bb;
                }
            
                $this->pdf->MultiCell(10, $heigh, $row->voucher_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, $row->acc_no,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(40, $heigh, $row->acc_name,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(15, $heigh, $row->chq_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, $row->payee_id,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(40, $heigh, $row->payee_name,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(30, $heigh, $row->amount,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
                $amount=$amount+ (float)$row->amount;
        }
                 $this->pdf->Ln();
                 

                 $this->pdf->setX(10);
                $this->pdf->MultiCell(10, $heigh, "",  0, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, "",  0, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(40, $heigh, "",  0, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(15, $heigh, "",  0, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, "",  0, 'L', 0, 0, '', '', true, 0, false, true, 0);
                
                $this->pdf->SetFont('helvetica', 'B',10);
                $this->pdf->MultiCell(40, $heigh, "Total",  0, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(30, $heigh, number_format($amount,2),  TB, 'R', 0, 1, '', '', true, 0, false, true, 0);

     

    
           

    $this->pdf->Output("Cheque Report".date('Y-m-d').".pdf", 'I');

?>
