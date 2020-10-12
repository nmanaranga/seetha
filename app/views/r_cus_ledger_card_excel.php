<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->excel->setActiveSheetIndex(0);

foreach($branch as $ress){
	$this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach ($category as $value) {
	$cat_code = $value->code;
	$cat_name = $value->description;
}

$this->excel->setHeading("CUSTOMER LEDGER CARD REPORT");

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
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Ref No");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Type");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Description");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Dr Amount");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Cr Amount");  
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Balance");  

$this->excel->SetBorders('A'.$r.":".'G'.$r);
$this->excel->SetFont('A'.$r.":".'G'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$balance =(float)0;
foreach($cus_det as $row){
	$balance +=(float)$row->dr_amount;
    $balance -=(float)$row->cr_amount; 
	$this->excel->getActiveSheet()->setCellValue('A'.$key, $row->ddate);
	$this->excel->getActiveSheet()->setCellValue('B'.$key, $row->ref_no);
	$this->excel->getActiveSheet()->setCellValue('C'.$key, ucwords(strtolower($row->trans_type)));
	$this->excel->getActiveSheet()->setCellValue('D'.$key, ucfirst(strtolower($row->description)));
	$this->excel->getActiveSheet()->setCellValue('E'.$key, $row->dr_amount);  
	$this->excel->getActiveSheet()->setCellValue('F'.$key, $row->cr_amount);
	$this->excel->getActiveSheet()->setCellValue('G'.$key, $balance);

	$this->excel->SetBorders('A'.$key.":".'G'.$key);
	$key++;
}

$this->excel->SetFont('D'.$key.":".'G'.$key,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('D'.$key,"");
$this->excel->getActiveSheet()->setCellValue('E'.$key,"");
$this->excel->getActiveSheet()->setCellValue('F'.$key,"Balance");  
$this->excel->getActiveSheet()->setCellValue('G'.$key,$balance);

$this->excel->SetBlank();
$r2=$this->excel->NextRowNum();
$this->excel->SetFont('D'.$r2.":".'G'.$r2,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$r2,"");
$this->excel->getActiveSheet()->setCellValue('B'.$r2,"");
$this->excel->getActiveSheet()->setCellValue('C'.$r2,"");
$this->excel->getActiveSheet()->setCellValue('D'.$r2,"");
$this->excel->getActiveSheet()->setCellValue('E'.$r2,"LESS - PD Cheques Given");
$this->excel->getActiveSheet()->setCellValue('F'.$r2,"Cheque No");  
$this->excel->getActiveSheet()->setCellValue('G'.$r2,"Amount"); 


$key2=$this->excel->NextRowNum();
$pd_tot=0;
foreach($pd_chq as $row){
	$this->excel->getActiveSheet()->setCellValue('A'.$key2, "");
	$this->excel->getActiveSheet()->setCellValue('B'.$key2, "");
	$this->excel->getActiveSheet()->setCellValue('C'.$key2, "");
	$this->excel->getActiveSheet()->setCellValue('D'.$key2, "");
	$this->excel->getActiveSheet()->setCellValue('E'.$key2, "");  
	$this->excel->getActiveSheet()->setCellValue('F'.$key2, (string)$row->cheque_no);
	$this->excel->getActiveSheet()->setCellValue('G'.$key2, $row->amount);

	$this->excel->SetBorders('A'.$key2.":".'G'.$key);
	$key2++;
	$pd_tot+=(float)$row->amount;
}


$this->excel->SetBlank();
$r3=$this->excel->NextRowNum();
$this->excel->SetFont('D'.$r3.":".'G'.$r3,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$r3,"");
$this->excel->getActiveSheet()->setCellValue('B'.$r3,"");
$this->excel->getActiveSheet()->setCellValue('C'.$r3,"");
$this->excel->getActiveSheet()->setCellValue('D'.$r3,"");
$this->excel->getActiveSheet()->setCellValue('E'.$r3,"");
$this->excel->getActiveSheet()->setCellValue('F'.$r3,"PD Cheques Total ");  
$this->excel->getActiveSheet()->setCellValue('G'.$r3,$pd_tot); 

$r4=$this->excel->NextRowNum();
$this->excel->SetFont('D'.$r4.":".'G'.$r4,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$r4,"");
$this->excel->getActiveSheet()->setCellValue('B'.$r4,"");
$this->excel->getActiveSheet()->setCellValue('C'.$r4,"");
$this->excel->getActiveSheet()->setCellValue('D'.$r4,"");
$this->excel->getActiveSheet()->setCellValue('E'.$r4,"");
$this->excel->getActiveSheet()->setCellValue('F'.$r4,"Balance After PD Cheques Given ");  
$this->excel->getActiveSheet()->setCellValue('G'.$r4,$balance-$pd_tot); 

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));



























