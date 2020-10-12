<?php
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    $this->pdf->setPrintHeader($header,$type,$duration);
    $this->pdf->setPrintHeader(true,$type);
    $this->pdf->setPrintFooter(true);
    
    $this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage($orientation,$page); 

    $branch_name="";

    //set header -----------------------------------------------------------------------------------------
    foreach($branch as $ress)
    {
        $branch_name=$ress->name;
        $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    }
    $this->pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
    
    $this->pdf->setY(22);$this->pdf->SetFont('helvetica', 'BI',12);
    $this->pdf->Cell(180, 1,"Cheque in Hand   ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(25);$this->pdf->Cell(40, 1,"",'T',0, 'L', 0);
    $this->pdf->Ln(); 


    $this->pdf->setY(28);$this->pdf->SetFont('helvetica', '', 10);
    $this->pdf->Cell(180, 1,"Date Form - ".$dfrom."  To - ".$dto,0,false, 'L', 0, '', 0, false, 'M', 'M');
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
   $this->pdf->SetX(20);
   $this->pdf->setY(28);$this->pdf->SetFont('helvetica', '', 10);
   $this->pdf->Ln();
   $this->pdf->Cell(20, 4,'Cluster', '0', 0, 'L', 0);
   $this->pdf->Cell(5, 4,':', '0', 0, 'L', 0);
   $this->pdf->Cell(120, 4,"$cl_id - $cluster_name", '0', 0, 'L', 0);
   $this->pdf->Ln();
   $this->pdf->Cell(20, 4,'Branch', '0', 0, 'L', 0);
   $this->pdf->Cell(5, 4,':', '0', 0, 'L', 0);
   $this->pdf->Cell(20, 4,"$bc_id - $branch_name", '0', 0, 'L', 0);
       
    
         // Headings
         $this->pdf->Ln();
         $this->pdf->SetFont('helvetica', 'B', 10);
         $this->pdf->Ln();
         $this->pdf->SetX(16);

         $this->pdf->Cell(30, 6,"Receipt Date", '1', 0, 'C', 0);
         $this->pdf->Cell(30, 6,"Rec. No", '1', 0, 'C', 0);
         $this->pdf->Cell(30, 6,"Bank Code", '1', 0, 'C', 0);
         $this->pdf->Cell(60, 6,"Customer", '1', 0, 'C', 0);
         $this->pdf->Cell(30, 6,"Cheque No.", '1', 0, 'C', 0);
         $this->pdf->Cell(40, 6,"Amount", '1', 0, 'C', 0);
         $this->pdf->Cell(40, 6,"Realise Date", '1', 0, 'C', 0);
         $this->pdf->Ln();
          
        foreach($r_cheque_in_hand as $row){

         $this->pdf->SetX(16);
         $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        
         $this->pdf->SetFont('helvetica','',9);
         $this->pdf->Cell(30, 6,$row->ddate, '1', 0, 'C', 0);
         $this->pdf->Cell(30, 6,$row->trans_no, '1', 0, 'C', 0);
         $this->pdf->Cell(30, 6,$row->bank, '1', 0, 'C', 0);
         $this->pdf->Cell(60, 6,$row->description, '1', 0, 'L', 0);
         $this->pdf->Cell(30, 6,$row->cheque_no, '1', 0, 'C', 0);
         $this->pdf->Cell(40, 6,$row->amount, '1', 0, 'R', 0);
         $this->pdf->Cell(40, 6,$row->bank_date, '1', 1, 'C', 0);

         $tot+=(float)$row->amount;
         
         }

            $this->pdf->SetFont('helvetica','B',9);             
            $this->pdf->SetX(16);
            $this->pdf->Cell(30, 6, "", '0', 0, 'L', 0);
            $this->pdf->Cell(30, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(30, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(60, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(30, 6, "Total ", '0', 0, 'R', 0);
            $this->pdf->Cell(40, 6, number_format($tot,2), 'TB', 0, 'R', 0);
             $this->pdf->Cell(40, 6, "", '0', 0, 'R', 0);
            $this->pdf->Ln();  
    //----------------------------------------------------------
       
         

        $this->pdf->Output("recievable_invoice_details".date('Y-m-d').".pdf", 'I');
           
?>
        


