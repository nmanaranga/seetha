<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->excel->setActiveSheetIndex(0);
foreach($branch as $ress){
 $this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
$this->excel->setHeading("SUPPLIER BALANCES");

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dfrom);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dto);
$eno=$this->excel->LastRowNum();

$this->excel->SetFont('A'.$eno.":".'A'.$eno,"B",12,"","");

$this->excel->SetBlank();
$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"ID");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Name");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Tel No");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Contact Person");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Balance");
$this->excel->SetBorders('A'.$r.":".'E'.$r);
$this->excel->SetFont('A'.$r.":".'E'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("C,D");

$supp='default';
$bal=(int)0;

foreach($item_det as $row)
{

    $this->excel->getActiveSheet()->setCellValue('A'.$key, $row->code);
    $this->excel->getActiveSheet()->setCellValue('B'.$key, $row->name);
    $this->excel->getActiveSheet()->setCellValue('C'.$key, $row->tp);
    $this->excel->getActiveSheet()->setCellValue('D'.$key, $row->contact_name);
    $this->excel->getActiveSheet()->setCellValue('E'.$key, $row->balance);
    $this->excel->SetBorders('A'.$key.":".'E'.$key);
    $key++;

}

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));

