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
$this->pdf->Cell(180, 1,"Available Stock Before 6 Month Purchasing   ",0,false, 'L', 0, '', 0, false, 'M', 'M');

$this->pdf->Ln();
$this->pdf->setY(27);$this->pdf->Cell(85, 1,"",'T',0, 'L', 0);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(180, 1,"As At Date - ".$to,0,false, 'L', 0, '', 0, false, 'M', 'M');

$this->pdf->Ln();
$this->pdf->Ln();
$this->pdf->setX(15);
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(25, 1, "Cluster", '0', 0, 'L', 0);
$this->pdf->Cell(80, 1, ": ".$cl_code." - ".$claster_name,'0', 0, 'L', 0);
$this->pdf->Cell(20, 1, "Item", '0', 0, 'L', 0);
$this->pdf->Cell(20, 1,": ".$i_code." - ".$i_name, '0', 0, 'L', 0);

$this->pdf->Ln();

$this->pdf->setX(15);
$this->pdf->Cell(25, 1, "Branch", '0', 0, 'L', 0);
$this->pdf->Cell(80, 1,": ".$b_code." - ".$b_name, '0', 0, 'L', 0);

$this->pdf->Ln();

$this->pdf->setX(15);
$this->pdf->Cell(25, 1, "Store", '0', 0, 'L', 0);
$this->pdf->Cell(80, 1,": ".$s_code." - ".$s_name, '0', 0, 'L', 0);


$this->pdf->setY(51);
$this->pdf->Ln();

$this->pdf->SetX(5);
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(18, 6,"GRN Date", '1', 0, 'C', 0);
$this->pdf->Cell(28, 6,"Item Code", '1', 0, 'C', 0);
$this->pdf->Cell(45, 6,"Item Name",'1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Model ", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6,"Batch ", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6,"Qty ", '1', 0, 'C', 0);
$this->pdf->Cell(18, 6,"Cost", '1', 0, 'C', 0);
$this->pdf->Cell(18, 6,"Min Price", '1', 0, 'C', 0);
$this->pdf->Cell(18, 6,"Max Price", '1', 0, 'C', 0);
$this->pdf->Cell(18, 6,"Total", '1', 0, 'C', 0);
$this->pdf->Ln();

$this->pdf->setY(60.5);
$tot; 
$tot_cost; 
$cost; 
foreach($item_det as $row){
  $this->pdf->HaveMorePages(5);
  $this->pdf->SetX(5);
  $this->pdf->SetFont('helvetica','',8);
  $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

  $aa = $this->pdf->getNumLines($row->item_des, 45);
  $bb = $this->pdf->getNumLines($row->model, 20);
  if($aa>$bb){
    $heigh=5*$aa;
  }else{
    $heigh=5*$bb;
  }
  $cost=(int)$row->qty*(float)$row->purchase_price;
  $this->pdf->MultiCell(18, $heigh, $row->date,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(28, $heigh, $row->item,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(45, $heigh, $row->item_des,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(20, $heigh, $row->model,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(10, $heigh, $row->batch,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(10, $heigh, $row->qty,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(18, $heigh, $row->purchase_price,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(18, $heigh, $row->min_price,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(18, $heigh, $row->max_price,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(18, $heigh, number_format($cost,2),  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
  $this->pdf->SetX(5);
  $this->pdf->SetFont('helvetica','',9);
  $this->pdf->MultiCell(203, 5, $row->serials,  1, 'L', 0, 1, '', '', true, 0, false, true, 0);

  $tot_cost+=$cost;
}
$this->pdf->Ln();
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->HaveMorePages(1);
$this->pdf->SetX(5);
/*$this->pdf->MultiCell(55, 1, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(40, 1, "",  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(20, 1, "",  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(15, 1, "",  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(25, 1, "",  1, 'R', 0, 0, '', '', true, 0, false, true, 0);*/
$this->pdf->MultiCell(150, 1, "",  0, 'R', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->Cell(20, 1, "Total", '0', 0, 'L', 0);
$this->pdf->Cell(3, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, number_format($tot_cost,2), 'TB', 0, 'R', 0);



$this->pdf->Output("Stock In Hand Report".date('Y-m-d').".pdf", 'I');

?>
