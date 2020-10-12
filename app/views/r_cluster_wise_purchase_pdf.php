<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page);   

foreach($branch as $ress){
	$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
foreach ($purchase as $value){
	$inv_no=$value->nno;
	$name=$value->name;
}

$clustrName='';
foreach ($SelCluster as $r){
	$clustrName=$r->code." - ".$r->description;
}

$supplierName='';
foreach ($SelSupplier as $r){
	$supplierName=$r->code." - ".$r->name;
}






$this->pdf->setY(25);

$this->pdf->SetFont('helvetica', 'BU',14);
$this->pdf->Cell(0, 5, 'Cluster Wise Purchase Details Report',0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();
$this->pdf->Ln();


$this->pdf->SetFont('helvetica', '',11);
$this->pdf->Cell(30, 5, 'Cluster ' ,0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Cell(0, 5, ': '.$clustrName ,0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();
$this->pdf->Cell(30, 5, 'Date Between ',0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Cell(0, 5, ': '. $dfrom.' and '.$dto ,0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();
$this->pdf->Cell(30, 5, 'Vendor ' ,0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Cell(0, 5, ': '. $supplierName ,0,false, 'L', 0, '', 0, false, 'M', 'M');

$this->pdf->Ln();
$this->pdf->Ln();

if($value->grn_no == "")
{
	$this->pdf->SetX(80);
	$this->pdf->Cell(20, 1, "No Records For View ! ! !", '0', 0, 'L', 0);     
}
else
{
	$this->pdf->SetY(52);
	$this->pdf->SetX(5);
	$this->pdf->SetFont('helvetica','B',9);
	$this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
	$this->pdf->Cell(15, 6,"GRN NO", '1', 0, 'C', 0);
	$this->pdf->Cell(15, 6,"Inv No", '1', 0, 'C', 0);
	$this->pdf->Cell(32, 6,"Item Code", '1', 0, 'C', 0);
	$this->pdf->Cell(30, 6,"Item Model", '1', 0, 'C', 0);
	$this->pdf->Cell(55, 6,"Item Des", '1', 0, 'C', 0);
	$this->pdf->Cell(10, 6,"QTY", '1', 0, 'C', 0);
	$this->pdf->Cell(25, 6,"Cost Prize", '1', 0, 'C', 0);
	$this->pdf->Cell(25, 6,"Last Prize", '1', 0, 'C', 0);
	$this->pdf->Cell(25, 6,"Sales Prize", '1', 0, 'C', 0);
	$this->pdf->Cell(30, 6,"Total", '1', 0, 'C', 0);
	$this->pdf->Ln();

	$xcost_prize=(float)0;
	$xlast_prize=(float)0;
	$xsales_prize=(float)0;

	foreach ($purchase as $value) {
		
		$this->pdf->SetFont('helvetica','',9);
		$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
		$aa = max($this->pdf->getNumLines($value->itm_des, 55),$this->pdf->getNumLines($value->itm_model, 30),$this->pdf->getNumLines($value->invoice_no, 15));
		$heigh=5*$aa;
		$this->pdf->haveMorePages($heigh);
		$this->pdf->SetX(5);
		$this->pdf->MultiCell(20,  $heigh,$value->ddate,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(15, $heigh,$value->grn_no,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(15, $heigh,$value->invoice_no,  1, 'C', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(32, $heigh,$value->itm_code,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(30, $heigh,$value->itm_model,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(55, $heigh,$value->itm_des,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(10, $heigh,$value->qty, 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh,number_format($value->cost_prize,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh,number_format($value->last_prize,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh,number_format($value->sales_prize,2),  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(30, $heigh,number_format($value->total,2),  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
		// $tot_disc+=(float)$value->discount;
		$xcost_prize+=(float)$value->cost_prize;
		$xlast_prize+=(float)$value->last_prize;
		$xsales_prize+=(float)$value->sales_prize;
		$xtotal+=(float)$value->total;
	}

	

	$this->pdf->SetFont('helvetica','B',10);
	$this->pdf->SetX(10);
	$this->pdf->Ln();

	$this->pdf->Cell(30, 6,"Total Cost Prize", '0', 0, 'L', 0);
	$this->pdf->Cell(30, 6,": ".number_format($xcost_prize,2), '0', 0, 'L', 0);
	$this->pdf->Ln();

	$this->pdf->Cell(30, 6,"Total Last prize", '0', 0, 'L', 0);
	$this->pdf->Cell(30, 6,": ".number_format($xlast_prize,2), '0', 0, 'L', 0);
	$this->pdf->Ln();

	$this->pdf->Cell(30, 6,"Total Sales Prize ", '0', 0, 'L', 0);
	$this->pdf->Cell(30, 6,": ".number_format($xsales_prize,2), '0', 0, 'L', 0);
	$this->pdf->Ln();

	$this->pdf->Cell(30, 6,"Total Amount", '0', 0, 'L', 0);
	$this->pdf->Cell(30, 6,": ".number_format($xtotal,2), '0', 0, 'L', 0);
	$this->pdf->Ln();
	






} 
$this->pdf->Output("Purchase Summary".date('Y-m-d').".pdf", 'I');

?>