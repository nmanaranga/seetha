<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintHeader(true,$type);
$this->pdf->setPrintFooter(true);

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage('L','A4'); 

$branch_name="";

foreach($branch as $ress){
    $branch_name=$ress->name;
    $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

$this->pdf->setY(25);
$this->pdf->SetFont('helvetica', 'BI',12);
$this->pdf->Cell(180, 1,"Internal Transfer Receive Status Report ",0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->setY(27);
$this->pdf->Cell(85, 1,"",'T',0, 'L', 0);
$this->pdf->Ln(); 

$this->pdf->SetFont('helvetica', '',10);
$this->pdf->Cell(180, 1,"Date Between ".$from." - ".$to,0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->setY(40);
$this->pdf->setX(15);

$this->pdf->SetFont('helvetica','B',9);

$this->pdf->Cell(20, 6,"  ", '1', 0, 'C', 0);
$this->pdf->Cell(120, 6," Transfer", '1', 0, 'C', 0);
$this->pdf->Cell(120, 6," Receive", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"  ", '1', 0, 'C', 0);
$this->pdf->Ln();
$this->pdf->setX(15);
$this->pdf->Cell(20, 6," Date ", '1', 0, 'C', 0);
$this->pdf->Cell(60, 6," Cluster", '1', 0, 'C', 0);
$this->pdf->Cell(30, 6," Branch", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6," No ", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6," Qty ", '1', 0, 'C', 0); 
$this->pdf->Cell(60, 6," Cluster", '1', 0, 'C', 0);
$this->pdf->Cell(30, 6," Branch ", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6," No ", '1', 0, 'C', 0); 
$this->pdf->Cell(15, 6," Qty", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6," Status", '1', 0, 'C', 0);

$this->pdf->Ln();

$this->pdf->GetY(40);
$this->pdf->SetX(10);
$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
$this->pdf->SetFont('helvetica','',9);

$customer_code="";
$customer_name="";
$total=(float)0;

foreach($int_trans as $row){
    $aa = $this->pdf->getNumLines($row->t_bc, 30);
    $heigh=5*$aa;

    $this->pdf->haveMorePages($heigh);
    $this->pdf->SetX(15);
    $this->pdf->MultiCell(20, $heigh,$row->ddate,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(60, $heigh,$row->t_clus,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(30, $heigh,$row->t_bc,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$row->tr_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$row->qty,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(60, $heigh,$row->r_clus,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(30, $heigh,$row->r_bc,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$row->rec_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$row->received_qty,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$row->status,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
    $tr_qty+=(float)$row->qty;
    $rc_qty+=(float)$row->received_qty;
} 

$this->pdf->Ln();
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->SetX(15);
$this->pdf->Cell(100, 6,"", '0', 0, 'L', 0);
$this->pdf->Cell(25, 6,"Transfer Total ", '0', 0, 'L', 0);
$this->pdf->Cell(15, 6,($tr_qty), 'TB', 0, 'R', 0);
$this->pdf->Cell(80, 6,"", '0', 0, 'L', 0);
$this->pdf->Cell(25, 6,"Transfer Total ", '0', 0, 'L', 0);
$this->pdf->Cell(15, 6,($rc_qty), 'TB', 0, 'R', 0);
$this->pdf->Output("Inter Branch Balance".date('Y-m-d').".pdf", 'I');
?>
