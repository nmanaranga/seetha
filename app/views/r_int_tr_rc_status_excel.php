<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->excel->setActiveSheetIndex(0);
foreach($branch as $ress){
 $this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
$this->excel->setHeading("Transfer Receive Status ");

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$from);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$to);
$eno=$this->excel->LastRowNum();

$this->excel->SetFont('A'.$eno.":".'A'.$eno,"B",12,"","");

$this->excel->SetBlank();
$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->mergeCells("A".($r).":E".($r));
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Internal Transfer");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"");
$this->excel->getActiveSheet()->mergeCells("F".($r).":J".($r));
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Internal Transfer Receive");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"");        
$this->excel->getActiveSheet()->setCellValue('I'.$r,"");
$this->excel->getActiveSheet()->setCellValue('J'.$r,"");


$this->excel->SetBorders('A'.$r.":".'J'.$r);
$this->excel->SetFont('A'.$r.":".'J'.$r,"BC",12,"","");
$r++;

$this->excel->getActiveSheet()->setCellValue('A'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Cluster");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Branch");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"No");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Qty");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Cluster");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Branch");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"No");        
$this->excel->getActiveSheet()->setCellValue('I'.$r,"Qty");
$this->excel->getActiveSheet()->setCellValue('I'.$r,"Status");


$this->excel->SetBorders('A'.$r.":".'I'.$r);
$this->excel->SetFont('A'.$r.":".'I'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("I,E");


foreach($int_trans as $row)
{
 $this->excel->getActiveSheet()->setCellValue('A'.$key, $row->ddate);
 $this->excel->getActiveSheet()->setCellValue('B'.$key, $row->t_clus);
 $this->excel->getActiveSheet()->setCellValue('C'.$key, $row->t_bc);
 $this->excel->getActiveSheet()->setCellValue('D'.$key, $row->tr_no);
 $this->excel->getActiveSheet()->setCellValue('E'.$key, $row->qty);
 $this->excel->getActiveSheet()->setCellValue('F'.$key, $row->r_clus);
 $this->excel->getActiveSheet()->setCellValue('G'.$key, $row->r_bc);
 $this->excel->getActiveSheet()->setCellValue('H'.$key, $row->rec_no);
 $this->excel->getActiveSheet()->setCellValue('I'.$key, $row->received_qty);
 $this->excel->getActiveSheet()->setCellValue('J'.$key, $row->status);

 $this->excel->SetBorders('A'.$key.":".'J'.$key);

 $key++;
 $tr_qty+=(float)$row->qty;
 $rc_qty+=(float)$row->received_qty;

}
$this->excel->getActiveSheet()->mergeCells("A".($key).":D".($key));
$this->excel->getActiveSheet()->setCellValue('A'.$key,"");
$this->excel->getActiveSheet()->setCellValue('B'.$key,"");
$this->excel->getActiveSheet()->setCellValue('C'.$key,"");
$this->excel->getActiveSheet()->setCellValue('D'.$key,"");
$this->excel->getActiveSheet()->setCellValue('E'.$key,$tr_qty);
$this->excel->getActiveSheet()->mergeCells("F".($key).":H".($key));
$this->excel->getActiveSheet()->setCellValue('F'.$key,"");
$this->excel->getActiveSheet()->setCellValue('G'.$key,"");
$this->excel->getActiveSheet()->setCellValue('H'.$key,"");        
$this->excel->getActiveSheet()->setCellValue('I'.$key,$rc_qty);
$this->excel->SetBorders('E'.$key.":".'E'.$key);
$this->excel->SetBorders('I'.$key.":".'I'.$key);
$key++;

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));

