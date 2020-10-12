<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
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

foreach ($sum as $value) {
	$from = $value->f_cl." - ".$value->f_bc."( ".$value->f_bc_name." )";
}

$this->pdf->setY(22);

$this->pdf->SetFont('helvetica', 'BI',12);
$this->pdf->Cell(0, 5, 'INTERNAL TRANSFER SUMMARY - MAIN STORES',0,false, 'L', 0, '', 0, false, 'M', 'M');

$this->pdf->setY(25);
$this->pdf->Cell(70, 1,"",'T',0, 'L', 0);
$this->pdf->Ln(); 

$this->pdf->SetFont('helvetica', '',9);
$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 9);
$this->pdf->Cell(25, 1, "Transfer From -  ", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 9);
$this->pdf->Cell(80, 1, $from , '0', 0, 'L', 0);
$this->pdf->Ln();
$this->pdf->Ln();

$this->pdf->SetY(45);
$this->pdf->SetX(5);
$this->pdf->SetFont('helvetica','B',8);

$this->pdf->Cell(10, 6,"", 'TLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"", 'TLR', 0, 'C', 0);
$this->pdf->Cell(18, 6,"", 'TLR', 0, 'C', 0);
$this->pdf->Cell(22, 6,"", 'TLR', 0, 'C', 0);
$this->pdf->Cell(30, 6,"", 'TLR', 0, 'C', 0);
$this->pdf->Cell(25, 6,"", 'TLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"", 'TLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"", 'TLR', 0, 'C', 0);
$this->pdf->Cell(20, 6,"", 'TLR', 0, 'C', 0);
$this->pdf->Cell(27, 6,"Transfer out", '1', 0, 'C', 0);
$this->pdf->Cell(27, 6,"Transfer returns", '1', 0, 'C', 0);
$this->pdf->Cell(27, 6,"Net Transfer out", '1', 0, 'C', 0);
$this->pdf->Cell(27, 6,"Transfer in", '1', 0, 'C', 0);
$this->pdf->Cell(27, 6,"Variance", '1', 0, 'C', 0);
$this->pdf->Ln();
$this->pdf->SetX(5);
$this->pdf->Cell(10, 6,"No", 'BLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"S.No", 'BLR', 0, 'C', 0);
$this->pdf->Cell(18, 6,"Date", 'BLR', 0, 'C', 0);
$this->pdf->Cell(22, 6,"Department", 'BLR', 0, 'C', 0);
$this->pdf->Cell(30, 6,"Stores", 'BLR', 0, 'C', 0);
$this->pdf->Cell(25, 6,"Transfer To", 'BLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"Or.No", 'BLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"Rc.No", 'BLR', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Rc.Date", 'BLR', 0, 'C', 0);
$this->pdf->Cell(10, 6,"Qty", '1', 0, 'C', 0);
$this->pdf->Cell(17, 6,"value", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6,"Qty", '1', 0, 'C', 0);
$this->pdf->Cell(17, 6,"value", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6,"Qty", '1', 0, 'C', 0);
$this->pdf->Cell(17, 6,"value", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6,"Qty", '1', 0, 'C', 0);
$this->pdf->Cell(17, 6,"value", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6,"Qty", '1', 0, 'C', 0);
$this->pdf->Cell(17, 6,"value", '1', 0, 'C', 0);



$this->pdf->Ln();
$tot=$count=0;
foreach ($sum as $row) {
	$from = $row->f_bc."( ".$row->f_bc_name." )";
	$to = $row->to_bc."( ".$row->to_bc_name." )";
	$store = $row->from_store." - ".$row->store_name;

	$this->pdf->SetX(5);
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
	$this->pdf->SetFont('helvetica','',8);

	$heigh=5*(max(1,$this->pdf->getNumLines($row->to_bcName,25),$this->pdf->getNumLines($store,30)));
	$this->pdf->HaveMorePages($heigh);
	$this->pdf->SetX(5);
	$this->pdf->MultiCell(10, $heigh,$row->nno,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(10, $heigh,$row->sub_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(18, $heigh,$row->ddate,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(22, $heigh,$row->dep,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(30, $heigh,$store,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, $heigh,$row->to_bcName,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(10, $heigh,$row->order_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(10, $heigh,$row->r_no,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(20, $heigh,$row->r_date,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(10, $heigh,$row->t_qty, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(17, $heigh,$row->t_amo, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(10, $heigh,$row->rt_qty, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(17, $heigh,$row->rt_amo, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);	

	$this->pdf->MultiCell(10, $heigh,$row->t_qty-$row->rt_qty, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(17, $heigh,number_format($row->t_amo-$row->rt_amo,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);

	$this->pdf->MultiCell(10, $heigh,$row->r_qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(17, $heigh,$row->r_amo, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(10, $heigh,($row->t_qty-$row->rt_qty)-$row->r_qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(17, $heigh,number_format(($row->t_amo-$row->rt_amo)-$row->r_amo,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
	$count++;
	$tott_qty+=(float)$row->t_qty; //transfer qty
	$totr_qty+=(float)$row->r_qty; // receive qty
	$totrt_qty+=(float)$row->rt_qty; // return qty
	$totn_qty+=(float)$row->t_qty+$row->rt_qty; // total net= transfer qty+return qty
	$totv_qty+=(float)$row->t_qty-(float)$row->r_qty; // varience

	$tott_amo+=(float)$row->t_amo;//transfer amount
	$totr_amo+=(float)$row->r_amo;// receive amount
	$totrt_amo+=(float)$row->rt_amo;// return amount
	$totn_amo+=number_format($row->t_amo+$row->rt_amo,2);// total net= transfer amount+return amount
	$totv_amo+=(float)$row->t_amo-(float)$row->r_amo;// varience
}
$this->pdf->SetX(5);
$this->pdf->SetFont('helvetica','',7);
$this->pdf->Cell(10, 6,"", '', 0, 'R', 0);
$this->pdf->Cell(10, 6,"", '', 0, 'R', 0);
$this->pdf->Cell(40, 6,"", '', 0, 'R', 0);
$this->pdf->Cell(35, 6,"", '', 0, 'R', 0);
$this->pdf->Cell(40, 6,"", '', 0, 'R', 0);
$this->pdf->Cell(10, 6,"", '', 0, 'R', 0);
$this->pdf->Cell(10, 6,"Total", '', 0, 'L', 0);
$this->pdf->Cell(10, 6,$tott_qty, 'TB', 0, 'R', 0);
$this->pdf->Cell(17, 6,$tott_amo, 'TB', 0, 'R', 0);
$this->pdf->Cell(10, 6,$totrt_qty, 'TB', 0, 'R', 0);
$this->pdf->Cell(17, 6,$totrt_amo, 'TB', 0, 'R', 0);
$this->pdf->Cell(10, 6,$totn_qty, 'TB', 0, 'R', 0);
$this->pdf->Cell(17, 6,$totn_amo, 'TB', 0, 'R', 0);
$this->pdf->Cell(10, 6,$totr_qty, 'TB', 0, 'R', 0);
$this->pdf->Cell(17, 6,$totr_amo, 'TB', 0, 'R', 0);
$this->pdf->Cell(10, 6,$totv_qty, 'TB', 0, 'R', 0);
$this->pdf->Cell(17, 6,$totv_amo, 'TB', 0, 'R', 0);

$this->pdf->Ln();

$this->pdf->Cell(20, 6,"", '', 0, 'R', 0);
$this->pdf->Cell(100, 6,"internal transfer summary : ". $count, '', 0, 'L', 0);



$this->pdf->Output("Cash Sale Summary".date('Y-m-d').".pdf", 'I');

?>