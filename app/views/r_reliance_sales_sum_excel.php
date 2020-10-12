<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->excel->setActiveSheetIndex(0);

foreach($branch as $ress){
	$this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach ($category as $value) {
	$cat_code = $value->code;
	$cat_name = $value->description;
}

$this->excel->setHeading("RELIANCE SALES SUMMARY");

$sno=$this->excel->NextRowNum();

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dfrom);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dto);

/*if($sales_category!= ""){
	$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"category");
	$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$cat_code ." | ".$cat_name);
}*/

$eno=$this->excel->LastRowNum();
$this->excel->SetFont('A'.$sno.":".'A'.$eno,"B",12,"","");
$this->excel->SetBlank();

$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"No");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"DO.No");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Customer");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Gross Amount");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Discount");  
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Net Amount");  

$this->excel->SetBorders('A'.$r.":".'G'.$r);
$this->excel->SetFont('A'.$r.":".'G'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();

foreach($purchase as $row){

	$this->excel->getActiveSheet()->setCellValue('A'.$key, $row->nno);
	$this->excel->getActiveSheet()->setCellValue('B'.$key, $row->ddate);
	$this->excel->getActiveSheet()->setCellValue('C'.$key, $row->do_no);
	$this->excel->getActiveSheet()->setCellValue('D'.$key, $row->name);
	$this->excel->getActiveSheet()->setCellValue('E'.$key, $row->gross);  
	$this->excel->getActiveSheet()->setCellValue('F'.$key, $row->discount);
	$this->excel->getActiveSheet()->setCellValue('G'.$key, $row->net_amount);

	$tot_disc+=(float)$row->discount;
	$tot_gross+=(float)$row->gross;
	$tot_net+=(float)$row->net_amount;


	$this->excel->SetBorders('A'.$key.":".'G'.$key);
	$key++;
}

$this->excel->SetFont('D'.$key.":".'G'.$key,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('D'.$key,"Total");
$this->excel->getActiveSheet()->setCellValue('E'.$key,$tot_gross);
$this->excel->getActiveSheet()->setCellValue('F'.$key,$tot_disc);  
$this->excel->getActiveSheet()->setCellValue('G'.$key,$tot_net);

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));



























