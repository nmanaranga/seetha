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
    
    $this->pdf->setY(22);
    $this->pdf->SetFont('helvetica', 'BUI',10);
    $this->pdf->Cell(180, 1,"Cancelled Advanced Payment List- All    ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();
    //----------------------------------------------------------------------------------------------------
    
    foreach($r_branch_name as $row){
        
       $branch_name=$row->name;
       $cluster_name=$row->description;
       $cl_id=$row->code;
       $bc_id=$row->bc;
}

//===================================================================================
 //Filter to given cluster & branch
  if(($cluster1!="0") && ($branch1!="0"))
  { 
    if($dfrom!=""){
       $this->pdf->SetX(20);
       $this->pdf->setY(30);
       $this->pdf->SetFont('helvetica', '',10);
       $this->pdf->Cell(0, 5, 'Date   From '. $dfrom.' To '.$dto ,0,false, 'L', 0, '', 0, false, 'M', 'M');
       $this->pdf->Ln();
     }
    

     $this->pdf->SetX(20);
     $this->pdf->setY(35);
     $this->pdf->SetFont('helvetica', '', 10);
     $this->pdf->Cell(30, 6,'Cluster', '0', 0, 'L', 0);
     $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
     $this->pdf->Cell(120, 6,"$cl_id - $cluster_name", '0', 0, 'L', 0);
     $this->pdf->Ln();

     $this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
     $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
     $this->pdf->Cell(20, 6,"$bc_id - $branch_name", '0', 0, 'L', 0);
     $this->pdf->Ln();

       
      // if($acc!=""){
      //  $this->pdf->SetFont('helvetica', '', 10);
      //  $this->pdf->Cell(30, 6,'Supplier', '0', 0, 'L', 0);
      //  $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
      //  $this->pdf->Cell(55, 6,$acc."  -  ".$acc_des, '0', 0, 'L', 0);
      //  $this->pdf->Ln();
      // }
        
      if($t_no_from!=""){
         $this->pdf->SetFont('helvetica', '', 10);
         $this->pdf->Cell(30, 6,'Transaction Range     ', '0', 0, 'L', 0);
         $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
         $this->pdf->Cell(15, 6,"From  -  ".$t_no_from, '0', 0, 'L', 0);

      }
        
      if($t_no_to!="")
      {
        $this->pdf->Cell(40, 6,"   To  -  ".$t_no_to, '0', 0, 'L', 0);
        $this->pdf->Ln(); 
      }

      // Headings

         $this->pdf->SetFont('helvetica', 'B', 8);
         $this->pdf->Ln();
         $this->pdf->SetX(15);


         $this->pdf->SetFont('helvetica','B',8);
         $this->pdf->Cell(18, 6,"Date", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"No", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"CN No ", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Customer Id", '1', 0, 'C', 0);
         $this->pdf->Cell(40, 6,"Customer Name", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Expire Date", '1', 0, 'C', 0);
         $this->pdf->Cell(25, 6,"Amount", '1', 0, 'C', 0);
         $this->pdf->Ln();

          // Deatils loop            
         foreach($r_advanced_payment_list as $row){
         $this->pdf->SetX(15);
         $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
         $this->pdf->SetFont('helvetica','',7);

         $aa = $this->pdf->getNumLines($row->name, 40);
         $heigh=6*$aa;

         $this->pdf->MultiCell(18, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(20, $heigh, $row->nno,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(20, $heigh, $row->cn_no,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(20, $heigh, $row->nic,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(40, $heigh, $row->name,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(20, $heigh, $row->expire_date,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(25, $heigh, $row->total_amount,1, 'R', 0, 1, '', '', true, 0, false, true, 0);
                 

        $t_amount=$t_amount+$row->total_amount;
 
    }
      // total
     $this->pdf->Ln();
     $this->pdf->SetFont('helvetica','B',7);
     $this->pdf->Cell(18, 6,"", '0', 0, 'C', 0);
     $this->pdf->Cell(20, 6,'', '0', 0, 'C', 0);
     $this->pdf->Cell(20, 6,'', '0', 0, 'C', 0);
     $this->pdf->Cell(20, 6,'', '0', 0, 'C', 0);
     $this->pdf->Cell(40, 6,'', '0', 0, 'C', 0);
     $this->pdf->Cell(20, 6,'Total', '0', 0, 'C', 0);
     $this->pdf->Cell(25, 6,"Rs  ".number_format($t_amount,2), 'T,B', 0, 'R', 0);  
     $this->pdf->Ln();
     $this->pdf->Ln();

     $t_amount=0;
  //------------------ End 1-----------------------------       
  }
//===================================================================================

if(($cluster1!="0") && ($branch1=="0"))//check cluster only
  {
    $b1=0; //$b1-1st row
    $xx=0; //row counts
    $old_branch="";
    foreach($r_advanced_payment_list as $row)
    {
      $row_count=$row->counts;
      //first row--------------------------
        if($b1==0)
        {
          if($dfrom!=""){
             $this->pdf->SetX(20);
             $this->pdf->setY(30);
             $this->pdf->SetFont('helvetica', '',10);
             $this->pdf->Cell(0, 5, 'Date   From '. $dfrom.' To '.$dto ,0,false, 'L', 0, '', 0, false, 'M', 'M');
             $this->pdf->Ln();
           }
    

             $this->pdf->SetX(20);
             $this->pdf->setY(30);$this->pdf->SetFont('helvetica', '', 10);
             $this->pdf->Ln();
             $this->pdf->Cell(30, 6,'Cluster', '0', 0, 'L', 0);
             $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
             $this->pdf->Cell(120, 6,"$cl_id - $cluster_name", '0', 0, 'L', 0);
             $this->pdf->Ln();

             $this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
             $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
             $this->pdf->Cell(20, 6,"$bc_id - $branch_name", '0', 0, 'L', 0);
             $this->pdf->Ln();

       
            // if($acc!=""){
            //  $this->pdf->SetFont('helvetica', '', 10);
            //  $this->pdf->Cell(30, 6,'Supplier', '0', 0, 'L', 0);
            //  $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
            //  $this->pdf->Cell(55, 6,$acc."  -  ".$acc_des, '0', 0, 'L', 0);
            //  $this->pdf->Ln();
            // }
        
            if($t_no_from!=""){
               $this->pdf->SetFont('helvetica', '', 10);
               $this->pdf->Cell(30, 6,'Transaction Range     ', '0', 0, 'L', 0);
               $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
               $this->pdf->Cell(15, 6,"From  -  ".$t_no_from, '0', 0, 'L', 0);

            }
        
            if($t_no_to!="")
            {
              $this->pdf->Cell(40, 6,"   To  -  ".$t_no_to, '0', 0, 'L', 0);
              $this->pdf->Ln(); 
            }

            // Headings

               $this->pdf->SetFont('helvetica', 'B', 9);
               $this->pdf->Ln();
               $this->pdf->SetX(15);


               $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
               $this->pdf->Cell(20, 6,"No", '1', 0, 'C', 0);
               $this->pdf->Cell(20, 6,"CN No ", '1', 0, 'C', 0);
               $this->pdf->Cell(25, 6,"Customer Id", '1', 0, 'C', 0);
               $this->pdf->Cell(40, 6,"Customer Name", '1', 0, 'C', 0);
               $this->pdf->Cell(20, 6,"Expire Date", '1', 0, 'C', 0);
               $this->pdf->Cell(25, 6,"Amount", '1', 0, 'C', 0);
               $this->pdf->Ln();

               $this->pdf->SetX(15);
               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
               $this->pdf->SetFont('helvetica','',9);

               $aa = $this->pdf->getNumLines($row->name, 40);
               $heigh=6*$aa;

               $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(20, $heigh, $row->nno,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(20, $heigh, $row->cn_no,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->nic,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(40, $heigh, $row->name,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(20, $heigh, $row->expire_date,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->total_amount,1, 'R', 0, 1, '', '', true, 0, false, true, 0);
                       

              $t_amount=$t_amount+$row->total_amount;
              $b1++; 
              $old_branch=$row->bc; 
              $xx++;  
        }
        else //other rows
        {
          $branch=$row->bc;

          if($old_branch==$branch)
          {
            $this->pdf->SetX(15);
            $this->pdf->SetFont('helvetica','',9);

            $aa = $this->pdf->getNumLines($row->name, 40);
            $heigh=6*$aa;

           $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
           $this->pdf->MultiCell(20, $heigh, $row->nno,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
           $this->pdf->MultiCell(20, $heigh, $row->cn_no,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
           $this->pdf->MultiCell(25, $heigh, $row->nic,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
           $this->pdf->MultiCell(40, $heigh, $row->name,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
           $this->pdf->MultiCell(20, $heigh, $row->expire_date,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
           $this->pdf->MultiCell(25, $heigh, $row->total_amount,1, 'R', 0, 1, '', '', true, 0, false, true, 0);
           
           $t_amount=$t_amount+$row->total_amount;

            $xx++;
            $branch_total=0;
            $old_branch=$row->bc;
          }

          //---------------------next branch--------------------------------------
          if($old_branch!=$branch)
          {
            if($branch_total==0)
              {
                  $this->pdf->SetX(15);
                  $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
                  $this->pdf->SetFont('helvetica','B',10);

                   // total
                   $this->pdf->Ln();
                   $this->pdf->Cell(20, 6,"", '0', 0, 'C', 0);
                   $this->pdf->Cell(20, 6,'', '0', 0, 'C', 0);
                   $this->pdf->Cell(20, 6,'', '0', 0, 'C', 0);
                   $this->pdf->Cell(25, 6,'', '0', 0, 'C', 0);
                   $this->pdf->Cell(40, 6,'', '0', 0, 'C', 0);
                   $this->pdf->Cell(20, 6,'Total', '0', 0, 'C', 0);
                   $this->pdf->Cell(25, 6,"Rs  ".number_format($t_amount,2), 'T,B', 0, 'R', 0);  
                   $this->pdf->Ln();
                   $this->pdf->Ln();
              }
               //---------------End branch total--------------------------------------
            $t_amount=0;

             $this->pdf->SetFont('helvetica', 'B', 9);
             $this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
             $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
             $this->pdf->Cell(20, 6,$row->bc." - ".$row->bc_name, '0', 0, 'L', 0);
             $this->pdf->Ln();


             // Headings
             $this->pdf->SetFont('helvetica', 'B', 9);
             $this->pdf->Ln();
             $this->pdf->SetX(15);

              $this->pdf->SetFont('helvetica','B',8);
               $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
               $this->pdf->Cell(20, 6,"No", '1', 0, 'C', 0);
               $this->pdf->Cell(20, 6,"CN No ", '1', 0, 'C', 0);
               $this->pdf->Cell(25, 6,"Customer Id", '1', 0, 'C', 0);
               $this->pdf->Cell(40, 6,"Customer Name", '1', 0, 'C', 0);
               $this->pdf->Cell(20, 6,"Expire Date", '1', 0, 'C', 0);
               $this->pdf->Cell(25, 6,"Amount", '1', 0, 'C', 0);
               $this->pdf->Ln();

               $this->pdf->SetX(15);
               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
               $this->pdf->SetFont('helvetica','',9);

               $aa = $this->pdf->getNumLines($row->name, 40);
               $heigh=6*$aa;

               $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(20, $heigh, $row->nno,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(20, $heigh, $row->cn_no,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->nic,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(40, $heigh, $row->name,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(20, $heigh, $row->expire_date,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->total_amount,1, 'R', 0, 1, '', '', true, 0, false, true, 0);
                       

              $t_amount=$t_amount+$row->total_amount;
              $old_branch=$row->bc;
              $xx++; 
          }//end different branch 1row
      }
    }//end foreach

     if($row_count==$xx)// differant branch final branch total
    {

      $this->pdf->SetX(15);
      $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
  
      $this->pdf->SetFont('helvetica','B',10);             
      $this->pdf->SetX(15);

       // total
     $this->pdf->Ln();
     $this->pdf->Cell(20, 6,"", '0', 0, 'C', 0);
     $this->pdf->Cell(20, 6,'', '0', 0, 'C', 0);
     $this->pdf->Cell(20, 6,'', '0', 0, 'C', 0);
     $this->pdf->Cell(25, 6,'', '0', 0, 'C', 0);
     $this->pdf->Cell(40, 6,'', '0', 0, 'C', 0);
     $this->pdf->Cell(20, 6,'Total', '0', 0, 'C', 0);
     $this->pdf->Cell(25, 6,"Rs  ".number_format($t_amount,2), 'T,B', 0, 'R', 0);  
     $this->pdf->Ln();
     $this->pdf->Ln();
    //---------------End branch total---------------------

    }

  }// end of checking cluster
    
    //===============================End Cluster selecting=========================================
  if(($cluster1=="0") && ($branch1=="0"))
  {
    $xx=0; //row counts
    $old_branch="";
    $b1=0; //$b1-1st row

    foreach($r_advanced_payment_list as $row)
    {
      $row_count=$row->counts;
      //first row--------------------------
       if($b1==0)
      {
        if($dfrom!=""){
           $this->pdf->SetX(20);
           $this->pdf->setY(40);
           $this->pdf->SetFont('helvetica', '',10);
           $this->pdf->Cell(0, 5, 'Date   From '. $dfrom.' To '.$dto ,0,false, 'L', 0, '', 0, false, 'M', 'M');
           //$this->pdf->Ln();
         }

         if($acc!=""){
         
           $this->pdf->SetFont('helvetica', '', 8);
           $this->pdf->Cell(30, 6,'Supplier', '0', 0, 'L', 0);
           $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
           $this->pdf->Cell(55, 6,$acc."  -  ".$acc_des, '0', 0, 'L', 0);
           $this->pdf->Ln();
          }

            if($t_no_from!=""){
           
             $this->pdf->SetFont('helvetica', '', 8);
             $this->pdf->Cell(30, 6,'Transaction Range     ', '0', 0, 'L', 0);
             $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
             $this->pdf->Cell(30, 6,"From  -  ".$t_no_from, '0', 0, 'L', 0);

          }

          if($t_no_to!="")
          {
            $this->pdf->Cell(45, 6,"To  -  ".$t_no_to, '0', 0, 'L', 0);
            $this->pdf->Ln(); 
          }


           $this->pdf->SetX(20);
           //$this->pdf->setY(50);
           $this->pdf->SetFont('helvetica', 'B', 10);
           $this->pdf->Ln();
           $this->pdf->Cell(30, 6,'Cluster', '0', 0, 'L', 0);
           $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
           $this->pdf->Cell(120, 6,$row->cl." - ".$row->cl_description, '0', 0, 'L', 0);
           $this->pdf->Ln();
             
          //-----------------------first row--------------------------
          
           $this->pdf->SetFont('helvetica', 'B', 9);
           $this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
           $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
           $this->pdf->Cell(20, 6,$row->bc." - ".$row->bc_name, '0', 0, 'L', 0);
           $this->pdf->Ln();

           // Headings
             $this->pdf->SetFont('helvetica', 'B', 9);
             $this->pdf->Ln();
             $this->pdf->SetX(15);

             $this->pdf->SetFont('helvetica','B',9);
             $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
             $this->pdf->Cell(20, 6,"No", '1', 0, 'C', 0);
             $this->pdf->Cell(20, 6,"CN No ", '1', 0, 'C', 0);
             $this->pdf->Cell(25, 6,"Customer Id", '1', 0, 'C', 0);
             $this->pdf->Cell(40, 6,"Customer Name", '1', 0, 'C', 0);
             $this->pdf->Cell(20, 6,"Expire Date", '1', 0, 'C', 0);
             $this->pdf->Cell(25, 6,"Amount", '1', 0, 'C', 0);
             $this->pdf->Ln();

             $this->pdf->SetX(15);
             $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
             $this->pdf->SetFont('helvetica','',9);

             $aa = $this->pdf->getNumLines($row->name, 40);
             $heigh=6*$aa;

             $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
             $this->pdf->MultiCell(20, $heigh, $row->nno,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
             $this->pdf->MultiCell(20, $heigh, $row->cn_no,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
             $this->pdf->MultiCell(25, $heigh, $row->nic,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
             $this->pdf->MultiCell(40, $heigh, $row->name,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
             $this->pdf->MultiCell(20, $heigh, $row->expire_date,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
             $this->pdf->MultiCell(25, $heigh, $row->total_amount,1, 'R', 0, 1, '', '', true, 0, false, true, 0);
             
             $t_amount=$t_amount+$row->total_amount;
             $old_branch=$row->bc;
             $cl2=$row->cl;
             $nno=$row->nno;
             $branch_total1=0;
             $b1++;
             $xx++;         
      }
      //-----------------------end first row--------------------------
        $cl1=$row->cl;
        $N_branch=$row->bc;
        $nno1=$row->nno;
        if($cl1==$cl2)
        {
          if($old_branch==$N_branch)
          {
            if($nno1!=$nno)
            {
              $this->pdf->SetX(15);
              $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
              $this->pdf->SetFont('helvetica','',9);

              $aa = $this->pdf->getNumLines($row->name, 40);
              $heigh=6*$aa;

             $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
             $this->pdf->MultiCell(20, $heigh, $row->nno,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
             $this->pdf->MultiCell(20, $heigh, $row->cn_no,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
             $this->pdf->MultiCell(25, $heigh, $row->nic,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
             $this->pdf->MultiCell(40, $heigh, $row->name,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
             $this->pdf->MultiCell(20, $heigh, $row->expire_date,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
             $this->pdf->MultiCell(25, $heigh, $row->total_amount,1, 'R', 0, 1, '', '', true, 0, false, true, 0);
             
             $t_amount=$t_amount+$row->total_amount;
             $old_branch=$row->bc;
             $cl2=$row->cl;
             $branch_total1=0;
                
             $xx++;
            }
          }//same branch 

          if($old_branch!=$N_branch)
          {
            if($branch_total1==0)
            {
               $this->pdf->SetX(15);
               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
            
               $this->pdf->SetFont('helvetica','B',10);             
               $this->pdf->SetX(15);

                 // total
               $this->pdf->Ln();
               $this->pdf->Cell(20, 6,"", '0', 0, 'C', 0);
               $this->pdf->Cell(20, 6,'', '0', 0, 'C', 0);
               $this->pdf->Cell(20, 6,'', '0', 0, 'C', 0);
               $this->pdf->Cell(25, 6,'', '0', 0, 'C', 0);
               $this->pdf->Cell(40, 6,'', '0', 0, 'C', 0);
               $this->pdf->Cell(20, 6,'Total', '0', 0, 'C', 0);
               $this->pdf->Cell(25, 6,"Rs  ".number_format($t_amount,2), 'T,B', 0, 'R', 0);  
               $this->pdf->Ln();
               $this->pdf->Ln();

               $branch_total1=1;
               $t_amount=0;
               //---------------End branch total---------------------
            }

               $this->pdf->SetFont('helvetica', 'B', 9);
               $this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
               $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
               $this->pdf->Cell(20, 6,$row->bc." - ".$row->bc_name, '0', 0, 'L', 0);
               $this->pdf->Ln();

               // Headings
               $this->pdf->SetFont('helvetica', 'B', 9);
               $this->pdf->Ln();
               $this->pdf->SetX(15);

               $this->pdf->SetFont('helvetica','B',9);
               $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
               $this->pdf->Cell(20, 6,"No", '1', 0, 'C', 0);
               $this->pdf->Cell(20, 6,"CN No ", '1', 0, 'C', 0);
               $this->pdf->Cell(25, 6,"Customer Id", '1', 0, 'C', 0);
               $this->pdf->Cell(40, 6,"Customer Name", '1', 0, 'C', 0);
               $this->pdf->Cell(20, 6,"Expire Date", '1', 0, 'C', 0);
               $this->pdf->Cell(25, 6,"Amount", '1', 0, 'C', 0);
               $this->pdf->Ln();

               $this->pdf->SetX(15);
               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
               $this->pdf->SetFont('helvetica','',9);

               $aa = $this->pdf->getNumLines($row->name, 40);
               $heigh=6*$aa;

               $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(20, $heigh, $row->nno,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(20, $heigh, $row->cn_no,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->nic,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(40, $heigh, $row->name,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(20, $heigh, $row->expire_date,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->total_amount,1, 'R', 0, 1, '', '', true, 0, false, true, 0);
               
               $t_amount=$t_amount+$row->total_amount;
               $old_branch=$row->bc;
               $cl2=$row->cl;
               $nno=$row->nno;
               $branch_total1=0;
               $xx++;         
          }
   
        }//check same branch & cluster

        if($cl1!=$cl2)
          {
             if($branch_total1==0)
              {
                $this->pdf->SetX(15);
                $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
            
                $this->pdf->SetFont('helvetica','B',10);             
                $this->pdf->SetX(15);

                // total
                $this->pdf->Ln();
                $this->pdf->Cell(20, 6,"", '0', 0, 'C', 0);
                $this->pdf->Cell(20, 6,'', '0', 0, 'C', 0);
                $this->pdf->Cell(20, 6,'', '0', 0, 'C', 0);
                $this->pdf->Cell(25, 6,'', '0', 0, 'C', 0);
                $this->pdf->Cell(40, 6,'', '0', 0, 'C', 0);
                $this->pdf->Cell(20, 6,'Total', '0', 0, 'C', 0);
                $this->pdf->Cell(25, 6,"Rs  ".number_format($t_amount,2), 'T,B', 0, 'R', 0);  
                $this->pdf->Ln();
                $this->pdf->Ln();

                $y1=0; 
                $branch_total1=1;
                $t_amount=0;

                //---------------End branch total--------------------------------------
              }
              if($y1==0)
              {
                 $this->pdf->SetX(20);
                 //$this->pdf->setY(50);
                 $this->pdf->SetFont('helvetica', 'B', 10);
                 $this->pdf->Ln();
                 $this->pdf->Cell(30, 6,'Cluster', '0', 0, 'L', 0);
                 $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
                 $this->pdf->Cell(120, 6,$row->cl." - ".$row->cl_description, '0', 0, 'L', 0);
                 $this->pdf->Ln();
             
          
          
                 $this->pdf->SetFont('helvetica', 'B', 9);
                 $this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
                 $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
                 $this->pdf->Cell(20, 6,$row->bc." - ".$row->bc_name, '0', 0, 'L', 0);
                 $this->pdf->Ln();

                 // Headings
                   $this->pdf->SetFont('helvetica', 'B', 9);
                   $this->pdf->Ln();
                   $this->pdf->SetX(15);

                   $this->pdf->SetFont('helvetica','B',9);
                   $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
                   $this->pdf->Cell(20, 6,"No", '1', 0, 'C', 0);
                   $this->pdf->Cell(20, 6,"CN No ", '1', 0, 'C', 0);
                   $this->pdf->Cell(25, 6,"Customer Id", '1', 0, 'C', 0);
                   $this->pdf->Cell(40, 6,"Customer Name", '1', 0, 'C', 0);
                   $this->pdf->Cell(20, 6,"Expire Date", '1', 0, 'C', 0);
                   $this->pdf->Cell(25, 6,"Amount", '1', 0, 'C', 0);
                   $this->pdf->Ln();

                   $this->pdf->SetX(15);
                   $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
                   $this->pdf->SetFont('helvetica','',9);

                   $aa = $this->pdf->getNumLines($row->name, 40);
                   $heigh=6*$aa;

                   $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                   $this->pdf->MultiCell(20, $heigh, $row->nno,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                   $this->pdf->MultiCell(20, $heigh, $row->cn_no,1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                   $this->pdf->MultiCell(25, $heigh, $row->nic,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                   $this->pdf->MultiCell(40, $heigh, $row->name,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                   $this->pdf->MultiCell(20, $heigh, $row->expire_date,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                   $this->pdf->MultiCell(25, $heigh, $row->total_amount,1, 'R', 0, 1, '', '', true, 0, false, true, 0);
                   
                   $t_amount=$t_amount+$row->total_amount;
                   $old_branch=$row->bc;
                   $cl2=$row->cl;
                   $nno=$row->nno;
                   $branch_total1=0;
                   $y1++;
                   $xx++;         
              }
          }
     }//end foreach
     if($row_count==$xx)// differant branch final branch total
     {
          $this->pdf->SetX(15);
          $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
      
          $this->pdf->SetFont('helvetica','B',10);             
          $this->pdf->SetX(15);

          // total
          $this->pdf->Ln();
          $this->pdf->Cell(20, 6,"", '0', 0, 'C', 0);
          $this->pdf->Cell(20, 6,'', '0', 0, 'C', 0);
          $this->pdf->Cell(20, 6,'', '0', 0, 'C', 0);
          $this->pdf->Cell(25, 6,'', '0', 0, 'C', 0);
          $this->pdf->Cell(40, 6,'', '0', 0, 'C', 0);
          $this->pdf->Cell(20, 6,'Total', '0', 0, 'C', 0);
          $this->pdf->Cell(25, 6,"Rs  ".number_format($t_amount,2), 'T,B', 0, 'R', 0);  
          $this->pdf->Ln();
          $this->pdf->Ln();
          $this->pdf->SetX(15);
          $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
          $this->pdf->SetFont('helvetica','B',11);

          $this->pdf->Cell(90, 6, "Total Vouchers -    ".$row_count, '0', 0, 'R', 0);
                
     }
  }
  //============================================================================================  

    //----------------------------------------------------------

         
         

        $this->pdf->Output("Cancelled Advanced_Payment_list".date('Y-m-d').".pdf", 'I');

?>
        


