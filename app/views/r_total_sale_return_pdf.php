<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	$this->pdf->setPrintHeader($header,$type,$duration);
    $this->pdf->setPrintFooter(true);
    $this->pdf->SetFont('helvetica', 'B', 16);
	$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

	foreach($branch as $ress){
		$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
	}

	$this->pdf->setY(25);

	$this->pdf->SetFont('helvetica', 'BI',12);
 	$this->pdf->Cell(0, 5, 'TOTAL SALES RETURN SUMMARY',0,false, 'L', 0, '', 0, false, 'M', 'M');
 	
 	$this->pdf->setY(27);
    $this->pdf->Cell(70, 1,"",'T',0, 'L', 0);
    $this->pdf->Ln(); 

 	$this->pdf->SetFont('helvetica', '',9);
 	$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'L', 0, '', 0, false, 'M', 'M');
	$this->pdf->Ln();
	$this->pdf->Ln();
	$i=0;
	$a=-1;
	$j=-1;
 	$Goss=array();
	$net=array();
 	$my_array=array();
 	foreach ($purchase as $value) {
          $my_array[]=$value->name;
	}
	foreach ($sum as $sum){
    	$Goss[]=$sum->gsum;
    	$net[]=$sum->nsum;
    	$a++;
    }
	$this->pdf->SetFont('helvetica', 'B', 8);
 	if ($i==0) {
 		$this->pdf->setY(40);
 	}
	$this->pdf->Ln();
	$this->pdf->SetX(15);
	$this->pdf->SetFont('helvetica','B',8);
    $this->pdf->Cell(10, 6,"No", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
    $this->pdf->Cell(10, 6,"Inv No", '1', 0, 'C', 0);
    $this->pdf->Cell(15, 6,"Type", '1', 0, 'L', 0);
	$this->pdf->Cell(70, 6,"Customer", '1', 0, 'C', 0);
    $this->pdf->Cell(22, 6,"Gross Amount", '1', 0, 'C', 0);
    $this->pdf->Cell(22, 6,"Discount", '1', 0, 'C', 0);
    $this->pdf->Cell(22, 6,"Net Amount", '1', 0, 'C', 0);
    $this->pdf->Ln();

    $tot_dis=(float)0;

	foreach ($purchase as $value) {	
		$aa = $this->pdf->getNumLines($value->cus_id." - ".$value->name,70);
		$heigh=4*$aa;
		if($value->sales_type=="4"){
			$status="Cash";
		}else{
			$status="Credit";
		}

		$this->pdf->SetX(15);
		$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
		$this->pdf->SetFont('helvetica','',8);
        $this->pdf->MultiCell(10, $heigh,$value->nno,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$value->ddate,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(10, $heigh,$value->inv_no,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(15, $heigh,$status,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(70, $heigh,$value->cus_id." | ".$value->name,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(22, $heigh,$value->gross_amount,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(22, $heigh,$value->discount,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(22, $heigh,$value->net_amount,1, 'R', 0, 1, '', '', true, 0, false, true, 0);
                   	
    	$tot_disc+=(float)$value->discount;
    	$tot_gross+=(float)$value->gross_amount;
    	$tot_net+=(float)$value->net_amount;
	}


	$this->pdf->SetX(15);
	$this->pdf->SetFont('helvetica','B',8);
    $this->pdf->Cell(10, 6,"", '0', 0, 'L', 0);
    $this->pdf->Cell(100, 6,"", '0', 0, 'L', 0);
    $this->pdf->Cell(15, 6,"Total ", '0', 0, 'C', 0);
    $this->pdf->Cell(22, 6,number_format($tot_gross,2), 'TB', 0, 'R', 0);
    $this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
    $this->pdf->Cell(21, 6,number_format($tot_disc,2), 'TB', 0, 'R', 0);
    $this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
    $this->pdf->Cell(22, 6,number_format($tot_net,2), 'TB', 0, 'R', 0);

	$this->pdf->Output("Cash Sales Return".date('Y-m-d').".pdf", 'I');

?>