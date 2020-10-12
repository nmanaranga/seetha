<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->excel->setActiveSheetIndex(0);

foreach($branch as $ress){
	$this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach ($category as $value) {
	$cat_code = $value->code;
	$cat_name = $value->description;
}



$this->excel->setHeading("ITEM WISE TOTAL SALES REPORT");

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
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Code");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Description");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Model");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"Gross Price");
$this->excel->getActiveSheet()->setCellValue('I'.$r,"Discount");
$this->excel->getActiveSheet()->setCellValue('J'.$r,"Net Price");
$this->excel->getActiveSheet()->setCellValue('K'.$r,"Sales Return");
$this->excel->getActiveSheet()->setCellValue('L'.$r,"Net Amount");
$this->excel->getActiveSheet()->setCellValue('M'.$r,"VAT");

$this->excel->SetBorders('A'.$r.":".'M'.$r);
$this->excel->SetFont('A'.$r.":".'M'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$c_gross=$c_dis=$c_add=$c_net=$cr_gross=$cr_dis=$cr_add=$cr_net=$return_net=$tot=$add_tot=0;
foreach($r_data as $row){
	if($row->t_rate !='004'){
		$vat ="YES";
	}else{
		$vat ="NO";
	}

	$ret_amo=$row->ret_price*$row->ret_qty;
	$ret_price=$row->ret_price*$row->ret_qty;

	if($row->cus_name!=""){
		$cus=" (".$row->cus_name ." - ".$row->cus_address.")";
	}

	$this->excel->getActiveSheet()->setCellValue('A'.$key, $row->ddate);
	$this->excel->getActiveSheet()->setCellValue('B'.$key, $row->name." - ".$row->address.$cus);
	$this->excel->getActiveSheet()->setCellValue('C'.$key, $row->s_type);
	$this->excel->getActiveSheet()->setCellValue('D'.$key, $row->cl.$row->bc.$row->nno);
	$this->excel->getActiveSheet()->setCellValue('E'.$key, $row->code);
	$this->excel->getActiveSheet()->setCellValue('F'.$key, $row->description);
	$this->excel->getActiveSheet()->setCellValue('G'.$key, $row->model);
	$this->excel->getActiveSheet()->setCellValue('H'.$key, $row->purchase_price);
	$this->excel->getActiveSheet()->setCellValue('I'.$key, $row->discount);
	$this->excel->getActiveSheet()->setCellValue('J'.$key, $row->net_amount);
	$this->excel->getActiveSheet()->setCellValue('K'.$key, $row->ret_price*$row->ret_qty);
	$this->excel->getActiveSheet()->setCellValue('L'.$key, $row->net_amount-$ret_amo);   
	$this->excel->getActiveSheet()->setCellValue('M'.$key, $vat); 
	$this->excel->SetBorders('A'.$key.":".'M'.$key);
	$key++;
	$c_gross+=$row->purchase_price;
	$c_dis+=$row->discount;
	$c_net+=$row->net_amount;
	$c_ret+=$row->ret_price*$row->ret_qty;
	$c_net_ret+=$row->net_amount-($row->ret_price*$row->ret_qty);
}

foreach ($add_data as $row) {
	if($row->is_add=='1'){
		$tot_add+=(float)$row->amount;
	}else{
		$tot_ded+=(float)$row->amount;
	}
}

foreach ($data_ret as $row) {
	
		$tot_return+=(float)$row->amount;
	
}

$tot_additonal=$tot_add-$tot_ded;

$this->excel->SetBlank();
$r2=$this->excel->NextRowNum();
$this->excel->SetFont('A'.$r2.":".'M'.$r2,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('B'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('C'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('D'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('E'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('F'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('G'.$r2, "Total");
$this->excel->getActiveSheet()->setCellValue('H'.$r2, number_format($c_gross,2));
$this->excel->getActiveSheet()->setCellValue('I'.$r2, number_format($c_dis,2));
$this->excel->getActiveSheet()->setCellValue('J'.$r2, number_format($c_net,2));  
$this->excel->getActiveSheet()->setCellValue('K'.$r2, number_format($c_ret,2));
$this->excel->getActiveSheet()->setCellValue('L'.$r2, number_format($c_net,2)); 
$this->excel->getActiveSheet()->setCellValue('M'.$r2, "");

$this->excel->SetBlank();
$r2=$this->excel->NextRowNum();
$this->excel->SetFont('A'.$r2.":".'M'.$r2,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('B'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('C'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('D'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('E'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('F'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('G'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('H'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('I'.$r2, "");  
$this->excel->getActiveSheet()->setCellValue('J'.$r2, "");  
$this->excel->getActiveSheet()->setCellValue('K'.$r2, "Less Additonal");
$this->excel->getActiveSheet()->setCellValue('L'.$r2, number_format($tot_additonal,2)); 
$this->excel->getActiveSheet()->setCellValue('M'.$r2, "");

$r2=$this->excel->NextRowNum();
$this->excel->SetFont('A'.$r2.":".'M'.$r2,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('B'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('C'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('D'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('E'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('F'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('G'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('H'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('I'.$r2, "");  
$this->excel->getActiveSheet()->setCellValue('J'.$r2, "");  
$this->excel->getActiveSheet()->setCellValue('K'.$r2, "Less SRN");
$this->excel->getActiveSheet()->setCellValue('L'.$r2, "-".number_format($tot_return,2)); 
$this->excel->getActiveSheet()->setCellValue('M'.$r2, "");

$r2=$this->excel->NextRowNum();
$this->excel->SetFont('A'.$r2.":".'M'.$r2,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('B'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('C'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('D'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('E'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('F'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('G'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('H'.$r2, "");
$this->excel->getActiveSheet()->setCellValue('I'.$r2, "");  
$this->excel->getActiveSheet()->setCellValue('J'.$r2, "");  
$this->excel->getActiveSheet()->setCellValue('K'.$r2, "Net Sale");
$this->excel->getActiveSheet()->setCellValue('L'.$r2, number_format(($c_net_ret+$tot_additonal)-$tot_return,2)); 
$this->excel->getActiveSheet()->setCellValue('M'.$r2, "");



$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));
