<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//$this->pdf->setPrintHeader($header,$type,$duration);

$this->pdf->setPrintFooter(false);
$this->pdf->setPrintHeader(true);
$this->pdf->setPrintHeader($header,$type); 
//'helvetica' = $this->pdf->addTTFfont('fonts/timesbd.ttf', 'TrueTypeUnicode', '', 96);
$this->pdf->SetFont('helvetica', '', 12);
$this->pdf->AddPage('L','CHQ');   // L or P amd page type A4 or A3

foreach ($chq as $r){
	$cross_x = $r->crossing_x;	
	$cross_y = $r->crossing_y;	
	$bdate_x = $r->bdata_x;	
	$bdate_y = $r->bdate_y;

	$date1_x = $r->bdate1_x;	
	$date1_y = $r->bdate2_x;	

	$date2_x = $r->bdate2_x;	

	$month_x = $r->bmonth1_x;	
	$month2_x = $r->bmonth2_x;	

	$year_x = $r->byear1_x;	
	$year2_x = $r->byear2_x;	
	$year3_x = $r->byear3_x;	
	$year4_x = $r->byear4_x;	

	$payee_x = $r->payee_x;	
	$payee_y = $r->payee_y;

	$amount_in_word_x = $r->amw_x;	
	$amount_in_word_y = $r->amw_y;
	
	$pay_lenght = $r->paylength;
	$pay_gap = $r->paygap;

	$amw_lenght = $r->amwlength;
	$amw_gap = $r->amountgap;	
	$amw_second_x = $r->second_line_x;

	$amount_x = $r->amt_x;	
	$amount_y = $r->amt_y;
	
	$cross_only_x = $r->crossonly_x;
	$cross_only_y = $r->crossonly_y;
	
	$stamp1 = $r->stamp_1;
	$stamp2 = $r->stamp_2;

	$stamp_x = $r ->stamp_x;
	$stamp_y = $r ->stamp_y;
	
	$sign_x = $r ->sign_x;
	$sign_y = $r ->sign_y;	
}

foreach ($print as $x){
	$cross_word = $x->cross_word;
	$b_date = $x->bank_date;
	$payee = $x->payee_name;	
	$amnt = $x->amount;	
	$is_print_date = $x->is_bank_date;
	$is_cross= $x->is_cross_cheq;		
}

$b_date1 = explode("-",$b_date);
$year = $b_date1[0];
$month = $b_date1[1];
$day = $b_date1[2];

$day_one = str_split($day[0]);
$day_two = str_split($day[1]);

$month_one = str_split($month[0]);
$month_two = str_split($month[1]);

$year_one = str_split($year[0]);
$year_two = str_split($year[1]);
$year_three = str_split($year[2]);
$year_four = str_split($year[3]);

$cc = str_split($payee, $pay_lenght);

$dd = str_split($rec, $amw_lenght);

$stamp_count = strlen($stamp1);

$dots="";

for($h=0;$h<$stamp_count+20;$h++){
	$dots.=".";
}


if(count($cc)>1){
	$payee1 = $cc[0];
	$payee2 = $cc[1];
}else{
	$payee1 = $cc[0];
	$payee2 = "";
}

if(count($dd)>1){
	$amw1 = $dd[0];
	$amw2 = $dd[1];
}else{
	$amw1 = $dd[0];
	$amw2 = "";
}

if($is_cross=="1"){
	if($cross_word=="Cross Only"){
		$this->pdf->setY($cross_only_y);
		$this->pdf->setX($cross_only_x);
		$this->pdf->image("images/cross_only.png",$x = '',$y = '',$w = 10,$h = 8,$type = '',$link = '',$align = '',$resize = false,$dpi = 300,$palign = '',$ismask = false,$imgmask = false,$border = 0,$fitbox = false,$hidden = false,$fitonpage = false,$alt = false);
	}else{
		$this->pdf->setY($cross_y);
		$this->pdf->setX($cross_x);
		$this->pdf->Cell(30, 1, $cross_word, '0', 0, 'L', 0);
	}
}

