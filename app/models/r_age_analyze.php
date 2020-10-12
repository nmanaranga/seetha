<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_age_analyze extends CI_Model {
    
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
        // $a['title'] = "Age Analyze";
        
        // return $a;
    }
    
    // public function report(){
        
    //     error_reporting(0);
        
    //     if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}
    //     if(! isset($this->sd['to'])){ $this->sd['to'] = date("Y-m-d");}
    //     if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
    //      if(! isset($this->sd['type'])){ $this->sd['type'] = 0;}
    //     if(isset($this->sd['paper'])){
    //         if($this->sd['paper'] = "l"){
    //             $this->h = 279;
    //             $this->w = 216;
    //         }
    //     }
        
    //     if(! isset($this->sd['stores'])){ $this->sd['stores'] = 0; }
    //     if(! isset($this->sd['item_code'])){ $this->sd['item_code'] = ""; }
        
    //     $this->load->model('m_age_analyze_setup');
        
    //     if($this->sd['type'] == "sum"){
    //         $sa = array("m_customer.code", "CONCAT(outlet_name, ' (', name, ')') AS name");
    //         $old_date = $old_des = $old_key = '';  $dates = $this->m_age_analyze_setup->dates(); $x = 1; $total_a = array();
    //         foreach($dates as $r){
    //             if($old_date == ''){
    //                 $old_date = $r->date;
    //                 $old_des = $r->description;
    //                 $old_key = $r->key;
    //             }else{
    //                 $sa[]= "IFNULL(`".$old_key."`.amount, 0) AS `".$old_des."`";
                    
    //                 $sql = "(SELECT
    //                             t_customer_acc_trance.customer,
    //                             (SUM(dr_amount) - SUM(cr_amount)) AS amount,
    //                             IFNULL(b.adv_bal,0) AS adv_bal
                                
    //                         FROM t_customer_acc_trance
    //                         INNER JOIN m_customer ON t_customer_acc_trance.customer=m_customer.`code`
    //                          LEFT JOIN 
    //                         (SELECT customer,
    //                           (
    //                             SUM(`dr_trance_amount`) - SUM(`cr_trance_amount`)
    //                           ) AS adv_bal 
    //                         FROM
    //                           `t_advance_pay_trance` 
    //                         GROUP BY customer) AS b ON  m_customer.code=b.customer

    //                         WHERE date BETWEEN DATE('".$r->date."')+1 AND '".$old_date."'  
    //                         GROUP BY customer) AS `".$old_key."`";    

                    
    //                 $this->db->join($sql, $old_key.".customer = m_customer.code", "LEFT OUTER", false);
    //             }
                
    //             if(count($dates) == $x){
    //                 $sa[]= "IFNULL(`".$r->key."`.amount, 0) AS `".$r->description."`";
                    
    //                 $sql = "(SELECT
    //                             t_customer_acc_trance.customer,
    //                              (SUM(dr_amount) - SUM(cr_amount)) AS amount,
    //                             IFNULL(b.adv_bal,0) AS adv_bal
    //                         FROM t_customer_acc_trance
    //                         INNER JOIN m_customer ON t_customer_acc_trance.customer=m_customer.`code`
    //                          LEFT JOIN 
    //                         (SELECT customer,
    //                           (
    //                             SUM(`dr_trance_amount`) - SUM(`cr_trance_amount`)
    //                           ) AS adv_bal 
    //                         FROM
    //                           `t_advance_pay_trance` 
    //                         GROUP BY customer) AS b ON  m_customer.code=b.customer
    //                         WHERE date <= '".$r->date."'  
    //                         GROUP BY customer) AS `".$r->key."`";
                    
    //                 echo $sql;exit;
                    
    //                 $this->db->join($sql, $r->key.".customer = m_customer.code", "LEFT OUTER", false);
    //             }
    //             $x++; $old_date = $r->date; $old_des = $r->description; $old_key = $r->key; 
    //             $total_a[] = "IFNULL(`".$r->key."`.amount, 0)";
    //             $total_adv[] = "IFNULL(`".$r->key."`.adv_bal, 0)";
    //         }
            
    //         $sa[] = "(".join(" + ", $total_a).") AS total";
    //         $sa[] = "(".join(" + ", $total_adv).") AS adv_bal";
    //         $sa[] = "m_root.description AS root";
    //         $sa[] = "m_area.description AS area";
            
    //         $this->db->select($sa);
    //         $this->db->join("m_root", "m_root.code = m_customer.root", "INNER");
    //         $this->db->join("m_area", "m_area.code = m_root.area", "INNER");
    //         $query = $this->db->get("m_customer");
    //     }else{
            
    //     }
    //     if($query->num_rows){
    //         if($this->sd['type'] == "sum"){
    //             $result = $query->result();
                
    //             $heading = $field = array();
                
    //             $heading[] = array("data"=>"Code", "style"=>"text-align: left; width:70px;", "chalign"=>"text-align: left;");
    //             $heading[] = array("data"=>"Name", "style"=>"text-align: left;", "chalign"=>"text-align: left;");
                
    //             $field[] = array("data"=>"code", "total"=>false, "format"=>"text");
    //             $field[]  = array("data"=>"name", "total"=>false, "format"=>"text");
                
    //             foreach($dates as $r){
    //                 $heading[] = array("data"=>$r->description, "style"=>"text-align: right;", "chalign"=>"text-align: right;");
    //                 $field[]  = array("data"=>$r->description, "total"=>false, "format"=>"amount");
    //             }
                

    //             $heading[] = array("data"=>"Advance", "style"=>"text-align: right; font-weight: bold;", "chalign"=>"text-align: right; font-weight: bold;");
    //             $heading[] = array("data"=>"Total", "style"=>"text-align: right; font-weight: bold;", "chalign"=>"text-align: right; font-weight: bold;");
    //             $field[]  = array("data"=>"adv_bal", "total"=>false, "format"=>"amount");
    //             $field[]  = array("data"=>"total", "total"=>false, "format"=>"amount");
               
                
                
    //             $page_rec = 30;
                
    //             if($this->sd['area'] == "0"){
    //                 $name = "Age Analyze List";
    //             }else{
    //                 $name = "Age Analyze List | ".$result[0]->area." (".$this->sd['area'].") Area & All Routes";
    //                 if($this->sd['root'] != "0"){
    //                     $name = "Age Analyze List | Area : ".$result[0]->area." (".$this->sd['area'].") | Route : ".$result[0]->root." (".$this->sd['root'].")";
    //                 }
    //             }
                
    //             $header  = array("data"=>$this->useclass->r_header($name), "style"=>"");
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
    //             $resu = "<span style='color:red;'>det</span>";
    //         }
    //     }else{
    //         $resu = "<span style='color:red;'>There is no data to load.</span>";
    //     }
        
    //     return $resu;
    // }
}
?>