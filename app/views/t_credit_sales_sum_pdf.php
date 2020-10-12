<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//$this->pdf->setPrintHeader($header,$type,$duration);
// echo '<pre>'.print_r($items,true).'</pre>';
//exit;
$this->pdf->setPrintFooter(true);
$this->pdf->setPrintHeader(true);
$this->pdf->setPrintHeader($header,$type); 

	
		$tot_free_item=(float)0;

		foreach($free_item as $free){
			$free_price = $free->price;
			$tot_free_item+=(float)$free_price;
		}


        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage("L","A5");   // L or P amd page type A4 or A3
 		foreach($branch as $ress){
 			
 			$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
		}
		$cus_name=$cus_address="";
		foreach($customer as $cus){
			$cus_name=$cus->name;
			$cus_address=$cus->address1." ".$cus->address2." ".$cus->address3;
			$cus_id=$cus->code;
		}

		foreach($session as $ses){
			$invoice_no=$session[0].$session[1].'CR'.$session[2];
		}

		foreach($credit_sum as $csum){
			$bname=$csum->cus_name;
			$baddress=$csum->cus_address;
			$bdo_no=$csum->do_no;
			$rcpt_no=$csum->receipt_no;
			$memo = $csum->memo;
		}
			
			$this->pdf->setY(20);
            
            $this->pdf->SetFont('helvetica', 'BU', 10);
		 	
		 	if($duplicate=="1"){
				$this->pdf->Cell(0, 5, $r_type.' INVOICE',0,false, 'R', 0, '', 0, false, 'M', 'M');
			}else{
				$this->pdf->Cell(0, 5, $r_type.' INVOICE (DUPLICATE)',0,false, 'R', 0, '', 0, false, 'M', 'M');
			}
			
		 	$this->pdf->SetFont('helvetica', 'B', 9);
		 	$this->pdf->setY(23);
		 	$this->pdf->Cell(30, 1, 'Invoice No.', '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1, $invoice_no, '0', 0, 'L', 0);
		 	$this->pdf->SetFont('helvetica', '', 8);
		 	$this->pdf->Cell(5, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1, "Bill to Customer", '0', 0, 'L', 0);
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, 'Invoice Date', '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1, $dt, '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Name", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1,$bname , '0', 0, 'L', 0);
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, 'Customer ID', '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1, $cus_id, '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Address", '0', 0, 'L', 0);		 	
		 	$this->pdf->Cell(30, 1, $baddress, '0', 0, 'L', 0);
		 	
		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, 'Customer Name', '0', 0, 'L', 0);
		 	$this->pdf->Cell(60, 1, $cus_name, '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Do No", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $bdo_no, '0', 0, 'L', 0);

		 	$this->pdf->Ln();

		 	$this->pdf->Cell(30, 1, 'Customer Address', '0', 0, 'L', 0);
		 	//$this->pdf->MultiCell(60, 1,  $cus_address, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
		 	$this->pdf->Cell(60, 1, $cus_address, '0', 0, 'L', 0);
		 	$this->pdf->Cell(5, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Receipt No", '0', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, $rcpt_no, '0', 0, 'L', 0);

		 	if($gr_id!=""){
		 		$this->pdf->Ln();
				$this->pdf->Cell(30, 1, 'Group', '0', 0, 'L', 0);
			 	$this->pdf->Cell(60, 1, $gr_id." - ".$gr_des, '0', 0, 'L', 0);
			 	$this->pdf->Cell(5, 1, "", '0', 0, 'L', 0);
			 	$this->pdf->Cell(1, 1, "", '0', 0, 'L', 0);
			 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
			 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	}
		 	
		 	$this->pdf->Ln();
		 	$this->pdf->SetY(45);
		 	$this->pdf->setX(10);
		 	$this->pdf->HaveMorePages(6);
		 	$this->pdf->setX(10);
			$this->pdf->SetFont('helvetica','B',8);
            $this->pdf->Cell(10, 6,"", '1', 0, 'C', 0);
			$this->pdf->Cell(35, 6,"", '1', 0, 'C', 0);
            $this->pdf->Cell(55, 6,"", '1', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"", '1', 0, 'C', 0);
            $this->pdf->Cell(10, 6,"", '1', 0, 'C', 0);
            $this->pdf->Cell(18, 6,"", '1', 0, 'C', 0);
            $this->pdf->Cell(17, 6,"", '1', 0, 'C', 0);
            $this->pdf->Cell(20, 6,"", '1', 0, 'C', 0);
            $this->pdf->Ln();

            $x=1;
            $code="default";
            $batch_no="default";
           
            foreach($items as $row){
            	$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

	            if($code!='default' && $code==$row->code && $batch_no==$row->batch_no)
	            {
	            	if($row->sub_item!="")
	     			{	
	     				$this->pdf->setX(10);
	            		$this->pdf->SetFont('helvetica','',7);
	            		$aa = $this->pdf->getNumLines($row->des, 55);
	        			$heigh=5*$aa;
	        			$this->pdf->HaveMorePages($heigh);
	        			$this->pdf->setX(10);
				        $this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				    	$this->pdf->MultiCell(35, $heigh, $row->sub_item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
						$this->pdf->MultiCell(55, $heigh, $row->des, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				        $this->pdf->MultiCell(25, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				        $this->pdf->MultiCell(10, $heigh, $row->sub_qty, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				        $this->pdf->MultiCell(18, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				        $this->pdf->MultiCell(17, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				        $this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				        $this->pdf->Ln();
				        $x=$x-1;
	                
                	}	
	            	
	            }
	            else
	            {
	            	$this->pdf->GetY();
	            	$this->pdf->setX(10);
					$this->pdf->SetFont('helvetica','B',7);
					$aa = $this->pdf->getNumLines($row->description, 55);
	        		$heigh=5*$aa;
	        		$this->pdf->HaveMorePages($heigh);
	        		$this->pdf->setX(10);
                	$this->pdf->MultiCell(10, $heigh, $x, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			    	$this->pdf->MultiCell(35, $heigh, $row->code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					$this->pdf->MultiCell(55, $heigh, $row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			        $this->pdf->MultiCell(25, $heigh, $row->model, 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
			        $this->pdf->MultiCell(10, $heigh, $row->qty, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			        $this->pdf->MultiCell(18, $heigh, number_format($row->price,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			        $this->pdf->MultiCell(17, $heigh, number_format($row->discount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			        $this->pdf->MultiCell(20, $heigh, number_format($row->amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			        $this->pdf->Ln();
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
    		if($row->code==$rows->item)
	 		{					
	 			$all_serial=$all_serial.$rows->serial_no."   ";
 			}
		}
		
	
	        $this->pdf->GetY();
	        $this->pdf->setX(10);
			$this->pdf->SetFont('helvetica','',7);
	        $aa = $this->pdf->getNumLines($all_serial, 55);
	        $heigh=5*$aa;
	        $this->pdf->HaveMorePages($heigh);
	        $this->pdf->setX(10);
	    	$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	    	$this->pdf->MultiCell(35, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			$this->pdf->MultiCell(55, $heigh, $all_serial, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	        $this->pdf->MultiCell(25, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	        $this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	        $this->pdf->MultiCell(18, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	        $this->pdf->MultiCell(17, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	        $this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
	        $this->pdf->Ln();

	        if($row->sub_item!="")
	     	{
	     		$this->pdf->setX(10);
		        $this->pdf->SetFont('helvetica','',7);
		        $aa = $this->pdf->getNumLines($des, 55);
	        	$heigh=5*$aa;
	        	$this->pdf->HaveMorePages($heigh);
	        	$this->pdf->setX(10);
				$this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		    	$this->pdf->MultiCell(35, $heigh, $row->sub_item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(55, $heigh, $row->des, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		        $this->pdf->MultiCell(25, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		        $this->pdf->MultiCell(10, $heigh, $row->sub_qty, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		        $this->pdf->MultiCell(18, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		        $this->pdf->MultiCell(17, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		        $this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		        $this->pdf->Ln();
	     	}

		}else{

            if($row->sub_item!="")
            {
            	$this->pdf->setX(10);
            	$this->pdf->SetFont('helvetica','',7);
            	$aa = $this->pdf->getNumLines($des, 55);
	        	$heigh=5*$aa;
	        	$this->pdf->HaveMorePages($heigh);
	        	$this->pdf->setX(10);
                $this->pdf->MultiCell(10, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		    	$this->pdf->MultiCell(35, $heigh, $row->sub_item, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				$this->pdf->MultiCell(55, $heigh, $row->des, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		        $this->pdf->MultiCell(25, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		        $this->pdf->MultiCell(10, $heigh, $row->sub_qty, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		        $this->pdf->MultiCell(18, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		        $this->pdf->MultiCell(17, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		        $this->pdf->MultiCell(20, $heigh, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
		        $this->pdf->Ln();
            }
	    }
	}

	            $code=$row->code;
	            $batch_no=$row->batch_no;
	            $x++;
}

	$this->pdf->HaveMorePages(6);
	$this->pdf->footer_set_credit_sales($employee,$amount,$additional,$discount_total,$user,$ins_payment,$credit_card,$tot_free_item,$cheque_detail,$credit_card_sum,$other1,$other2,$additional_tot,$memo);


	$this->pdf->Output("Credit Sales_".date('Y-m-d').".pdf", 'I');

?>