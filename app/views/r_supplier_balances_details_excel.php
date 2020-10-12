<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->excel->setActiveSheetIndex(0);

foreach($branch as $ress){
	$this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach ($category as $value) {
	$cat_code = $value->code;
	$cat_name = $value->description;
}

$this->excel->setHeading("Supplier Balance - Details");

$sno=$this->excel->NextRowNum();

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$from);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$to);

if($cus_code !=""){
	$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Customer");
	$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$cus_code." ( ".$cus_name." ) ( ".$cus_nic." ) ");
}

$eno=$this->excel->LastRowNum();
$this->excel->SetFont('A'.$sno.":".'A'.$eno,"B",12,"","");
$this->excel->SetBlank();

$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Cluster");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Branch");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Trans Type");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Trans No");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Description");  
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Amount");  
$this->excel->getActiveSheet()->setCellValue('H'.$r,"Balance");  

$this->excel->SetBorders('A'.$r.":".'H'.$r);
$this->excel->SetFont('A'.$r.":".'H'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$balance =(float)0;
foreach($item_det as $row){
	$amount +=(float)$row->amount;
	$balance +=(float)$row->balance;
	$this->excel->getActiveSheet()->setCellValue('A'.$key, $row->cl);
	$this->excel->getActiveSheet()->setCellValue('B'.$key, $row->bc);
	$this->excel->getActiveSheet()->setCellValue('C'.$key, ucwords(strtolower($row->description)));
	$this->excel->getActiveSheet()->setCellValue('D'.$key, ucfirst(strtolower($row->trans_no)));
	$this->excel->getActiveSheet()->setCellValue('E'.$key, $row->ddate);  
	$this->excel->getActiveSheet()->setCellValue('F'.$key, $row->memo);
	$this->excel->getActiveSheet()->setCellValue('G'.$key, $row->amount);
	$this->excel->getActiveSheet()->setCellValue('H'.$key, $row->balance);

	$this->excel->SetBorders('A'.$key.":".'H'.$key);
	$key++;
}

$this->excel->SetFont('D'.$key.":".'H'.$key,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('D'.$key,"");
$this->excel->getActiveSheet()->setCellValue('E'.$key,"");
$this->excel->getActiveSheet()->setCellValue('F'.$key,"");  
$this->excel->getActiveSheet()->setCellValue('G'.$key,$amount);
$this->excel->getActiveSheet()->setCellValue('H'.$key,$balance);


$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));



























