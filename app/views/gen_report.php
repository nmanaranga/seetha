<?php
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

	$this->pdf->setPrintHeader($header,$type); //header-->true or false and '$type'
        $this->pdf->setPrintFooter(true);
        $this->pdf->SetFont('helvetica', 'B', 20);
	$this->pdf->AddPage($orientation,$page);   // L or P amd page type A4 or A3
       // $this->pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 044', PDF_HEADER_STRING);
       
        
        
        
	//$this->pdf->Ln(2);
	$this->pdf->setX(5);

	if($r_type=="r_stock")
        {
	
          
            
	foreach($info as $ress){
		$this->pdf->headerSet('Stock Detail Report',$ress->name,$ress->address01,$from,$filter);
	} 
	  
        $this->pdf->Ln(6);
	$cat = '';
	$cat2 = '';
	$tot=0;
        
		
	foreach($r_data as $result) {
		
		$this->pdf->setFont('helvetica','',9);
		$this->pdf->setX(5);
		
		if ($cat != $result->main_cat||$cat2 != $result->sub_cat) {
			$total=0;
			if ($cat != ''|| $cat2 !='') {
			 if($val!='sum')
                        {
			$this->pdf->SetX(5);
                        $y=$this->pdf->GetY();$this->pdf->line(160,$y,208,$y);
			$this->pdf->setFont('helvetica','B',9);
			$this->pdf->Cell(180, 1,"Subtotal",0, 0, 'R', 0);
			$this->pdf->Cell(22, 1,d($subtot),0, 0,'R', 0);
			
			$this->pdf->Ln();
			$subtot=0;	
                        }
			}
			
			$this->pdf->SetX(5);
			$this->pdf->setFont('helvetica','B',9);
                         if($val!='sum')
                        {
			$this->pdf->Cell(202, 1,$result->main_cat.'-'.$result->sub_cat,1, 0, 'L', 0);
                        }
                        else
                        {
                        $this->pdf->Cell(180, 1,$result->main_cat.'-'.$result->sub_cat,1, 0, 'L', 0);    
                        }    
			$this->pdf->Ln();
		}
		
               
                
		$cat=$result->main_cat;
		$cat2=$result->sub_cat;
		
		$subtot+=$result->value;
		
		$this->pdf->setFont('helvetica','',9);
		$this->pdf->SetX(5);
		$this->pdf->Cell(30, 1,$result->code,0, 0, 'L', 0);
		$this->pdf->Cell(120, 1,$result->item_name,0, 0, 'L', 0);    
		$this->pdf->Cell(15, 1,$result->price,0, 0, 'R', 0);
		$this->pdf->Cell(15, 1,$result->qun,0, 0, 'R', 0);
                
               
                
                if($val!='sum')
                { 
		$this->pdf->Cell(22, 1,$result->value,0, 0, 'R', 0);
                }
		$this->pdf->Ln();
	
		$tot+=$result->value; 

	}
        
		 if($val!='sum')
                {
		$this->pdf->SetX(5);
                $y=$this->pdf->GetY();$this->pdf->line(160,$y,208,$y);
		$this->pdf->setFont('helvetica','B',9);
		$this->pdf->Cell(175, 1,"Subtotal",0, 0, 'R', 0);
		$this->pdf->Cell(27, 1,d($subtot),0, 0,'R', 0);
			
		$this->pdf->Ln();
		
	
		$this->pdf->Ln(2);
		$this->pdf->SetX(5);
		$this->pdf->setFont('helvetica','B',9);
		$this->pdf->Cell(175, 1,"Total",0, 0, 'R', 0);
		$this->pdf->Cell(27, 1,d($tot),0, 0,'R', 0);
                $y=$this->pdf->GetY()+4.7;$this->pdf->line(6,$y,208,$y);
                $y=$this->pdf->GetY()+4.5;$this->pdf->line(6,$y,208,$y);
                $y=$this->pdf->GetY()+0.4;$this->pdf->line(6,$y,208,$y);
                }
        
	}
	
	if($r_type=="r_bin_card")
        {
	//echo $balance;exit;
		foreach($info as $ress){
			$this->pdf->headerSet2('gdfg',$ress->name,$ress->address01,$ress->phone01,$item,$itemid);
		}
		
		$bal = 0;
		
		foreach($r_res as $result) {
		
		$bal += $result->quantity;	
		$this->pdf->setFont('helvetica','',9);
		
		$this->pdf->Ln();
		$this->pdf->setX(5);
		$this->pdf->Cell(30, 1,$result->date,0, 0, 'L', 0);
		$this->pdf->Cell(60, 1,$result->description,0, 0, 'L', 0);
		$this->pdf->Cell(30, 1,$result->trance_id,0, 0, 'R', 0);
		$this->pdf->Cell(30, 1,$result->trance_type,0, 0, 'R', 0);
		$this->pdf->Cell(15, 1,d($result->quantity),0, 0, 'R', 0);
		$this->pdf->Cell(27, 1,d($bal),0, 0, 'R', 0);
		$this->pdf->Ln();
		
		
		}
		
	
	}
	
	if($r_type=="r_stock_sum")
        {
            
            if($sys=='det')
            {
                $tot=0;
                $i=0;
                $y = $this->pdf->GetY();
		foreach($info as $ress){
		$this->pdf->headerSet('r_stock_sum',$ress->name,$ress->address01,$from,$filter);
		}
  
		$this->pdf->Ln(3);
		foreach($r_data as $result) {
                    
		$this->pdf->setFont('helvetica','',9);
		$this->pdf->SetX(5);
		$this->pdf->Cell(40, 1,$result->code,0, 0, 'L', 0);
		$this->pdf->Cell(110, 1,$result->item_name,0, 0, 'L', 0);    
		$this->pdf->Cell(15, 1,$result->price,0, 0, 'R', 0);
		$this->pdf->Cell(15, 1,$result->qun,0, 0, 'R', 0);
		$this->pdf->Cell(22, 1,$result->value,0, 0, 'R', 0);
                $tot=$tot+$result->value;
                
                $i=$i+1;
                
                if($i%5==0)
                {
                    $this->pdf->Ln();
                    $y=$this->pdf->GetY();$this->pdf->line(6,$y,208,$y);
                }
		$this->pdf->Ln();
		}

                $this->pdf->Ln();
                $this->pdf->setFont('helvetica','B',9);
		$this->pdf->Cell(160, 1,"Total ",0, 0, 'R', 0);
		$this->pdf->Cell(10, 1,"",0, 0, 'R', 0);
                $this->pdf->Cell(22, 1,d($tot),0, 0, 'R', 0);
                $y=$this->pdf->GetY()+4.7;$this->pdf->line(6,$y,208,$y);
                $y=$this->pdf->GetY()+4.5;$this->pdf->line(6,$y,208,$y);
                $y=$this->pdf->GetY()+0.4;$this->pdf->line(6,$y,208,$y);
                
            }
            else
            {
                $tot=0;
                $i=0;
                $y = $this->pdf->GetY();
		foreach($info as $ress){
		$this->pdf->headerSet('r_stock_sum',$ress->name,$ress->address01,$from,$filter);
		}
  
		$this->pdf->Ln(3);
		foreach($r_data as $result) {
                    
		$this->pdf->setFont('helvetica','',9);
		$this->pdf->SetX(12);
		$this->pdf->Cell(37, 1,$result->code,0, 0, 'L', 0);
		$this->pdf->Cell(130, 1,$result->item_name,0, 0, 'L', 0);    
		$this->pdf->Cell(15, 1,$result->qun,0, 0, 'R', 0);
                $tot=$tot+$result->value;
                
                $i=$i+1;
                
                if($i%5==0)
                {
                    $this->pdf->Ln();
                    $y=$this->pdf->GetY();$this->pdf->line(6,$y,208,$y);
                }
                
		$this->pdf->Ln();
		}

                $this->pdf->Ln();
                $this->pdf->setFont('helvetica','B',9);
		$this->pdf->Cell(150, 1,"Total ",0, 0, 'R', 0);
		$this->pdf->Cell(10, 1,"",0, 0, 'R', 0);
                $this->pdf->Cell(22, 1,d($tot),0, 0, 'R', 0);
                $y=$this->pdf->GetY()+4.7;$this->pdf->line(6,$y,208,$y);
                $y=$this->pdf->GetY()+4.5;$this->pdf->line(6,$y,208,$y);
                $y=$this->pdf->GetY()+0.4;$this->pdf->line(6,$y,208,$y);
                
                
                
            }    
	}
	
	if($r_type=="r_stock_qty")
        {
		$inv = '';
		$tot=0;
		
		foreach($info as $ress){
		$this->pdf->headerSet('r_stock_qty',$ress->name,$ress->address01,$from);
		}
		$this->pdf->Ln(5);
		
		foreach($r_data as $result) {
		
		$this->pdf->setFont('helvetica','',9);
		$this->pdf->setX(5);
		
		if ($inv != $result->no) {
			$total=0;
			
			$this->pdf->SetX(5);
			$this->pdf->setFont('helvetica','B',9);
			$this->pdf->Cell(200, 1,$result->no,1, 0, 'L', 0);
			//$this->pdf->Cell(0, 1,$result->sub_cat,1, 0, 'L', 0);
			$this->pdf->Ln();
		}
		
		$inv=$result->no;
		
		$this->pdf->setFont('helvetica','',9);
		$this->pdf->SetX(5);
		$this->pdf->Cell(30, 1,$result->item_code,0, 0, 'L', 0);
		$this->pdf->Cell(120, 1,$result->description,0, 0, 'L', 0);
		$this->pdf->Cell(10, 1,$result->original_qty,0, 0, 'R', 0);
		$this->pdf->Cell(40, 1,$result->quantity,0, 0, 'R', 0);
		
		$this->pdf->Ln();
		
		}
			
	}
        if($r_type=="r_cost_sales")
        {
            
            $inv = '';
            $tot1=$tot2=0;
		
		foreach($info as $ress){
		$this->pdf->headerSet('r_cost_sales',$ress->name,$ress->address01,$from,$to);
		}
		$this->pdf->Ln(5);
		
		foreach($r_data as $result) {
		
		$this->pdf->setFont('helvetica','',9);
		$this->pdf->setX(5);
		
		if ($inv != $result->sales_no) {
			$total=0;
			 if($inv!='')
                        {

                        $y=$this->pdf->GetY();$this->pdf->line(170,$y,290,$y);
			$this->pdf->setFont('helvetica','B',9);
			$this->pdf->Cell(20, 1,"",0, 0, 'R', 0);
                        $this->pdf->Cell(30, 1,"",0, 0, 'R', 0);
                        $this->pdf->Cell(110, 1,"",0, 0, 'R', 0);
                        $this->pdf->Cell(15, 1,"",0, 0, 'R', 0);
                        $this->pdf->Cell(35, 1,"Subtotal",0, 0, 'R', 0);
                        $this->pdf->Cell(25, 1,d($subtot1),0, 0, 'R', 0);
                        $this->pdf->Cell(25, 1,"",0, 0, 'R', 0);
                        $this->pdf->Cell(25, 1,d($subtot2),0, 0, 'R', 0);
                        $y=$this->pdf->GetY()+4.5;$this->pdf->line(170,$y,290,$y);
                        
                        $this->pdf->Ln();
                        
                        $y=$this->pdf->GetY();$this->pdf->line(170,$y,290,$y);
			$this->pdf->setFont('helvetica','B',9);
			$this->pdf->Cell(20, 1,"",0, 0, 'R', 0);
                        $this->pdf->Cell(30, 1,"",0, 0, 'R', 0);
                        $this->pdf->Cell(110, 1,"Discount-".$discount,0, 0, 'L', 0);
                        $this->pdf->Cell(15, 1,"",0, 0, 'R', 0);
                        $this->pdf->Cell(25, 1,"Net Amount",0, 0, 'R', 0);
                        $this->pdf->Cell(25, 1,d($subtot1-$discount),0, 0, 'R', 0);
                        $this->pdf->Cell(25, 1,"",0, 0, 'R', 0);
                        $this->pdf->Cell(25, 1,d($subtot2-$discount),0, 0, 'R', 0);
                        $y=$this->pdf->GetY()+4.5;$this->pdf->line(170,$y,290,$y);
                        
                        
                        
                        
                        
                        $this->pdf->Ln();
			$subtot1=0;	
			$subtot2=0;
                        $discount=0;
                        }
	
			$this->pdf->SetX(5);
			$this->pdf->setFont('helvetica','B',9);
			$this->pdf->Cell(20, 1,$result->sales_no,1, 0, 'R', 0);
			$this->pdf->Ln();
                        
                        $subtot1=0;
                        $subtot2=0;
		}
		
		$inv=$result->sales_no;
		
                $subtot1=$subtot1+$result->tot;
                $subtot2=$subtot2+$result->cost;
                $discount=$result->discount;
                
                
		$this->pdf->setFont('helvetica','',9);
		$this->pdf->SetX(25);
		$this->pdf->Cell(30, 1,$result->item_code,0, 0, 'L', 0);
		$this->pdf->Cell(110, 1,$result->description,0, 0, 'L', 0);
		$this->pdf->Cell(25, 1,$result->out_quantity,0, 0, 'R', 0);
		$this->pdf->Cell(25, 1,$result->sal_price,0, 0, 'R', 0);
		$this->pdf->Cell(25, 1,$result->tot,0, 0, 'R', 0);
		$this->pdf->Cell(25, 1,$result->avg_price,0, 0, 'R', 0);
		$this->pdf->Cell(25, 1,$result->cost,0, 0, 'R', 0);
		$tot1=$tot1+$result->tot;
		$tot2=$tot2+$result->cost;
		$this->pdf->Ln();
		
		}
            
                $this->pdf->Ln();
                $this->pdf->setFont('helvetica','B',9);
		$this->pdf->Cell(20, 1,"Total ",0, 0, 'R', 0);
		$this->pdf->Cell(30, 1,"",0, 0, 'R', 0);
		$this->pdf->Cell(110, 1,"",0, 0, 'R', 0);
		$this->pdf->Cell(15, 1,"",0, 0, 'R', 0);
		$this->pdf->Cell(25, 1,"",0, 0, 'R', 0);
                $this->pdf->Cell(25, 1,d($tot1),0, 0, 'R', 0);
                $this->pdf->Cell(25, 1,"",0, 0, 'R', 0);
                $this->pdf->Cell(25, 1,d($tot2),0, 0, 'R', 0);
                $y=$this->pdf->GetY()+4.7;$this->pdf->line(6,$y,290,$y);
                $y=$this->pdf->GetY()+4.5;$this->pdf->line(6,$y,290,$y);
                $y=$this->pdf->GetY()+0.4;$this->pdf->line(6,$y,290,$y);
            
        }    
        
        
        
        
function d($number) {
return number_format($number, 2, '.', ',');
}

function dd($number, $decimals) {
    return number_format($number, $decimals, '.', ',');
}        
                

	//$this->pdf->footerSet();
                
	$this->pdf->Output("Stock_Report_".date('Y-m-d').".pdf", 'I');
?>