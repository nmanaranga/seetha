<?php
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    $this->pdf->setPrintHeader($header,$type,$duration);
    $this->pdf->setPrintHeader(true,$type);
    $this->pdf->setPrintFooter(true);
    
    $this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage("L","A5"); 

    $branch_name="";

    //set header -----------------------------------------------------------------------------------------
    foreach($branch as $ress){
               $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
            }

    $this->pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
    
    $this->pdf->setY(22);$this->pdf->SetFont('helvetica', 'BIU',12);
    $this->pdf->Cell(180, 1,"Bank Entry",0,false, 'L', 0, '', 0, false, 'M', 'M');
    

    foreach($session as $ses){
        $invoice_no=$session[0].$session[1]."BE".$session[2];
    }

    foreach($r_bank_entry as $row){
        $date        = $row->dDate;
        $type        = $row->type;
        $ref         = $row->ref_no;
        $cr          = $row->craccId;
        $cr_des      = $row->cracc_des;
        $dr          = $row->draccId;
        $dr_des      = $row->dracc_des;
        $description = $row->description;
        $narration   = $row->narration;
        $batch       = $row->batch_code;
        $amount      = $row->amount;                          
    }

    foreach($user as $row){
        $operator=$row->discription;
        $tt=$row->actionDate;
    }

    $amount_latter = $rec;

    if($type=="OtherBankEntry"){
        $types = "Other Bank Entry";
    }else if($type=="CashEntry"){
        $types = "Cash Entry";
    }

        $this->pdf->SetX(20);
        $this->pdf->Ln();
        
        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(105, 6,"", '0', 0, 'L', 0);
        $this->pdf->Cell(25, 6,"No", '0', 0, 'L', 0);
        $this->pdf->SetFont('helvetica','',8);
        $this->pdf->Cell(10, 6,$invoice_no, '0', 0, 'L', 0);
        $this->pdf->Ln();

        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(105, 6,"", '0', 0, 'L', 0);
        $this->pdf->Cell(25, 6,"Date", '0', 0, 'L', 0);
        $this->pdf->SetFont('helvetica','',8);
        $this->pdf->Cell(10, 6,$date, '0', 0, 'L', 0);
        $this->pdf->Ln();

        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(105, 6,"", '0', 0, 'L', 0);
        $this->pdf->Cell(25, 6,"Ref. No", '0', 0, 'L', 0);
        $this->pdf->SetFont('helvetica','',8);
        $this->pdf->Cell(10, 6,$ref, '0', 0, 'L', 0);
        $this->pdf->Ln();
      

        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(25, 6,"Type", '0', 0, 'L', 0);
        $this->pdf->SetFont('helvetica','',8);
        $this->pdf->Cell(25, 6,$types, '0', 0, 'L', 0);

        $this->pdf->Ln();

        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(25, 6,"Credit Account", '0', 0, 'L', 0);
        $this->pdf->SetFont('helvetica','',8);
        $this->pdf->Cell(55, 6,$cr." - ".$cr_des, '0', 0, 'L', 0);

        $this->pdf->Ln();

        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(25, 6,"Debit Account", '0', 0, 'L', 0);
        $this->pdf->SetFont('helvetica','',8);
        $this->pdf->Cell(55, 6,$dr." - ".$dr_des, '0', 0, 'L', 0);

        $this->pdf->Ln();

        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(25, 6,"Description", '0', 0, 'L', 0);
        $this->pdf->SetFont('helvetica','',8);
        $this->pdf->Cell(55, 6,$description, '0', 0, 'L', 0);

        $this->pdf->Ln();

        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(25, 6,"Narration", '0', 0, 'L', 0);
        $this->pdf->SetFont('helvetica','',8);
        $this->pdf->Cell(55, 6,$narration, '0', 0, 'L', 0);

        $this->pdf->Ln();

        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(25, 6,"Batch", '0', 0, 'L', 0);
        $this->pdf->SetFont('helvetica','',8);
        $this->pdf->Cell(55, 6,$batch, '0', 0, 'L', 0);

   
        $this->pdf->Ln();
   
        $this->pdf->SetFont('helvetica','',8);
        $this->pdf->Cell(25, 6,"", '0', 0, 'L', 0);
        $this->pdf->Cell(105, 6,"Amount invert".$amount_latter, '0', 0, 'L', 0);
       
        $this->pdf->Ln();
      

        $this->pdf->SetFont('helvetica','B',8);
        $this->pdf->Cell(25, 6,"Amount", '0', 0, 'L', 0);
        $this->pdf->SetFont('helvetica','B',15);
        $this->pdf->Cell(55, 6,$amount, '1', 0, 'C', 0);

        $this->pdf->Ln();

   

    //----------------------------------------------------------------------------------------------------

            $this->pdf->Ln();             
            $this->pdf->SetFont('helvetica', '', 8);
                    
            $this->pdf->Ln();
            $this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);
            $this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);
            $this->pdf->Cell(50, 1, '...................................', '0', 0, 'L', 0);

            $this->pdf->Ln();
            $this->pdf->Cell(50, 1, '       Prepaired By', '0', 0, 'L', 0);
            $this->pdf->Cell(50, 1, '         Checked By', '0', 0, 'L', 0);
            $this->pdf->Cell(50, 1, ' Cashier Signature', '0', 0, 'L', 0);
         
            $this->pdf->Ln();
            $this->pdf->Ln();
        

            //$tt = date("H:i");
            $this->pdf->Cell(20, 1, "User ", '0', 0, 'L', 0);
            $this->pdf->Cell(1, 1, $operator, '0', 0, 'L', 0);
            $this->pdf->Ln();
           

            $this->pdf->Cell(20, 1, "Print Time ", '0', 0, 'L', 0);
            $this->pdf->Cell(1, 1, $tt, '0', 0, 'L', 0);
            $this->pdf->Ln();

    $this->pdf->Output("Bank Entry ".date('Y-m-d').".pdf", 'I');

?>
        


