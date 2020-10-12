<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//$this->pdf->setPrintHeader($header,$type,$duration);

$this->pdf->setPrintFooter(true,'0',$is_cur_time);
$this->pdf->setPrintHeader(true);
$this->pdf->setPrintHeader($header,$type); 

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

$tot_free_item=(float)0;

foreach($tot_free_items as $free){
	$free_price = $free->price;
	$tot_free_item+=(float)$free_price;	
}

foreach($branch as $ress){
	$this->pdf->headerSet4($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach($sum as $cus){
	$cus_name=$cus->name;
	$nno=$cus->nno;
	$ddate=$cus->ddate;
}


foreach($session as $ses){
	$invoice_no=$session[0].$session[1].'CA'.$session[2];
}


$this->pdf->setY(23);


$this->pdf->SetFont('helvetica', 'BU', 10);
$orgin_print=$_POST['org_print'];
if($orgin_print=="1"){
	$this->pdf->Cell(0, 5, 'CUSTOMER SETTLEMENT',0,false, 'L', 0, '', 0, false, 'M', 'M');
}else{
	$this->pdf->Cell(0, 5,'CUSTOMER SETTLEMENT (DUPLICATE)',0,false, 'L', 0, '', 0, false, 'M', 'M');	
}
$this->pdf->SetFont('helvetica', 'B', 10);
$this->pdf->setY(30);

$this->pdf->SetFont('helvetica', '', 9);
$this->pdf->Cell(30, 1, 'Customer', '0', 0, 'L', 0);
$this->pdf->Cell(90, 1, $cus_name, '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, 'Invoice No.', '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $nno, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', '', 9);
$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(90, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, 'Invoice Date', '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $ddate, '0', 0, 'L', 0);

$this->pdf->SetY(40);
$this->pdf->SetFont('helvetica', 'B', 10);
$this->pdf->Cell(30, 1, 'Credit Details', '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 9);
$this->pdf->MultiCell(30, '6','Type', 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(20, '6','Trans No', 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(25, '6','Date', 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(25, '6','Amount', 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(25, '6','Balance',  1, 'C', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(25, '6','Settle', 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(30, '6','Description', 1, 'C', 0, 1, '', '', true, 0, false, true, 0);

$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

foreach($dr_det as $drrow){
	$this->pdf->setX(15);
	$this->pdf->SetFont('helvetica','',8);
	$this->pdf-> HaveMorePages(6);

	$this->pdf->MultiCell(30, 6,$drrow->type, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(20, 6,$drrow->trans_no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, 6,$drrow->date, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, 6,$drrow->amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, 6,$drrow->balance,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, 6,$drrow->settle, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(30, 6,$drrow->description, 1, 'L', 0, 1, '', '', true, 0, false, true, 0);

	$tot_dr+=$drrow->settle;
	
}
$this->pdf->Ln();
$this->pdf->SetFont('helvetica', 'B', 10);
$this->pdf->Cell(30, 1, 'Debit Details', '0', 0, 'L', 0);
$this->pdf->Ln();
$this->pdf->SetFont('helvetica', 'B', 9);
$this->pdf->MultiCell(30, '6','Type', 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(20, '6','Trans No', 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(25, '6','Date', 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(25, '6','Amount', 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(25, '6','Balance',  1, 'C', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(25, '6','Settle', 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(30, '6','Description', 1, 'C', 0, 1, '', '', true, 0, false, true, 0);

foreach($cr_det as $crrow){

	
	$this->pdf->setX(15);
	$this->pdf->SetFont('helvetica','',9);
	$this->pdf-> HaveMorePages(6);

	$this->pdf->MultiCell(30, 6,$crrow->type, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(20, 6,$crrow->trans_no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, 6,$crrow->date, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, 6,$crrow->amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, 6,$crrow->balance,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(25, 6,$crrow->settle, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
	$this->pdf->MultiCell(30, 6,$crrow->description, 1, 'L', 0, 1, '', '', true, 0, false, true, 0);
	
	$tot_cr+=$crrow->settle;
}
$this->pdf->Ln();
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->MultiCell(140, 6,'Toatal Cr', 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(10, 6,'', 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(30, 6,number_format($tot_dr,2), 'TB', 'R', 0, 1, '', '', true, 0, false, true, 0);

$this->pdf->MultiCell(140, 6,'Toatal Dr', 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(10, 6,'', 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(30, 6,number_format($tot_cr,2), 'TB', 'R', 0, 1, '', '', true, 0, false, true, 0);

$this->pdf->MultiCell(140, 6,'Cr Balance', 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(10, 6,'', 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->MultiCell(30, 6,number_format($tot_dr-$tot_cr,2), 'TB', 'R', 0, 1, '', '', true, 0, false, true, 0);

$this->pdf->Output("Customer Settlement_".date('Y-m-d').".pdf", 'I');

?>