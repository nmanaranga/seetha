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

    $this->pdf->SetFont('helvetica', 'BU',10);
    $this->pdf->Cell(0, 5, 'Employee Wise Item Commission Report',0,false, 'L', 0, '', 0, false, 'M', 'M');

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


    if($emp!=""){
        $this->pdf->Ln();       
        $this->pdf->SetFont('helvetica', 'B', 8);
        $this->pdf->Cell(25, 1, "Employee", '0', 0, 'L', 0);
        $this->pdf->SetFont('helvetica', '', 8);    
        $this->pdf->Cell(20, 1,": ".$emp_des, '0', 0, 'L', 0);
    }
    $this->pdf->Ln(); 
    $this->pdf->Ln(); 
    $this->pdf->Ln(); 



    $tot_cost=(float)0;
    $tot_dis=(float)0;
    $tot_net=(float)0;

    $rep='default';
    $x=0;
    foreach ($r_data as $value) {
        if($rep=='default'|| $rep!=$value->rep){
            if($x!=0){
                $this->pdf->SetFont('helvetica','B',8);
                $this->pdf->setX(15);
                $this->pdf->Cell(175, 6,"", '0', 0, 'R', 0);
                $this->pdf->Cell(25, 6,"Total", '0', 0, 'R', 0);
                $this->pdf->Cell(25, 6,number_format($price,2), 'TB', 0, 'R', 0);   
                $this->pdf->Cell(25, 6,number_format($value_of_item,2), 'TB', 0, 'R', 0);
                $this->pdf->Cell(25, 6,number_format($commissions,2), 'TB', 0, 'R', 0);  
            }
            $price=0;
            $value_of_item=0;
            $commissions=0;

            $this->pdf->Ln();
            $this->pdf->SetFont('helvetica','B',10);
            $this->pdf->Cell(0, 6,$value->name, '0', 0, 'L', 0);
            $this->pdf->Ln();
            $this->pdf->HaveMorePages(6);
            $this->pdf->SetFont('helvetica','B',8);
            $this->pdf->setX(15);
            $this->pdf->Cell(30, 6,"Code", '1', 0, 'C', 0);
            $this->pdf->Cell(55, 6,"Item Name", '1', 0, 'C', 0);
            $this->pdf->Cell(15, 6,"Model", '1', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"Sale Commission", '1', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"Total Sales Item", '1', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"Sales Return", '1', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"Net Items", '1', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"Selling Price", '1', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"Total Sales Value", '1', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"Net Commission", '1', 0, 'C', 0);
            $this->pdf->Ln();
            $this->pdf->HaveMorePages(6);
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
            $this->pdf->Cell(30, 6,"", 'L', 0, 'C', 0);
            $this->pdf->Cell(55, 6,"", '0', 0, 'C', 0);
            $this->pdf->Cell(15, 6,"", '0', 0, 'C', 0);
            $this->pdf->Cell(13, 6,"Amount", 'L', 0, 'C', 0);
            $this->pdf->Cell(12, 6,"%", 'LR', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"", '0', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"", '0', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"", '0', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"", '0', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"", '0', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"", 'R', 0, 'C', 0);
            $this->pdf->Ln();

            $this->pdf->setX(15);
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
            $this->pdf->SetFont('helvetica','',8);
            $heigh=6*(max(1,$this->pdf->getNumLines($value->name, 65)));
            $this->pdf->HaveMorePages($heigh);

            if($value->rate!='0.00'){
                $commission=$value->net_qty*(($value->rate*$value->price)/100);
            }
            if($value->amount!='0.00'){
                $commission=$value->net_qty*$value->amount;
            }

            $this->pdf->MultiCell(30, $heigh,$value->code,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(55, $heigh,$value->description,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(15, $heigh,$value->model,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(13, $heigh,$value->amount,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(12, $heigh,$value->rate,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(25, $heigh,$value->qty,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(25, $heigh,$value->rt_qty,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(25, $heigh,$value->net_qty,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(25, $heigh,$value->price,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(25, $heigh,$value->value_of_item,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(25, $heigh,number_format($commission,2),'1', 'R', false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);
        }else{
          $this->pdf->setX(15);
          $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
          $this->pdf->SetFont('helvetica','',8);
          $heigh=6*(max(1,$this->pdf->getNumLines($value->name, 65)));
          $this->pdf->HaveMorePages($heigh);

          if($value->rate!='0.00'){
            $commission=$value->net_qty*(($value->rate*$value->price)/100);
        }
        if($value->amount!='0.00'){
            $commission=$value->net_qty*$value->amount;
        }

        $this->pdf->MultiCell(30, $heigh,$value->code,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(55, $heigh,$value->description,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(15, $heigh,$value->model,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(13, $heigh,$value->amount,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(12, $heigh,$value->rate,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(25, $heigh,$value->qty,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(25, $heigh,$value->rt_qty,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(25, $heigh,$value->net_qty,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(25, $heigh,$value->price,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(25, $heigh,$value->value_of_item,'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
        $this->pdf->MultiCell(25, $heigh,number_format($commission,2),'1', 'R', false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);

    }

    $price+=(float)$value->price;
    $value_of_item+=(float)$value->value_of_item;
    $commissions+=(float)$commission;

    $grprice+=(float)$value->price;
    $grvalue_of_item+=(float)$value->value_of_item;
    $grcommissions+=(float)$commission;

    $rep=$value->rep;
    $commission=0;
    $x++;
}



$this->pdf->Ln();
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->setX(15);
$this->pdf->Cell(175, 6,"", '0', 0, 'R', 0);
$this->pdf->Cell(25, 6,"Total", '0', 0, 'R', 0);
$this->pdf->Cell(25, 6,number_format($price,2), 'TB', 0, 'R', 0);   
$this->pdf->Cell(25, 6,number_format($value_of_item,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(25, 6,number_format($commissions,2), 'TB', 0, 'R', 0); 

$this->pdf->Ln();
$this->pdf->SetFont('helvetica','B',8);
$this->pdf->setX(15);
$this->pdf->Cell(175, 6,"", '0', 0, 'R', 0);
$this->pdf->Cell(25, 6,"Grand Total", '0', 0, 'R', 0);
$this->pdf->Cell(25, 6,number_format($grprice,2), 'TB', 0, 'R', 0);   
$this->pdf->Cell(25, 6,number_format($grvalue_of_item,2), 'TB', 0, 'R', 0);
$this->pdf->Cell(25, 6,number_format($grcommissions,2), 'TB', 0, 'R', 0);      

$this->pdf->Output("Item Wise Total Sales_".date('Y-m-d').".pdf", 'I');

?>