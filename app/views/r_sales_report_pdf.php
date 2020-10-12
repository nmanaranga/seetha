<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintHeader(true,$type);
$this->pdf->setPrintFooter(true);

$this->pdf->SetFont('helvetica', 'B', 16);
$this->pdf->AddPage($orientation,"A2"); 

$branch_name="";

foreach($branch as $ress)
{
    $branch_name=$ress->name;
    $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
}
$this->pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

$this->pdf->setY(22);$this->pdf->SetFont('helvetica', 'BI',12);
$this->pdf->Cell(180, 1,"DEPARTMENT WISE SALES SUMMARY  ",0,false, 'L', 0, '', 0, false, 'M', 'M');
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
        $this->pdf->MultiCell(30, 15,"CATEGORY", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    }else{
        $this->pdf->GetX(0);
    }   
    $this->pdf->MultiCell(38, 15,strtoupper($row->description), 1, 'C', 0, 0, '', '', true, 0, false, true, 0);
    if($no=="0"){
        $width+=30;
    }else{
        $width+=35;
    }  
    $no++;
}
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->MultiCell(30, 10,"Total  ", 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
$this->pdf->Ln();
$no='0'; 
$final_cash=0; 
foreach($trading as $row){
    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    if($no=="0"){
        $this->pdf->SetX(8);
        $this->pdf->MultiCell(30, 5,"Cash Sales", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    }else{
        $this->pdf->GetX(0);
    }   
    $this->pdf->MultiCell(38, 5,$row->CashSales, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    if($no=="0"){
        $width+=30;
    }else{
        $width+=35;
    }  
    $no++;
    $final_cash+=(float)$row->CashSales;
}
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->MultiCell(30, 5,number_format($final_cash,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);

$this->pdf->Ln();
$no='0'; 
$final_cash_dis=0; 
foreach($trading as $row){
    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    if($no=="0"){
        $this->pdf->SetX(8);
        $this->pdf->MultiCell(30, 5,"Cash Discount", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    }else{
        $this->pdf->GetX(0);
    }   
    $this->pdf->MultiCell(38, 5,$row->CashDis, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    if($no=="0"){
        $width+=30;
    }else{
        $width+=35;
    }  
    $no++;
    $final_cash_dis+=(float)$row->CashDis;
}
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->MultiCell(30, 5,number_format($final_cash_dis,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);

$this->pdf->Ln();
$no='0';  
$final_tot_cash=0;
foreach($trading as $row){
    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    if($no=="0"){
        $this->pdf->SetX(8);
        $this->pdf->MultiCell(30, 5,"Total Cash", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    }else{
        $this->pdf->GetX(0);
    }   
    $this->pdf->MultiCell(38, 5,$row->TotCash, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    if($no=="0"){
        $width+=30;
    }else{
        $width+=35;
    }  
    $no++;
    $final_tot_cash+=(float)$row->TotCash;
}
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->MultiCell(30, 5,number_format($final_tot_cash,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);

$this->pdf->Ln();
$no='0'; 
$final_credit=0; 
foreach($trading as $row){
    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    if($no=="0"){
        $this->pdf->SetX(8);
        $this->pdf->MultiCell(30, 5,"Credit Sales", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    }else{
        $this->pdf->GetX(0);
    }   
    $this->pdf->MultiCell(38, 5,$row->CreditSales, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    if($no=="0"){
        $width+=30;
    }else{
        $width+=35;
    }  
    $no++;
    $final_credit+=(float)$row->CreditSales;
}
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->MultiCell(30, 5,number_format($final_credit,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);

$this->pdf->Ln();
$no='0'; 
$final_credit_dis=0; 
foreach($trading as $row){
    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    if($no=="0"){
        $this->pdf->SetX(8);
        $this->pdf->MultiCell(30, 5,"Credit Discount", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    }else{
        $this->pdf->GetX(0);
    }   
    $this->pdf->MultiCell(38, 5,$row->CreditDis, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    if($no=="0"){
        $width+=30;
    }else{
        $width+=35;
    }  
    $no++;
    $final_credit_dis+=(float)$row->CreditDis;
}
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->MultiCell(30, 5,number_format($final_credit_dis,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);

$this->pdf->Ln();
$no='0'; 
$final_credit=0; 
foreach($trading as $row){
    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    if($no=="0"){
        $this->pdf->SetX(8);
        $this->pdf->MultiCell(30, 5,"Total Credit", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    }else{
        $this->pdf->GetX(0);
    }   
    $this->pdf->MultiCell(38, 5,$row->TotCredit, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    if($no=="0"){
        $width+=30;
    }else{
        $width+=35;
    }  
    $no++;
    $final_credit+=(float)$row->TotCredit;
}
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->MultiCell(30, 5,number_format($final_credit,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);

$this->pdf->Ln();

$no='0'; 
$final_pos=0; 
foreach($trading as $row){
    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    if($no=="0"){
        $this->pdf->SetX(8);
        $this->pdf->MultiCell(30, 5,"Pos Sales", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    }else{
        $this->pdf->GetX(0);
    }   
    $this->pdf->MultiCell(38, 5,$row->PosSales, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    if($no=="0"){
        $width+=30;
    }else{
        $width+=35;
    }  
    $no++;
    $final_pos+=(float)$row->PosSales;
}
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->MultiCell(30, 5,number_format($final_pos,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);

$this->pdf->Ln();
$no='0'; 
$final_pos_dis=0; 
foreach($trading as $row){
    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    if($no=="0"){
        $this->pdf->SetX(8);
        $this->pdf->MultiCell(30, 5,"Pos Discount", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    }else{
        $this->pdf->GetX(0);
    }   
    $this->pdf->MultiCell(38, 5,$row->PosDis, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    if($no=="0"){
        $width+=30;
    }else{
        $width+=35;
    }  
    $no++;
    $final_pos_dis+=(float)$row->PosDis;
}
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->MultiCell(30, 5,number_format($final_pos_dis,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);

$this->pdf->Ln();
$no='0'; 
$final_pos=0; 
foreach($trading as $row){
    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    if($no=="0"){
        $this->pdf->SetX(8);
        $this->pdf->MultiCell(30, 5,"Total Pos", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    }else{
        $this->pdf->GetX(0);
    }   
    $this->pdf->MultiCell(38, 5,$row->TotPos, 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    if($no=="0"){
        $width+=30;
    }else{
        $width+=35;
    }  
    $no++;
    $final_pos+=(float)$row->TotPos;
}
$this->pdf->SetFont('helvetica','B',9);
$this->pdf->MultiCell(30, 5,number_format($final_pos,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);

$this->pdf->Ln();



$no='0'; 
$tot ='0';
$final_tot=0; 
foreach($trading as $row){
    $this->pdf->SetFont('helvetica','B',9);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    if($no=="0"){
        $this->pdf->SetX(8);
        $this->pdf->MultiCell(30, 5,"Net Sale", 1, 'L', 0, 0, '', '', true, 0, false, true, 0);
    }else{
        $this->pdf->GetX(0);
    }   
    $tot=(float)$row->TotCredit+(float)$row->TotCash+(float)$row->TotPos;
    $this->pdf->MultiCell(38, 5,number_format($tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);
    if($no=="0"){
        $width+=30;
    }else{
        $width+=35;
    }  
    $no++;
    $final_tot+=(float)$tot;
}
$this->pdf->MultiCell(30, 5,number_format($final_tot,2), 1, 'R', 0, 0, '', '', true, 0, false, true, 0);

$this->pdf->GetY(0);
$this->pdf->SetFont('helvetica', 'B', 3);
$this->pdf->Output("Sales Report".date('Y-m-d').".pdf", 'I');

?>



