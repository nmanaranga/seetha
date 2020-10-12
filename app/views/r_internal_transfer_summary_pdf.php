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
 	$this->pdf->Cell(0, 5, 'INTERNAL TRANSFER SUMMARY',0,false, 'L', 0, '', 0, false, 'M', 'M');

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
    $this->pdf->Cell(10, 6,"No", '1', 0, 'C', 0);
    $this->pdf->Cell(10, 6,"S.No", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
	$this->pdf->Cell(60, 6,"Transfer To", '1', 0, 'C', 0);
    $this->pdf->Cell(10, 6,"Or.No", '1', 0, 'C', 0);
    $this->pdf->Cell(35, 6,"Store", '1', 0, 'C', 0);
    $this->pdf->Cell(18, 6,"Status", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6,"Vehicle", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6,"Amount", '1', 0, 'C', 0);

    $this->pdf->Ln();
    $tot=$count=0;
    foreach ($sum as $row) {
    	$from = $row->f_cl." - ".$row->f_bc."( ".$row->f_bc_name." )";
    	$to = $row->to_cl." - ".$row->to_bc."( ".$row->to_bc_name." )";
    	$store = $row->from_store." - ".$row->store_name;
    	if($row->status=="P"){
    		$status="PENDING";
    	}else if($row->status=="R"){
    		$status="RECEIVED";
    	}else{
    		$status="";
    	}
		$this->pdf->SetX(5);
		$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
		$this->pdf->SetFont('helvetica','',8);
	    $aa = $this->pdf->getNumLines($from, 65);
        $bb = $this->pdf->getNumLines($store,35);
        if($aa>$bb){
        	$heigh=5*$aa;
        }else{
            $heigh=5*$bb;
        }
		$this->pdf->MultiCell(10, $heigh,$row->nno,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(10, $heigh,$row->sub_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh,$row->ddate,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(60, $heigh,$to,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(10, $heigh,$row->order_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(35, $heigh,$store,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(18, $heigh,$status,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$row->vehicle, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$row->total, 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
  		$count++;
        $tot+=(float)$row->total;
    }
    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->Cell(10, 6,"", '0', 0, 'C', 0);
    $this->pdf->Cell(10, 6,"", '0', 0, 'C', 0);
    $this->pdf->Cell(20, 6,"", '0', 0, 'C', 0);
    $this->pdf->Cell(60, 6,"", '0', 0, 'C', 0);
    $this->pdf->Cell(10, 6,"", '0', 0, 'C', 0);
    $this->pdf->Cell(35, 6,"", '0', 0, 'C', 0);
    $this->pdf->Cell(18, 6,"", '0', 0, 'C', 0);
    $this->pdf->Cell(10, 6,"Total", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 6,"Rs. ".number_format($tot,2), 'TB', 0, 'R', 0);

    $this->pdf->Ln();
    
    $this->pdf->Cell(20, 6,"", '', 0, 'R', 0);
    $this->pdf->Cell(100, 6,"Number Of Internal Transfers : ". $count, '', 0, 'L', 0);
    

                       
	$this->pdf->Output("Cash Sale Summary".date('Y-m-d').".pdf", 'I');

?>