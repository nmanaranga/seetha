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
    $this->pdf->Cell(180, 1,"Payable Invoice-Summery   ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(25);$this->pdf->Cell(60, 1,"",'T',0, 'L', 0);
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

         $this->pdf->SetFont('helvetica', 'B', 9);
         $this->pdf->Ln();
         $this->pdf->SetX(16);

         $this->pdf->Cell(10, 6,"No", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Type", '1', 0, 'C', 0);
         $this->pdf->Cell(60, 6,"Description", '1', 0, 'C', 0);
         $this->pdf->Cell(50, 6,"Narration", '1', 0, 'C', 0); 
         $this->pdf->Cell(25, 6,"Acc No", '1', 0, 'C', 0);
         $this->pdf->Cell(60, 6,"Acc Name", '1', 0, 'C', 0);
         $this->pdf->Cell(25, 6,"Amount", '1', 0, 'C', 0);
         $this->pdf->Ln();
          $tot=(float)0;

         // Deatils loop 
                
        foreach($r_payable_invoice_summery as $row){
         $this->pdf->SetX(16);
        

         $this->pdf->SetFont('helvetica','',9);

         $aa = $this->pdf->getNumLines($row->des, 60);
         $bb=$this->pdf->getNumLines($row->narration, 50); 
         $cc=$this->pdf->getNumLines($row->p_type, 20); 
         
         if($bb>$aa){
          $heigh=5*$bb;
         }else{
          $heigh=5*$aa;
         }
         if($cc>$bb){
          $heigh=5*$cc;
         }
         $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
         $this->pdf->MultiCell(10, $heigh, $row->no, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(20, $heigh, $row->date, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(20, $heigh, $row->p_type, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(60, $heigh, $row->des, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(50, $heigh, $row->narration,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(25, $heigh, $row->payable_account,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(60, $heigh, $row->acc_name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(25, $heigh, $row->total,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
         
          $tot+=(float)$row->total;

          
         }

            $this->pdf->Ln();   
            $this->pdf->SetFont('helvetica','B',8);             
            $this->pdf->SetX(16);
            $this->pdf->Cell(10, 6, "", '0', 0, 'L', 0);
            $this->pdf->Cell(20, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(20, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(60, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(50, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(25, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(60, 6, "Total ", '0', 0, 'R', 0);
            $this->pdf->Cell(25, 6, number_format($tot,2), 'TB', 0, 'R', 0);
            $this->pdf->Ln();  

       
    //----------------------------------------------------------

         
         

        $this->pdf->Output("payable_voice_summery".date('Y-m-d').".pdf", 'I');
           
?>
        


