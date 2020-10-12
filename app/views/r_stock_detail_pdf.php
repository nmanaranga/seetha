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
    $this->pdf->SetFont('helvetica', 'BU',12);
    $this->pdf->Cell(270, 1,"Stock Details Report  ",0,false, 'C', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(35);
    $this->pdf->setX(25);
    $this->pdf->SetFont('helvetica', 'B', 8);
    $this->pdf->Cell(25, 1, "Cluster", '0', 0, 'L', 0);
    $this->pdf->Cell(130, 1, ": ".$cl_code." - ".$claster_name, '0', 0, 'L', 0);
    //$this->pdf->Cell(60, 1,, '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1, "Sub Category", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$s_cat_code." - ".$s_cat, '0', 0, 'L', 0);
    //$this->pdf->Cell(10, 1,, '0', 0, 'L', 0);
    
    $this->pdf->Ln();

    $this->pdf->setX(25);
    $this->pdf->Cell(25, 1, "Branch", '0', 0, 'L', 0);
    $this->pdf->Cell(130, 1,": ".$b_code." - ".$b_name, '0', 0, 'L', 0);
    //$this->pdf->Cell(60, 1,, '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1, "Item", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$i_code." - ".$i_name, '0', 0, 'L', 0);
    //$this->pdf->Cell(10, 1,, '0', 0, 'L', 0);
    
    $this->pdf->Ln();

    $this->pdf->setX(25);
    $this->pdf->Cell(25, 1, "Store", '0', 0, 'L', 0);
    $this->pdf->Cell(130, 1,": ".$s_code." - ".$s_name, '0', 0, 'L', 0);
    //$this->pdf->Cell(60, 1,, '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1, "Unit", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$u_code." - ".$u_name, '0', 0, 'L', 0);
    //$this->pdf->Cell(10, 1,, '0', 0, 'L', 0);
    
    $this->pdf->Ln();

    $this->pdf->setX(25);
    $this->pdf->Cell(25, 1, "Department", '0', 0, 'L', 0);
    $this->pdf->Cell(130, 1,": ".$d_code." - ".$d_name, '0', 0, 'L', 0);
    //$this->pdf->Cell(60, 1,, '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1, "Brand", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$br_code." - ".$br_name, '0', 0, 'L', 0);
    //$this->pdf->Cell(10, 1,, '0', 0, 'L', 0);
    
    $this->pdf->Ln();

    $this->pdf->setX(25);
    $this->pdf->Cell(25, 1, "Main Category", '0', 0, 'L', 0);
    $this->pdf->Cell(130, 1,": ".$m_cat_code." - ".$m_cat, '0', 0, 'L', 0);
    //$this->pdf->Cell(60, 1,, '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1, "Supplier", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$su_code." - ".$su_name, '0', 0, 'L', 0);
    //$this->pdf->Cell(10, 1,, '0', 0, 'L', 0);
    
    $this->pdf->Ln();
 
    $this->pdf->Ln();
    $this->pdf->setY(66);


    foreach($item_det as $row)
    {
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    
        $this->pdf->GetY(40);
        $this->pdf->SetX(25);

        $this->pdf->SetFont('helvetica','',7);
    
        $this->pdf->Cell(25, 6,$row->code, '1', 0, 'L', 0);
        $this->pdf->Cell(70, 6,$row->description, '1', 0, 'L', 0);
        $this->pdf->Cell(25, 6,$row->model, '1', 0, 'L', 0);
        $this->pdf->Cell(22, 6,$row->purchase_price, '1', 0, 'R', 0);
        $this->pdf->Cell(22, 6,$row->max_price, '1', 0, 'R', 0);
        $this->pdf->Cell(20, 6,$row->last_price, '1', 0, 'R', 0);
        $this->pdf->Cell(20, 6,$row->rol, '1', 0, 'R', 0);
        $this->pdf->Cell(20, 6,$row->roq, '1', 0, 'R', 0);
        $this->pdf->Cell(20, 6,$row->supplier, '1', 0, 'L', 0);
       
        $this->pdf->Ln();          
    }  

    $this->pdf->Output("Stock Detils Report".date('Y-m-d').".pdf", 'I');

?>
