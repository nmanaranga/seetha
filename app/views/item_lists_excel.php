<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->excel->setActiveSheetIndex(0);


foreach($branch as $ress){
   $this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

foreach($clus as $cl){
    $claster_name=$cl->description;
    $cl_code=$cl->code;
}
foreach($bran as $b){
    $b_name=$b->name;
    $b_code=$b->bc;
}
foreach($str as $s){
    $s_name=$s->description;
    $s_code=$s->code;
}
foreach($dp as $d){
    $d_name=$d->description;
    $d_code=$d->code;
}
foreach($cat as $mc){
    $m_cat=$mc->description;
    $m_cat_code=$mc->code;
}
foreach($scat as $sc){
    $s_cat=$sc->description;
    $s_cat_code=$sc->code;
}
foreach($itm as $it){
    $i_name=$it->description;
    $i_code=$it->code;
}
foreach($unt as $u){
    $u_name=$u->description;
    $u_code=$u->code;
}
foreach($brnd as $br){
    $br_name=$br->description;
    $br_code=$br->code;
}
foreach($sup as $su){
    $su_name=$su->name;
    $su_code=$su->code;
}


$this->excel->setHeading("Item List Report");


$sno=$this->excel->NextRowNum();
if (!empty($to)) {
    $this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(), "As At Date");
    $this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(), $to);
}
if (!empty($cl_code)) {                    
    $this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(), "Cluster");
    $this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(), $cl_code." - ".$claster_name);
}
if (!empty($b_code)) { 
    $this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(), "Branch");
    $this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(), $b_code." - ".$b_name);
}
if (!empty($s_code)) { 
    $this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(), "Store");
    $this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(), $s_code." - ".$s_name);
}
if (!empty($d_code)) { 
    $this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(), "Department");
    $this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(), $d_code." - ".$d_name);
}
if (!empty($m_cat_code)) { 
    $this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(), "Main Category");
    $this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(), $m_cat_code." - ".$m_cat);
}
if (!empty($s_cat_code)) { 
    $this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(), "Sub Category");
    $this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(), $s_cat_code." - ".$s_cat);
}
if (!empty($i_code)) { 
    $this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(), "Item");
    $this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(), $i_code." - ".$i_name);
}
if (!empty($u_code)) { 
    $this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(), "Unit");
    $this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(), $u_code." - ".$u_name);
}
if (!empty($br_code)) { 
    $this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(), "Brand");
    $this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(), $br_code." - ".$br_name);
}
if (!empty($su_code)) { 
    $this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(), "Supplier");
    $this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(), $su_code." - ".$su_name);
}
$eno=$this->excel->LastRowNum();
$this->excel->SetFont('A'.$sno.":".'A'.$eno,"B",12,"","");

$this->excel->SetBlank();


$r=$this->excel->NextRowNum();
$this->excel->getActiveSheet()->setCellValue('A'.$r,"Item Code");
$this->excel->getActiveSheet()->setCellValue('B'.$r,"Item Name");
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Item Brand");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Item Model");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Max Price");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Min Price");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Category");        
$this->excel->getActiveSheet()->setCellValue('H'.$r,"Department");
$this->excel->SetBorders('A'.$r.":".'H'.$r);
$this->excel->SetFont('A'.$r.":".'H'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("E,F");
$tot=0;

foreach($customer as $row){


    $this->excel->getActiveSheet()->setCellValue('A'.$key, $row->code);
    $this->excel->getActiveSheet()->setCellValue('B'.$key, $row->description);
    $this->excel->getActiveSheet()->setCellValue('C'.$key, $row->brand);
    $this->excel->getActiveSheet()->setCellValue('D'.$key, $row->model);
    $this->excel->getActiveSheet()->setCellValue('E'.$key, $row->max_price);
    $this->excel->getActiveSheet()->setCellValue('F'.$key, $row->min_price);
    $this->excel->getActiveSheet()->setCellValue('G'.$key, $row->Category);        
    $this->excel->getActiveSheet()->setCellValue('H'.$key, $row->Department_Name);
    $this->excel->SetBorders('A'.$key.":".'H'.$key);

    $key++;
}



$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));
