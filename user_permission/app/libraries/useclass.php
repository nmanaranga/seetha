<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class useclass {
    
    private $CI;
    private $sd;
    private $db;
    
    function __construct(){
        // $CI =& get_instance();
        // $CI->load->library('session');
        
        // $this->sd = $CI->session->all_userdata();
        // $CI->load->database($this->sd['up_db'], true);
        // $db = $CI->up_db;
    }
    
    public function file_size_format($s){
        if($s<1024){
            return $s."bit";
        }elseif($s>=1024 && $s<(1024*1024)){
            return round(($s/1024), 2)." Kb";
        }elseif($s>=(1024*1024) && $s<(1024*1024*1024)){
            return round(($s/(1024*1024)), 2)." Mb";
        }elseif($s>=(1024*1024*1024)){
            return round(($s/(1024*1024*1024)), 2)." Gb";
        }
    }
    
    public function get_phone_array($p){
        $a = array();
        if(strstr($p, ',')){
            $p = explode(',', $p);
            foreach($p as $pp){
                $pp = trim($pp);
                if($this->pnv($pp)){
                    $a[] = $pp;
                }
            }
        }else{
            $p = trim($p);
            if($this->pnv($p)){
                $a[] = $p;
            }
        }
        
        return $a;
    }
    
    public function pnv($p){
        $ph = true;
        
        if(! preg_match("/^[+]{1}[0-9]{11}$/", $p) && ! preg_match("/^[0-9]{9}$/", $p) && ! preg_match("/^[0-9]{10}$/", $p)){
            $ph = false;
        }
        
        return $ph;
    }
    
    public function time_count($t){
        if($t<0){
            $p = "-";
            $t *= -1;
        }else{
            $p = "";
        }
        
        $d = floor($t/86400);
        $t -= $d*86400;
        
        $h = floor($t/3600);
        $t -= $h*3600;
        
        if($h < 10){ $h = "0".$h; }
        
        $m = floor($t/60);
        $t -= $m*60;
        
        if($m < 10){ $m = "0".$m; }
        if($t < 10){ $t = "0".$t; }
        
        return $p.$d." | ".$h.":".$m.":".$t;
    }
    
    public function limit_text($txt, $lim=50){
        if(strlen($txt)>$lim){
            $txt = substr($txt, 0, $lim)."...";
        }
        
        return $txt;
    }
    
    public function check_login(){
        if(isset($this->session_data['isLogin'])){
            if(! $this->session_data['isLogin']){
                redirect(base_url()."index.php");
            }
        }else{
            redirect(base_url()."index.php");
        }   
    }
    
    public function heading($title = ""){
        $con = "<html>
                <head>
                    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                    <link type='text/css' href='".base_url()."css/report.css' rel='stylesheet' />
                        <style type='text/css'>
                            body{
                                padding: 0px;
                                margin : 0px;
                            }
                        </style>
                    <title>".$title."</title>
                    <script type='text/javascript'>
                        function print_(){
                            window.print();
                            //window.close();
                        }
                    </script>
                </head>
                <body onload='print_()'>";
        return $con;
    }
    
    public function footer(){
        return "</body></html>";
    }
    
    public function grid_style($gname="grid", $w = "100%"){
        $tmpl = array (
            'table_open' => '<table border="0" style="width:'.$w.'" cellpadding="0" cellspacing="0" id="'.$gname.'" class="grid">',
            
            'heading_row_start'   => '<tr>',
            'heading_row_end'     => '</tr>',
            'heading_cell_start'  => '<th class="tb_head_th">',
            'heading_cell_end'    => '</th>',
            
            'row_start'           => '<tr class="tb_row_tr">',
            'row_end'             => '</tr>',
            'cell_start'          => '<td  class="tb_row_td">',
            'cell_end'            => '</td>',
            
            'row_alt_start'       => '<tr class="tb_row_tr_alt">',
            'row_alt_end'         => '</tr>',
            'cell_alt_start'      => '<td  class="tb_row_td_alt">',
            'cell_alt_end'        => '</td>',
            
            'table_close'         => '</table>'
        );
        
        return $tmpl;
    }
    
    public function report_style($gname="report"){
        $tmpl = array (
            'table_open'          => '<table border="0" width="100%" cellpadding="0" cellspacing="0" id="'.$gname.'" class="grid">',
            
            'heading_row_start'   => '<tr class="tb_rpt_head_tr">',
            'heading_row_end'     => '</tr>',
            'heading_cell_start'  => '<th class="tb_rpt_head_th">',
            'heading_cell_end'    => '</th>',
            
            'row_start'           => '<tr>',
            'row_end'             => '</tr>',
            'cell_start'          => '<td  class="tb_rpt_row_td">',
            'cell_end'            => '</td>',
            
            'row_alt_start'       => '<tr>',
            'row_alt_end'         => '</tr>',
            'cell_alt_start'      => '<td  class="tb_rpt_row_td_alt">',
            'cell_alt_end'        => '</td>',
            
            'table_close'         => '</table>'
        );
        
        return $tmpl;
    }
    
    public function r_header($tit, $dd=""){
        global $CI;
        $r = $CI->db->get('s_company')->first_row();
        $header = "<div id='header'>
                <div id='company'>".$r->name."</div>
                <div id='heading'>".$r->address01.", ".$r->address02.", ".$r->address03."</div>
                <div id='address'> TEL ".$r->phone01.", ".$r->phone02.", ".$r->phone03."<br />".$tit."</div>
                <div style='font-size: 12px; font-weight: bold; text-align: left;'>".$dd."</div>
            </div>";
            
        return $header;
    }
    
    
    
    public function dob_from_nic($nic){
        $y = substr($nic, 0, 2);
        $d = substr($nic, 2, 3);
        
        if($d > 500){ $d -= 500; }
        $m = 1;
        
        if($d > 31){ $d -= 31; $m++; }else{ return $y."-".$m."-".$d; }
        
        if($d > 29){ $d -= 29; $m++; }else{ return $y."-".$m."-".$d; }
        
        if($d > 31){ $d -= 31; $m++; }else{ return $y."-".$m."-".$d; }
        
        if($d > 30){ $d -= 30; $m++; }else{ return $y."-".$m."-".$d; }
        
        if($d > 31){ $d -= 31; $m++; }else{ return $y."-".$m."-".$d; }
        
        if($d > 30){ $d -= 30; $m++; }else{ return $y."-".$m."-".$d; }
        
        if($d > 31){ $d -= 31; $m++; }else{ return $y."-".$m."-".$d; }
        
        if($d > 31){ $d -= 31; $m++; }else{ return $y."-".$m."-".$d; }
        
        if($d > 30){ $d -= 30; $m++; }else{ return $y."-".$m."-".$d; }
        
        if($d > 31){ $d -= 31; $m++; }else{ return $y."-".$m."-".$d; }
        
        if($d > 30){ $d -= 30; $m++; }else{ return $y."-".$m."-".$d; }
        
        if($d > 31){ $d -= 31; $m++; }else{ return $y."-".$m."-".$d; }
    }
}

?>