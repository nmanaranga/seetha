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
    $this->pdf->Cell(180, 1,"Advanced Payment List- All    ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(25);$this->pdf->Cell(60, 1,"",'T',0, 'L', 0);
    $this->pdf->Ln(); 


    $this->pdf->setY(28);$this->pdf->SetFont('helvetica', '', 8);
    $this->pdf->Cell(180, 1,"Date From - ".$dfrom."  To - ".$dto,0,false, 'L', 0, '', 0, false, 'M', 'M');
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
   $this->pdf->setY(28);$this->pdf->SetFont('helvetica', '', 8);
   $this->pdf->Ln();
   $this->pdf->Cell(20, 6,'Cluster', '0', 0, 'L', 0);
   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
   $this->pdf->Cell(120, 6,"$cl_id - $cluster_name", '0', 0, 'L', 0);
   $this->pdf->Ln();
   $this->pdf->Cell(20, 6,'Branch', '0', 0, 'L', 0);
   $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
   $this->pdf->Cell(20, 6,"$bc_id - $branch_name", '0', 0, 'L', 0);
       
    
    // var_dump($r_branch_name);
    // exti();

   
         // Headings

         $this->pdf->SetFont('helvetica', 'B', 8);
         $this->pdf->Ln();
         $this->pdf->SetX(16);


         $this->pdf->SetFont('helvetica','B',8);
         $this->pdf->Cell(18, 6,"Date", '1', 0, 'C', 0);
         $this->pdf->Cell(30, 6,"No", '1', 0, 'C', 0);
         $this->pdf->Cell(30, 6,"CN No ", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Customer Id", '1', 0, 'C', 0);
         $this->pdf->Cell(40, 6,"Customer Name", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Expire Date", '1', 0, 'C', 0);
         $this->pdf->Cell(25, 6,"Amount", '1', 0, 'C', 0);
          $this->pdf->Ln();


         // Deatils loop            
         foreach($r_advanced_payment_list as $row){
         $this->pdf->SetX(16);
         $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
         $this->pdf->SetFont('helvetica','',7);
         $this->pdf->Cell(18, 6,$row->ddate, '1', 0, 'L', 0);
         $this->pdf->Cell(30, 6,$row->nno, '1', 0, 'R', 0);
         $this->pdf->Cell(30, 6,$row->cn_no, '1', 0, 'R', 0);
         $this->pdf->Cell(20, 6,$row->nic, '1', 0, 'L', 0);
         $this->pdf->Cell(40, 6,$row->name, '1', 0, 'L', 0);
         $this->pdf->Cell(20, 6,$row->expire_date, '1', 0, 'L', 0);
         $this->pdf->Cell(25, 6,$row->total_amount, '1', 0, 'R', 0);
          $this->pdf->Ln();

        $t_amount=$t_amount+$row->total_amount;
    
                  
         }

         

        // total
         $this->pdf->Ln();
         $this->pdf->Ln();
         $this->pdf->SetFont('helvetica','B',7);
         $this->pdf->Cell(18, 6,'', '0', 0, 'C', 0);
         $this->pdf->Cell(30, 6,'', '0', 0, 'C', 0);
         $this->pdf->Cell(30, 6,'', '0', 0, 'C', 0);
         $this->pdf->Cell(20, 6,'', '0', 0, 'C', 0);
         $this->pdf->Cell(40, 6,'', '0', 0, 'C', 0);
         $this->pdf->Cell(25, 6,'Total', '0', 0, 'C', 0);
         $this->pdf->Cell(22, 6,"Rs  ".number_format($t_amount,2), 'T,B', 0, 'R', 0);  
         $this->pdf->Ln();
         $this->pdf->Ln();

    //----------------------------------------------------------

         
         

        $this->pdf->Output("Advanced_Payment_list".date('Y-m-d').".pdf", 'I');

?>
        


