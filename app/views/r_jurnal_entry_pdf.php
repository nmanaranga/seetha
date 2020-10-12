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
    $this->pdf->Cell(180, 1,"Jurnal Entry  ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(25);$this->pdf->Cell(60, 1,"",'T',0, 'L', 0);
    $this->pdf->Ln(); 

    $this->pdf->setY(28);$this->pdf->SetFont('helvetica', '', 8);
    $this->pdf->Cell(180, 1,"Date From - ".$dfrom."  To - ".$dto,0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    //----------------------------------------------------------------------------------------------------

    // set data ------------------------------------------------------------------------------------------

     // Headings

         $this->pdf->SetFont('helvetica', 'B', 9);
         $this->pdf->Ln();
         $this->pdf->SetX(20);


         $this->pdf->SetFont('helvetica','B',9);
         $this->pdf->Cell(15, 6,"No", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
         $this->pdf->Cell(25, 6,"Type", '1', 0, 'C', 0);
         $this->pdf->Cell(45, 6,"Decription", '1', 0, 'C', 0);
         $this->pdf->Cell(25, 6,"Account Code", '1', 0, 'C', 0);
         $this->pdf->Cell(45, 6,"Account Name", '1', 0, 'C', 0);
         $this->pdf->Cell(45, 6,"Memo", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Dr", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Cr", '1', 0, 'C', 0);


                        
         $this->pdf->Ln();

         // Deatils loop            
         foreach($t_jurnal_sum as $row){
         $this->pdf->SetX(20);
         $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
         $this->pdf->SetFont('helvetica','',9);

         $aa = $this->pdf->getNumLines($row->dis, 45);
         $bb = $this->pdf->getNumLines($row->description, 45);
          if($aa>$bb){
            $heigh=5*$aa;
          }else{
            $heigh=5*$bb;
          }
         $this->pdf->MultiCell(15, $heigh,$row->no, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(20, $heigh,$row->date, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(25, $heigh,$row->journal_type, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(45, $heigh,$row->dis, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(25, $heigh,$row->account_code, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(45, $heigh,$row->description, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(45, $heigh,$row->memo, 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(20, $heigh,$row->dr_amount, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
         $this->pdf->MultiCell(20, $heigh,$row->cr_amount, 1, 'R', 0, 1, '', '', true, 0, false, true, 0);
         

        // $amount=$amount+$row->amount;
                  
         }

    $this->pdf->GetY(0);
    $this->pdf->SetFont('helvetica', 'B', 3);
    $this->pdf->Output("Jrunal Entry Report".date('Y-m-d').".pdf", 'I');

?>
        


