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
    $this->pdf->Cell(180, 1,"Purchase Bill Summary ",0,false, 'C', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();


    $this->pdf->SetFont('helvetica', '',8);
    $this->pdf->Cell(180, 1,"Date From ".$from." To ".$to,0,false, 'C', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(40);
    $this->pdf->setX(20);

    $this->pdf->SetFont('helvetica','B',7);
    $this->pdf->Cell(15, 6," Date", '1', 0, 'L', 0);
    $this->pdf->Cell(15, 6,"GRN No ", '1', 0, 'R', 0);
    $this->pdf->Cell(15, 6,"PO No ", '1', 0, 'R', 0);
    $this->pdf->Cell(15, 6,"Inv No ", '1', 0, 'R', 0);
    $this->pdf->Cell(20, 6,"Amount ", '1', 0, 'R', 0);
    $this->pdf->Cell(20, 6,"Discount ", '1', 0, 'R', 0);
    $this->pdf->Cell(20, 6,"Net Amount ", '1', 0, 'R', 0);
    $this->pdf->Cell(20, 6,"Paid ", '1', 0, 'R', 0);
    $this->pdf->Cell(20, 6,"Returned ", '1', 0, 'R', 0);
    $this->pdf->Cell(20, 6,"Balance ", '1', 0, 'R', 0);
    $this->pdf->Ln();

    $supp='default';
    $bal=(int)0;

    foreach($item_det as $row)
    {
        $bal=(int)$row->net_amount -((int)$row->paid+(int)$row->return_q);

        $this->pdf->GetY(40);
        $this->pdf->SetX(20);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

        $this->pdf->SetFont('helvetica','',7);
    
        if($supp!="default" && $supp==$row->supp_id)
        {
            $this->pdf->SetX(20);
            $this->pdf->Cell(15, 6,$row->ddate, '1', 0, 'L', 0);
            $this->pdf->Cell(15, 6,$row->nno, '1', 0, 'R', 0);
            $this->pdf->Cell(15, 6,$row->po_no, '1', 0, 'R', 0);
            $this->pdf->Cell(15, 6,$row->inv_no, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$row->amount, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$row->discount, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$row->net_amount, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$row->paid, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$row->return_q, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,number_format($bal,2,'.',''), '1', 0, 'R', 0);
            $this->pdf->Ln();  
            $bal=0;          
        }
        else
        {
            $this->pdf->SetX(20);
            $this->pdf->Cell(15, 6,$row->supp_id." - ".$row->name , '0', 0, 'L', 0);
            $this->pdf->Cell(15, 6,"", '0', 0, 'L', 0);
            $this->pdf->Cell(15, 6,"", '0', 0, 'R', 0);
            $this->pdf->Cell(15, 6,"", '0', 0, 'R', 0);
            $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
            $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
            $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
            $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
            $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
            $this->pdf->Cell(20, 6,"", '0', 0, 'R', 0);
            $this->pdf->Ln();

            $this->pdf->SetX(20);
            $this->pdf->Cell(15, 6,$row->ddate, '1', 0, 'L', 0);
            $this->pdf->Cell(15, 6,$row->nno, '1', 0, 'R', 0);
            $this->pdf->Cell(15, 6,$row->po_no, '1', 0, 'R', 0);
            $this->pdf->Cell(15, 6,$row->inv_no, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$row->amount, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$row->discount, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$row->net_amount, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$row->paid, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$row->return_q, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,number_format($bal,2,'.',''), '1', 0, 'R', 0);
            $this->pdf->Ln();
            $bal=0;          
        }
        $supp=$row->supp_id;
    }          
    $this->pdf->Output("Purchase Bill Summery".date('Y-m-d').".pdf", 'I');
?>
