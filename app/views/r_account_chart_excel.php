<?php  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$this->excel->setActiveSheetIndex(0);


foreach($branch as $ress){
	$this->excel->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}


$this->excel->setHeading("CHART OF ACCOUNT");
$this->excel->SetBlank();



$rtype="default";
$type="default";
$count=(Int)0;



foreach($acc_det as $row){
	$isLeg=false;

	$nrn=$this->excel->NextRowNum();


	$sql_l="SELECT * FROM m_account_type WHERE is_ledger_acc='1'";
	$query_l = $this->db->query($sql_l);
	foreach($query_l->result() as $r){
		if($row->code==$r->code){
			$this->excel->SetFont('A'.$nrn.":".'B'.$nrn,'B',"11","",'0000FF');
		}
	} 



	$this->excel->getActiveSheet()->setCellValue('A'.$nrn, $row->code);
	$this->excel->getActiveSheet()->setCellValue('B'.$nrn, $row->heading);
	$this->excel->SetBorders('A'.$nrn.":".'L'.$nrn,"outline");

	$sql2="SELECT * FROM `m_account_type` t WHERE t.`control_category`='".$row->code."'"; 
	$query=$this->db->query($sql2)->result();
	foreach($query as $row2){

		$nrn=$this->excel->NextRowNum();
		foreach($query_l->result() as $r){
			if($row2->code==$r->code){
				$this->excel->SetFont('C'.$nrn.":".'D'.$nrn,'B',"11","",'0000FF');
			}
		}

		$this->excel->getActiveSheet()->setCellValue('C'.$nrn, $row2->code);
		$this->excel->getActiveSheet()->setCellValue('D'.$nrn, $row2->heading);
		$this->excel->SetBorders('C'.$nrn.":".'L'.$nrn,"outline");

		$sql3="SELECT * FROM `m_account_type` t WHERE t.`control_category`='".$row2->code."'"; 
		$query=$this->db->query($sql3)->result();
		foreach($query as $row3){
			$nrn=$this->excel->NextRowNum();
			foreach($query_l->result() as $r){
				if($row3->code==$r->code){
					$this->excel->SetFont('E'.$nrn.":".'F'.$nrn,'B',"11","",'0000FF');
				}
			}



			$this->excel->getActiveSheet()->setCellValue('E'.$nrn, $row3->code);
			$this->excel->getActiveSheet()->setCellValue('F'.$nrn, $row3->heading);
			$this->excel->SetBorders('E'.$nrn.":".'L'.$nrn,"outline");

			$sql4="SELECT * FROM `m_account_type` t WHERE t.`control_category`='".$row3->code."'"; 
			$query=$this->db->query($sql4)->result();
			foreach($query as $row4){

				$nrn=$this->excel->NextRowNum();

				foreach($query_l->result() as $r){
					if($row4->code==$r->code){
						$this->excel->SetFont('G'.$nrn.":".'H'.$nrn,'B',"11","",'0000FF');
					}
				}

				
				
				$this->excel->getActiveSheet()->setCellValue('G'.$nrn, $row4->code);
				$this->excel->getActiveSheet()->setCellValue('H'.$nrn, $row4->heading);
				$this->excel->SetBorders('G'.$nrn.":".'L'.$nrn,"outline");

				$sql5="SELECT * FROM `m_account_type` t WHERE t.`control_category`='".$row4->code."'"; 
				$query=$this->db->query($sql5)->result();
				foreach($query as $row5){

					$nrn=$this->excel->NextRowNum();

					foreach($query_l->result() as $r){
						if($row5->code==$r->code){
							$this->excel->SetFont('I'.$nrn.":".'J'.$nrn,'B',"11","",'0000FF');
						}
					}

					

					$this->excel->getActiveSheet()->setCellValue('I'.$nrn, $row5->code);
					$this->excel->getActiveSheet()->setCellValue('J'.$nrn, $row5->heading);
					$this->excel->SetBorders('I'.$nrn.":".'L'.$nrn,"outline");

					$sql6="SELECT * FROM `m_account_type` t WHERE t.`control_category`='".$row5->code."'"; 
					$query=$this->db->query($sql6)->result();
					foreach($query as $row6){

						$nrn=$this->excel->NextRowNum();

						foreach($query_l->result() as $r){
							if($row6->code==$r->code){
								$this->excel->SetFont('K'.$nrn.":".'L'.$nrn,'B',"11","",'0000FF');
							}
						}


						
						$this->excel->getActiveSheet()->setCellValue('K'.$nrn, $row6->code);
						$this->excel->getActiveSheet()->setCellValue('L'.$nrn, $row6->heading);
						$this->excel->SetBorders('K'.$nrn.":".'L'.$nrn,"outline");
					}
				}
			}
		}
	}
}




































// $r=$this->excel->NextRowNum();
// $this->excel->getActiveSheet()->setCellValue('A'.$r,"Item Code");
// $this->excel->getActiveSheet()->setCellValue('B'.$r,"Item Name");
// $this->excel->getActiveSheet()->setCellValue('C'.$r,"Item Model");
// $this->excel->getActiveSheet()->setCellValue('D'.$r,"Batch No");
// $this->excel->getActiveSheet()->setCellValue('E'.$r,"Qty");
// $this->excel->getActiveSheet()->setCellValue('F'.$r,"Total Qty");
// $this->excel->getActiveSheet()->setCellValue('G'.$r,"Purchase Price");        
// $this->excel->getActiveSheet()->setCellValue('H'.$r,"Last Price");

// $this->excel->SetBorders('A'.$r.":".'H'.$r);
// $this->excel->SetFont('A'.$r.":".'H'.$r,"BC",12,"","");



// $key=$this->excel->NextRowNum();
// // $this->excel->SetAsNumber("G,H");
// foreach($item_det as $row){
// 	$tot_price=$row->qty*$row->p_price;
// 	$cost=(int)$row->qty*(float)$row->purchase_price;
// 	$this->excel->getActiveSheet()->setCellValue('A'.$key, $row->item);
// 	$this->excel->getActiveSheet()->setCellValue('B'.$key, $row->description);
// 	$this->excel->getActiveSheet()->setCellValue('C'.$key, $row->model);
// 	$this->excel->getActiveSheet()->setCellValue('D'.$key, $row->batch_no);
// 	$this->excel->getActiveSheet()->setCellValue('E'.$key, $row->qty);  
// 	$this->excel->getActiveSheet()->setCellValue('F'.$key, $row->item_tot);
// 	$this->excel->getActiveSheet()->setCellValue('G'.$key, $row->b_price);
// 	$this->excel->getActiveSheet()->setCellValue('H'.$key, $row->b_min);

// 	// $this->excel->SetBorders('A'.$key.":".'J'.$key);

// 	$key++;
// }











// $this->excel->SetBorders();
$this->excel->AllSettings(array("borders"=>"all",));
$this->excel->SetOutput(array("data"=>$this->excel,"title"=>$header));
