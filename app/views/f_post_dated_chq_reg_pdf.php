<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);

$this->pdf->setPrintFooter(true);
$this->pdf->setPrintHeader(true);
$this->pdf->setPrintHeader($header,$type);  

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page); 

foreach($branch as $ress){
   $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

$this->pdf->setY(18);
$this->pdf->Ln();
$this->pdf->SetFont('helvetica', 'IBU', 14);
$this->pdf->Cell(280, 1,"POST DATED CHEQUES REGISTRY",0,false, 'L', 0, '', 0, false, 'M', 'M');

$this->pdf->Ln();
    /*
    $this->pdf->SetFont('helvetica', 'B', 9);
    $this->pdf->Cell(25, 1,"As At Date",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Cell(225, 1,$all_det["date"],0,false, 'L', 0, '', 0, false, 'M', 'M');
    */
    $this->pdf->setX(10);
    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->Cell(20, 6, " Date", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6, " R. Date", '1', 0, 'C', 0);
    $this->pdf->Cell(40, 6, " Customer", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6, " Chq No", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6, " Amount", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6, "Account ", '1', 0, 'C', 0);
    $this->pdf->Cell(35, 6, "Bank  ", '1', 0, 'C', 0);
    $this->pdf->Cell(35, 6, "Branch  ", '1', 0, 'C', 0);
    $this->pdf->Cell(10, 6, "A. No", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6, "Rpt Date  ", '1', 0, 'C', 0);
    $this->pdf->Cell(10, 6, "Rpt No", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6, "Bank Date", '1', 0, 'C', 0);
    $this->pdf->Cell(10, 6, "St", '1', 0, 'C', 0);
    $this->pdf->Ln();

    $this->pdf->setY(35);
    $len=count($all_det);
    $act_len=(int)(($len-11)/14);
    //for($x=0; $x<$act_len; $x++){

    foreach ($all_det as $value) {

        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        $this->pdf->setX(10);
        $this->pdf->SetFont('helvetica','',8);

        $aa = max(1,
            $this->pdf->getNumLines($value->customer." - ".$value->cus_name, 40),
            $this->pdf->getNumLines($value->bank." - ".$value->bank_name, 35),
            $this->pdf->getNumLines($value->branch." - ".$value->branch_name, 35)
            );
        
        $heigh=5*$aa;

        $this->pdf->HaveMorePages($heigh);
        $this->pdf->MultiCell(20, $heigh,$value->date,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$value->chq_date,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(40, $heigh,$value->customer." - ".$value->cus_name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$value->cheque_no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$value->amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$value->account, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(35, $heigh,$value->bank." - ".$value->bank_name,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(35, $heigh,$value->branch." - ".$value->branch_name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(10, $heigh,$value->ack_no, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$value->rcpt_date, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(10, $heigh,$value->rcpt_no, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$value->bank_date, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(10, $heigh,$value->status, 1, 'C', 0, 1, '', '', true, 0, false, true, 0);
        
    }
    $this->pdf->Output($title.date('Y-m-d').".pdf", 'I');
    ?><?php
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    $this->pdf->setPrintHeader($header,$type,$duration);

    $this->pdf->setPrintFooter(true);
    $this->pdf->setPrintHeader(true);
    $this->pdf->setPrintHeader($header,$type);  

    $this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage($orientation,$page); 

    foreach($branch as $ress){
     $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
 }

 $this->pdf->setY(18);
 $this->pdf->Ln();
 $this->pdf->SetFont('helvetica', 'IBU', 14);
 $this->pdf->Cell(280, 1,"POST DATED CHEQUES REGISTRY",0,false, 'L', 0, '', 0, false, 'M', 'M');

 $this->pdf->Ln();
    /*
    $this->pdf->SetFont('helvetica', 'B', 9);
    $this->pdf->Cell(25, 1,"As At Date",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Cell(225, 1,$all_det["date"],0,false, 'L', 0, '', 0, false, 'M', 'M');
    */
    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->Cell(20, 6, " Date", '1', 0, 'C', 0);
    $this->pdf->Cell(40, 6, " Customer", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6, " Chq No", '1', 0, 'C', 0);
    $this->pdf->Cell(25, 6, " Amount", '1', 0, 'C', 0);
    $this->pdf->Cell(25, 6, "Account ", '1', 0, 'C', 0);
    $this->pdf->Cell(35, 6, "Bank  ", '1', 0, 'C', 0);
    $this->pdf->Cell(35, 6, "Branch  ", '1', 0, 'C', 0);
    $this->pdf->Cell(10, 6, "A. No", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6, "Rpt Date  ", '1', 0, 'C', 0);
    $this->pdf->Cell(10, 6, "Rpt No", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6, "Bank Date", '1', 0, 'C', 0);
    $this->pdf->Cell(10, 6, "St", '1', 0, 'C', 0);
    $this->pdf->Ln();

    $this->pdf->setY(35);
    $len=count($all_det);
    $act_len=(int)(($len-11)/14);
    //for($x=0; $x<$act_len; $x++){

    foreach ($all_det as $value) {

        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        $this->pdf->setX(15);
        $this->pdf->SetFont('helvetica','',8);

        $aa = max(1,
            $this->pdf->getNumLines($value->customer." - ".$value->cus_name, 40),
            $this->pdf->getNumLines($value->bank." - ".$value->bank_name, 35),
            $this->pdf->getNumLines($value->branch." - ".$value->branch_name, 35)
            );
        
        $heigh=5*$aa;

        $this->pdf->HaveMorePages($heigh);
        $this->pdf->MultiCell(20, $heigh,$value->date,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(40, $heigh,$value->customer." - ".$value->cus_name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$value->cheque_no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(25, $heigh,$value->amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(25, $heigh,$value->account, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(35, $heigh,$value->bank." - ".$value->bank_name,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(35, $heigh,$value->branch." - ".$value->branch_name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(10, $heigh,$value->ack_no, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$value->rcpt_date, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(10, $heigh,$value->rcpt_no, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$value->bank_date, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(10, $heigh,$value->status, 1, 'C', 0, 1, '', '', true, 0, false, true, 0);
        
    }
    $this->pdf->Output($title.date('Y-m-d').".pdf", 'I');
    ?>