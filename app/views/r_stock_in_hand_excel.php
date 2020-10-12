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


$this->excel->setHeading("Stock In Hand Report");


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
$this->excel->getActiveSheet()->setCellValue('C'.$r,"Item Model");
$this->excel->getActiveSheet()->setCellValue('D'.$r,"Quantity");
$this->excel->getActiveSheet()->setCellValue('E'.$r,"Last Price");
$this->excel->getActiveSheet()->setCellValue('F'.$r,"Max Price");
$this->excel->getActiveSheet()->setCellValue('G'.$r,"Cost Price");        
$this->excel->getActiveSheet()->setCellValue('H'.$r,"Total");
$this->excel->SetBorders('A'.$r.":".'H'.$r);
$this->excel->SetFont('A'.$r.":".'H'.$r,"BC",12,"","");

$key=$this->excel->NextRowNum();
$this->excel->SetAsNumber("E,F,G,H");
$tot=0;

foreach($item_det as $row){

    $cost=(int)$row->qty*(float)$row->purchase_price;
    $tot+=$cost;
    $this->excel->getActiveSheet()->setCellValue('A'.$key, $row->code);
    $this->excel->getActiveSheet()->setCellValue('B'.$key, $row->description);
    $this->excel->getActiveSheet()->setCellValue('C'.$key, $row->model);
    $this->excel->getActiveSheet()->setCellValue('D'.$key, $row->qty);
    $this->excel->getActiveSheet()->setCellValue('E'.$key, $row->min_price);
    $this->excel->getActiveSheet()->setCellValue('F'.$key, $row->max_price);
    $this->excel->getActiveSheet()->setCellValue('G'.$key, $row->purchase_price);        
    $this->excel->getActiveSheet()->setCellValue('H'.$key, $cost);
    $this->excel->SetBorders('A'.$key.":".'H'.$key);

    $key++;
}

$key2=$this->excel->NextRowNum();
$this->excel->SetFont('G'.$key2.":".'H'.$key2,"B",12,"","");
$this->excel->getActiveSheet()->setCellValue('G'.$key2, "Total");
$this->excel->getActiveSheet()->setCellValue('H'.$key2, $tot);

// $this->excel->SetBorders();
$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));




// $colString = PHPExcel_Cell::stringFromColumnIndex($colString);
// $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($colString);



// $this->excel->setActiveSheetIndex(0);
// //name the worksheet
// $this->excel->getActiveSheet()->setTitle('test worksheet');
// //set cell A1 content with some text
// $this->excel->getActiveSheet()->setCellValue('A1', 'This is just some text value');
// //change the font size
// $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
// //make the font become bold
// $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
// //merge cell A1 until D1
// $this->excel->getActiveSheet()->mergeCells('A1:D1');

// $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

























// // Create your database query
// $query = "SELECT * FROM m_branch";  

// // Execute the database query
// $result = mysql_query($query) or die(mysql_error());

// // Instantiate a new PHPExcel object 
// $objPHPExcel = new PHPExcel();  
// // Set the active Excel worksheet to sheet 0 
// $objPHPExcel->setActiveSheetIndex(0);  
// // Initialise the Excel row number 
// $rowCount = 1;  

// //start of printing column names as names of MySQL fields  
// $column = 'A';
// for ($i = 1; $i < mysql_num_fields($result); $i++)  
// {
//     $objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, mysql_field_name($result,$i));
//     $column++;
// }
// //end of adding column names  

// //start while loop to get data  
// $rowCount = 2;  
// while($row = mysql_fetch_row($result))  
// {  
//     $column = 'A';
//     for($j=1; $j<mysql_num_fields($result);$j++)  
//     {  
//         if(!isset($row[$j]))  
//             $value = NULL;  
//         elseif ($row[$j] != "")  
//             $value = strip_tags($row[$j]);  
//         else  
//             $value = "";  

//         $objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $value);
//         $column++;
//     }  
//     $rowCount++;
// } 

// $filename="sddfgg";
// // Redirect output to a client’s web browser (Excel5) 
// // header('Content-Type: app/vnd.ms-excel'); 
// // header('Content-Disposition: attachment;filename="Limesurvey_Results.xls"'); 
// // header('Cache-Control: max-age=0'); 
// // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
// // $objWriter->save('php://output');



// //   $objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold(true);
// //   $objPHPExcel->setActiveSheetIndex(0);
//   // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// //   $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
// // // $objWriter->save('php://output');  


// // header("Content-Disposition: attachment; filename=\"" . $filename . ".xlsx\"");  
// // header('Cache-Control: max-age=0');   

// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// header('Content-Type: application/vnd.ms-excel');
// header('Content-Disposition: attachment;filename="userList.xls"');
