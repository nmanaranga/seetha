<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintHeader(true,$type);
$this->pdf->setPrintFooter(true);

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page); 

$branch_name="";

    //set header -----------------------------------------------------------------------------------------
foreach ($company as $r) {
  $c_name=$r->name; 
}
foreach ($branch as $ress) {
  $name=$ress->name;
  $address=$ress->address;
  $tp= $ress->telno;
  $fax=$ress->faxno;
  $email=$ress->email;    
}

foreach($branch as $ress){
 $this->pdf->headerSet3($name,$address,$tp,$fax,$email,$c_name);
}
$this->pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

$this->pdf->setY(25);$this->pdf->SetFont('helvetica', 'BI',12);
$this->pdf->Cell(180, 1,"BALANCE SHEET  ",0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->setY(28);$this->pdf->Cell(60, 1,"",'T',0, 'L', 0);
$this->pdf->Ln(); 

$this->pdf->setY(30);$this->pdf->SetFont('helvetica', '', 8);
    //$this->pdf->Cell(180, 1,"Date From - ".$dfrom."  To - ".$dto,0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Cell(180, 1,"As at ".$dto,0,false, 'L', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$t1=$t2=0;
$t1s=$t2s=0;
$gh;
$bdr = "0";

    //Assets---------------------------------------------------------------------------------------------
$this->pdf->SetFont('helvetica', '', '12');
$this->pdf->SetX(6);
$this->pdf->MultiCell(0, 1, "Assets", 'U', 'C', 0,1, '', '', 1, 0, 0);
$this->pdf->MultiCell(155, 1, "(Rs.)      ", '0', 'R', 0, 0, '', '', 1, 0, 0);
$this->pdf->ln();

foreach($bal_sheet2 as $r)
{
  $this->pdf->SetFont('', 'BI', '9');
                if ($gh!=($r->heading)) {//New Heading
                 $this->pdf->HaveMorePages(5);
                    if ($t1s<>0){//Sub total
                      $this->pdf->HaveMorePages(5);
                      $this->pdf->SetX(150);
                      $this->pdf->MultiCell(50, 5, d( $t1s) , $bdr, 'R', 0, 0, '', '', false, '', 0);    
                      $this->pdf->ln();
                      $t1s=0;
                    };
                    $this->pdf->MultiCell(150, 5, $r->heading , $bdr, 'LBU', 0, 0, '', '', false, '', 0);
                    $this->pdf->ln();                
                  } 
                  $this->pdf->SetFont('', '', '9');
                  $this->pdf->HaveMorePages(5);
                  $this->pdf->SetX(25);
                  $this->pdf->MultiCell(30, 5, $r->code, $bdr, 'L', 0, 0, '', '', false, '', 0);
                  $this->pdf->MultiCell(90, 5, $r->description, $bdr, 'L', 0, 0, '', '', false, '', 0);
                  $this->pdf->MultiCell(25, 5,d( $r->bal), $bdr, 'R', 0, 1, '', '', '', '', 0, 0);
                  $gh=$r->heading; 
                  $t1 +=$r->bal;
                  $t1s+=$r->bal;

                  $this->pdf->SetFont('', 'B', '');
                }
    //Last sub total
                $y=$this->pdf->GetY();
                $this->pdf->line($lineLeft,$y,$lineRight,$y);
                $this->pdf->SetFont('', 'BI', '9');
                $this->pdf->SetX(150);
                $this->pdf->MultiCell(50, 1, d($t1s), '0', 'R', 0, 0, '', '', '', '', 0, 0);
                $this->pdf->ln();                


                $y=$this->pdf->GetY();
                $this->pdf->line($lineLeft,$y,$lineRight,$y);
                $this->pdf->SetFont('', 'BI', '11');
                $this->pdf->SetX(100);
                $this->pdf->MultiCell(50, 1, "Total Assets :", '0', 'R', 0, 0, '', '', '', '', 0, 0);
                $this->pdf->SetFont('', 'BU', '12');
                $this->pdf->MultiCell(50, 1,d($t1), '0', 'R', 0, 1, '', '', '', '', 0, 0);

   //Liabilities---------------------------------------------------------------------------------------------
                $this->pdf->HaveMorePages(15);
                $this->pdf->SetFont('', 'B', '12');
                $this->pdf->SetX(6);
                $this->pdf->MultiCell(0, 1, "Liabilities", 'U', 'C', 0,1, '', '', 1, 0, 0);
                $this->pdf->MultiCell(155, 1, "(Rs.)      ", '0', 'R', 0, 0, '', '', 1, 0, 0);
                $this->pdf->ln();
                $this->pdf->SetFont('helvetica', '', '10');

                $gh="";
          //  $bdr ="1";

                foreach($bal_sheet3 as $r)
                {

                  $this->pdf->SetFont('', 'BI', '9');
                if ($gh!=($r->heading)) {//New Heading
                    if ($t2s<>0){//Sub total
                      $this->pdf->SetX(150);
                      $this->pdf->MultiCell(50, 1, d( $t2s) , $bdr, 'R', 0, 0, '', '', false, '', 0);    
                      $this->pdf->ln();
                      $t1s=0;
                    };
                    $this->pdf->MultiCell(150, 1, $r->heading , $bdr, 'LBU', 0, 0, '', '', false, '', 0);
                    $this->pdf->ln();                
                  } 
                  
                  $this->pdf->SetFont('', '', '9');
                  $this->pdf->SetX(25);
                  $this->pdf->MultiCell(30, 1, $r->code, $bdr, 'L', 0, 0, '', '', false, '', 0);
                  $this->pdf->MultiCell(90, 1, $r->description, $bdr, 'L', 0, 0, '', '', false, '', 0);
                  $this->pdf->MultiCell(25, 1, d($r->bal), $bdr, 'R', 0, 1, '', '', '', '', 0, 0);
                  $gh=$r->heading; 
                  $t2 +=$r->bal;
                  $t2s+=$r->bal;

                  $this->pdf->SetFont('', 'B', '');
                }

        //Last sub total
                $y=$this->pdf->GetY();
    //$this->pdf->line($lineLeft,$y,$lineRight,$y);
                $this->pdf->SetFont('', 'BI', '9');
                $this->pdf->SetX(45);
                $this->pdf->MultiCell(150, 1, d($t2s), $bdr, 'R', 0, 0, '', '', '', '', 0, 0);
                $this->pdf->ln();       

    //--Profit of the period
                foreach($bal_sheet1 as $r)
                {
                  $this->pdf->SetFont('', '', '9');
                  $this->pdf->SetX(25);
                  $this->pdf->MultiCell(20, 1, "", $bdr, 'R', 0, 0, '', '', false, '', 0);
                  $this->pdf->MultiCell(90, 1, "Profit of the period", $bdr, 'L', 0, 0, '', '', false, '', 0);
                  $this->pdf->MultiCell(25, 1, d($r->bal), $bdr, 'R', 0, 1, '', '', '', '', 0, 0);
                  $t2 +=$r->bal;
                }
                

                $this->pdf->SetFont('', 'B', '');


                $y=$this->pdf->GetY();
                $this->pdf->line($lineLeft,$y,$lineRight,$y);
                $this->pdf->SetFont('', 'BI', '11');
                $this->pdf->SetX(40);
                $this->pdf->MultiCell(124, 1, "Total Liabilities :", $bdr, 'R', 0, 0, '', '', '', '', 0, 0);
                $this->pdf->SetFont('', 'BU', '12');
                $this->pdf->MultiCell(40, 1, d($t2), '0', 'R', 0, 1, '', '', '', '', 0, 0);

    //----------------------------------------------------------------------------------------------------

                function d($number) 
                {
                  return number_format($number, 2, '.', ',');
                }
                function dd($number,$decimals) 
                {
                  return number_format($number, $decimals, '.', ',');
                }



                $this->pdf->Output("Balance Sheet Report".date('Y-m-d').".pdf", 'I');


                ?>



