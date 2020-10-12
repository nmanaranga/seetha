<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->excel->setActiveSheetIndex(0);
foreach($branch as $ress){
 $this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
$this->excel->setHeading("Internal Transfer Status ");

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$from);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$to);
$eno=$this->excel->LastRowNum();

$this->excel->SetFont('A'.$eno.":".'A'.$eno,"B",12,"","");

$this->excel->SetBlank();
$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->mergeCells("A".($r).":F".($r));
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Internal Transfer Order (Requesting Branch)");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"");
$this->excel->getActiveSheet()->mergeCells("G".($r).":L".($r));
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Internal Transfer (Sending Branch)");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"");        
$this->excel->getActiveSheet()->setCellValue('I'.$r,"");
$this->excel->getActiveSheet()->setCellValue('J'.$r,"");
$this->excel->getActiveSheet()->setCellValue('K'.$r,"");
$this->excel->getActiveSheet()->setCellValue('L'.$r,"");
$this->excel->getActiveSheet()->mergeCells("M".($r).":R".($r));
$this->excel->getActiveSheet()->setCellValue('M'.$r,"Internal Receipt (Receiving Branch)");
$this->excel->getActiveSheet()->setCellValue('N'.$r,"");
$this->excel->getActiveSheet()->setCellValue('O'.$r,"");
$this->excel->getActiveSheet()->setCellValue('P'.$r,"");
$this->excel->getActiveSheet()->setCellValue('Q'.$r,"");
$this->excel->getActiveSheet()->setCellValue('R'.$r,"");    
$this->excel->getActiveSheet()->mergeCells("S".($r).":X".($r));
$this->excel->getActiveSheet()->setCellValue('S'.$r,"Internal Transfer Return (Return Branch)");
$this->excel->getActiveSheet()->setCellValue('T'.$r,"");
$this->excel->getActiveSheet()->setCellValue('U'.$r,"");
$this->excel->getActiveSheet()->setCellValue('V'.$r,"");
$this->excel->getActiveSheet()->setCellValue('W'.$r," ");
$this->excel->getActiveSheet()->setCellValue('X'.$r,"");   
$this->excel->getActiveSheet()->mergeCells("Y".($r).":Z".($r));
$this->excel->getActiveSheet()->setCellValue('Y'.$r,"Variances");
$this->excel->getActiveSheet()->setCellValue('Z'.$r,"");

$this->excel->SetBorders('A'.$r.":".'Z'.$r);
$this->excel->SetFont('A'.$r.":".'Z'.$r,"BC",12,"","");
$r++;

