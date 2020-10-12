<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->excel->setActiveSheetIndex(0);
foreach($branch as $ress){
 $this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
$this->excel->setHeading("Recipt List Summery");

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$from);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$to);
$eno=$this->excel->LastRowNum();

$this->excel->SetFont('A'.$eno.":".'A'.$eno,"B",12,"","");

$this->excel->SetBlank();
$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"No");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Customer");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Cash");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"C.Card");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Cheaque");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"PD Cheaque");  
$this->excel->SetBorders('A'.$r.":".'G'.$r);
$this->excel->SetFont('A'.$r.":".'G'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("D,E,F,G");
$totamo=$totpaid=$totbal=0;
$code=true;
foreach($purchase as $row){
  $totcash_amount+=$row->cash_amount;
  $totpay_card+=$row->pay_card;
  $totcheque_amount+=$row->cheque_amount;
  $totpd_amount+=$row->pd_amount;

  $this->excel->getActiveSheet()->setCellValue('A'.$key, $row->nno);
  $this->excel->getActiveSheet()->setCellValue('B'.$key, $row->ddate);
  $this->excel->getActiveSheet()->setCellValue('C'.$key, $row->cus);
  $this->excel->getActiveSheet()->setCellValue('D'.$key, $row->cash_amount);
  $this->excel->getActiveSheet()->setCellValue('E'.$key, $row->pay_card);
  $this->excel->getActiveSheet()->setCellValue('F'.$key, $row->cheque_amount);
  $this->excel->getActiveSheet()->setCellValue('G'.$key, $row->pd_amount);   
  $this->excel->SetBorders('A'.$key.":".'G'.$key);

  $key++;

}


$this->excel->SetFont('A'.$key.":".'G'.$key,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$key, "");
$this->excel->getActiveSheet()->setCellValue('B'.$key, "");
$this->excel->getActiveSheet()->setCellValue('C'.$key, "Total");
$this->excel->getActiveSheet()->setCellValue('D'.$key, $totcash_amount);
$this->excel->getActiveSheet()->setCellValue('E'.$key, $totpay_card);
$this->excel->getActiveSheet()->setCellValue('F'.$key, $totcheque_amount);
$this->excel->getActiveSheet()->setCellValue('G'.$key, $totpd_amount);        

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));

