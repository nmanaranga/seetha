<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$this->excel->setActiveSheetIndex(0);

foreach($branch as $ress){
	$this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach ($category as $value) {
	$cat_code = $value->code;
	$cat_name = $value->description;
}

foreach($clus as $cl){
	$cl_des= $cl->description;
}

foreach($bran as $bc){
	$bc_des= $bc->name;
}

$this->excel->setHeading("TOTAL SALES REPORT");

$sno=$this->excel->NextRowNum();

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$from);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$to);

$this->excel->SetBlank();

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Cluster");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$cluster." - ".$cl_des);
$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Branch");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$branchs." - ".$bc_des);

$eno=$this->excel->LastRowNum();
$this->excel->SetFont('A'.$sno.":".'A'.$eno,"B",12,"","");
$this->excel->SetBlank();

$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Cash Gross");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Cash Discount");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Cash Additional");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Cash Net");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Credit Gross");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Credit Discount");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"Credit Additional");
$this->excel->getActiveSheet()->setCellValue('I'.$r,"Credit Net");
$this->excel->getActiveSheet()->setCellValue('J'.$r,"Sales Return");
$this->excel->getActiveSheet()->setCellValue('K'.$r,"SRN Discount in Previous Month");
$this->excel->getActiveSheet()->setCellValue('L'.$r,"Less-Additonal Discoount");
$this->excel->getActiveSheet()->setCellValue('M'.$r,"Total");

$this->excel->SetBorders('A'.$r.":".'M'.$r);
$this->excel->SetFont('A'.$r.":".'M'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$c_gross=$c_dis=$c_add=$c_net=$cr_gross=$cr_dis=$cr_add=$cr_net=$return_net=$tot=0;
foreach($r_data as $row){
	$this->excel->getActiveSheet()->setCellValue('A'.$key, $row->ddate);
	$this->excel->getActiveSheet()->setCellValue('B'.$key, $row->cash_gross);
	$this->excel->getActiveSheet()->setCellValue('C'.$key, $row->cash_dis);
	$this->excel->getActiveSheet()->setCellValue('D'.$key, $row->cash_add);
	$this->excel->getActiveSheet()->setCellValue('E'.$key, $row->cash_net);
	$this->excel->getActiveSheet()->setCellValue('F'.$key, $row->credit_gross);
	$this->excel->getActiveSheet()->setCellValue('G'.$key, $row->credit_dis);
	$this->excel->getActiveSheet()->setCellValue('H'.$key, $row->credit_add);
	$this->excel->getActiveSheet()->setCellValue('I'.$key, $row->credit_net);  
	$this->excel->getActiveSheet()->setCellValue('J'.$key, $row->return_net);
	$this->excel->getActiveSheet()->setCellValue('K'.$key, $row->ret_dis_lm);
	$this->excel->getActiveSheet()->setCellValue('L'.$key, $row->tot_additional);
	$this->excel->getActiveSheet()->setCellValue('M'.$key, $row->total);
	$this->excel->SetBorders('A'.$key.":".'M'.$key);
	$key++;
	$c_gross+=$row->cash_gross;
	$c_dis+=$row->cash_dis;
	$c_add+=$row->cash_add;
	$c_net+=$row->cash_net;
	$cr_gross+=$row->credit_gross;
	$cr_dis+=$row->credit_dis;
	$cr_add+=$row->credit_add;
	$cr_net+=$row->credit_net;
	$return_net+=$row->return_net;
	$ret_dis_lm+=$row->ret_dis_lm;
	$tot_additional+=$row->tot_additional;
	$tot+=$row->total;
}


$this->excel->SetBlank();
$r2=$this->excel->NextRowNum();
$this->excel->SetFont('A'.$r2.":".'M'.$r2,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$r2, "Total");
$this->excel->getActiveSheet()->setCellValue('B'.$r2, $c_gross);
$this->excel->getActiveSheet()->setCellValue('C'.$r2, $c_dis);
$this->excel->getActiveSheet()->setCellValue('D'.$r2, $c_add);
$this->excel->getActiveSheet()->setCellValue('E'.$r2, $c_net);
$this->excel->getActiveSheet()->setCellValue('F'.$r2, $cr_gross);
$this->excel->getActiveSheet()->setCellValue('G'.$r2, $cr_dis);
$this->excel->getActiveSheet()->setCellValue('H'.$r2, $cr_add);
$this->excel->getActiveSheet()->setCellValue('I'.$r2, $cr_net);  
$this->excel->getActiveSheet()->setCellValue('J'.$r2, $return_net);
$this->excel->getActiveSheet()->setCellValue('K'.$r2, $ret_dis_lm);
$this->excel->getActiveSheet()->setCellValue('L'.$r2, $tot_additional);
$this->excel->getActiveSheet()->setCellValue('M'.$r2, $tot);

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));
