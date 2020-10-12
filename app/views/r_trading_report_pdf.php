<?php
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    $this->pdf->setPrintHeader($header,$type,$duration);
    $this->pdf->setPrintHeader(true,$type);
    $this->pdf->setPrintFooter(true);
    
    $this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage($orientation,$page); 

    $branch_name="";

    foreach($branch as $ress)
    {
        $branch_name=$ress->name;
        $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    }
    $this->pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
    
    $this->pdf->setY(22);$this->pdf->SetFont('helvetica', 'BI',12);
    $this->pdf->Cell(180, 1,"TRADING ACCOUNT  ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(25);$this->pdf->Cell(60, 1,"",'T',0, 'L', 0);
    $this->pdf->Ln(); 

    $this->pdf->setY(28);$this->pdf->SetFont('helvetica', '', 8);
    $this->pdf->Cell(180, 1,"Date From - ".$dfrom."  To - ".$dto,0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();


    $this->pdf->SetFont('helvetica', 'B', 9);
    $this->pdf->Ln();
    $this->pdf->SetX(20);

    
    
                    
    $this->pdf->Ln();
    $code="";
    $width=30;
    $no='0'; 

    foreach($trading as $row){
        $this->pdf->SetFont('helvetica','B',10);
        if($no=="0"){
            $this->pdf->SetX(8);
            $this->pdf->MultiCell(35, 10,"CATEGORY", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        }else{
            $this->pdf->GetX(0);
        }   
        $this->pdf->MultiCell(35, 10,strtoupper($row->description)."  ", 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        if($no=="0"){
            $width+=20;
        }else{
            $width+=30;
        }  
        $no++;
    }
    $this->pdf->Ln();
    $no='0';  
    foreach($trading as $row){
        $this->pdf->SetFont('helvetica','',9);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        if($no=="0"){
            $this->pdf->SetX(8);
            $this->pdf->MultiCell(35, 5,"Cash Sales", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        }else{
            $this->pdf->GetX(0);
        }   
        $this->pdf->MultiCell(35, 5,$row->CashSales, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        if($no=="0"){
            $width+=20;
        }else{
            $width+=30;
        }  
        $no++;
    }
    $this->pdf->Ln();
    $no='0';  
    foreach($trading as $row){
        $this->pdf->SetFont('helvetica','',9);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        if($no=="0"){
            $this->pdf->SetX(8);
            $this->pdf->MultiCell(35, 5,"Cash Discount", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        }else{
            $this->pdf->GetX(0);
        }   
        $this->pdf->MultiCell(35, 5,$row->CashDis, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        if($no=="0"){
            $width+=20;
        }else{
            $width+=30;
        }  
        $no++;
    }
    $this->pdf->Ln();
    $no='0';  
    foreach($trading as $row){
        $this->pdf->SetFont('helvetica','',9);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        if($no=="0"){
            $this->pdf->SetX(8);
            $this->pdf->MultiCell(35, 5,"Total Cash", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        }else{
            $this->pdf->GetX(0);
        }   
        $this->pdf->MultiCell(35, 5,$row->TotCash, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        if($no=="0"){
            $width+=20;
        }else{
            $width+=30;
        }  
        $no++;
    }
    $this->pdf->Ln();
    $no='0';  
    foreach($trading as $row){
        $this->pdf->SetFont('helvetica','',9);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        if($no=="0"){
            $this->pdf->SetX(8);
            $this->pdf->MultiCell(35, 5,"Credit Sales", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        }else{
            $this->pdf->GetX(0);
        }   
        $this->pdf->MultiCell(35, 5,$row->CreditSales, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        if($no=="0"){
            $width+=20;
        }else{
            $width+=30;
        }  
        $no++;
    }
    $this->pdf->Ln();
    $no='0';  
    foreach($trading as $row){
        $this->pdf->SetFont('helvetica','',9);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        if($no=="0"){
            $this->pdf->SetX(8);
            $this->pdf->MultiCell(35, 5,"Credit Discount", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        }else{
            $this->pdf->GetX(0);
        }   
        $this->pdf->MultiCell(35, 5,$row->CreditDis, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        if($no=="0"){
            $width+=20;
        }else{
            $width+=30;
        }  
        $no++;
    }
    $this->pdf->Ln();
    $no='0';  
    foreach($trading as $row){
        $this->pdf->SetFont('helvetica','',9);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        if($no=="0"){
            $this->pdf->SetX(8);
            $this->pdf->MultiCell(35, 5,"Total Credit", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        }else{
            $this->pdf->GetX(0);
        }   
        $this->pdf->MultiCell(35, 5,$row->TotCredit, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        if($no=="0"){
            $width+=20;
        }else{
            $width+=30;
        }  
        $no++;
    }
    $this->pdf->Ln();
    $no='0'; 
    $tot ='0'; 
    foreach($trading as $row){
        $this->pdf->SetFont('helvetica','B',9);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        if($no=="0"){
            $this->pdf->SetX(8);
            $this->pdf->MultiCell(35, 5,"Net Sale", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        }else{
            $this->pdf->GetX(0);
        }   
        $tot=(float)$row->TotCredit+(float)$row->TotCash;
        $this->pdf->MultiCell(35, 5,number_format($tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        if($no=="0"){
            $width+=20;
        }else{
            $width+=30;
        }  
        $no++;
    }
    $this->pdf->Ln();
    $no='0'; 
    $tot ='0'; 
    foreach($opening as $row){
        $this->pdf->SetFont('helvetica','',9);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        if($no=="0"){
            $this->pdf->SetX(8);
            $this->pdf->MultiCell(35, 5,"Opening Balance", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        }else{
            $this->pdf->GetX(0);
        }   
        $this->pdf->MultiCell(35, 5,$row->OPB, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        if($no=="0"){
            $width+=20;
        }else{
            $width+=30;
        }  
        $no++;
    }
    $this->pdf->Ln();
    $no='0'; 
    $tot ='0'; 
    foreach($opening as $row){
        $this->pdf->SetFont('helvetica','',9);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        if($no=="0"){
            $this->pdf->SetX(8);
            $this->pdf->MultiCell(35, 5,"Purchase", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        }else{
            $this->pdf->GetX(0);
        }   
        $this->pdf->MultiCell(35, 5,$row->Purchase, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        if($no=="0"){
            $width+=20;
        }else{
            $width+=30;
        }  
        $no++;
    }
    $this->pdf->Ln();
    $no='0'; 
    $tot ='0'; 
    foreach($opening as $row){
        $this->pdf->SetFont('helvetica','',9);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        if($no=="0"){
            $this->pdf->SetX(8);
            $this->pdf->MultiCell(35, 5,"Closing", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        }else{
            $this->pdf->GetX(0);
        }   
        $this->pdf->MultiCell(35, 5,$row->Closing, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        if($no=="0"){
            $width+=20;
        }else{
            $width+=30;
        }  
        $no++;
    }
    $this->pdf->Ln();
    $no='0'; 
    $tot ='0'; 
    foreach($opening as $row){
        $this->pdf->SetFont('helvetica','',9);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        if($no=="0"){
            $this->pdf->SetX(8);
            $this->pdf->MultiCell(35, 5,"Cost Of Sale", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        }else{
            $this->pdf->GetX(0);
        }   
        $this->pdf->MultiCell(35, 5,number_format(((float)$row->OPB+(float)$row->Purchase)-(float)$row->Closing,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        if($no=="0"){
            $width+=20;
        }else{
            $width+=30;
        }  
        $no++;
    }
    $this->pdf->Ln();
    $no='0'; 
    $tot ='0';
     
    $myarray = array();
    $myarray2 = array();
    foreach($trading as $r){
        $net=(float)$r->TotCredit+(float)$r->TotCash; 
        array_push($myarray, array(
        "netsale" => $net 
        ));
    }

    foreach($opening as $row){
        $gross=((float)$row->OPB+(float)$row->Purchase)-(float)$row->Closing;
        array_push($myarray2, array(
        "costsale" => $gross 
        ));
    }

    for($x=0; $x<count($myarray2); $x++){
        $tott =  $myarray[$x]['netsale'] - $myarray2[$x]['costsale']; 
      
        $this->pdf->SetFont('helvetica','B',9);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        if($no=="0"){
            $this->pdf->SetX(8);
            $this->pdf->MultiCell(35, 5,"Gross Profit", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
        }else{
            $this->pdf->GetX(0);
        }   
        $this->pdf->MultiCell(35, 5,number_format($tott,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
        if($no=="0"){
            $width+=20;
        }else{
            $width+=30;
        }  
        $no++;
     }

    $this->pdf->GetY(0);
    $this->pdf->SetFont('helvetica', 'B', 3);
    $this->pdf->Output("Trading Report".date('Y-m-d').".pdf", 'I');

?>
        


