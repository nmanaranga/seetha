<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

foreach($branch as $ress){
	$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
foreach ($purchase as $value){
	$inv_no=$value->nno;
	$name=$value->name;
}

foreach ($category as $cat){
	$code=$cat->code;
	$des=$cat->description;
}

$this->pdf->setY(22);

$this->pdf->SetFont('helvetica', 'BU',10);
$this->pdf->Cell(0, 5, 'TOTAL CREDIT SALES SUMMARY',0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();$this->pdf->Ln();

$this->pdf->SetFont('helvetica', '',9);
$this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();
$this->pdf->Ln();

if($category_field!="0"){
	$this->pdf->SetX(20);
	$this->pdf->SetFont('helvetica', 'B',8);
	$this->pdf->Cell(45, 6,"Category : ".$code." - ".$des, '0', 0, 'R', 0);
}

if($group!=""){
	$this->pdf->SetX(60);
	$this->pdf->SetFont('helvetica', 'B',8);
	$this->pdf->Cell(45, 6,"Group : ".$group." - ".$group_des, '0', 0, 'R', 0);
}

$this->pdf->Ln();
$this->pdf->Ln();

			 //----check data is available for print ----        
if($value->nno == "")
{
	$this->pdf->SetX(80);
	$this->pdf->Cell(20, 1, "No Records For View ! ! !", '0', 0, 'L', 0);     
}
else{

	$this->pdf->SetY(50);
	$this->pdf->SetX(5);
	$this->pdf->SetFont('helvetica','B',7);
	$this->pdf->Cell(15, 6,"No", '1', 0, 'C', 0);
	$this->pdf->Cell(15, 6,"Sub No", '1', 0, 'C', 0);
	$this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
	$this->pdf->Cell(50, 6,"Customer", '1', 0, 'C', 0);
	$this->pdf->Cell(20, 6,"Gross Amount", '1', 0, 'C', 0);
	$this->pdf->Cell(20, 6,"Discount", '1', 0, 'C', 0);
	$this->pdf->Cell(20, 6,"Additional", '1', 0, 'C', 0);
	$this->pdf->Cell(20, 6,"Net Amount", '1', 0, 'C', 0);
	$this->pdf->Cell(20, 6,"Store", '1', 0, 'C', 0);

	$this->pdf->Ln();

	$tot_gross=(float)0;
	$tot_net=(float)0;
	$tot_dis=(float)0;

	foreach ($purchase as $value) {
		$net = (float)$value->net_amount;
		
		$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
		$this->pdf->SetFont('helvetica','',7);

		$aa = $this->pdf->getNumLines($value->cus_id." | ".$value->name, 50);
		$bb = $this->pdf->getNumLines($value->description,20);
		if($aa>$bb){
			$heigh=4*$aa;
		}else{
			$heigh=4*$bb;
		}
		$this->pdf->haveMorePages($heigh);
		$this->pdf->SetX(5);
		$this->pdf->MultiCell(15, $heigh,$value->nno,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(15, $heigh,$value->sub_no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh,$value->ddate, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(50, $heigh,$value->cus_id." | ".$value->name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh,number_format($value->gross,2),  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh,number_format((float)$value->discount+(float)$value->additional_deduct,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh,number_format($value->additional_add,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh,number_format($value->net_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
		$this->pdf->MultiCell(20, $heigh,$value->description, 1, 'L', 0, 1, '', '', true, 0, false, true, 0);

		$tot_gross+=(float)$value->gross;
		$tot_net+=(float)$value->net_amount;
		$tot_disc+=(float)$value->discount+(float)$value->additional_deduct;
		$tot_add+=(float)$value->additional_add;

	}
	foreach ($sum as $sum) {
		$Goss=$sum->gsum;
		$net=$sum->nsum;
		$addi=$sum->addi;
	}

	$this->pdf->SetFont('helvetica','B',8);
	$this->pdf->SetX(5);
	$this->pdf->Cell(15, 6,"", '0', 0, 'L', 0);
	$this->pdf->Cell(15, 6,"", '0', 0, 'L', 0);
	$this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
	$this->pdf->Cell(50, 6,"Credit Note Discount ", '0', 0, 'R', 0);
	$this->pdf->Cell(20, 6,number_format(0.00,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
	$this->pdf->Cell(19, 6,number_format($crn,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
	$this->pdf->Cell(20, 6,number_format(0.00,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(20, 6,number_format(0.00,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);

	$this->pdf->Ln();

	$this->pdf->SetFont('helvetica','B',8);
	$this->pdf->SetX(5);
	$this->pdf->Cell(15, 6,"", '0', 0, 'L', 0);
	$this->pdf->Cell(15, 6,"", '0', 0, 'L', 0);
	$this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
	$this->pdf->Cell(50, 6,"Total ", '0', 0, 'R', 0);
	$this->pdf->Cell(20, 6,number_format($tot_gross,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
	$this->pdf->Cell(19, 6,number_format((float)$tot_disc+(float)$crn,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
	$this->pdf->Cell(20, 6,number_format((float)$tot_add,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(20, 6,number_format($tot_net-(float)$crn,2), 'TB', 0, 'R', 0);
	$this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);



	if(!empty($cancel)){
		$this->pdf->Ln();
		$this->pdf->SetX(5);
		$this->pdf->Cell(15, 6,"CANCELED SALES", '0', 0, 'L', 0);
		$c_gross=$c_dis=$c_add=$c_net=0;
		$this->pdf->Ln();
		$this->pdf->Ln();
		$this->pdf->SetX(5);
		$this->pdf->SetFont('helvetica','B',7);
		$this->pdf->Cell(15, 6,"No", '1', 0, 'C', 0);
		$this->pdf->Cell(15, 6,"Sub No", '1', 0, 'C', 0);
		$this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
		$this->pdf->Cell(50, 6,"Customer", '1', 0, 'C', 0);
		$this->pdf->Cell(20, 6,"Gross Amount", '1', 0, 'C', 0);
		$this->pdf->Cell(20, 6,"Discount", '1', 0, 'C', 0);
		$this->pdf->Cell(20, 6,"Additional", '1', 0, 'C', 0);
		$this->pdf->Cell(20, 6,"Net Amount", '1', 0, 'C', 0);
		$this->pdf->Cell(20, 6,"Store", '1', 0, 'C', 0);
		$this->pdf->Ln();

		foreach ($cancel as $value) {
			$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
			$aa = $this->pdf->getNumLines($value->cus_id." | ".$value->name, 50);
			$bb = $this->pdf->getNumLines($value->description,20);
			if($aa>$bb){
				$heigh=4*$aa;
			}else{
				$heigh=4*$bb;
			}
			$this->pdf->haveMorePages($heigh);
			$this->pdf->SetX(5);
			$this->pdf->MultiCell(15, $heigh,$value->nno,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(15, $heigh,$value->sub_no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(20, $heigh,$value->ddate, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(50, $heigh,$value->cus_id." | ".$value->name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(20, $heigh,number_format($value->gross,2),  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(20, $heigh,number_format((float)$value->discount+(float)$value->additional_deduct,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(20, $heigh,number_format($value->additional_add,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(20, $heigh,number_format($value->net_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(20, $heigh,$value->description, 1, 'L', 0, 1, '', '', true, 0, false, true, 0);
			
			$c_gross+=$value->gross;
			$c_dis+=(float)$value->discount+(float)$value->additional_deduct;
			$c_add+=$value->additional_add;
			$c_net+=$value->net_amount;

		}

		$this->pdf->SetFont('helvetica','B',8);
		$this->pdf->SetX(5);
		$this->pdf->Cell(15, 6,"", '0', 0, 'L', 0);
		$this->pdf->Cell(15, 6,"", '0', 0, 'L', 0);
		$this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
		$this->pdf->Cell(50, 6,"Total ", '0', 0, 'R', 0);
		$this->pdf->Cell(20, 6,number_format($c_gross,2), 'TB', 0, 'R', 0);
		$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
		$this->pdf->Cell(19, 6,number_format((float)$c_dis,2), 'TB', 0, 'R', 0);
		$this->pdf->Cell(1, 6,"", '0', 0, 'R', 0);
		$this->pdf->Cell(20, 6,number_format((float)$c_add,2), 'TB', 0, 'R', 0);
		$this->pdf->Cell(20, 6,number_format($c_net,2), 'TB', 0, 'R', 0);
		$this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);

	}










}
$this->pdf->Output("Credit Sale Summary".date('Y-m-d').".pdf", 'I');

?>