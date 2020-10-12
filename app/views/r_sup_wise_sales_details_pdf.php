<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintHeader(true,$type);
$this->pdf->setPrintFooter(true);
$this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage("L",$page);   // L or P amd page type A4 or A3
    foreach($branch as $ress){
      $branch_name=$ress->name;
      $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    }
    $this->pdf->Ln(); 

    $this->pdf->SetFont('helvetica', 'BU',12);
    $this->pdf->Cell(0, 5, 'Category wise Summary Report  ',0,false, 'L', 0, '', 0, false, 'M', 'M');

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
    
    $sup='default';
    $s_cat='default_cat';
    $x=0;
    foreach ($r_data as $value) {
      $heigh=6*(max(1,$this->pdf->getNumLines($value->code,35),$this->pdf->getNumLines($value->description, 80)));
      $this->pdf->HaveMorePages($heigh);
      $this->pdf->setX(15);
      $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));


      if($sup=='default' || $sup!=$value->supplier){

        $this->pdf->HaveMorePages(6);
        if($x!=0){
          $this->pdf->setX(15);
          $this->pdf->HaveMorePages($heigh);
          $this->pdf->Cell(130, 6,"", '0', 0, 'R', 0);
          $this->pdf->Cell(55, 6,'Sub Total', 'TB', 0, 'R', 0);   
          $this->pdf->Cell(30, 6,number_format($amount,2), 'TB', 0, 'R', 0);    
          $this->pdf->Cell(20, 6,number_format($qty,2), 'TB', 0, 'R', 0);
        }
        $this->pdf->Ln();
        $qty=0;
        $amount=0;

        $this->pdf->HaveMorePages(6);
        if($x!=0){
         $this->pdf->setX(15);
         $this->pdf->HaveMorePages($heigh);
         $this->pdf->Cell(145, 6,"", '0', 0, 'R', 0);
         $this->pdf->Cell(55, 6,'Total', 'TB', 0, 'R', 0);   
         $this->pdf->Cell(30, 6,number_format($ramount,2), 'TB', 0, 'R', 0);    
         $this->pdf->Cell(20, 6,number_format($rqty,2), 'TB', 0, 'R', 0);
       }
       $this->pdf->Ln();
       $rqty=0;
       $ramount=0;

       $this->pdf->SetFont('helvetica','B',12);
       $this->pdf->HaveMorePages($heigh);
       $this->pdf->MultiCell(0, $heigh,$value->name,'0', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->Ln();
       $this->pdf->SetFont('helvetica','B',10);
       $this->pdf->HaveMorePages($heigh);
       if($s_cat=='default_cat' || $s_cat!=$value->main_category){


         $this->pdf->Ln();
         $this->pdf->SetFont('helvetica','B',11);
         $this->pdf->setX(15);
         $this->pdf->HaveMorePages($heigh);
         $this->pdf->Cell(30, 6,"Department", '1', 0, 'C', 0);
         $this->pdf->Cell(30, 6,"Main Category", '1', 0, 'C', 0);
         $this->pdf->Cell(55, 6,"Description", '1', 0, 'C', 0);
         $this->pdf->Cell(30, 6,"Sub category", '1', 0, 'C', 0);
         $this->pdf->Cell(55, 6,"Description", '1', 0, 'C', 0);
         $this->pdf->Cell(30, 6,"Sales Amount", '1', 0, 'C', 0);
         $this->pdf->Cell(20, 6,"Qty", '1', 0, 'C', 0);
         $this->pdf->Ln();

         $this->pdf->SetFont('helvetica','',10);
         $this->pdf->HaveMorePages($heigh);
         $this->pdf->MultiCell(30, $heigh,$value->dep_des,'TLR', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
         $this->pdf->MultiCell(30, $heigh,$value->main_category,'TLR', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
         $this->pdf->MultiCell(55, $heigh,$value->main_cat,'TLR', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
         $this->pdf->MultiCell(30, $heigh,$value->category,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
         $this->pdf->MultiCell(55, $heigh,$value->cat_name,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
         $this->pdf->MultiCell(30, $heigh,number_format($value->amount,2),'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
         $this->pdf->MultiCell(20, $heigh,$value->qty,'1', 'R', false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);
         $this->pdf->HaveMorePages($heigh);
       }else{

         $this->pdf->SetFont('helvetica','',10);
         $this->pdf->HaveMorePages($heigh);
         $this->pdf->MultiCell(30, $heigh,'','LR', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
         $this->pdf->MultiCell(30, $heigh,'','LR', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
         $this->pdf->MultiCell(55, $heigh,'','BLR', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
         $this->pdf->MultiCell(30, $heigh,$value->category,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
         $this->pdf->MultiCell(55, $heigh,$value->cat_name,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
         $this->pdf->MultiCell(30, $heigh,number_format($value->amount,2),'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
         $this->pdf->MultiCell(20, $heigh,$value->qty,'1', 'R', false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);
         $this->pdf->HaveMorePages($heigh);
       }
     }else{
      if($s_cat=='default_cat' || $s_cat!=$value->main_category){

       $this->pdf->HaveMorePages(6);
       if($x!=0){
         $this->pdf->setX(15);
         $this->pdf->HaveMorePages($heigh);
         $this->pdf->Cell(145, 6,"", 'T', 0, 'R', 0);
         $this->pdf->Cell(55, 6,'Total', 'TB', 0, 'R', 0);   
         $this->pdf->Cell(30, 6,number_format($amount,2), 'TB', 0, 'R', 0);    
         $this->pdf->Cell(20, 6,number_format($qty,2), 'TB', 0, 'R', 0);
       }
       $this->pdf->Ln();
       $qty=0;
       $amount=0;


       $this->pdf->Ln();
       $this->pdf->SetFont('helvetica','B',11);
       $this->pdf->setX(15);
       $this->pdf->HaveMorePages($heigh);
       $this->pdf->Cell(30, 6,"Department", '1', 0, 'C', 0);
       $this->pdf->Cell(30, 6,"Main Category", '1', 0, 'C', 0);
       $this->pdf->Cell(55, 6,"Description", '1', 0, 'C', 0);
       $this->pdf->Cell(30, 6,"Sub category", '1', 0, 'C', 0);
       $this->pdf->Cell(55, 6,"Description", '1', 0, 'C', 0);
       $this->pdf->Cell(30, 6,"Sales Amount", '1', 0, 'C', 0);
       $this->pdf->Cell(20, 6,"Qty", '1', 0, 'C', 0);
       $this->pdf->Ln();

       $this->pdf->SetFont('helvetica','',10);
       $this->pdf->HaveMorePages($heigh);
       $this->pdf->MultiCell(30, $heigh,$value->dep_des,'TLR', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->MultiCell(30, $heigh,$value->main_category,'TLR', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->MultiCell(55, $heigh,$value->main_cat,'TLR', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->MultiCell(30, $heigh,$value->category,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->MultiCell(55, $heigh,$value->cat_name,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->MultiCell(30, $heigh,number_format($value->amount,2),'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->MultiCell(20, $heigh,$value->qty,'1', 'R', false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);
       $this->pdf->HaveMorePages($heigh);
     }else{
      $this->pdf->SetFont('helvetica','',10);
      $this->pdf->HaveMorePages($heigh);
      $this->pdf->MultiCell(30, $heigh,'','LR', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
      $this->pdf->MultiCell(30, $heigh,'','LR', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
      $this->pdf->MultiCell(55, $heigh,'','LR', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
      $this->pdf->MultiCell(30, $heigh,$value->category,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
      $this->pdf->MultiCell(55, $heigh,$value->cat_name,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
      $this->pdf->MultiCell(30, $heigh,number_format($value->amount,2),'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
      $this->pdf->MultiCell(20, $heigh,$value->qty,'1', 'R', false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);
      $this->pdf->HaveMorePages($heigh);
    }

  }
  $qty+=(float)$value->qty;
  $amount+=(float)$value->amount;

  $rqty+=(float)$value->qty;
  $ramount+=(float)$value->amount;

  $sup=$value->supplier;
  $s_cat=$value->main_category;

  $gqty+=(float)$value->qty;
  $gamount+=(float)$value->amount; 

  $x++;

}

$this->pdf->SetFont('helvetica','B',9);
$this->pdf->setX(15);
$this->pdf->Cell(145, 6,"", '0', 0, 'R', 0);
$this->pdf->Cell(55, 6,'Total', 'TB', 0, 'R', 0);   
$this->pdf->Cell(30, 6,number_format($amount,2), 'TB', 0, 'R', 0);      
$this->pdf->Cell(20, 6,number_format($qty,2), 'TB', 0, 'R', 0);
$this->pdf->Ln();

$this->pdf->SetFont('helvetica','B',9);
$this->pdf->setX(15);
$this->pdf->Cell(145, 6,"", '0', 0, 'R', 0);
$this->pdf->Cell(55, 6,'Grand Total', 'TB', 0, 'R', 0);   
$this->pdf->Cell(30, 6,number_format($gamount,2), 'TB', 0, 'R', 0);       
$this->pdf->Cell(20, 6,number_format($gqty,2), 'TB', 0, 'R', 0);

$this->pdf->Output("Supplier wise Details_".date('Y-m-d').".pdf", 'I');

?>