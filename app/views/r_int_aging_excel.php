<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->excel->setActiveSheetIndex(0);
foreach($branch as $ress){
 $this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
$this->excel->setHeading("Inter Branch Aging Report");

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$from);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$to);
$eno=$this->excel->LastRowNum();

$this->excel->SetFont('A'.$eno.":".'A'.$eno,"B",12,"","");

$this->excel->SetBlank();
$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Account");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Description");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Issue No");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Receive No");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Balance");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"<30");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"31 to 60");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"61 to 90");      
$this->excel->getActiveSheet()->setCellValue('I'.$r,"Over 90");     
$this->excel->SetBorders('A'.$r.":".'I'.$r);
$this->excel->SetFont('A'.$r.":".'I'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("C,D,E,F,G,H,I");
$totamo=$totpaid=$totbal=0;
$code=true;
foreach($cus_det as $row){
  $totbalance+=$row->balance;
  $totBelow30+=$row->Below30;
  $totD30t60+=$row->D30t60;
  $totD60t90+=$row->D60t90;
  $totOver90+=$row->Over90;


  if($code!=ture && $code==$row->acc_code){
    $this->excel->getActiveSheet()->mergeCells("A".($key).":B".($key));
    $this->excel->getActiveSheet()->setCellValue('A'.$key, '');
    $this->excel->getActiveSheet()->setCellValue('B'.$key, '');
    $this->excel->getActiveSheet()->setCellValue('C'.$key, $row->sub_no);
    $this->excel->getActiveSheet()->setCellValue('D'.$key, $row->receive_no);
    $this->excel->getActiveSheet()->setCellValue('E'.$key, $row->balance);
    $this->excel->getActiveSheet()->setCellValue('F'.$key, $row->Below30);
    $this->excel->getActiveSheet()->setCellValue('G'.$key, $row->D30t60);   
    $this->excel->getActiveSheet()->setCellValue('H'.$key, $row->D60t90);  
    $this->excel->getActiveSheet()->setCellValue('I'.$key, $row->Over90);        

    $this->excel->SetBorders('C'.$key.":".'I'.$key);
  }else{
   $this->excel->getActiveSheet()->setCellValue('A'.$key, $row->acc_code);
   $this->excel->getActiveSheet()->setCellValue('B'.$key, ucfirst(strtolower($row->description)));
   $this->excel->getActiveSheet()->setCellValue('C'.$key, $row->sub_no);
   $this->excel->getActiveSheet()->setCellValue('D'.$key, $row->receive_no);
   $this->excel->getActiveSheet()->setCellValue('E'.$key, $row->balance);
   $this->excel->getActiveSheet()->setCellValue('F'.$key, $row->Below30);
   $this->excel->getActiveSheet()->setCellValue('G'.$key, $row->D30t60);   
   $this->excel->getActiveSheet()->setCellValue('H'.$key, $row->D60t90);  
   $this->excel->getActiveSheet()->setCellValue('I'.$key, $row->Over90);  
   $this->excel->SetBorders('A'.$key.":".'I'.$key);
 }
 $code=$row->acc_code;
 $key++;

}


$this->excel->SetFont('C'.$key.":".'I'.$key,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('A'.$key, "");
$this->excel->getActiveSheet()->setCellValue('B'.$key, "");
$this->excel->getActiveSheet()->setCellValue('C'.$key, "");
$this->excel->getActiveSheet()->setCellValue('D'.$key, "Total");
$this->excel->getActiveSheet()->setCellValue('E'.$key, $totbalance);
$this->excel->getActiveSheet()->setCellValue('F'.$key, $totBelow30);
$this->excel->getActiveSheet()->setCellValue('G'.$key, $totD30t60);        
$this->excel->getActiveSheet()->setCellValue('H'.$key, $totD60t90);  
$this->excel->getActiveSheet()->setCellValue('I'.$key, $totOver90); 

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));

