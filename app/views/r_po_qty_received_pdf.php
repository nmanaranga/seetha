<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	$this->pdf->setPrintHeader($header,$type,$duration);
	$this->pdf->setPrintHeader(true,$type);
    $this->pdf->setPrintFooter(true);
    //print_r($customer);
    $this->pdf->SetFont('helvetica', 'B', 16);
	$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

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
 	$this->pdf->Cell(0, 5, 'Purchase Order Quantity To Be Received  ',0,false, 'C', 0, '', 0, false, 'M', 'M');
	$this->pdf->Ln();
	$this->pdf->Ln();

	$this->pdf->setX(25);
    $this->pdf->SetFont('helvetica', 'B', 8);
    $this->pdf->Cell(25, 1, "Cluster", '0', 0, 'L', 0);
    $this->pdf->Cell(120, 1, ": ".$cl_code." - ".$claster_name, '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1, "Branch", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$b_code." - ".$b_name, '0', 0, 'L', 0);
    $this->pdf->Ln();
    $this->pdf->Ln();
    $this->pdf->Ln();
   
    $this->pdf->Ln();


    $this->pdf->Ln();

    $this->pdf->SetFont('helvetica', 'B', 8);
	$this->pdf->SetY(66);
	$this->pdf->SetX(25);


    foreach ($customer as $value) {
	
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

	$this->pdf->SetX(15);
	$this->pdf->SetFont('helvetica','',8);
	
	$this->pdf->Cell(60, 6,$value->supplier." - ".$value->name , '1', 0, 'L', 0);
    $this->pdf->Cell(15, 6,$value->nno, '1', 0, 'R', 0);
    $this->pdf->Cell(20, 6,$value->ddate,'1', 0, 'L', 0);
	$this->pdf->Cell(35, 6,$value->item,'1', 0, 'L', 0);
	$this->pdf->Cell(75, 6,$value->description,'1', 0, 'L', 0);
    $this->pdf->Cell(20, 6,$value->qty, '1', 0, 'R', 0);
    $this->pdf->Cell(20, 6,$value->Received,'1', 0, 'R', 0);
    $this->pdf->Cell(20, 6,$value->ToBeReceive , '1', 0, 'R', 0);
    $this->pdf->Ln();
    	
    }

	$this->pdf->Output("Purchase order qty received".date('Y-m-d').".pdf", 'I');

?>