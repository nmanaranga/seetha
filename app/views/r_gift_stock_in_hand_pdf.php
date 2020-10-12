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

        foreach($clus as $cl){
            $claster_name=$cl->description;
            $cl_code=$cl->code;
        }
        foreach($bran as $b){
            $b_name=$b->name;
            $b_code=$b->bc;
        }
        foreach($str as $s){
            $s_name=$s->description;
            $s_code=$s->code;
        }
        
         foreach($itm as $it){
            $i_name=$it->description;
            $i_code=$it->code;
        }
         
     

            $this->pdf->setY(25);
            $this->pdf->setX(16);
             $this->pdf->SetFont('helvetica', 'BI',12);
            $this->pdf->Cell(180, 1,"GIFT VOUCHER-STOCK IN HAND REPORT   ",0,false, 'L', 0, '', 0, false, 'M', 'M');

            $this->pdf->Ln();
            $this->pdf->setX(17);
            $this->pdf->setY(27);$this->pdf->Cell(95, 1,"",'T',0, 'L', 0);
            $this->pdf->Ln();

            $this->pdf->SetFont('helvetica', '', 8);
            $this->pdf->Cell(180, 1,"As At Date - ".$to,0,false, 'C', 0, '', 0, false, 'M', 'M');

            $this->pdf->Ln();
            $this->pdf->setY(35);
            $this->pdf->setX(16);
            $this->pdf->SetFont('helvetica', 'B', 8);
            $this->pdf->Cell(25, 1, "Cluster", '0', 0, 'L', 0);
            $this->pdf->Cell(80, 1, ": ".$cl_code." - ".$claster_name,'0', 0, 'L', 0);
            
            $this->pdf->Ln();

            $this->pdf->setX(16);
            $this->pdf->Cell(25, 1, "Branch", '0', 0, 'L', 0);
            $this->pdf->Cell(80, 1,": ".$b_code." - ".$b_name, '0', 0, 'L', 0);
            $this->pdf->Ln();
             $this->pdf->setX(16);
            $this->pdf->Cell(25, 1, "Item", '0', 0, 'L', 0);
            $this->pdf->Cell(80, 1,": ".$i_code." - ".$i_name, '0', 0, 'L', 0);
            
            $this->pdf->Ln();

            $this->pdf->setX(16);
            $this->pdf->Cell(25, 1, "Store", '0', 0, 'L', 0);
            $this->pdf->Cell(80, 1,": ".$s_code." - ".$s_name, '0', 0, 'L', 0);
           
            $this->pdf->Ln();
            $this->pdf->setY(66);

        $tot; 
        $tot_cost; 
        $cost; 
        foreach($item_det as $row){
            
            
                $this->pdf->GetY(40);
                $this->pdf->SetX(16);
                $this->pdf->SetFont('helvetica','',9);
                $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        
                $aa = $this->pdf->getNumLines($row->description, 40);
                $bb = $this->pdf->getNumLines($row->model, 20);
                if($aa>$bb){
                    $heigh=5*$aa;
                }else{
                    $heigh=5*$bb;
                }
                $cost=(int)$row->qty*(float)$row->purchase_price;
                $this->pdf->MultiCell(35, $heigh, $row->code,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(50, $heigh, $row->description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(15, $heigh, $row->qty,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, $row->purchase_price,  1, 'R', 0, 0, '', '', true, 0, false, true, 0); 
                $this->pdf->MultiCell(20, $heigh, $row->max_price,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(30, $heigh, number_format($cost,2),  1, 'R', 0, 1, '', '', true, 0, false, true, 0);

     
                $tot=$tot+$row->qty;
                $tot_cost=(float)$tot_cost+(float)$cost;

        //$x++;
        }
        $this->pdf->Ln();
         $this->pdf->SetFont('helvetica','B',9);
    $this->pdf->SetX(16);    
    $this->pdf->MultiCell(35, 1, "",  0, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(50, 1, "",  0, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, 1, "",  0, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(25, 1, "",  0, 'R', 0, 0, '', '', true, 0, false, true, 0);
   
    

 

    $this->pdf->Cell(20, 1, "Total", '0', 0, 'L', 0);
    $this->pdf->Cell(30, 1, number_format($tot_cost,2), 'TB', 0, 'R', 0);

           

    $this->pdf->Output("Gift Voucher - Stock In Hand Report".date('Y-m-d').".pdf", 'I');

?>
