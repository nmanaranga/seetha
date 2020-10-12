<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);
$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

foreach($branch as $ress){
	$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
foreach ($category as $cat){
  $code=$cat->code;
  $des=$cat->description;
}
		
	$this->pdf->setY(22);

	$this->pdf->SetFont('helvetica', 'B',12);
 	$this->pdf->Cell(0, 5, 'INTERNAL TRANSFER ORDER SUMMARY',0,false, 'L', 0, '', 0, false, 'M', 'M');

	$this->pdf->setY(25);
    $this->pdf->Cell(90, 1,"",'T',0, 'L', 0);
    $this->pdf->Ln(); 

	$this->pdf->SetFont('helvetica', '',9);
 	$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'L', 0, '', 0, false, 'M', 'M');
	$this->pdf->Ln();

    foreach ($ordr_sum as $row) {
        $from = $row->cl." - ( ".$row->bc." - ".$row->fr_bc." )";
    }

    $this->pdf->SetFont('helvetica', 'B',8);
    $this->pdf->Cell(20, 6,"Order From", '0', 0, 'L', 0);
    $this->pdf->SetFont('helvetica', '',8);
    $this->pdf->Cell(40, 6,$from, '0', 0, 'L', 0);
    $this->pdf->Cell(10, 6,"", '0', 0, 'C', 0);
    $this->pdf->Ln(); 

	$this->pdf->SetY(45);
	$this->pdf->SetX(15);
	$this->pdf->SetFont('helvetica','B',8);
    $this->pdf->Cell(10, 6,"No", '1', 0, 'C', 0);
    $this->pdf->Cell(10, 6,"S.No", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
    $this->pdf->Cell(90, 6,"Order To", '1', 0, 'C', 0);
    $this->pdf->Cell(30, 6,"Status", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6,"Amount", '1', 0, 'C', 0);
    
    $this->pdf->Ln();
    $count=0;
    foreach ($ordr_sum as $row) {
    	$to = $row->to_cl." - ( ".$row->to_bc." - ".$row->t_bc.")";
		$this->pdf->SetX(15);
		$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
		$this->pdf->SetFont('helvetica','',8);
	    $aa = $this->pdf->getNumLines($to, 90);
    	$heigh=5*$aa;
		$this->pdf->MultiCell(10, $heigh,$row->nno,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(10, $heigh,$row->sub_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh,$row->ddate,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(90, $heigh,$to,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(30, $heigh,$row->status,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,number_format($row->amount,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);

  		$count++;
        $fin_tot+=$row->amount;
    }

    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->SetX(25);
    $this->pdf->Cell(75, 6, "", '0', 0, 'L', 0);
    $this->pdf->Cell(25, 6, "", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 6, "", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 6, "Total", '0', 0, 'R', 0);
    $this->pdf->Cell(30, 6, "Rs. ".number_format($fin_tot,2), 'TB', 0, 'R', 0);

    $this->pdf->Ln();
    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->Cell(20, 6,"", '', 0, 'R', 0);
    $this->pdf->Cell(100, 6,"Number Of Internal Transfer Orders : ". $count, '', 0, 'L', 0);
    

                       
	$this->pdf->Output("Internal Transfers Oreder Summary".date('Y-m-d').".pdf", 'I');

?>