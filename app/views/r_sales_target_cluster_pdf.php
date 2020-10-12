<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintHeader(true,$type);
$this->pdf->setPrintFooter(true);

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page); 

$branch_name="";
$bcName="";
$clName="";
foreach($branch as $ress){
    $branch_name=$ress->name;
    $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    $bcName=$ress->bc."- ".$ress->name;
}

foreach($cluster as $cl){
    $clName=$cl->code."- ".$cl->description;

}
foreach($occ as $us){
    $user=$us->discription;

}





$this->pdf->setY(25);
$this->pdf->SetFont('helvetica', 'BI',12);
$this->pdf->Cell(180, 1,"Monthly Sales Target Cluster Wise  ",0,false, 'L', 0, '', 0, false, 'M', 'M');

$this->pdf->Ln();
$this->pdf->setY(27);
$this->pdf->Cell(70, 1,"",'T',0, 'L', 0);
$this->pdf->Ln();
$this->pdf->Ln();


$this->pdf->Ln();
$this->pdf->setY(35);
$this->pdf->setX(25);
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(25, 1, "Cluster", '0', 0, 'L', 0);
$this->pdf->Cell(80, 1, ": ".$clName,'0', 0, 'L', 0);

$this->pdf->Ln();

// $this->pdf->setX(25);
// $this->pdf->Cell(25, 1, "Branch", '0', 0, 'L', 0);
// $this->pdf->Cell(80, 1,": ".$bcName, '0', 0, 'L', 0);

// $this->pdf->Ln();

$this->pdf->setX(25);
$this->pdf->Cell(25, 1, "User", '0', 0, 'L', 0);
$this->pdf->Cell(80, 1,": ".$user, '0', 0, 'L', 0);

$this->pdf->Ln();

$this->pdf->setX(25);
$this->pdf->Cell(25, 1, "Month", '0', 0, 'L', 0);
$this->pdf->Cell(80, 1,": ".$month, '0', 0, 'L', 0);


$this->pdf->Ln(10);

$this->pdf->setX(5);
$this->pdf->MultiCell(37, $heigh, "Branch" ,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
// $this->pdf->MultiCell(25, $heigh, "Branch",  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(25, $heigh, "Target",  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(35, $heigh, "Achivement",  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(15, $heigh, "Achi. %",  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(30, $heigh, "Variance Amount",  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(15, $heigh, "V. %",  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->Ln();


$target;
$archment;

foreach($salesTrget as $row){
    $target+=(float)$row->target;
    $archment+=$row->achivement;


    $this->pdf->GetY(40);
    $this->pdf->SetX(5);
    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));


    $yrdata= strtotime($row->ddate);

    $pre=(($row->achivement)/($row->target) ) *100;

    $this->pdf->MultiCell(37, $heigh, $row->bcName  ,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    // $this->pdf->MultiCell(25, $heigh, $row->bc,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(25, $heigh, number_format($row->target,2),  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(35, $heigh, number_format($row->achivement,2)  ,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh, number_format($pre,2)."%"          ,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
     $this->pdf->MultiCell(30, $heigh, $varAmount  ,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh, number_format($varAmountPres,2)."%"   ,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);


//$this->pdf->Ln();
}
$this->pdf->Ln();
$this->pdf->SetFont('helvetica','B',9);


$this->pdf->HaveMorePages(1);

$archmentPre=($archment/$target)*100;
$this->pdf->Cell(35, 1, " Total Target", '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, ":".number_format($target,2), 'TB', 0, 'L', 0);
$this->pdf->Ln();
$this->pdf->Cell(35, 1, " Total Archivement", '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, ":".number_format($archment,2)." ~  ".number_format($archmentPre,2)."%", 'TB', 0, 'L', 0);
$this->pdf->Ln();
$this->pdf->Cell(35, 1, " Variance", '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, ":".number_format($target-$archment,2) ,'TB', 0, 'L', 0);


$this->pdf->Ln();
$this->pdf->Cell(35, 6, "Cumilative Target", '0', 0, 'L', 0);
$this->pdf->Cell(5, 6, " : ", '0', 0, 'L', 0);
$this->pdf->Cell(30, 6, $cumlTar ,'TB', 0, 'R', 0);
$this->pdf->Ln();
$this->pdf->Cell(35, 6, "Cumilative Archi. ", '0', 0, 'L', 0);
$this->pdf->Cell(5, 6, " : ", '0', 0, 'L', 0);
$this->pdf->Cell(30, 6, $cumlArch,'TB', 0, 'R', 0);
$this->pdf->Ln();
$this->pdf->Cell(35, 6, "Cumilative Variance :", '0', 0, 'L', 0);
$this->pdf->Cell(5, 6, " : ", '0', 0, 'L', 0);
$this->pdf->Cell(30, 6, $cumlVar."  ~ ".$cumlVarPre."%" ,'TB', 0, 'R', 0);




$this->pdf->Output("Monthly Sales Target Report".date('Y-m-d').".pdf", 'I');

?>
