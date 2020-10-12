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
$this->pdf->Cell(270, 1,"Stock Aging Report  ",0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->setX(15);
$this->pdf->SetFont('helvetica', 'B', 8);

if($cl_code!=""){
    $this->pdf->Cell(25, 1, "Cluster", '0', 0, 'L', 0);
    $this->pdf->Cell(80, 1, ": ".$cl_code." - ".$claster_name, '0', 0, 'L', 0);
}
if($s_cat_code!=""){
    $this->pdf->Cell(25, 1, "Sub Category", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$s_cat_code." - ".$s_cat, '0', 0, 'L', 0);
}
$this->pdf->Ln();
$this->pdf->setX(15);
if($b_code!=""){
    $this->pdf->Cell(25, 1, "Branch", '0', 0, 'L', 0);
    $this->pdf->Cell(80, 1,": ".$b_code." - ".$b_name, '0', 0, 'L', 0);
}
if($i_code!=""){
    $this->pdf->Cell(25, 1, "Item", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$i_code." - ".$i_name, '0', 0, 'L', 0);
}
$this->pdf->Ln();
$this->pdf->setX(15);
if($s_code!=""){
    $this->pdf->Cell(25, 1, "Store", '0', 0, 'L', 0);
    $this->pdf->Cell(80, 1,": ".$s_code." - ".$s_name, '0', 0, 'L', 0);
}
if($u_code!=""){
    $this->pdf->Cell(25, 1, "Unit", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$u_code." - ".$u_name, '0', 0, 'L', 0);
}
$this->pdf->Ln();
$this->pdf->setX(15);
if($d_code!=""){
    $this->pdf->Cell(25, 1, "Department", '0', 0, 'L', 0);
    $this->pdf->Cell(80, 1,": ".$d_code." - ".$d_name, '0', 0, 'L', 0);
}
if($br_code!=""){
    $this->pdf->Cell(25, 1, "Brand", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$br_code." - ".$br_name, '0', 0, 'L', 0);
}
$this->pdf->Ln();
$this->pdf->setX(15);
if($m_cat_code!=""){
    $this->pdf->Cell(25, 1, "Main Category", '0', 0, 'L', 0);
    $this->pdf->Cell(80, 1,": ".$m_cat_code." - ".$m_cat, '0', 0, 'L', 0);
}
if($su_code!=""){
    $this->pdf->Cell(25, 1, "Supplier", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1,": ".$su_code." - ".$su_name, '0', 0, 'L', 0);
}

$this->pdf->Ln();
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
$this->pdf->Cell(55, 6,"Item", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Store", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"Batch", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6,"Qty", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6,"GRN", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"Bellow 30", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"30 To 60", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"60 To 90", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"Above 90", '1', 0, 'C', 0);
$this->pdf->setY(54);
foreach($item_det as $row)
{
    $heigh=6*(max(1,$this->pdf->getNumLines($row->itemName, 55)));
    $this->pdf->HaveMorePages($heigh);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

    $this->pdf->SetX(15);
    $this->pdf->SetFont('helvetica','',8);
    $this->pdf->MultiCell(20, $heigh,$row->datex, '1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
    $this->pdf->MultiCell(55, $heigh,$row->itemName, '1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
    $this->pdf->MultiCell(20, $heigh,$row->store_code, '1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
    $this->pdf->MultiCell(15, $heigh,$row->batch_no, '1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
    $this->pdf->MultiCell(10, $heigh,$row->qty, '1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
    $this->pdf->MultiCell(10, $heigh,$row->trans_no, '1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
    $this->pdf->MultiCell(15, $heigh,$row->below30, '1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
    $this->pdf->MultiCell(15, $heigh,$row->Over30, '1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
    $this->pdf->MultiCell(15, $heigh,$row->Over60, '1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
    $this->pdf->MultiCell(15, $heigh,$row->Over90, '1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
    $this->pdf->Ln();   
    $qty+=$row->qty;
    $below30+=$row->below30;
    $Over30+=$row->Over30;
    $Over60+=$row->Over60;
    $Over90+=$row->Over90;
}  

$this->pdf->SetX(15);
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->MultiCell(110, $heigh,"Total", '0', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(10, $heigh,$qty, 'TB', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(10, $heigh,"", '0', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(15, $heigh,$below30, 'TB', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(15, $heigh,$Over30, 'TB', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(15, $heigh,$Over60, 'TB', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->MultiCell(15, $heigh,$Over90, 'TB', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
$this->pdf->Ln();  

$this->pdf->Output("Stock Age Report".date('Y-m-d').".pdf", 'I');

?>
