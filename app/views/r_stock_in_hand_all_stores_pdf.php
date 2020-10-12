<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
        $this->pdf->setPrintFooter(true);
        $this->pdf->setPrintHeader(true);
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage("P","A4");   // L or P amd page type A4 or A3

 		foreach($branch as $ress){//Common
 		 			$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
 				}

			$this->pdf->Ln(3);
        	$this->pdf->SetFont('helvetica', 'IBU',12);
		 	$this->pdf->Cell(0, 5, 'Stock In Hand - All Stores   ',0,false, 'L', 0, '', 0, false, 'M', 'M');
			//var_dump($cluster);exit();
			
			$this->pdf->SetFont('helvetica', 'N', 10);
			$this->pdf->Ln(3);
			$this->pdf->Cell(12, 6, "As At :  ", '0', 0, 'L', 0);					
			$this->pdf->Cell(25, 6, $dto ,'0', 0, 'L', 0);

			$item_des=$item="";

	 		foreach($r_st_i_h as $res){
	 			$item=$res->item;
				$item_des=$res->description;
			}		
			

				$this->pdf->Ln();
			 	$this->pdf->SetFont('helvetica', 'B', 10);
			 	$this->pdf->Cell(15, 6,"Item : ", '0', 0, 'L', 0);
			 	$this->pdf->Cell(0, 6,$item." - ".$item_des, '0', 0, 'L', 0);
				$this->pdf->Ln();
				$this->pdf->Ln();

			 	$this->pdf->Cell(30, 6,"Store Code", '1', 0, 'C', 0);
			 	$this->pdf->Cell(40, 6,"Store Name", '1', 0, 'C', 0);
			 	$this->pdf->Cell(25, 6,"Qty", '1', 0, 'C', 0);



			 	$this->pdf->Ln();

				$this->pdf->SetFont('helvetica', 'N', 10);

			$tot_amo=0;

	 		foreach($r_st_i_h as $res){

			        $this->pdf->MultiCell(30, 6, $res->store_code, $border=1, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
			        $this->pdf->MultiCell(40, 6, $res->Sto_name, $border=1, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
			        $this->pdf->MultiCell(25, 6, $res->su_qty, $border=1, $align='R', $fill=false, $ln=1, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
			       	$tot_amo+=$res->su_qty;
			}		
					


					$this->pdf->SetFont('helvetica', 'B', 10);		
/*			        $this->pdf->MultiCell(30, 6, "Number Of Suppliers", $border=0, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
			        $this->pdf->MultiCell(15, 6, $NoS, $border=0, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
			        $this->pdf->MultiCell(10, 6, "", $border=0, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
			        $this->pdf->MultiCell(10, 6, "", $border=0, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
			        $this->pdf->MultiCell(25, 6, "", $border=0, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
*/			        $this->pdf->MultiCell(30, 6, "", $border=0, $align='L', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
			        $this->pdf->MultiCell(40, 6, "Net Amount    ", $border=0, $align='R', $fill=false, $ln=0, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);
			        $this->pdf->MultiCell(25, 6, number_format($tot_amo), $border='TB', $align='R', $fill=false, $ln=1, $x=($this->pdf->GetX()), $y=$this->pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=6, $valign='M', $fitcell=true);



	$this->pdf->Output("Employee Details_".date('Y-m-d').".pdf", 'I');

?>
