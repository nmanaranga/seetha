<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//$this->pdf->setPrintHeader($header,false,$duration);
//$this->pdf->setPrintHeader(true,$type);
$this->pdf->setPrintFooter(true);

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,$page); 

$branch_name="";
foreach($branch as $ress){
  $branch_name=$ress->name;
  $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach($clus as $cl){
  $claster_name=$cl->description;
  $cl_code=$cl->code;
}
foreach($bran as $b){
  $b_name=$b->name;
  $b_code=$b->bc;
}
foreach($str as $s){
  $s_name=$s->description;
  $s_code=$s->code;
}
foreach($dp as $d){
  $d_name=$d->description;
  $d_code=$d->code;
}
foreach($itm as $it){
  $i_name=$it->description;
  $i_code=$it->code;
}



$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'BU',10);
$this->pdf->Cell(180, 1,"Stock In Hand Serial Wise Report  ",0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 8);

$this->pdf->Cell(20, 1, "Cluster", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(80, 1, ": ".$cl_code." - ".$claster_name, '0', 0, 'L', 0);

$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1, "Branch", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(80, 1,": ".$b_code." - ".$b_name, '0', 0, 'L', 0);



$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1, "Store", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(80, 1,": ".$s_code." - ".$s_name, '0', 0, 'L', 0);
$this->pdf->Ln();
$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1,"Item", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(20, 1,": ".$i_code." - ".$i_name, '0', 0, 'L', 0);

$this->pdf->Ln();
$this->pdf->Ln();

$this->pdf->SetFont('helvetica','B',9);
$this->pdf->Cell(30, 6," Item Code", '1', 0, 'L', 0);
$this->pdf->Cell(90, 6," Item Name", '1', 0, 'L', 0);
$this->pdf->Cell(35, 6," Item Model", '1', 0, 'L', 0);
$this->pdf->Cell(25, 6," Quantity  ", '1', 0, 'R', 0);
$this->pdf->Ln();
$this->pdf->Ln();

$last_code="";
foreach($serial_det as $row){
  $x=0;
  if($last_code != $row->code){

    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    $this->pdf->SetFont('helvetica','B',8);
    $exp = explode(",", $row->serial_no);
    $this->pdf->HaveMorePages(10);
    $this->pdf->Cell(30, 6,$row->code, '1', 0, 'L', 0);
    $this->pdf->Cell(90, 6,$row->description, '1', 0, 'L', 0);
    $this->pdf->Cell(35, 6,$row->model, '1', 0, 'L', 0);
    $this->pdf->Cell(25, 6,$row->qty, '1', 0, 'R', 0);
    $this->pdf->Ln();

    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 3, 'color' => array(0, 0, 0)));
    $this->pdf->SetFont('helvetica','',8);

    foreach ($exp as  $value){
      if($x==6){
        $x=0;
        $this->pdf->Ln();
        $this->pdf->Cell(30, 6,$value,'1', 0, 'R', 0);
        $x++;
      }else{
        $this->pdf->Cell(30, 6,$value,'1', 0, 'R', 0);
        $x++;
      }
    }
    $this->pdf->Ln();
    $this->pdf->Ln();

  }
  $last_code = $row->code;
}

$this->pdf->Output("Serial In Hand Report".date('Y-m-d').".pdf", 'I');

?>
