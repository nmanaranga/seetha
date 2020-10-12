<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');  

require_once APPPATH."/third_party/PHPExcel.php";
/** PHPExcel_Writer_Excel2007 */
include APPPATH.'third_party/PHPExcel/Writer/Excel2007.php';
include APPPATH.'third_party/PHPExcel/Writer/Excel5.php';
// include APPPATH.'third_party/PHPExcel/Writer/PDF.php';
// include APPPATH.'/third_party/dompdf/dompdf.php';

class excel extends PHPExcel {
	public function __construct() {
		parent::__construct();

	}

	public $HedRow;

	public function headerSet3($name,$address,$tp,$fax,$email){
		$sql="SELECT c.`description` AS cl FROM m_branch b 
			JOIN m_cluster c ON c.`code`=b.`cl`
			WHERE  b.`name`='$name'
			LIMIT 1";
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);

		//if($name=="ARPICO DISTRIBUTOR" || $name=="BALAGOLLA" || $name=="GELIOYA" || $name=="HUAWEI" || $name=="LUMALA DISTRIBUTOR"){
			$this->getActiveSheet()->setCellValue('A1', strtoupper($row['cl'])." - ".strtoupper($name) );
			$this->SetFont('A1:A1',"BC",20,"","");
			$this->getActiveSheet()->setCellValue('A2', "Address: ".$address);
			$this->getActiveSheet()->setCellValue('A3', "Tel: ".$tp." Fax: ".$fax);
			$this->getActiveSheet()->setCellValue('A4', "Email: ".$email );	
			$this->getActiveSheet()->setCellValue('A5',"");
			$this->SetBlank();
		/*}else{
			$this->getActiveSheet()->setCellValue('A1', 'Seetha Holdings(Pvt) Ltd - '.$name );
			$this->SetFont('A1:A1',"BC",20,"","");
			$this->getActiveSheet()->setCellValue('A2', "Address: ".$address);
			$this->getActiveSheet()->setCellValue('A3', "Tel: ".$tp." Fax: ".$fax);
			$this->getActiveSheet()->setCellValue('A4', "Email: ".$email );	
			$this->getActiveSheet()->setCellValue('A5',"");
			$this->SetBlank();
		}*/
	}

	public function AllSettings($allSetDt){

		$ColEnd = $this->setActiveSheetIndex(0)->getHighestColumn();
		$this->getActiveSheet()->mergeCells('A1:'.$ColEnd.'1');
		$this->getActiveSheet()->mergeCells('A2:'.$ColEnd.'2');
		$this->getActiveSheet()->mergeCells('A3:'.$ColEnd.'3');
		$this->getActiveSheet()->mergeCells('A4:'.$ColEnd.'4');
		$this->getActiveSheet()->mergeCells("A".$GLOBALS["HedRow"].':'.$ColEnd.$GLOBALS["HedRow"]);		
		$this->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
		$this->SetFont('A2:A4',"B",11,"","");

		foreach(range('A',$ColEnd) as $columnID) {
			$this->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
			// $this->getActiveSheet()->getStyle($columnID.'6')->getFont()->setBold(true);	
			// $this->getActiveSheet()->getStyle($columnID.'6')->getFont()->setSize(12);	
			// $this->getActiveSheet()->getStyle($columnID.'6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
			

		}


	}

	public function SetOutput($allOutDt){

		$this->getSecurity()->setLockWindows(false);//true for pass
		$this->getSecurity()->setLockStructure(false);//true for pass
		// $this->getSecurity()->setWorkbookPassword("password");

		$this->getActiveSheet()->getProtection()->setSheet(false);//true for pass
		// $this->getActiveSheet()->getProtection()->setPassword("password");


		ob_end_clean();
		$filename = (is_null($allOutDt['title']))? $this->getSheetNames():$allOutDt['title'];
		$filename = $filename[0];
		$filename = strtoupper($filename);
		$filename = $filename."_".date('Y m d');
		$filename = preg_replace('/[\s]+/', '_', $filename);

		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'");
		header("Content-Disposition: attachment; filename=\"".$filename.".xlsx\"");
		header("Cache-Control: max-age=0");

		// Save Excel 2007 file
		$objWriter = PHPExcel_IOFactory::createWriter($allOutDt['data'], 'Excel2007');
		ob_end_clean();
		// $objWriter->save(str_replace('.php', '.xls', __FILE__));
		$objWriter->save("php://output");

	}

	public function LastRowNum(){
		return $this->setActiveSheetIndex()->getHighestRow();
	}

	public function NextRowNum(){
		return $this->setActiveSheetIndex()->getHighestRow()+1;
	}

	public function SetAsNumber($d){
		$cols = explode(",", $d);
		foreach ($cols as  $c) {
			if (!empty($c)) {
				$this->getActiveSheet()->getStyle($c)->getNumberFormat()->setFormatCode('#,##0.00');
				$this->getActiveSheet()->getStyle($c)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			}
		}
	}

	public function SetAsDate($d){
		$cols = explode(",", $d);
		foreach ($cols as  $c) {
			if (!empty($c)) {
				$this->getActiveSheet()->getStyle($c)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
				$this->getActiveSheet()->getStyle($c)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			}
		}
	}	

	public function SetFont($Cels,$sty,$fsiz,$font,$col){
		if (empty($Cels)) {
			return ;
		}

		$cols = str_split($sty);
	// var_dump($cols);exit();
		foreach ($cols as  $sty) {

			if (!empty($sty)) {
				switch ($sty) {
					case 'B':
					$B=true;
					break;
					case 'I':
					$I=true;
					break;	
					case 'S':
					$S=true;
					break;	
					case 'C':
					$C=true;
					break;							
					case 'U':
					$U=PHPExcel_Style_Font::UNDERLINE_SINGLE;
					break;	

					default:
						# code...
					break;
				}
// var_dump($B);exit();
// const UNDERLINE_NONE = 'none';
// const UNDERLINE_DOUBLE = 'double';
// const UNDERLINE_DOUBLEACCOUNTING = 'doubleAccounting';
// const UNDERLINE_SINGLE = 'single';
// const UNDERLINE_SINGLEACCOUNTING = 'singleAccounting';
                 // 'underline' => PHPExcel_Style_Font::UNDERLINE_DOUBLEACCOUNTING,

				$this->getActiveSheet()->getStyle($Cels)->getFont()->applyFromArray(
					array(
						'name'      => $font,
						'bold'      => $B,
						'italic'    => $I,
						'underline' => $U,
						'strike'    => $S,
						'size'  	 => $fsiz,
						'color'     => array(
							'rgb' 	=> $col
							),

						)
					
					);

				if ($C) {
					$this->getActiveSheet()->getStyle($Cels)->applyFromArray(array(
						'alignment' => array(
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							)
						)

					);

				}

			}
		}

		// foreach(range('A',$ColEnd) as $columnID) {
		// 	$this->getActiveSheet()->getStyle($columnID.'4')->getFont()->setBold(true);	
		// 	$this->getActiveSheet()->getStyle($columnID.'4')->getFont()->setSize(12);	
		// 	$this->getActiveSheet()->getStyle($columnID.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
		
		// }
	}

	public function SetBorders($cel){

		$styleArray1 = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				)
			);

		$styleArray2 = array(
			'borders' => array(
				'bottom' => array(
					'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
					)
				)
			);

		// var_dump($rng);exit();
		$this->getActiveSheet()->getStyle($cel)->applyFromArray($styleArray1);
		// $this->getActiveSheet()->getStyle('A1:A5')->applyFromArray($styleArray2);
	}

	public function SetBlank(){
		$this->getActiveSheet()->setCellValue("A".$this->NextRowNum(),"");
	}

	public function setHeading($d="SMTK"){
		$this->getActiveSheet()->setTitle($d);
		$r=$this->LastRowNum();
		$this->getActiveSheet()->setCellValue('A'.$r, $d);
		$this->SetFont('A'.$r,"B",14,"","");
		$GLOBALS["HedRow"]=$r;



// $colIndex = PHPExcel_Cell::columnIndexFromString(2);
//     $colIndex = PHPExcel_Cell::getCurrentAddress();
// var_dump($colIndex);exit();

	}

	public function setMerge($c,$e=""){
		if (empty($e)) {
			$this->getActiveSheet()->mergeCells($c);	
		}
		else
		{
			$ColEnd = $this->setActiveSheetIndex()->getHighestColumn();
			$n = preg_replace('/[A-Z]+/', '', $c);
			$this->getActiveSheet()->mergeCells($c.":".$ColEnd.$n);	
		}
	}

}
