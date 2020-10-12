<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->excel->setActiveSheetIndex(0);
foreach($branch as $ress){
 $this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
$this->excel->setHeading("PURCHASE BILL SUMMARY");

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dfrom);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dto);
$eno=$this->excel->LastRowNum();

$this->excel->SetFont('A'.$eno.":".'A'.$eno,"B",12,"","");

$this->excel->SetBlank();
$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"GRN No");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"PO No");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Inv No");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Amount");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Discount");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Net Amount");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"Paid");        
$this->excel->getActiveSheet()->setCellValue('I'.$r,"Returned");
$this->excel->getActiveSheet()->setCellValue('J'.$r,"Balance");
$this->excel->SetBorders('A'.$r.":".'J'.$r);
$this->excel->SetFont('A'.$r.":".'J'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("E,F,G,H,I,J");
$tot=$tot_discount=$tot_net=0;

$supp='default';
$bal=(int)0;

foreach($item_det as $row)
{
  $bal=(int)$row->net_amount -((int)$row->paid+(int)$row->return_q);

  if($supp!=$row->supp_id)
  {
    $this->excel->getActiveSheet()->mergeCells("A".($key).":J".($key));
    $this->excel->getActiveSheet()->setCellValue('A'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('B'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('C'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('D'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('E'.$key, ""); 
    $this->excel->getActiveSheet()->setCellValue('F'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('G'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('H'.$key, "");        
    $this->excel->getActiveSheet()->setCellValue('I'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('J'.$key, "");
    $this->excel->SetBorders('A'.$key.":".'J'.$key);
    $key++;

    $this->excel->getActiveSheet()->mergeCells("A".($key).":J".($key));
    $this->excel->getActiveSheet()->setCellValue('A'.$key, $row->supp_id." - ".$row->name);
    $this->excel->getActiveSheet()->setCellValue('B'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('C'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('D'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('E'.$key, ""); 
    $this->excel->getActiveSheet()->setCellValue('F'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('G'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('H'.$key, "");        
    $this->excel->getActiveSheet()->setCellValue('I'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('J'.$key, "");
    $this->excel->SetBorders('A'.$key.":".'J'.$key);
    $key++;
}


$this->excel->getActiveSheet()->setCellValue('A'.$key, $row->ddate);
$this->excel->getActiveSheet()->setCellValue('B'.$key, $row->nno);
$this->excel->getActiveSheet()->setCellValue('C'.$key, $row->po_no);
$this->excel->getActiveSheet()->setCellValue('D'.$key, $row->inv_no);
$this->excel->getActiveSheet()->setCellValue('E'.$key, $row->amount);
$this->excel->getActiveSheet()->setCellValue('F'.$key, $row->discount);
$this->excel->getActiveSheet()->setCellValue('G'.$key, $row->net_amount);
$this->excel->getActiveSheet()->setCellValue('H'.$key, $row->paid);        
$this->excel->getActiveSheet()->setCellValue('I'.$key, $row->return_q);
$this->excel->getActiveSheet()->setCellValue('J'.$key, $bal);
$this->excel->SetBorders('A'.$key.":".'J'.$key);

$supp=$row->supp_id;
$key++;

}

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));

