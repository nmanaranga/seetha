<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintHeader(true,$type);
$this->pdf->setPrintFooter(true);

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

foreach($branch as $ress){
	$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
foreach ($purchase as $value){
	$inv_no=$value->nno;
	$name=$value->name;
}

foreach ($category as $cat){
	$code=$cat->code;
	$des=$cat->description;
}

$this->pdf->setY(22);

$this->pdf->SetFont('helvetica', 'BU',10);
$this->pdf->Cell(0, 5, 'TOTAL CREDIT SALES SUMMARY 02',0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', '',9);
$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();


if($category_field!="0"){
	$this->pdf->SetX(5);
	$this->pdf->SetFont('helvetica', 'B',8);
	$this->pdf->Cell(45, 6,"Category : ".$code." - ".$des, '0', 0, 'R', 0);
	$this->pdf->Ln();
}
if($group!=""){
	$this->pdf->SetX(5);
	$this->pdf->SetFont('helvetica', 'B',8);
	$this->pdf->Cell(45, 6,"Group : ".$group." - ".$group_des, '0', 0, 'R', 0);
	$this->pdf->Ln();
}

$this->pdf->Ln();
//$this->pdf->Ln();



$this->pdf->setX(5);
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
$this->pdf->Cell(12, 6,"In No", '1', 0, 'C', 0);
$this->pdf->Cell(50, 6,"Customer", '1', 0, 'C', 0);
$this->pdf->Cell(30, 6,"Purchase Price Total", '1', 0, 'C', 0);
$this->pdf->Cell(30, 6,"Min Price Total", '1', 0, 'C', 0);
$this->pdf->Cell(30, 6,"Max Price Total", '1', 0, 'C', 0);
$this->pdf->Cell(30, 6,"Net Amount", '1', 0, 'C', 0);
$this->pdf->Ln();

$purchase_tot =0;
$min_tot=0;
$max_tot=0;
$net_tot=0;

$this->pdf->SetFont('helvetica','',8);
foreach($data as $row){
	$heigh=6*(max(1,$this->pdf->getNumLines($row->name,50)));
	$this->pdf->HaveMorePages($heigh);
	$this->pdf->setX(5);
	
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
	$this->pdf->MultiCell(20,$heigh,$row->ddate,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(12,$heigh,$row->nno,1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(50,$heigh,$row->name,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(30,$heigh,number_format($row->purchase_tot,2),1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(30,$heigh,number_format($row->min_tot,2),1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(30,$heigh,number_format($row->max_tot,2),1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(30,$heigh,number_format($row->net_amount,2),1, 'R',false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);

	$purchase_tot +=$row->purchase_tot;
	$min_tot+=$row->min_tot;
	$max_tot+=$row->max_tot;
	$net_tot+=$row->net_amount;
}

$this->pdf->SetFont('helvetica','B',8);
$this->pdf->SetX(5);
$this->pdf->Cell(82, 6,"Total", '0', 0, 'R', 0);
$this->pdf->Cell(30, 6,number_format($purchase_tot,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(30, 6,number_format($min_tot,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(30, 6,number_format($max_tot,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(30, 6,number_format($net_tot,2), 'TB', 0, 'R', 0);


$this->pdf->Output("Credit Sale Summary".date('Y-m-d').".pdf", 'I');

?>