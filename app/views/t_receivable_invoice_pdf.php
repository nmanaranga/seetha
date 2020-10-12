<?php

// echo '<pre>'.print_r($r_detail,true).'</pre>';
//  		exit;
//   echo $data;
//         echo '<pre>'.print_r($b,true).'</pre>';
//  		exit;
//                
//                  var_dump("r_detail");
//        exit();


error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header, $type, $duration);
$this->pdf->setPrintHeader(true, $type);
$this->pdf->setPrintFooter(true);
//$this->pdf->setPrintHeader(true);

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation, $page);   // L or P amd page type A4 or A3

foreach ($branch as $ress) {
    $this->pdf->headerSet3($ress->name, $ress->address, $ress->tp, $ress->fax, $ress->email);
}

foreach($session as $ses){
    $invoice_no=$session[0].$session[1].$session[2];
}

$sup_name;
$sup_address;
$sup_tp;
$sup_email;
$ship_branch_name;
$ship_branch_add;
$ship_branch_tp;
$ship_branch_email;


foreach ($suppliers as $sup) {
    $sup_name = $sup->name;
    $sup_address = $sup->address1 . " " . $sup->address2 . " " . $sup->address3;
    $sup_tp = $sup->tp;
    $sup_email = $sup->email;
}

foreach ($ship_branch as $sb) {
    $ship_branch_name = $sb->name;
    $ship_branch_add = $sb->address1 . " " . $sb->address2 . " " . $sb->address3;
    $ship_branch_tp = $sb->tp;
    $ship_branch_email = $sb->email;
}






if (isset($jtype_desc)) {
    $description = $jtype_desc;
}




$no = "1";


$this->pdf->setY(20);
$this->pdf->SetFont('helvetica', 'BU', 10);
$this->pdf->Ln();
$this->pdf->Cell(0, 5, ' Receivable Invoice Amount', 0, false, 'C', 0, '', 0, false, '0', '0');

$this->pdf->SetFont('helvetica', '', 7);
$this->pdf->setY(25);
$this->pdf->Ln();
foreach ($jrn_en_body as $value) {
    $mcl = $value->mdescrp;
    $mcid = $value->mccode;
    $brnch = $value->bname;
    $brncd = $value->bc;
    $date=$value->date;
    $no=$value->no;
    $ref=$value->no;
}

