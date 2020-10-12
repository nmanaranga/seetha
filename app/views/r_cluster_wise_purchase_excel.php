<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->excel->setActiveSheetIndex(0);
foreach($branch as $ress){
 $this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
$this->excel->setHeading("Cluster Wise Purchase Details");


$clustrName='';
foreach ($SelCluster as $r){
  $clustrName=$r->code." - ".$r->description;
}

$supplierName='';
foreach ($SelSupplier as $r){
  $supplierName=$r->code." - ".$r->name;
}


$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dfrom);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dto);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Cluster");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$clustrName);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Vendor");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$supplierName);
$eno=$this->excel->LastRowNum();

$this->excel->SetFont('A'.$eno.":".'A'.$eno,"B",12,"","");

$this->excel->SetBlank();
$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"GRN NO");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Inv No");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Item Cod");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Item Model");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Item Des");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"QTY");  
$this->excel->getActiveSheet()->setCellValue('H'.$r,"Cost Prize");
$this->excel->getActiveSheet()->setCellValue('I'.$r,"Last Prize");
$this->excel->getActiveSheet()->setCellValue('J'.$r,"Sales Prize");
$this->excel->getActiveSheet()->setCellValue('K'.$r,"Total");  
$this->excel->SetBorders('A'.$r.":".'K'.$r);
$this->excel->SetFont('A'.$r.":".'K'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("G,H,I,J,K");
$totamo=$totpaid=$totbal=0;
$code=true;
foreach($purchase as $row){
  $totcost+=$row->cost_prize;
  $totlast+=$row->last_prize;
  $totsales+=$row->sales_prize;
  $tot+=$row->total;

  $this->excel->getActiveSheet()->setCellValue('A'.$key, $row->ddate);
  $this->excel->getActiveSheet()->setCellValue('B'.$key, $row->grn_no);
  $this->excel->getActiveSheet()->setCellValue('C'.$key, $row->invoice_no);
  $this->excel->getActiveSheet()->setCellValue('D'.$key, $row->itm_code);
  $this->excel->getActiveSheet()->setCellValue('E'.$key, $row->itm_model);
  $this->excel->getActiveSheet()->setCellValue('F'.$key, $row->itm_des);
  $this->excel->getActiveSheet()->setCellValue('G'.$key, $row->qty);   
  $this->excel->getActiveSheet()->setCellValue('H'.$key, $row->cost_prize);
  $this->excel->getActiveSheet()->setCellValue('I'.$key, $row->last_prize);
  $this->excel->getActiveSheet()->setCellValue('J'.$key, $row->sales_prize);
  $this->excel->getActiveSheet()->setCellValue('K'.$key, $row->total);   
  $this->excel->SetBorders('A'.$key.":".'K'.$key);

  $key++;

}


$this->excel->SetFont('A'.$key.":".'K'.$key,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$key, "");
$this->excel->getActiveSheet()->setCellValue('B'.$key, "");
$this->excel->getActiveSheet()->setCellValue('C'.$key, "");
$this->excel->getActiveSheet()->setCellValue('D'.$key, "");
$this->excel->getActiveSheet()->setCellValue('E'.$key, "");
$this->excel->getActiveSheet()->setCellValue('F'.$key, "");
$this->excel->getActiveSheet()->setCellValue('G'.$key, "Total");        
$this->excel->getActiveSheet()->setCellValue('H'.$key, $totcost);
$this->excel->getActiveSheet()->setCellValue('I'.$key, $totlast);
$this->excel->getActiveSheet()->setCellValue('J'.$key, $totsales);
$this->excel->getActiveSheet()->setCellValue('K'.$key, $tot);  

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));

