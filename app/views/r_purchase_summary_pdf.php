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
$this->pdf->setY(25);

$this->pdf->SetFont('helvetica', 'BU',10);
$this->pdf->Cell(0, 5, ' PURCHASE SUMMARY',0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();$this->pdf->Ln();

$this->pdf->SetFont('helvetica', '',9);
$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();
$this->pdf->Ln();

if($value->nno == "")
{
	$this->pdf->SetX(80);
	$this->pdf->Cell(20, 1, "No Records For View ! ! !", '0', 0, 'L', 0);     
}
else
{
	$this->pdf->SetY(45);
	$this->pdf->SetX(5);
	$this->pdf->SetFont('helvetica','B',7);
	$this->pdf->Cell(8, 6,"No", '1', 0, 'C', 0);
	$this->pdf->Cell(15, 6,"Date", '1', 0, 'C', 0);
	$this->pdf->Cell(15, 6,"Inv No", '1', 0, 'C', 0);
	$this->pdf->Cell(15, 6,"INV Date", '1', 0, 'C', 0);
	$this->pdf->Cell(50, 6,"Supplier", '1', 0, 'C', 0);
	$this->pdf->Cell(17, 6,"Gross Amount", '1', 0, 'C', 0);
	$this->pdf->Cell(17, 6,"Discount", '1', 0, 'C', 0);
	$this->pdf->Cell(17, 6,"Net Amount", '1', 0, 'C', 0);
	$this->pdf->Cell(25, 6,"Store", '1', 0, 'C', 0);
	$this->pdf->Cell(25, 6,"Memo", '1', 0, 'C', 0);
	$this->pdf->Ln();

	$tot_dis=(float)0;

	foreach ($purchase as $value) {
		
		$this->pdf->SetFont('helvetica','',7);
		$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
		$aa = max($this->pdf->getNumLines($value->name, 50),$this->pdf->getNumLines($value->description, 25),$this->pdf->getNumLines($value->memo, 25));
	  $heigh=5*$aa;
	  $this->pdf->haveMorePages($heigh);
	  $this->pdf->SetX(5);
		$this->pdf->MultiCell(8, $heigh,$value->nno,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(15, $heigh,$value->ddate,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(15, $heigh,$value->inv_no,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(15, $heigh,$value->inv_date,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(50, $heigh,$value->name,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(17, $heigh,$value->gross_amount,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(17, $heigh,$value->discount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(17, $heigh,$value->amount,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh,$value->description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh,$value->memo,  1, 'L', 0, 1, '', '', true, 0, false, true, 0);
		$tot_disc+=(float)$value->discount;
	}

	foreach ($sum as $sum) {
		$Goss=$sum->gsum;
		$net=$sum->nsum;
		$addi=$sum->addi;
		$dis=$sum->discount;
	}

	$this->pdf->SetFont('helvetica','B',8);
	$this->pdf->SetX(10);

	$this->pdf->Cell(10, 6,"", '0', 0, 'L', 0);
	$this->pdf->Cell(27, 6,"", '0', 0, 'L', 0);
	$this->pdf->Cell(10, 6,"", '0', 0, 'L', 0);
	$this->pdf->Cell(50, 6,"Total ", '0', 0, 'R', 0);
	$this->pdf->Cell(17, 6,number_format($Goss,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(1, 6,"", '0', 0, 'L', 0);
	$this->pdf->Cell(17, 6,number_format($tot_disc,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(1, 6,"", '0', 0, 'L', 0);
	$this->pdf->Cell(17, 6,number_format($net,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(20, 6,"", '0', 0, 'L', 0);
	$this->pdf->Cell(20, 6,"", '0', 0, 'L', 0);





} 
$this->pdf->Output("Purchase Summary".date('Y-m-d').".pdf", 'I');

?>