$this->pdf->Cell(10, 1, 'Cluster :', '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $mcl, '0', 0, 'L', 0);


$this->pdf->Cell(15, 1, 'Cluster ID :', '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, $mcid, '0', 0, 'L', 0);


$this->pdf->setX(120);

$this->pdf->Cell(15, 1, "Date      :", '0', 0, 'L', 0);
$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $date, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->Cell(10, 1, "Branch :", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $brnch, '0', 0, 'L', 0);

$this->pdf->Cell(15, 1, 'Branch ID :', '0', 0, 'L', 0);
$this->pdf->Cell(20, 1, $brncd, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->setX(120);

$this->pdf->Cell(15, 1, "No         :", '0', 0, 'L', 0);
$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $invoice_no, '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->SetY(40);

$this->pdf->SetX(120);
$this->pdf->Cell(15, 1, 'Ref.No   :', '0', 0, 'L', 0);
$this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $value->ref_no, '0', 0, 'L', 0);

$this->pdf->Ln();



$this->pdf->Cell(180, 1, "______________________________________________________________________________________________________________________ ", '0', 0, 'L', 0);
$this->pdf->Ln();
foreach ($opn_bl_head as $value) {
    $this->pdf->Cell(10, 1, 'Cluster :', '0', 0, 'L', 0);
    $this->pdf->Cell(30, 1, $value->name, '0', 0, 'L', 0);
}
foreach ($opn_bl_head as $value) {
    $this->pdf->Cell(15, 1, 'Cluster ID :', '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1, $value->code, '0', 0, 'L', 0);
}

$this->pdf->setX(120);
foreach ($opn_bl_head as $value) {
    $this->pdf->Cell(15, 1, "Date      :", '0', 0, 'L', 0);
    $this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
    $this->pdf->Cell(30, 1, $value->date, '0', 0, 'L', 0);
    $this->pdf->Ln();
}
foreach ($opn_bl_head as $value) {
    $this->pdf->Cell(10, 1, "Branch :", '0', 0, 'L', 0);
    $this->pdf->Cell(30, 1, $value->description, '0', 0, 'L', 0);
}
foreach ($opn_bl_head as $value) {
    $this->pdf->Cell(15, 1, 'Branch ID :', '0', 0, 'L', 0);
    $this->pdf->Cell(20, 1, $value->bc, '0', 0, 'L', 0);
    $this->pdf->Ln();
}
$this->pdf->setX(120);
foreach ($opn_bl_head as $value) {
    $this->pdf->Cell(15, 1, "No         :", '0', 0, 'L', 0);
    $this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
    $this->pdf->Cell(30, 1, $value->no, '0', 0, 'L', 0);
    $this->pdf->Ln();
}
$this->pdf->SetY(45);
foreach ($opn_bl_head as $value) {
    $this->pdf->SetX(120);
    $this->pdf->Cell(15, 1, 'Ref.No   :', '0', 0, 'L', 0);
    $this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
    $this->pdf->Cell(30, 1, $value->ref_no, '0', 0, 'L', 0);
}

$this->pdf->SetY(50);



$this->pdf->SetFont('helvetica', '', 7);
foreach ($jrn_en_body as $value) {
    $rc_type = $value->receivable_type;
    $rc_dc = $value->payable_dec;
    $rc_dc = $value->receivable_account;
    $rc_acc = $value->receivable_account_dec;
    $date = $value->date;
}

$this->pdf->Cell(40, 5, "Receivable Type", '0', 0, 'L', 0);
$this->pdf->Cell(40, 5, $rc_type, '0', 0, 'L', 0);
$this->pdf->Cell(60, 5, $rc_dc, '0', 0, 'L', 0);
$this->pdf->Ln();
$this->pdf->Cell(40, 5, "Receivable Account", '0', 0, 'L', 0);
$this->pdf->Cell(40, 5, $rc_dc, '0', 0, 'L', 0);
$this->pdf->Cell(60, 5, $rc_acc, '0', 0, 'L', 0);
$this->pdf->Ln();
$this->pdf->Cell(40, 5, "Date", '0', 0, 'L', 0);
$this->pdf->Cell(40, 5, $date, '0', 0, 'L', 0);
$this->pdf->Ln();


$x = 1;
$code = "default";
$this->pdf->SetX(10);
$this->pdf->SetFont('helvetica', 'B', 7);
$this->pdf->Cell(40, 8, "Account", '1', 0, 'C', 0);
$this->pdf->Cell(90, 8, "Discription", '1', 0, 'L', 0);
$this->pdf->Cell(40, 8, "Amount", '1', 0, 'L', 0);
$this->pdf->Ln();
$totdr = (float) '';
$totcr = (float) '';
foreach ($jrn_en_body as $value) {
    $x = 1;

    $code = "default";
    $this->pdf->SetX(10);
    $this->pdf->SetFont('helvetica', '', 7);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    $this->pdf->Cell(40, 6, "$value->income_account", '1', 0, 'C', 0);
    $this->pdf->Cell(90, 6, "$value->description", '1', 0, 'L', 0);
    $this->pdf->Cell(40, 6, number_format("$value->amount",2), '1', 0, 'L', 0);
    $this->pdf->Ln();
    $totdr = $totdr + $value->amount;
}
$this->pdf->SetX(100);
$this->pdf->Cell(20, 5, "", '0', 0, 'c', 0);
$this->pdf->Ln();
$this->pdf->SetX(100);
$this->pdf->Cell(40, 5, 'Total', '0', 0, 'C', 0);

$this->pdf->Cell(40, 5, number_format($totdr, 2), '1', 0, 'C', 0);

$this->pdf->Ln();
$this->pdf->SetFont('helvetica', 'B', 10);
foreach ($opn_bl_head as $value) {
    $this->pdf->Cell(20, 1, 'Description ', '0', 0, 'L', 0);
    $this->pdf->Cell(5, 1, '', '0', 0, 'L', 0);
    $this->pdf->Cell(100, 1, $value->description, '1', 0, 'L', 0);
    $this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
}
foreach ($jrn_en_body as $value) {
    $rc_dscp = $value->receivable_desc;
    $rc_narr = $value->narration;
}
$this->pdf->SetFont('helvetica', '', 7);
$this->pdf->Cell(20, 5, "Description", '0', 0, 'L', 0);
$this->pdf->Cell(90, 5, $rc_dscp, '0', 0, 'L', 0);
$this->pdf->Ln();
$this->pdf->Cell(20, 5, "Narration", '0', 0, 'L', 0);
$this->pdf->Cell(90, 5, $rc_narr, '0', 0, 'L', 0);
$this->pdf->Ln();
$this->pdf->Ln();
$this->pdf->SetX(125);
$this->pdf->Cell(15, 5,"Signature", '0', 0, 'L', 0);
$this->pdf->Cell(35, 5, "______________________________", '0', 0, 'L', 0);







//                   $op=1;
//                   foreach($det as $row){
//                   $this->pdf->GetY();
//                   $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
// $this->pdf->SetFont('helvetica','',6);
// $this->pdf->MultiCell(10, 6, $op, $border=1, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()-5), $y=($this->pdf->GetY()), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
//             $this->pdf->MultiCell(25, 6, $row->code, $border=1, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
//             $this->pdf->MultiCell(40, 6, $row->description, $border=1, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
//             $this->pdf->MultiCell(15, 6, $row->model, $border=1, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
//             $this->pdf->MultiCell(10, 6, $row->qty, $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
//             $this->pdf->MultiCell(15, 6, $row->unit_price, $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
//             $this->pdf->MultiCell(15, 6, $row->last_price, $border=1, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
//             $this->pdf->MultiCell(15, 6, $row->sales_price, $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
//             $this->pdf->MultiCell(15, 6, $row->discount, $border=1, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
//             $this->pdf->MultiCell(15, 6, $row->profit, $border=1, $align='C', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
//             $this->pdf->MultiCell(20, 6, $row->amount, $border=1, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
//             $this->pdf->Ln();
//                   $ss="";
// foreach ($serial as $rows){
// 	if($row->code==$rows->item)
// 		{
// 		$ss=$rows->serial_no;
// 	}
// }
// if($ss!=""){			
// 	$all_serial="";
// 	foreach ($serial as $rows) {
// 					if($row->code==$rows->item)
// 			{					
// 				$all_serial=$all_serial.$rows->serial_no."   ";
// 			}
// 	}
// 	$this->pdf->GetY();
// 	$this->pdf->SetX(10);
// 	$this->pdf->SetFont('helvetica','',6);
//        $aa = $this->pdf->getNumLines($all_serial, 40);
//        $heigh=3*$aa;
//             	$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
//    	$this->pdf->MultiCell(25, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
// 	$this->pdf->MultiCell(40, $heigh, $all_serial, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
//        $this->pdf->MultiCell(15, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
//        $this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
//        $this->pdf->MultiCell(15, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
//        $this->pdf->MultiCell(15, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
//        $this->pdf->MultiCell(15, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
//        $this->pdf->MultiCell(15, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
//        $this->pdf->MultiCell(15, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
//        $this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
//        $this->pdf->Ln();
// 	}
//                   $op++;
//                   }


$this->pdf->GetY();
$this->pdf->SetFont('helvetica', 'B', 6);




$this->pdf->Output("Purchase Qutoation_" . date('Y-m-d') . ".pdf", 'I');
?>