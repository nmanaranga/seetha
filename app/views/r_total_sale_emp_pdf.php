<?php

    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    $this->pdf->setPrintHeader($header,$type,$duration);
    $this->pdf->setPrintHeader(true,$type);
    $this->pdf->setPrintFooter(true);
    
    $this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage($orientation,$page); 

    $branch_name="";
    foreach($branch as $ress){
        $branch_name=$ress->name;
        $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    }

    foreach($clus as $cl){
        $claster_name=$cl->description;
        $cl_code=$cl->code;
    }
    foreach($bran as $b){
        $b_name=$b->name;
        $b_code=$b->bc;
    }

    $this->pdf->setY(22);
    $this->pdf->SetFont('helvetica', 'BI',12);
    $this->pdf->Cell(180, 1,"Employee Total Sale Report  ",0,false, 'L', 0, '', 0, false, 'M', 'M');

    $this->pdf->Ln();
    $this->pdf->setY(24);$this->pdf->Cell(58, 1,"",'T',0, 'L', 0);
    $this->pdf->Ln();

    $this->pdf->SetFont('helvetica', '', 9);
    $this->pdf->Cell(180, 1,"Date From - ".$from." To- ".$to,0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();
    $this->pdf->setX(15);
    $this->pdf->SetFont('helvetica', '', 9);

    if($cluster!="0"){
        $this->pdf->Cell(25, 1, "Cluster", '0', 0, 'L', 0);
        $this->pdf->Cell(20, 1, ": ".$cl_code." - ".$claster_name,'0', 0, 'L', 0);
    }
    $this->pdf->Ln();
    if($branchs!="0"){
        $this->pdf->Cell(25, 1, "Branch", '0', 0, 'L', 0);
        $this->pdf->Cell(20, 1,": ".$b_code." - ".$b_name, '0', 0, 'L', 0);
    }
    $this->pdf->Ln();

    if($emp!=""){
        $this->pdf->Cell(25, 1,"Employee", '0', 0, 'L', 0);
        $this->pdf->Cell(25, 1,": ".$emp." (".$emp_des.")", '0', 0, 'L', 0);
   
    }
    $this->pdf->setY(40);

    /*$this->pdf->Cell(20, 5, "Date", 'TL', 0, 'L', 0);
    $this->pdf->Cell(65, 5, "Cash", '1', 0, 'L', 0);
    $this->pdf->Cell(66, 5, "Credit", '1', 0, 'L', 0);
    $this->pdf->Cell(20, 5, "Sales Return", 'TLR', 0, 'L', 0);
    $this->pdf->Cell(23, 5, "Total", 'TLR', 0, 'L', 0);
    $this->pdf->Ln();
    $this->pdf->Cell(20, 5, "", 'LRB', 0, 'L', 0);
    $this->pdf->Cell(23, 5, "Gross", '1', 0, 'L', 0);
    $this->pdf->Cell(19, 5, "Discount", '1', 0, 'L', 0);
    $this->pdf->Cell(23, 5, "Net", '1', 0, 'L', 0);
    $this->pdf->Cell(24, 5, "Gross", '1', 0, 'L', 0);
    $this->pdf->Cell(19, 5, "Discount", '1', 0, 'L', 0);
    $this->pdf->Cell(23, 5, "Net", '1', 0, 'L', 0);
    $this->pdf->Cell(20, 5, "", 'LRB', 0, 'L', 0);
    $this->pdf->Cell(23, 5, "", 'LRB', 0, 'L', 0);*/
    $this->pdf->Ln();
    $s=0;
    $bc=0;
    $a_cash_g_t=$a_cash_d_t=$a_cash_add=$a_cash_n_t=$a_cr_g_t=$a_cr_d_t=$a_credit_add=$a_cr_n_t=$a_rt_n_t=$a_total_t=$a_tot_add=0;
    $cash_g_t=$cash_d_t=$cash_n_t=$cr_g_t=$cr_d_t=$cr_n_t=$rt_n_t=$total_t=$tot_add=0;
    foreach($r_data as $row){          
        $this->pdf->SetFont('helvetica','',9);
        
        if($s==0){
            $this->pdf->SetX(10);
            $this->pdf->SetFont('helvetica','B',9);
            $this->pdf->MultiCell(190, $heigh, $row->bc,  0, 'L', 0, 1, '', '', true, 0, false, true, 0);
            $this->pdf->Ln();
            $this->pdf->setX(10);
            $this->pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
            $this->pdf->SetFont('helvetica','B',8);
            $this->pdf->Cell(20, 5, " Employee", 'TL', 0, 'L', 0);
            $this->pdf->Cell(88, 5, " Cash", '1', 0, 'C', 0);
            $this->pdf->Cell(90, 5, " Credit", '1', 0, 'C', 0);
            $this->pdf->Cell(40, 5, " Sales Return", 'TLR', 0, 'L', 0);
            $this->pdf->Cell(20, 5, " Additional  ", 'TLR', 0, 'R', 0);
            $this->pdf->Cell(25, 5, " Total", 'TLR', 0, 'R', 0);
            $this->pdf->Ln();
            $this->pdf->setX(10);
            $this->pdf->Cell(20, 5, "", 'LRB', 0, 'L', 0);
            $this->pdf->Cell(25, 5, "Gross  ", '1', 0, 'R', 0);
            $this->pdf->Cell(20, 5, "Discount  ", '1', 0, 'R', 0);
            $this->pdf->Cell(20, 5, "Additional  ", '1', 0, 'R', 0);
            $this->pdf->Cell(23, 5, "Net  ", '1', 0, 'R', 0);
            $this->pdf->Cell(25, 5, "Gross  ", '1', 0, 'R', 0);
            $this->pdf->Cell(20, 5, "Discount  ", '1', 0, 'R', 0);
            $this->pdf->Cell(20, 5, "Additional  ", '1', 0, 'R', 0);
            $this->pdf->Cell(25, 5, "Net  ", '1', 0, 'R', 0);
            $this->pdf->Cell(20, 5, "Gross", '1', 0, 'L', 0);
            $this->pdf->Cell(20, 5, "Dis Pre Mo", '1', 0, 'L', 0);
            $this->pdf->Cell(20, 5, "", 'LRB', 0, 'L', 0);
            $this->pdf->Cell(25, 5, "", 'LRB', 0, 'L', 0);
            $this->pdf->Ln();
            $this->pdf->setX(10);
            $this->pdf->SetFont('helvetica','',9);
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
            $this->pdf->MultiCell(20, $heigh, $row->rep,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh, $row->cash_gross,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, $heigh, $row->cash_dis,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, $heigh, $row->cash_add,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(23, $heigh, $row->cash_net,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh, $row->credit_gross,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, $heigh, $row->credit_dis,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, $heigh, $row->credit_add,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh, $row->credit_net,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, $heigh, $row->return_net,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, $heigh, $row->return_dis,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(20, $heigh, $row->add_tot,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
            $this->pdf->MultiCell(25, $heigh, $row->total,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);
            $s++;
            $bc=$row->bc;
        }else{
            if($bc==$row->bc){
                $this->pdf->setX(10);
                $this->pdf->MultiCell(20, $heigh, $row->rep,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, $row->cash_gross,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->cash_dis,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->cash_add,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(23, $heigh, $row->cash_net,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, $row->credit_gross,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->credit_dis,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->credit_add,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, $row->credit_net,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->return_net,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->return_dis,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->add_tot,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, $row->total,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);

               $bc=$row->bc;
            }else{                
                $this->pdf->Ln();
                $this->pdf->Ln();
                $this->pdf->SetX(10);
                $this->pdf->SetFont('helvetica','B',9);
                $this->pdf->MultiCell(20, $heigh, "Total",  0, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, number_format($cash_g_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, number_format($cash_d_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, number_format($cash_add,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(23, $heigh, number_format($cash_n_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, number_format($cr_g_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, number_format($cr_d_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, number_format($credit_add,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, number_format($cr_n_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, number_format($rt_n_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, number_format($rt_d_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, number_format($tot_add,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, number_format($total_t,2), "TB", 'R', 0, 1, '', '', true, 0, false, true, 0);
                $this->pdf->Ln();
                $this->pdf->Ln();
                $this->pdf->MultiCell(190, $heigh, $row->bc,  0, 'L', 0, 1, '', '', true, 0, false, true, 0);
                $this->pdf->Ln();
                $this->pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
                $this->pdf->setX(10);
                $this->pdf->SetFont('helvetica','B',8);
                $this->pdf->Cell(20, 5, " Employee", 'TL', 0, 'L', 0);
                $this->pdf->Cell(88, 5, " Cash", '1', 0, 'C', 0);
                $this->pdf->Cell(90, 5, " Credit", '1', 0, 'C', 0);
                $this->pdf->Cell(40, 5, " Sales Return", 'TLR', 0, 'L', 0);
                $this->pdf->Cell(20, 5, " Additional  ", 'TLR', 0, 'R', 0);
                $this->pdf->Cell(25, 5, " Total", 'TLR', 0, 'R', 0);
                $this->pdf->Ln();
                $this->pdf->setX(10);
                $this->pdf->Cell(20, 5, "", 'LRB', 0, 'L', 0);
                $this->pdf->Cell(25, 5, "Gross  ", '1', 0, 'R', 0);
                $this->pdf->Cell(20, 5, "Discount  ", '1', 0, 'R', 0);
                $this->pdf->Cell(20, 5, "Additional  ", '1', 0, 'R', 0);
                $this->pdf->Cell(23, 5, "Net  ", '1', 0, 'R', 0);
                $this->pdf->Cell(25, 5, "Gross  ", '1', 0, 'R', 0);
                $this->pdf->Cell(20, 5, "Discount  ", '1', 0, 'R', 0);
                $this->pdf->Cell(20, 5, "Additional  ", '1', 0, 'R', 0);
                $this->pdf->Cell(25, 5, "Net  ", '1', 0, 'R', 0);
                $this->pdf->Cell(20, 5, "", 'LRB', 0, 'L', 0);
                $this->pdf->Cell(20, 5, "", 'LRB', 0, 'L', 0);
                $this->pdf->Cell(20, 5, "", 'LRB', 0, 'L', 0);
                $this->pdf->Cell(25, 5, "", 'LRB', 0, 'L', 0);
                $this->pdf->Ln();
                $this->pdf->setX(10);
                $cash_add=$credit_add=$cash_g_t=$cash_d_t=$cash_n_t=$cr_g_t=$cr_d_t=$cr_n_t=$rt_n_t=$total_t=$tot_add=0;
                $this->pdf->SetFont('helvetica','',9);
                $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
                $this->pdf->MultiCell(20, $heigh, $row->rep,  1, 'L', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, $row->cash_gross,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->cash_dis,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->cash_add,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(23, $heigh, $row->cash_net,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, $row->credit_gross,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->credit_dis,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->credit_add,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, $row->credit_net,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->return_net,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->return_dis,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(20, $heigh, $row->add_tot,  1, 'R', 0, 0, '', '', true, 0, false, true, 0);
                $this->pdf->MultiCell(25, $heigh, $row->total,  1, 'R', 0, 1, '', '', true, 0, false, true, 0);

                $bc=$row->bc;
            }

            $s++;
        }
        $cash_g_t +=(float)$row->cash_gross;
        $cash_d_t +=(float)$row->cash_dis;
        $cash_n_t +=(float)$row->cash_net;
        $cr_g_t   +=(float)$row->credit_gross;
        $cr_d_t   +=(float)$row->credit_dis;
        $cr_n_t   +=(float)$row->credit_net;
        $rt_n_t   +=(float)$row->return_net;
        $rt_d_t   +=(float)$row->return_dis;
        $total_t  +=(float)$row->total;
        $cash_add +=(float)$row->cash_add;
        $credit_add+=(float)$row->credit_add;
        $tot_add  +=(float)$row->add_tot;

        $a_cash_g_t  +=(float)$row->cash_gross;
        $a_cash_d_t  +=(float)$row->cash_dis;
        $a_cash_add  +=(float)$row->cash_add;
        $a_cash_n_t  +=(float)$row->cash_net;
        $a_cr_g_t    +=(float)$row->credit_gross;
        $a_cr_d_t    +=(float)$row->credit_dis;
        $a_credit_add+=(float)$row->credit_add;
        $a_cr_n_t    +=(float)$row->credit_net;
        $a_rt_n_t    +=(float)$row->return_net;
        $a_rt_d_t    +=(float)$row->return_dis;
        $a_total_t   +=(float)$row->total;
        $a_tot_add   +=(float)$row->add_tot;

    }
    $this->pdf->Ln();
    $this->pdf->setX(10);
    $this->pdf->SetFont('helvetica','B',9);
    $this->pdf->MultiCell(20, $heigh, "Total",  0, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(25, $heigh, number_format($cash_g_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, number_format($cash_d_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, number_format($cash_add,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(23, $heigh, number_format($cash_n_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(25, $heigh, number_format($cr_g_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, number_format($cr_d_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, number_format($credit_add,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(25, $heigh, number_format($cr_n_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, number_format($rt_n_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, number_format($rt_d_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, number_format($tot_add,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(25, $heigh, number_format($total_t,2), "TB", 'R', 0, 1, '', '', true, 0, false, true, 0);
        


    $this->pdf->Ln();
    $this->pdf->setX(10);
    $this->pdf->SetFont('helvetica','B',9);
    $this->pdf->MultiCell(20, $heigh, "Grantt Total",  0, 'L', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(25, $heigh, number_format($a_cash_g_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, number_format($a_cash_d_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, number_format($a_cash_add,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(23, $heigh, number_format($a_cash_n_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(25, $heigh, number_format($a_cr_g_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, number_format($a_cr_d_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, number_format($a_credit_add,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(25, $heigh, number_format($a_cr_n_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, number_format($a_rt_n_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, number_format($a_rt_d_t,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(20, $heigh, number_format($a_tot_add,2), "TB", 'R', 0, 0, '', '', true, 0, false, true, 0);
    $this->pdf->MultiCell(25, $heigh, number_format($a_total_t,2), "TB", 'R', 0, 1, '', '', true, 0, false, true, 0);
        

    $this->pdf->SetFont('helvetica','',9);
    $this->pdf->Ln();
    foreach($emp_data as $e){
        $this->pdf->MultiCell(300, $heigh, $e->emp_name,  0, 'L', 0, 1, '', '', true, 0, false, true, 0);

    }
    $this->pdf->Output("Total Sale Employee Report".date('Y-m-d').".pdf", 'I');

?>
