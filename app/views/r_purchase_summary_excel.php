<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->excel->setActiveSheetIndex(0);
foreach($branch as $ress){
   $this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
$this->excel->setHeading("PURCHASE SUMMARY");

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dfrom);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dto);
$eno=$this->excel->LastRowNum();

$this->excel->SetFont('A'.$eno.":".'A'.$eno,"B",12,"","");

$this->excel->SetBlank();
$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"GRN No");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"INV No");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"INV Date");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Supplier");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Gross Amount");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Discount");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"Net Amount");        
$this->excel->getActiveSheet()->setCellValue('I'.$r,"Store");
$this->excel->getActiveSheet()->setCellValue('J'.$r,"Memo");
$this->excel->SetBorders('A'.$r.":".'J'.$r);
$this->excel->SetFont('A'.$r.":".'J'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("F,G,H");
$tot=$tot_discount=$tot_net=0;

foreach($purchase as $row){
    $tot_cost+=$row->gross_amount;
    $tot_discount+=$row->discount;
    $tot_net+=$row->amount;

    $this->excel->getActiveSheet()->setCellValue('A'.$key, $row->nno);
    $this->excel->getActiveSheet()->setCellValue('B'.$key, $row->ddate);
    $this->excel->getActiveSheet()->setCellValue('C'.$key, $row->inv_no);
    $this->excel->getActiveSheet()->setCellValue('D'.$key, $row->inv_date);
    $this->excel->getActiveSheet()->setCellValue('E'.$key, $row->name);
    $this->excel->getActiveSheet()->setCellValue('F'.$key, $row->gross_amount);
    $this->excel->getActiveSheet()->setCellValue('G'.$key, $row->discount);
    $this->excel->getActiveSheet()->setCellValue('H'.$key, $row->amount);        
    $this->excel->getActiveSheet()->setCellValue('I'.$key, $row->description);
    $this->excel->getActiveSheet()->setCellValue('J'.$key, $row->memo);
    $this->excel->SetBorders('A'.$key.":".'J'.$key);
    $key++;
}


$this->excel->SetFont('D'.$key.":".'G'.$key,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$key, "");
$this->excel->getActiveSheet()->setCellValue('B'.$key, "");
$this->excel->getActiveSheet()->setCellValue('C'.$key, "");
$this->excel->getActiveSheet()->setCellValue('D'.$key, "");
$this->excel->getActiveSheet()->setCellValue('E'.$key, "Total");
$this->excel->getActiveSheet()->setCellValue('F'.$key, $tot_cost);
$this->excel->getActiveSheet()->setCellValue('G'.$key, $tot_discount);
$this->excel->getActiveSheet()->setCellValue('H'.$key, $tot_net);        
$this->excel->getActiveSheet()->setCellValue('I'.$key, "");
$this->excel->getActiveSheet()->setCellValue('J'.$key,"");

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));

