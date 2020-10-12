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
    $this->pdf->Cell(180, 1,"Trial Balance Report  ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(25);$this->pdf->Cell(60, 1,"",'T',0, 'L', 0);$this->pdf->Ln(); 

    $this->pdf->setY(28);$this->pdf->SetFont('helvetica', '', 8);
    $this->pdf->Cell(180, 1,"Date From - ".$dfrom."  To - ".$dto,0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    //----------------------------------------------------------------------------------------------------

    $lineRight=204;
$lineLeft=6;

$this->pdf->SetFont('helvetica', 'B', 16);

$y=$this->pdf->GetY();
$this->pdf->line($lineLeft,$y,$lineRight,$y);//160
$this->pdf->SetFont('helvetica', 'B', '10');

$y=$this->pdf->setY(40);
$this->pdf->line(10,$y,100,$y);

$this->pdf->SetX(8);

// $this->pdf->MultiCell(15, 1, "Code", '0', 'R', 0, 0, '', '', 1, 0, 0);
// $this->pdf->MultiCell(70,1, "Description ", '0', 'L', 0, 0, '', '', 1, 0, 0);
// $this->pdf->MultiCell(25, 1, "Dr. Amount ", '0', 'R', 0, 0, '', '', 1, 0, 0);
// $this->pdf->MultiCell(25, 1, "Cr. Amount ", '0', 'R', 0, 0, '', '', 1, 0, 0);
// $this->pdf->MultiCell(5, 1, "", '0', 'R', 0, 0, '', '', 0, 0, 0);
// $this->pdf->MultiCell(25, 1, "Dr. Amount ", '0', 'R', 0, 0, '', '', 1, 0, 0);
// $this->pdf->MultiCell(25, 1, "Cr. Amount ", '0', 'R', 0, 1, '', '', 1, 0, 0);

$y=$this->pdf->GetY();
//$this->pdf->line(10,$y,100,$y);

$y=$this->pdf->GetY();
//$this->pdf->line($lineLeft,$y,$lineRight,$y);


 $t1=0;
 $t11=0;
 $count=0;
 $t2;
 $t22;
 $bal=0;
 $balance=0;
 $b=0;
 $tbal=0;

 $this->pdf->SetFont('helvetica', 'B', '8');


        // $r = array();

        // $b=$r[8];

$this->pdf->MultiCell(15, 5, '', '', '1', 0, 0, '', '', false, '', 0);
$this->pdf->MultiCell(70, 5, '', '', '1', 0, 0, '', '', false, '', 0);        
$this->pdf->MultiCell(25, 5, 'As at date:', '1', 'C', 0, 0, '', '', false, '', 0);        
$this->pdf->MultiCell(25, 5, $dto, '1', 'R', 0, 0, '', '', false, '', 0);        
$this->pdf->MultiCell(5, 5, '', '#', '1', 0, 0, '', '', false, '', 0);         
$this->pdf->MultiCell(25, 5, 'Dt : '.$dfrom, '1', 'C', 0, 0, '', '', false, '', 0);        
$this->pdf->MultiCell(25, 5, $dto, '1', 'C', 0, 1, '', '', false, '', 0);        
        
foreach($trial_balance as $r){

$this->pdf->SetX(9);

$aa = $this->pdf->getNumLines($r->description, 50);
$heigh=5*$aa;

$this->pdf->SetFont('helvetica', '', '9');
$this->pdf->MultiCell(25, $heigh, $r->code,  0, 'L', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(59, $heigh, $r->description, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);

$balance=$r->dr_Asat - $r->cr_Asat;
    if($balance>0)
    {
    $this->pdf->MultiCell(25, $heigh, d($balance), '1', 'R', 0, 0, '', '', '', '', 0, 0);
    $this->pdf->MultiCell(25, $heigh, d(0), '1', 'R', 0, 0, '', '', '', '', 0, 0);
    $t1 =$balance+$t1;
    }
    else
    {
    $this->pdf->MultiCell(25, $heigh, d(0), '1', 'R', 0, 0, '', '', '', '', 0, 0);
    $this->pdf->MultiCell(25, $heigh, d(-($balance)), '1', 'R', 0, 0, '', '', '', '', 0, 0);
    $t2= -($balance)+$t2;
    }
   
 $this->pdf->MultiCell(5, $heigh, '', '#', 'L', 0, 0, '', '', false, '', 0);   
    
$balance1=$r->dr_range -$r->cr_range;
    if($balance1>0)
    {
    $this->pdf->MultiCell(25, $heigh, d($balance1), '1', 'R', 0, 0, '', '', '', '', 0, 0);
    $this->pdf->MultiCell(25, $heigh, d(0), '1', 'R', 0, 1, '', '', '', '', 0, 0);
    $t11 =$balance1+$t11;
    }
    else
    {
    $this->pdf->MultiCell(25, $heigh, d(0), '1', 'R', 0, 0, '', '', '', '', 0, 0);
    $this->pdf->MultiCell(25, $heigh, d(-($balance1)), '1', 'R', 0, 1, '', '', '', '', 0, 0);
    $t22= -($balance1)+$t22;
    }

$count=$count+1;
$balance=0;
$balance1=0;
}

 $this->pdf->SetFont('', 'B', '');



$y=$this->pdf->GetY();
// $this->pdf->line($lineLeft,$y,$lineRight,$y);
$this->pdf->SetFont('', 'B', '9');
$this->pdf->MultiCell(25, 1, "", '0', 'R', 0, 0, '', '', '', '', 0, 0);
$this->pdf->MultiCell(53, 1, "Total", '0', 'C', 0, 0, '', '', '', '', 0, 0);
$this->pdf->MultiCell(25, 1, d($t1), '1', 'R', 0, 0, '', '', '', '', 0, 0);
$this->pdf->MultiCell(25, 1, d($t2), '1', 'R', 0, 0, '', '', '', '', 0, 0);
$this->pdf->MultiCell(5, 1, '', '0', 'R', 0, 0, '', '', '', '', 0, 0);
$this->pdf->MultiCell(25, 1, d($t11), '1', 'R', 0, 0, '', '', '', '', 0, 0);
$this->pdf->MultiCell(25, 1, d($t22), '1', 'R', 0, 0, '', '', '', '', 0, 0);

// $y=$this->pdf->GetY()+5;
// $this->pdf->line($lineLeft,$y,$lineRight,$y);
// $y=$this->pdf->GetY()+5.5;
// $this->pdf->line($lineLeft,$y,$lineRight,$y);

function d($number) {
    return number_format($number, 2, '.', ',');
}
function dd($number,$decimals) {
    return number_format($number, $decimals, '.', ',');
}
    /*$des="default";
    $tno="default";
    $amt=(float)0;

        $colwidth_1 = 10; //no
        $colwidth_2 = 20; //date
        $colwidth_3 = 26; //Account Code
        $colwidth_4 = 40; //Acc Descrition
        $colwidth_5 = 26; //Oposit.Account
        $colwidth_6 = 35; //Description
        $colwidth_7 = 30; //Amount
        $rowsheight= 4;

        $this->pdf->SetFont('helvetica', '', 8);$this->pdf->Cell(188, 1,"",'B',0, 'L', 0);$this->pdf->Ln();
        $this->pdf->SetX(15);
        $this->pdf->SetFont('helvetica','B',8);
        
        $this->pdf->Cell($colwidth_1, 6,"No", 'B', 0, 'R', 0);
        $this->pdf->Cell($colwidth_2, 6,"Date", 'B', 0, 'R', 0);
        $this->pdf->Cell($colwidth_3, 6, "Account Code", 'B', 0, 'L', 0);
        $this->pdf->Cell($colwidth_4, 6, "Acc Description", 'B', 0, 'L', 0);
        $this->pdf->Cell($colwidth_5, 6,"Oposit.Account", 'B', 0, 'L', 0);
        $this->pdf->Cell($colwidth_6, 6,"Description", 'B', 0, 'L', 0);
        $this->pdf->Cell($colwidth_7, 6,"Amount", 'B', 0, 'R', 0);
        $this->pdf->Ln(); 

    $x=0;
    foreach($credit_note as $row)
    {
        $this->pdf->SetFont('helvetica','',8);
        $this->pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

        $this->pdf->SetX(15);
        $this->pdf->Cell($colwidth_1, $rowsheight,$row->nno, '0', 0, 'R', 0);
        $this->pdf->Cell($colwidth_2, $rowsheight,$row->ddate, '0', 0, 'R', 0);
        $this->pdf->Cell($colwidth_3, $rowsheight,$row->cus_code, '0', 0, 'L', 0);
        $this->pdf->Cell($colwidth_4, $rowsheight,$row->customer, '0', 0, 'L', 0);
        $this->pdf->Cell($colwidth_5, $rowsheight,$row->acc_code, '0', 0, 'L', 0);
        $this->pdf->Cell($colwidth_6, $rowsheight,$row->cr_account, '0', 0, 'L', 0);
        $this->pdf->Cell($colwidth_7, $rowsheight,$row->amount, '0', 0, 'R', 0);
        $this->pdf->Ln();  
 
        $amt=(float)$amt+(float)$row->amount;

        $x=$x+1;                      

        if($x % 5==0){
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1.5, 'color' => array(0, 0, 0)));
            $this->pdf->Cell(180, 1,'', 'B', 0, 'L', 0);
            $this->pdf->Ln();
        }
    } 

    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->SetX(20);
    $this->pdf->Cell(117, 6,"", '', 0, 'L', 0);
    $this->pdf->Cell($colwidth_6, 6,Total, '', 0, 'L', 0);
    $this->pdf->Cell($colwidth_7, 6,number_format($amt,2, '.', ''), 'B', 0, 'R', 0);
    $this->pdf->Ln(); 
*/
    $this->pdf->Output("Credit Note Detailes Report".date('Y-m-d').".pdf", 'I');

?>
        


