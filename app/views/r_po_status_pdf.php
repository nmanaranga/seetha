<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	$this->pdf->setPrintHeader($header,$type,$duration);
	$this->pdf->setPrintHeader(true,'r_po_status');
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

	$this->pdf->SetFont('helvetica', 'B',12);
 	$this->pdf->Cell(0, 5, 'Purchase Request Status',0,false, 'C', 0, '', 0, false, 'M', 'M');
	$this->pdf->Ln();
	//$this->pdf->Ln();

	$this->pdf->setX(25);
    $this->pdf->SetFont('helvetica', 'B', 10);
    $this->pdf->Cell(25, 1, "Cluster", '0', 0, 'L', 0);
    $this->pdf->Cell(120, 1, ": ".$cl_code." - ".$claster_name, '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1, "Branch", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$b_code." - ".$b_name, '0', 0, 'L', 0);
    $this->pdf->Ln();
    $this->pdf->setX(25);
    $this->pdf->Cell(25, 1, "Date Range", '0', 0, 'L', 0);
    $this->pdf->Cell(120, 1, ": From ".$from." To".$to, '0', 0, 'L', 0);
    //$this->pdf->Cell(120, 1, ": ".$cl_code." - ".$claster_name, '0', 0, 'L', 0);
    $this->pdf->Ln();
   
   
    $this->pdf->SetFont('helvetica', 'B',9);
	//$this->pdf->SetY(66);
    $this->pdf->SetY(68);
	$this->pdf->SetX(25);


    foreach ($customer as $value) {
	
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

	$this->pdf->SetX(5);
	$this->pdf->SetFont('helvetica','',9);

    $aa = $this->pdf->getNumLines($value->description, 60);
    $heigh=6*$aa;
	
  //Purchase Request
    $this->pdf->MultiCell(10, $heigh,$value->nno, '1', 'R',  0, 0, '', '', true, 0, false, true, 0);  
    $this->pdf->MultiCell(35, $heigh,$value->i_code,'1', 'L',  0, 0, '', '', true, 0, false, true, 0);  
    $this->pdf->MultiCell(60, $heigh,$value->description,'1', 'L',  0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(10, $heigh,$value->is_level_0_approved,'1', 'C',  0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(10, $heigh,$value->level_0_approve_qty,'1', 'R',  0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(10, $heigh,$value->is_level_1_approved,'1', 'C',  0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(10, $heigh,$value->level_1_approve_qty, '1', 'R',  0, 0, '', '', true, 0, false, true, 0);

    //PR Approve
    $this->pdf->MultiCell(10, $heigh,$value->nno,'1', 'R',  0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(10, $heigh,$value->is_level_2_approved,'1', 'C',  0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(10, $heigh,$value->level_2_approve_qty, '1', 'R',  0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(10, $heigh,"",'1', 'R',  0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(10, $heigh,"" , '1',  'R',  0, 0, '', '', true, 0, false, true, 0);

    //Purchase Order
    $this->pdf->MultiCell(15, $heigh,$value->orderd_no,'1',  'R',  0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->level_2_approve_qty, '1',  'R',  0, 0, '', '', true, 0, false, true, 0);

    //Purchase 
    $this->pdf->MultiCell(15, $heigh,$value->grn_no,'1', 'R',  0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->grn_qty, '1', 'R',  0, 0, '', '', true, 0, false, true, 0);

    //Internal Transfer
    $this->pdf->MultiCell(15, $heigh,"",'1',  'R',  0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,"", '1', 'R',  0, 1, '', '', true, 0, false, true, 0);

    //$this->pdf->Ln();
    	
    }

	$this->pdf->Output("Purchase order qty received".date('Y-m-d').".pdf", 'I');

?>