<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);
$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

foreach($branch as $ress){
	$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
foreach ($sum as $value) {
	$date=$value->ddate;
	$from = $value->f_cl." - ".$value->f_bc."( ".$value->f_bc_name." )";
}

$this->pdf->setY(26);

$this->pdf->SetFont('helvetica', 'BI',12);
$this->pdf->Cell(0, 5, 'INTERNAL TRANSFER ORDER DETAILS',0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->setY(29);
$this->pdf->Cell(83, 1,"",'T',0, 'L', 0);
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

$i=0;
$a=-1;
static $j=-1;
$my_array=array();
$Goss=array();
$net=array();
$tot=0;
$dis=0;
$net_tot=0;
	
foreach ($sum as $value) {
    $my_array[]=$value->sub_no;
}

foreach ($sum as $value) {	
	if ($i==0 || $my_array[$i]!=$my_array[$i-1]) {
		if ($j>=0){
		 	$this->pdf->SetFont('helvetica','B',8);
			$this->pdf->SetX(20);
		 	$this->pdf->Cell(44, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(90, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "Total", '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, "Rs   ", '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, number_format($tot,2), '0', 0, 'R', 0);
			$this->pdf->Ln(); 
			$this->pdf->Ln(); 
		}
		$j++;
	 	$this->pdf->SetFont('helvetica', 'B', 9);
 	if($i==0){
 		$this->pdf->setY(45);
 	}
 	$this->pdf->Ln();
 	$this->pdf->SetX(15);

    $to = $value->to_cl." - ".$value->to_bc."( ".$value->to_bc_name." )";
   
 	$this->pdf->Cell(10, 1, 'No - ', '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 9);
 	$this->pdf->Cell(25, 1, $value->nno, '0', 0, 'L', 0);

 	$this->pdf->SetFont('helvetica', 'B', 9);
 	$this->pdf->Cell(15, 1, 'Sub No - ', '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 9);
 	$this->pdf->Cell(30, 1, $value->sub_no, '0', 0, 'L', 0);

 	$this->pdf->SetFont('helvetica', 'B', 9);
 	$this->pdf->Cell(15, 1, 'Status - ', '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 9);
 	$this->pdf->Cell(50, 1, $value->status, '0', 0, 'L', 0);

 	$this->pdf->SetFont('helvetica', 'B', 9);
 	$this->pdf->Cell(12, 1, 'Date - ', '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 9);
 	$this->pdf->Cell(25, 1, $value->ddate, '0', 0, 'L', 0);

 	$this->pdf->Ln();

 	$this->pdf->SetFont('helvetica', 'B', 9);
 	$this->pdf->Cell(25, 1, "Transfer To -  ", '0', 0, 'L', 0);
 	$this->pdf->SetFont('helvetica', '', 9);
 	$this->pdf->Cell(100, 1,  $to, '0', 0, 'L', 0);

	$this->pdf->Ln();
	$this->pdf->Ln();
	$this->pdf->SetX(15);
	$this->pdf->SetFont('helvetica','B',8);

    $this->pdf->Cell(30, 6,"Item Code", '1', 0, 'C', 0);
	$this->pdf->Cell(100, 6,"Description", '1', 0, 'C', 0);
    $this->pdf->Cell(15, 6,"Qty", '1', 0, 'C', 0);
    $this->pdf->Cell(22, 6,"Price", '1', 0, 'C', 0);
    $this->pdf->Cell(22, 6,"Amount", '1', 0, 'C', 0);
    $this->pdf->Ln();
    $fin_tot+=$value->total;

}
                            	
	$this->pdf->SetX(15);
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
	$this->pdf->SetFont('helvetica','',8);
    $aa = $this->pdf->getNumLines($value->description, 100);
	$heigh=5*$aa;
	$this->pdf->MultiCell(30, $heigh,$value->item_code,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(100, $heigh,$value->description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->qty,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(22, $heigh,$value->item_cost,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(22, $heigh,$value->amount,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
    $i++;
    $tot=$value->total;
    
    //var_dump($value->total);
    }
 	$this->pdf->SetFont('helvetica','B',8);
	$this->pdf->SetX(20);
 	$this->pdf->Cell(44, 1, "", '0', 0, 'L', 0);
 	$this->pdf->Cell(90, 1, "", '0', 0, 'L', 0);
 	$this->pdf->Cell(20, 1, "Total", '0', 0, 'L', 0);
 	$this->pdf->Cell(10, 1, "Rs   ", '0', 0, 'L', 0);
 	$this->pdf->Cell(20, 1, number_format($tot,2), '0', 0, 'R', 0);

 	$this->pdf->Ln();
 	$this->pdf->SetFont('helvetica','B',8);
	$this->pdf->SetX(20);
 	$this->pdf->Cell(44, 1, "", '0', 0, 'L', 0);
 	$this->pdf->Cell(90, 1, "", '0', 0, 'L', 0);
 	$this->pdf->Cell(20, 1, "Final Total", '0', 0, 'L', 0);
 	$this->pdf->Cell(10, 1, "Rs   ", '0', 0, 'L', 0);
 	$this->pdf->Cell(20, 1, number_format($fin_tot,2), 'TB', 0, 'R', 0); 

	
	$this->pdf->Output("Internal Transfer Order Detail".date('Y-m-d').".pdf", 'I');

?>