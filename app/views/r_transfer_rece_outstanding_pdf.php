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

$this->pdf->setY(22);
$this->pdf->SetFont('helvetica', 'BI',12);
$this->pdf->Cell(180, 1,"Internal Transfer Outstanding List   ",0,false, 'L', 0, '', 0, false, 'M', 'M');


 //----------------------------------------------------------------------------------------------------

$this->pdf->Ln();
$this->pdf->SetFont('helvetica','',8);
$this->pdf->Cell(20, 6,'Account Code :', '0', 0, 'L', 0);
$this->pdf->Cell(20, 6,$acc_code .'-', '0', 0, 'L', 0);
$this->pdf->Cell(40, 6,$acc_code_des, '0', 0, 'L', 0);
$this->pdf->Ln();
$this->pdf->Ln();
$this->pdf->SetX(15);


$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(10, 6,"Cl", '1', 0, 'C', 0);
$this->pdf->Cell(65, 6,"Branch", '1', 0, 'C', 0);
$this->pdf->Cell(15, 6,"Trans No", '1', 0, 'C', 0); 
$this->pdf->Cell(20, 6,"Date" ,'1', 0, 'C', 0); 
$this->pdf->Cell(25, 6,"Amount", '1', 0, 'C', 0);
$this->pdf->Cell(25, 6,"Paid", '1', 0, 'C', 0);
$this->pdf->Cell(25, 6,"Balance", '1', 0, 'C', 0);
$this->pdf->Ln();

foreach($branch_outs as $row){
	$this->pdf->SetX(15);
	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
	$this->pdf->SetFont('helvetica','',8);
	$aa = $this->pdf->getNumLines($row->name, 65);
	$heigh=5*$aa;

	$this->pdf->MultiCell(10, $heigh,$row->sub_cl, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(65, $heigh,$row->name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(15, $heigh,$row->trans_no, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(20, $heigh,$row->ddate, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, $heigh,$row->amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, $heigh,number_format($row->amount-$row->balance,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, $heigh,$row->balance, 1, 'R', 0, 1, '', '', true, 0, false, true, 0);


	$amo+=(float)$row->amount;
	$paid+=(float)$row->amount-$row->balance;
	$bal+=(float)$row->balance;


}

$this->pdf->Ln();   
$this->pdf->SetFont('helvetica','B',8);             
$this->pdf->SetX(15);
$this->pdf->Cell(30, 6, "", '0', 0, 'R', 0);
$this->pdf->Cell(80, 6, "Total", '0', 0, 'R', 0);
$this->pdf->Cell(25, 6, number_format($amo,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(25, 6, number_format($paid,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(25, 6, number_format($bal,2), 'TB', 0, 'R', 0);
$this->pdf->Ln();  
    //----------------------------------------------------------




$this->pdf->Output("recipt_outstanding".date('Y-m-d').".pdf", 'I');

?>



