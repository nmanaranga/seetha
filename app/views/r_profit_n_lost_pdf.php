<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintFooter(true);
$this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

    foreach($branch as $ress){
        $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    }

    foreach($user as $row){
        $operator=$row->loginName;
    }

    $this->pdf->SetFont('helvetica', 'BI',12);
    $this->pdf->Ln(3);
    $this->pdf->Cell(180, 1,"Profit And Lost Statement  ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln(3);

    $this->pdf->Cell(60, 1,"",'T',0, 'L', 0);
    $this->pdf->Ln(3); 

    $this->pdf->SetFont('helvetica', '', 8);
    $this->pdf->Cell(180, 1,"Date From - ".$dfrom."  To - ".$dto,0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln(3);


    $complex_cell_border = array(
       'TB' => array('width' => 0.1, 'color' => array(0,0,0), 'dash' => 2.1, 'cap' => 'butt'),
       '1' => array('width' => 0.1, 'color' => array(0,0,0), 'dash' => 0.1, 'cap' => 'butt'),

       );

    $this->pdf->SetFillColor(206, 206, 206);
    $this->pdf->SetFont('helvetica', 'B', 10);
    $this->pdf->Cell(150, 1, "INCOME  ", 0, 0, 'L', 1);
    $this->pdf->Cell(30, 3, '', 0, 0, 'R', 1);
    $this->pdf->Ln();

    $heading='';
    $x=0;
    $rr=0;
    $trn_amo=0;
    foreach ($pr_lo as $value) {
        if($value->acc_type=='INCOME'){ 
            if($heading!=$value->heading){

                if($x!=0){  
                    //$this->pdf->Ln(2); 
                    //$this->pdf->Ln(1); 
                    //$this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
                    //$this->pdf->Cell(25, 1,"",'0',0, 'L', 0);
                    //$this->pdf->Cell(155, 1,"",'T' ,0, 'L', 0);
                    //$this->pdf->Ln(1); 
                    $this->pdf->Ln(); 
                }

                
                $this->pdf->SetFont('helvetica', 'B', 8);
                $this->pdf->Cell(25, 4,"", '0', 0, 'L', 0);
                $this->pdf->Cell(55, 4,$value->heading, '0', 0, 'L', 0);
                $this->pdf->Cell(30, 4,'', '0', 0, 'L', 0);
                $this->pdf->Cell(45, 4,'', '0', 0, 'R', 0);
                if($x!=0){  
                    $this->pdf->Cell(25, 5,number_format($tot_income_sep,2), '0', 0, 'R', 0);
                }else{
                }

                

                if($value->myOrder=='400'){
                	$trn_amo+=$value->balance;
                }
                $this->pdf->Ln();
                $tot_income_sep=0;
                $x=1;   

            }else{
                /*
                $this->pdf->Cell(25, 4,"", '0', 0, 'L', 0);
                $this->pdf->Cell(55, 4,'', '0', 0, 'L', 0);
                $this->pdf->Cell(30, 4,'', '0', 0, 'L', 0);
                $this->pdf->Cell(45, 4,'', '0', 0, 'R', 0);
                $this->pdf->Cell(25, 4,"", '0', 0, 'L', 0);
                $this->pdf->Ln();
                */
                
            }
            if($value->myOrder=='300'){
                    $rr+=$value->balance;
                    //$trn_amo=$value->balance;
                }

            $this->pdf->SetFont('helvetica', '', 8);
            $this->pdf->Cell(25, 2,"", '0', 0, 'L', 0);
            $this->pdf->Cell(25, 2,"", '0', 0, 'L', 0);
            $this->pdf->Cell(65, 2,$value->description, '0', 0, 'L', 0);
            $this->pdf->Cell(30, 2,number_format($value->balance,2), '0', 0, 'R', 0);
            $this->pdf->Cell(25, 2,"", '0', 0, 'R', 0);
            $this->pdf->Ln();

            $tot_income_sep+=(float)$value->balance;   
            $tot_income+=$value->balance;             
        }
        $heading=$value->heading;
        //var_dump($value->heading);

    }
    $this->pdf->Cell(25, 1,"",'0',0, 'L', 0);
    //$this->pdf->Cell(155, 1,"",'T',0, 'L', 0);
    $this->pdf->Ln(1); 

    $this->pdf->SetFont('helvetica', 'B', 8);
    $this->pdf->Cell(35, 5,"", '0', 0, 'L', 0);
    $this->pdf->Cell(70, 5,"", '0', 0, 'L', 0);
    $this->pdf->Cell(50, 5,"", '0', 0, 'R', 0);
    $this->pdf->Cell(25, 5,number_format($tot_income_sep,2), '0', 0, 'R', 0);
    $this->pdf->Ln();


    $this->pdf->SetFont('helvetica', 'BI', 10);
    $this->pdf->SetFillColor(206, 206, 206);
    $this->pdf->Cell(25, 5,"", '0', 0, 'L', 0);
    //$this->pdf->Cell(80, 5,"Total Income", '0', 0, 'L', 1);
    $this->pdf->Cell(80, 5,"Gross Profit    ", '0', 0, 'R', 1);
    $this->pdf->Cell(50, 5,"", '0', 0, 'R', 1);

    $tot_income= ($rr-$tot_income_sep)+$trn_amo;
    $this->pdf->Cell(25, 5,number_format($tot_income,2), '0', 0, 'R', 1);
    $this->pdf->Ln();
    $this->pdf->Ln();

    $this->pdf->SetFillColor(206, 206, 206);
    $this->pdf->SetFont('helvetica', 'B', 10);
    $this->pdf->Cell(150, 1, "EXPENSES  ", 0, 0, 'L', 1);
    $this->pdf->Cell(30, 3, '', 0, 0, 'R', 1);
    $this->pdf->Ln();

    $headings='';
    $tot_expence=0;
    //$tot_income_sep=0;
    $y=0;
    foreach ($pr_lo as $row) {
        if($row->acc_type=='EXPENSES'){ 
            if($headings!=$row->heading){
                $headings=$row->heading;       
                if($y!=0){  
                    $this->pdf->Ln(2); 
                    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
                    $this->pdf->Cell(25, 1,"",'0',0, 'L', 0);
                   // $this->pdf->Cell(155, 1,"",'T',0, 'L', 0);
                    $this->pdf->Ln(1); 
                }
                $this->pdf->SetFont('helvetica', 'B', 8);
                $this->pdf->Cell(25, 4,"", '0', 0, 'L', 0);
                $this->pdf->Cell(55, 4,$row->heading, '0', 0, 'L', 0);
                $this->pdf->Cell(30, 4,'', '0', 0, 'L', 0);
                $this->pdf->Cell(45, 4,'', '0', 0, 'R', 0);
                if($y!=0){ 
                    $this->pdf->Cell(25, 5,number_format($tot_expence_sep,2), '0', 0, 'R', 0);
                }
                $this->pdf->Ln();
                
                $tot_expence_sep=0;
                $y=1;
            }else{
                /*
                $this->pdf->Cell(25, 4,"", '0', 0, 'L', 0);
                $this->pdf->Cell(55, 4,'', '0', 0, 'L', 0);
                $this->pdf->Cell(30, 4,'', '0', 0, 'L', 0);
                $this->pdf->Cell(45, 4,'', '0', 0, 'R', 0);
                $this->pdf->Cell(25, 4,"", '0', 0, 'L', 0);
                $this->pdf->Ln();
                */
            }
            $this->pdf->SetFont('helvetica', '', 8);
            $this->pdf->Cell(25, 2,"", '0', 0, 'L', 0);
            $this->pdf->Cell(25, 2,"", '0', 0, 'L', 0);
            $this->pdf->Cell(65, 2,$row->description, '0', 0, 'L', 0);
            $this->pdf->Cell(30, 2,number_format($row->balance,2), '0', 0, 'R', 0);
            $this->pdf->Cell(25, 2,"", '0', 0, 'R', 0);
            $this->pdf->Ln();     

            $tot_expence_sep+=$row->balance;   
            $tot_expence+=$row->balance;     
        }
        $heading=$row->heading;
    }
    //$this->pdf->Cell(25, 1,"",'0',0, 'L', 0);
    //$this->pdf->Cell(155, 1,"",'T',0, 'L', 0);
    //$this->pdf->Ln(1); 

    $this->pdf->SetFont('helvetica', 'B', 8);
    $this->pdf->Cell(35, 5,"", '0', 0, 'L', 0);
    $this->pdf->Cell(70, 5,"", '0', 0, 'L', 0);
    $this->pdf->Cell(50, 5,"", '0', 0, 'R', 0);
    $this->pdf->Cell(25, 5,number_format($tot_expence_sep,2), '0', 0, 'R', 0);
    $this->pdf->Ln();


    $this->pdf->SetFont('helvetica', 'BI', 10);
    $this->pdf->SetFillColor(206, 206, 206);
    $this->pdf->Cell(25, 5,"", '0', 0, 'L', 0);
    $this->pdf->Cell(80, 5,"Total Expences ", '0', 0, 'R', 1);
    $this->pdf->Cell(50, 5,"", '0', 0, 'R', 1);
    $this->pdf->Cell(25, 5,number_format($tot_expence,2), '0', 0, 'R', 1);
    $this->pdf->Ln();
    $this->pdf->Ln();

    $net_profit=($tot_income-($tot_expence));

    $this->pdf->SetFont('helvetica', 'BI', 10);
    $this->pdf->SetFillColor(206, 206, 206);
    $this->pdf->Cell(25, 5,"", '0', 0, 'L', 0);
    $this->pdf->Cell(80, 5,"NET PROFIT ", 0, 0, 'R', 1);
    $this->pdf->Cell(50, 5,"", '0', 0, 'R', 1);
    $this->pdf->Cell(25, 5,number_format($net_profit,2), '0', 0, 'R', 1);
    $this->pdf->Ln();
    $this->pdf->Ln();


    $this->pdf->Output("Profit and lost_".date('Y-m-d').".pdf", 'I');

    ?>
