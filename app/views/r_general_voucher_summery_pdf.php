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
    
    $this->pdf->setY(22);$this->pdf->SetFont('helvetica', 'BUI',12);
    $this->pdf->Cell(300, 1,"General Voucher List - Summery         ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(25);
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
       $this->pdf->Cell(25, 6,"Voucher No", '1', 0, 'C', 0);
       $this->pdf->Cell(85, 6,"Description", '1', 0, 'C', 0);
       $this->pdf->Cell(30, 6,"Cash", '1', 0, 'C', 0);
       $this->pdf->Cell(30, 6,"Cheque", '1', 0, 'C', 0);
       $this->pdf->Cell(30, 6,"Total Paid", '1', 0, 'C', 0);
       $this->pdf->Ln();
       

      // Deatils loop            
      foreach($r_general_voucher_summery as $row){
       $this->pdf->SetX(15);
       $this->pdf->SetFont('helvetica','',9);

       $aa = $this->pdf->getNumLines($row->note, 85);
       $heigh=6*$aa;

       $cash=$row->cash_amount;
       $cheque=$row->cheque_amount;
       $totalamt=$cash+$cheque;

       $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
      
       $this->pdf->SetFont('helvetica', '', 9);
       $this->pdf->SetX(15);

       $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
       $this->pdf->MultiCell(25, $heigh, $row->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
       $this->pdf->MultiCell(85, $heigh, $row->note, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
       $this->pdf->MultiCell(30, $heigh, number_format($row->cash_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
       $this->pdf->MultiCell(30, $heigh, number_format($row->cheque_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
       $this->pdf->MultiCell(30, $heigh, number_format($totalamt,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
      
      $cash_tot+=$row->cash_amount;
      $cheque_tot+=$row->cheque_amount;
      $Tot_paid+=$totalamt;
        
              
    }
      $this->pdf->SetFont('helvetica', 'B', 9);
      $this->pdf->SetX(15);

      $this->pdf->MultiCell(20, $heigh, "",0, 'L', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(25, $heigh, "", 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(85, $heigh, "Total", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(30, $heigh, number_format($cash_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(30, $heigh, number_format($cheque_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(30, $heigh, number_format($Tot_paid,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);


      $this->pdf->Ln();
      $this->pdf->Ln();
      $this->pdf->Ln();

     
     $cash_tot=$cheque_tot=$Tot_paid=0;
     if(count($cancled)>0){       
          $x=0;
        }else{
          $x=2;
        }

     if($x==0)
     {
        $this->pdf->SetFont('helvetica', 'BU',12);
        $this->pdf->Cell(0, 5, 'Cancled Voucher List     ',0,false, 'L', 0, '', 0, false, 'M', 'M');
        $this->pdf->Ln();
        $this->pdf->Ln();
          
        $this->pdf->SetX(15);
        
        // Headings


       $this->pdf->SetFont('helvetica', 'B', 9);
       $this->pdf->Ln();
       $this->pdf->SetX(15);

             
       $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
       $this->pdf->Cell(25, 6,"Voucher No", '1', 0, 'C', 0);
       $this->pdf->Cell(85, 6,"Description", '1', 0, 'C', 0);
       $this->pdf->Cell(30, 6,"Cash", '1', 0, 'C', 0);
       $this->pdf->Cell(30, 6,"Cheque", '1', 0, 'C', 0);
       $this->pdf->Cell(30, 6,"Total Paid", '1', 0, 'C', 0);
       $this->pdf->Ln();
       
       $x++;    

     }
    
      // Deatils loop            
      foreach($cancled as $row)
      {
         $this->pdf->SetX(15);
         $this->pdf->SetFont('helvetica','',9);

         $aa = $this->pdf->getNumLines($row->note, 85);
         $heigh=6*$aa;

         $cash=$row->cash_amount;
         $cheque=$row->cheque_amount;
         $totalamt=$cash+$cheque;

         $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        
         $this->pdf->SetFont('helvetica', '', 9);
         $this->pdf->SetX(15);

         $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(25, $heigh, $row->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(85, $heigh, $row->note, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(30, $heigh, number_format($row->cash_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(30, $heigh, number_format($row->cheque_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(30, $heigh, number_format($totalamt,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
        
        $cash_tot+=$row->cash_amount;
        $cheque_tot+=$row->cheque_amount;
        $Tot_paid+=$totalamt;
          
              
      }
        
        $this->pdf->SetFont('helvetica', 'B', 9);
        $this->pdf->SetX(15);

        $this->pdf->MultiCell(20, $heigh, "",0, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(25, $heigh, "", 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(85, $heigh, "Total", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(30, $heigh, number_format($cash_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(30, $heigh, number_format($cheque_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(30, $heigh, number_format($Tot_paid,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);



    
  }
  //------------------ End 1-----------------------------

   //===================================================================================
  if(($cluster1!="0") && ($branch1=="0"))//check cluster only
  {
    $b1=0; //$b1-1st row
    $xx=0; //row counts
    $old_branch="";
    foreach ($r_general_voucher_summery as $row) 
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

         if($t_no_from!=""){
                 
                 $this->pdf->SetX(20);
                 $this->pdf->setY(39);
                 $this->pdf->SetFont('helvetica', '', 10);
                 $this->pdf->Cell(30, 6,'Transaction Range     ', '0', 0, 'L', 0);
                 $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
                 $this->pdf->Cell(20, 6,"From  -  ".$t_no_from, '0', 0, 'L', 0);

              }
        
              if($t_no_to!="")
              {
                $this->pdf->Cell(39, 6,"  To  -  ".$t_no_to, '0', 0, 'L', 0);
                $this->pdf->Ln(); 

              }

               $this->pdf->SetX(20);
               $this->pdf->setY(39);
               $this->pdf->SetFont('helvetica', 'B', 9);
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

                     
               $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
               $this->pdf->Cell(25, 6,"Voucher No", '1', 0, 'C', 0);
               $this->pdf->Cell(85, 6,"Description", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Cash", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Cheque", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Total Paid", '1', 0, 'C', 0);
               $this->pdf->Ln();
               
               $this->pdf->SetX(15);
               $this->pdf->SetFont('helvetica','',9);

               $aa = $this->pdf->getNumLines($row->note, 85);
               $heigh=6*$aa;

               $cash=$row->cash_amount;
               $cheque=$row->cheque_amount;
               $totalamt=$cash+$cheque;

               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
              
               $this->pdf->SetFont('helvetica', '', 9);
               $this->pdf->SetX(15);

               $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(85, $heigh, $row->note, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($row->cash_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($row->cheque_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($totalamt,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
              
              $cash_tot+=$row->cash_amount;
              $cheque_tot+=$row->cheque_amount;
              $Tot_paid+=$totalamt;
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

               $aa = $this->pdf->getNumLines($row->note, 85);
               $heigh=6*$aa;

               $cash=$row->cash_amount;
               $cheque=$row->cheque_amount;
               $totalamt=$cash+$cheque;

               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
              
               $this->pdf->SetFont('helvetica', '', 9);
               $this->pdf->SetX(15);

               $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(85, $heigh, $row->note, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($row->cash_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($row->cheque_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($totalamt,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
              
              $cash_tot+=$row->cash_amount;
              $cheque_tot+=$row->cheque_amount;
              $Tot_paid+=$totalamt;

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

                  $this->pdf->SetFont('helvetica','B',9);             
                  $this->pdf->SetX(15);

                  $this->pdf->MultiCell(20, $heigh, "",0, 'L', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(25, $heigh, "", 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(85, $heigh, "Total", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(30, $heigh, number_format($cash_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(30, $heigh, number_format($cheque_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(30, $heigh, number_format($Tot_paid,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);


                  $this->pdf->Ln();
                  $this->pdf->Ln();
                  $this->pdf->Ln();
              }
               //---------------End branch total--------------------------------------

               $cash_tot=$cheque_tot=$Tot_paid=0;

               $this->pdf->SetFont('helvetica', 'B', 9);
               $this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
               $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
               $this->pdf->Cell(20, 6,$row->bc." - ".$row->bc_name, '0', 0, 'L', 0);
               $this->pdf->Ln();


               // Headings
               $this->pdf->SetFont('helvetica', 'B', 9);
               $this->pdf->Ln();
               $this->pdf->SetX(15);

                     
               $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
               $this->pdf->Cell(25, 6,"Voucher No", '1', 0, 'C', 0);
               $this->pdf->Cell(85, 6,"Description", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Cash", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Cheque", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Total Paid", '1', 0, 'C', 0);
               $this->pdf->Ln();
               
               $this->pdf->SetX(15);
               $this->pdf->SetFont('helvetica','',9);

               $aa = $this->pdf->getNumLines($row->note, 85);
               $heigh=6*$aa;

               $cash=$row->cash_amount;
               $cheque=$row->cheque_amount;
               $totalamt=$cash+$cheque;

               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
              
               $this->pdf->SetFont('helvetica', '', 9);
               $this->pdf->SetX(15);

               $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(85, $heigh, $row->note, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($row->cash_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($row->cheque_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($totalamt,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
              
              $cash_tot+=$row->cash_amount;
              $cheque_tot+=$row->cheque_amount;
              $Tot_paid+=$totalamt;
               
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

      $this->pdf->MultiCell(20, $heigh, "",0, 'L', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(25, $heigh, "", 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(85, $heigh, "Total", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(30, $heigh, number_format($cash_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(30, $heigh, number_format($cheque_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(30, $heigh, number_format($Tot_paid,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);



      //---------------End branch total---------------------

    }
  }// end of checking cluster
    
    //===============================End Cluster selecting=========================================

      
  if(($cluster1=="0") && ($branch1=="0"))
  {
    $xx=0; //row counts
    $old_branch="";
    $b1=0; //$b1-1st row
    foreach ($r_general_voucher_summery as $row) 
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

         if($t_no_from!=""){
                 
                 $this->pdf->SetX(20);
                 $this->pdf->setY(39);
                 $this->pdf->SetFont('helvetica', '', 10);
                 $this->pdf->Cell(30, 6,'Transaction Range     ', '0', 0, 'L', 0);
                 $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
                 $this->pdf->Cell(20, 6,"From  -  ".$t_no_from, '0', 0, 'L', 0);

              }
        
              if($t_no_to!="")
              {
                $this->pdf->Cell(39, 6,"  To  -  ".$t_no_to, '0', 0, 'L', 0);
                $this->pdf->Ln(); 

              }

               $this->pdf->SetX(20);
               $this->pdf->setY(39);
               $this->pdf->SetFont('helvetica', 'B', 9);
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

                     
               $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
               $this->pdf->Cell(25, 6,"Voucher No", '1', 0, 'C', 0);
               $this->pdf->Cell(85, 6,"Description", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Cash", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Cheque", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Total Paid", '1', 0, 'C', 0);
               $this->pdf->Ln();
               
               $this->pdf->SetX(15);
               $this->pdf->SetFont('helvetica','',9);

               $aa = $this->pdf->getNumLines($row->note, 85);
               $heigh=6*$aa;

               $cash=$row->cash_amount;
               $cheque=$row->cheque_amount;
               $totalamt=$cash+$cheque;
              
               //details loop
               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
              
               $this->pdf->SetFont('helvetica', '', 9);
               $this->pdf->SetX(15);

               $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(85, $heigh, $row->note, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($row->cash_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($row->cheque_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($totalamt,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
              
              $cash_tot+=$row->cash_amount;
              $cheque_tot+=$row->cheque_amount;
              $Tot_paid+=$totalamt;
              
              $b1++; 
              $old_branch=$row->bc;
              $cl2=$row->cl;
              $nno=$row->nno;
              $xx++;                                                                                               

        }
            $cl1=$row->cl;
            $N_branch=$row->bc;
            $nno1=$row->nno;

        if($cl1==$cl2)
        {
          if($old_branch==$N_branch)
          {
            if($nno1!=$nno)
            {
              
              $cash=$row->cash_amount;
               $cheque=$row->cheque_amount;
               $totalamt=$cash+$cheque;

              //details loop
               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
              
               $this->pdf->SetFont('helvetica', '', 9);
               $this->pdf->SetX(15);
               $aa = $this->pdf->getNumLines($row->note, 85);
               $heigh=6*$aa;

               $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(85, $heigh, $row->note, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($row->cash_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($row->cheque_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($totalamt,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
              
              $cash_tot+=$row->cash_amount;
              $cheque_tot+=$row->cheque_amount;
              $Tot_paid+=$totalamt;

              $old_branch=$row->bc;
              $cl2=$row->cl;
              $branch_total1=0;

              $xx++;
            }
          }
          if($old_branch!=$N_branch)
          {
              if($branch_total1==0)
              {
                  $this->pdf->SetX(15);
                  $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
                  $this->pdf->SetFont('helvetica','B',10);
                    
                  $this->pdf->SetFont('helvetica','B',9);             
                  $this->pdf->SetX(15);

                  $this->pdf->MultiCell(20, $heigh, "",0, 'L', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(25, $heigh, "", 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(85, $heigh, "Total", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(30, $heigh, number_format($cash_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(30, $heigh, number_format($cheque_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(30, $heigh, number_format($Tot_paid,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);


                  $this->pdf->Ln();
                  $this->pdf->Ln();
                  $this->pdf->Ln();
              }
               //---------------End branch total--------------------------------------

                $cash=$cheque=$Tot_paid=$totalamt=$cash_tot=$cheque_tot=0;

               $this->pdf->SetFont('helvetica', 'B', 9);
               $this->pdf->Cell(30, 6,'Branch', '0', 0, 'L', 0);
               $this->pdf->Cell(5, 6,':', '0', 0, 'L', 0);
               $this->pdf->Cell(20, 6,$row->bc." - ".$row->bc_name, '0', 0, 'L', 0);
               $this->pdf->Ln();


               // Headings
               $this->pdf->SetFont('helvetica', 'B', 9);
               $this->pdf->Ln();
               $this->pdf->SetX(15);

                     
               $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
               $this->pdf->Cell(25, 6,"Voucher No", '1', 0, 'C', 0);
               $this->pdf->Cell(85, 6,"Description", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Cash", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Cheque", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Total Paid", '1', 0, 'C', 0);
               $this->pdf->Ln();
               
               $this->pdf->SetX(15);
               $this->pdf->SetFont('helvetica','',9);

               $aa = $this->pdf->getNumLines($row->note, 85);
               $heigh=6*$aa;

               $cash=$row->cash_amount;
               $cheque=$row->cheque_amount;
               $totalamt=$cash+$cheque;

               //details loop
               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
              
               $this->pdf->SetFont('helvetica', '', 9);
               $this->pdf->SetX(15);

               $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(85, $heigh, $row->note, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($row->cash_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($row->cheque_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($totalamt,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
              
              $cash_tot+=$row->cash_amount;
              $cheque_tot+=$row->cheque_amount;
              $Tot_paid+=$totalamt;
              
              $cl2=$row->cl;
              $branch_total1=0;
              $nno=$row->nno;
              $xx++;
          }//differant branch

        }//same cluster
        if($cl1!=$cl2)
        {
          if($branch_total1==0)
              {
                  $this->pdf->SetX(15);
                  $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
                  $this->pdf->SetFont('helvetica','B',10);
   
                  $this->pdf->SetFont('helvetica','B',9);             
                  $this->pdf->SetX(15);

                  $this->pdf->MultiCell(20, $heigh, "",0, 'L', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(25, $heigh, "", 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(85, $heigh, "Total", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(30, $heigh, number_format($cash_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(30, $heigh, number_format($cheque_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                  $this->pdf->MultiCell(30, $heigh, number_format($Tot_paid,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);


                  $this->pdf->Ln();
                  $this->pdf->Ln();
                  $this->pdf->Ln();

                  $branch_total++;
                  $y1=0;
                  $cash=$cheque=$Tot_paid=$totalamt=$cash_tot=$cheque_tot=0;

              }
               //---------------End branch total--------------------------------------

              if($y1==0)
                {
                   

                  $this->pdf->SetX(20);
                 //$this->pdf->setY(70);
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

                //-----------------------first row--------------------------
               // Headings

               $this->pdf->SetFont('helvetica', 'B', 9);
               $this->pdf->Ln();
               $this->pdf->SetX(15);

                     
               $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
               $this->pdf->Cell(25, 6,"Voucher No", '1', 0, 'C', 0);
               $this->pdf->Cell(85, 6,"Description", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Cash", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Cheque", '1', 0, 'C', 0);
               $this->pdf->Cell(30, 6,"Total Paid", '1', 0, 'C', 0);
               $this->pdf->Ln();
               
               $this->pdf->SetX(15);
               $this->pdf->SetFont('helvetica','',9);

               $aa = $this->pdf->getNumLines($row->note, 85);
               $heigh=6*$aa;

               $cash=$row->cash_amount;
               $cheque=$row->cheque_amount;
               $totalamt=$cash+$cheque;
              
               //details loop
               $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
              
               $this->pdf->SetFont('helvetica', '', 9);
               $this->pdf->SetX(15);

               $this->pdf->MultiCell(20, $heigh, $row->ddate,1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(25, $heigh, $row->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(85, $heigh, $row->note, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($row->cash_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($row->cheque_amount,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
               $this->pdf->MultiCell(30, $heigh, number_format($totalamt,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
              
              $cash_tot+=$row->cash_amount;
              $cheque_tot+=$row->cheque_amount;
              $Tot_paid+=$totalamt;
              
              $old_branch=$row->bc;
              $cl2=$row->cl;
              $branch_total1=0;
              $nno=$row->nno;

              $xx++;
              $y1++;

               }

        }//differant cluster
    }//end foreach

    if($row_count==$xx)// differant branch final branch total
    {
     
      $this->pdf->SetX(15);
      $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
      $this->pdf->SetFont('helvetica','B',10);

      $this->pdf->SetFont('helvetica','B',9);             
      $this->pdf->SetX(15);

      $this->pdf->MultiCell(20, $heigh, "",0, 'L', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(25, $heigh, "", 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(85, $heigh, "Total", 0, 'C', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(30, $heigh, number_format($cash_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(30, $heigh, number_format($cheque_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
      $this->pdf->MultiCell(30, $heigh, number_format($Tot_paid,2), 1, 'R', 0, 1, '', '', true, 0, false, true, 0);



     //---------------End branch total---------------------
    }
      $this->pdf->SetX(15);
      //$this->pdf->setY(50);
      $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
      $this->pdf->SetFont('helvetica','B',11);

      $this->pdf->Cell(90, 6, "Total General Vouchers Vouchers -    ".$row_count, '0', 0, 'R', 0);
     
  }
  //============================================================================================  

   
        $this->pdf->Output("general_reciept_summery".date('Y-m-d').".pdf", 'I');
           
?>
        


