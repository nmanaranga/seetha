<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
        $this->pdf->setPrintHeader($header,$type,$duration);
        $this->pdf->setPrintFooter(true);
        //$this->pdf->setPrintHeader(true);
        //print_r($purchase);
        $this->pdf->SetFont('helvetica', 'B', 16);
        $this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3

        foreach($branch as $ress){
                $this->pdf->headerSet3($ress->name,$ress->address,$ress->tp,$ress->fax,$ress->email);
            }
foreach ($purchase as $value){
      $inv_no=$value->nno;
      $name=$value->name;
    }

    foreach ($category as $cat){
      $code=$cat->code;
      $des=$cat->description;
    }
        
        $this->pdf->setY(22);

            $this->pdf->SetFont('helvetica', 'BU',10);
            $this->pdf->Cell(0, 5, 'RELIANCE SALES SUMMARY',0,false, 'C', 0, '', 0, false, 'M', 'M');
            $this->pdf->Ln();$this->pdf->Ln();

            $this->pdf->SetFont('helvetica', '',9);
            $this->pdf->Cell(0, 5, 'Date Between '. $dfrom.' and '.$dto ,0,false, 'C', 0, '', 0, false, 'M', 'M');
            $this->pdf->Ln();
            $this->pdf->Ln();

            if($category_field!="0"){
                $this->pdf->SetX(20);
                $this->pdf->SetFont('helvetica', 'B',9);
                $this->pdf->Cell(45, 6,"Category : ".$code." - ".$des, '0', 0, 'R', 0);
                $this->pdf->Ln();
                $this->pdf->Ln();
            }
            

            $this->pdf->SetY(50);
            $this->pdf->SetX(10);
            $this->pdf->SetFont('helvetica','B',9);
            $this->pdf->Cell(10, 6,"No  ", '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,"  Date", '1', 0, 'L', 0);
            $this->pdf->Cell(25, 6,"  Do No", '1', 0, 'L', 0);
            $this->pdf->Cell(70, 6,"  Customer", '1', 0, 'L', 0);
            $this->pdf->Cell(25, 6,"Gross Amount  ", '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,"Discount  ", '1', 0, 'R', 0);
            $this->pdf->Cell(25, 6,"Net Amount  ", '1', 0, 'R', 0);
       
            $this->pdf->Ln();

            $tot_gross=(float)0;
            $tot_net=(float)0;
            $tot_discount=(float)0;


            foreach ($purchase as $value) {
            $this->pdf->SetX(10);
            $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0.1, 'color' => array(0, 0, 0)));
            $this->pdf->SetFont('helvetica','',9);
            $this->pdf->Cell(10, 6,$value->nno, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$value->ddate,'1', 0, 'L', 0);
            $this->pdf->Cell(25, 6,$value->do_no,'1', 0, 'L', 0);
            $this->pdf->Cell(70, 6,$value->name, '1', 0, 'L', 0);
            $this->pdf->Cell(25, 6,$value->gross, '1', 0, 'R', 0);
            $this->pdf->Cell(20, 6,$value->discount, '1', 0, 'R', 0);
            $this->pdf->Cell(25, 6,$value->net_amount, '1', 0, 'R', 0);
          
          
            $tot_gross+=(float)$value->gross;
            $tot_discount+=(float)$value->discount;
            $tot_net+=(float)$value->net_amount;

            
            $this->pdf->Ln();
                
            }
            $this->pdf->Ln();

            foreach ($sum as $sum) {
                $Goss=$sum->gsum;
                $net=$sum->nsum;
                $addi=$sum->addi;
            }
            $this->pdf->SetX(10);
            $this->pdf->SetFont('helvetica','B',9);
            $this->pdf->Cell(10, 6,"", '0', 0, 'R', 0);
            $this->pdf->Cell(20, 6,"", '0', 0, 'L', 0);
            $this->pdf->Cell(25, 6,"", '0', 0, 'L', 0);
            $this->pdf->Cell(70, 6,"Total", '0', 0, 'R', 0);
            $this->pdf->Cell(2, 6,"", '0', 0, 'R', 0);
            $this->pdf->Cell(23, 6,number_format($tot_gross,2), 'TB', 0, 'R', 0);
            $this->pdf->Cell(2, 6,"", '0', 0, 'R', 0);
            $this->pdf->Cell(20, 6,number_format($tot_discount,2), 'TB', 0, 'R', 0);
            $this->pdf->Cell(2, 6,"", '0', 0, 'R', 0);
            $this->pdf->Cell(23, 6,number_format($tot_net,2), 'TB', 0, 'R', 0);

            $this->pdf->SetX(10);
            $this->pdf->Ln();
            $this->pdf->SetFont('helvetica', 'B', 7);
            $this->pdf->SetX(20);
        


    // $this->pdf->footerSet($employee,$amount,$additional,$discount,$user);
    $this->pdf->Output("Reliance Sale Summary".date('Y-m-d').".pdf", 'I');

?>