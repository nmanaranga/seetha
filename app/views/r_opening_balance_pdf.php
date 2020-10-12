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
    $this->pdf->Cell(180, 1,"Opening Balance Report  ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(25);$this->pdf->Cell(60, 1,"",'T',0, 'L', 0);
    $this->pdf->Ln(); 

    $this->pdf->setY(28);$this->pdf->SetFont('helvetica', '', 10);
    $this->pdf->Cell(180, 1,"Date From - ".$dfrom."  To - ".$dto,0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    //----------------------------------------------------------------------------------------------------


   $this->pdf->Ln();
   $this->pdf->Ln();
    foreach($r_branch_name as $row){
        
        $branch_name=$row->name;
        $cluster_name=$row->description;
}
   if(!empty($r_branch_name))
   {
     //displing cluster and branch
        
         $this->pdf->SetX(20);
         $this->pdf->setY(28);$this->pdf->SetFont('helvetica', '', 10);
         $this->pdf->Ln();
         $this->pdf->Cell(20, 6,'Cluster', '0', 0, 'L', 0);
         $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
         $this->pdf->Cell(120, 6,"$cluster_name", '0', 0, 'L', 0);
         $this->pdf->Ln();
         $this->pdf->Cell(20, 6,'Branch', '0', 0, 'L', 0);
         $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
         $this->pdf->Cell(20, 6,"$branch_name", '0', 0, 'L', 0);

   }


   
       
    
    // var_dump($r_branch_name);
    // exti();

   
         // Headings

         $this->pdf->SetFont('helvetica', 'B', 9);
         $this->pdf->Ln();
         $this->pdf->SetX(20);


         $this->pdf->SetFont('helvetica','B',9);
         $this->pdf->Cell(30, 6,"Code", '1', 0, 'C', 0);
         $this->pdf->Cell(50, 6,"Account Name", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Type", '1', 0, 'C', 0);
         $this->pdf->Cell(38, 6,"Dr", '1', 0, 'C', 0);
         $this->pdf->Cell(38, 6,"Cr", '1', 0, 'C', 0);
          $this->pdf->Ln();


         // Deatils loop            
         foreach($r_opening_balance as $row){
         $this->pdf->SetX(20);
         $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
         $this->pdf->SetFont('helvetica','',9);

          $aa = $this->pdf->getNumLines($row->account_code, 30);
          $bb = $this->pdf->getNumLines($row->description, 50);      
           if($aa>$bb){
                 $heigh=5*$aa;
          }else{
                 $heigh=5*$bb;
          }

         $this->pdf->MultiCell(30, $heigh,$row->account_code,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(50, $heigh,$row->description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(20, $heigh,$row->heading,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(38, $heigh,$row->dr_amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(38, $heigh,$row->cr_amount,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
         

        $dr_amount=$dr_amount+$row->dr_amount;
        $cr_amount=$cr_amount+$row->cr_amount;

        $Note=$row->note;
                  
         }

         

        // total
         $this->pdf->Ln();
         $this->pdf->Ln();
         $this->pdf->SetFont('helvetica','B',7);
         $this->pdf->Cell(30, 6,'', '0', 0, 'C', 0);
         $this->pdf->Cell(50, 6,'', '0', 0, 'C', 0);
         $this->pdf->Cell(25, 6,'Total', '0', 0, 'R', 0);
         $this->pdf->Cell(38, 6,"Rs  ".number_format($dr_amount,2), 'T,B', 0, 'R', 0);
         $this->pdf->Cell(2, 6,"", '0', 0, 'R', 0);
         $this->pdf->Cell(37, 6,"Rs  ".number_format($cr_amount,2), 'T,B', 0, 'R', 0);
         $this->pdf->Ln();
         $this->pdf->Ln();

    //----------------------------------------------------------

         
         $this->pdf->SetX(20);
         $this->pdf->SetFont('helvetica', 'BI',10);
         $this->pdf->Cell(120, 6,'Note', '0', 0, 'L', 0);
         $this->pdf->Ln();
         $this->pdf->Cell(5, 8,"", '0', 0, 'L', 0);
         $this->pdf->SetFont('helvetica',8);
         $this->pdf->Cell(120, 8,"$Note", '0', 0, 'L', 0);
         

        $this->pdf->GetY(0);
        $this->pdf->SetFont('helvetica', 'B', 3);


        $this->pdf->Output("Opening Balance Report".date('Y-m-d').".pdf", 'I');

?>
        


