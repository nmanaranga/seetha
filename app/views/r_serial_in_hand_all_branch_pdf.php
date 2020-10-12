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


$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'BU',10);
$this->pdf->Cell(180, 1,"Serial In Hand  All Branch  ",0,false, 'C', 0, '', 0, false, 'M', 'M');
$this->pdf->Ln();

$this->pdf->SetFont('helvetica', 'B', 8);
$this->pdf->Cell(20, 1,"Item", '0', 0, 'L', 0);
$this->pdf->SetFont('helvetica', '', 8);
$this->pdf->Cell(20, 1,": ".$item." - ".$item_des, '0', 0, 'L', 0);

$this->pdf->Ln();


$bc="DEFAULT";
foreach($serial_det as $row){
  $x=0;
  if($bc=='DEFAULT' || $bc!=$row->bc){
   $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
   $this->pdf->SetFont('helvetica','B',8);
   $this->pdf->Ln(8);
   $this->pdf->Cell(40, 6,$row->name,'0', 0, 'L', 0);
   $exp = explode(",", $row->serial_no);
   
   foreach ($exp as  $value){
    if($x==5){
      $x=0;
      $this->pdf->Ln();
      $this->pdf->SetX(55);
      $this->pdf->Cell(30, 6,$value,'1', 0, 'R', 0);
      $x++;
    }else{
      $this->pdf->Cell(30, 6,$value,'1', 0, 'R', 0);
      $x++;
    }
  }

}else{
  $exp = explode(",", $row->serial_no);

  foreach ($exp as  $value){
    if($x==5){
      $x=0;
      $this->pdf->Ln();
      $this->pdf->SetX(55);
      $this->pdf->Cell(30, 6,$value,'1', 0, 'R', 0);
      $x++;
    }else{
      $this->pdf->Cell(30, 6,$value,'1', 0, 'R', 0);
      $x++;
    }
  }

}
$bc = $row->bc;
$x++;
}

/*
if($last_code != $row->code){

    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    $this->pdf->SetFont('helvetica','B',8);
    $exp = explode(",", $row->serial_no);
   
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
  $last_code = $row->code;*/
  $this->pdf->Output("Serial In Hand Report".date('Y-m-d').".pdf", 'I');

  ?>
