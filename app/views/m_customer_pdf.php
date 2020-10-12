<?php
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
        $this->pdf->setPrintFooter(true);
        //print_r($data);
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

 		foreach($branch as $ress){
 		 			$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
 				}

 		foreach($data as $d){
 			$code=$d->code;
 			$name=$d->name;
 			$company_name=$d->company_name;
 			$address1=$d->address1;
 			$address2=$d->address2;
 			$address3=$d->address3;
 			$email=$d->email;
 			$mobile=$d->mobile;
 			$salary=$d->salary;
 			$nationality=$d->nationality;
 			$area=$d->area;
 			$town=$d->town;
 			$tp=$d->tp;
 			$dob=$d->dob;
 			$Occupation=$d->Occupation ; 
 			$category=$d->category;
 			$credit_period=$d->credit_period;
 			$credit_limit=$d->credit_limit;
 			$tax_reg_no=$d->tax_reg_no;

 		}

			$this->pdf->setY(20);
        	$this->pdf->SetFont('helvetica', 'BU', 8);

         	$this->pdf->SetFont('helvetica', 'BU', 12);
         	$this->pdf->Cell(150, 1, "Main", '0', 0, 'C', 0);


         	$this->pdf->Ln();$this->pdf->Ln();

		 	$this->pdf->SetFont('helvetica', 'B', 10);
		 	$this->pdf->setY(25);
		 	$this->pdf->Cell(50, 1,"Code : ", '1', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $code, '1', 0, 'L', 0);
		 	$this->pdf->Cell(30, 1, "Name : ", '1', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, $name, '1', 0, 'L', 0);

			$this->pdf->Ln();
            $this->pdf->Cell(50, 1, "Company Name : ", '1', 0, 'L', 0);
            $this->pdf->Cell(100, 1, $company_name, '1', 0, 'L', 0);

            $this->pdf->Ln();
            $this->pdf->Cell(50, 1, "Address : ", '1', 0, 'L', 0);            
            $this->pdf->Cell(45, 1, $address1, '1', 0, 'L', 0);
            $this->pdf->Cell(10, 1, ",", '0', 0, 'C', 0);
            $this->pdf->Cell(45, 1, $address2, '1', 0, 'L', 0);

            $this->pdf->Ln();
 			$this->pdf->Cell(50, 1, "", '1', 0, 'L', 0);
 			$this->pdf->Cell(100, 1, $address3, '1', 0, 'L', 0);

 			$this->pdf->Ln();
 			$this->pdf->Cell(150, 1, "__________________________________
 				_________________________________________", '0', 0, 'L', 0);

 			$this->pdf->Ln();
 			$this->pdf->Ln();
 			$this->pdf->Cell(50, 1, "Email : ", '1', 0, 'L', 0);
 			$this->pdf->Cell(100, 1, $email, '1', 0, 'L', 0);

 			$this->pdf->Ln();
 			$this->pdf->Cell(40, 1, "Telepone : ", '1', 0, 'L', 0);            
            $this->pdf->Cell(35, 1, $tp, '1', 0, 'L', 0);
            $this->pdf->Cell(40, 1, "Mobile : ", '1', 0, 'L', 0);
            $this->pdf->Cell(35, 1, $mobile, '1', 0, 'L', 0);

            $this->pdf->Ln();
            $this->pdf->Ln();
 			$this->pdf->Cell(50, 1, "Date Of Join : ", '1', 0, 'L', 0);
 			$this->pdf->Cell(100, 1, $doj, '1', 0, 'L', 0);

 			$this->pdf->Ln();
 			$this->pdf->Cell(20, 1, "Category : ", '1', 0, 'L', 0);            
            $this->pdf->Cell(30, 1, $category, '1', 0, 'L', 0);
            $this->pdf->Cell(20, 1, "Town : ", '1', 0, 'L', 0);
            $this->pdf->Cell(30, 1, $town, '1', 0, 'L', 0);            
            $this->pdf->Cell(20, 1, "Area : ", '1', 0, 'L', 0);
            $this->pdf->Cell(30, 1, $area, '1', 0, 'L', 0);
    	    $this->pdf->Ln();
			$this->pdf->Ln();
 			$this->pdf->Cell(150, 1, "__________________________________
 				_________________________________________", '0', 0, '', 0);


            $this->pdf->Ln();
            $this->pdf->Ln();
         	$this->pdf->SetFont('helvetica', 'BU', 13);
         	$this->pdf->Cell(150, 1, "Financial And Other", '0', 0, 'C', 0);

         	$this->pdf->SetFont('helvetica', 'B', 10);
  			$this->pdf->Ln();

         	$this->pdf->Cell(50, 1, "Balance", '1', 0, 'L', 0);
			$this->pdf->Cell(100, 1, "100000.00", '1', 0, 'R', 0);
        	$this->pdf->Ln();
        	$this->pdf->Ln();
         	$this->pdf->SetFont('helvetica', 'BU', 11);
         	$this->pdf->Cell(10, 1, "Credit ", '0', 0, 'L', 0);
  			
  			$this->pdf->Ln();
         	$this->pdf->Ln();
			$this->pdf->SetFont('helvetica', 'B', 10);
 			$this->pdf->Cell(30, 1, "Credit Limit : ", '1', 0, 'L', 0);            
            $this->pdf->Cell(45, 1, $credit_limit, '1', 0, 'L', 0);
            $this->pdf->Cell(30, 1, "Credit Period : ", '1', 0, 'L', 0);
            $this->pdf->Cell(45, 1, $credit_period, '1', 0, 'L', 0); 
			$this->pdf->Ln();
         	$this->pdf->Ln();

            $this->pdf->SetFont('helvetica', 'BU', 11);
         	$this->pdf->Cell(10, 1, "Tax ", '0', 0, 'L', 0);
  			
  			$this->pdf->Ln();
         	$this->pdf->Ln();  $this->pdf->SetFont('helvetica', 'B', 10);
			$this->pdf->Cell(50, 1, "Tax no", '1', 0, 'L', 0);
            $this->pdf->Cell(100, 1, $tax_reg_no, '1', 0, 'L', 0);
			
			$this->pdf->Ln();	$this->pdf->Ln();
            $this->pdf->SetFont('helvetica', 'BU', 11);
         	$this->pdf->Cell(10, 1, "Other", '0', 0, 'L', 0);
  			
  			$this->pdf->Ln();
			$this->pdf->Ln();  
			$this->pdf->SetFont('helvetica', 'B', 10);
  			$this->pdf->Cell(50, 1, "Date Of Birth", '1', 0, 'L', 0);
            $this->pdf->Cell(100, 1, $dob, '1', 0, 'L', 0);

            $this->pdf->Ln();

            $this->pdf->Cell(50, 1, "Occupation", '1', 0, 'L', 0);
            $this->pdf->Cell(100, 1, $Occupation , '1', 0, 'L', 0);

            $this->pdf->Ln();

            $this->pdf->Cell(50, 1, "Salary", '1', 0, 'L', 0);
            $this->pdf->Cell(100, 1, $salary, '1', 0, 'L', 0);

            $this->pdf->Ln();

            $this->pdf->Cell(50, 1, "Nationality", '1', 0, 'L', 0);
            $this->pdf->Cell(100, 1, $nationality, '1', 0, 'L', 0);

			$this->pdf->Output("Customer_".date('Y-m-d').".pdf", 'I');

?>