<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
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

	$this->pdf->setY(22);

	$this->pdf->SetFont('helvetica', 'BU',10);
	$this->pdf->Cell(0, 5, 'TOTAL POS SALES SUMMARY',0,false, 'C', 0, '', 0, false, 'M', 'M');
	$this->pdf->Ln();$this->pdf->Ln();

	$this->pdf->SetFont('helvetica', '',9);
	$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'C', 0, '', 0, false, 'M', 'M');
	$this->pdf->Ln();
	$this->pdf->Ln();


	$this->pdf->SetY(40);
	$this->pdf->SetX(10);
	$this->pdf->SetFont('helvetica','B',7);
	$this->pdf->Cell(15, 6,"No", '1', 0, 'C', 0);
	$this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
	$this->pdf->Cell(80, 6,"Customer", '1', 0, 'C', 0);
	$this->pdf->Cell(25, 6,"Gross Amount", '1', 0, 'C', 0);
	$this->pdf->Cell(25, 6,"Discount", '1', 0, 'C', 0);
	$this->pdf->Cell(25, 6,"Net Amount", '1', 0, 'C', 0);

	$this->pdf->Ln();
	$tot_gross=(float)0;
	$tot_net=(float)0;
	$tot_dis=(float)0;

	foreach ($pos as $value) {
		$this->pdf->SetX(10);
		$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
		$this->pdf->SetFont('helvetica','',7);
		$aa = $this->pdf->getNumLines($value->customer, 80);
	    $heigh=5*$aa;
		$this->pdf->MultiCell(15, $heigh, $value->nno, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh, $value->ddate, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(80, $heigh, $value->customer, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh, number_format($value->gross_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh, number_format($value->total_discount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(25, $heigh, number_format($value->net_amount,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);

		$tot_gross+=(float)$value->gross_amount;
		$tot_net+=(float)$value->net_amount;
		$tot_disc+=(float)$value->total_discount;
	}

	

	$this->pdf->SetFont('helvetica','B',8);
	$this->pdf->SetX(10);
	$this->pdf->Cell(15, 6,"", '0', 0, 'L', 0);
	$this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
	$this->pdf->Cell(80, 6,"Total ", '0', 0, 'R', 0);
	$this->pdf->Cell(25, 6,number_format($tot_gross,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
	$this->pdf->Cell(24, 6,number_format($tot_disc,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
	$this->pdf->Cell(25, 6,number_format($tot_net,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);


	$this->pdf->Output("POS Sale Summary".date('Y-m-d').".pdf", 'I');

	?>