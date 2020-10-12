<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->excel->setActiveSheetIndex(0);
foreach($branch as $ress){
 $this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
$this->excel->setHeading("TRANSFER SUMMARY - MAIN STORES");

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"Date Between");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dfrom);

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(),"To");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(),$dto);
$eno=$this->excel->LastRowNum();

$this->excel->SetFont('A'.$eno.":".'A'.$eno,"B",12,"","");

$this->excel->SetBlank();
$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->mergeCells("A".($r).":H".($r));
$this->excel->getActiveSheet()->setCellValue('A'.$r,"");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"");
$this->excel->getActiveSheet()->setCellValue('I'.$r,"");
$this->excel->getActiveSheet()->setCellValue('J'.$r,""); 
$this->excel->getActiveSheet()->setCellValue('K'.$r,""); 

$this->excel->getActiveSheet()->mergeCells("L".($r).":M".($r));
$this->excel->getActiveSheet()->setCellValue('L'.$r,"Transfer Out");
$this->excel->getActiveSheet()->setCellValue('M'.$r,"");

$this->excel->getActiveSheet()->mergeCells("N".($r).":O".($r));
$this->excel->getActiveSheet()->setCellValue('N'.$r,"Transfer returns");
$this->excel->getActiveSheet()->setCellValue('O'.$r,"");

$this->excel->getActiveSheet()->mergeCells("P".($r).":Q".($r));
$this->excel->getActiveSheet()->setCellValue('P'.$r,"Net Transfer out");
$this->excel->getActiveSheet()->setCellValue('Q'.$r,"");

$this->excel->getActiveSheet()->mergeCells("R".($r).":S".($r));
$this->excel->getActiveSheet()->setCellValue('R'.$r,"Transfer in");
$this->excel->getActiveSheet()->setCellValue('S'.$r,"");

$this->excel->getActiveSheet()->mergeCells("T".($r).":U".($r));
$this->excel->getActiveSheet()->setCellValue('T'.$r,"Varience");
$this->excel->getActiveSheet()->setCellValue('U'.$r,"");  



$this->excel->SetBorders('A'.$r.":".'U'.$r);
$this->excel->SetFont('A'.$r.":".'U'.$r,"BC",12,"","");
$r++;

$this->excel->getActiveSheet()->setCellValue('A'.$r,"No");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Sub No");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Mode");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Mode No");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Date");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Department");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Stores");
$this->excel->getActiveSheet()->setCellValue('H'.$r,"Transfer to");
$this->excel->getActiveSheet()->setCellValue('I'.$r,"Order No");
$this->excel->getActiveSheet()->setCellValue('J'.$r,"Receive No");   
$this->excel->getActiveSheet()->setCellValue('K'.$r,"Receive Date");   

$this->excel->getActiveSheet()->setCellValue('L'.$r,"  Qty  ");
$this->excel->getActiveSheet()->setCellValue('M'.$r,"  Value  ");

$this->excel->getActiveSheet()->setCellValue('N'.$r,"  Qty  ");
$this->excel->getActiveSheet()->setCellValue('O'.$r,"  Value  ");

$this->excel->getActiveSheet()->setCellValue('P'.$r,"  Qty  ");
$this->excel->getActiveSheet()->setCellValue('Q'.$r,"  Value  ");

$this->excel->getActiveSheet()->setCellValue('R'.$r,"  Qty  ");
$this->excel->getActiveSheet()->setCellValue('S'.$r,"  Value  ");

$this->excel->getActiveSheet()->setCellValue('T'.$r,"  Qty  ");
$this->excel->getActiveSheet()->setCellValue('U'.$r,"  Value  ");  




