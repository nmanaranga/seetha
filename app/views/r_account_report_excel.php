<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->excel->setActiveSheetIndex(0);

foreach($branch as $ress){
	$this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach ($category as $value) {
	$cat_code = $value->code;
	$cat_name = $value->description;
}

$this->excel->setHeading("Account's Details Report");

$sno=$this->excel->NextRowNum();

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dfrom);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dto);

if($acc_code!= ""){
	$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Account Code ");
	$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$acc_code ." | ".$account_det);
}

$eno=$this->excel->LastRowNum();
$this->excel->SetFont('A'.$sno.":".'A'.$eno,"B",12,"","");
$this->excel->SetBlank();

$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"No");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Transaction");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Description");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Dr Amount");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Cr Amount");  
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Balance");  

$this->excel->SetBorders('A'.$r.":".'G'.$r);
$this->excel->SetFont('A'.$r.":".'G'.$r,"BC",12,"","");

$key1=$this->excel->NextRowNum();


foreach ($op as $key){
	$op = (float)$key->op;
	$cr = (float)$key->cr;
	$dr = (float)$key->dr;
}

$total=(float)$op;
$cr_toal=$cr;
$dr_toal=$dr;
$period_cr=(float)0;
$period_dr=(float)0;

$this->excel->SetFont('D'.$key1.":".'G'.$key1,"B",12,"","");

$this->excel->getActiveSheet()->setCellValue('A'.$key1, '');
$this->excel->getActiveSheet()->setCellValue('B'.$key1, '');
$this->excel->getActiveSheet()->setCellValue('C'.$key1, '');
$this->excel->getActiveSheet()->setCellValue('D'.$key1, 'Begining Balance');
$this->excel->getActiveSheet()->setCellValue('E'.$key1, $dr);  
$this->excel->getActiveSheet()->setCellValue('F'.$key1, $cr);
$this->excel->getActiveSheet()->setCellValue('G'.$key1, $op);


$key2=$this->excel->NextRowNum();

foreach($all_acc_det as $row1){
	if($row1->dr_amount==0){
		$total=$total-(float)$row1->cr_amount;
	}else if($row1->cr_amount==0){
		$total=$total+(float)$row1->dr_amount;
	}

	$cr_toal=$cr_toal+(float)$row1->cr_amount;
	$dr_toal=$dr_toal+(float)$row1->dr_amount;

	$period_cr+=(float)$row1->cr_amount;
	$period_dr+=(float)$row1->dr_amount;

	$this->excel->getActiveSheet()->setCellValue('A'.$key2, $row1->ddate);
	$this->excel->getActiveSheet()->setCellValue('B'.$key2, $row1->tno);
	$this->excel->getActiveSheet()->setCellValue('C'.$key2, ucfirst(strtolower($row1->det)));
	$this->excel->getActiveSheet()->setCellValue('D'.$key2, $row1->description);
	$this->excel->getActiveSheet()->setCellValue('E'.$key2, $row1->dr_amount);  
	$this->excel->getActiveSheet()->setCellValue('F'.$key2, $row1->cr_amount);
	$this->excel->getActiveSheet()->setCellValue('G'.$key2, $total);

	$this->excel->SetBorders('A'.$key2.":".'G'.$key2);
	$key2++;
}

$key3=$this->excel->NextRowNum();
$this->excel->SetFont('D'.$key3.":".'G'.$key3,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$key3, '');
$this->excel->getActiveSheet()->setCellValue('B'.$key3, '');
$this->excel->getActiveSheet()->setCellValue('C'.$key3, '');
$this->excel->getActiveSheet()->setCellValue('D'.$key3, 'Period Balance');
$this->excel->getActiveSheet()->setCellValue('E'.$key3, $period_dr);  
$this->excel->getActiveSheet()->setCellValue('F'.$key3, $period_cr);
$this->excel->getActiveSheet()->setCellValue('G'.$key3, '');

$key4=$this->excel->NextRowNum();
$this->excel->SetFont('D'.$key4.":".'G'.$key4,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$key4, '');
$this->excel->getActiveSheet()->setCellValue('B'.$key4, '');
$this->excel->getActiveSheet()->setCellValue('C'.$key4, '');
$this->excel->getActiveSheet()->setCellValue('D'.$key4, 'Ending Balance');
$this->excel->getActiveSheet()->setCellValue('E'.$key4, $dr_toal);  
$this->excel->getActiveSheet()->setCellValue('F'.$key4, $cr_toal);
$this->excel->getActiveSheet()->setCellValue('G'.$key4, $total);

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));



























