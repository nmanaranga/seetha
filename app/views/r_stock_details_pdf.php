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
$this->pdf->Cell(0, 5, 'Stock Movement Summary Report  ',0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(25, 1, "Cluster", '0', 0, 'L', 0);
$this->pdf->Cell(120, 1, ": ".$cl_code." - ".$claster_name, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->Cell(25, 1, "Branch", '0', 0, 'L', 0);
$this->pdf->Cell(120, 1,": ".$b_code." - ".$b_name, '0', 0, 'L', 0);
$this->pdf->Ln();

if($department!=""){
    $this->pdf->Cell(25, 1, "Department", '0', 0, 'L', 0);
    $this->pdf->Cell(120, 1,": ".$department_des, '0', 0, 'L', 0);
    $this->pdf->Ln();
}

if($main_category!=""){
    $this->pdf->Cell(25, 1, "Main Category", '0', 0, 'L', 0);
    $this->pdf->Cell(120, 1,": ".$main_category_des, '0', 0, 'L', 0);
    $this->pdf->Ln();
}

if($sub_category!=""){
    $this->pdf->Cell(25, 1, "Sub Category", '0', 0, 'L', 0);
    $this->pdf->Cell(120, 1,": ".$sub_category_des, '0', 0, 'L', 0);
    $this->pdf->Ln();
}
if($item!=""){
    $this->pdf->Cell(25, 1, "Item", '0', 0, 'L', 0);
    $this->pdf->Cell(120, 1,": ".$item_des, '0', 0, 'L', 0);
    $this->pdf->Ln();
}

$this->pdf->Ln();
$this->pdf->SetFont('helvetica', 'B', 10);
$this->pdf->Cell(0, 1, "Date From " .$from. " To ". $to, '0', 0, 'L', 0);

$this->pdf->Ln();
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->SetX(5);
$this->pdf->Cell(30, 6,"Item", '1', 0, 'C', 0);
$this->pdf->Cell(40, 6,"Description", '1', 0, 'C', 0);
$this->pdf->Cell(25, 6,"Model", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"OP Bal", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"Int.Tr.Rec", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"Pur ", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"Dis.In", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"SRN", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"Int.Tr.Rtn", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"Int.Tr.Out", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"PRN", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"Dis.Out", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"Sales", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"Cl Bal", '1', 0, 'C', 0);
$this->pdf->Cell(25, 6,"Remark", '1', 0, 'C', 0);
$this->pdf->Ln();

foreach ($stock as $value) {
	
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

    $heigh=5*(max(1,$this->pdf->getNumLines($value->description,40),$this->pdf->getNumLines($value->model,25)));
    $this->pdf->HaveMorePages($heigh);
    $this->pdf->SetX(5);
    $this->pdf->SetFont('helvetica','',8);
    $this->pdf->MultiCell(30, $heigh,$value->code,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(40, $heigh,$value->description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(25, $heigh,$value->model,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->opqty,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->trQty,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->grQty,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->diQty,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->srQty,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->irQty,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->tQty,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->prQty,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->doQty,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->csQty,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->clbal,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(25, $heigh,"",  1, 'L', 0, 1, '', '', true, 0, false, true, 0);

}


$this->pdf->Output("Item List".date('Y-m-d').".pdf", 'I');

?>