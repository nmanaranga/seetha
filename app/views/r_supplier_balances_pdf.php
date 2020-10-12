<?php

    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    $this->pdf->setPrintHeader($header,$type,$duration);
    $this->pdf->setPrintHeader(true,$type);
    $this->pdf->setPrintFooter(true);
    
    $this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage($orientation,$page); 

    $branch_name="";

    foreach($branch as $ress)
    {
        $branch_name=$ress->name;
        $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    }
      
    $this->pdf->setY(25);
    $this->pdf->SetFont('helvetica', 'BU',10);
    $this->pdf->Cell(180, 1,"Supplier (Creditor) Balance   ",0,false, 'C', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();


    $this->pdf->SetFont('helvetica', '',9);
    $this->pdf->Cell(180, 1,"As At Date  ".$to,0,false, 'C', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(40);
    $this->pdf->setX(20);

    $this->pdf->SetFont('helvetica','B',7);
    $this->pdf->Cell(20, 6," ID", '1', 0, 'L', 0);
    $this->pdf->Cell(65, 6," Name ", '1', 0, 'L', 0);
    $this->pdf->Cell(25, 6," Tel No ", '1', 0, 'L', 0);
    $this->pdf->Cell(40, 6," Contact Person", '1', 0, 'L', 0);
    $this->pdf->Cell(25, 6,"Balance ", '1', 0, 'R', 0);
    $this->pdf->Ln();

    $code='default';
    $total=(float)0;

    foreach($item_det as $row)
    {
        $this->pdf->GetY(40);
        $this->pdf->SetX(20);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        $this->pdf->SetFont('helvetica','',7);
        
        $this->pdf->SetX(20);
        $this->pdf->Cell(20, 6,$row->code, '1', 0, 'L', 0);
        $this->pdf->Cell(65, 6,$row->name, '1', 0, 'L', 0);
        $this->pdf->Cell(25, 6,$row->tp, '1', 0, 'L', 0);
        $this->pdf->Cell(40, 6,$row->contact_name, '1', 0, 'L', 0);
        $this->pdf->Cell(25, 6,$row->balance, '1', 0, 'R', 0);
        $this->pdf->Ln();
        $total+=(float)$row->balance;
    } 

    $this->pdf->SetX(20);
    $this->pdf->SetFont('helvetica','B',9);
    $this->pdf->Cell(20, 6,"", '0', 0, 'L', 0);
    $this->pdf->Cell(65, 6,"", '0', 0, 'L', 0);
    $this->pdf->Cell(25, 6,"", '0', 0, 'L', 0);
    $this->pdf->Cell(40, 6,"Total", '0', 0, 'R', 0);
    $this->pdf->Cell(25, 6,number_format($total,2), 'TB', 0, 'R', 0);

    $this->pdf->Output("Supplier Balance".date('Y-m-d').".pdf", 'I');
?>
