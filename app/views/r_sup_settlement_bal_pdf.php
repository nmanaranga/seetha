<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);
$this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

		foreach($branch as $ress){
			$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
		}

		
		$this->pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

		$this->pdf->setY(20);
		$this->pdf->SetFont('helvetica', 'BUI',12);
		$this->pdf->Cell(0, 5, 'Supplier Settlement Balance Details	 	',0,false, 'L', 0, '', 0, false, 'M', 'M');
		$this->pdf->Ln();
		$this->pdf->Ln();

    //----------------------------------------------------------------------------------------------------


		foreach($r_branch_name as $row){

			$branch_name=$row->name;
			$cluster_name=$row->description;
			$cl_id=$row->code;
			$bc_id=$row->bc;

		}


		if($dfrom!=""){
			$this->pdf->SetFont('helvetica', '',10);
			$this->pdf->Cell(0, 5, 'Date   From '. $dfrom.' To '.$dto ,0,false, 'L', 0, '', 0, false, 'M', 'M');
			$this->pdf->Ln();
		}


		$this->pdf->SetFont('helvetica', '', 10);
		$this->pdf->Cell(30, 6,'Cluster', '0', 0, 'L', 0);
		$this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
		$this->pdf->Cell(120, 6,"$cluster1", '0', 0, 'L', 0);
		$this->pdf->Ln();

		$this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
		$this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
		$this->pdf->Cell(20, 6,"$branch1", '0', 0, 'L', 0);
		$this->pdf->Ln();


		if($acc!=""){
			$this->pdf->SetFont('helvetica', '', 10);
			$this->pdf->Cell(30, 6,'Supplier', '0', 0, 'L', 0);
			$this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
			$this->pdf->Cell(55, 6,$acc."  -  ".$acc_des, '0', 0, 'L', 0);
			$this->pdf->Ln();
		}

					 // Headings-------------------------------------
		$this->pdf->SetFont('helvetica','B',9);
		$this->pdf->Cell(25, 6,"Date", '1', 0, 'C', 0);
		$this->pdf->Cell(70, 6,"Transaction", '1', 0, 'C', 0);
		$this->pdf->Cell(25, 6,"Debit Note No", '1', 0, 'C', 0);
		$this->pdf->Cell(20, 6,"Trans No", '1', 0, 'C', 0);
		$this->pdf->Cell(20, 6,"DR", '1', 0, 'C', 0);
		$this->pdf->Cell(20, 6,"CR", '1', 0, 'C', 0);
		$this->pdf->Ln();
		$code="default";

		foreach ($sum as $value) {
			$heigh=5;	

			if($code=="default" || $code!=$value->trans_no){
				$this->pdf->ln();
				$this->pdf->SetX(15);
				$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
				$this->pdf->SetFont('helvetica','',8);
				$this->pdf->HaveMorePages($heigh);
				$this->pdf->MultiCell(25, $heigh, $value->ddate, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(70, $heigh, $value->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(25, $heigh, $value->trans_no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(20, $heigh, $value->sub_trans_no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(20, $heigh, number_format($value->dr,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(20, $heigh, number_format($value->cr,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
				
			}else{

				$this->pdf->SetX(15);
				$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
				$this->pdf->SetFont('helvetica','',8);
				$this->pdf->HaveMorePages($heigh);
				$this->pdf->MultiCell(25, $heigh, $value->ddate, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(70, $heigh, $value->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(25, $heigh, $value->trans_no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(20, $heigh, $value->sub_trans_no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(20, $heigh, number_format($value->dr,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(20, $heigh, number_format($value->cr,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
			}
			$code=$value->trans_no;
			$totdr+=$value->dr;
			$totcr+=$value->cr;
		}

		$this->pdf->SetFont('helvetica','B',9);
		$this->pdf->Cell(140, 6,"Total", '0', 0, 'R', 0);
		$this->pdf->Cell(20, 6,number_format($totdr,2), 'TB', 0, 'R', 0);
		$this->pdf->Cell(20, 6,number_format($totcr,2), 'TB', 0, 'R', 0);
		$this->pdf->Ln();

		$this->pdf->SetFont('helvetica','B',9);
		$this->pdf->Cell(140, 6,"", '0', 0, 'R', 0);
		$this->pdf->Cell(20, 6,"Balance", '0', 0, 'R', 0);
		$this->pdf->Cell(20, 6,number_format($totdr-$totcr,2), 'TB', 0, 'R', 0);
		$this->pdf->Ln();

		$this->pdf->Output("Voucher List-Supplier Payment".date('Y-m-d').".pdf", 'I');

		?>