<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);
$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

foreach($branch as $ress){
	$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

$this->pdf->setY(22);

$this->pdf->SetFont('helvetica', 'BU',10);
$this->pdf->Cell(0, 5, 'TOTAL CREDIT SALES RETURN SUMMARY 02',0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();$this->pdf->Ln();

$this->pdf->SetFont('helvetica', '',9);
$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->Ln();

$this->pdf->SetX(10);
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"No", '1', 0, 'C', 0);
$this->pdf->Cell(68, 6,"Customer", '1', 0, 'C', 0);
$this->pdf->Cell(22, 6,"Purchase Price", '1', 0, 'C', 0);
$this->pdf->Cell(22, 6,"Min Price", '1', 0, 'C', 0);
$this->pdf->Cell(22, 6,"Max Price", '1', 0, 'C', 0);
$this->pdf->Cell(22, 6,"Net Amount", '1', 0, 'C', 0);

$this->pdf->Ln();
$t_cost=$t_min=$t_max=$t_net=(float)0;


foreach ($result as $row) {
	$this->pdf->SetX(10);
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
	$this->pdf->SetFont('helvetica','',8);
	$heigh=6*(max(1,$this->pdf->getNumLines($row->customer,68)));

	$this->pdf->MultiCell(20, $heigh,$row->ddate,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(15, $heigh,$row->nno,1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(68, $heigh,$row->customer,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(22, $heigh,number_format($row->purchase_price,2),1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(22, $heigh,number_format($row->min_price,2),1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(22, $heigh,number_format($row->max_price,2),1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(22, $heigh,number_format($row->net_amount,2),1, 'R',false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);

	$t_cost+=(float)$row->purchase_price;
	$t_min+=(float)$row->min_price;
	$t_max+=(float)$row->max_price;
	$t_net+=(float)$row->net_amount;

	$this->pdf->HaveMorePages($heigh);

}

$this->pdf->Ln();

$this->pdf->SetFont('helvetica','B',8);
$this->pdf->SetX(10);
$this->pdf->Cell(20, 6,"", '0', 0, 'L', 0);
$this->pdf->Cell(15, 6,"", '0', 0, 'L', 0);
$this->pdf->Cell(68, 6,"Total ", '0', 0, 'R', 0);
$this->pdf->Cell(22, 6,number_format($t_cost,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
$this->pdf->Cell(21, 6,number_format($t_min,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
$this->pdf->Cell(22, 6,number_format($t_max,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(22, 6,number_format($t_net,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(22, 6,"", '0', 0, 'R', 0);

$this->pdf->Output("Credit Sale Return Summary 02".date('Y-m-d').".pdf", 'I');

?>