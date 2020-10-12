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
    $this->pdf->Cell(0, 5, 'Department wise Details Report  ',0,false, 'L', 0, '', 0, false, 'M', 'M');

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

    
    $this->pdf->Ln();
    $rep='default';
    $x=0;
    foreach ($r_data as $value) {
        $heigh=6*(max(1,$this->pdf->getNumLines($value->code,35),$this->pdf->getNumLines($value->description, 80)));
        $this->pdf->HaveMorePages($heigh);
        $this->pdf->setX(15);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));

        if($rep=='default' || $rep!=$value->rep){

            if($x!=0){
                $this->pdf->setX(15);
                $this->pdf->Cell(185, 6,"", '0', 0, 'R', 0);
                $this->pdf->Cell(25, 6,'Total', '0', 0, 'L', 0);
                $this->pdf->Cell(20, 6,number_format($price,2), 'TB', 0, 'R', 0);   
                $this->pdf->Cell(20, 6,number_format($discount,2), 'TB', 0, 'R', 0);
                $this->pdf->Cell(20, 6,number_format($amount,2), 'TB', 0, 'R', 0);  
            }
            $this->pdf->Ln();
            $price=0;
            $discount=0;
            $amount=0;
            $this->pdf->HaveMorePages($heigh);
            $this->pdf->SetFont('helvetica','B',10);
            $this->pdf->MultiCell(0, $heigh,$value->name,'0', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->Ln();

            $this->pdf->SetFont('helvetica','B',9);
            $this->pdf->setX(15);
            $this->pdf->Cell(20, 6,"Date", '1', 0, 'C', 0);
            $this->pdf->Cell(15, 6,"Type", '1', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"No", '1', 0, 'C', 0);
            $this->pdf->Cell(35, 6,"Code", '1', 0, 'C', 0);
            $this->pdf->Cell(90, 6,"Description", '1', 0, 'C', 0);
            $this->pdf->Cell(25, 6,"Model", '1', 0, 'C', 0);
            $this->pdf->Cell(20, 6,"Gross Price", '1', 0, 'C', 0);
            $this->pdf->Cell(20, 6,"Discount", '1', 0, 'C', 0);
            $this->pdf->Cell(20, 6,"Net Price", '1', 0, 'C', 0);
            $this->pdf->Ln();

            $this->pdf->SetFont('helvetica','',9);
            $this->pdf->HaveMorePages($heigh);
            $this->pdf->MultiCell(20, $heigh,$value->ddate,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(15, $heigh,$value->type,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(25, $heigh,$value->cl.$value->bc.$value->nno,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(35, $heigh,$value->code,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(90, $heigh,$value->description,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(25, $heigh,$value->model,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(20, $heigh,number_format($value->price,2),'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(20, $heigh,number_format($value->discount,2),'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(20, $heigh,number_format($value->amount,2),'1', 'R', false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);
        }else{
            $this->pdf->HaveMorePages($heigh);
            $this->pdf->MultiCell(20, $heigh,$value->ddate,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(15, $heigh,$value->type,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(25, $heigh,$value->cl.$value->bc.$value->nno,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(35, $heigh,$value->code,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(90, $heigh,$value->description,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(25, $heigh,$value->model,'1', 'L', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(20, $heigh,number_format($value->price,2),'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(20, $heigh,number_format($value->discount,2),'1', 'R', false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);
            $this->pdf->MultiCell(20, $heigh,number_format($value->amount,2),'1', 'R', false, 1, '', '', true, 0, false, true, $heigh,'M' ,false);
        }
        $price+=(float)$value->price;
        $discount+=(float)$value->discount;
        $amount+=(float)$value->amount;
        $rep=$value->rep;

        $gprice+=(float)$value->price;
        $gdiscount+=(float)$value->discount;
        $gamount+=(float)$value->amount;
        $x++;

    }

    $this->pdf->SetFont('helvetica','B',9);
    $this->pdf->setX(15);
    $this->pdf->Cell(185, 6,"", '0', 0, 'R', 0);
    $this->pdf->Cell(25, 6,'Total', '0', 0, 'L', 0);
    $this->pdf->Cell(20, 6,number_format($price,2), 'TB', 0, 'R', 0);   
    $this->pdf->Cell(20, 6,number_format($discount,2), 'TB', 0, 'R', 0);
    $this->pdf->Cell(20, 6,number_format($amount,2), 'TB', 0, 'R', 0);      
    $this->pdf->Ln();

    $this->pdf->SetFont('helvetica','B',9);
    $this->pdf->setX(15);
    $this->pdf->Cell(185, 6,"", '0', 0, 'R', 0);
    $this->pdf->Cell(25, 6,'Grand Total', '0', 0, 'L', 0);
    $this->pdf->Cell(20, 6,number_format($gprice,2), 'TB', 0, 'R', 0);   
    $this->pdf->Cell(20, 6,number_format($gdiscount,2), 'TB', 0, 'R', 0);
    $this->pdf->Cell(20, 6,number_format($gamount,2), 'TB', 0, 'R', 0);      

    $this->pdf->Output("Department wise Details_".date('Y-m-d').".pdf", 'I');

    ?>