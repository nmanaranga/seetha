<?php

// echo '<pre>'.print_r($otherdtl,true).'</pre>';
//  		exit;


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

foreach($det as $row){
        $store=$row->store;
        $date=$row->ddate;
        $nno=$row->nno;
        $amount=$row->amount;
        $sname=$row->sname;
}


foreach($session as $ses){
            $invoice_no=$session[0].$session[1].$session[2];
        }

$this->pdf->setY(20);
$this->pdf->SetFont('helvetica', 'BU', 10);
$this->pdf->Ln();
$this->pdf->Cell(0, 5, ' Open Stock ', 0, false, 'C', 0, '', 0, false, '0', '0');

$this->pdf->SetFont('helvetica', '', 7);
$this->pdf->setY(25);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->setY(25);
$this->pdf->Ln();
$this->pdf->Cell(30, 1, 'Store ', '0', 0, 'L', 0);
$this->pdf->Cell(90, 1, $store, '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, "No ", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $invoice_no, '0', 0, 'L', 0);

$this->pdf->Ln();

$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
$this->pdf->Cell(90, 1, '', '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, "Date ", '0', 0, 'L', 0);
$this->pdf->Cell(30, 1, $date, '0', 0, 'L', 0);

$this->pdf->Ln();

$this->pdf->Cell(180, 1, "______________________________________________________________________________________________________________________ ", '0', 0, 'L', 0);
$this->pdf->Ln();

$this->pdf->SetY(50);



$this->pdf->SetY(45);
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->Cell(30, 6,"Code", '1', 0, 'C', 0);
$this->pdf->Cell(80, 6,"Description", '1', 0, 'C', 0);
$this->pdf->Cell(30, 6,"Model", '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,"Qty", '1', 0, 'C', 0);
$this->pdf->Cell(30, 6,"Amount", '1', 0, 'C', 0);


$this->pdf->Ln();

$amount=(float)0;

foreach($det as $row){
$this->pdf->GetY();
$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
$this->pdf->SetFont('helvetica','',7);
$this->pdf->Cell(30, 6,$row->item_code, '1', 0, 'L', 0);
$this->pdf->Cell(80, 6,$row->description, '1', 0, 'L', 0);
$this->pdf->Cell(30, 6,$row->model, '1', 0, 'C', 0);
$this->pdf->Cell(20, 6,$row->qty, '1', 0, 'C', 0);
$this->pdf->Cell(30, 6,number_format($row->cost*$row->qty,2), '1', 0, 'R', 0);
$this->pdf->Ln();

$amount+=(float)$row->cost*$row->qty;

}


            $this->pdf->Ln();
            $this->pdf->Ln();
            $this->pdf->SetFont('helvetica','B',7);
            $this->pdf->Cell(30, 6,'', '0', 0, 'C', 0);
            $this->pdf->Cell(60, 6,'', '0', 0, 'C', 0);
            $this->pdf->Cell(30, 6,'', '0', 0, 'C', 0);
            $this->pdf->Cell(30, 6,'Net Amount ', '0', 0, 'C', 0);
            $this->pdf->Cell(30, 6,number_format($amount,2), '0', 0, 'C', 0);

            
            $this->pdf->Ln();
            $this->pdf->SetFont('helvetica','',7);
            $this->pdf->Cell(30, 1, 'Officer ', '0', 0, 'L', 0);
            $this->pdf->Cell(30, 1, $officer, '0', 0, 'L', 0);
            $this->pdf->Ln();
            $this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
            $this->pdf->Cell(30, 1, '................................', '0', 0, 'L', 0);

         
         
            $this->pdf->Ln();
            $this->pdf->Ln();
            $this->pdf->Ln();
            $this->pdf->Ln();

            $this->pdf->Cell(80, 1, 'Prepaired By', '0', 0, 'L', 0);    
            $this->pdf->Cell(80, 1, 'Authorized By', '0', 0, 'L', 0);
            $this->pdf->Cell(30, 1, "Recivied By ", '0', 0, 'L', 0);
            $this->pdf->Ln();
            $this->pdf->Ln();
            $this->pdf->Ln();
            $this->pdf->Cell(80, 1, '................................', '0', 0, 'L', 0);
            $this->pdf->Cell(80, 1, '................................', '0', 0, 'L', 0);
            $this->pdf->Cell(30, 1, '................................', '0', 0, 'L', 0);
            








$this->pdf->Output("Purchase Qutoation_" . date('Y-m-d') . ".pdf", 'I');
?>