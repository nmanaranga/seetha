<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);
$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

foreach($branch as $ress){
    $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
foreach ($rec_det as $value) {
    $date=$value->ddate;
}

$this->pdf->setY(26);

$this->pdf->SetFont('helvetica', 'BI',12);
$this->pdf->Cell(0, 5, 'INTERNAL TRANSFER RECEIVE DETAILS',0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->setY(29);
$this->pdf->Cell(90, 1,"",'T',0, 'L', 0);
$this->pdf->Ln(); 

$this->pdf->SetFont('helvetica', '',9);
$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$from = $value->cl." - ".$value->bc."( ".$value->f_b_name." )";

$this->pdf->SetFont('helvetica', 'B', 9);
$this->pdf->Cell(25, 5, "Transfer From -  ", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 9);
$this->pdf->Cell(80, 5, $from , '0', 0, 'L', 0);
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
    
foreach ($rec_det as $value) {
    $my_array[]=$value->sub_no;
    $tot=($value->item_cost*$value->qty);
}

foreach ($rec_det as $value) {  
    if ($i==0 || $my_array[$i]!=$my_array[$i-1]) {
        if ($j>=0){
            $this->pdf->SetFont('helvetica','B',8);
            $this->pdf->SetX(25);
            $this->pdf->Cell(40, 6, "", '0', 0, 'L', 0);
            $this->pdf->Cell(90, 6, "", '0', 0, 'L', 0);
            $this->pdf->Cell(20, 6, "Total", '0', 0, 'L', 0);
            $this->pdf->Cell(10, 6, "Rs   ", '0', 0, 'L', 0);
            $this->pdf->Cell(20, 6, number_format($net_tot,2), 'TB', 0, 'R', 0);
            $this->pdf->Ln(); 
            $this->pdf->Ln(); 
        }
        $j++;
        $this->pdf->SetFont('helvetica', 'B', 9);
    if($i==0){
        $this->pdf->setY(43);
    }
    $this->pdf->Ln();
    $this->pdf->SetX(15);

    $fin_tot+=($value->amount*$value->qty);
    $to = $value->to_cl." - ".$value->to_bc."( ".$value->to_bc_name." )";
    $store = $value->store." - ".$value->sto_name;
    
    $this->pdf->Cell(15, 5, 'No - ', '0', 0, 'L', 0);
    $this->pdf->SetFont('helvetica', '', 9);
    $this->pdf->Cell(15, 5, $value->nno, '0', 0, 'L', 0);

    $this->pdf->SetFont('helvetica', 'B', 9);
    $this->pdf->Cell(15, 5, 'Sub No - ', '0', 0, 'L', 0);
    $this->pdf->SetFont('helvetica', '', 9);
    $this->pdf->Cell(15, 5, $value->sub_no, '0', 0, 'L', 0);

    $this->pdf->SetFont('helvetica', 'B', 9);
    $this->pdf->Cell(20, 5, 'Store - ', '0', 0, 'L', 0);
    $this->pdf->SetFont('helvetica', '', 9);
    $this->pdf->Cell(50, 5, $store, '0', 0, 'L', 0);

    $this->pdf->SetFont('helvetica', 'B', 9);
    $this->pdf->Cell(20, 5, 'Date - ', '0', 0, 'L', 0);
    $this->pdf->SetFont('helvetica', '', 9);
    $this->pdf->Cell(25, 5, $value->ddate, '0', 0, 'L', 0);
    $this->pdf->Ln();

    $this->pdf->SetFont('helvetica', 'B', 9);
    $this->pdf->Cell(25, 5, "Transfer To -  ", '0', 0, 'L', 0);
    $this->pdf->SetFont('helvetica', '', 9);
    $this->pdf->Cell(60, 5,  $to, '0', 0, 'L', 0);

    $this->pdf->Ln();
    $this->pdf->Ln();
    $this->pdf->SetX(15);
    $this->pdf->SetFont('helvetica','B',8);

    $this->pdf->Cell(30, 6,"Item Code", '1', 0, 'C', 0);
    $this->pdf->Cell(90, 6,"Description", '1', 0, 'C', 0);
    $this->pdf->Cell(10, 6,"Batch", '1', 0, 'C', 0);
    $this->pdf->Cell(15, 6,"Qty", '1', 0, 'C', 0);
    $this->pdf->Cell(22, 6,"Price", '1', 0, 'C', 0);
    $this->pdf->Cell(22, 6,"Amount", '1', 0, 'C', 0);
    $this->pdf->Ln();

}
                                
    $this->pdf->SetX(15);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    $this->pdf->SetFont('helvetica','',8);
    $aa = $this->pdf->getNumLines($value->description, 90);
    $heigh=5*$aa;
    $this->pdf->MultiCell(30, $heigh,$value->item_code,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(90, $heigh,$value->itm_name,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(10, $heigh,$value->batch_no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(15, $heigh,$value->qty,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(22, $heigh,$value->item_cost,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(22, $heigh,number_format($tot,2),  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
    $i++;
    $net_tot=($value->amount*$value->qty);
    

    }
    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->SetX(25);
    $this->pdf->Cell(40, 6, "", '0', 0, 'L', 0);
    $this->pdf->Cell(90, 6, "", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 6, "Total", '0', 0, 'L', 0);
    $this->pdf->Cell(10, 6, "Rs   ", '0', 0, 'R', 0);
    $this->pdf->Cell(20, 6, number_format($net_tot,2), '0', 0, 'R', 0);
    $this->pdf->Ln();

    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->SetX(25);
    $this->pdf->Cell(40, 6, "", '0', 0, 'L', 0);
    $this->pdf->Cell(90, 6, "", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 6, "", '0', 0, 'L', 0);
    $this->pdf->Cell(10, 6, "   ", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 6, "", '0', 0, 'R', 0);

    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->SetX(25);
    $this->pdf->Cell(40, 6, "", '0', 0, 'L', 0);
    $this->pdf->Cell(90, 6, "", '0', 0, 'L', 0);
    $this->pdf->Cell(20, 6, "Final Total", '0', 0, 'L', 0);
    $this->pdf->Cell(10, 6, "Rs   ", '0', 0, 'R', 0);
    $this->pdf->Cell(20, 6, number_format($fin_tot,2), 'TB', 0, 'R', 0);

      
    $this->pdf->Output("Internal Transfer Detail".date('Y-m-d').".pdf", 'I');

?>