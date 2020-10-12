<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintHeader(true,$type);
$this->pdf->setPrintFooter(true);

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage("L","A3"); 

$branch_name="";

foreach($branch as $ress){
    $branch_name=$ress->name;
    $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

$this->pdf->setY(25);
$this->pdf->SetFont('helvetica', 'BI',12);
$this->pdf->Cell(180, 1,"Internal Transfer Status Report ",0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->setY(27);
$this->pdf->Cell(55, 1,"",'T',0, 'L', 0);
$this->pdf->Ln(); 

$this->pdf->SetFont('helvetica', '',10);
$this->pdf->Cell(180, 1,"Date From ".$from."  To ".$to,0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->setY(40);
$this->pdf->setX(5);
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->Cell(96, 6,"Internal Transfer Order (Requesting Branch)", '1', 0, 'C', 0);
$this->pdf->Cell(96, 6,"Internal Transfer (Sending Branch)", '1', 0, 'C', 0);
$this->pdf->Cell(96, 6,"Internal Receipt (Receiving Branch)", '1', 0, 'C', 0); 
$this->pdf->Cell(96, 6,"Internal Transfer Return (Return Branch)", '1', 0, 'C', 0); 
$this->pdf->Cell(30, 6,"Variances", '1', 0, 'C', 0);  
$this->pdf->Ln();
$this->pdf->setX(5);
$this->pdf->Cell(18, 6,"Date", 'TLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"BC", 'TLR', 0, 'C', 0);
$this->pdf->Cell(8, 6,"No", 'TLR', 0, 'C', 0); 
$this->pdf->Cell(60, 6,"Request", '1', 0, 'C', 0);

$this->pdf->Cell(18, 6,"Date", 'TLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"BC", 'TLR', 0, 'C', 0);
$this->pdf->Cell(8, 6,"No", 'TLR', 0, 'C', 0); 
$this->pdf->Cell(60, 6,"Request", '1', 0, 'C', 0);

$this->pdf->Cell(18, 6,"Date", 'TLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"BC", 'TLR', 0, 'C', 0);
$this->pdf->Cell(8, 6,"No", 'TLR', 0, 'C', 0); 
$this->pdf->Cell(60, 6,"Request", '1', 0, 'C', 0);

$this->pdf->Cell(18, 6,"Date", 'TLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"BC", 'TLR', 0, 'C', 0);
$this->pdf->Cell(8, 6,"No", 'TLR', 0, 'C', 0); 
$this->pdf->Cell(60, 6,"Request", '1', 0, 'C', 0);

$this->pdf->Cell(10, 6,"Qty", 'TLR', 0, 'C', 0); 
$this->pdf->Cell(20, 6,"Value", 'TLR', 0, 'C', 0);


$this->pdf->Ln();
$this->pdf->setX(5);

$this->pdf->Cell(18, 6,"", 'BLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"", 'BLR', 0, 'C', 0);
$this->pdf->Cell(8, 6,"", 'BLR', 0, 'C', 0); 
$this->pdf->Cell(30, 6,"Code", '1', 0, 'C', 0);
$this->pdf->Cell(8, 6,"Qty", '1', 0, 'C', 0);
$this->pdf->Cell(22, 6,"Value", '1', 0, 'C', 0);

$this->pdf->Cell(18, 6,"", 'BLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"", 'BLR', 0, 'C', 0);
$this->pdf->Cell(8, 6,"", 'BLR', 0, 'C', 0); 
$this->pdf->Cell(30, 6,"Code", '1', 0, 'C', 0);
$this->pdf->Cell(8, 6,"Qty", '1', 0, 'C', 0);
$this->pdf->Cell(22, 6,"Value", '1', 0, 'C', 0);

$this->pdf->Cell(18, 6,"", 'BLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"", 'BLR', 0, 'C', 0);
$this->pdf->Cell(8, 6,"", 'BLR', 0, 'C', 0); 
$this->pdf->Cell(30, 6,"Code", '1', 0, 'C', 0);
$this->pdf->Cell(8, 6,"Qty", '1', 0, 'C', 0);
$this->pdf->Cell(22, 6,"Value", '1', 0, 'C', 0);

$this->pdf->Cell(18, 6,"", 'BLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"", 'BLR', 0, 'C', 0);
$this->pdf->Cell(8, 6,"", 'BLR', 0, 'C', 0); 
$this->pdf->Cell(30, 6,"Code", '1', 0, 'C', 0);
$this->pdf->Cell(8, 6,"Qty", '1', 0, 'C', 0);
$this->pdf->Cell(22, 6,"Value", '1', 0, 'C', 0);

$this->pdf->Cell(10, 6,"", 'BLR', 0, 'C', 0); 
$this->pdf->Cell(20, 6,"", 'BLR', 0, 'C', 0);
$this->pdf->Ln();

foreach($details as $row){
    $this->pdf->SetFont('helvetica','',8);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    $aa = max($this->pdf->getNumLines($row->o_item, 35),$this->pdf->getNumLines($row->i_item, 35),$this->pdf->getNumLines($row->r_item, 35),$this->pdf->getNumLines($row->o_amount, 27),$this->pdf->getNumLines($row->i_amount, 27),$this->pdf->getNumLines($row->r_amount, 27));
    $heigh=5*$aa;
    $this->pdf->haveMorePages($heigh);
    $this->pdf->setX(5);
    $dif_qty = $row->r_qty - $row->i_qty+$row->rt_qty;
    $dif_val = $row->i_amount - $row->r_amount - $row->rt_amount;
    $this->pdf->MultiCell(18, $heigh,$row->o_date, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(10, $heigh,$row->o_bc, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(8, $heigh,$row->o_nno, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(30, $heigh,$row->o_item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);   
    $this->pdf->MultiCell(8, $heigh,$row->o_qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);  
    $this->pdf->MultiCell(22, $heigh,number_format($row->o_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0); 

    if($row->i_date==""){
        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->MultiCell(96,$heigh, "Items Not Issued", 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->SetFont('helvetica','',8);
    }else{
        $this->pdf->MultiCell(18, $heigh,$row->i_date, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(10, $heigh,$row->i_bc, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(8, $heigh,$row->i_nno, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(30, $heigh,$row->i_item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);   
        $this->pdf->MultiCell(8, $heigh,$row->i_qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);  
        $this->pdf->MultiCell(22, $heigh,number_format($row->i_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0); 
    }


    if($row->r_date==""){
        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->MultiCell(96,$heigh, "Items Not Received", 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->SetFont('helvetica','',8);
    }else{
        $this->pdf->MultiCell(18, $heigh,$row->r_date, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(10, $heigh,$row->r_bc, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(8, $heigh,$row->r_nno, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(30, $heigh,$row->r_item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);   
        $this->pdf->MultiCell(8, $heigh,$row->r_qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);  
        $this->pdf->MultiCell(22, $heigh,number_format($row->r_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);   
    }

    if($row->rt_date==""){
        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->MultiCell(96,$heigh, "Items Not Returned", 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->SetFont('helvetica','',8);
    }else{
        $this->pdf->MultiCell(18, $heigh,$row->rt_date, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(10, $heigh,$row->rt_bc, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(8, $heigh,$row->rt_nno, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(30, $heigh,$row->rt_item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);   
        $this->pdf->MultiCell(8, $heigh,$row->rt_qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);  
        $this->pdf->MultiCell(22, $heigh,number_format($row->rt_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);   
    }


    $this->pdf->MultiCell(10, $heigh,$dif_qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh,number_format($dif_val,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);

}


$this->pdf->Output("Customer Balance".date('Y-m-d').".pdf", 'I');
?>
