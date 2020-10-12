<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintHeader(true,$type);
$this->pdf->setPrintFooter(true);
$this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage("P",$page);   // L or P amd page type A4 or A3
    foreach($branch as $ress){
      $branch_name=$ress->name;
      $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    }
    $this->pdf->Ln(); 

    $this->pdf->SetFont('helvetica', 'BU',12);
    $this->pdf->Cell(0, 5, 'Salesman wise Department Summary Report  ',0,false, 'L', 0, '', 0, false, 'M', 'M');

    $this->pdf->Ln(4); 
    $this->pdf->SetFont('helvetica', 'B', 9);   
    $this->pdf->Cell(25, 1, "Date", '0', 0, 'L', 0);
    $this->pdf->SetFont('helvetica', '', 8);    
    $this->pdf->Cell(80, 1, ": From : ".$from."  To : ".$to, '0', 0, 'L', 0);

    if($cluster!="0"){
      $this->pdf->Ln();       
      $this->pdf->SetFont('helvetica', 'B', 9);
      $this->pdf->Cell(25, 1, "Cluster", '0', 0, 'L', 0);
      $this->pdf->SetFont('helvetica', '', 8);    
      $this->pdf->Cell(80, 1, ": ".$clus, '0', 0, 'L', 0);
    }

    if($branchs!="0"){
      $this->pdf->Ln();       
      $this->pdf->SetFont('helvetica', 'B', 9);
      $this->pdf->Cell(25, 1, "Branch", '0', 0, 'L', 0);
      $this->pdf->SetFont('helvetica', '', 8);    
      $this->pdf->Cell(80, 1, ": ".$bran, '0', 0, 'L', 0);
    }

    if($department!=""){
      $this->pdf->Ln();       
      $this->pdf->SetFont('helvetica', 'B', 9);
      $this->pdf->Cell(25, 1, "Department", '0', 0, 'L', 0);
      $this->pdf->SetFont('helvetica', '', 8);    
      $this->pdf->Cell(20, 1,": ".$department_des, '0', 0, 'L', 0);
    }else{
      $this->pdf->Ln();       
      $this->pdf->SetFont('helvetica', 'B', 9);
      $this->pdf->Cell(25, 1, "Department", '0', 0, 'L', 0);
      $this->pdf->SetFont('helvetica', '', 8);    
      $this->pdf->Cell(20, 1,": ALL", '0', 0, 'L', 0);
    } 
    if($main_category!=""){
      $this->pdf->Ln();       
      $this->pdf->SetFont('helvetica', 'B', 9);
      $this->pdf->Cell(25, 1, "Main Category", '0', 0, 'L', 0);
      $this->pdf->SetFont('helvetica', '', 8);    
      $this->pdf->Cell(20, 1,": ".$main_category_des, '0', 0, 'L', 0);
    }else{
      $this->pdf->Ln();       
      $this->pdf->SetFont('helvetica', 'B', 9);
      $this->pdf->Cell(25, 1, "Main Category", '0', 0, 'L', 0);
      $this->pdf->SetFont('helvetica', '', 8);    
      $this->pdf->Cell(20, 1,": ALL", '0', 0, 'L', 0);
    } 
    if($sub_category!=""){
      $this->pdf->Ln();       
      $this->pdf->SetFont('helvetica', 'B', 9);
      $this->pdf->Cell(25, 1, "Category", '0', 0, 'L', 0);
      $this->pdf->SetFont('helvetica', '', 8);    
      $this->pdf->Cell(20, 1,": ".$sub_category_des, '0', 0, 'L', 0);
    }else{
      $this->pdf->Ln();       
      $this->pdf->SetFont('helvetica', 'B', 9);
      $this->pdf->Cell(25, 1, "Category", '0', 0, 'L', 0);
      $this->pdf->SetFont('helvetica', '', 8);    
      $this->pdf->Cell(20, 1,": ALL", '0', 0, 'L', 0);
    }

    $this->pdf->Ln();
    
    $code='default';
    $x=0;
    foreach ($r_data as $value) {
      $heigh=6*(max(1,$this->pdf->getNumLines($value->name,70),$this->pdf->getNumLines($value->dep_des, 50)));
      $this->pdf->HaveMorePages($heigh);
      $this->pdf->setX(15);
      $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));


      if($code=='default' || $code!=$value->rep){

       if($x!=0){
         $this->pdf->setX(15);
         $this->pdf->HaveMorePages(6);
         $this->pdf->Cell(70, 6,"", 'T', 0, 'R', 0);
         $this->pdf->Cell(50, 6,'Total', 'TB', 0, 'R', 0);   
         $this->pdf->Cell(30, 6,number_format($amount,2), 'TB', 0, 'R', 0);    
         $this->pdf->Cell(30, 6,number_format($qty,2), 'TB', 0, 'R', 0);
       }
       $this->pdf->Ln();
       $qty=0;
       $amount=0;


       $this->pdf->Ln();
       $this->pdf->SetFont('helvetica','B',11);
       $this->pdf->setX(15);
       $this->pdf->HaveMorePages($heigh);
       $this->pdf->Cell(70, 6,"Salesman", '1', 0, 'C', 0);
       $this->pdf->Cell(50, 6,"Department", '1', 0, 'C', 0);
       $this->pdf->Cell(30, 6,"Value", '1', 0, 'C', 0);
       $this->pdf->Cell(30, 6,"Units", '1', 0, 'C', 0);
       $this->pdf->Ln();

       $this->pdf->SetFont('helvetica','',10);
       $this->pdf->HaveMorePages($heigh);
       $this->pdf->MultiCell(70, $heigh,$value->name,'TLR', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->MultiCell(50, $heigh,$value->dep_des,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->MultiCell(30, $heigh,number_format($value->amount,2),'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->MultiCell(30, $heigh,$value->qty,'1', 'R', false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);


     }else{

       $this->pdf->SetFont('helvetica','',10);
       $this->pdf->HaveMorePages($heigh);
       $this->pdf->MultiCell(70, $heigh,'','LR', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->MultiCell(50, $heigh,$value->dep_des,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->MultiCell(30, $heigh,number_format($value->amount,2),'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->MultiCell(30, $heigh,$value->qty,'1', 'R', false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->HaveMorePages($heigh);

     }
     $qty+=(float)$value->qty;
     $amount+=(float)$value->amount;

     $code=$value->rep;

     $gqty+=(float)$value->qty;
     $gamount+=(float)$value->amount; 

     $x++;

   }

   $this->pdf->SetFont('helvetica','B',9);
   $this->pdf->setX(15);
   $this->pdf->Cell(70, 6,"", '0', 0, 'R', 0);
   $this->pdf->Cell(50, 6,'Total', 'TB', 0, 'R', 0);   
   $this->pdf->Cell(30, 6,number_format($amount,2), 'TB', 0, 'R', 0);      
   $this->pdf->Cell(30, 6,number_format($qty,2), 'TB', 0, 'R', 0);
   $this->pdf->Ln();

   $this->pdf->SetFont('helvetica','B',9);
   $this->pdf->setX(15);
   $this->pdf->Cell(70, 6,"", '0', 0, 'R', 0);
   $this->pdf->Cell(50, 6,'Grand Total', 'TB', 0, 'R', 0);   
   $this->pdf->Cell(30, 6,number_format($gamount,2), 'TB', 0, 'R', 0);       
   $this->pdf->Cell(30, 6,number_format($gqty,2), 'TB', 0, 'R', 0);

   $this->pdf->Output("Saleman wise departmane summary_".date('Y-m-d').".pdf", 'I');

   ?>