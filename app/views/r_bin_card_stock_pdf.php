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
        foreach($dp as $d){
            $d_name=$d->description;
            $d_code=$d->code;
        }
        foreach($cat as $mc){
            $m_cat=$mc->description;
            $m_cat_code=$mc->code;
        }
        foreach($scat as $sc){
            $s_cat=$sc->description;
            $s_cat_code=$sc->code;
        }
         foreach($itm as $it){
            $i_name=$it->description;
            $i_code=$it->code;
        }
         foreach($unt as $u){
            $u_name=$u->description;
            $u_code=$u->code;
        }
         foreach($brnd as $br){
            $br_name=$br->description;
            $br_code=$br->code;
        }
         foreach($sup as $su){
            $su_name=$su->name;
            $su_code=$su->code;
        }


            $this->pdf->setY(25);
            $this->pdf->SetFont('helvetica', 'BU',10);
            $this->pdf->Cell(180, 1,"Bin Card ",0,false, 'C', 0, '', 0, false, 'M', 'M');
            $this->pdf->Ln();

            $this->pdf->setY(35);
            $this->pdf->setX(20);
            $this->pdf->SetFont('helvetica', 'B', 8);
            $this->pdf->Cell(25, 1, "Cluster", '0', 0, 'L', 0);
            $this->pdf->Cell(80, 1, ": ".$cl_code." - ".$claster_name, '0', 0, 'L', 0);
            //$this->pdf->Cell(60, 1,, '0', 0, 'L', 0);
            $this->pdf->Cell(20, 1, "Sub Category", '0', 0, 'L', 0);
            $this->pdf->Cell(20, 1,": ".$s_cat_code." - ".$s_cat, '0', 0, 'L', 0);
            //$this->pdf->Cell(10, 1,, '0', 0, 'L', 0);
            
            $this->pdf->Ln();

            $this->pdf->setX(20);
            $this->pdf->Cell(25, 1, "Branch", '0', 0, 'L', 0);
            $this->pdf->Cell(80, 1,": ".$b_code." - ".$b_name, '0', 0, 'L', 0);
            //$this->pdf->Cell(60, 1,, '0', 0, 'L', 0);
            $this->pdf->Cell(20, 1, "Item", '0', 0, 'L', 0);
            $this->pdf->Cell(20, 1,": ".$i_code." - ".$i_name, '0', 0, 'L', 0);
            //$this->pdf->Cell(10, 1,, '0', 0, 'L', 0);
            
            $this->pdf->Ln();

            $this->pdf->setX(20);
            $this->pdf->Cell(25, 1, "Store", '0', 0, 'L', 0);
            $this->pdf->Cell(80, 1,": ".$s_code." - ".$s_name, '0', 0, 'L', 0);
            //$this->pdf->Cell(60, 1,, '0', 0, 'L', 0);
            $this->pdf->Cell(20, 1, "Unit", '0', 0, 'L', 0);
            $this->pdf->Cell(20, 1,": ".$u_code." - ".$u_name, '0', 0, 'L', 0);
            //$this->pdf->Cell(10, 1,, '0', 0, 'L', 0);
            
            $this->pdf->Ln();

            $this->pdf->setX(20);
            $this->pdf->Cell(25, 1, "Department", '0', 0, 'L', 0);
            $this->pdf->Cell(80, 1,": ".$d_code." - ".$d_name, '0', 0, 'L', 0);
            //$this->pdf->Cell(60, 1,, '0', 0, 'L', 0);
            $this->pdf->Cell(20, 1, "Brand", '0', 0, 'L', 0);
            $this->pdf->Cell(20, 1,": ".$br_code." - ".$br_name, '0', 0, 'L', 0);
            //$this->pdf->Cell(10, 1,, '0', 0, 'L', 0);
            
            $this->pdf->Ln();

            $this->pdf->setX(20);
            $this->pdf->Cell(25, 1, "Main Category", '0', 0, 'L', 0);
            $this->pdf->Cell(80, 1,": ".$m_cat_code." - ".$m_cat, '0', 0, 'L', 0);
            //$this->pdf->Cell(60, 1,, '0', 0, 'L', 0);
            $this->pdf->Cell(20, 1, "Supplier", '0', 0, 'L', 0);
            $this->pdf->Cell(20, 1,": ".$su_code." - ".$su_name, '0', 0, 'L', 0);
            //$this->pdf->Cell(10, 1,, '0', 0, 'L', 0);
            
            $this->pdf->Ln();
         
            $this->pdf->Ln();
            $this->pdf->setY(66);

         foreach($op_detail as $row)
         {
            $this->pdf->setX(20);
            $this->pdf->GetY(20);
            $this->pdf->SetFont('helvetica','B',7);
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        
            $this->pdf->Cell(25, 6,$row->ddate, '1', 0, 'L', 0);
            $this->pdf->Cell(60, 6,'Opening Balance', '1', 0, 'L', 0);
            $this->pdf->Cell(18, 6,'', '1', 0, 'L', 0);
            $this->pdf->Cell(18, 6,'', '1', 0, 'L', 0);
            $this->pdf->Cell(18, 6,'', '1', 0, 'R', 0);
            $this->pdf->Cell(18, 6,'', '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$row->op, '1', 0, 'R', 0);  
            $bal=(int)$row->op;  
         }
     
          $this->pdf->setY(72);

        foreach($item_det as $row)
        {
            $in=(int)$row->qty_in;
            $out=(int)$row->qty_out;

            $bal = $bal + $in;
            $bal = $bal - $out;
            $tot_in = $tot_in + $in;
            $tot_out = $tot_out + $out;

            $this->pdf->GetY(40);
            $this->pdf->SetX(20);

            $this->pdf->SetFont('helvetica','',7);
        
            $this->pdf->Cell(25, 6,$row->ddate, '1', 0, 'L', 0);
            $this->pdf->Cell(60, 6,ucwords(strtolower($row->trans_des)), '1', 0, 'L', 0);
            $this->pdf->Cell(18, 6,$row->trans_no, '1', 0, 'R', 0);
            $this->pdf->Cell(18, 6,$row->sub_trans_no, '1', 0, 'R', 0);
            $this->pdf->Cell(18, 6,$row->qty_in, '1', 0, 'R', 0);
            $this->pdf->Cell(18, 6,$row->qty_out, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$bal, '1', 0, 'R', 0);
           
            $this->pdf->Ln();          
        } 
            $this->pdf->SetX(20);
            $this->pdf->SetFont('helvetica','B',7);
            $this->pdf->Cell(25, 6,'', '1', 0, 'L', 0);
            $this->pdf->Cell(60, 6,'Ending Balance', '1', 0, 'L', 0);
            $this->pdf->Cell(18, 6,'', '1', 0, 'L', 0);
            $this->pdf->Cell(18, 6,'', '1', 0, 'L', 0);
            $this->pdf->Cell(18, 6,$tot_in, '1', 0, 'R', 0);
            $this->pdf->Cell(18, 6,$tot_out, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$bal, '1', 0, 'R', 0);

    $this->pdf->Output("Stock In Hand Report".date('Y-m-d').".pdf", 'I');

?>
