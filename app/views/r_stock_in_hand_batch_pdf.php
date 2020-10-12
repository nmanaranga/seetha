<?php
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    $this->pdf->setPrintHeader($header,'r_stock_in_hand_batch',$duration);
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
            $this->pdf->Cell(180, 1,"Stock In Hand Batch Wise Report  ",0,false, 'L', 0, '', 0, false, 'M', 'M');
            $this->pdf->Ln();

            $this->pdf->setY(27);$this->pdf->Cell(70, 1,"",'T',0, 'L', 0);
            $this->pdf->Ln(); 

        
         
                $code="default";
                $this->pdf->SetY(46);
                $this->pdf->SetX(15);
            foreach($item_det as $row){
               $tot_price=$row->qty*$row->b_price; 
                $this->pdf->SetFont('helvetica','',7);
                $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        
                if($code!="default" && $code==$row->item){
                    

                    $this->pdf->Cell(25, 6,'', '0', 0, 'L', 0);
                    $this->pdf->Cell(80, 6,'', '0', 0, 'L', 0);
                    $this->pdf->Cell(25, 6,'', '0', 0, 'L', 0);
                    $this->pdf->Cell(15, 6,$row->batch_no, '1', 0, 'R', 0);
                    $this->pdf->Cell(15, 6,$row->qty, '1', 0, 'R', 0);
                    $this->pdf->Cell(15, 6,$row->item_tot, '1', 0, 'R', 0);
                    $this->pdf->Cell(25, 6,$row->b_price, '1', 0, 'R', 0);
                    $this->pdf->Cell(25, 6,$row->b_min, '1', 0, 'R', 0);
                    $this->pdf->Cell(25, 6,$row->b_max, '1', 0, 'R', 0);
                    $this->pdf->Cell(25, 6,number_format($tot_price,2), '1', 0, 'R', 0);

                    $this->pdf->Ln();
                   
                }else{

                    $heigh1=6*(max(1,$this->pdf->getNumLines($row->description, 80)));
                    $this->pdf->HaveMorePages($heigh1);
                    $this->pdf->SetX(15);

                    $this->pdf->MultiCell(25, $heigh1,$row->item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                    $this->pdf->MultiCell(80, $heigh1,$row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                    $this->pdf->MultiCell(25, $heigh1,$row->model, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                    $this->pdf->MultiCell(15, $heigh1,$row->batch_no, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                    $this->pdf->MultiCell(15, $heigh1,$row->qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                    $this->pdf->MultiCell(15, $heigh1,$row->item_tot, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                    $this->pdf->MultiCell(25, $heigh1,$row->b_price, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                    $this->pdf->MultiCell(25, $heigh1,$row->b_min, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                    $this->pdf->MultiCell(25, $heigh1,$row->b_max, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                    $this->pdf->MultiCell(25, $heigh1,number_format($tot_price,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);

            }
            $code=$row->item;          
        }

           

    $this->pdf->Output("Stock In Hand Report".date('Y-m-d').".pdf", 'I');

?>
