<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->excel->setActiveSheetIndex(0);
foreach($branch as $ress){
 $this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
$this->excel->setHeading("CREDIT SALE SUMMARY GROUP");

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dfrom);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dto);
$eno=$this->excel->LastRowNum();

$this->excel->SetFont('A'.$eno.":".'A'.$eno,"B",12,"","");

$this->excel->SetBlank();
$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"No");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Sub No");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"DO No");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Customer");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Gross Amount");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Discount");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"Additional");        
$this->excel->getActiveSheet()->setCellValue('I'.$r,"Net Amount");
$this->excel->getActiveSheet()->setCellValue('J'.$r,"Store");
$this->excel->SetBorders('A'.$r.":".'J'.$r);
$this->excel->SetFont('A'.$r.":".'J'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("F,G,H,I");
$tot=$tot_discount=$tot_net=0;

foreach($purchase as $row){
    $tot_gross+=$row->gross;
    $tot_net+=$row->net_amount;
    $tot_disc+=$row->discount+$row->additional_deduct;
    $tot_add+=$row->additional_add;
    $tot_vat+=$row->vat_amount;

    $dis=($row->discount+$row->additional_deduct);

    $this->excel->getActiveSheet()->setCellValue('A'.$key, $row->nno);
    $this->excel->getActiveSheet()->setCellValue('B'.$key, $row->sub_no);
    $this->excel->getActiveSheet()->setCellValue('C'.$key, $row->ddate);
    $this->excel->getActiveSheet()->setCellValue('D'.$key, $row->do_no);  
    $this->excel->getActiveSheet()->setCellValue('E'.$key, $row->cus_name);
    $this->excel->getActiveSheet()->setCellValue('F'.$key, $row->gross);
    $this->excel->getActiveSheet()->setCellValue('G'.$key, $dis);
    $this->excel->getActiveSheet()->setCellValue('H'.$key, $row->additional_add);        
    $this->excel->getActiveSheet()->setCellValue('I'.$key, $row->net_amount);
    $this->excel->getActiveSheet()->setCellValue('J'.$key, $row->description);
    $this->excel->SetBorders('A'.$key.":".'J'.$key);
    $key++;
}


$this->excel->SetFont('D'.$key.":".'G'.$key,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$key, "");
$this->excel->getActiveSheet()->setCellValue('B'.$key, "");
$this->excel->getActiveSheet()->setCellValue('C'.$key, "");
$this->excel->getActiveSheet()->setCellValue('D'.$key, "");
$this->excel->getActiveSheet()->setCellValue('E'.$key, "TOTAL");
$this->excel->getActiveSheet()->setCellValue('F'.$key, $tot_gross);
$this->excel->getActiveSheet()->setCellValue('G'.$key, $tot_disc);
$this->excel->getActiveSheet()->setCellValue('H'.$key, $tot_add);        
$this->excel->getActiveSheet()->setCellValue('I'.$key, $tot_net);
$this->excel->getActiveSheet()->setCellValue('J'.$key,"");

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));

