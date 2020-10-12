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

$this->pdf->setY(22);

$this->pdf->SetFont('helvetica', 'B',12);
$this->pdf->Cell(0, 5, 'INTERNAL TRANSFER RECEIVE SUMMARY',0,false, 'L', 0, '', 0, false, 'M', 'M');

$this->pdf->setY(25);
$this->pdf->Cell(90, 1,"",'T',0, 'L', 0);
$this->pdf->Ln(); 

$this->pdf->SetFont('helvetica', '',9);
$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->SetX(15);
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(10, 6,"R No", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6,"R S.No", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6,"T No", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6,"T S.No", '1', 0, 'C', 0);
$this->pdf->Cell(60, 6,"Transfer Branch", '1', 0, 'C', 0);
$this->pdf->Cell(45, 6,"Store", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Amount", '1', 0, 'C', 0);

$this->pdf->Ln();
$count=0;
foreach ($rec_sum as $row) {


   $this->pdf->SetX(15);
   $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
   $this->pdf->SetFont('helvetica','',8);
   $aa = $this->pdf->getNumLines($row->name, 60);
   $heigh=5*$aa;
   $this->pdf->HaveMorePages($heigh);
   $this->pdf->SetX(15);
   $this->pdf->MultiCell(10, $heigh,$row->rec_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
   $this->pdf->MultiCell(10, $heigh,$row->res_sub,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
   $this->pdf->MultiCell(20, $heigh,$row->ddate,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
   $this->pdf->MultiCell(10, $heigh,$row->tr_no,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
   $this->pdf->MultiCell(10, $heigh,$row->tr_sub,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
   $this->pdf->MultiCell(60, $heigh,$row->name,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
   $this->pdf->MultiCell(45, $heigh,$row->stores,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
   $this->pdf->MultiCell(20, $heigh,$row->net_amount,  1, 'R',0 , 1, '', '', true, 0, false, true, 0);

   $count++;
   $fin_tot+=$row->net_amount;
}

$this->pdf->SetFont('helvetica','B',8);
$this->pdf->SetX(25);
$this->pdf->Cell(75, 6, "", '0', 0, 'L', 0);
$this->pdf->Cell(30, 6, "", '0', 0, 'L', 0);
$this->pdf->Cell(20, 6, "Total", '0', 0, 'L', 0);
$this->pdf->Cell(20, 6, "Rs   ", '0', 0, 'R', 0);
$this->pdf->Cell(30, 6, number_format($fin_tot,2), 'TB', 0, 'R', 0);

$this->pdf->Ln();
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(20, 6,"", '', 0, 'R', 0);
$this->pdf->Cell(100, 6,"Number Of Internal Transfers Receive : ". $count, '', 0, 'L', 0);



$this->pdf->Output("Internal Transfers Receive Summary".date('Y-m-d').".pdf", 'I');

?>