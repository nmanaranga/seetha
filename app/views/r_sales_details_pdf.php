<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
        $this->pdf->setPrintFooter(true);
        //$this->pdf->setPrintHeader(true);
        //print_r($sum);
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

 		foreach($branch as $ress){
 				$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
 			}

		
		$this->pdf->setY(20);

        	$this->pdf->SetFont('helvetica', 'IBU',12);
		 	$this->pdf->Cell(0, 5, 'SALES DETAILS',0,false, 'C', 0, '', 0, false, 'M', 'M');
			$this->pdf->Ln();$this->pdf->Ln();
			$i=0;
		    $a=-1;
			static $j=-1;
			$my_array=array();
			$Goss=array();
			$net=array();

			foreach ($purchase as $value) {
                  $my_array[]=$value->name;
			}

			foreach ($sum as $sum){
                        	$Goss[]=$sum->gsum;
                        	$net[]=$sum->nsum;
                        	$a++;
                        }

					
			foreach ($purchase as $value) {	
				if ($i==0 || $my_array[$i]!=$my_array[$i-1]) {

					if ($j>=0) {
						$this->pdf->SetX(45);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "Goss", '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $Goss[$j], '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		 
		 	$this->pdf->Ln();
$this->pdf->SetX(45);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "Net Amount", '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $net[$j], '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		 	
			$this->pdf->Ln(); $this->pdf->Ln(); 
					}
					
				$j++;

		 	$this->pdf->SetFont('helvetica', 'B', 8);
		 	if ($i==0) {
		 		$this->pdf->setY(25);
		 	}
		 	$this->pdf->Ln();
		 	
		 	$this->pdf->SetX(45);
		 	$this->pdf->Cell(30, 1, 'INV No - ', '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, $value->nno, '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "Rep", '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $value->rep, '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		 	
		 	$this->pdf->Ln();
$this->pdf->SetX(45);
		 	$this->pdf->Cell(30, 1, "Date - ", '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, date('Y-m-d'), '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "Store", '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $value->store, '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		 
		 	$this->pdf->Ln();
$this->pdf->SetX(45);
		 	$this->pdf->Cell(30, 1, "Customer - ", '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, $value->name, '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		 	
$this->pdf->Ln();$this->pdf->Ln();
				//$this->pdf->SetY(45);
		 				$this->pdf->SetX(45);
						$this->pdf->SetFont('helvetica','B',8);
                        $this->pdf->Cell(30, 6,"Item Code", '1', 0, 'C', 0);
                       
						$this->pdf->Cell(70, 6,"Description", '1', 0, 'C', 0);
                        $this->pdf->Cell(20, 6,"Qty", '1', 0, 'C', 0);
                        $this->pdf->Cell(20, 6,"Price", '1', 0, 'C', 0);
                        $this->pdf->Cell(20, 6,"Discount", '1', 0, 'C', 0);
                        $this->pdf->Cell(20, 6,"Amount", '1', 0, 'C', 0);
                        $this->pdf->Ln();

}

                        
						//$this->pdf->SetY(45);
                        	
                        	
		 				$this->pdf->SetX(45);
						$this->pdf->SetFont('helvetica','B',8);
                        $this->pdf->Cell(30, 6,$value->code, '1', 0, 'L', 0);
                       
						$this->pdf->Cell(70, 6,$value->description,'1', 0, 'L', 0);
                        $this->pdf->Cell(20, 6,$value->qty, '1', 0, 'R', 0);
                        $this->pdf->Cell(20, 6,$value->price, '1', 0, 'R', 0);
                        $this->pdf->Cell(20, 6,$value->discount, '1', 0, 'R', 0);
                        $this->pdf->Cell(20, 6,$value->price*$value->qty, '1', 0, 'R', 0);
                        $this->pdf->Ln();

                        $i++;
                        	
                        }

                       

$this->pdf->SetX(45);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "Goss", '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $Goss[$a], '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		 
		 	$this->pdf->Ln();
$this->pdf->SetX(45);
		 	$this->pdf->Cell(30, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(50, 1, "", '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "Net Amount", '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, $net[$a], '0', 0, 'L', 0);
		 	$this->pdf->Cell(20, 1, "", '0', 0, 'L', 0);
		 	
			$this->pdf->Ln();              
		 	



	
	$this->pdf->Output("Cash Sales_".date('Y-m-d').".pdf", 'I');

?>