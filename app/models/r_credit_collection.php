<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_credit_collection extends CI_Model {
    
    private $tb_items;
    private $tb_move;
    private $tb_sales;
    private $sd;
    private $w = 297;
    private $h = 210;
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('useclass');
        
        $this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
        $this->tb_items = $this->tables->tb['m_items'];
        $this->tb_storse = $this->tables->tb['m_stores'];
	$this->tb_recipt = $this->tables->tb['t_customer_receipt_sum'];
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
 //                $this->h = 216;
 //                $this->w = 279;
 //            }
 //        }
        
 //        if(! isset($this->sd['stores'])){ $this->sd['stores'] = 0; }
 //        if(! isset($this->sd['item_code'])){ $this->sd['item_code'] = ""; }
        
	
	// $query = $this->db
	// 		->select(array('IF(ref_no = "Reference No", "", ref_no) AS ref_no', 'over_payment', 'no', 'cash_amount', 'IFNULL(cheque_no, 0) AS cheque_no', 'cheque_amount', 'IFNULL(cheque_date, 0) AS cheque_date', 'date', '(cash_amount + cheque_amount - over_payment) AS total'))
	// 		->where('date BETWEEN ', "'".$this->sd['from']."' AND '".$this->sd['to']."'", false)
	// 		->where('is_cancel', 0)
	// 		->where('stores', $this->sd['stores'])
	// 		->order_by('ref_no', 'ASC')
	// 		->get($this->tb_recipt);
	
 //        if($query->num_rows){
 //            $result = $query->result();
	    
	//     $t_c = $t_cheque = $t_credit = $tt = $t_dis = 0;
	//     foreach($result as $r){
	// 	$t_c += $r->cash_amount;
	// 	$t_cheque += $r->cheque_amount;
	// 	$tt += $r->total;
	//     }
	    
	//     $std = new stdClass;
	//     $std->no = array('data'=>'total', 'style'=>'font-weight : bold; text-align: right; border-top : 1px solid #000; border-bottom : 2px solid #000;');
	//     $std->cash_amount = array('data'=>$t_c, 'style'=>'font-weight : bold; text-align: right; border-top : 1px solid #000; border-bottom : 2px solid #000;');
	//     $std->cheque_amount = array('data'=>$t_cheque, 'style'=>'font-weight : bold; text-align: right; border-top : 1px solid #000; border-bottom : 2px solid #000;');
	//     $std->date = array('data'=>'', 'style'=>'font-weight : bold; text-align: right; border-top : 1px solid #000; border-bottom : 2px solid #000;');
	//     $std->cheque_no = array('data'=>'', 'style'=>'font-weight : bold; text-align: right; border-top : 1px solid #000; border-bottom : 2px solid #000;');
	//     $std->cheque_date = '';
	//     $std->ref_no = '';
	//     $std->over_payment ='';
	//     $std->total = array('data'=>$tt, 'style'=>'font-weight : bold; text-align: right; border-top : 1px solid #000; border-bottom : 2px solid #000;');
	    
	//     array_push($result, $std);
	    
	//     $heading = $field = array();
	    
 //            $heading[] = array("data"=>"DATE", "style"=>"text-align: center; width:100px", "chalign"=>"text-align: center;");
 //            $heading[] = array("data"=>"REC. No", "style"=>"text-align: left;  width:60px", "chalign"=>"text-align: left;");
 //            $heading[] = array("data"=>"REF. No", "style"=>"text-align: left;  width:80px", "chalign"=>"text-align: left;");
 //            $heading[] = array("data"=>"CASH AMO.", "style"=>"text-align: right;   width:100px", "chalign"=>"text-align: right;");
 //            $heading[] = array("data"=>"CHQ. NO", "style"=>"text-align: center;", "chalign"=>"text-align: center;");
 //            $heading[] = array("data"=>"CHQ. AMO","style"=>"text-align: right; width : 100px;", "chalign"=>"text-align: right;");
 //            $heading[] = array("data"=>"CHQ. DATE", "style"=>"text-align: center;", "chalign"=>"text-align: center;");
 //            $heading[] = array("data"=>"OVE. PAY", "style"=>"text-align: right; width : 80px;", "chalign"=>"text-align: right;");
 //            $heading[] = array("data"=>"TOTAL", "style"=>"text-align: right;  width:100px", "chalign"=>"text-align: right;");
            
 //            //$heading = array($date, $tid, $tcode, $qun, $balance);
 //            $field[] = array("data"=>"date", "total"=>false, "format"=>"text");
 //            $field[] = array("data"=>"no", "total"=>false, "format"=>"text");
 //            $field[] = array("data"=>"ref_no", "total"=>false, "format"=>"text");
 //            $field[] = array("data"=>"cash_amount", "total"=>false, "format"=>"amount");
 //            $field[] = array("data"=>"cheque_no", "total"=>false, "format"=>"text");
 //            $field[] = array("data"=>"cheque_amount", "total"=>false, "format"=>"amount");
 //            $field[] = array("data"=>"cheque_date", "total"=>false, "format"=>"text");
 //            $field[] = array("data"=>"over_payment", "total"=>false, "format"=>"text");
 //            $field[] = array("data"=>"total", "total"=>false, "format"=>"amount");
	    
 //            //$field = array($date, $tid, $tcode, $qun, $balance);
            
 //            $page_rec = 20;
            
 //            $header  = array("data"=>$this->useclass->r_header("CREDIT COLLECTION REPORT","Date From ".$this->sd['from']." To ".$this->sd['to']), "style"=>"font-size : 14px;");
 //            $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
 //            $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
 //            $data = array(
 //                                  "dbtem"=>$this->useclass->report_style(),
 //                                  "data"=>$result,
 //                                  "field"=>$field,
 //                                  "heading"=>$heading,
 //                                  "page_rec"=>$page_rec,
 //                                  "height"=>$this->h,
 //                                  "width"=>$this->w,
 //                                  "header"=>37,
 //                                  "header_txt"=>$header,
 //                                  "footer_txt"=>$footer,
 //                                  "page_no"=>$page_no,
 //                                  "header_of"=>false
 //                                  );
 //                //print_r($data); exit;
 //            $this->load->library('greport', $data);
            
 //            $resu = $this->greport->_print();
 //        }else{
 //            $resu = "<span style='color:red;'>There is no data to load.</span>";
 //        }
        
 //        return $resu;
 //    }
}
?>