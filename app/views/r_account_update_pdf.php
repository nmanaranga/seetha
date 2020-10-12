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

    $this->pdf->setY(25);
    $this->pdf->SetFont('helvetica', 'BU',12);
    $this->pdf->Cell(180, 1,"Account's Update Reoprt  ",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();
    $this->pdf->Ln();

    $this->pdf->setY(30);
    $this->pdf->setX(15);
    $this->pdf->SetFont('helvetica', '', 9);
    $this->pdf->Cell(180, 1,"From - ".$dfrom."     To - ".$dto,0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->Ln();

    $this->pdf->setY(35);
    $this->pdf->setX(25);
    $this->pdf->SetFont('helvetica', 'B', 8);

    $this->pdf->Ln();
    $this->pdf->setY(40);
    $this->pdf->SetY(43);
    $this->pdf->SetX(20);
    $this->pdf->Ln();

    $des="default";
    $tno="default";
    $cr=(float)0;
    $dr=(float)0;

    foreach($acc_update as $row)
    {
        $this->pdf->SetX(20);
        $this->pdf->SetFont('helvetica','',7);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
    
        $this->pdf->SetX(20);
        $this->pdf->Cell(40, 6,$row->description, '1', 0, 'L', 0);
        $this->pdf->Cell(15, 6,$row->trans_no, '1', 0, 'R', 0);
        $this->pdf->Cell(20, 6,$row->acc_code, '1', 0, 'L', 0);
        $this->pdf->Cell(57, 6,$row->acc_des, '1', 0, 'L', 0);
        $this->pdf->Cell(20, 6,$row->dr_amount, '1', 0, 'R', 0);
        $this->pdf->Cell(20, 6,$row->cr_amount, '1', 0, 'R', 0);
        $this->pdf->Ln();




        /*if($des !="default" && $des==$row->description)
        {
            if($tno !="default" && $tno==$row->trans_no)
            {
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
        }*/
           
        $des=$row->description;
        $tno=$row->trans_no; 
        $cr=(float)$cr+(float)$row->cr_amount;
        $dr=(float)$dr+(float)$row->dr_amount;                       
    } 

    $this->pdf->SetFont('helvetica','B',7);
    $this->pdf->SetX(20);
    $this->pdf->Cell(40, 6,"", '', 0, 'L', 0);
    $this->pdf->Cell(15, 6,"", '', 0, 'L', 0);
    $this->pdf->Cell(20, 6,"", '', 0, 'L', 0);
    $this->pdf->Cell(57, 6,Total, '', 0, 'L', 0);
    $this->pdf->Cell(20, 6,number_format($dr,2, '.', ''), '', 0, 'R', 0);
    $this->pdf->Cell(20, 6,number_format($cr,2, '.', ''), '', 0, 'R', 0);
    $this->pdf->Ln(); 

    $this->pdf->Output("Account's Update Report".date('Y-m-d').".pdf", 'I');

?>
