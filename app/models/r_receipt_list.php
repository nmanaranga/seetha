<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_receipt_list extends CI_Model {
    
    private $tb_items;
    private $tb_cus;
    private $tb_sales;
    private $sd;
    private $w = 210;
    private $h = 297;
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('useclass');
        
        $this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
        $this->tb_cus = $this->tables->tb['m_customer'];
        $this->tb_storse = $this->tables->tb['m_stores'];
	$this->tb_sales = $this->tables->tb['t_customer_receipt_sum'];
    }
    
    public function base_details(){
        // $this->load->model('m_stores');
        
        // $a['report'] = $this->report();
        // $a['stores'] = $this->m_stores->select("des", "");
        // $a['title'] = "Items";
        
        // return $a;
    }
    
 //    public function report(){
 //        if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}
 //        if(! isset($this->sd['to'])){ $this->sd['to'] = date("Y-m-d");}
 //        if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
 //        if(isset($this->sd['paper'])){
 //            if($this->sd['paper'] = "l"){
 //                $this->h = 279;
 //                $this->w = 216;
 //            }
 //        }
        
 //        if(! isset($this->sd['stores'])){ $this->sd['stores'] = 0; }
 //        if(! isset($this->sd['item_code'])){ $this->sd['item_code'] = ""; }
	
	// $query = $this->db
	// 		->select(array('no', 'IF(`ref_no` = "Reference No", "", `ref_no`) AS ref_no', 'name', 'cash_amount', 'cheque_amount', '(cash_amount + cheque_amount) AS total'))
	// 		->where('date BETWEEN ', "'".$this->sd['from']."' AND '".$this->sd['to']."'", false)
	// 		->where('is_cancel', 0)
	// 		->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sales.".customer", "INNER")
	// 		->order_by('CAST(ref_no AS DECIMAL)', 'ASC')
	// 		->get($this->tb_sales);
	
 //        if($query->num_rows){
 //            $result = $query->result();
	    
	//     $t_c = $t_cheque = $t_credit = $tt = $t_dis = 0;
	//     foreach($result as $r){
	// 	$t_c += $r->cash_amount;
	// 	$t_cheque += $r->cheque_amount;
	//     }
	    
	//     $tt = $t_c + $t_cheque;
	    
	//     $std = new stdClass;
	//     $std->name = array("data"=>"Toatal", "style"=>"border: none; font-weight : bold; text-align : right;");
	//     $std->no = '';
	//     $std->cash_amount = array("data"=>$t_c, "style"=>"border-bottom: 1px double #000; font-weight : bold; text-align : right;");
	//     $std->cheque_amount = array("data"=>$t_cheque, "style"=>"border-bottom: 1px double #000; font-weight : bold; text-align : right;");
	//     $std->ref_no = '';
	//     $std->total = array("data"=>$tt, "style"=>"border-bottom: 1px double #000; font-weight : bold; text-align : right;");
	    
	//     array_push($result, $std);
	    
	//     $heading = $field = array();
	    
 //            $heading[] = array("data"=>"RCP. No", "style"=>"text-align: left; width : 80px", "chalign"=>"text-align: left;");
 //            $heading[] = array("data"=>"REF. No", "style"=>"text-align: left; width:80px", "chalign"=>"text-align: left;");
 //            $heading[] = array("data"=>"CUSTOMER", "style"=>"text-align: left;", "chalign"=>"text-align: left;");
 //            $heading[] = array("data"=>"CASH AMO.", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
 //            $heading[] = array("data"=>"CHQ. AMO", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
 //            $heading[] = array("data"=>"TOTAL", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
            
 //            $field[] = array("data"=>"no", "total"=>false, "format"=>"text");
 //            $field[] = array("data"=>"ref_no", "total"=>false, "format"=>"text");
 //            $field[] = array("data"=>"name", "total"=>false, "format"=>"text", "limit"=>23);
 //            $field[]  = array("data"=>"cash_amount", "total"=>false, "format"=>"amount");
 //            $field[]  = array("data"=>"cheque_amount", "total"=>false, "format"=>"amount");
 //            $field[]  = array("data"=>"total", "total"=>false, "format"=>"amount");
            
 //            $page_rec = 25;
            
 //            $header  = array("data"=>$this->useclass->r_header("Date : ".$this->sd['from']." To ".$this->sd['to']."<br />Receipt List"), "style"=>"font-size : 18px; font-weight : bold;");
 //            $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
 //            $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
 //            $data = array(
 //                "dbtem"=>$this->useclass->report_style(),
 //                "data"=>$result,
 //                "field"=>$field,
 //                "heading"=>$heading,
 //                "page_rec"=>$page_rec,
 //                "height"=>$this->h,
 //                "width"=>$this->w,
 //                "header"=>37,
 //                "header_txt"=>$header,
 //                "footer_txt"=>$footer,
 //                "page_no"=>$page_no,
 //                "header_of"=>false
 //            );
            
	//     //print_r($data); exit;
 //            $this->load->library('greport', $data);
            
 //            $resu = $this->greport->_print();
 //        }else{
 //            $resu = "<span style='color:red;'>There is no data to load.</span>";
 //        }
        
 //        return $resu;
 //    }
}
?>