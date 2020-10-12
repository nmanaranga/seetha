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
    $this->pdf->Cell(180, 1,"General Recipt Summery   ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(25);$this->pdf->Cell(60, 1,"",'T',0, 'L', 0);
    $this->pdf->Ln(); 


    $this->pdf->setY(28);$this->pdf->SetFont('helvetica', '',10);
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


         $this->pdf->SetFont('helvetica','B',8);
         $this->pdf->Cell(15, 6,"No", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
         $this->pdf->Cell(15, 6,"Type", '1', 0, 'C', 0);
         $this->pdf->Cell(15, 6,"Sub No", '1', 0, 'C', 0);
         $this->pdf->Cell(80, 6,"Description", '1', 0, 'C', 0);
         $this->pdf->Cell(25, 6,"Acc No", '1', 0, 'C', 0);
         $this->pdf->Cell(50, 6,"Acc Name", '1', 0, 'C', 0); 
         $this->pdf->Cell(20, 6,"Cash", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"C.Card", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Cheaque", '1', 0, 'C', 0);
         $this->pdf->Ln();
         $cash_tot=$card_tot=$chq_tot=(float)0;


         // Deatils loop            
         foreach($r_general_recipt_summery as $row){
         $this->pdf->SetX(16);
         $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
         $this->pdf->SetFont('helvetica','',9);
         $aa = $this->pdf->getNumLines($row->note, 80);
         $bb = $this->pdf->getNumLines($row->description, 50);
         if($aa>$bb){
             $heigh=5*$aa;
         }else{
             $heigh=5*$bb;
         }
         $this->pdf->MultiCell(15, $heigh,$row->nno, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(20, $heigh,$row->ddate, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(15, $heigh,$row->type, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(15, $heigh,$row->sub_no, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(80, $heigh,$row->note, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(25, $heigh,$row->paid_acc, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);

         $this->pdf->MultiCell(50, $heigh,$row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(20, $heigh,$row->cash_amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(20, $heigh,$row->pay_card, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(20, $heigh,$row->cheque_amount, 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
        
         
         $cash_tot+=(float)$row->cash_amount;
         $card_tot+=(float)$row->pay_card;
         $chq_tot+=(float)$row->cheque_amount;
         /* if($row->type=="cash")
          $this->pdf->Cell(25, 6,$row->cash_amount, '1', 0, 'R', 0);
         else
           $this->pdf->Cell(25, 6,$row->cheque_amount, '1', 0, 'R', 0);
         */
            //$this->pdf->Ln();
          }

            $this->pdf->Ln();   
            $this->pdf->SetFont('helvetica','B',8);             
            $this->pdf->SetX(16);
            $this->pdf->Cell(15, 6, "", '0', 0, 'L', 0);
            $this->pdf->Cell(20, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(15, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(15, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(80, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(25, 6, "", '0', 0, 'R', 0);
            $this->pdf->Cell(50, 6, "Total ", '0', 0, 'R', 0);
            $this->pdf->Cell(20, 6, number_format($cash_tot,2), 'TB', 0, 'R', 0);
            $this->pdf->Cell(20, 6, number_format($card_tot,2), 'TB', 0, 'R', 0);
            $this->pdf->Cell(20, 6, number_format($chq_tot,2), 'TB', 0, 'R', 0);
            $this->pdf->Ln();  
    //----------------------------------------------------------

         
         

        $this->pdf->Output("general_recipt_summery".date('Y-m-d').".pdf", 'I');

?>
        


