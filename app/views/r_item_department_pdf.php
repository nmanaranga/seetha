<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
        $this->pdf->setPrintHeader(true,$type);
        $this->pdf->setPrintFooter(true);
        //print_r($customer);
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

$branch_name="";
    foreach($branch as $ress){
        $branch_name=$ress->name;
        $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    }
        
	$this->pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
	$this->pdf->setY(22);
    $this->pdf->SetX(15);
	$this->pdf->SetFont('helvetica', 'B',12);
 	$this->pdf->Cell(140, 4, 'Item Department List  ','B',false, 'L', 0, '', 0, false, 'M', 'M');
	$this->pdf->Ln();

    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->Cell(50, 4,'Code', 'B', 0, 'L', 0);
    $this->pdf->Cell(90, 4,'Description','B', 0, 'L', 0);
    $this->pdf->Ln();

    $x=0;
	foreach ($dep as $value) {

		$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    

		//$this->pdf->SetX(20);
		$this->pdf->SetFont('helvetica','',8);

		$this->pdf->Cell(50, 4,$value->code, '0', 0, 'L', 0);
		$this->pdf->Cell(90, 4,$value->description,'0', 0, 'L', 0);
		$this->pdf->Ln();
        $x=$x+1;

        if($x % 5==0){
                $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1.5, 'color' => array(0, 0, 0)));
    

                //$this->pdf->SetX(20);
                $this->pdf->Cell(50, 4,'', 'B', 0, 'L', 0);
                $this->pdf->Cell(90, 4,'','B', 0, 'L', 0);
                $this->pdf->Ln();
        }
	}

    $this->pdf->Ln();
    //$this->pdf->SetX(20);
    $this->pdf->SetFont('helvetica','i',7);
    $this->pdf->Cell(50, 4,"No Of departments ".$x, '0', 0, 'L', 0);
    $this->pdf->Cell(9, 4,'','0', 0, 'L', 0);

    //$this->pdf->footerSet($employee,$amount,$additional,$discount,$user);
	$this->pdf->Output("Item_Department_".date('Y-m-d').".pdf", 'I');

?>