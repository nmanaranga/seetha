<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//latest update 2012/01/14

class greport {

    private $page_h = 297;
    private $page_w = 210;
    
    private $header = 50;
    
    private $footer = 15;
    private $body;
    private $bno = false;
    private $tno = false;
    private $header_of = false;
    private $add_rec = 10;
    
    private $margin = 5;
    
    private $header_txt = array("data"=>"Header", "style"=>"text-align: center; font-size: large;");
    private $footer_txt = array("data"=>"Footer", "style"=>"text-align: center; font-size: small;");
    private $page_no    = array("data"=>"Page Number", "style"=>"font-size: small;", "horizontal"=>"right", "vertical"=>"bottom");
    private $page_end = "";
    
    
    private $tb_header;
    private $tb_data;
    private $tb_style;
    private $field;
    private $page_rec;
    private $txt_total = "";
    
    private $CI;
    
    
    public function __construct($data){
        $CI =& get_instance();
        $this->config($data);
        
        $CI->load->library('table');
        $CI->load->library('useclass');
        $CI->table->set_template($this->tb_style);
    }
    
    public function reload($data){
        $this->config($data);
    }
    
    public function _print(){
        return $this->genarate();
    }
    
    private function genarate(){
        global $CI;
        $this->make_head();
        $rpt  = "<div id='greport'>";
        $row = 1; $j = 1; $total = array(); $ttotal = array(); $tstotal = array(); $ftotal = array();
        foreach($this->tb_data as $r){
            $rec = array();
            for($i=0; $i<count($this->field); $i++){
                if(isset($this->field[$i]["format"])){
                    if($this->field[$i]["format"]=="number"){
                        if(is_array($r->{$this->field[$i]["data"]})){
                            $rec[$i]["data"] = number_format($r->{$this->field[$i]["data"]}["data"], 0, ".", ",");
                            $rec[$i]["style"] = $r->{$this->field[$i]["data"]}["style"];
                        }else{
                            $rec[$i]["data"] = number_format($r->{$this->field[$i]["data"]}, 0, ".", ",");
                        }
                    }elseif($this->field[$i]["format"] == "amount"){
                        if(is_array($r->{$this->field[$i]["data"]})){
                            $rec[$i]["data"] = number_format($r->{$this->field[$i]["data"]}["data"], 2, ".", ",");
                            $rec[$i]["style"] = $r->{$this->field[$i]["data"]}["style"];
                        }else{
                            $rec[$i]["data"] = number_format($r->{$this->field[$i]["data"]}, 2, ".", ",");
                        }
                    }else{
                        if(is_array($r->{$this->field[$i]["data"]})){
                            if(isset($this->field[$i]["limit"])){
                                $rec[$i]["data"] = $CI->useclass->limit_text($r->{$this->field[$i]["data"]}["data"], $this->field[$i]["limit"]);
                            }else{
                                $rec[$i]["data"] = $r->{$this->field[$i]["data"]}["data"];
                            }
                            $rec[$i]["style"] = $r->{$this->field[$i]["data"]}["style"];
                        }else{
                            if(isset($this->field[$i]["limit"])){
                                $rec[$i]["data"] = $CI->useclass->limit_text($r->{$this->field[$i]["data"]}, $this->field[$i]["limit"]);
                            }else{
                                $rec[$i]["data"] = $r->{$this->field[$i]["data"]};
                            }
                        }
                    }
                }else{
                    if(is_array($r->{$this->field[$i]["data"]})){
                        if(isset($this->field[$i]["limit"])){
                            $rec[$i]["data"] = $CI->useclass->limit_text($r->{$this->field[$i]["data"]}["data"], $this->field[$i]["limit"]);
                        }else{
                            $rec[$i]["data"] = $r->{$this->field[$i]["data"]["data"]};
                        }
                        $rec[$i]["style"] = $r->{$this->field[$i]["data"]}["style"];
                    }else{
                        if(isset($this->field[$i]["limit"])){
                            $rec[$i]["data"] = $CI->useclass->limit_text($r->{$this->field[$i]["data"]}, $this->field[$i]["limit"]);
                        }else{
                            $rec[$i]["data"] = $r->{$this->field[$i]["data"]};
                        }
                    }
                }
                
                if(isset($this->tb_header[$i]["chalign"]) && ! isset($rec[$i]["style"])){ $rec[$i]["style"] = $this->tb_header[$i]["chalign"]; }
            }
            
            
            $CI->table->add_row($rec);
            
            if($row >= $this->page_rec){
                for($i=0; $i<count($this->field); $i++){
                    if(isset($total[$i])){
                        $tstotal[$i]["data"] = number_format($total[$i], 2, ".", ",");
                        $tstotal[$i]["style"] = "text-align: right; font-weight: bold;";
                        if(isset($ttotal[$i])){
                            $ttotal[$i] += $total[$i];
                        }else{
                            $ttotal[$i] = $total[$i];
                        }
                        $total[$i] = 0;
                    }else{
                        $tstotal[$i]["data"] = "";
                        $ttotal[$i] = "";
                    }
                }
                $CI->table->add_row($tstotal);
                $rpt .= $this->page($CI->table->generate(), $j);
                $this->make_head();
                $j++; $row = 1;
            }else{
                for($i=0; $i<count($this->field); $i++){
                    if(isset($total[$i])){
                        $ttotal[$i] = $total[$i];
                    }
                }
                $row++;
            }
        }
        
        if($row>1){
            $rpt .= $this->page($CI->table->generate()."".$this->page_end, $j);
        }elseif($this->page_end != ""){
            $rpt .= $this->page($this->page_end, $j);
        }
        
        $rpt .= "</div>";
        
        return $rpt;
    }
    
