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
    $this->pdf->Cell(180, 1,"Credit Note Detailes Reoprt  ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(25);$this->pdf->Cell(60, 1,"",'T',0, 'L', 0);$this->pdf->Ln(); 

    $this->pdf->setY(28);$this->pdf->SetFont('helvetica', '', 8);
    $this->pdf->Cell(180, 1,"Date From - ".$dfrom."  To - ".$dto,0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    //----------------------------------------------------------------------------------------------------

    $des="default";
    $tno="default";
    $amt=(float)0;

        $colwidth_1 = 10; //no
        $colwidth_2 = 20; //date
        $colwidth_3 = 26; //Account Code
        $colwidth_4 = 40; //Acc Descrition
        $colwidth_5 = 26; //Oposit.Account
        $colwidth_6 = 35; //Description
        $colwidth_7 = 20; //Amount
        $rowsheight= 4;

        $this->pdf->SetFont('helvetica', '', 8);$this->pdf->Cell(193, 1,"",'B',0, 'L', 0);$this->pdf->Ln();
        $this->pdf->SetX(15);
        $this->pdf->SetFont('helvetica','B',8);
        
        $this->pdf->Cell($colwidth_1, 6,"No", 'B', 0, 'R', 0);
        $this->pdf->Cell($colwidth_2, 6,"Date", 'B', 0, 'R', 0);
        $this->pdf->Cell($colwidth_3, 6, "Account Code", 'B', 0, 'L', 0);
        $this->pdf->Cell($colwidth_4, 6, "Acc Description", 'B', 0, 'L', 0);
        $this->pdf->Cell($colwidth_5, 6,"Oposit.Account", 'B', 0, 'L', 0);
        $this->pdf->Cell($colwidth_6, 6,"Description", 'B', 0, 'L', 0);
        $this->pdf->Cell($colwidth_7, 6,"Amount", 'B', 0, 'R', 0);
        $this->pdf->Cell(16, 6,"Status", 'B', 0, 'L', 0);
        $this->pdf->Ln(); 

    $x=0;
    foreach($credit_note as $row)
    {
        $this->pdf->SetFont('helvetica','',8);
        $this->pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        
        $aa = $this->pdf->getNumLines($row->customer,40);
        $heigh=5*$aa;
        
        $this->pdf->SetX(15);
        $this->pdf->MultiCell(10, $heigh,$row->nno, 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$row->ddate, 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(26, $heigh,$row->cus_code, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(40, $heigh,$row->customer, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(26, $heigh,$row->acc_code, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(35, $heigh,$row->cr_account, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
        $this->pdf->MultiCell(20, $heigh,$row->amount, 0, 'R', 0, 0, '', '', true, 0, false, true, 0);
        if((float)$row->amount > (float)$row->balance){
            $this->pdf->MultiCell(20, $heigh,"SETTLED", 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
        }else{
             $this->pdf->MultiCell(20, $heigh,"PENDING", 0, 'L', 0, 1, '', '', true, 0, false, true, 0);         
        }
        //$this->pdf->Cell(10, $rowsheight,$row->nno, '0', 0, 'R', 0);
        //$this->pdf->Cell(20, $rowsheight,$row->ddate, '0', 0, 'R', 0);
        //$this->pdf->Cell(26, $rowsheight,$row->cus_code, '0', 0, 'L', 0);
        //$this->pdf->Cell(40, $rowsheight,$row->customer, '0', 0, 'L', 0);
        //$this->pdf->Cell(26, $rowsheight,$row->acc_code, '0', 0, 'L', 0);
        //$this->pdf->Cell(35, $rowsheight,$row->cr_account, '0', 0, 'L', 0);
        //$this->pdf->Cell(30, $rowsheight,$row->amount, '0', 0, 'R', 0);
        //$this->pdf->Ln();  
 
        $amt=(float)$amt+(float)$row->amount;

        $x=$x+1;                      

        if($x % 5==0){
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1.5, 'color' => array(0, 0, 0)));
            $this->pdf->Cell(193, 1,'', 'B', 0, 'L', 0);
            $this->pdf->Ln();
        }
    } 

    $this->pdf->SetFont('helvetica','B',8);
    $this->pdf->SetX(20);
    $this->pdf->Cell(117, 6,"", '', 0, 'L', 0);
    $this->pdf->Cell($colwidth_6, 6,Total, '', 0, 'L', 0);
    $this->pdf->Cell($colwidth_7, 6,number_format($amt,2, '.', ''), 'B', 0, 'R', 0);
    $this->pdf->Ln(); 

    $this->pdf->Output("Credit Note Detailes Report".date('Y-m-d').".pdf", 'I');

?>
        



        /*if($des !="default" && $des==$row->description)
        {
            if($tno !="default" && $tno==$row->trans_no)
            {

            }
            /*else
            {
                $this->pdf->SetFont('helvetica','B',7);
                $this->pdf->SetX(20);
                $this->pdf->Cell(40, 6,"", '', 0, 'L', 0);
                $this->pdf->Cell(15, 6,"", '', 0, 'R', 0);
                $this->pdf->Cell(20, 6,"", '', 0, 'L', 0);
                $this->pdf->Cell(57, 6,Total, '', 0, 'L', 0);
                $this->pdf->Cell(20, 6,number_format($dr,2, '.', ''), '', 0, 'R', 0);
                $this->pdf->Cell(20, 6,number_format($cr,2, '.', ''), '', 0, 'R', 0);
                $this->pdf->Ln(); 

                $this->pdf->SetFont('helvetica','',7);
                $this->pdf->SetX(20);
                $this->pdf->Cell(40, 6,"", '1', 0, 'L', 0);
                $this->pdf->Cell(15, 6,$row->trans_no, '1', 0, 'R', 0);
                $this->pdf->Cell(20, 6,"", '1', 0, 'L', 0);
                $this->pdf->Cell(57, 6,"", '1', 0, 'L', 0);
                $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
                $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
                $this->pdf->Ln();

                $this->pdf->SetX(20);
                $this->pdf->Cell(40, 6,"", '1', 0, 'L', 0);
                $this->pdf->Cell(15, 6,"", '1', 0, 'R', 0);
                $this->pdf->Cell(20, 6,$row->acc_code, '1', 0, 'L', 0);
                $this->pdf->Cell(57, 6,$row->acc_des, '1', 0, 'L', 0);
                $this->pdf->Cell(20, 6,$row->dr_amount, '1', 0, 'R', 0);
                $this->pdf->Cell(20, 6,$row->cr_amount, '1', 0, 'R', 0);
                $this->pdf->Ln(); 

                $cr=0;  
                $dr=0;
            }  
        }
        else
        {
            if($tno !="default" && $tno==$row->trans_no)
            {
                $this->pdf->SetFont('helvetica','B',7);
                $this->pdf->SetX(20);
                $this->pdf->Cell(40, 6,"", '', 0, 'L', 0);
                $this->pdf->Cell(15, 6,"", '', 0, 'R', 0);
                $this->pdf->Cell(20, 6,"", '', 0, 'L', 0);
                $this->pdf->Cell(57, 6,Total, '', 0, 'L', 0);
                $this->pdf->Cell(20, 6,number_format($dr,2, '.', ''), '', 0, 'R', 0);
                $this->pdf->Cell(20, 6,number_format($cr,2, '.', ''), '', 0, 'R', 0);
                $this->pdf->Ln(); 

                $this->pdf->SetFont('helvetica','',7);
                $this->pdf->SetX(20);
                $this->pdf->Cell(40, 6,$row->description, '1', 0, 'L', 0);
                $this->pdf->Cell(15, 6,"", '1', 0, 'R', 0);
                $this->pdf->Cell(20, 6,"", '1', 0, 'L', 0);
                $this->pdf->Cell(57, 6,"", '1', 0, 'L', 0);
                $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
                $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
                $this->pdf->Ln();

                $this->pdf->SetX(20);
                $this->pdf->Cell(40, 6,"", '1', 0, 'L', 0);
                $this->pdf->Cell(15, 6,$row->trans_no, '1', 0, 'R', 0);
                $this->pdf->Cell(20, 6,"", '1', 0, 'L', 0);
                $this->pdf->Cell(57, 6,"", '1', 0, 'L', 0);
                $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
                $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
                $this->pdf->Ln();

                $this->pdf->SetX(20);
                $this->pdf->Cell(40, 6,"", '1', 0, 'L', 0);
                $this->pdf->Cell(15, 6,"", '1', 0, 'R', 0);
                $this->pdf->Cell(20, 6,$row->acc_code, '1', 0, 'L', 0);
                $this->pdf->Cell(57, 6,$row->acc_des, '1', 0, 'L', 0);
                $this->pdf->Cell(20, 6,$row->dr_amount, '1', 0, 'R', 0);
                $this->pdf->Cell(20, 6,$row->cr_amount, '1', 0, 'R', 0);
                $this->pdf->Ln(); 

                $cr=0;
                $dr=0;
            }    
            else
            {
                if($tno =="default")
                {
                    $this->pdf->SetX(20);
                    $this->pdf->Cell(40, 6,$row->description, '1', 0, 'L', 0);
                    $this->pdf->Cell(15, 6,"", '1', 0, 'R', 0);
                    $this->pdf->Cell(20, 6,"", '1', 0, 'L', 0);
                    $this->pdf->Cell(57, 6,"", '1', 0, 'L', 0);
                    $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
                    $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
                    $this->pdf->Ln(); 

                    $this->pdf->SetX(20);
                    $this->pdf->Cell(40, 6,"", '1', 0, 'L', 0);
                    $this->pdf->Cell(15, 6,$row->trans_no, '1', 0, 'R', 0);
                    $this->pdf->Cell(20, 6,"", '1', 0, 'L', 0);
                    $this->pdf->Cell(57, 6,"", '1', 0, 'L', 0);
                    $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
                    $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
                    $this->pdf->Ln(); 

                    $this->pdf->SetX(20);
                    $this->pdf->Cell(40, 6,"", '1', 0, 'L', 0);
                    $this->pdf->Cell(15, 6,"", '1', 0, 'R', 0);
                    $this->pdf->Cell(20, 6,$row->acc_code, '1', 0, 'L', 0);
                    $this->pdf->Cell(57, 6,$row->acc_des, '1', 0, 'L', 0);
                    $this->pdf->Cell(20, 6,$row->dr_amount, '1', 0, 'R', 0);
                    $this->pdf->Cell(20, 6,$row->cr_amount, '1', 0, 'R', 0);
                    $this->pdf->Ln();
                }
                else
                {

                    $this->pdf->SetFont('helvetica','B',7);
                    $this->pdf->SetX(20);
                    $this->pdf->Cell(40, 6,"", '', 0, 'L', 0);
                    $this->pdf->Cell(15, 6,"", '', 0, 'R', 0);
                    $this->pdf->Cell(20, 6,"", '', 0, 'L', 0);
                    $this->pdf->Cell(57, 6,Total, '', 0, 'L', 0);
                    $this->pdf->Cell(20, 6,number_format($dr,2, '.', ''), '', 0, 'R', 0);
                    $this->pdf->Cell(20, 6,number_format($cr,2, '.', ''), '', 0, 'R', 0);
                    $this->pdf->Ln(); 

                    $this->pdf->SetFont('helvetica','',7);
                    $this->pdf->SetX(20);
                    $this->pdf->Cell(40, 6,$row->description, '1', 0, 'L', 0);
                    $this->pdf->Cell(15, 6,"", '1', 0, 'R', 0);
                    $this->pdf->Cell(20, 6,"", '1', 0, 'L', 0);
                    $this->pdf->Cell(57, 6,"", '1', 0, 'L', 0);
                    $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
                    $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
                    $this->pdf->Ln(); 

                    $this->pdf->SetX(20);
                    $this->pdf->Cell(40, 6,"", '1', 0, 'L', 0);
                    $this->pdf->Cell(15, 6,$row->trans_no, '1', 0, 'R', 0);
                    $this->pdf->Cell(20, 6,"", '1', 0, 'L', 0);
                    $this->pdf->Cell(57, 6,"", '1', 0, 'L', 0);
                    $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
                    $this->pdf->Cell(20, 6,"", '1', 0, 'R', 0);
                    $this->pdf->Ln(); 

                    $this->pdf->SetX(20);
                    $this->pdf->Cell(40, 6,"", '1', 0, 'L', 0);
                    $this->pdf->Cell(15, 6,"", '1', 0, 'R', 0);
                    $this->pdf->Cell(20, 6,$row->acc_code, '1', 0, 'L', 0);
                    $this->pdf->Cell(57, 6,$row->acc_des, '1', 0, 'L', 0);
                    $this->pdf->Cell(20, 6,$row->dr_amount, '1', 0, 'R', 0);
                    $this->pdf->Cell(20, 6,$row->cr_amount, '1', 0, 'R', 0);
                    $this->pdf->Ln();

                    $cr=0;
                    $dr=0;
                }
            }
        } 
           
        $des=$row->description;
        $tno=$row->trans_no; 
        $amt=(float)$amt+(float)$row->amount;                      
    } 

    $this->pdf->SetFont('helvetica','B',7);
    $this->pdf->SetX(20);
    $this->pdf->Cell(10, 6,"", '', 0, 'L', 0);
    $this->pdf->Cell(15, 6,"", '', 0, 'L', 0);
    $this->pdf->Cell(20, 6,"", '', 0, 'L', 0);
    $this->pdf->Cell(40, 6,"", '', 0, 'L', 0);
    $this->pdf->Cell(20, 6,"", '', 0, 'L', 0);
    $this->pdf->Cell(40, 6,Total, '', 0, 'L', 0);
    $this->pdf->Cell(20, 6,number_format($amt,2, '.', ''), '', 0, 'R', 0);

    $this->pdf->Ln(); 

    $this->pdf->Output("Credit Note Detailes Report".date('Y-m-d').".pdf", 'I');

?>*/
