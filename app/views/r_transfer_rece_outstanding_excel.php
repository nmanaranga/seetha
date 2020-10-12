<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->excel->setActiveSheetIndex(0);
foreach($branch as $ress){
   $this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
$this->excel->setHeading("Transfer Outstanding List");

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Account Code :");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$acc_code .' - '.$acc_code_des);
$eno=$this->excel->LastRowNum();

$this->excel->SetFont('A'.$eno.":".'A'.$eno,"B",12,"","");

$this->excel->SetBlank();
$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Cl");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Branch");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Trans");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Amount");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Paid");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Balance");        
$this->excel->SetBorders('A'.$r.":".'G'.$r);
$this->excel->SetFont('A'.$r.":".'G'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("E,F,G");
$totamo=$totpaid=$totbal=0;

foreach($branch_outs as $row){
    $totamo+=$row->amount;
    $totpaid+=$row->amount-$row->balance;
    $totbal+=$row->balance;

    $paid=$row->amount-$row->balance;

    $this->excel->getActiveSheet()->setCellValue('A'.$key, $row->sub_cl);
    $this->excel->getActiveSheet()->setCellValue('B'.$key, $row->name);
    $this->excel->getActiveSheet()->setCellValue('C'.$key, $row->trans_no);
    $this->excel->getActiveSheet()->setCellValue('D'.$key, $row->ddate);
    $this->excel->getActiveSheet()->setCellValue('E'.$key, $row->amount);
    $this->excel->getActiveSheet()->setCellValue('F'.$key, $paid);
    $this->excel->getActiveSheet()->setCellValue('G'.$key, $row->balance);        
    $this->excel->SetBorders('A'.$key.":".'G'.$key);
    $key++;
}


$this->excel->SetFont('D'.$key.":".'G'.$key,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$key, "");
$this->excel->getActiveSheet()->setCellValue('B'.$key, "");
$this->excel->getActiveSheet()->setCellValue('C'.$key, "");
$this->excel->getActiveSheet()->setCellValue('D'.$key, "Total");
$this->excel->getActiveSheet()->setCellValue('E'.$key, $totamo);
$this->excel->getActiveSheet()->setCellValue('F'.$key, $totpaid);
$this->excel->getActiveSheet()->setCellValue('G'.$key, $totbal);        


$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));

