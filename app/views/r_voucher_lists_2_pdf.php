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

		
			 $this->pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
    
   			$this->pdf->setY(22);
   			$this->pdf->setY(22);$this->pdf->SetFont('helvetica', 'BUI',12);
		 	$this->pdf->Cell(0, 5, 'Voucher List 02- (Supplier Payment)	 	',0,false, 'L', 0, '', 0, false, 'M', 'M');
		 	$this->pdf->Ln();
		 	//$this->pdf->Ln();

		 	$this->pdf->setY(25);
    		$this->pdf->Ln(); 

		 	

    
    //----------------------------------------------------------------------------------------------------



			   $this->pdf->Ln();
			   $this->pdf->Ln();

		    foreach($r_branch_name as $row){
		        
		       $branch_name=$row->name;
		       $cluster_name=$row->description;
		       $cl_id=$row->code;
		       $bc_id=$row->bc;

			}
			
			//============================================================
			
			//Filter to given cluster & branch
			if(($cluster1!="0") && ($branch1!="0"))
			{
				   if($dfrom!=""){
				   	 $this->pdf->SetFont('helvetica', '',10);
				   	 $this->pdf->Cell(0, 5, 'Date   From '. $dfrom.' To '.$dto ,0,false, 'L', 0, '', 0, false, 'M', 'M');
				     $this->pdf->Ln();
				   }
				  

				   $this->pdf->SetX(20);
				   $this->pdf->setY(40);$this->pdf->SetFont('helvetica', '', 10);
				   $this->pdf->Ln();
				   $this->pdf->Cell(30, 6,'Cluster', '0', 0, 'L', 0);
				   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
				   $this->pdf->Cell(120, 6,"$cl_id - $cluster_name", '0', 0, 'L', 0);
				   $this->pdf->Ln();

				   $this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
				   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
				   $this->pdf->Cell(20, 6,"$bc_id - $branch_name", '0', 0, 'L', 0);
				   $this->pdf->Ln();

			 
				  	if($acc!=""){
					   $this->pdf->SetFont('helvetica', '', 10);
					   $this->pdf->Cell(30, 6,'Supplier', '0', 0, 'L', 0);
					   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
					   $this->pdf->Cell(55, 6,$acc."  -  ".$acc_des, '0', 0, 'L', 0);
					   $this->pdf->Ln();
					  }
				
					if($t_no_from!=""){
					   $this->pdf->SetFont('helvetica', '', 10);
					   $this->pdf->Cell(30, 6,'Transaction Range     ', '0', 0, 'L', 0);
					   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
					   $this->pdf->Cell(15, 6,"From  -  ".$t_no_from, '0', 0, 'L', 0);

					}
			  
					if($t_no_to!="")
					{
					  $this->pdf->Cell(40, 6,"   To  -  ".$t_no_to, '0', 0, 'L', 0);
					  $this->pdf->Ln(); 
					}
		  

					 // Headings-------------------------------------
					$this->pdf->SetFont('helvetica','B',10);
		            $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
		            $this->pdf->Cell(25, 6,"Voucher No", '1', 0, 'C', 0);
		            $this->pdf->Cell(30, 6,"Supplier Id", '1', 0, 'C', 0);  
		            $this->pdf->Cell(40, 6,"Supplier Name", '1', 0, 'C', 0);
		            $this->pdf->Cell(25, 6,"Cach", '1', 0, 'C', 0);
				    $this->pdf->Cell(25, 6,"Cheque", '1', 0, 'C', 0);
		            $this->pdf->Cell(25, 6,"Credit Card", '1', 0, 'C', 0);
		            $this->pdf->Cell(25, 6,"Debit Note", '1', 0, 'C', 0);
		            $this->pdf->Cell(25, 6,"Discount", '1', 0, 'C', 0);
		            $this->pdf->Cell(30, 6,"Total Settlement", '1', 0, 'C', 0);
		            $this->pdf->Ln();


					foreach ($sum as $value) {	
				
						$this->pdf->SetX(15);
						$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
						$this->pdf->SetFont('helvetica','',9);

						
			            $bb=$this->pdf->getNumLines($value->description, 40); 

			            
			              $heigh=6*$bb;
			            
			            // Deatils loop---------------------------------
			            $this->pdf->MultiCell(20, $heigh, $value->ddate, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(25, $heigh, $value->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(30, $heigh, $value->acc_code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(40, $heigh, $value->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_cash,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_receive_chq,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_credit,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_dnote,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(25, $heigh, number_format($value->discount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(30, $heigh, number_format($value->settle_amount,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
			            
			      
					    $cash+=$value->pay_cash;
					    $cheque+=$value->pay_receive_chq;
					    $credit+=$value->pay_credit;
					    $dnote+=$value->pay_dnote;
					    $discount+=$value->discount;
					    $amt+=$value->settle_amount;                   	
			                   
					}

					$this->pdf->SetX(15);
					$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
					$this->pdf->SetFont('helvetica','B',10);

		            $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
		            $this->pdf->Cell(25, 6,"", '0', 0, 'L', 0);
		            $this->pdf->Cell(70, 6,"Total", '0', 0, 'C', 0);

		            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
					$this->pdf->SetFont('helvetica','B',9);

		            $this->pdf->Cell(25, 6, number_format($cash,2), '1', 0, 'R', 0);
					$this->pdf->Cell(25, 6, number_format($cheque,2),'1', 0, 'R', 0);
		            $this->pdf->Cell(25, 6, number_format($credit,2), '1', 0, 'R', 0);
		            $this->pdf->Cell(25, 6, number_format($dnote,2), '1', 0, 'R', 0);
		            $this->pdf->Cell(25, 6, number_format($discount,2), '1', 0, 'R', 0);
		            $this->pdf->Cell(30, 6, number_format($amt,2), '1', 0, 'R', 0);
		           
		            $this->pdf->Ln();
		            $this->pdf->Ln();
		            $this->pdf->Ln();

            
		            $cash=$cheque=$credit=$dnote=$amt=$discount=0;
		           foreach ($cancled as $value) 
		            {
			            
		            	$x=0;
		            }
		            	if($x==0)
		            	{
		            	$this->pdf->SetFont('helvetica', 'BU',12);
					 	$this->pdf->Cell(0, 5, 'Cancelled Voucher List 02	 ',0,false, 'L', 0, '', 0, false, 'M', 'M');
					 	$this->pdf->Ln();
					 	$this->pdf->Ln();
			
						$this->pdf->SetX(15);
						 // Headings

						$this->pdf->SetFont('helvetica','B',10);
			            $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
			            $this->pdf->Cell(25, 6,"Voucher No", '1', 0, 'C', 0);
			            $this->pdf->Cell(30, 6,"Supplier Id", '1', 0, 'C', 0);  
		            	$this->pdf->Cell(40, 6,"Supplier Name", '1', 0, 'C', 0);
			            $this->pdf->Cell(25, 6,"Cach", '1', 0, 'C', 0);
					    $this->pdf->Cell(25, 6,"Cheque", '1', 0, 'C', 0);
			            $this->pdf->Cell(25, 6,"Credit Card", '1', 0, 'C', 0);
			            $this->pdf->Cell(25, 6,"Debit Note", '1', 0, 'C', 0);
			            $this->pdf->Cell(25, 6,"Discount", '1', 0, 'C', 0);
			            $this->pdf->Cell(30, 6,"Total Settlement", '1', 0, 'C', 0);
			            $this->pdf->Ln();
			            $x++;
			          }
			            
			          foreach ($cancled as $value) 
		              {
			            //detail loop
			           
				
							$this->pdf->SetX(15);
							$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
							$this->pdf->SetFont('helvetica','',9);

							
				            $bb=$this->pdf->getNumLines($value->description, 40); 

				            
				              $heigh=6*$bb;
				            
				            // Deatils loop
				            $this->pdf->MultiCell(20, $heigh, $value->ddate, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, $value->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(30, $heigh, $value->acc_code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(40, $heigh, $value->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_cash,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					        $this->pdf->MultiCell(25, $heigh, number_format($value->pay_receive_chq,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_credit,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_dnote,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, number_format($value->discount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(30, $heigh, number_format($value->settle_amount,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
				            
				      
						    $cash+=$value->pay_cash;
						    $cheque+=$value->pay_receive_chq;
						    $credit+=$value->pay_credit;
						    $dnote+=$value->pay_dnote;
						    $discount+=$value->discount;
						    $amt+=$value->settle_amount;

					    

		            } 


		            	
		            	      
				
						$this->pdf->SetX(15);
						$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
						$this->pdf->SetFont('helvetica','B',10);

			            $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
			            $this->pdf->Cell(25, 6,"", '0', 0, 'L', 0);
			            $this->pdf->Cell(70, 6,"Total", '0', 0, 'C', 0);

			            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
						$this->pdf->SetFont('helvetica','B',9);

			            $this->pdf->Cell(25, 6, number_format($cash,2), '1', 0, 'R', 0);
						$this->pdf->Cell(25, 6, number_format($cheque,2),'1', 0, 'R', 0);
			            $this->pdf->Cell(25, 6, number_format($credit,2), '1', 0, 'R', 0);
			            $this->pdf->Cell(25, 6, number_format($dnote,2), '1', 0, 'R', 0);
			            $this->pdf->Cell(25, 6, number_format($discount,2), '1', 0, 'R', 0);
			            $this->pdf->Cell(30, 6, number_format($amt,2), '1', 0, 'R', 0);
			            $this->pdf->Ln(); 
			   	//}

			}
						
			//------------------ End 1-----------------------------

			//==================================================================================================================
				
			//
			if(($cluster1!="0") && ($branch1=="0"))//check cluster only
			{
				
				 //first row--------------------------
				$b1=0; //$b1-1st row
				$xx=0; //row counts
				$old_branch="";
		        foreach ($sum as $value) 
		        {
		           	$row_count=$value->counts;

		           	if($b1==0)
				   	{
				   	   
					   if($dfrom!=""){
					   	 $this->pdf->SetX(20);
					   	 $this->pdf->setY(40);
					   	 $this->pdf->SetFont('helvetica', '',10);
					   	 $this->pdf->Cell(0, 5, 'Date   From '. $dfrom.' To '.$dto ,0,false, 'L', 0, '', 0, false, 'M', 'M');
					     $this->pdf->Ln();
					   }

					   if($acc!=""){
						   
						   $this->pdf->SetFont('helvetica', '', 10);
						   $this->pdf->Cell(30, 6,'Supplier', '0', 0, 'L', 0);
						   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
						   $this->pdf->Cell(55, 6,$acc."  -  ".$acc_des, '0', 0, 'L', 0);
						   $this->pdf->Ln();
					   }
				
					   if($t_no_from!=""){
						   
						   $this->pdf->SetFont('helvetica', '', 10);
						   $this->pdf->Cell(30, 6,'Transaction Range     ', '0', 0, 'L', 0);
						   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
						   $this->pdf->Cell(20, 6,"From  -  ".$t_no_from, '0', 0, 'L', 0);

						}
		  
						if($t_no_to!="")
						{
						  $this->pdf->Cell(45, 6,"  To  -  ".$t_no_to, '0', 0, 'L', 0);
						  $this->pdf->Ln(); 

						}

					   $this->pdf->SetX(20);
					   $this->pdf->setY(52);$this->pdf->SetFont('helvetica', 'B', 10);
					   $this->pdf->Ln();
					   $this->pdf->Cell(30, 6,'Cluster', '0', 0, 'L', 0);
					   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
					   $this->pdf->Cell(120, 6,$value->cl." - ".$value->cl_description, '0', 0, 'L', 0);
					   $this->pdf->Ln();

				   	   $this->pdf->SetFont('helvetica', 'B', 9);
					   $this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
					   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
					   $this->pdf->Cell(20, 6,$value->bc." - ".$value->bc_name, '0', 0, 'L', 0);
					   $this->pdf->Ln();

					   // Headings
						$this->pdf->SetFont('helvetica','B',10);
			            $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
			            $this->pdf->Cell(25, 6,"Voucher No", '1', 0, 'C', 0);
			            $this->pdf->Cell(60, 6,"Description", '1', 0, 'C', 0);
			            $this->pdf->Cell(30, 6,"Cach", '1', 0, 'C', 0);
					    $this->pdf->Cell(30, 6,"Cheque", '1', 0, 'C', 0);
			            $this->pdf->Cell(25, 6,"Credit Card", '1', 0, 'C', 0);
			            $this->pdf->Cell(25, 6,"Debit Note", '1', 0, 'C', 0);
			            $this->pdf->Cell(25, 6,"Discount", '1', 0, 'C', 0);
			            $this->pdf->Cell(30, 6,"Total Settlement", '1', 0, 'C', 0);
			            $this->pdf->Ln();

						$this->pdf->SetX(15);
						$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
						$this->pdf->SetFont('helvetica','',9);

						
			            $bb=$this->pdf->getNumLines($value->memo, 60); 
			            $heigh=6*$bb;
			            
			            // Deatils loop
			            $this->pdf->MultiCell(20, $heigh, $value->ddate, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(25, $heigh, $value->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(60, $heigh, $value->memo, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(30, $heigh, number_format($value->pay_cash,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(30, $heigh, number_format($value->pay_receive_chq,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_credit,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_dnote,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(25, $heigh, number_format($value->discount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(30, $heigh, number_format($value->settle_amount,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
			            
			      
					    $cash+=$value->pay_cash;
					    $cheque+=$value->pay_receive_chq;
					    $credit+=$value->pay_credit;
					    $dnote+=$value->pay_dnote;
					    $discount+=$value->discount;
					    $amt+=$value->settle_amount; 
					   	$b1++; 
					   	$old_branch=$value->bc;
					   	$cl1=$values->cl;
					   	$xx++;
					   
			     	}

			     	 else //other rows
		   			{
		   				$branch=$value->bc;
		   				if($old_branch==$branch)
			   			{
			   				$this->pdf->SetX(15);
							$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
							$this->pdf->SetFont('helvetica','',9);

							
				            $bb=$this->pdf->getNumLines($value->memo, 60); 
				            $heigh=6*$bb;
				            
				            // Deatils loop
				            $this->pdf->MultiCell(20, $heigh, $value->ddate, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, $value->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(60, $heigh, $value->memo, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(30, $heigh, number_format($value->pay_cash,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(30, $heigh, number_format($value->pay_receive_chq,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_credit,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_dnote,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, number_format($value->discount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(30, $heigh, number_format($value->settle_amount,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
				            
				      
						    $cash+=$value->pay_cash;
						    $cheque+=$value->pay_receive_chq;
						    $credit+=$value->pay_credit;
						    $dnote+=$value->pay_dnote;
						    $discount+=$value->discount;
						    $amt+=$value->settle_amount;
						    $old_branch=$value->bc;
						    $branch_total=0;
						    $xx++;
						  
			   			}

			   			//---------------------next branch--------------------------------------
			   			if($old_branch!=$branch)
		   				{
		   					if($branch_total==0)
						  	{
							  	$this->pdf->SetX(15);
								$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
								$this->pdf->SetFont('helvetica','B',10);

					            $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
					            $this->pdf->Cell(25, 6,"", '0', 0, 'L', 0);
					            $this->pdf->Cell(60, 6,"Total", '0', 0, 'C', 0);

					            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
								$this->pdf->SetFont('helvetica','B',9);

					            $this->pdf->Cell(30, 6, number_format($cash,2), '1', 0, 'R', 0);
								$this->pdf->Cell(30, 6, number_format($cheque,2),'1', 0, 'R', 0);
					            $this->pdf->Cell(25, 6, number_format($credit,2), '1', 0, 'R', 0);
					            $this->pdf->Cell(25, 6, number_format($dnote,2), '1', 0, 'R', 0);
					            $this->pdf->Cell(25, 6, number_format($discount,2), '1', 0, 'R', 0);
					            $this->pdf->Cell(30, 6, number_format($amt,2), '1', 0, 'R', 0);
					            $this->pdf->Ln(); 
					            $branch_total=1;

					   		//---------------End branch total--------------------------------------

					        }    
					        
					   		   $cash=$cheque=$credit=$dnote=$discount=$amt=0;
					   		   $this->pdf->SetFont('helvetica', 'B', 9);
							   $this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
							   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
							   $this->pdf->Cell(20, 6,$value->bc." - ".$value->bc_name, '0', 0, 'L', 0);
							   $this->pdf->Ln();

							   // Headings
								$this->pdf->SetFont('helvetica','B',10);
					            $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
					            $this->pdf->Cell(25, 6,"Voucher No", '1', 0, 'C', 0);
					            $this->pdf->Cell(60, 6,"Description", '1', 0, 'C', 0);
					            $this->pdf->Cell(30, 6,"Cach", '1', 0, 'C', 0);
							    $this->pdf->Cell(30, 6,"Cheque", '1', 0, 'C', 0);
					            $this->pdf->Cell(25, 6,"Credit Card", '1', 0, 'C', 0);
					            $this->pdf->Cell(25, 6,"Debit Note", '1', 0, 'C', 0);
					            $this->pdf->Cell(25, 6,"Discount", '1', 0, 'C', 0);
					            $this->pdf->Cell(30, 6,"Total Settlement", '1', 0, 'C', 0);
					            $this->pdf->Ln();

								$this->pdf->SetX(15);
								$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
								$this->pdf->SetFont('helvetica','',9);

								
					            $bb=$this->pdf->getNumLines($value->memo, 60); 
					            $heigh=6*$bb;
					            
					            // Deatils loop
					            $this->pdf->MultiCell(20, $heigh, $value->ddate, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(25, $heigh, $value->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(60, $heigh, $value->memo, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(30, $heigh, number_format($value->pay_cash,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(30, $heigh, number_format($value->pay_receive_chq,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_credit,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_dnote,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(25, $heigh, number_format($value->discount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(30, $heigh, number_format($value->settle_amount,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
					            
					      
							    $cash+=$value->pay_cash;
							    $cheque+=$value->pay_receive_chq;
							    $credit+=$value->pay_credit;
							    $dnote+=$value->pay_dnote;
							    $discount+=$value->discount;
							    $amt+=$value->settle_amount; 
							   	$xx++;
							   	$old_branch=$value->bc;
		   				}//end different branch 1row

				   	}
		   		}
		   		//var_dump($row_count."==".$xx);
		   		//exit();
		        if($row_count==$xx)// differant branch final branch total
	   				{
	   				
				  	$this->pdf->SetX(15);
					$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
					$this->pdf->SetFont('helvetica','B',10);

		            $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
		            $this->pdf->Cell(25, 6,"", '0', 0, 'L', 0);
		            $this->pdf->Cell(60, 6,"Total", '0', 0, 'C', 0);

		            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
					$this->pdf->SetFont('helvetica','B',9);
					
		            
		            $this->pdf->Cell(30, 6, number_format($cash,2), '1', 0, 'R', 0);
					$this->pdf->Cell(30, 6, number_format($cheque,2),'1', 0, 'R', 0);
		            $this->pdf->Cell(25, 6, number_format($credit,2), '1', 0, 'R', 0);
		            $this->pdf->Cell(25, 6, number_format($dnote,2), '1', 0, 'R', 0);
		            $this->pdf->Cell(25, 6, number_format($discount,2), '1', 0, 'R', 0);
		            $this->pdf->Cell(30, 6, number_format($amt,2), '1', 0, 'R', 0);
		            $this->pdf->Ln(); 
		               
			   		//---------------End branch total---------------------

		            }
		         
			}// end of checking cluster
			//===============================End Cluster selecting=========================================

			if(($cluster1=="0") && ($branch1=="0"))
			{
				$xx=0; //row counts
				$old_branch="";
				$b1=0; //$b1-1st row
				foreach ($sum as $value) 
				{
				   
				   $row_count=$value->counts;

				   
				   if($b1==0)
				   {
					   if($dfrom!=""){
					   	 $this->pdf->SetX(20);
					   	 $this->pdf->setY(40);
					   	 $this->pdf->SetFont('helvetica', '',10);
					   	 $this->pdf->Cell(0, 5, 'Date   From '. $dfrom.' To '.$dto ,0,false, 'L', 0, '', 0, false, 'M', 'M');
					     $this->pdf->Ln();
					   }

					   if($acc!=""){
					   
						   $this->pdf->SetFont('helvetica', '', 8);
						   $this->pdf->Cell(30, 6,'Supplier', '0', 0, 'L', 0);
						   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
						   $this->pdf->Cell(55, 6,$acc."  -  ".$acc_des, '0', 0, 'L', 0);
						   $this->pdf->Ln();
				   		}

				   		if($t_no_from!=""){
					   
						   $this->pdf->SetFont('helvetica', '', 8);
						   $this->pdf->Cell(30, 6,'Transaction Range     ', '0', 0, 'L', 0);
						   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
						   $this->pdf->Cell(30, 6,"From  -  ".$t_no_from, '0', 0, 'L', 0);

						}

						if($t_no_to!="")
						{
						  $this->pdf->Cell(45, 6,"To  -  ".$t_no_to, '0', 0, 'L', 0);
						  $this->pdf->Ln(); 
						}


					   $this->pdf->SetX(20);
					   $this->pdf->setY(50);$this->pdf->SetFont('helvetica', 'B', 10);
					   $this->pdf->Ln();
					   $this->pdf->Cell(30, 6,'Cluster', '0', 0, 'L', 0);
					   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
					   $this->pdf->Cell(120, 6,$value->cl." - ".$value->cl_description, '0', 0, 'L', 0);
					   $this->pdf->Ln();
					   
					//-----------------------first row--------------------------
					
				   	   $this->pdf->SetFont('helvetica', 'B', 9);
					   $this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
					   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
					   $this->pdf->Cell(20, 6,$value->bc." - ".$value->bc_name, '0', 0, 'L', 0);
					   $this->pdf->Ln();

					   // Headings
						$this->pdf->SetFont('helvetica','B',10);
			            $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
			            $this->pdf->Cell(25, 6,"Voucher No", '1', 0, 'C', 0);
			            $this->pdf->Cell(60, 6,"Description", '1', 0, 'C', 0);
			            $this->pdf->Cell(30, 6,"Cach", '1', 0, 'C', 0);
					    $this->pdf->Cell(30, 6,"Cheque", '1', 0, 'C', 0);
			            $this->pdf->Cell(25, 6,"Credit Card", '1', 0, 'C', 0);
			            $this->pdf->Cell(25, 6,"Debit Note", '1', 0, 'C', 0);
			            $this->pdf->Cell(25, 6,"Discount", '1', 0, 'C', 0);
			            $this->pdf->Cell(30, 6,"Total Settlement", '1', 0, 'C', 0);
			            $this->pdf->Ln();

						$this->pdf->SetX(15);
						$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
						$this->pdf->SetFont('helvetica','',9);

						
			            $bb=$this->pdf->getNumLines($value->memo, 60); 
			            $heigh=6*$bb;
			            
			            // Deatils loop
			            $this->pdf->MultiCell(20, $heigh, $value->ddate, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(25, $heigh, $value->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(60, $heigh, $value->memo, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(30, $heigh, number_format($value->pay_cash,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(30, $heigh, number_format($value->pay_receive_chq,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_credit,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_dnote,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(25, $heigh, number_format($value->discount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
			            $this->pdf->MultiCell(30, $heigh, number_format($value->settle_amount,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
			            
			      
					    $cash+=$value->pay_cash;
					    $cheque+=$value->pay_receive_chq;
					    $credit+=$value->pay_credit;
					    $dnote+=$value->pay_dnote;
					    $discount+=$value->discount;
					    $amt+=$value->settle_amount; 
					   	 
					   	$old_branch=$value->bc;
					   	$cl2=$value->cl;
					   	$nno=$value->nno;
					   	$branch_total1=0;
					   	$b1++;
					   	$xx++; 
				   	}
				 	//-----------------------end first row--------------------------
				   	$cl1=$value->cl;
			     	$N_branch=$value->bc;
			     	$nno1=$value->nno;
				 	if($cl1==$cl2)
				 	{
				 		
				 		if($old_branch==$N_branch)
				 		{
				 			if($nno1!=$nno)
		     				{
			     				$this->pdf->SetX(15);
								$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
								$this->pdf->SetFont('helvetica','',9);

								
					            $bb=$this->pdf->getNumLines($value->memo, 60); 
					            $heigh=6*$bb;
				            
					            // Deatils loop
					            $this->pdf->MultiCell(20, $heigh, $value->ddate, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(25, $heigh, $value->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(60, $heigh, $value->memo, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(30, $heigh, number_format($value->pay_cash,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(30, $heigh, number_format($value->pay_receive_chq,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_credit,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_dnote,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(25, $heigh, number_format($value->discount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
					            $this->pdf->MultiCell(30, $heigh, number_format($value->settle_amount,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
					            
				      
							    $cash+=$value->pay_cash;
							    $cheque+=$value->pay_receive_chq;
							    $credit+=$value->pay_credit;
							    $dnote+=$value->pay_dnote;
							    $discount+=$value->discount;
							    $amt+=$value->settle_amount;
							    $old_branch=$value->bc;
							    $cl2=$value->cl;
							    $branch_total1=0;
						    
		     					$xx++;
		     				}

		     // 				var_dump($row_count."==".$xx);
							// exit();
		     				
				 		}//same branch 

				 		if($old_branch!=$N_branch)
				 		{
					 		
					 		if($branch_total1==0)
						  	{
							  	$this->pdf->SetX(15);
								$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
								$this->pdf->SetFont('helvetica','B',10);

					            $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
					            $this->pdf->Cell(25, 6,"", '0', 0, 'L', 0);
					            $this->pdf->Cell(60, 6,"Total", '0', 0, 'C', 0);

					            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
								$this->pdf->SetFont('helvetica','B',9);

					            $this->pdf->Cell(30, 6, number_format($cash,2), '1', 0, 'R', 0);
								$this->pdf->Cell(30, 6, number_format($cheque,2),'1', 0, 'R', 0);
					            $this->pdf->Cell(25, 6, number_format($credit,2), '1', 0, 'R', 0);
					            $this->pdf->Cell(25, 6, number_format($dnote,2), '1', 0, 'R', 0);
					            $this->pdf->Cell(25, 6, number_format($discount,2), '1', 0, 'R', 0);
					            $this->pdf->Cell(30, 6, number_format($amt,2), '1', 0, 'R', 0);
					            $this->pdf->Ln();
					            $this->pdf->Ln();
					            $this->pdf->Ln();
						   		$this->pdf->Ln();
						   		//$this->pdf->SetY(80); 
					            $branch_total1=1;

					   		    //---------------End branch total--------------------------------------

					        }
					    	
					       $cash=$cheque=$credit=$dnote=$discount=$amt=0;

					       $this->pdf->SetFont('helvetica', 'B', 9);
						   $this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
						   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
						   $this->pdf->Cell(20, 6,$value->bc." - ".$value->bc_name, '0', 0, 'L', 0);
						   $this->pdf->Ln();

						   // Headings
							$this->pdf->SetFont('helvetica','B',10);
				            $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
				            $this->pdf->Cell(25, 6,"Voucher No", '1', 0, 'C', 0);
				            $this->pdf->Cell(60, 6,"Description", '1', 0, 'C', 0);
				            $this->pdf->Cell(30, 6,"Cach", '1', 0, 'C', 0);
						    $this->pdf->Cell(30, 6,"Cheque", '1', 0, 'C', 0);
				            $this->pdf->Cell(25, 6,"Credit Card", '1', 0, 'C', 0);
				            $this->pdf->Cell(25, 6,"Debit Note", '1', 0, 'C', 0);
				            $this->pdf->Cell(25, 6,"Discount", '1', 0, 'C', 0);
				            $this->pdf->Cell(30, 6,"Total Settlement", '1', 0, 'C', 0);
				            $this->pdf->Ln();

							$this->pdf->SetX(15);
							$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
							$this->pdf->SetFont('helvetica','',9);

							
				            $bb=$this->pdf->getNumLines($value->memo, 60); 
				            $heigh=6*$bb;
				            
				            // Deatils loop
				            $this->pdf->MultiCell(20, $heigh, $value->ddate, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, $value->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(60, $heigh, $value->memo, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(30, $heigh, number_format($value->pay_cash,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(30, $heigh, number_format($value->pay_receive_chq,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_credit,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_dnote,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, number_format($value->discount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(30, $heigh, number_format($value->settle_amount,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
				            
				      		
						    
						    $cash+=$value->pay_cash;
						    $cheque+=$value->pay_receive_chq;
						    $credit+=$value->pay_credit;
						    $dnote+=$value->pay_dnote;
						    $discount+=$value->discount;
						    $amt+=$value->settle_amount;
						    $old_branch=$value->bc;
						    $cl2=$value->cl;
						    $branch_total1=0;
						    $nno=$value->nno;
			     			$xx++;
				 		}
				 	}//check same branch & cluster
				 	
				 	if($cl1!=$cl2)
				 	{
				 	   if($branch_total1==0)
					  	{
						  	$this->pdf->SetX(15);
							$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
							$this->pdf->SetFont('helvetica','B',10);

				            $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
				            $this->pdf->Cell(25, 6,"", '0', 0, 'L', 0);
				            $this->pdf->Cell(60, 6,"Total", '0', 0, 'C', 0);

				            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
							$this->pdf->SetFont('helvetica','B',9);

				            $this->pdf->Cell(30, 6, number_format($cash,2), '1', 0, 'R', 0);
							$this->pdf->Cell(30, 6, number_format($cheque,2),'1', 0, 'R', 0);
				            $this->pdf->Cell(25, 6, number_format($credit,2), '1', 0, 'R', 0);
				            $this->pdf->Cell(25, 6, number_format($dnote,2), '1', 0, 'R', 0);
				            $this->pdf->Cell(25, 6, number_format($discount,2), '1', 0, 'R', 0);
				            $this->pdf->Cell(30, 6, number_format($amt,2), '1', 0, 'R', 0);
				            $this->pdf->Ln();
				            
					   		$y1=0; 
				            $branch_total1=1;

				   		    //---------------End branch total--------------------------------------
				        }

				        $cash=$cheque=$credit=$dnote=$discount=$amt=0;
				       
				 	   if($y1==0)
					   { 
						   
						   $this->pdf->SetX(20);
						   //$this->pdf->setY(70);
						   $this->pdf->SetFont('helvetica', 'B', 10);
						   $this->pdf->Ln();
						   $this->pdf->Cell(30, 6,'Cluster', '0', 0, 'L', 0);
						   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
						   $this->pdf->Cell(120, 6,$value->cl." - ".$value->cl_description, '0', 0, 'L', 0);
						   $this->pdf->Ln();
						   
						//-----------------------first row--------------------------
						
					   	   $this->pdf->SetFont('helvetica', 'B', 9);
						   $this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
						   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
						   $this->pdf->Cell(20, 6,$value->bc." - ".$value->bc_name, '0', 0, 'L', 0);
						   $this->pdf->Ln();

						   // Headings
							$this->pdf->SetFont('helvetica','B',10);
				            $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
				            $this->pdf->Cell(25, 6,"Voucher No", '1', 0, 'C', 0);
				            $this->pdf->Cell(60, 6,"Description", '1', 0, 'C', 0);
				            $this->pdf->Cell(30, 6,"Cach", '1', 0, 'C', 0);
						    $this->pdf->Cell(30, 6,"Cheque", '1', 0, 'C', 0);
				            $this->pdf->Cell(25, 6,"Credit Card", '1', 0, 'C', 0);
				            $this->pdf->Cell(25, 6,"Debit Note", '1', 0, 'C', 0);
				            $this->pdf->Cell(25, 6,"Discount", '1', 0, 'C', 0);
				            $this->pdf->Cell(30, 6,"Total Settlement", '1', 0, 'C', 0);
				            $this->pdf->Ln();

							$this->pdf->SetX(15);
							$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
							$this->pdf->SetFont('helvetica','',9);

							
				            $bb=$this->pdf->getNumLines($value->memo, 60); 
				            $heigh=6*$bb;
				            
				            // Deatils loop
				            $this->pdf->MultiCell(20, $heigh, $value->ddate, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, $value->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(60, $heigh, $value->memo, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(30, $heigh, number_format($value->pay_cash,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(30, $heigh, number_format($value->pay_receive_chq,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_credit,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, number_format($value->pay_dnote,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(25, $heigh, number_format($value->discount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
				            $this->pdf->MultiCell(30, $heigh, number_format($value->settle_amount,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
				            
				      
						    $cash+=$value->pay_cash;
						    $cheque+=$value->pay_receive_chq;
						    $credit+=$value->pay_credit;
						    $dnote+=$value->pay_dnote;
						    $discount+=$value->discount;
						    $amt+=$value->settle_amount; 
						   	 
						   	$old_branch=$value->bc;
						   	$cl2=$value->cl;
						   	$nno=$value->nno;
						   	$branch_total1=0;
						   	$y1++;
						   	$xx++; 
					   	}
					 	//-----------------------end first row--------------------------
				 	}	
				 	
			    }//end foreach
			   
			     if($row_count==$xx)// differant branch final branch total
	   				{
	   				
				  	$this->pdf->SetX(15);
					$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
					$this->pdf->SetFont('helvetica','B',10);

		            $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
		            $this->pdf->Cell(25, 6,"", '0', 0, 'L', 0);
		            $this->pdf->Cell(60, 6,"Total", '0', 0, 'C', 0);

		            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
					$this->pdf->SetFont('helvetica','B',9);
					
		            
		            $this->pdf->Cell(30, 6, number_format($cash,2), '1', 0, 'R', 0);
					$this->pdf->Cell(30, 6, number_format($cheque,2),'1', 0, 'R', 0);
		            $this->pdf->Cell(25, 6, number_format($credit,2), '1', 0, 'R', 0);
		            $this->pdf->Cell(25, 6, number_format($dnote,2), '1', 0, 'R', 0);
		            $this->pdf->Cell(25, 6, number_format($discount,2), '1', 0, 'R', 0);
		            $this->pdf->Cell(30, 6, number_format($amt,2), '1', 0, 'R', 0);
		            $this->pdf->Ln(); 
		            $this->pdf->Ln();
		            $this->pdf->Ln();
		               
			   		//---------------End branch total---------------------
		            }
					$this->pdf->SetX(15);
		           //$this->pdf->setY(50);
					$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
					$this->pdf->SetFont('helvetica','B',11);

		            $this->pdf->Cell(90, 6, "Total Vouchers -    ".$row_count, '0', 0, 'R', 0);
		            
		            
				//var_dump("all");
				//exit();
			}
			//============================================================

   //-------------------------------------------------------------------------------------------------------------                 
	//$this->pdf->footerSet($employee,$amount,$additional,$discount,$user);
	$this->pdf->Output("Voucher List-Supplier Payment".date('Y-m-d').".pdf", 'I');

?>