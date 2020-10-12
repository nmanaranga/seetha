<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->excel->setActiveSheetIndex(0);

foreach($branch as $ress){
	$this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach ($category as $value) {
	$cat_code = $value->code;
	$cat_name = $value->description;
}

$this->excel->setHeading("CUSTOMER (DEDITOR) BALANCE");

$sno=$this->excel->NextRowNum();

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"As at Date");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$to);

$eno=$this->excel->LastRowNum();
$this->excel->SetFont('A'.$sno.":".'A'.$eno,"B",12,"","");
$this->excel->SetBlank();

$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Code");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"NIC");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Name");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Tel No");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Balance");
 

$this->excel->SetBorders('A'.$r.":".'E'.$r);
$this->excel->SetFont('A'.$r.":".'E'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$bal=0;
foreach($item_det as $row){
	$this->excel->getActiveSheet()->setCellValue('A'.$key, $row->code);
	$this->excel->getActiveSheet()->setCellValue('B'.$key, $row->nic);
	$this->excel->getActiveSheet()->setCellValue('C'.$key, $row->name);
	$this->excel->getActiveSheet()->setCellValue('D'.$key, $row->tp);  
	$this->excel->getActiveSheet()->setCellValue('E'.$key, $row->balance);
	$this->excel->SetBorders('A'.$key.":".'E'.$key);
	$bal+=$row->balance;
	$key++;
}
$this->excel->SetFont('D'.$key.":".'E'.$key,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('D'.$key,"Balance");  
$this->excel->getActiveSheet()->setCellValue('E'.$key,$bal);
$this->excel->SetBlank();

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));



























