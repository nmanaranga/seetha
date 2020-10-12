<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_age_analyze_supplier extends CI_Model {
    
    private $tb_items;
    private $tb_move;
    private $sd;
    private $w = 210;
    private $h = 297;
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('useclass');
        
        $this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
        $this->tb_items = $this->tables->tb['m_items'];
        $this->tb_move = $this->tables->tb['t_item_movement'];
    }
    
    public function base_details(){
        // $this->load->model('m_root');
        // $this->load->model('m_area');
        
        // $a['report'] = $this->report();
        // $a['root'] = $this->m_root->select("des");
        // $a['area'] = $this->m_area->select("name");
        // $a['title'] = "Items";
        
        // return $a;
    }
    
    // public function report(){
    //     if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}
    //     if(! isset($this->sd['to'])){ $this->sd['to'] = date("Y-m-d");}
    //     if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
    //     if(isset($this->sd['paper'])){
    //         if($this->sd['paper'] = "l"){
    //             $this->h = 279;
    //             $this->w = 216;
    //         }
    //     }
        
    //     if(! isset($this->sd['stores'])){ $this->sd['stores'] = 0; }
    //     if(! isset($this->sd['type'])){ $this->sd['type'] = "sum"; }
        
    //     $this->load->model('m_age_analyze_setup');
        
    //     if($this->sd['type'] == "sum"){
    //         $sa = array("m_supplier.code", "CONCAT(name, ' (', full_name, ')') AS name");
    //         $old_date = $old_des = $old_key = '';  $dates = $this->m_age_analyze_setup->dates(); $x = 1; $total_a = array();
    //         foreach($dates as $r){
    //             if($old_date == ''){
    //                 $old_date = $r->date;
    //                 $old_des = $r->description;
    //                 $old_key = $r->key;
    //             }else{
    //                 $sa[]= "IFNULL(`".$old_key."`.amount, 0) AS `".$old_des."`";
                    
    //                 $sql = "(SELECT
    //                             supplier,
    //                             (SUM(dr_amount) - SUM(cr_amount)) AS amount
    //                         FROM t_supplier_acc_trance
    //                         WHERE date BETWEEN DATE('".$r->date."')+1 AND '".$old_date."'  
    //                         GROUP BY supplier) AS `".$old_key."`";
                    
    //                 $this->db->join($sql, $old_key.".supplier = m_supplier.code", "LEFT OUTER", false);
    //             }
                
    //             if(count($dates) == $x){
    //                 $sa[]= "IFNULL(`".$r->key."`.amount, 0) AS `".$r->description."`";
                    
    //                 $sql = "(SELECT
    //                             supplier,
    //                             (SUM(dr_amount) - SUM(cr_amount)) AS amount
    //                         FROM t_supplier_acc_trance
    //                         WHERE date <= '".$r->date."'  
    //                         GROUP BY supplier) AS `".$r->key."`";
                    
    //                 $this->db->join($sql, $r->key.".supplier = m_supplier.code", "LEFT OUTER", false);
    //             }
    //             $x++; $old_date = $r->date; $old_des = $r->description; $old_key = $r->key; $total_a[] = "IFNULL(`".$r->key."`.amount, 0)";
    //         }
            
    //         $sa[] = "(".join(" + ", $total_a).") AS total";
            
    //         $this->db->select($sa);
    //         $query = $this->db->get("m_supplier");
    //     }else{
    //         $sa = array("m_supplier.code", "CONCAT(name, ' (', full_name, ')') AS name");
            
    //         $old_date = $old_des = $old_key = '';  $dates = $this->m_age_analyze_setup->dates(); $x = 1; $total_a = array();
    //         foreach($dates as $r){
    //             if($old_date == ''){
    //                 $old_date = $r->date;
    //                 $old_des = $r->description;
    //                 $old_key = $r->key;
    //             }else{
    //                 $sa[]= "IFNULL(`".$old_key."`.amount, 0) AS `".$old_des."`";
    //                 $sa[]= "`".$old_key."`.dr_trnce_code AS `".$old_des."_code`";
    //                 $sa[]= "`".$old_key."`.dr_trnce_no AS `".$old_des."_no`";
    //                 $sa[]= "`".$old_key."`.date AS `".$old_des."_date`";
                    
    //                 $sql = "(SELECT
    //                             supplier, date, 
    //                             (SUM(dr_amount) - SUM(cr_amount)) AS amount, dr_trnce_code, dr_trnce_no
    //                         FROM t_supplier_acc_trance
    //                         WHERE date BETWEEN DATE('".$r->date."')+1 AND '".$old_date."'  
    //                         GROUP BY supplier, dr_trnce_code, dr_trnce_no) AS `".$old_key."`";
                    
    //                 $this->db->join($sql, $old_key.".supplier = m_supplier.code", "LEFT OUTER", false);
    //             }
                
    //             if(count($dates) == $x){
    //                 $sa[]= "IFNULL(`".$r->key."`.amount, 0) AS `".$r->description."`";
    //                 $sa[]= "`".$r->key."`.dr_trnce_code AS `".$r->description."_code`";
    //                 $sa[]= "`".$r->key."`.dr_trnce_no AS `".$r->description."_no`";
    //                 $sa[]= "`".$r->key."`.date AS `".$r->description."_date`";
                    
    //                 $sql = "(SELECT
    //                             supplier, date,
    //                             (SUM(dr_amount) - SUM(cr_amount)) AS amount, dr_trnce_code, dr_trnce_no
    //                         FROM t_supplier_acc_trance
    //                         WHERE date <= '".$r->date."'  
    //                         GROUP BY supplier, dr_trnce_code, dr_trnce_no) AS `".$r->key."`";
                    
    //                 $this->db->join($sql, $r->key.".supplier = m_supplier.code", "LEFT OUTER", false);
    //             }
    //             $x++; $old_date = $r->date; $old_des = $r->description; $old_key = $r->key; $total_a[] = "IFNULL(`".$r->key."`.amount, 0)";
    //         }
            
    //         $sa[] = "(".join(" + ", $total_a).") AS total";
            
    //         $this->db->select($sa);
    //         $query = $this->db->get("m_supplier");
    //         //print_r($query->result()); exit;
    //     }
    //     if($query->num_rows){
    //         if($this->sd['type'] == "sum"){
    //             $result = $query->result();
                
    //             $heading = $field = array();
                
    //             $heading[] = array("data"=>"Code", "style"=>"text-align: left; width:70px;", "chalign"=>"text-align: left;");
    //             $heading[] = array("data"=>"Type", "style"=>"text-align: left;", "chalign"=>"text-align: left;");
                
    //             $field[] = array("data"=>"code", "total"=>false, "format"=>"text");
    //             $field[]  = array("data"=>"name", "total"=>false, "format"=>"text");
                
    //             foreach($dates as $r){
    //                 $heading[] = array("data"=>$r->description, "style"=>"text-align: right;", "chalign"=>"text-align: right;");
    //                 $field[]  = array("data"=>$r->description, "total"=>false, "format"=>"amount");
    //             }
                
    //             $heading[] = array("data"=>"Total", "style"=>"text-align: right; font-weight: bold;", "chalign"=>"text-align: right; font-weight: bold;");
    //             $field[]  = array("data"=>"total", "total"=>false, "format"=>"amount");
                
    //             $page_rec = 30;
                
    //             $header  = array("data"=>$this->useclass->r_header("Age Analyze Supplier"), "style"=>"");
    //             $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
    //             $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
                
    //             $data = array(
    //                               "dbtem"=>$this->useclass->report_style(),
    //                               "data"=>$result,
    //                               "field"=>$field,
    //                               "heading"=>$heading,
    //                               "page_rec"=>$page_rec,
    //                               "height"=>$this->h,
    //                               "width"=>$this->w,
    //                               "header"=>30,
    //                               "header_txt"=>$header,
    //                               "footer_txt"=>$footer,
    //                               "page_no"=>$page_no,
    //                               "header_of"=>false
    //                               );
    //             //print_r($data); exit;
    //             $this->load->library('greport', $data);
                
    //             $resu = $this->greport->_print();
    //         }else{
    //             $result = $query->result();
                
    //             $heading = $field = $res = array();
                
    //             $heading[] = array("data"=>"Name & Code", "style"=>"text-align: left;", "chalign"=>"text-align: left;");
    //             $heading[] = array("data"=>"Inv. No", "style"=>"text-align: left; width:70px;", "chalign"=>"text-align: left;");
    //             $heading[] = array("data"=>"Date", "style"=>"text-align: left; width:70px;", "chalign"=>"text-align: left;");
                
    //             $field[] = array("data"=>"code_name", "total"=>false, "format"=>"text");
    //             $field[]  = array("data"=>"inv", "total"=>false, "format"=>"text");
    //             $field[]  = array("data"=>"date", "total"=>false, "format"=>"text");
                
    //             foreach($dates as $r){
    //                 $heading[] = array("data"=>$r->description, "style"=>"text-align: right;", "chalign"=>"text-align: right;");
    //                 $field[]  = array("data"=>$r->description, "total"=>false, "format"=>"text");
    //             }
                
    //             $code2 = "";
    //             foreach($result as $r){
    //                 if($code2 == "" || $code2 != $r->code){
    //                     $std = new stdClass;
                        
    //                     $std->code_name = array("data"=>$r->name." (".$r->code.")", "style"=>"font-weight : bold;");
    //                     $std->inv = "";
    //                     $std->date = "";
    //                     foreach($dates as $r2){
    //                         $std->{$r2->description} = "";
    //                     }
                        
    //                     $res[] = $std; $code2 = $r->code;
    //                 }
                    
    //                 $std = new stdClass;
    //                 $std->code_name = "";
    //                 foreach($dates as $r2){
    //                     $std->inv = "";
    //                     $std->date = "";
    //                     $std->{$r2->description} = "";
    //                 }
                    
    //                 $res[] = $std; $code2 = $r->code;
    //             }
                
    //             $page_rec = 30;
                
    //             $header  = array("data"=>$this->useclass->r_header("Age Analyze Supplier"), "style"=>"");
    //             $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
    //             $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
                
    //             $data = array(
    //                               "dbtem"=>$this->useclass->report_style(),
    //                               "data"=>$res,
    //                               "field"=>$field,
    //                               "heading"=>$heading,
    //                               "page_rec"=>$page_rec,
    //                               "height"=>$this->h,
    //                               "width"=>$this->w,
    //                               "header"=>30,
    //                               "header_txt"=>$header,
    //                               "footer_txt"=>$footer,
    //                               "page_no"=>$page_no,
    //                               "header_of"=>false
    //                               );
    //             //print_r($data); exit;
    //             $this->load->library('greport', $data);
                
    //             $resu = $this->greport->_print();
    //         }
    //     }else{
    //         $resu = "<span style='color:red;'>There is no data to load.</span>";
    //     }
        
    //     return $resu;
    // }
}
?>