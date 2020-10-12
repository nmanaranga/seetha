<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_collection extends CI_Model {
    
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
        // $this->load->model('m_stores');
        
        // $a['report'] = $this->report();
        // $a['stores'] = $this->m_stores->select("des");
        // $a['title'] = "Items";
        
        // return $a;
    }
    
  //   public function report(){
  //       if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}
  //       if(! isset($this->sd['to'])){ $this->sd['to'] = date("Y-m-d");}
  //       if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
  //       if(isset($this->sd['paper'])){
  //           if($this->sd['paper'] = "l"){
  //               $this->h = 279;
  //               $this->w = 216;
  //           }
  //       }
        
  //       if(! isset($this->sd['stores'])){ $this->sd['stores'] = 0; }
  //       if(! isset($this->sd['type'])){ $this->sd['type'] = "sum"; }
        
  //       if($this->sd['stores'] != "0"){
  //           $where = "WHERE `stores` = '".$this->sd['stores']."'";
  //       }else{
  //           $where = "";
  //       }
        
  //       $sql = "SELECT
		//     `m_customer`.`code` AS `code`,
		//     `outlet_name`,
		//     `no`,
		//     `ref_no`,
		//     `cheque_amount`,
		//     `cash_amount`,
		//     'RECEIPT'           AS `type`
		//   FROM t_customer_receipt_sum
		//     INNER JOIN m_customer
		//       ON (m_customer.code = t_customer_receipt_sum.customer)
		//   WHERE is_cancel = 0
		//       AND `date` BETWEEN '".$this->sd['from']."'
		//       AND '".$this->sd['to']."'UNION SELECT
		// 			      `m_customer`.`code`  AS `code`,
		// 			      `outlet_name`,
		// 			      `no`,
		// 			      `ref_no`,
		// 			      `cheque`             as cheque_amount,
		// 			      `cash`               as cash_amount,
		// 			      'SALES'              AS `type`
		// 			    FROM t_sales_sum
		// 			      INNER JOIN m_customer
		// 				ON (m_customer.code = t_sales_sum.customer)
		// 			    WHERE is_cancel = 0
		// 				and (cheque > 0 || cash > 0)
		// 				AND `date` BETWEEN '".$this->sd['from']."'
		// 				AND '".$this->sd['to']."'";
  //       //echo $sql; exit;
  //       $query = $this->db->query($sql);
        
  //       if($query->num_rows){
  //           $result = $query->result();
  //           $res = array();
  //           $chet = $casht = $chett = $cashtt = 0; $type = "RECEIPT";
            
	 //    foreach($result as $r){
		// if($type != $r->type){
		//     $std = new stdClass;
		    
		//     $std->code = "";
		//     $std->name = "";
		//     $std->no = "";
		//     $std->ref = array("data"=>"Sub Total", "style"=>"font-weight : bold; text-align: right;border-top : 1px solid #000; border-bottom : 2px solid #000; ");
		//     $std->cheque = array("data"=>$chet, "style"=>"font-weight : bold; text-align: right;border-top : 1px solid #000; border-bottom : 2px solid #000; ");
		//     $std->cash = array("data"=>$casht, "style"=>"font-weight : bold; text-align: right;border-top : 1px solid #000; border-bottom : 2px solid #000; ");
		//     $std->type = "";
		    
		//     $res[] = $std;
		    
		//     $chet = $casht = 0; $type = $r->type;
		// }
		
		// $std = new stdClass;
		
		// if($r->ref_no == "Reference No"){
		//     $r->ref_no = "";
		// }
		
		// $std->code = $r->code;
		// $std->name = $r->outlet_name;
		// $std->no = $r->no;               
		// $std->ref = $r->ref_no;
		// $std->cash = $r->cash_amount;
		// $std->cheque = $r->cheque_amount;
		// $std->type = $r->type;
		
		// $res[] = $std;
		
		// $chet += $r->cheque_amount;
		// $casht += $r->cash_amount;
		// $chett += $r->cheque_amount;
		// $cashtt += $r->cash_amount;
	 //    }
	    
  //           $std = new stdClass;
	    
	 //    $std->code = "";
	 //    $std->name = "";
	 //    $std->no = "";
	 //    $std->ref = array("data"=>"Sub Total", "style"=>"font-weight : bold; text-align: right;border-top : 1px solid #000;");
	 //    $std->cheque = array("data"=>$chet, "style"=>"font-weight : bold; text-align: right;border-top : 1px solid #000; ");
	 //    $std->cash = array("data"=>$casht, "style"=>"font-weight : bold; text-align: right;border-top : 1px solid #000;");
	 //    $std->type = "";
	    
	 //    $res[] = $std;
	    
	 //    $std = new stdClass;
	    
	 //    $std->code = "";
	 //    $std->name = "";
	 //    $std->no = "";
	 //    $std->ref = array("data"=>"Total", "style"=>"font-weight : bold; text-align: right;border-top : 1px solid #000; border-bottom : 2px solid #000; ");
	 //    $std->cheque = array("data"=>$chett, "style"=>"font-weight : bold; text-align: right;border-top : 1px solid #000; border-bottom : 2px solid #000; ");
	 //    $std->cash = array("data"=>$cashtt, "style"=>"font-weight : bold; text-align: right;border-top : 1px solid #000; border-bottom : 2px solid #000; ");
	 //    $std->type = array("data"=>number_format($cashtt+$chett, 2, '.', ','), "style"=>"font-weight : bold; text-align: right;border-top : 1px solid #000; border-bottom : 2px solid #000; ");
	    
	 //    $res[] = $std;
	    
	 //    $field = $heading = array();
	    
  //           $heading[] = array("data"=>"Code", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //           $heading[] = array("data"=>"Outlet Name", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //           $heading[] = array("data"=>"No","style"=>"text-align: left; ", "chalign"=>"text-align: left; width:60px");
  //           $heading[] = array("data"=>"Ref. No", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width : 80px;");
  //           $heading[] = array("data"=>"Cash", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:90px");
  //           $heading[] = array("data"=>"Cheque", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:90px");
  //           $heading[] = array("data"=>"Type", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:90px");
            
  //           $field[] = array("data"=>"code", "total"=>false, "format"=>"text");
  //           $field[]  = array("data"=>"name", "total"=>false, "format"=>"text", "limit"=>30);
  //           $field[]  = array("data"=>"no", "total"=>false, "format"=>"text");
  //           $field[]  = array("data"=>"ref", "total"=>false, "format"=>"text");
  //           $field[]  = array("data"=>"cash", "total"=>false, "format"=>"amount");
  //           $field[]  = array("data"=>"cheque", "total"=>false, "format"=>"amount");
  //           $field[]  = array("data"=>"type", "total"=>false, "format"=>"text");
            
  //           $page_rec = 28;
            
  //           $header  = array("data"=>$this->useclass->r_header("CASH COLLECTION","As at:".$this->sd['from'].""), "style"=>"");
  //           $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
  //           $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
  //           $data = array(
  //                             "dbtem"=>$this->useclass->report_style(),
  //                             "data"=>$res,
  //                             "field"=>$field,
  //                             "heading"=>$heading,
  //                             "page_rec"=>$page_rec,
  //                             "height"=>$this->h,
  //                             "width"=>$this->w,
  //                             "header"=>37,
  //                             "header_txt"=>$header,
  //                             "footer_txt"=>$footer,
  //                             "page_no"=>$page_no,
  //                             "header_of"=>true
  //                             );
  //           //print_r($data); exit;
  //           $this->load->library('greport', $data);
            
  //           $resu = $this->greport->_print();
  //       }else{
  //           $resu = "<span style='color:red;'>There is no data to load.</span>";
  //       }
        
  //       return $resu;
  //   }
}
?>