<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->excel->setActiveSheetIndex(0);

foreach($branch as $ress){
	$this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach ($category as $value) {
	$cat_code = $value->code;
	$cat_name = $value->description;
}



$this->excel->setHeading("INVOICE WISE TOTAL SALES REPORT");

$sno=$this->excel->NextRowNum();

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$from);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$to);

$this->excel->SetBlank();

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Cluster");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$cluster." - ".$clus);
$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Branch");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$branchs." - ".$bran);

$eno=$this->excel->LastRowNum();
$this->excel->SetFont('A'.$sno.":".'A'.$eno,"B",12,"","");
$this->excel->SetBlank();

$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Customer");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Type");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"No");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Gross Price");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Discount");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Net Price");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"Sales Return");
$this->excel->getActiveSheet()->setCellValue('I'.$r,"Net Amount");

$this->excel->SetBorders('A'.$r.":".'I'.$r);
$this->excel->SetFont('A'.$r.":".'I'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$c_gross=$c_dis=$c_add=$c_net=$cr_gross=$cr_dis=$cr_add=$cr_net=$return_net=$tot=$add_tot=0;
foreach($r_data as $row){
	if($row->t_rate >0){
		$vat ="YES";
	}else{
		$vat ="NO";
	}

	

	if($row->cus_name!=""){
		$cus=" (".$row->cus_name ." - ".$row->cus_address.")";
	}

	$this->excel->getActiveSheet()->setCellValue('A'.$key, $row->ddate);
	$this->excel->getActiveSheet()->setCellValue('B'.$key, $row->name." - ".$row->address.$cus);
	$this->excel->getActiveSheet()->setCellValue('C'.$key, $row->s_type);
	$this->excel->getActiveSheet()->setCellValue('D'.$key, $row->non);
	$this->excel->getActiveSheet()->setCellValue('E'.$key, $row->purchase_price);
	$this->excel->getActiveSheet()->setCellValue('F'.$key, $row->discount);
	$this->excel->getActiveSheet()->setCellValue('G'.$key, $row->net_amount);
	$this->excel->getActiveSheet()->setCellValue('H'.$key, $row->ret_price);
	$this->excel->getActiveSheet()->setCellValue('I'.$key, $row->net_amount-$row->ret_price);   
	$this->excel->SetBorders('A'.$key.":".'I'.$key);
	$key++;
	$c_gross+=$row->purchase_price;
	$c_dis+=$row->discount;
	$c_net+=$row->net_amount;
	//$c_ret+=$row->ret_price;
	//$c_net_ret+=$row->net_amount-$row->ret_price;
}

foreach ($add_data as $row) {
	if($row->is_add=='1'){
		$tot_add+=(float)$row->amount;
	}else if($row->is_add=='0'){
		$tot_ded+=(float)$row->amount;
	}

	if($row->is_add=='3'){
		$c_ret+=(float)$row->amount;
	}
}

$tot_additonal=$tot_add-$tot_ded;
$c_net_ret=$c_net-$c_ret+$tot_additonal;

$this->excel->SetBlank();
$r2=$this->excel->NextRowNum();
$this->excel->SetFont('A'.$r2.":".'I'.$r2,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('B'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('C'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('D'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('E'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('F'.$r2, "");  
$this->excel->getActiveSheet()->setCellValue('G'.$r2, "");  
$this->excel->getActiveSheet()->setCellValue('H'.$r2, "Additonal");
$this->excel->getActiveSheet()->setCellValue('I'.$r2, $tot_additonal); 

$this->excel->SetBlank();
$r2=$this->excel->NextRowNum();
$this->excel->SetFont('A'.$r2.":".'I'.$r2,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('B'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('C'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('D'.$r2, "Toatal");
$this->excel->getActiveSheet()->setCellValue('E'.$r2, $c_gross);
$this->excel->getActiveSheet()->setCellValue('F'.$r2, $c_dis);
$this->excel->getActiveSheet()->setCellValue('G'.$r2, $c_net);  
$this->excel->getActiveSheet()->setCellValue('H'.$r2, $c_ret);
$this->excel->getActiveSheet()->setCellValue('I'.$r2, $c_net_ret+tot_additonal); 


$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));
