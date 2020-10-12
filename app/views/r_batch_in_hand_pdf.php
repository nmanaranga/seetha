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
$this->pdf->SetFont('helvetica', 'BI',12);
$this->pdf->Cell(180, 1,"Stock In Hand Batch Wise Report  ",0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->setY(27);$this->pdf->Cell(70, 1,"",'T',0, 'L', 0);
$this->pdf->Ln(); 

$this->pdf->setY(35);
$this->pdf->setX(10);
$this->pdf->SetFont('helvetica', 'B', 8);

$this->pdf->Cell(25, 1, "Cluster", '0', 0, 'L', 0);
$this->pdf->Cell(80, 1, ": ".$cl_code." - ".$claster_name, '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, "Sub Category", '0', 0, 'L', 0);
$this->pdf->Cell(20, 1,": ".$s_cat_code." - ".$s_cat, '0', 0, 'L', 0);

$this->pdf->Ln();

$this->pdf->setX(10);
$this->pdf->Cell(25, 1, "Branch", '0', 0, 'L', 0);
$this->pdf->Cell(80, 1,": ".$b_code." - ".$b_name, '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, "Item", '0', 0, 'L', 0);
$this->pdf->Cell(20, 1,": ".$i_code." - ".$i_name, '0', 0, 'L', 0);

$this->pdf->Ln();

$this->pdf->setX(10);
$this->pdf->Cell(25, 1, "Store", '0', 0, 'L', 0);
$this->pdf->Cell(80, 1,": ".$s_code." - ".$s_name, '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, "Unit", '0', 0, 'L', 0);
$this->pdf->Cell(20, 1,": ".$u_code." - ".$u_name, '0', 0, 'L', 0);

$this->pdf->Ln();

$this->pdf->setX(10);
$this->pdf->Cell(25, 1, "Department", '0', 0, 'L', 0);
$this->pdf->Cell(80, 1,": ".$d_code." - ".$d_name, '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, "Brand", '0', 0, 'L', 0);
$this->pdf->Cell(20, 1,": ".$br_code." - ".$br_name, '0', 0, 'L', 0);

$this->pdf->Ln();

$this->pdf->setX(10);
$this->pdf->Cell(25, 1, "Main Category", '0', 0, 'L', 0);
$this->pdf->Cell(80, 1,": ".$m_cat_code." - ".$m_cat, '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, "Supplier", '0', 0, 'L', 0);
$this->pdf->Cell(20, 1,": ".$su_code." - ".$su_name, '0', 0, 'L', 0);

$this->pdf->Ln();


$this->pdf->setY(66);


$code="default";

foreach($item_det as $row){


  $this->pdf->SetX(5);
  $this->pdf->SetFont('helvetica','',7);
  $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));


  $tot_price2=$row->qty*$row->b_price;
  $heigh1=6*(max(1,$this->pdf->getNumLines($row->description, 45)));
  $this->pdf->HaveMorePages($heigh1);
  $this->pdf->SetX(5);
  $this->pdf->MultiCell(25, $heigh1,$row->item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(45, $heigh1,$row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(20, $heigh1,$row->model, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(12, $heigh1,$row->batch_no, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(8,  $heigh1,$row->qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(12, $heigh1,$row->item_tot, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(12, $heigh1,$row->sr_count, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(16, $heigh1,$row->b_price, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(16, $heigh1,$row->b_min, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(16, $heigh1,$row->b_max, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(16, $heigh1,number_format($tot_price2,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);

  $tot_cost=(float)$tot_cost+(float)$tot_price;
  $code=$row->item;          
}
$this->pdf->SetX(5);
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(150, 6,'', '0', 0, 'R', 0);
$this->pdf->Cell(16, 6,'Total', '0', 0, 'R', 0);
$this->pdf->Cell(32, 6,number_format($f_tot,2), 'TB', 0, 'R', 0);

$this->pdf->Output("Batch In Hand Report".date('Y-m-d').".pdf", 'I');

?>
