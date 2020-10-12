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
    $this->pdf->Cell(180, 1,"Recievable Invoice - Details   ",0,false, 'L', 0, '', 0, false, 'M', 'M');
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

         $this->pdf->SetFont('helvetica', 'B', 10);
         $this->pdf->Ln();
         $this->pdf->SetX(16);

         $this->pdf->Cell(10, 6,"No", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
         $this->pdf->Cell(40, 6,"Type", '1', 0, 'C', 0);
         $this->pdf->Cell(85, 6,"Description", '1', 0, 'C', 0);
         $this->pdf->Cell(25, 6,"Acc No", '1', 0, 'C', 0);
         $this->pdf->Cell(60, 6,"Acc Name", '1', 0, 'C', 0);
         $this->pdf->Cell(25, 6,"Amount", '1', 0, 'C', 0);
         $this->pdf->Ln();

         // Deatils loop            
        foreach($r_payable_invoice_details as $row){

          $no;
          $x;
          $y;
          $R_no=0;


         if($no==0) 
         {
            $this->pdf->SetX(16);
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
            $this->pdf->SetFont('helvetica','B',9);

             $aa = $this->pdf->getNumLines($row->description, 85);
             $bb=$this->pdf->getNumLines($row->ac_name, 60); 

            if($bb>$aa)
              $heigh=5*$bb;
            else
              $heigh=5*$aa;

               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
               $this->pdf->MultiCell(10, $heigh, $row->no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(20, $heigh, $row->date,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(40, $heigh, $row->r_type,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(85, $heigh, $row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->receivable_account, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(60, $heigh, $row->ac_name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->total, 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
               
             
               $this->pdf->SetX(16);
               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
               $this->pdf->SetFont('helvetica','',9);

              $aa = $this->pdf->getNumLines($row->description, 85);
              $bb=$this->pdf->getNumLines($row->d_ac_name, 60); 

              if($bb>$aa)
                $heigh=4*$bb;
              else
                $heigh=4*$aa;

               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
               $this->pdf->MultiCell(10, $heigh, '', 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(20, $heigh, '',1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(40, $heigh, '', 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(85, $heigh, '', 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->account_code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(60, $heigh, $row->d_ac_name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->amount,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
               

              $x=$row->no;
              $detail_no=$row->D_nno;
              $no=2;

          }

         else{
              $R_no=$row->D_nno;
              if($R_no==$x)
              {
                
                 
                  $this->pdf->SetX(16);
                  $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
                  $this->pdf->SetFont('helvetica','',9);


              $aa = $this->pdf->getNumLines($row->description, 85);
              $bb=$this->pdf->getNumLines($row->d_ac_name, 60); 

              if($bb>$aa)
                $heigh=4*$bb;
              else
                $heigh=4*$aa;

               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
               $this->pdf->MultiCell(10, $heigh, '',1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(20, $heigh, '',1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(40, $heigh, '', 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(85, $heigh, '',1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->account_code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(60, $heigh, $row->d_ac_name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->amount,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
               
                        
                   $R_no=$row->no;
                   $detail_no=$row->D_nno;
                    
              } 
                 $y++; 

            }
       
              if($no>0)
            {
              
            if($no==$R_no) 
         {
            $this->pdf->SetX(16);
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
            $this->pdf->SetFont('helvetica','B',9);

            $aa = $this->pdf->getNumLines($row->description, 85);
            $bb=$this->pdf->getNumLines($row->ac_name, 60); 

            if($bb>$aa)
              $heigh=4*$bb;
            else
              $heigh=4*$aa;

              $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
              $this->pdf->MultiCell(10, $heigh, $row->no, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
              $this->pdf->MultiCell(20, $heigh, $row->date, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
              $this->pdf->MultiCell(40, $heigh, $row->r_type, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
              $this->pdf->MultiCell(85, $heigh, $row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
              $this->pdf->MultiCell(25, $heigh, $row->receivable_account, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
              $this->pdf->MultiCell(60, $heigh, $row->ac_name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
              $this->pdf->MultiCell(25, $heigh, $row->total,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
              

            $this->pdf->SetX(16);
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
            $this->pdf->SetFont('helvetica','',9);

            $aa = $this->pdf->getNumLines($row->description, 85);
              $bb=$this->pdf->getNumLines($row->d_ac_name, 60); 

              if($bb>$aa)
                $heigh=4*$bb;
              else
                $heigh=4*$aa;

               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
               $this->pdf->MultiCell(10, $heigh, '',1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(15, $heigh, '',1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(40, $heigh, '',1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(85, $heigh, '', 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->account_code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(60, $heigh, $row->d_ac_name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->amount, 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
               
            $x=$row->no;
            $detail_no=$row->D_nno;
            $no++;

            }
           


               
            }
         
         

         }

       
    //----------------------------------------------------------

         
         

        $this->pdf->Output("recievable_invoice_details".date('Y-m-d').".pdf", 'I');
           
?>
        