$this->excel->getActiveSheet()->setCellValue('A'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Branch");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"No");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Code");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Qty");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Value");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"Branch");        
$this->excel->getActiveSheet()->setCellValue('I'.$r,"No");
$this->excel->getActiveSheet()->setCellValue('J'.$r,"Code");
$this->excel->getActiveSheet()->setCellValue('K'.$r,"Qty");
$this->excel->getActiveSheet()->setCellValue('L'.$r,"Value");
$this->excel->getActiveSheet()->setCellValue('M'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('N'.$r,"Branch");
$this->excel->getActiveSheet()->setCellValue('O'.$r,"No");
$this->excel->getActiveSheet()->setCellValue('P'.$r,"Code");
$this->excel->getActiveSheet()->setCellValue('Q'.$r,"Qty");
$this->excel->getActiveSheet()->setCellValue('R'.$r,"Value");    
$this->excel->getActiveSheet()->setCellValue('S'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('T'.$r,"Branch");
$this->excel->getActiveSheet()->setCellValue('U'.$r,"No");
$this->excel->getActiveSheet()->setCellValue('V'.$r,"Code");
$this->excel->getActiveSheet()->setCellValue('W'.$r,"Qty");
$this->excel->getActiveSheet()->setCellValue('X'.$r,"Value");   
$this->excel->getActiveSheet()->setCellValue('Y'.$r,"Qty");
$this->excel->getActiveSheet()->setCellValue('Z'.$r,"Value");


$this->excel->SetBorders('A'.$r.":".'Z'.$r);
$this->excel->SetFont('A'.$r.":".'Z'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("E,F,K,L,I,Q,R,W,X,Y,Z");


foreach($details as $row)
{
  $dif_qty = $row->r_qty - $row->i_qty+$row->rt_qty;
  $dif_val = $row->i_amount - $row->r_amount - $row->rt_amount;


  $this->excel->getActiveSheet()->setCellValue('A'.$key, $row->o_date);
  $this->excel->getActiveSheet()->setCellValue('B'.$key, $row->o_bc);
  $this->excel->getActiveSheet()->setCellValue('C'.$key, $row->o_nno);
  $this->excel->getActiveSheet()->setCellValue('D'.$key, $row->o_item);
  $this->excel->getActiveSheet()->setCellValue('E'.$key, $row->o_qty);
  $this->excel->getActiveSheet()->setCellValue('F'.$key, $row->o_amount);

  if($row->i_date==""){
    $this->excel->getActiveSheet()->mergeCells("G".($key).":L".($key));
    $this->excel->getActiveSheet()->setCellValue('G'.$key, "Items Not Issued");
    $this->excel->getActiveSheet()->setCellValue('H'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('I'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('J'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('K'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('L'.$key, "");
    
}else{
  $this->excel->getActiveSheet()->setCellValue('G'.$key, $row->i_date);
  $this->excel->getActiveSheet()->setCellValue('H'.$key, $row->i_bc);        
  $this->excel->getActiveSheet()->setCellValue('I'.$key, $row->i_nno);
  $this->excel->getActiveSheet()->setCellValue('J'.$key, $row->i_item);
  $this->excel->getActiveSheet()->setCellValue('K'.$key, $row->i_qty);        
  $this->excel->getActiveSheet()->setCellValue('L'.$key, $row->i_amount);
}
if($row->r_date==""){
   $this->excel->getActiveSheet()->mergeCells("M".($key).":R".($key));
   $this->excel->getActiveSheet()->setCellValue('M'.$key, "Items Not Received");
   $this->excel->getActiveSheet()->setCellValue('N'.$key, "");
   $this->excel->getActiveSheet()->setCellValue('O'.$key, "");
   $this->excel->getActiveSheet()->setCellValue('P'.$key, "");
   $this->excel->getActiveSheet()->setCellValue('Q'.$key, "");
   $this->excel->getActiveSheet()->setCellValue('R'.$key, "");
}else{
    $this->excel->getActiveSheet()->setCellValue('M'.$key, $row->r_date);
    $this->excel->getActiveSheet()->setCellValue('N'.$key, $row->r_bc);
    $this->excel->getActiveSheet()->setCellValue('O'.$key, $row->r_nno);
    $this->excel->getActiveSheet()->setCellValue('P'.$key, $row->r_item);
    $this->excel->getActiveSheet()->setCellValue('Q'.$key, $row->r_qty);
    $this->excel->getActiveSheet()->setCellValue('R'.$key, $row->r_amount);
}
if($row->rt_date==""){
    $this->excel->getActiveSheet()->mergeCells("S".($key).":X".($key));
    $this->excel->getActiveSheet()->setCellValue('S'.$key, "Items Not Returned");
    $this->excel->getActiveSheet()->setCellValue('T'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('U'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('V'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('W'.$key, "");
    $this->excel->getActiveSheet()->setCellValue('X'.$key, "");
}else{
    $this->excel->getActiveSheet()->setCellValue('S'.$key, $row->rt_date);
    $this->excel->getActiveSheet()->setCellValue('T'.$key, $row->rt_bc);
    $this->excel->getActiveSheet()->setCellValue('U'.$key, $row->rt_nno);
    $this->excel->getActiveSheet()->setCellValue('V'.$key, $row->rt_item);
    $this->excel->getActiveSheet()->setCellValue('W'.$key, $row->rt_qty);
    $this->excel->getActiveSheet()->setCellValue('X'.$key, $row->rt_amount);
}
$this->excel->getActiveSheet()->setCellValue('Y'.$key, $dif_qty);
$this->excel->getActiveSheet()->setCellValue('Z'.$key, $dif_val);


$this->excel->SetBorders('A'.$key.":".'Z'.$key);

$key++;

}

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));

