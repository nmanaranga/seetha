<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_item_list extends CI_Model {
    
    private $tb_items;
    private $tb_main_cat;
    private $tb_sub_cat;
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
        $this->tb_main_cat = $this->tables->tb['m_main_cat'];
        $this->tb_sub_cat = $this->tables->tb['m_sub_cat'];
    }
    
    public function base_details(){
        // $a['report'] = $this->report();
        // $a['title'] = "Items";
        
        // return $a;
    }
    
    public function report(){
        
        // if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}
        // if(! isset($this->sd['to'])){ $this->sd['to'] = date("Y-m-d");}
        // if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
        // if(isset($this->sd['paper'])){
        //     if($this->sd['paper'] = "l"){
        //         $this->h = 279;
        //         $this->w = 216;
        //     }
        // }
        
        // if(! isset($this->sd['type'])){ $this->sd['type'] = "sum"; }
        
        // $this->db->select(array($this->tb_main_cat.'.description AS main_cat', $this->tb_sub_cat.'.description AS sub_cat', $this->tb_items.'.code', $this->tb_items.'.description AS item_name', 'purchase_price', 'cost_price','max_sales'));
        // $this->db->join($this->tb_main_cat, $this->tb_main_cat.".code = ".$this->tb_items.".main_cat", "INNER");
        // $this->db->join($this->tb_sub_cat, $this->tb_sub_cat.".code = ".$this->tb_items.".sub_cat", "INNER");
        // $query = $this->db->get($this->tb_items);
        
        // if($query->num_rows){
        //     $result = $query->result();
            
        //     $inv_no = array("data"=>"Main Cat.", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
        //     $job_no = array("data"=>"Sub Cat.", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
        //     $vehi = array("data"=>"Item Code","style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
        //     $cus = array("data"=>"Item Name", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
        //     $total = array("data"=>"Pur. Price", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
        //     $sale = array("data"=>"Sale Price", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
            
        //     $heading = array($inv_no, $job_no, $vehi, $cus);//, , 
        //     if($this->sd['type'] == "det"){ $heading[] = $total; } $heading[] = $sale;
            
            
        //     $inv_no = array("data"=>"main_cat", "total"=>false, "format"=>"text");
        //     $job_no  = array("data"=>"sub_cat", "total"=>false, "format"=>"text");
        //     $vehi  = array("data"=>"code", "total"=>false, "format"=>"text");
        //     $cus  = array("data"=>"item_name", "total"=>false, "format"=>"text");
        //     $total  = array("data"=>"cost_price", "total"=>false, "format"=>"amount");
        //     $sale  = array("data"=>"max_sales", "total"=>false, "format"=>"amount");
            
        //     $field = array($inv_no, $job_no, $vehi, $cus);//, $total, $sale
        //     if($this->sd['type'] == "det"){ $field[] = $total; } $field[] = $sale;
            
        //     $page_rec = 30;
            
        //     if($this->sd['type'] == "det"){
        //         $name = "Price List";
        //     }else{
        //         $name = "Item List";
        //     }
            
        //     $header  = array("data"=>$this->useclass->r_header($name), "style"=>"");
        //     $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
        //     $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
        //     $data = array(
        //                       "dbtem"=>$this->useclass->report_style(),
        //                       "data"=>$result,
        //                       "field"=>$field,
        //                       "heading"=>$heading,
        //                       "page_rec"=>$page_rec,
        //                       "height"=>$this->h,
        //                       "width"=>$this->w,
        //                       "header"=>30,
        //                       "header_txt"=>$header,
        //                       "footer_txt"=>$footer,
        //                       "page_no"=>$page_no,
        //                       "header_of"=>false
        //                       );
        //     //print_r($data); exit;
        //     $this->load->library('greport', $data);
            
        //     $resu = $this->greport->_print();
        // }else{
        //     $resu = "<span style='color:red;'>There is no data to load.</span>";
        // }
        
        // return $resu;
    }
}
?>