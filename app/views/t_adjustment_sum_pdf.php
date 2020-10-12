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

        foreach($store as $st){
            $st_code=$st->code;
            $st_name=$st->description;
        }
     

            $this->pdf->setY(25);
            $this->pdf->SetFont('helvetica', 'BU',10);
            $this->pdf->Cell(180, 1,"Stock Adjustment  ",0,false, 'C', 0, '', 0, false, 'M', 'M');
            $this->pdf->Ln();

            $this->pdf->setY(35);
            $this->pdf->setX(25);
            $this->pdf->SetFont('helvetica', 'B', 8);

            $this->pdf->Cell(25, 1, "Memo", '0', 0, 'L', 0);
            $this->pdf->SetFont('helvetica', '', 8);
            $this->pdf->Cell(80, 1, $memo, '0', 0, 'L', 0);
            $this->pdf->SetFont('helvetica', 'B', 8);
            $this->pdf->Cell(20, 1, "No", '0', 0, 'L', 0);
            $this->pdf->SetFont('helvetica', '', 8);
            $this->pdf->Cell(20, 1, $qno, '0', 0, 'L', 0);
            
            $this->pdf->Ln();

            $this->pdf->setX(25);
            $this->pdf->SetFont('helvetica', 'B', 8);
            $this->pdf->Cell(25, 1, "Store", '0', 0, 'L', 0);
            $this->pdf->SetFont('helvetica', '', 8);
            $this->pdf->Cell(80, 1, $st_code." - ".$st_name, '0', 0, 'L', 0);
            $this->pdf->SetFont('helvetica', 'B', 8);
            $this->pdf->Cell(20, 1, "Date", '0', 0, 'L', 0);
            $this->pdf->SetFont('helvetica', '', 8);
            $this->pdf->Cell(20, 1, $dt, '0', 0, 'L', 0);
                      
                      
            $this->pdf->Ln();

            $this->pdf->Ln();
            $this->pdf->setY(56);

        foreach($items as $row){
                $aa = $this->pdf->getNumLines($row->description, 40);
                $heigh=5*$aa;
                $this->pdf->GetY();
                $this->pdf->SetX(5);
                $this->pdf->SetFont('helvetica','',8);
                $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
                $this->pdf->MultiCell(33, $heigh, $row->code,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(40, $heigh, $row->description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(12, $heigh, $row->batch_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(15, $heigh, $row->cur_qty,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(10, $heigh, $row->f_qty,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(10, $heigh, $row->s_qty,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(10, $heigh, $row->t_qty,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(17, $heigh, $row->difference,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->cost,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->total,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(15, $heigh, $row->Status,  1, 'L', 0, 1, '', '', true, 0, false, true, 0);
        }
    

    $this->pdf->Output("Item Adjustment".date('Y-m-d').".pdf", 'I');

?>
