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

			$this->pdf->setY(18);
            $this->pdf->Ln();
            $this->pdf->SetFont('helvetica', 'IBU', 14);
		 	$this->pdf->Cell(280, 1,$title,0,false, 'L', 0, '', 0, false, 'M', 'M');

			$this->pdf->Ln();
            $this->pdf->SetFont('helvetica', 'B', 9);
            $this->pdf->Cell(25, 1,"As At Date",0,false, 'L', 0, '', 0, false, 'M', 'M');
            $this->pdf->Cell(225, 1,$all_det["date"],0,false, 'L', 0, '', 0, false, 'M', 'M');
           
		
		
		$this->pdf->setY(40);
		$len=count($all_det);
        $act_len=(($len-7)/9);

        for($x=0; $x<$act_len; $x++){
             
                	
        		$this->pdf->setX(10);
                $this->pdf->SetFont('helvetica','',8);
                $this->pdf->Cell(20, 6,$all_det['0_'.$x], '1', 0, 'L', 0);
				$this->pdf->Cell(20, 6,$all_det['n_'.$x], '1', 0, 'L', 0);
				$this->pdf->Cell(50, 6,$all_det['1_'.$x], '1', 0, 'L', 0);
                $this->pdf->Cell(80, 6,$all_det['2_'.$x], '1', 0, 'L', 0);
                $this->pdf->Cell(20, 6,$all_det['3_'.$x], '1', 0, 'R', 0);
                $this->pdf->Cell(20, 6,$all_det['4_'.$x], '1', 0, 'R', 0);
                $this->pdf->Cell(20, 6,$all_det['5_'.$x], '1', 0, 'R', 0);
                $this->pdf->Cell(20, 6,$all_det['6_'.$x], '1', 0, 'L', 0);
                $this->pdf->Cell(25, 6,$all_det['7_'.$x], '1', 0, 'R', 0);
                $this->pdf->Ln(); 
      
        }
	

	$this->pdf->Output($title.date('Y-m-d').".pdf", 'I');

?>