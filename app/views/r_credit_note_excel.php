<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->excel->setActiveSheetIndex(0);

foreach($branch as $ress){
	$this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach ($category as $value) {
	$cat_code = $value->code;
	$cat_name = $value->description;
}



$this->excel->setHeading("CREDIT NOTE DETAILS REPORT");

$sno=$this->excel->NextRowNum();

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dfrom);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dto);

$this->excel->SetBlank();

$eno=$this->excel->LastRowNum();
$this->excel->SetFont('A'.$sno.":".'A'.$eno,"B",12,"","");
$this->excel->SetBlank();

$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"No");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Acc. Code");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Acc. Description");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Oposit. Account");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Description");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Amount");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"Status");


$this->excel->SetBorders('A'.$r.":".'H'.$r);
$this->excel->SetFont('A'.$r.":".'H'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$tot=0;
foreach($credit_note as $row){
	$this->excel->getActiveSheet()->setCellValue('A'.$key, $row->nno);
	$this->excel->getActiveSheet()->setCellValue('B'.$key, $row->ddate);
	$this->excel->getActiveSheet()->setCellValue('C'.$key, $row->cus_code);
	$this->excel->getActiveSheet()->setCellValue('D'.$key, $row->customer);
	$this->excel->getActiveSheet()->setCellValue('E'.$key, $row->acc_code);
	$this->excel->getActiveSheet()->setCellValue('F'.$key, $row->cr_account);
	$this->excel->getActiveSheet()->setCellValue('G'.$key, $row->amount);
	if((float)$row->amount > (float)$row->balance){
        $this->excel->getActiveSheet()->setCellValue('H'.$key, 'SETTLED');
    }else{
        $this->excel->getActiveSheet()->setCellValue('H'.$key, 'PENDING');       
    }
	$this->excel->SetBorders('A'.$key.":".'H'.$key);
	$key++;
	$tot+=$row->amount;
}


$this->excel->SetBlank();
$r2=$this->excel->NextRowNum();
$this->excel->SetFont('A'.$r2.":".'H'.$r2,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('B'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('C'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('D'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('E'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('F'.$r2, "Total");
$this->excel->getActiveSheet()->setCellValue('G'.$r2, $tot);
$this->excel->getActiveSheet()->setCellValue('H'.$r2, "");

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));
