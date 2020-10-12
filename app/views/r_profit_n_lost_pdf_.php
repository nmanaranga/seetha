<?php
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    $this->pdf->setPrintHeader($header,$type,$duration);
    $this->pdf->setPrintHeader(true,$type);
    $this->pdf->setPrintFooter(true);
    
    $this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage($orientation,$page); 

    $branch_name="";

    //set header -----------------------------------------------------------------------------------------
    foreach($branch as $ress)
    {
        $branch_name=$ress->name;
        $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    }
    $this->pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
    
    $this->pdf->setY(22);$this->pdf->SetFont('helvetica', 'BI',12);
    $this->pdf->Cell(180, 1,"Profit And Lost Reoprt  ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(25);$this->pdf->Cell(60, 1,"",'T',0, 'L', 0);$this->pdf->Ln(); 

    $this->pdf->setY(28);$this->pdf->SetFont('helvetica', '', 8);
    $this->pdf->Cell(180, 1,"Date From - ".$dfrom."  To - ".$dto,0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    //----------------------------------------------------------------------------------------------------


    $uc = 1;
    $no = 2;
    $eDate = 3;
    $lineRight=204;
    $lineLeft=6;
    $t1=$t2=0;

    $this->pdf->SetFont('helvetica', 'B', '10');
    $y=$this->pdf->GetY();
    $this->pdf->line($lineLeft,$y,$lineRight,$y);//160

//====================income section==========================    
    
    $this->pdf->SetX(6);
    $this->pdf->MultiCell(0, 1, "INCOME", 'U', 'C', 0,1, '', '', 1, 0, 0);
    $this->pdf->MultiCell(155, 1, "(Rs.)      ", '0', 'R', 0, 0, '', '', 1, 0, 0);
   // $this->pdf->MultiCell(30, 1, "(Rs.)", '0', 'C', 0, 1, '', '', 1, 0, 0);
    $this->pdf->ln();
    $this->pdf->SetFont('helvetica', '', '10');

foreach($pnl as $r){
            
            $this->pdf->SetFont('', '', '9');
            $this->pdf->SetX(6);
            $this->pdf->MultiCell(25, 1, $r->code, '0', 'R', 0, 0, '', '', false, '', 0);
            $this->pdf->MultiCell(110, 1, $r->description, '0', 'L', 0, 0, '', '', false, '', 0);
            $this->pdf->MultiCell(25, 1, d($r->bal), '0', 'R', 0, 1, '', '', '', '', 0, 0);
            $t1 +=$r->bal;
           
 $this->pdf->SetFont('', 'B', '');
}

$y=$this->pdf->GetY();
$this->pdf->line($lineLeft,$y,$lineRight,$y);
$this->pdf->SetFont('', 'B', '9');
$this->pdf->SetX(6);
$this->pdf->MultiCell(130, 1, "Total Income :", '0', 'R', 0, 0, '', '', '', '', 0, 0);
$this->pdf->MultiCell(30, 1, d($t1), '0', 'R', 0, 1, '', '', '', '', 0, 0);

//====================expence section==========================

$this->pdf->ln();
$this->pdf->SetFont('', 'B', '10');
$this->pdf->MultiCell(173, 1, "EXPENSES", '0', 'C', 0, 1, '', '', 1, 0, 0);

foreach($pnl2 as $rr){

    $this->pdf->SetFont('', '', '9');
    $this->pdf->SetX(6);
    $this->pdf->MultiCell(25, 1, $rr->code, '0', 'R', 0, 0, '', '', false, '', 0);
    $this->pdf->MultiCell(110, 1, $rr->description, '0', 'L', 0, 0, '', '', false, '', 0);
    $this->pdf->MultiCell(25, 1, d($rr->bal), '0', 'R', 0, 1, '', '', '', '', 0, 0);
    $t2 =$t2+$rr->bal;

}

$this->pdf->SetFont('', 'B', '');

$y=$this->pdf->GetY();
$this->pdf->line($lineLeft,$y,$lineRight,$y);
$this->pdf->SetFont('', 'B', '9');
$this->pdf->SetX(6);
$this->pdf->MultiCell(130, 1, "Total Expenses :", '0', 'R', 0, 0, '', '', '', '', 0, 0);
$this->pdf->MultiCell(30, 1, d($t2), '0', 'R', 0, 1, '', '', '', '', 0, 0);
$this->pdf->ln();
$this->pdf->SetX(6);
$y=$this->pdf->GetY();
$this->pdf->line(140,$y,170,$y);
$this->pdf->MultiCell(130, 1, "Total Profit :", '0', 'R', 0, 0, '', '', '', '', 0, 0);
$this->pdf->MultiCell(30, 1, d($t1-$t2), '0', 'R', 0, 1, '', '', '', '', 0, 0);
$y=$this->pdf->GetY();
$this->pdf->line(140,$y,170,$y);
$y=$this->pdf->GetY()+0.5;
$this->pdf->line(140,$y,170,$y);



function d($number) {
    return number_format($number, 2, '.', ',');
}
function dd($number,$decimals) {
    return number_format($number, $decimals, '.', ',');
}


    $this->pdf->Output("Credit Note Detailes Report".date('Y-m-d').".pdf", 'I');

?>
        


