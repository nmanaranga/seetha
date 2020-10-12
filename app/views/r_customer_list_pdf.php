<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);
       //print_r($customer);
$this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

       foreach($branch as $ress){
         $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
     }


     $this->pdf->setY(20);
     $this->pdf->Ln();
     $this->pdf->SetFont('helvetica', 'BI',12);

     $this->pdf->Cell(0, 5, 'Category wise Customer   ',0,false, 'L', 0, '', 0, false, 'M', 'M');
     $this->pdf->Ln();
     $this->pdf->Ln();

     $this->pdf->setY(27);
     $this->pdf->Cell(50, 1,"",'T',0, 'L', 0);
     $this->pdf->Ln(); 

     $this->pdf->SetY(35);
     $this->pdf->Ln();
     $this->pdf->SetX(10);
     $this->pdf->SetFont('helvetica','B',7);
     $this->pdf->Cell(15, 6,"Catregory", '1', 0, 'C', 0);
     $this->pdf->Cell(20, 6,"Code", '1', 0, 'C', 0);
     $this->pdf->Cell(20, 6,"NIC", '1', 0, 'C', 0);
     $this->pdf->Cell(50, 6,"Name", '1', 0, 'C', 0);
     $this->pdf->Cell(85, 6,"Address", '1', 0, 'C', 0);
     $this->pdf->Cell(20, 6,"DOB", '1', 0, 'C', 0);
     $this->pdf->Cell(25, 6,"Email", '1', 0, 'C', 0);
     $this->pdf->Cell(20, 6,"TP", '1', 0, 'C', 0);
     $this->pdf->Cell(20, 6,"Mobile", '1', 0, 'C', 0);
     $this->pdf->Ln();
     $this->pdf->SetX(10);

     foreach ($customer as $value) {
			//$this->pdf->SetY(45);
        $this->pdf->SetX(10);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

        $this->pdf->SetFont('helvetica','',7);


        $aa = $this->pdf->getNumLines($row->name, 50);
        $bb = $this->pdf->getNumLines($row->address, 85);
        if($aa>$bb){
         $heigh=6*$aa;
     }else{
      $heigh=6*$bb;
  }

  $this->pdf->HaveMorePages($heigh);
  $this->pdf->SetX(10);
  $this->pdf->MultiCell(15, $heigh, $value->CategoryName,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(20, $heigh, $value->code,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(20, $heigh, $value->nic,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(50, $heigh, $value->name,   1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(85, $heigh, $value->address,   1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(20, $heigh, $value->dob,   1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(25, $heigh, $value->email,   1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(20, $heigh, $value->tp,   1, 'L', 0, 0, '', '', true, 0, false, true, 0);
  $this->pdf->MultiCell(20, $heigh, $value->mobile, 1, 'R', 0, 1, '', '', true, 0, false, true, 0);



}

$this->pdf->Output("Catregory Wise Customer Report".date('Y-m-d').".pdf", 'I');

?>