if($is_print_date=="1"){
	$this->pdf->setY($bdate_y );
	$this->pdf->setX($date1_x);
	$this->pdf->Cell(30, 1,$day_one[0] , '0', 0, 'L', 0);
	
	$this->pdf->setY($bdate_y );
	$this->pdf->setX($date2_x);
	$this->pdf->Cell(30, 1,$day_two[0], '0', 0, 'L', 0);

	$this->pdf->setY($bdate_y);
	$this->pdf->setX($month_x);
	$this->pdf->Cell(30, 1,$month_one[0], '0', 0, 'L', 0);

	$this->pdf->setY($bdate_y);
	$this->pdf->setX($month2_x);
	$this->pdf->Cell(30, 1,$month_two[0], '0', 0, 'L', 0);

	$this->pdf->setY($bdate_y);
	$this->pdf->setX($year_x);
	if($year_x=="0"){
		$this->pdf->Cell(30, 1,"", '0', 0, 'L', 0);	
	}else{
		$this->pdf->Cell(30, 1,$year_one[0], '0', 0, 'L', 0);	
	}

	$this->pdf->setY($bdate_y);
	$this->pdf->setX($year2_x);
	if($year2_x=="0"){
		$this->pdf->Cell(30, 1,"", '0', 0, 'L', 0);	
	}else{
		$this->pdf->Cell(30, 1,$year_two[0], '0', 0, 'L', 0);
	}

	$this->pdf->setY($bdate_y);
	$this->pdf->setX($year3_x);
	if($year3_x=="0"){
		$this->pdf->Cell(30, 1,"" , '0', 0, 'L', 0);	
	}else{
		$this->pdf->Cell(30, 1,$year_three[0] , '0', 0, 'L', 0);
	}

	$this->pdf->setY($bdate_y);
	$this->pdf->setX($year4_x);
	if($year4_x=="0"){
		$this->pdf->Cell(30, 1,"", '0', 0, 'L', 0);
	}else{
		$this->pdf->Cell(30, 1,$year_four[0], '0', 0, 'L', 0);
	}
		
}

$this->pdf->setY($payee_y);
$this->pdf->setX($payee_x);
$this->pdf->Cell(100, 1,$payee1, '0', 0, 'L', 0);

$this->pdf->setY($payee_y+$pay_gap);
$this->pdf->setX($payee_x);
$this->pdf->Cell(100, 1,$payee2, '0', 0, 'L', 0);

$this->pdf->setY($amount_in_word_y);
$this->pdf->setX($amount_in_word_x);

$heigh=$amw_gap*(max(1,$this->pdf->getNumLines($rec,$amw_lenght)));
//$this->pdf->setCellHeightRatio(1.3);
$this->pdf->MultiCell($amw_lenght, $heigh,$rec,0, 'L',false, 0, '', '', true, 0, false, true, $heigh,'M' ,false);

/*$this->pdf->Cell(100, 1,$amw1, '0', 0, 'L', 0);
$this->pdf->setY($amount_in_word_y+$amw_gap);
$this->pdf->setX($amw_second_x);
$this->pdf->Cell(100, 1,$amw2, '0', 0, 'L', 0);*/

$this->pdf->SetFont('helvetica', '', 13);
$this->pdf->setY($amount_y);
$this->pdf->setX($amount_x);
$this->pdf->Cell(100, 1,number_format($amnt,2), '0', 0, 'L', 0);

$this->pdf->SetFont('helvetica', '', 12);
$this->pdf->setY($stamp_y);
$this->pdf->setX($stamp_x);
if($stamp_x=="0"){
	$this->pdf->Cell(100, 1,"", '0', 0, 'L', 0);
}else{
	$this->pdf->Cell(100, 1,$stamp1, '0', 0, 'L', 0);
}


$this->pdf->setY($stamp_y+10);
$this->pdf->setX($stamp_x);
if($stamp_x=="0"){
	$this->pdf->Cell(100, 1,"", '0', 0, 'L', 0);
}else{
	$this->pdf->Cell(100, 1,$dots, '0', 0, 'L', 0);
}

$this->pdf->setY($sign_y);
$this->pdf->setX($sign_x);
$this->pdf->Cell(100, 1,$stamp2, '0', 0, 'L', 0);


$this->pdf->Output("Cheque Print_ ".date('Y-m-d').".pdf", 'I');

?>