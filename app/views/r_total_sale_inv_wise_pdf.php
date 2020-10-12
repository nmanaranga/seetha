<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
$this->pdf->setPrintHeader($header,$type,$duration);
$this->pdf->setPrintHeader(true,$type);
$this->pdf->setPrintFooter(true);
    //print_r($customer);
$this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage("L",$page);   // L or P amd page type A4 or A3
    foreach($branch as $ress){
        $branch_name=$ress->name;
        $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    }
    $this->pdf->Ln(); 

    $this->pdf->SetFont('helvetica', 'BU',10);
    $this->pdf->Cell(0, 5, 'Invoice Wise Total Sales Report  ',0,false, 'L', 0, '', 0, false, 'M', 'M');

    $this->pdf->Ln(4); 
    $this->pdf->SetFont('helvetica', 'B', 8);   
    $this->pdf->Cell(25, 1, "Date", '0', 0, 'L', 0);
    $this->pdf->SetFont('helvetica', '', 8);    
    $this->pdf->Cell(80, 1, ": From : ".$from."  To : ".$to, '0', 0, 'L', 0);

    if($cluster!="0"){
        $this->pdf->Ln();       
        $this->pdf->SetFont('helvetica', 'B', 8);
        $this->pdf->Cell(25, 1, "Cluster", '0', 0, 'L', 0);
        $this->pdf->SetFont('helvetica', '', 8);    
        $this->pdf->Cell(80, 1, ": ".$clus, '0', 0, 'L', 0);
    }

    if($branchs!="0"){
        $this->pdf->Ln();       
        $this->pdf->SetFont('helvetica', 'B', 8);
        $this->pdf->Cell(25, 1, "Branch", '0', 0, 'L', 0);
        $this->pdf->SetFont('helvetica', '', 8);    
        $this->pdf->Cell(80, 1, ": ".$bran, '0', 0, 'L', 0);
    }


    if($supplier!=""){
        $this->pdf->Ln();       
        $this->pdf->SetFont('helvetica', 'B', 8);
        $this->pdf->Cell(25, 1, "Supplier", '0', 0, 'L', 0);
        $this->pdf->SetFont('helvetica', '', 8);    
        $this->pdf->Cell(20, 1,": ".$supplier, '0', 0, 'L', 0);
    }
    $this->pdf->Ln(); 
    $this->pdf->Ln(); 

    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->setX(15);
    $this->pdf->Cell(18, 6,"Date", '1', 0, 'C', 0);
    $this->pdf->Cell(128, 6,"Customer", '1', 0, 'C', 0);
    $this->pdf->Cell(13, 6,"Type", '1', 0, 'C', 0);
    $this->pdf->Cell(20, 6,"No", '1', 0, 'C', 0);
    $this->pdf->Cell(18, 6,"Gross Price", '1', 0, 'C', 0);
    $this->pdf->Cell(18, 6,"Discount", '1', 0, 'C', 0);
    $this->pdf->Cell(18, 6,"Net Price", '1', 0, 'C', 0);
    $this->pdf->Cell(18, 6,"Sales Ret", '1', 0, 'C', 0);
    $this->pdf->Cell(18, 6,"Net Amount", '1', 0, 'C', 0);
    $this->pdf->Ln();

    $tot_cost=(float)0;
    $tot_dis=(float)0;
    $tot_net=(float)0;

    $s_type=0;

    foreach ($r_data as $value) {


        if($value->t_rate >0){
            $vat ="YES";
        }else{
            $vat ="NO";
        }
        if($value->cus_name!=""){
            $cus=" (".$value->cus_name ." - ".$value->cus_address.")";
        }
        $heigh=6*(max(1,$this->pdf->getNumLines($value->name ." - ".$value->address.$cus, 128)));
        $this->pdf->HaveMorePages($heigh);
        $this->pdf->setX(15);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        $this->pdf->SetFont('helvetica','',8);
        $this->pdf->MultiCell(18, $heigh,$value->ddate,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(128, $heigh,$value->name ." - ".$value->address.$cus,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(13, $heigh,$value->s_type,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(20, $heigh,$value->non,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(18, $heigh,number_format($value->purchase_price,2),'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(18, $heigh,number_format($value->discount,2),'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(18, $heigh,number_format($value->net_amount,2),'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(18, $heigh,number_format($value->ret_price,2),'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(18, $heigh,number_format($value->net_amount-($value->ret_price),2),'1', 'R', false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);

        $tot_cost+=(float)$value->purchase_price;
        $tot_dis+=(float)$value->discount;
        $tot_net+=(float)$value->net_amount;
        $tot_ret+=(float)($value->ret_price*$value->ret_qty);
        $tot_net_amo+=(float)($value->net_amount);

    }

    foreach ($add_data as $value) {
        if($value->is_add=='1'){
            $tot_add+=(float)$value->amount;
        }else if($value->is_add=='0'){
            $tot_ded+=(float)$value->amount;
        }

        if($value->is_add=='3'){
            $s_return+=(float)$value->amount;
        }
    }
    $this->pdf->Ln();
    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->setX(15);
    $this->pdf->Cell(150, 6,"", '0', 0, 'R', 0);
    $this->pdf->Cell(29, 6,"", '0', 0, 'R', 0);
    $this->pdf->Cell(18, 6,'', 'TB', 0, 'R', 0);
    $this->pdf->Cell(18, 6,'Sales Return', 'TB', 0, 'R', 0);
    $this->pdf->Cell(18, 6,number_format($s_return,2), 'TB', 0, 'R', 0);   
    $this->pdf->Cell(18, 6,'Additional', 'TB', 0, 'R', 0);
    $this->pdf->Cell(18, 6,number_format($tot_add-$tot_ded,2), 'TB', 0, 'R', 0); 

    $this->pdf->Ln();
    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->setX(15);
    $this->pdf->Cell(150, 6,"", '0', 0, 'R', 0);
    $this->pdf->Cell(29, 6,"Total", '0', 0, 'R', 0);
    $this->pdf->Cell(18, 6,number_format($tot_cost,2), 'TB', 0, 'R', 0);
    $this->pdf->Cell(18, 6,number_format($tot_dis,2), 'TB', 0, 'R', 0);
    $this->pdf->Cell(18, 6,number_format($tot_net,2), 'TB', 0, 'R', 0);   
    $this->pdf->Cell(18, 6,number_format($tot_ret,2), 'TB', 0, 'R', 0);
    $this->pdf->Cell(18, 6,number_format($tot_net_amo+($tot_add-$tot_ded)-$s_return,2), 'TB', 0, 'R', 0);      

    $this->pdf->Output("Item Wise Total Sales_".date('Y-m-d').".pdf", 'I');

    ?>