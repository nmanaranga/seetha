<?php
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    $this->pdf->setPrintHeader($header,$type,$duration);
    $this->pdf->setPrintHeader(true,$type);
    $this->pdf->setPrintFooter(true);
    
    $this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage("P",$page); 

    $branch_name="";

    //set header -----------------------------------------------------------------------------------------
    foreach($branch as $ress)
    {
        $branch_name=$ress->name;
        $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    }
    $this->pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
    
    $this->pdf->setY(22);$this->pdf->SetFont('helvetica', 'BI',12);
    $this->pdf->Cell(180, 1,"Cheque Acknowledgement List   ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(25);$this->pdf->Cell(70, 1,"",'T',0, 'L', 0);
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

         $this->pdf->Cell(20, 6,"Ack. Date", '1', 0, 'C', 0);
         $this->pdf->Cell(15, 6,"Ack. No", '1', 0, 'C', 0);
         $this->pdf->Cell(75, 6,"Customer", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Cheque No.", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Status", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"R. Date", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Amount", '1', 0, 'C', 0);
         $this->pdf->Ln();
          
        foreach($r_cheque_in_hand as $row){

         $this->pdf->SetX(16);
         $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
         if($row->status=="p"){
           $status="PENDING";
         }else{
           $status="DEPOSIT";
         }
         $this->pdf->SetFont('helvetica','',9);
         $this->pdf->Cell(20, 6,$row->date, '1', 0, 'L', 0);
         $this->pdf->Cell(15, 6,$row->nno, '1', 0, 'R', 0);
         $this->pdf->Cell(75, 6,$row->customer, '1', 0, 'L', 0);
         $this->pdf->Cell(20, 6,$row->cheque_no, '1', 0, 'L', 0);
         $this->pdf->Cell(20, 6,$status, '1', 0, 'L', 0);
         $this->pdf->Cell(20, 6,$row->realize_date, '1', 0, 'L', 0);
         $this->pdf->Cell(20, 6,number_format($row->amount,2), '1', 1, 'R', 0);

         $tot+=(float)$row->amount;
         
         }

            $this->pdf->SetFont('helvetica','B',9);             
            $this->pdf->SetX(16);
            $this->pdf->Cell(20, 6, "", '0', 0, 'L', 0);
            $this->pdf->Cell(15, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(75, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(20, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(20, 6, " ", '0', 0, 'R', 0);
            $this->pdf->Cell(20, 6,"Total", '0', 0, 'R', 0);
             $this->pdf->Cell(20, 6,  number_format($tot,2), 'TB', 0, 'R', 0);
            $this->pdf->Ln();  
    //----------------------------------------------------------
       
         

        $this->pdf->Output("recievable_invoice_details".date('Y-m-d').".pdf", 'I');
           
?>
        


