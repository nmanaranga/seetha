<?php
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    $this->pdf->setPrintHeader($header,$type,$duration);
    $this->pdf->setPrintHeader(true,$type);
    $this->pdf->setPrintFooter(true);
    
    $this->pdf->SetFont('helvetica', 'B', 16);
    $this->pdf->AddPage("P","A4"); 

    $branch_name="";
    foreach($branch as $ress){
        $branch_name=$ress->name;
        $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
    }

    $this->pdf->setY(25);
    $this->pdf->SetFont('helvetica', 'BU',10);
    $this->pdf->Cell(180, 1,"CHART OF ACCOUNT",0,false, 'L', 0, '', 0, false, 'M', 'M');
    $this->pdf->setX(15);
    $this->pdf->SetFont('helvetica', '', 8);

    $this->pdf->Ln();
    $rtype="default";
    $type="default";
    $count=(Int)0;

    foreach($acc_det as $row){
        $this->pdf->SetTextColor(11,0,0);
        $sql_l="SELECT * FROM m_account_type WHERE is_ledger_acc='1'";
        $query_l = $this->db->query($sql_l);
        foreach($query_l->result() as $r){
            if($row->code==$r->code){
                $this->pdf->SetTextColor(14,19,169);
            }
        } 

        $this->pdf->HaveMorePages(6);
        $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
        $this->pdf->SetFont('helvetica', 'B', 8);
        foreach($query_l->result() as $r){
            if($row->code==$r->code){
                $this->pdf->SetTextColor(14,19,169);
            }
        }
        $this->pdf->Cell(5, 6,$row->code, 'TR', 0, 'L', 0);
        $this->pdf->Cell(265, 6,$row->heading, 'TR', 0, 'L', 0);
        $this->pdf->Ln();
        $this->pdf->SetTextColor(11,0,0);


        $sql2="SELECT * FROM `m_account_type` t WHERE t.`control_category`='".$row->code."'"; 
        $query=$this->db->query($sql2)->result();
        foreach($query as $row2){
            $this->pdf->HaveMorePages(6);
            $this->pdf->SetFont('helvetica', '', 8);
            foreach($query_l->result() as $r){
                if($row2->code==$r->code){
                    $this->pdf->SetTextColor(14,19,169);
                }
            }
            $this->pdf->Cell(15, 6,"", 'TR', 0, 'L', 0);
            $this->pdf->Cell(25, 6,$row2->code, 'TR', 0, 'L', 0);
            $this->pdf->Cell(230, 6,$row2->heading, 'TR', 0, 'L', 0);
            $this->pdf->Ln(); 
            $this->pdf->SetTextColor(11,0,0);

            $sql3="SELECT * FROM `m_account_type` t WHERE t.`control_category`='".$row2->code."'"; 
            $query=$this->db->query($sql3)->result();
            foreach($query as $row3){
                $this->pdf->HaveMorePages(6);
                $this->pdf->SetFont('helvetica', '', 8);
                foreach($query_l->result() as $r){
                    if($row3->code==$r->code){
                        $this->pdf->SetTextColor(14,19,169);
                    }
                }
                $this->pdf->Cell(40, 6,"", '0', 0, 'L', 0);
                $this->pdf->Cell(25, 6,$row3->code, 'TR', 0, 'L', 0);
                $this->pdf->Cell(205, 6,$row3->heading, 'TR', 0, 'L', 0);
                $this->pdf->Ln(); 
                $this->pdf->SetTextColor(11,0,0);

                $sql4="SELECT * FROM `m_account_type` t WHERE t.`control_category`='".$row3->code."'"; 
                $query=$this->db->query($sql4)->result();
                foreach($query as $row4){
                    $this->pdf->HaveMorePages(6);
                    $this->pdf->SetFont('helvetica', '', 8);
                    foreach($query_l->result() as $r){
                        if($row4->code==$r->code){
                            $this->pdf->SetTextColor(14,19,169);
                        }
                    }
                    $this->pdf->Cell(65, 6,"", 'TR', 0, 'L', 0);
                    $this->pdf->Cell(25, 6,$row4->code, 'TR', 0, 'L', 0);
                    $this->pdf->Cell(180, 6,$row4->heading, 'TR', 0, 'L', 0);
                    $this->pdf->Ln(); 
                    $this->pdf->SetTextColor(11,0,0);


                    $sql5="SELECT * FROM `m_account_type` t WHERE t.`control_category`='".$row4->code."'"; 
                    $query=$this->db->query($sql5)->result();
                    foreach($query as $row5){
                        $this->pdf->HaveMorePages(6);
                        $this->pdf->SetFont('helvetica', '', 8);
                        foreach($query_l->result() as $r){
                            if($row5->code==$r->code){
                                $this->pdf->SetTextColor(14,19,169);
                            }
                        }
                        $this->pdf->Cell(90, 6,"", 'TR', 0, 'L', 0);
                        $this->pdf->Cell(25, 6,$row5->code, 'TR', 0, 'L', 0);
                        $this->pdf->Cell(155, 6,$row5->heading, 'TR', 0, 'L', 0);
                        $this->pdf->Ln(); 
                        $this->pdf->SetTextColor(11,0,0);

                        $sql6="SELECT * FROM `m_account_type` t WHERE t.`control_category`='".$row5->code."'"; 
                        $query=$this->db->query($sql6)->result();
                        foreach($query as $row6){
                            $this->pdf->HaveMorePages(6);
                            $this->pdf->SetFont('helvetica', '', 8);
                            foreach($query_l->result() as $r){
                                if($row6->code==$r->code){
                                    $this->pdf->SetTextColor(14,19,169);
                                }
                            }
                            $this->pdf->Cell(115, 6,"", 'TR', 0, 'L', 0);
                            $this->pdf->Cell(25, 6,$row6->code, 'TR', 0, 'L', 0);
                            $this->pdf->Cell(130, 6,$row6->heading, 'TR', 0, 'L', 0);
                            $this->pdf->Ln(); 
                            $this->pdf->SetTextColor(11,0,0);
                        }
                    }
                }
            }
        }
    }
    $this->pdf->Output("Chart Of Account Report".date('Y-m-d').".pdf", 'I');
?>
