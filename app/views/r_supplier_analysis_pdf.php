<?php

    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    $this->pdf->setPrintHeader($header,$type,$duration);
    $this->pdf->setPrintHeader(true,$type);
    $this->pdf->setPrintFooter(true);
    
    $this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage($orientation,$page); 

    $branch_name="";

    foreach($branch as $ress){
        $branch_name=$ress->name;
        $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    }
      
    $this->pdf->setY(25);
    $this->pdf->SetFont('helvetica', 'BU',12);
    $this->pdf->Cell(180, 1,"Supplier Age Analysis Report ",0,false, 'C', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();


    $this->pdf->SetFont('helvetica', '',10);
    $this->pdf->Cell(180, 1,"As At ".$to,0,false, 'C', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(40);
    $this->pdf->setX(10);

    $this->pdf->SetFont('helvetica','B',7);
    $this->pdf->Cell(20, 6," Supplier ID", '1', 0, 'L', 0);
    $this->pdf->Cell(60, 6," Supplier Name ", '1', 0, 'L', 0);
    $this->pdf->Cell(25, 6," Balance ", '1', 0, 'R', 0);
    $this->pdf->Cell(21, 6,"Over 90 Days ", '1', 0, 'R', 0);
    $this->pdf->Cell(21, 6,"61 to 90 Days ", '1', 0, 'R', 0);
    $this->pdf->Cell(21, 6,"31 to 60 Days ", '1', 0, 'R', 0);
    $this->pdf->Cell(21, 6,"Current <30 ", '1', 0, 'R', 0);
    $this->pdf->Ln();

    $this->pdf->GetY(40);
    $this->pdf->SetX(10);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    $this->pdf->SetFont('helvetica','',7);
    
    $customer_code="";
    $customer_name="";
    $over90=0;
    $over61=0;
    $over31=0;
    $over0=0;
    $total=(float)0;

    foreach($cus_det as $row){
        $this->pdf->SetX(10);
        $this->pdf->Cell(20, 6,$row->acc_code, '1', 0, 'L', 0);
        $this->pdf->Cell(60, 6,$row->NAME, '1', 0, 'L', 0);
        $this->pdf->Cell(25, 6,$row->balance, '1', 0, 'R', 0);
        $this->pdf->Cell(21, 6,$row->Over90, '1', 0, 'R', 0);
        $this->pdf->Cell(21, 6,$row->D60t90, '1', 0, 'R', 0);
        $this->pdf->Cell(21, 6,$row->D30t60, '1', 0, 'R', 0);
        $this->pdf->Cell(21, 6,$row->Below30, '1', 0, 'R', 0);
        $this->pdf->Ln();
        $total+=(float)$row->balance;
        $total1+=(float)$row->Over90;
        $total2+=(float)$row->D60t90;
        $total3+=(float)$row->D30t60;
        $total4+=(float)$row->Below30;
    } 

        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->SetX(10);
        $this->pdf->Cell(20, 6,"", '0', 0, 'L', 0);
        $this->pdf->Cell(60, 6,"Total ", '0', 0, 'R', 0);
        $this->pdf->Cell(25, 6,number_format($total,2), 'TB', 0, 'R', 0);
        $this->pdf->Cell(1, 6,"", '0', 0, 'L', 0);
        $this->pdf->Cell(20, 6,number_format($total1,2), 'TB', 0, 'R', 0);
        $this->pdf->Cell(1, 6,"", '0', 0, 'L', 0);
        $this->pdf->Cell(20, 6,number_format($total2,2), 'TB', 0, 'R', 0);
        $this->pdf->Cell(1, 6,"", '0', 0, 'L', 0);
        $this->pdf->Cell(20, 6,number_format($total3,2), 'TB', 0, 'R', 0);
        $this->pdf->Cell(1, 6,"", '0', 0, 'L', 0);
        $this->pdf->Cell(20, 6,number_format($total4,2), 'TB', 0, 'R', 0);

    $this->pdf->Output("Customer Balance".date('Y-m-d').".pdf", 'I');
?>
