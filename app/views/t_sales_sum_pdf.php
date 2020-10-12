<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//$this->pdf->setPrintHeader($header,$type,$duration);

$this->pdf->setPrintFooter(true);
$this->pdf->setPrintHeader(true);
$this->pdf->setPrintHeader($header,$type); 

        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3
 		foreach($branch as $ress){
 			
 			$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
		}
		$cus_name=$cus_address="";
		foreach($customer as $cus){
			$cus_name=$cus->name;
			$cus_address=$cus->address1." ".$cus->address2." ".$cus->address3;
			$cus_id=$cus->code;
		}
			$this->pdf->setY(20);
            
            $this->pdf->SetFont('helvetica', 'IBU', 8);
		 	//$this->pdf->Cell(50, 1, $r_type.' SALES INVOICE', '0', 0, 'L', 0); 
		 	$this->pdf->Cell(0, 5, $r_type.' SALES INVOICE',0,false, 'C', 0, '', 0, false, 'M', 'M');

		 	$this->pdf->SetFont('helvetica', 'IB', 8);
		 	$this->pdf->setY(25);
		 	$this->pdf->Cell(30, 1, 'Invoice No.', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $qno, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1, "Bill to Customer", '0', 0, 'L', 0);
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, 'Invoice Date', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $dt, '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Name", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $cus_name, '0', 0, 'L', 0);
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "ID No", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $cus_id, '0', 0, 'L', 0);
		 	
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Address", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $cus_address, '0', 0, 'L', 0);
		 	$this->pdf->Ln();$this->pdf->SetY(45);
			$this->pdf->SetFont('helvetica','B',8);
                        $this->pdf->Cell(10, 6,"", '1', 0, 'C', 0);
			$this->pdf->Cell(20, 6,"", '1', 0, 'C', 0);
                        $this->pdf->Cell(55, 6,"", '1', 0, 'C', 0);
                        $this->pdf->Cell(15, 6,"", '1', 0, 'C', 0);
                        $this->pdf->Cell(10, 6,"", '1', 0, 'C', 0);
                        $this->pdf->Cell(20, 6,"", '1', 0, 'C', 0);
                        $this->pdf->Cell(20, 6,"", '1', 0, 'C', 0);
                        $this->pdf->Cell(30, 6,"", '1', 0, 'C', 0);
                        $this->pdf->Ln();
                        $x=1;
                        foreach($items as $row){
                        $this->pdf->GetY();
						$this->pdf->SetFont('helvetica','IB',7);
                        $this->pdf->Cell(10, 6,$x, '1', 0, 'C', 0);
                        $this->pdf->SetFont('helvetica','IB',6);
						$this->pdf->Cell(20, 6,$row->code, '1', 0, 'L', 0);
                        $this->pdf->Cell(55, 6,$row->description, '1', 0, 'L', 0);
                        $this->pdf->SetFont('helvetica','IB',7);
                        $this->pdf->Cell(15, 6,$row->model, '1', 0, 'C', 0);
                        $this->pdf->Cell(10, 6,$row->qty, '1', 0, 'R', 0);
                        $this->pdf->Cell(20, 6,$row->price, '1', 0, 'R', 0);
                        $this->pdf->Cell(20, 6,$row->discount, '1', 0, 'R', 0);
                        $this->pdf->Cell(30, 6,$row->cost, '1', 0, 'R', 0);
                        $this->pdf->Ln();
                        $x++;
                        }
	$this->pdf->footerSet($employee,$amount,$additional,$discount,$user);

	$this->pdf->Output("Cash Sales_".date('Y-m-d').".pdf", 'I');

?>