    private function make_head(){
        global $CI;
        $hed = array(); $style = "";
        
        for($i=0; $i<count($this->tb_header); $i++){
            if(isset($this->tb_header[$i]["style"])) { $style = $this->tb_header[$i]["style"]; }else{ $style = ""; }
            $hed[] = array("data"=>$this->tb_header[$i]["data"], "style"=>$style);
        }
        
        $CI->table->set_heading($hed);
    }
    
    private function page($data_, $no){
        $tno = $bno = "";
        if($this->tno){
            $tno = $no;
        }elseif($this->bno){
            $bno = $no;
        }
        $rpt  = "<div style='width: ".($this->page_w - (($this->margin+1)*2))."mm; height:".($this->page_h  - (($this->margin+1)*2))."mm; padding: ".$this->margin."mm;'>";
            if(! $this->header_of || intval($no)==1){
                $rpt .= "<div style='height: ".$this->header."mm;'>";
                    $rpt .= "<div style=' ".$this->page_no['style']." text-align: ".$this->page_no['horizontal'].";'>".$tno."</div>";
                    $rpt .= "<div style='".$this->header_txt["style"]."'>".$this->header_txt["data"]."</div>";
                $rpt .= "</div>";
            }elseif($this->header_of && intval($no)==2){
                $this->body += $this->header;
                $this->page_rec += $this->add_rec;
            }
            $rpt .= "<div style='height: ".$this->body."mm'>".$data_."</div>";
            $rpt .= "<div style='height: ".$this->footer."mm'>";
                $rpt .= "<div style='".$this->footer_txt["style"]."'>".$this->footer_txt["data"]."</div>";
                $rpt .= "<div style=' ".$this->page_no['style']." text-align: ".$this->page_no['horizontal'].";'>".$bno."</div>";
            $rpt .= "</div>";
        $rpt .= "</div>";
        
        return $rpt;
    }
    
    private function config($data){
        $this->tb_style = $data["dbtem"];
        $this->tb_data = $data["data"];
        
        $this->tb_header = $data["heading"];
        $this->field = $data["field"];
        $this->page_rec = $data["page_rec"];
        
        if(isset($data["height"])){ $this->page_h = intval($data["height"]); }
        if(isset( $data["width"])){ $this->page_w = intval($data["width"]);  }
        if(isset($data["header"])){ $this->header = intval($data["header"]); }
        if(isset($data["footer"])){ $this->footer = intval($data["footer"]); }
        if(isset($data["margin"])){ $this->margin = intval($data["margin"]); }
        
        $this->body = $this->page_h - ($this->footer + $this->header + ($this->margin*2));
        
        if(isset($data["header_txt"])){ $this->header_txt = $data["header_txt"]; }
        if(isset($data["footer_txt"])){ $this->footer_txt = $data["footer_txt"]; }
        if(isset($data["page_no"])){ $this->page_no = $data["page_no"]; }
        
        if(isset($data["page_no"]["vertical"])){
            if($data["page_no"]["vertical"] == "top"){
                $this->tno = true;
            }else{
                $this->bno = true;
            }
        }else{
            $this->bno = true;
        }
        
        if(isset($data["header_of"])){ $this->header_of = $data["header_of"]; }
        if(isset($data["add_rec"])){ $this->add_rec = $data["add_rec"]; }
        
        if(isset($data["page_end"])){ $this->page_end = $data["page_end"]; }
    }
    
}

?>