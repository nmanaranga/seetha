<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_purchase_order extends CI_Model {
    
    private $mtb = "";
    private $sd;
    private $w = 210;
    private $h = 297;
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('useclass');
        
        $this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
    }
    
    public function base_details(){
        // $a['report'] = $this->report();
        // $a['title'] = "Sales Report";
        
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
  //       if(! isset($this->sd['type'])){ $this->sd['type'] = "sum";}
  //       if($this->sd['type'] == 'sum'){
  //           $sql = "SELECT
  //                       `no`,
  //                       `date`,
  //                       `ref_no`,
  //                       CONCAT(`name`, ' (', `full_name`, ')') AS full_name,
  //                       `cost`.`cost`
  //                     FROM t_purchse_order_sum
  //                       INNER JOIN m_supplier
  //                         ON m_supplier.code = t_purchse_order_sum.supplier
  //                       LEFT OUTER JOIN (SELECT
  //                                          SUM(cost * quantity) AS cost,
  //                                          id
  //                                        FROM t_purchse_order_det
  //                                        GROUP BY `id`) AS cost
  //                         ON (cost.id = t_purchse_order_sum.id)
  //                     WHERE t_purchse_order_sum.date BETWEEN '".$this->sd['from']."'
  //                         AND '".$this->sd['to']."'
  //                         AND t_purchse_order_sum.is_cancel = 0
  //                         AND bc = '".$this->sd['bc']."'
  //                     ORDER BY t_purchse_order_sum.id";
  //       }else{

		      
	 //    $sql="SELECT
		//     t_purchse_order_det.item_code,
		//     NO,
		//     cost,
		//     quantity,
		//     m_items.description AS item_name, 
		//     CONCAT(`name`, ' (', `full_name`, ')') AS full_name,
		//     t_purchse_order_sum.date,
		//     t_purchse_order_sum.ref_no, 
		//     m_items.re_order_level,
		//     qry_cur_stock.Current_Stock,
		//     m_items.re_order_level- (qry_cur_stock.Current_Stock)AS short 

		// FROM t_purchse_order_det 
		// INNER JOIN t_purchse_order_sum
		// ON (t_purchse_order_sum.id = t_purchse_order_det.id) 
		// LEFT JOIN qry_cur_stock
		// ON (t_purchse_order_det.item_code = qry_cur_stock.item_code)
		// INNER JOIN m_items
		// ON (m_items.code = t_purchse_order_det.item_code) 
		// INNER JOIN m_supplier
		// ON m_supplier.code = t_purchse_order_sum.supplier 
		//     WHERE t_purchse_order_sum.date BETWEEN '".$this->sd['from']."' AND '".$this->sd['to']."'
		// 	AND t_purchse_order_sum.is_cancel = 0 
		// 	AND t_purchse_order_sum.bc = '".$this->sd['bc']."' 
		// 	ORDER BY t_purchse_order_sum.id";
		      
		//       //echo $sql;exit;
  //       }
        
  //       $query = $this->db->query($sql);
        
  //       if($query->num_rows){
  //           if($this->sd['type'] == 'sum'){
  //               $result = $query->result();
  //               $t = 0;
  //               foreach($result as $rr){
  //                   $t += $rr->cost;
  //               }
                
  //               $r = new stdClass();
  //               $r->no = "";
  //               $r->full_name = "";
  //               $r->ref_no = "";
  //               $r->date = array("data"=>"Total", "style"=>"text-align: right; font-weight: bold; border: none;");
  //               $r->cost = array("data"=>$t, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
                
  //               array_push($result, $r);
                
  //               $inv_no = array("data"=>"PUR. No", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:80px");
  //               $job_no = array("data"=>"Supplier", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //               $vehi = array("data"=>"Ref. No","style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
  //               $cus = array("data"=>"Date", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
  //               $total = array("data"=>"Total", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
                
  //               $heading = array($inv_no, $job_no, $vehi, $cus, $total);
                
  //               $inv_no = array("data"=>"no", "total"=>false, "format"=>"text");
  //               $job_no  = array("data"=>"full_name", "total"=>false, "format"=>"text");
  //               $vehi  = array("data"=>"ref_no", "total"=>false, "format"=>"text");
  //               $cus  = array("data"=>"date", "total"=>false, "format"=>"text");
  //               $total  = array("data"=>"cost", "total"=>false, "format"=>"amount");
                
  //               $field = array($inv_no, $job_no, $vehi, $cus, $total);
                
  //               $page_rec = 30;
                
  //               $header  = array("data"=>$this->useclass->r_header("Purchase Order Summary Report - ".$this->sd['from']." To ".$this->sd['to']), "style"=>"");
  //               $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
  //               $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
                
  //               $data = array(
  //                             "dbtem"=>$this->useclass->report_style(),
  //                             "data"=>$result,
  //                             "field"=>$field,
  //                             "heading"=>$heading,
  //                             "page_rec"=>$page_rec,
  //                             "height"=>$this->h,
  //                             "width"=>$this->w,
  //                             "header"=>30,
  //                             "header_txt"=>$header,
  //                             "footer_txt"=>$footer,
  //                             "page_no"=>$page_no,
  //                             "header_of"=>false
  //                             );
  //               //print_r($data); exit;
  //               $this->load->library('greport', $data);
                
  //               $resu = $this->greport->_print();
  //           }else{
  //               $result = $query->result();
  //               $t = $st = $inv = 0; $res = array();
  //               foreach($result as $rr){
  //                   if($inv == 0 || $inv != $rr->NO){
  //                       if($inv != 0){
  //                           $r = new stdClass();
  //                           $r->code = "";
  //                           $r->name = "";
  //                           $r->quantity = "";
  //                           $r->price = "";
		// 	    $r->date = "";
		// 	    $r->ref_no = "";
		// 	    $r->re_order_level = "";
		// 	    $r->Current_Stock = "";
		// 	    $r->short = "";
  //                           $r->total = array("data"=>number_format($st, 2, '.', ','), "style"=>"border-bottom: 1px solid #000; font-weight: bold; text-align:right;");
                            
  //                           $res[] = $r;
  //                           $st = 0;
  //                       }
                        
  //                       $inv = $rr->NO;                      
  //                       $r = new stdClass();
  //                       $r->date = array("data"=>$rr->date,
		// 			   "style"=>"font-weight: bold; border-bottom: 1px solid #000; ");
		// 	$r->ref_no = array("data"=>$rr->ref_no,
		// 			   "style"=>"font-weight: bold; border-bottom: 1px solid #000; ");
  //                       $r->name = array("data"=>"Name : ".$rr->full_name." | PUR. No : ".$rr->NO,
  //                                        "style"=>"font-weight: bold; border-bottom: 1px solid #000; ");
  //                       $r->code = "";
  //                       $r->price = "";
  //                       $r->total = "";
		// 	$r->quantity = "";			
  //                       $r->re_order_level = "";
		// 	$r->Current_Stock = "";
		// 	$r->short = "";
  //                       $res[] = $r;
                        
  //                   }
  //                   $tt = $rr->quantity*$rr->cost;
  //                   $t += $tt; $st += $tt;
  //                   $r = new stdClass();
		//     $r->date = "";
		//     $r->ref_no = "";
  //                   $r->code = $rr->item_code;
  //                   $r->name = $rr->item_name;		    
  //                   $r->quantity = number_format($rr->quantity, 0, '.', ',');
  //                   $r->price = number_format($rr->cost, 2, '.', ',');
		//     $r->re_order_level = number_format($rr->re_order_level, 0, '.', ',');
		//     $r->Current_Stock = number_format($rr->Current_Stock, 0, '.', ',');
		//     $r->short = number_format($rr->short, 0, '.', ',');
  //                   $r->total = number_format($tt, 2, '.', ',');
                    
  //                   $res[] = $r;
  //               }
                
  //               $r = new stdClass();
  //               $r->code = "";
  //               $r->name = "";
  //               $r->quantity = "";
  //               $r->price = "";
		// $r->date = "";
		// $r->ref_no = "";
		// $r->re_order_level = "";
		// $r->Current_Stock = "";
		// $r->short = "";
  //               $r->total = array("data"=>number_format($st, 2, '.', ','), "style"=>"border-bottom: 1px solid #000; font-weight: bold; text-align:right;");
                
  //               $res[] = $r;
                
  //               $r = new stdClass();
  //               $r->code = "";
  //               $r->name = "";
  //               $r->quantity = "";
  //               $r->price = "";
		// $r->date = "";
		// $r->ref_no = "";
		// $r->re_order_level = "";
		// $r->Current_Stock = "";
		// $r->short = "";
  //               $r->total = array("data"=>number_format($t, 2, '.', ','), "style"=>"border-bottom: 2px solid #000; font-weight: bold; text-align:right;");
                
  //               $res[] = $r;
                
		// $date = array("data"=>"Date", "style"=>"text-align: left; ", "chalign"=>"text-align: left;width:50px");
		// $rno = array("data"=>"Ref No", "style"=>"text-align: left; ", "chalign"=>"text-align: left;width:50px");
  //               $name = array("data"=>"Item_name", "style"=>"text-align: left; ", "chalign"=>"text-align: left;width:600px");
  //               $code = array("data"=>"Item_code", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:80px");
  //               $qun = array("data"=>"Quantity","style"=>"text-align: right; ", "chalign"=>"text-align: right; width:60px");
  //               $pri = array("data"=>"Price", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:80px");
		// $reodr = array("data"=>"Min Stock", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:80px");
		// $cst = array("data"=>"Cur Stock", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:80px");
		// $shrt = array("data"=>"Short", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:80px");
  //               $total = array("data"=>"Total", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
                
  //               $heading = array($date,$rno,$name, $code, $qun, $pri,$reodr,$cst,$shrt, $total);
                
  //               $date = array("data"=>"date", "total"=>false, "format"=>"text");
		// $rno = array("data"=>"ref_no", "total"=>false, "format"=>"text");
		// $name = array("data"=>"name", "total"=>false, "format"=>"text");
  //               $code  = array("data"=>"code", "total"=>false, "format"=>"text");
  //               $qun  = array("data"=>"quantity", "total"=>false, "format"=>"text");
  //               $pri  = array("data"=>"price", "total"=>false, "format"=>"text");
		// $reodr  = array("data"=>"re_order_level", "total"=>false, "format"=>"text");
		// $cst  = array("data"=>"Current_Stock", "total"=>false, "format"=>"text");
		// $shrt  = array("data"=>"short", "total"=>false, "format"=>"text");
  //               $total  = array("data"=>"total", "total"=>false, "format"=>"text");
                
  //               $field = array($date,$rno,$name, $code, $qun, $pri,$reodr,$cst,$shrt,$total);
                
  //               $page_rec = 28;
                
  //               $header  = array("data"=>$this->useclass->r_header("Purchase Order Detail Report - ".$this->sd['from']." To ".$this->sd['to']), "style"=>"");
  //               $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
  //               $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
                
  //               $data = array(
  //                             "dbtem"=>$this->useclass->report_style(),
  //                             "data"=>$res,
  //                             "field"=>$field,
  //                             "heading"=>$heading,
  //                             "page_rec"=>$page_rec,
  //                             "height"=>$this->w,
  //                             "width"=>$this->h,
  //                             "header"=>30,
  //                             "header_txt"=>$header,
  //                             "footer_txt"=>$footer,
  //                             "page_no"=>$page_no,
  //                             "header_of"=>false
  //                             );
  //               //print_r($data); exit;
  //               $this->load->library('greport', $data);
                
  //               $resu = $this->greport->_print();
  //           }
  //       }else{
  //           $resu = "<span style='color:red;'>There is no data to load.</span>";
  //       }
        
  //       return $resu;
  //   }
}
?>