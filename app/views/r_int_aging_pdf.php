<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintHeader(true,$type);
$this->pdf->setPrintFooter(true);

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page); 

$branch_name="";

foreach($branch as $ress){
    $branch_name=$ress->name;
    $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

$this->pdf->setY(25);
$this->pdf->SetFont('helvetica', 'BI',12);
$this->pdf->Cell(180, 1,"Inter Branch Aging Report ",0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->setY(27);
$this->pdf->Cell(55, 1,"",'T',0, 'L', 0);
$this->pdf->Ln(); 

$this->pdf->SetFont('helvetica', '',10);
$this->pdf->Cell(180, 1,"As At ".date("Y-m-d"),0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->setY(40);
$this->pdf->setX(5);

$this->pdf->SetFont('helvetica','B',9);

$this->pdf->Cell(25, 6," Account", '1', 0, 'C', 0);
$this->pdf->Cell(60, 6," Description ", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6," I.No ", '1', 0, 'C', 0);
$this->pdf->Cell(10, 6," R.No ", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6," Balance ", '1', 0, 'C', 0); 
$this->pdf->Cell(15, 6," <30 ", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"31 to 60 ", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"61 to 90 ", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Over 90 ", '1', 0, 'C', 0);

$this->pdf->Ln();

$this->pdf->GetY(40);
$this->pdf->SetX(10);

$this->pdf->SetFont('helvetica','',9);

$customer_code="";
$customer_name="";
$over90=0;
$over61=0;
$over31=0;
$over0=0;
$total=(float)0;
$code=true;

foreach($cus_det as $row){
    $aa = $this->pdf->getNumLines($row->description, 60);
    $bb = $this->pdf->getNumLines($row->acc_code, 25);
    if($aa>$bb){
        $heigh=5*$aa;
    }else{
     $heigh=5*$bb; 
 }
 $this->pdf->haveMorePages($heigh);
 $this->pdf->SetX(5);
 if($code!=ture && $code==$row->acc_code){
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    $this->pdf->MultiCell(25, $heigh,' ',  LR, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(60, $heigh,' ',  LR, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(10, $heigh,$row->sub_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(10, $heigh,$row->receive_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh,$row->balance,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$row->Below30,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh,$row->D30t60,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh,$row->D60t90,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh,$row->Over90,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
}else{
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    $this->pdf->MultiCell(25, $heigh,$row->acc_code,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(60, $heigh,ucfirst(strtolower($row->description)),  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(10, $heigh,$row->sub_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(10, $heigh,$row->receive_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh,$row->balance,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$row->Below30,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh,$row->D30t60,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh,$row->D60t90,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh,$row->Over90,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
}

$code=$row->acc_code;
$total+=(float)$row->balance;
$total1+=(float)$row->Over90;
$total2+=(float)$row->D60t90;
$total3+=(float)$row->D30t60;
$total4+=(float)$row->Below30;
} 

$this->pdf->SetFont('helvetica','B',9);
$this->pdf->SetX(5);
$this->pdf->Cell(25, 6,"", '0', 0, 'L', 0);
$this->pdf->Cell(80, 6,"Total ", '0', 0, 'R', 0);
$this->pdf->Cell(19, 6,number_format($total,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(1, 6,"", '0', 0, 'L', 0);
$this->pdf->Cell(20, 6,number_format($total4,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(1, 6,"", '0', 0, 'L', 0);
$this->pdf->Cell(14, 6,number_format($total3,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(1, 6,"", '0', 0, 'L', 0);
$this->pdf->Cell(19, 6,number_format($total2,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(1, 6,"", '0', 0, 'L', 0);
$this->pdf->Cell(19, 6,number_format($total1,2), 'TB', 0, 'R', 0);




$this->pdf->Output("Customer Balance".date('Y-m-d').".pdf", 'I');
?>
