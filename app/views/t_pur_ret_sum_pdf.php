<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//$this->pdf->setPrintHeader($header,$type,$duration);

$this->pdf->setPrintFooter(true);
$this->pdf->setPrintHeader(true);
$this->pdf->setPrintHeader($header,$type); 

$this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3
		foreach($branch as $ress){

			$this->pdf->headerSet4($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
		}
		$cus_name=$cus_address="";
		foreach($customer as $cus){
			$cus_name=$cus->name;
			$cus_address=$cus->address1." ".$cus->address2." ".$cus->address3;
			$cus_id=$cus->code;
		}

		foreach($vender as $ven){
			$v_name=$ven->name;
			$v_address=$ven->address1." ".$cus->address2." ".$cus->address3;
			
		}

		foreach($session as $ses){
			$invoice_no=$session[0].$session[1]."PR".$session[2];
		}

		foreach($additional as $res2){
			$grn_no = $res2->grn_no;
			$memo = $res2->memo;

		}
		

		$this->pdf->setY(15);

		$this->pdf->SetFont('helvetica', 'BU', 10);
		$this->pdf->Ln();
		 	//$this->pdf->Cell(50, 1, $r_type.' SALES INVOICE', '0', 0, 'L', 0); 
		$orgin_print=$_POST['org_print'];

		if($duplicate=="1"){
			$this->pdf->Cell(0, 5,'PURCHASE RETURN NOTE',0,false, 'C', 0, '', 0, false, 'M', 'M');
		}else{
			$this->pdf->Cell(0, 5,'PURCHASE RETURN NOTE (DUPLICATE)',0,false, 'C', 0, '', 0, false, 'M', 'M');
		}

		$this->pdf->SetFont('helvetica', '', 8);
		$this->pdf->setY(25);
		$this->pdf->Ln();
		$this->pdf->Cell(30, 1, 'Invoice No.', '0', 0, 'L', 0);
		$this->pdf->Cell(20, 1, $invoice_no, '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, "Bill No", '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, $drn, '0', 0, 'L', 0);
		$this->pdf->Ln();

		$this->pdf->Cell(30, 1, 'Date', '0', 0, 'L', 0);
		$this->pdf->Cell(20, 1, $dt, '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, "Vendor's Name", '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, $v_name, '0', 0, 'L', 0);
		$this->pdf->Ln();

		$this->pdf->Cell(30, 1, 'GRN No.', '0', 0, 'L', 0);
		$this->pdf->Cell(20, 1, $grn_no, '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, "Address", '0', 0, 'L', 0);
		$this->pdf->Cell(30, 1, $v_address, '0', 0, 'L', 0);

		$this->pdf->Ln();
		$this->pdf->Ln();
		$this->pdf->SetY(51);



		$x=1;
		$code="default";
		$batch="default";


		foreach($items as $row){
			$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
			if($code!='default' && $code==$row->code && $batch==$row->batch_no)
			{
				if($row->sub_item!="")
				{	
					$this->pdf->SetFont('helvetica','',7);
					$heigh=5*(max(1,$this->pdf->getNumLines($row->des,55)));
					$this->pdf->HaveMorePages($heigh);
					$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(25, $heigh, $row->sub_item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(55, $heigh, $row->des, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(10, $heigh, $row->sub_qty, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->Ln();
					$x=$x-1;
				}
			}
			else
			{
				$this->pdf->GetY();
				$this->pdf->SetFont('helvetica','B',7);
				$heigh=5*(max(1,$this->pdf->getNumLines($row->description,50)));
				$this->pdf->HaveMorePages($heigh);
				$this->pdf->MultiCell(10, $heigh, $x, 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(25, $heigh, $row->code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(50, $heigh, $row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(25, $heigh, $row->model, 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(10, $heigh, $row->batch_no, 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(10, $heigh, $row->qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(20, $heigh, number_format($row->price,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(20, $heigh, number_format($row->discount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(20, $heigh, number_format($row->price*$row->qty,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->Ln();

				$tot_qty+=$row->qty;

				$ss="";
				foreach ($serial as $rows) {
					if($row->code==$rows->item)
					{
						$ss=$rows->serial_no;
					}

				}

				if($ss!=""){
					$all_serial="";

					foreach ($serial as $rows) {
						//var_dump($row->batch_no."=".$rows->batch_no."===".$row->code."==".$rows->item);
						if($row->code==$rows->item && $row->batch_no==$rows->batch_no)
						{					
							$all_serial=$all_serial.$rows->serial_no."   ";
						}
					}

					$this->pdf->GetY();
					$this->pdf->SetFont('helvetica','',7);
					$heigh=5*(max(1,$this->pdf->getNumLines($all_serial,50)));
					$this->pdf->HaveMorePages($heigh);

					$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(25, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(50, $heigh, $all_serial, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(25, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->Ln();

					if($row->sub_item!="")
					{
						$aa = $this->pdf->getNumLines($row->des, 50);
						$heigh=5*$aa;
						$this->pdf->MultiCell(10, $heigh,"", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(35, $heigh,$row->sub_item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(50, $heigh,$row->des, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(25, $heigh,"", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(10, $heigh,$row->sub_qty, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(18, $heigh,"", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(17, $heigh,"",1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(20, $heigh,"",  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
					}
				}else{




					if($row->sub_item!="")
					{
						$aa = $this->pdf->getNumLines($row->des, 50);
						$heigh=5*$aa;
						$this->pdf->MultiCell(10, $heigh,"", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(35, $heigh,$row->sub_item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(50, $heigh,$row->des, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(25, $heigh,"", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(10, $heigh,$row->sub_qty, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(18, $heigh,"", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(17, $heigh,"",1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(20, $heigh,"",  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
					}



				}
			}

			$code=$row->code;
			$batch=$row->batch_no;
			$x++;
		}







			// $this->pdf->SetFont('helvetica','B',8);
   //                      $this->pdf->Cell(10, 6,"", '1', 0, 'C', 0);
			// $this->pdf->Cell(20, 6,"", '1', 0, 'C', 0);
   //                      $this->pdf->Cell(50, 6,"", '1', 0, 'C', 0);
   //                      $this->pdf->Cell(25, 6,"", '1', 0, 'C', 0);
   //                      $this->pdf->Cell(10, 6,"", '1', 0, 'C', 0);
   //                      $this->pdf->Cell(20, 6,"", '1', 0, 'C', 0);
   //                      $this->pdf->Cell(20, 6,"", '1', 0, 'C', 0);
   //                      $this->pdf->Cell(25, 6,"", '1', 0, 'C', 0);
   //                      $this->pdf->Ln();
   //                      $x=1;
   //                      foreach($items as $row){
   //                      $this->pdf->GetY();
   //                      $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
			// 			$this->pdf->SetFont('helvetica','',7);
   //                      $this->pdf->Cell(10, 6,$x, '1', 0, 'C', 0);
			// 			$this->pdf->Cell(20, 6,$row->code, '1', 0, 'L', 0);
   //                      $this->pdf->Cell(50, 6,$row->description, '1', 0, 'L', 0);
   //                      $this->pdf->Cell(25, 6,$row->model, '1', 0, 'C', 0);
   //                      $this->pdf->Cell(10, 6,$row->qty, '1', 0, 'R', 0);
   //                      $this->pdf->Cell(20, 6,$row->price, '1', 0, 'R', 0);
   //                      $this->pdf->Cell(20, 6,$row->discount, '1', 0, 'R', 0);
   //                      $this->pdf->Cell(25, 6,$row->prices, '1', 0, 'R', 0);
   //                      $this->pdf->Ln();
   //                      $x++;
   //                      }

		$this->pdf->SetX(2);
		$this->pdf->SetFont('helvetica','B',9);
		$this->pdf->Cell(110, 6,"", '0', 0, 'C', 0);
		$this->pdf->Cell(24, 6,"Tot Qty", '0', 0, 'C', 0);
		$this->pdf->Cell(10, 6,$tot_qty, 'TB', 0, 'R', 0);
		$this->pdf->Ln(6);    

		$this->pdf->footerSet15($memo,$employee,$amount,$additional,$discount,$user,$netString);

		$this->pdf->Output("Cash Sales_".date('Y-m-d').".pdf", 'I');

		?>