<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->excel->setActiveSheetIndex(0);


foreach ($branch as $ress) {
	$this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}

$this->excel->getActiveSheet()->setCellValue('A'.$this->excel->NextRowNum(), "Date");
$this->excel->getActiveSheet()->setCellValue('B'.$this->excel->LastRowNum(), $dfrom);
$this->excel->getActiveSheet()->setCellValue('C'.$this->excel->LastRowNum(), 'TO');
$this->excel->getActiveSheet()->setCellValue('D'.$this->excel->LastRowNum(), $dto);
$this->excel->SetBlank();
$this->excel->setHeading("Department Wise Sales");


$lettr = array("B","C","D","E", "F", "G","H", "I","J", "K","L", "M","N", "O","P", "Q","R", "S","T", "U","V", "W","X", "Y","Z", "AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ",);

$r=$this->excel->NextRowNum();

$this->excel->getActiveSheet()->getStyle('A'.$r)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('a2a276');
$this->excel->SetBorders('A'.$r);
$this->excel->getActiveSheet()->setCellValue('A'.$r, "Code");

$y=0;
foreach ($branch_det as $row) {
	$this->excel->getActiveSheet()->getStyle('A'.$r.":".$lettr[$y].$r)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('a2a276');
	$this->excel->SetFont('A1'.":".$lettr[$y].$r,"B",11,"","");
	$this->excel->getActiveSheet()->setCellValue($lettr[$y].$r, $row->cl.$row->bc);
	
	$y++;
}
$this->excel->SetBorders('A'.$r.":".$lettr[$y].$r);
$this->excel->SetFont($lettr[$y].$r,"B",11,"","");
$this->excel->getActiveSheet()->getStyle('A'.$r.":".$lettr[$y].$r)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('a2a276');
$this->excel->getActiveSheet()->setCellValue($lettr[$y].$r, "Total");



$amount=(float)0;
foreach($sales as $row){
	$w=0;$z=0;
	$bc="";
	$r=$this->excel->NextRowNum();
	$this->excel->SetBorders('A'.$r);
	$this->excel->SetFont('A'.$r,"B",11,"","");
	$this->excel->getActiveSheet()->getStyle('A'.$r)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ccddff');
	$this->excel->getActiveSheet()->setCellValue('A'.$r, $row->description);

	foreach ($branch_det as $value) {
		$code=$value->bc;
		$this->excel->getActiveSheet()->getStyle('A'.$r.":".$lettr[$y].$r)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ccddff');
		$this->excel->SetFont('A1'.":".$lettr[$w].$r,"",11,"","");
		$this->excel->getActiveSheet()->setCellValue($lettr[$w].$r, $row->$code);
		$w++;

	}
	$this->excel->SetBorders('A'.$r.":".$lettr[$w].$r);
	$this->excel->SetFont($lettr[$y].$s,"B",11,"","");
	$this->excel->getActiveSheet()->setCellValue($lettr[$w].$r, $row->totsale);
	$tot_qty+=$row->qty;
	$z++;
}


$this->excel->SetBlank();
$this->excel->SetBlank();
/*----------------------------------------------- purchase section -----------------------------------------------*/


$this->excel->setHeading("Department Wise Purchase");


$lettr = array("B","C","D","E", "F", "G","H", "I","J", "K","L", "M","N", "O","P", "Q","R", "S","T", "U","V", "W","X", "Y","Z", "AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ",);

$s=$this->excel->NextRowNum();

$this->excel->SetBorders('A'.$s);
$this->excel->getActiveSheet()->getStyle('A'.$s)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('a2a276');
$this->excel->getActiveSheet()->setCellValue('A'.$s, "Code");

$y=0;
foreach ($branch_det as $row) {
	$this->excel->getActiveSheet()->getStyle('A'.$s.":".$lettr[$y].$s)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('a2a276');
	$this->excel->SetFont('A1'.":".$lettr[$y].$s,"B",11,"","");
	$this->excel->getActiveSheet()->setCellValue($lettr[$y].$s, $row->cl.$row->bc);
	
	$y++;
}
$this->excel->SetBorders('A'.$s.":".$lettr[$y].$s);
$this->excel->SetFont($lettr[$y].$s,"B",11,"","");
$this->excel->getActiveSheet()->getStyle('A'.$s.":".$lettr[$y].$s)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('a2a276');
$this->excel->getActiveSheet()->setCellValue($lettr[$y].$s, "Total");



$amount=(float)0;
foreach($purchase as $row){
	$w=0;$z=0;
	$bc="";
	$s=$this->excel->NextRowNum();
	$this->excel->SetBorders('A'.$s);
	$this->excel->SetFont('A'.$s,"B",11,"","");
	$this->excel->getActiveSheet()->getStyle('A'.$s)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ccddff');
	$this->excel->getActiveSheet()->setCellValue('A'.$s, $row->description);

	foreach ($branch_det as $value) {
		$code=$value->bc;
	$this->excel->getActiveSheet()->getStyle('A'.$s.":".$lettr[$y].$s)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('ccddff');
		$this->excel->SetFont('A1'.":".$lettr[$w].$s,"B",11,"","");
		$this->excel->getActiveSheet()->setCellValue($lettr[$w].$s, $row->$code);
		$w++;

	}
	$this->excel->SetBorders('A'.$s.":".$lettr[$w].$s);
	$this->excel->SetFont($lettr[$y].$s,"B",11,"","");
	$this->excel->getActiveSheet()->setCellValue($lettr[$w].$s, $row->totsale);
	$tot_qty+=$row->qty;
	$z++;
}



$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));
?>