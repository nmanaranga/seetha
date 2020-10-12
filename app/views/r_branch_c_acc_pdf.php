<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
        $this->pdf->setPrintFooter(true);
        //$this->pdf->setPrintHeader(true);
        //print_r($purchase);
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

 		foreach($branch as $ress){
 				$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
 			}
foreach ($purchase as $value){
	  $inv_no=$value->nno;
	  $name=$value->name;
	}

	foreach ($category as $cat){
	  $code=$cat->code;
	  $des=$cat->description;
	}
		
		$this->pdf->setY(22);

    	$this->pdf->SetFont('helvetica', 'BUI',12);
	 	$this->pdf->Cell(0, 5, 'BRANCH CURRENT ACCOUNT CHART',0,false, 'L', 0, '', 0, false, 'M', 'M');
		$this->pdf->Ln();
	 	$x=0;
	 	$y=1;
			 //----check data is available for print ----        
           
		

               
        foreach ($sum as $value) {
        	
        	 if($x==0){
        	 	
        	 	$this->pdf->SetX(16);
				$this->pdf->SetFont('helvetica','B',9);
        	  	$this->pdf->Cell(70, 6,"Cluster :".$value->ref_cl. " - ".$value->description, '0', 0, 'L', 0);
        	 	$this->pdf->Ln();

        	 	$this->pdf->SetY(35);
				$this->pdf->SetX(16);
				$this->pdf->SetFont('helvetica','B',7);
		        $this->pdf->Cell(10, 6,"No", '1', 0, 'C', 0);
		        $this->pdf->Cell(15, 6,"Branch", '1', 0, 'C', 0);
				$this->pdf->Cell(50, 6,"Branch Name", '1', 0, 'C', 0);
		        $this->pdf->Cell(30, 6,"Account Code", '1', 0, 'C', 0);
		        $this->pdf->Cell(70, 6,"Account Name", '1', 0, 'C', 0);
		   
		        $this->pdf->Ln();
        	 	$this->pdf->SetX(16);
 				$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
				$this->pdf->SetFont('helvetica','',8);
                $this->pdf->Cell(10, 6,$y, '1', 0, 'R', 0);
                $this->pdf->Cell(15, 6,$value->ref_bc, '1', 0, 'R', 0);
				$this->pdf->Cell(50, 6,$value->name,'1', 0, 'L', 0);
                $this->pdf->Cell(30, 6,$value->acc_code, '1', 0, 'L', 0);
                $this->pdf->Cell(70, 6,$value->acc_name, '1', 0, 'L', 0);
                $this->pdf->Ln();
               	$x++; $y++;
               	$prev_cl=$value->ref_cl;
               
    	 	}
    	 	else{
	    	 	$current_cl=$value->ref_cl;
	    	 	if($current_cl==$prev_cl){

	        	 	$this->pdf->SetX(16);
	 				$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
					$this->pdf->SetFont('helvetica','',8);
	                $this->pdf->Cell(10, 6,$y, '1', 0, 'R', 0);
	                $this->pdf->Cell(15, 6,$value->ref_bc, '1', 0, 'R', 0);
					$this->pdf->Cell(50, 6,$value->name,'1', 0, 'L', 0);
	                $this->pdf->Cell(30, 6,$value->acc_code, '1', 0, 'L', 0);
	                $this->pdf->Cell(70, 6,$value->acc_name, '1', 0, 'L', 0);
	                $this->pdf->Ln();
	                $prev_cl=$value->ref_cl;
	               	$y++;
	               	$x=1;
	    	 	}
	    	 	$current_cl=$value->ref_cl;
	    	 	if($current_cl!=$prev_cl){
	    	 		$this->pdf->Ln();	
	    	 		$this->pdf->Ln();

	    	 		$this->pdf->SetX(16);
					$this->pdf->SetFont('helvetica','B',9);
		        	$this->pdf->Cell(70, 6,"Cluster :".$value->ref_cl. " - ".$value->description, '0', 0, 'L', 0);
		        	$this->pdf->Ln();
	    	 		
	    	 		
	        	 	if($x==1){

	        	 		

		        	 	//$this->pdf->SetY(35);
						$this->pdf->SetX(16);
						$this->pdf->SetFont('helvetica','B',7);
				        $this->pdf->Cell(10, 6,"No", '1', 0, 'C', 0);
				        $this->pdf->Cell(15, 6,"Branch", '1', 0, 'C', 0);
						$this->pdf->Cell(50, 6,"Branch Name", '1', 0, 'C', 0);
				        $this->pdf->Cell(30, 6,"Account Code", '1', 0, 'C', 0);
				        $this->pdf->Cell(70, 6,"Account Name", '1', 0, 'C', 0);
				   
				        
				        $this->pdf->Ln();
		        	 	$this->pdf->SetX(16);
		 				$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
						$this->pdf->SetFont('helvetica','',8);
		                $this->pdf->Cell(10, 6,$y, '1', 0, 'R', 0);
		                $this->pdf->Cell(15, 6,$value->ref_bc, '1', 0, 'R', 0);
						$this->pdf->Cell(50, 6,$value->name,'1', 0, 'L', 0);
		                $this->pdf->Cell(30, 6,$value->acc_code, '1', 0, 'L', 0);
		                $this->pdf->Cell(70, 6,$value->acc_name, '1', 0, 'L', 0);
		                $this->pdf->Ln();
		               	$x++; $y++;
		               	$prev_cl=$value->ref_cl;


	        	 	}else{

	        	 		$this->pdf->Ln();
		        	 	$this->pdf->SetX(16);
		 				$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
						$this->pdf->SetFont('helvetica','',8);
		                $this->pdf->Cell(10, 6,$y, '1', 0, 'R', 0);
		                $this->pdf->Cell(15, 6,$value->ref_bc, '1', 0, 'R', 0);
						$this->pdf->Cell(50, 6,$value->name,'1', 0, 'L', 0);
		                $this->pdf->Cell(30, 6,$value->acc_code, '1', 0, 'L', 0);
		                $this->pdf->Cell(70, 6,$value->acc_name, '1', 0, 'L', 0);
		                $this->pdf->Ln();
		               	$y++;
		               	$prev_cl=$value->ref_cl;	
	        	 	}
	    	 	}
    	 	}

			}        
	// $this->pdf->footerSet($employee,$amount,$additional,$discount,$user);
	$this->pdf->Output("Branch Current Account Setup".date('Y-m-d').".pdf", 'I');

?>