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
 	$this->pdf->Cell(0, 5, 'Item Price List  ',0,false, 'C', 0, '', 0, false, 'M', 'M');
	$this->pdf->Ln();
	$this->pdf->Ln();

			
    $this->pdf->setX(25);
    $this->pdf->SetFont('helvetica', 'B', 8);
    $this->pdf->Cell(25, 1, "Cluster", '0', 0, 'L', 0);
    $this->pdf->Cell(80, 1, ": ".$cl_code." - ".$claster_name, '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1, "Sub Category", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$s_cat_code." - ".$s_cat, '0', 0, 'L', 0);
    
    $this->pdf->Ln();

    $this->pdf->setX(25);
    $this->pdf->Cell(25, 1, "Branch", '0', 0, 'L', 0);
    $this->pdf->Cell(80, 1,": ".$b_code." - ".$b_name, '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1, "Item", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$i_code." - ".$i_name, '0', 0, 'L', 0);
    
    $this->pdf->Ln();

    $this->pdf->setX(25);
    $this->pdf->Cell(25, 1, "Store", '0', 0, 'L', 0);
    $this->pdf->Cell(80, 1,": ".$s_code." - ".$s_name, '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1, "Unit", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$u_code." - ".$u_name, '0', 0, 'L', 0);
    
    $this->pdf->Ln();

    $this->pdf->setX(25);
    $this->pdf->Cell(25, 1, "Department", '0', 0, 'L', 0);
    $this->pdf->Cell(80, 1,": ".$d_code." - ".$d_name, '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1, "Brand", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$br_code." - ".$br_name, '0', 0, 'L', 0);
    
    $this->pdf->Ln();

    $this->pdf->setX(25);
    $this->pdf->Cell(25, 1, "Main Category", '0', 0, 'L', 0);
    $this->pdf->Cell(80, 1,": ".$m_cat_code." - ".$m_cat, '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1, "Supplier", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$su_code." - ".$su_name, '0', 0, 'L', 0);
    
    $this->pdf->Ln();
 
    $this->pdf->Ln();
    $this->pdf->setY(66);


     $this->pdf->SetX(16);
     $this->pdf->SetFont('helvetica','B',8);

     $this->pdf->Cell(28, 6,"Item Id", '1', 0, 'R', 0);
        $this->pdf->Cell(50, 6,"Item", '1', 0, 'R', 0);
        $this->pdf->Cell(25, 6,"Cost",'1', 0, 'R', 0);
        $this->pdf->Cell(25, 6,"Max Sales Price",'1', 0, 'R', 0);
        $this->pdf->Cell(18, 6,"Margin",'1', 0, 'R', 0);
        $this->pdf->Cell(25, 6,"Last Sales Price",'1', 0, 'R', 0);
        $this->pdf->Cell(18, 6,"Margin",'1', 0, 'R', 0);       
     $this->pdf->Ln();
		 				

	foreach ($customer as $value) {

		$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        $margin1=number_format($value->Margin,2);
        $margin2=number_format($value->Margin2,2);

		$this->pdf->SetX(16);
		$this->pdf->SetFont('helvetica','',8);
		$this->pdf->Cell(28, 12,$value->Item_Id, '1', 0, 'R', 0);
        $this->pdf->Cell(50, 12,$value->Item, '1', 0, 'R', 0);
		$this->pdf->Cell(25, 12,$value->Cost,'1', 0, 'R', 0);
        $this->pdf->Cell(25, 12,$value->Max_Sales_Price,'1', 0, 'R', 0);
        $this->pdf->Cell(18, 12,$margin1,'1', 0, 'R', 0);
        $this->pdf->Cell(25, 12,$value->Last_Sales_Price,'1', 0, 'R', 0);
        $this->pdf->Cell(18, 12,$margin2,'1', 0, 'R', 0);        
		$this->pdf->Ln();

	}

                   	



	//$this->pdf->footerSet($employee,$amount,$additional,$discount,$user);
	$this->pdf->Output("Cash Sales_".date('Y-m-d').".pdf", 'I');

?>