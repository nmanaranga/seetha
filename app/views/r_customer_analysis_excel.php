<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->excel->setActiveSheetIndex(0);

foreach($branch as $ress){
	$this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach ($category as $value) {
	$cat_code = $value->code;
	$cat_name = $value->description;
}

$this->excel->setHeading("Customer Age Analysis");

$sno=$this->excel->NextRowNum();

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"As at Date");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$to);

$eno=$this->excel->LastRowNum();
$this->excel->SetFont('A'.$sno.":".'A'.$eno,"B",12,"","");
$this->excel->SetBlank();

$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Customer ID");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Customer Name");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Balance");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Current <30");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"31 to 60 Days");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"61 to 90 Days");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Over 90 Days");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"unsettle Amo");


$this->excel->SetBorders('A'.$r.":".'H'.$r);
$this->excel->SetFont('A'.$r.":".'H'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$bal=0;
foreach($cus_det as $row){
	$this->excel->getActiveSheet()->setCellValue('A'.$key, $row->nic);
	$this->excel->getActiveSheet()->setCellValue('B'.$key, $row->NAME);
	$this->excel->getActiveSheet()->setCellValue('C'.$key, $row->balance);
	$this->excel->getActiveSheet()->setCellValue('D'.$key, $row->Below30);  
	$this->excel->getActiveSheet()->setCellValue('E'.$key, $row->D30t60);	
	$this->excel->getActiveSheet()->setCellValue('F'.$key, $row->D60t90);
	$this->excel->getActiveSheet()->setCellValue('G'.$key, $row->Over90);  
	$this->excel->getActiveSheet()->setCellValue('H'.$key, $row->unSettle);
	$this->excel->SetBorders('A'.$key.":".'H'.$key);

	$total+=(float)$row->balance;
	$total1+=(float)$row->Below30;
	$total2+=(float)$row->D30t60;
	$total3+=(float)$row->D60t90;
	$total4+=(float)$row->Over90;
	$total5+=(float)$row->unSettle;

	$key++;
}
$this->excel->SetFont('C'.$key.":".'H'.$key,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('B'.$key,"Balance");  
$this->excel->getActiveSheet()->setCellValue('C'.$key,$total);
$this->excel->getActiveSheet()->setCellValue('D'.$key,$total1);
$this->excel->getActiveSheet()->setCellValue('E'.$key,$total2);
$this->excel->getActiveSheet()->setCellValue('F'.$key,$total3);
$this->excel->getActiveSheet()->setCellValue('G'.$key,$total4);
$this->excel->getActiveSheet()->setCellValue('H'.$key,$total5);
$this->excel->SetBlank();

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));



























