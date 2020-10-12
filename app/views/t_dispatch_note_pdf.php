<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);
$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

foreach($branch as $ress){
	$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

$cus_name=$cus_address="";

foreach($sum as $row){
	$from 	 = $row->store_from." - ".$row->fstore_des;
	$to   	 = $row->store_to." - ".$row->tstore_des;
	$date 	 = $row->ddate;
	$officer = $row->officer." - ".$row->e_name;
	$ref_no  = $row->ref_no;
	$nno	 = $row->nno;
	$memo	 = $row->memo;
}

foreach($session as $ses){
	$invoice_no=$session[0].$session[1].$session[2];
}

$this->pdf->setY(20);
$this->pdf->SetFont('helvetica', 'BU', 10);
$this->pdf->Ln();

$this->pdf->Cell(0, 5, ' DISPATCH '.$pdf_page_type,0,false, 'C', 0, '', 0, false, 'M', 'M');

$this->pdf->setY(35);
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(25, 1, 'From ', '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','',8);
$this->pdf->Cell(95, 1, $from, '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(25, 1, "No ", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','',8);
$this->pdf->Cell(30, 1, $invoice_no, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(25, 1, 'To ', '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','',8);
$this->pdf->Cell(95, 1, $to, '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(25, 1, "Date ", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','',8);
$this->pdf->Cell(30, 1, $date, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(25, 1, 'Memo', '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','',8);
$this->pdf->Cell(95, 1, $memo, '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(25, 1, "Ref No", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','',8);
$this->pdf->Cell(30, 1, $ref_no, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->SetY(50);
$this->pdf->SetX(10);
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->Cell(30, 6,"Code", '1', 0, 'L', 0);
$this->pdf->Cell(70, 6,"Description", '1', 0, 'L', 0);
$this->pdf->Cell(25, 6,"Model", '1', 0, 'L', 0);
$this->pdf->Cell(10, 6,"Qty", '1', 0, 'R', 0);
$this->pdf->Cell(20, 6,"Min Price", '1', 0, 'R', 0);
$this->pdf->Cell(20, 6,"Max Price", '1', 0, 'R', 0);
$this->pdf->Cell(20, 6,"Total", '1', 0, 'R', 0);
$this->pdf->Ln();

$f_tot_price=$f_tot_qty=0;

foreach($det as $row){
	$this->pdf->GetY();
	$this->pdf->SetX(10);
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
	$this->pdf->SetFont('helvetica','',8);
	$total = (float)$row->max_price*(float)$row->qty;
	$f_tot_price+=$total;
	$f_tot_qty+=(float)$row->qty;
	$heigh=6*(max(1,$this->pdf->getNumLines($row->description,70)));
	
	$this->pdf->MultiCell(30, $heigh,$row->code,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(70, $heigh,$row->description,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(25, $heigh,$row->model,1, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(10, $heigh,$row->qty,1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$row->min,1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,$row->max_price,1, 'R',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->MultiCell(20, $heigh,number_format($total,2),1, 'R',false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);
	$this->pdf->HaveMorePages($heigh);

}
$this->pdf->SetX(10);
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->Cell(30, 6,"", '0', 0, 'L', 0);
$this->pdf->Cell(80, 6,"", '0', 0, 'L', 0);
$this->pdf->Cell(10, 6,"Total", '0', 0, 'L', 0);
$this->pdf->Cell(15, 6,$f_tot_qty, 'TB', 0, 'R', 0);
$this->pdf->Cell(40, 6,"", '0', 0, 'R', 0);
$this->pdf->Cell(20, 6,number_format($f_tot_price,2), 'TB', 0, 'R', 0);
$this->pdf->Ln();

$this->pdf->Ln();
$this->pdf->Ln();

$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(25, 1, 'Officer ', '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica','',8);
$this->pdf->Cell(30, 1, $officer, '0', 0, 'L', 0);

$this->pdf->Ln();
$this->pdf->Ln();
$this->pdf->Ln();
$this->pdf->Ln();
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(80, 1, '....................................', '0', 0, 'L', 0);
$this->pdf->Cell(75, 1, '....................................', '0', 0, 'L', 0);
$this->pdf->Cell(10, 1, '....................................', '0', 0, 'L', 0);
$this->pdf->Ln();


$this->pdf->Cell(30, 1, 'Prepaired By', '0', 0, 'C', 0);
$this->pdf->Cell(130, 1, 'Authorized', '0', 0, 'C', 0);
$this->pdf->Cell(20, 1, "Recivied By ", '0', 0, 'C', 0);


	//$this->pdf->footerSet($employee,$amount,$additional,$discount,$user);

$this->pdf->Output("Cash Sales_".date('Y-m-d').".pdf", 'I');

?>