$this->excel->SetBorders('A'.$r.":".'U'.$r);
$this->excel->SetFont('A'.$r.":".'U'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("M,O,Q,S,U");


foreach($sum as $row)
{
  $store = $row->from_store." - ".$row->store_name;
  if($row->trns_mode=='0'){
    $trns_mode='Internal Transfer';
  }else if($row->trns_mode=='1'){
    $trns_mode='Direct Transfer';
  }else{
    $trns_mode='Return Transfer';
  }
  $this->excel->getActiveSheet()->setCellValue('A'.$key, $row->nno);
  $this->excel->getActiveSheet()->setCellValue('B'.$key, $row->sub_no);
  $this->excel->getActiveSheet()->setCellValue('C'.$key, $trns_mode);
  $this->excel->getActiveSheet()->setCellValue('D'.$key, $row->mode_no);
  $this->excel->getActiveSheet()->setCellValue('E'.$key, $row->ddate);
  $this->excel->getActiveSheet()->setCellValue('F'.$key, $row->dep);
  $this->excel->getActiveSheet()->setCellValue('G'.$key, $store);
  $this->excel->getActiveSheet()->setCellValue('H'.$key, $row->to_bcName);
  $this->excel->getActiveSheet()->setCellValue('I'.$key, $row->order_no);
  $this->excel->getActiveSheet()->setCellValue('J'.$key, $row->r_no);
  $this->excel->getActiveSheet()->setCellValue('K'.$key, $row->r_date);
  $this->excel->getActiveSheet()->setCellValue('L'.$key, $row->t_qty);
  $this->excel->getActiveSheet()->setCellValue('M'.$key, $row->t_amo);
  $this->excel->getActiveSheet()->setCellValue('N'.$key, $row->rt_qty);
  $this->excel->getActiveSheet()->setCellValue('O'.$key, $row->rt_amo);
  $this->excel->getActiveSheet()->setCellValue('P'.$key, $row->t_qty-$row->rt_qty);
  $this->excel->getActiveSheet()->setCellValue('Q'.$key, $row->t_amo-$row->rt_amo);
  $this->excel->getActiveSheet()->setCellValue('R'.$key, $row->r_qty);
  $this->excel->getActiveSheet()->setCellValue('S'.$key, $row->r_amo);
  $this->excel->getActiveSheet()->setCellValue('T'.$key, ($row->t_qty-$row->rt_qty)-$row->r_qty);
  $this->excel->getActiveSheet()->setCellValue('U'.$key, ($row->t_amo-$row->rt_amo)-$row->r_amo);

  $this->excel->SetBorders('A'.$key.":".'U'.$key);

  $key++;

  $tot_t_qty+=$row->t_qty;
  $tot_t_amo+=$row->t_amo;
  $tot_rt_qty+=$row->rt_qty;
  $tot_rt_amo+=$row->rt_amo;
  $net_tot_qty+=$row->t_qty+$row->rt_qty;
  $net_tot_amo+=$row->t_amo+$row->rt_amo;
  $tot_r_qty+=$row->r_qty;
  $tot_r_amo+=$row->r_amo;
  $v_tot_qty+=($row->t_qty-$row->rt_qty)-$row->r_qty;
  $v_tot_amo+=($row->t_amo-$row->rt_amo)-$row->r_amo;

}

$nxt=$this->excel->NextRowNum();
$this->excel->SetBorders('A'.$nxt.":".'U'.$nxt);
$this->excel->SetFont('A'.$nxt.":".'U'.$nxt,"BR",12,"","");
$this->excel->getActiveSheet()->mergeCells("A".($nxt).":H".($nxt));
$this->excel->getActiveSheet()->setCellValue('A'.$nxt, "Total");
$this->excel->getActiveSheet()->setCellValue('B'.$nxt, "");
$this->excel->getActiveSheet()->setCellValue('C'.$nxt, "");
$this->excel->getActiveSheet()->setCellValue('D'.$nxt, "");
$this->excel->getActiveSheet()->setCellValue('E'.$nxt, "");
$this->excel->getActiveSheet()->setCellValue('F'.$nxt, "");
$this->excel->getActiveSheet()->setCellValue('G'.$nxt, "");
$this->excel->getActiveSheet()->setCellValue('H'.$nxt, "");
$this->excel->getActiveSheet()->setCellValue('I'.$nxt, "");
$this->excel->getActiveSheet()->setCellValue('J'.$nxt, "");
$this->excel->getActiveSheet()->setCellValue('K'.$nxt, "");
$this->excel->getActiveSheet()->setCellValue('L'.$nxt, $tot_t_qty);
$this->excel->getActiveSheet()->setCellValue('M'.$nxt, $tot_t_amo);
$this->excel->getActiveSheet()->setCellValue('N'.$nxt, $tot_rt_qty);
$this->excel->getActiveSheet()->setCellValue('O'.$nxt, $tot_rt_amo);
$this->excel->getActiveSheet()->setCellValue('P'.$nxt, $net_tot_qty);
$this->excel->getActiveSheet()->setCellValue('Q'.$nxt, $net_tot_amo);
$this->excel->getActiveSheet()->setCellValue('R'.$nxt, $tot_r_qty);
$this->excel->getActiveSheet()->setCellValue('S'.$nxt, $tot_r_amo);
$this->excel->getActiveSheet()->setCellValue('T'.$nxt, $v_tot_qty);
$this->excel->getActiveSheet()->setCellValue('U'.$nxt, $v_tot_amo);

$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));

