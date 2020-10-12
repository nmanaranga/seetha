<?php

	$this->pdf->setPrintHeader($header,$type,$duration);
	$this->pdf->setPrintHeader(true,$type);
    $this->pdf->setPrintFooter(true);    
    $this->pdf->SetFont('helvetica', '', 8);
	$this->pdf->AddPage("L","A4");   // L or P amd page type A4 or A3
 	
    $this->pdf->MultiCell(30, 0, "XXXXX", $border = '1', $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0, $valign = 'T', $fitcell = false);
	$this->pdf->MultiCell(30, 0, "XXXXX", $border = '1', $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0, $valign = 'T', $fitcell = false);
	$this->pdf->MultiCell(30, 0, "XXXXX", $border = '1', $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0, $valign = 'T', $fitcell = false);
	$this->pdf->MultiCell(30, 0, "XXXXX", $border = '1', $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0, $valign = 'T', $fitcell = false);
	$this->pdf->MultiCell(30, 0, "XXXXX", $border = '1', $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0, $valign = 'T', $fitcell = false);
	$this->pdf->MultiCell(30, 0, "XXXXX", $border = '1', $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0, $valign = 'T', $fitcell = false);
	$this->pdf->MultiCell(30, 0, "XXXXX", $border = '1', $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0, $valign = 'T', $fitcell = false);
	$this->pdf->MultiCell(30, 0, "XXXXX", $border = '1', $align = 'L', $fill = false, $ln = 0, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0, $valign = 'T', $fitcell = false);
	$this->pdf->MultiCell(30, 0, "XXXXX", $border = '1', $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0, $valign = 'T', $fitcell = false);	

	foreach($r_data as $data){
		$this->pdf->MultiCell(270, 0, "XXXXX", $border = '1', $align = 'L', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0, $valign = 'T', $fitcell = false);     	   
    }
                   	



	
	$this->pdf->Output("R_".date('Y-m-d').".pdf", 'I');

?>