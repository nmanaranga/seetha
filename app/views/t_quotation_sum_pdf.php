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
			$cus_name=$cus->cus_name;
			$cus_address=$cus->cus_address;
			$cus_id=$cus->cus_id;
		}

		foreach($session as $ses){
			$invoice_no=$session[0].$session[1].$session[2];
		}

			$this->pdf->setY(20);

			$orgin_print=$_POST['org_print'];
		if($orgin_print=="1"){
            $this->pdf->SetFont('helvetica', 'BU', 12);
		 	$this->pdf->Cell(0, 5, $r_type.'Quotation ',0,false, 'C', 0, '', 0, false, 'M', 'M');
		 }else{
		 	 $this->pdf->SetFont('helvetica', 'BU', 12);
		 	$this->pdf->Cell(0, 5, $r_type.'Quotation (Duplicate) ',0,false, 'C', 0, '', 0, false, 'M', 'M');
		 }
		 	$this->pdf->SetFont('helvetica', '', 8);
		 	$this->pdf->setY(28);

		 	$this->pdf->Cell(30, 1, 'Invoice No.', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $invoice_no, '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1, "Bill to Customer", '0', 0, 'L', 0);
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, 'Invoice Date', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $ddate, '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Name", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $cus_name, '0', 0, 'L', 0);
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "ID No", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $cus_id, '0', 0, 'L', 0);
		 	
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, '', '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(10, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Address", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $cus_address, '0', 0, 'L', 0);
		 	$this->pdf->Ln();$this->pdf->SetY(45);
			$this->pdf->SetFont('helvetica','B',8);
            
            $this->pdf->Cell(10, 6,"", '1', 0, 'C', 0);
			$this->pdf->Cell(28, 6,"", '1', 0, 'C', 0);
            $this->pdf->Cell(45, 6,"", '1', 0, 'C', 0);
            $this->pdf->Cell(10, 6,"", '1', 0, 'C', 0);
            $this->pdf->Cell(20, 6,"", '1', 0, 'C', 0);
            $this->pdf->Cell(15, 6,"", '1', 0, 'C', 0);
            $this->pdf->Cell(15, 6,"", '1', 0, 'C', 0);
            $this->pdf->Cell(17, 6,"", '1', 0, 'C', 0);
            $this->pdf->Cell(30, 6,"", '1', 0, 'C', 0);
            $this->pdf->Ln();
            $x=1;
            $val_p='';
            foreach($items as $row){
            $val_p=$row->validity_period;
            $this->pdf->GetY();
			$this->pdf->SetFont('helvetica','',8);

			$bb=$this->pdf->getNumLines($row->description, 45); 
	        $heigh=6*$bb;
	            
			$this->pdf->MultiCell(10, $heigh, $x, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
   			$this->pdf->MultiCell(28, $heigh, $row->code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(45, $heigh, $row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(10, $heigh, $row->qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, $heigh, $row->price, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(15, $heigh, $row->discountp, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(15, $heigh, $row->discount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(17, $heigh, $row->net_amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(30, $heigh, $row->item_discription, 1, 'L', 0, 1, '', '', true, 0, false, true, 0);
           
   //        
            $x++;
            }
	$this->pdf->footerSetQ($employee,$amount,$additional,$discount,$user);

	$this->pdf->Cell(190, 6,"Validity Period : ". $val_p, '1', 0, 'L', 0);

	$this->pdf->Output("Cash Sales_".date('Y-m-d').".pdf", 'I');

?>