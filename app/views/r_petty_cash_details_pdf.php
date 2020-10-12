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
    $this->pdf->Cell(180, 1,"Petty Cash Details   ",0,false, 'L', 0, '', 0, false, 'M', 'M');
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
   $this->pdf->setY(22);$this->pdf->SetFont('helvetica', '', 10);
   $this->pdf->Ln();
   $this->pdf->Ln();
   $this->pdf->Cell(20,4,'Cluster', '0', 0, 'L', 0);
   $this->pdf->Cell(5, 4,':', '0', 0, 'L', 0);
   $this->pdf->Cell(120, 4,"$cl_id - $cluster_name", '0', 0, 'L', 0);
   $this->pdf->Ln();
   $this->pdf->Cell(20,4,'Branch', '0', 0, 'L', 0);
   $this->pdf->Cell(5, 4,':', '0', 0, 'L', 0);
   $this->pdf->Cell(20, 4,"$bc_id - $branch_name", '0', 0, 'L', 0);
       

   
         // Headings

         $this->pdf->SetFont('helvetica', 'B',9);
         $this->pdf->Ln();
         $this->pdf->SetX(16);


         $this->pdf->SetFont('helvetica','B',8);
         $this->pdf->Cell(25, 6,"No", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
         $this->pdf->Cell(80, 6,"Description ", '1', 0, 'C', 0);
         $this->pdf->Cell(50, 6,"Narretion", '1', 0, 'C', 0);
         $this->pdf->Cell(40, 6,"Category", '1', 0, 'C', 0);
         $this->pdf->Cell(25, 6,"Group", '1', 0, 'C', 0);
         $this->pdf->Cell(25, 6,"Amount", '1', 0, 'C', 0);
         $this->pdf->Ln();
      

       
    
        foreach($r_petty_cash_details as $row){
           $no;
           $x;
           $y;
           
           $R_no=0;
          
          //print 1st row
        if($no==0) 
         {
            $this->pdf->SetX(16);
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
            $this->pdf->SetFont('helvetica','B',9);

            $aa = $this->pdf->getNumLines($row->s_description, 80);
            $bb=$this->pdf->getNumLines($row->narration, 50); 

            if($bb>$aa)
              $heigh=4*$bb;
            else
              $heigh=4*$aa;

            $this->pdf->MultiCell(25, $heigh, $row->no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, $heigh, $row->date,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(80, $heigh, $row->s_description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(50, $heigh, $row->narration,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(40, $heigh, $row->description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh, $row->name,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh, $row->total, 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
            




            $this->pdf->SetX(16);
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
            $this->pdf->SetFont('helvetica','',9);

            $aa = $this->pdf->getNumLines($row->s_description, 80);
            $bb=$this->pdf->getNumLines($row->narration, 50); 

            if($bb>$aa)
              $heigh=4*$bb;
            else
              $heigh=4*$aa;

            $this->pdf->MultiCell(25, $heigh, '',  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, $heigh, '',  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(80, $heigh, $row->d_description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(50, $heigh, $row->narration,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(40, $heigh, $row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh, $row->name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh, $row->amount,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
            

            $x=$row->no;
            $detail_no=$row->d_no;
            $no=2;
         
            }else{
              $R_no=$row->d_no;
              if($R_no==$x)
              {
                
                 
                  $this->pdf->SetX(16);
                  $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
                  $this->pdf->SetFont('helvetica','',9);

                  $aa = $this->pdf->getNumLines($row->s_description, 80);
                  $bb=$this->pdf->getNumLines($row->narration, 50); 

                  if($bb>$aa)
                    $heigh=4*$bb;
                  else
                    $heigh=4*$aa;

                  $this->pdf->MultiCell(25, $heigh, '', 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(20, $heigh, '',  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(80, $heigh, $row->d_description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(50, $heigh, $row->narration,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(40, $heigh, $row->description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(25, $heigh, $row->name,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(25, $heigh, $row->amount,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
                  
                  
                   $R_no=$row->no;
                   $detail_no=$row->d_no;
                    
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

            $aa = $this->pdf->getNumLines($row->s_description, 80);
            $bb=$this->pdf->getNumLines($row->narration, 50); 

            if($bb>$aa)
              $heigh=4*$bb;
            else
              $heigh=4*$aa;

            $this->pdf->MultiCell(25, $heigh, $row->no,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, $heigh, $row->date,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(80, $heigh, $row->s_description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(50, $heigh, $row->narration,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(40, $heigh, $row->description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh, $row->name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh, $row->total,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
            
            


            $this->pdf->SetX(16);
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
            $this->pdf->SetFont('helvetica','',9);

            $aa = $this->pdf->getNumLines($row->s_description, 80);
            $bb=$this->pdf->getNumLines($row->narration, 50); 

            if($bb>$aa)
              $heigh=4*$bb;
            else
              $heigh=4*$aa;

            $this->pdf->MultiCell(25, $heigh, '',  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, $heigh, '',  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(80, $heigh, $row->d_description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(50, $heigh, $row->narration,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(40, $heigh, $row->description,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh, $row->name, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh, $row->amount, 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
            $this->pdf->Ln();
            
            $x=$row->no;
            $R_no=$row->d_no;
            $no++;
         
            }
    
            }

        }

            $this->pdf->Ln();   
            $this->pdf->SetFont('helvetica','B',8);             
            $this->pdf->SetX(16);
           /* $this->pdf->Cell(25, 6, "", '0', 0, 'L', 0);
            $this->pdf->Cell(20, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(80, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(50, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(40, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(25, 6, "Total ", '0', 0, 'R', 0);
            $this->pdf->Cell(25, 6, number_format($total,2), 'TB', 0, 'R', 0);*/
            $this->pdf->Ln();  

        $this->pdf->Output("petty_cash_details".date('Y-m-d').".pdf", 'I');
        //exit();

?>
        


