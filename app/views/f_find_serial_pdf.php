<?php

		//echo "<pre>".print_r($all_det,true)."</pre>";
		//exit;

		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		$this->pdf->setPrintHeader($header,$type,$duration);
		$this->pdf->setPrintHeader(true,$type);
        $this->pdf->setPrintFooter(true);
        //$this->pdf->setPrintHeader(true);
        
        $this->pdf->SetFont('helvetica', 'B', 16);
		$this->pdf->AddPage($orientation,$page); 


 		foreach($branch as $ress){
 			$this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
		}

			$this->pdf->setY(20);
            $this->pdf->Ln();
            $this->pdf->SetFont('helvetica', 'IBU', 12);
		 	$this->pdf->Cell(280, 1,$title,0,false, 'C', 0, '', 0, false, 'M', 'M');

			$this->pdf->Ln();


  
		$x=0; 	
		$this->pdf->Ln();
		$this->pdf->setY(40);
		
        foreach($all_det as $row){

        	if($_POST['3_'.$x]!="")
        	{
        		
        		$this->pdf->setX(25);
                $this->pdf->SetFont('helvetica','',8);
                $this->pdf->Cell(30, 6,$all_det['0_'.$x], '1', 0, 'L', 0);
				$this->pdf->Cell(20, 6,$all_det['1_'.$x], '1', 0, 'R', 0);
				$this->pdf->Cell(20, 6,$all_det['2_'.$x], '1', 0, 'L', 0);
                $this->pdf->Cell(30, 6,$all_det['3_'.$x], '1', 0, 'L', 0);
                $this->pdf->Cell(60, 6,$all_det['4_'.$x], '1', 0, 'L', 0);
                $this->pdf->Cell(30, 6,$all_det['5_'.$x], '1', 0, 'L', 0);
                $this->pdf->Cell(30, 6,$all_det['6_'.$x], '1', 0, 'L', 0);
                $this->pdf->Cell(30, 6,$all_det['7_'.$x], '1', 0, 'L', 0);
                $this->pdf->Ln();
            }

        $x++;
        }
	

	$this->pdf->Output($title.date('Y-m-d').".pdf", 'I');

?>