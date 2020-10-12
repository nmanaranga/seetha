<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_discount_list extends CI_Model {
    
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
        // $a['title'] = "Cash Sales Report";
        
        // return $a;
    }
    
 //    public function report(){
        
 //        if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}
 //        if(! isset($this->sd['to'])){ $this->sd['to'] = date("Y-m-d");}
 //        if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
	// if(! isset($this->sd['cat'])){ $this->sd['cat'] = "cash";}
 //        if(isset($this->sd['paper'])){
 //            if($this->sd['paper'] = "l"){
 //                $this->h = 279;
 //                $this->w = 216;
 //            }
 //        }
 //        if(! isset($this->sd['type'])){ $this->sd['type'] = "sum";}
 //        if($this->sd['cat'] == 'cash'){
	//     if($this->sd['type'] == 'sum'){
	//     //echo $this->sd['type'];
 //            $sql = "SELECT
 //                        t_cash_sales_sum.no,
	// 		t_cash_sales_sum.date,
	// 		t_cash_sales_sum.ref_no,
 //                        m_customer.name AS full_name,
 //                        m_sales_ref.name,
 //                        m_stores.description,
 //                        pri.cost,
 //                        pri.dis+IFNULL(t_cash_sales_sum.`discount`,0) AS dis,
 //                        (pri.cost-(pri.dis+IFNULL(t_cash_sales_sum.`discount`,0))) AS net
 //                      FROM t_cash_sales_sum
 //                        INNER JOIN m_customer
 //                          ON (m_customer.code = t_cash_sales_sum.customer)
 //                        INNER JOIN m_stores
 //                          ON (m_stores.code = t_cash_sales_sum.stores)
 //                        INNER JOIN m_sales_ref
 //                          ON (m_sales_ref.code = t_cash_sales_sum.sales_ref)
 //                        LEFT OUTER JOIN (SELECT
 //                                           SUM(quantity*cost)        AS cost,
 //                                           id,
 //                                           SUM(discount) AS dis
 //                                         FROM t_cash_sales_det
 //                                         GROUP BY id) AS pri
 //                          ON (pri.id = t_cash_sales_sum.id)
 //                      WHERE t_cash_sales_sum.date BETWEEN '".$this->sd['from']."'
 //                          AND '".$this->sd['to']."'
 //                          AND  t_cash_sales_sum.is_cancel = 0
 //                          AND bc = '".$this->sd['bc']."'
 //                      ORDER BY t_cash_sales_sum.id";
 //        }
	// else{
	//      //echo $this->sd['type'];
 //            $sql = "SELECT
 //                        t_cash_sales_sum.no,
	// 		t_cash_sales_sum.date,
	// 		t_cash_sales_sum.discount,
	// 		t_cash_sales_sum.ref_no,
 //                        m_customer.name AS full_name,
 //                        item_code,
	// 		m_sales_ref.name,
 //                        m_items.description AS item_name,
 //                        quantity,
 //                        t_cash_sales_det.cost,
 //                        t_cash_sales_det.`discount` AS dis,
 //                        (quantity*t_cash_sales_det.cost)-t_cash_sales_det.`discount` AS net,
 //                        IF(t_cash_sales_det.foc,'Yes','No') as foc
 //                      FROM t_cash_sales_det
 //                        INNER JOIN t_cash_sales_sum
 //                          ON (t_cash_sales_sum.id = t_cash_sales_det.id)
 //                        INNER JOIN m_customer
 //                          ON (m_customer.code = t_cash_sales_sum.customer)
 //                        INNER JOIN m_items
 //                          ON (m_items.code = t_cash_sales_det.item_code)
	// 		INNER JOIN m_sales_ref
 //                          ON (m_sales_ref.code = t_cash_sales_sum.sales_ref)
 //                      WHERE t_cash_sales_sum.date BETWEEN '".$this->sd['from']."'
 //                          AND '".$this->sd['to']."'
 //                          AND t_cash_sales_sum.is_cancel = 0
 //                          AND bc = '".$this->sd['bc']."'
 //                      ORDER BY t_cash_sales_sum.id";
 //        }
 //        //echo $sql;exit;
 //        $query = $this->db->query($sql);
        
 //        if($query->num_rows){
 //            if($this->sd['type'] == 'sum'){
 //                $result = $query->result();
 //                $c=$d=$t = 0;
 //                foreach($result as $rr){
 //                    $t += $rr->cost-$rr->dis;
 //                    $c += $rr->cost;
 //                    $d += $rr->dis;
                    
 //                }
                
 //                $r = new stdClass();
 //                $r->no = "";
	// 	$r->date = "";
	// 	$r->ref_no = "";
 //                $r->full_name = "";
 //                $r->name = "";
 //                $r->description = array("data"=>"Total", "style"=>"text-align: right; font-weight: bold; border: none;");
 //                $r->cost = array("data"=>$c, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
 //                $r->dis = array("data"=>$d, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
 //                $r->net = array("data"=>$t, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
                
 //                array_push($result, $r);
                
 //                $inv_no = array("data"=>"INV. No", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:80px");
	// 	$date = array("data"=>"Date", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:80px");
	// 	$rn= array("data"=>"Ref No", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:80px");
 //                $job_no = array("data"=>"Customer", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
 //                $vehi = array("data"=>"Sales Ref","style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
 //                $cus = array("data"=>"Stores", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
 //                $foc = array("data"=>"foc", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
 //                $total = array("data"=>"Gross", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
 //                $dis = array("data"=>"Dis", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
 //                $net = array("data"=>"Net", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
                
 //                $heading = array($inv_no, $date, $cus, $total,$dis,$net);
                
 //                $inv_no = array("data"=>"no", "total"=>false, "format"=>"text");
	// 	$date = array("data"=>"date", "total"=>false, "format"=>"text");
	// 	$rn = array("data"=>"ref_no", "total"=>false, "format"=>"text");
 //                $job_no  = array("data"=>"full_name", "total"=>false, "format"=>"text");
 //                $vehi  = array("data"=>"name", "total"=>false, "format"=>"text");
 //                $cus  = array("data"=>"description", "total"=>false, "format"=>"text");
 //                $foc  = array("data"=>"foc", "total"=>false, "format"=>"text");
 //                $total  = array("data"=>"cost", "total"=>false, "format"=>"amount");
 //                $dis  = array("data"=>"dis", "total"=>false, "format"=>"amount");
 //                $net  = array("data"=>"net", "total"=>false, "format"=>"amount");
                
 //                $field = array($inv_no,$date, $cus, $total,$dis,$net);
                
 //                $page_rec = 30;
                
 //                $header  = array("data"=>$this->useclass->r_header("Cash Sales Discount List Summary Report - ".$this->sd['from']." To ".$this->sd['to']), "style"=>"");
 //                $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
 //                $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
                
 //                $data = array(
 //                              "dbtem"=>$this->useclass->report_style(),
 //                              "data"=>$result,
 //                              "field"=>$field,
 //                              "heading"=>$heading,
 //                              "page_rec"=>$page_rec,
 //                              "height"=>$this->h,
 //                              "width"=>$this->w,
 //                              "header"=>30,
 //                              "header_txt"=>$header,
 //                              "footer_txt"=>$footer,
 //                              "page_no"=>$page_no,
 //                              "header_of"=>false
 //                              );
 //                //print_r($data); exit;
 //                $this->load->library('greport', $data);
                
 //                $resu = $this->greport->_print();
 //            }else{
 //                $result = $query->result();
 //                $d_t=$d_st=$t = $st = $inv = 0; $res = array();
 //                foreach($result as $rr){
 //                    if($inv == 0 || $inv != $rr->no){
 //                        if($inv != 0){
 //                            $r = new stdClass();
 //                            $r->code = "";
	// 		    $r->date = "";
	// 		    $r->ref_no = "";
 //                            $r->name = "";
 //                            $r->foc = "";
 //                            $r->quantity = "";
 //                            $r->price = "";
 //                            $r->total = "";
 //                            $r->dis = "";
 //                            $r->net = array("data"=>number_format($st, 2, '.', ','), "style"=>"border-bottom: 1px solid #000; font-weight: bold; text-align:right;");
                            
 //                            $res[] = $r;
 //                            $st = 0;
 //                            $d_st = 0;
 //                        }
                        
 //                        $inv = $rr->no;                      
 //                        $r = new stdClass();
 //                        $r->code = "";
	// 		$r->date = array("data"=>"INV No : ".$rr->no,
 //                                         "style"=>"font-weight: bold; border-bottom: 1px solid #000; ");
	// 		$r->ref_no = "";
 //                        $r->name = array("data"=>"Name : ".$rr->full_name,
 //                                         "style"=>"font-weight: bold; border-bottom: 1px solid #000; ");
 //                        $r->foc = "";
 //                        $r->quantity = "";
 //                        $r->price = "";
 //                        $r->total = "";
 //                        $r->dis =array("data"=>"".number_format($rr->discount, 2, '.', ','),
 //                                         "style"=>"font-weight: bold;text-align:right; ");
 //                        $r->net = "";
                        
 //                        $res[] = $r;
                        
 //                    }
 //                    $tt = ($rr->quantity*$rr->cost)-($rr->dis+$rr->discount);
 //                    $d_tt=$rr->dis;
                    
                    
 //                    $t += $tt; $st += $tt;
 //                    $d_t += $d_tt; $d_st += $d_tt;
                    
                    
 //                    $r = new stdClass();
 //                    $r->code = $rr->item_code;
	// 	    $r->date = $rr->date;
	// 	    $r->ref_no = $rr->ref_no;;
 //                    $r->name = $rr->item_name;
 //                    $r->foc = $rr->foc;
 //                    $r->quantity = number_format($rr->quantity, 0, '.', ',');
 //                    $r->price = number_format($rr->cost, 2, '.', ',');
 //                    $r->total = number_format($tt, 2, '.', ',');
 //                    $r->dis = number_format($rr->dis, 2, '.', ',');
 //                    $r->net = number_format($rr->net, 2, '.', ',');
                    
 //                    $res[] = $r;
 //                }
                
 //                $r = new stdClass();
 //                $r->code = "";
	// 	$r->date = "";
	// 	$r->ref_no ="";
 //                $r->name = "";
 //                $r->foc = "";
 //                $r->quantity = "";
 //                $r->price = "";
 //                $r->dis = array("data"=>number_format($d_st+$rr->discount, 2, '.', ','), "style"=>"border-bottom: 1px solid #000; font-weight: bold; text-align:right;");
 //                $r->net = array("data"=>number_format($st, 2, '.', ','), "style"=>"border-bottom: 1px solid #000; font-weight: bold; text-align:right;");
                
 //                $res[] = $r;
                
 //                $r = new stdClass();
 //                $r->code = "";
	// 	$r->date = "";
	// 	$r->ref_no = "";
 //                $r->name = "";
 //                $r->foc = "";
 //                $r->quantity = "";
 //                $r->price = "";
 //                $r->dis = array("data"=>number_format($d_t+$rr->discount, 2, '.', ','), "style"=>"border-bottom: 2px solid #000; font-weight: bold; text-align:right;");
 //                $r->net = array("data"=>number_format($t, 2, '.', ','), "style"=>"border-bottom: 2px solid #000; font-weight: bold; text-align:right;");
                
 //                $res[] = $r;
                
 //                $name = array("data"=>"Item_name", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
	// 	$date = array("data"=>"Date", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
	// 	$rn = array("data"=>"Ref No", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
 //                $code = array("data"=>"Item_code", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
 //                $foc = array("data"=>"foc","style"=>"text-align: right; ", "chalign"=>"text-align: right; width:80px");
 //                $qun = array("data"=>"Quantity","style"=>"text-align: right; ", "chalign"=>"text-align: right; width:80px");
 //                $pri = array("data"=>"Price", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:80px");
 //                $dis = array("data"=>"Dis", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:80px");
 //                $total = array("data"=>"Net", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
                
 //                $heading = array($code,$name, $date, $foc,$qun, $pri,$dis, $total);
                
 //                $name = array("data"=>"name", "total"=>false, "format"=>"text");
	// 	$date = array("data"=>"date", "total"=>false, "format"=>"text");
	// 	$rn = array("data"=>"ref_no", "total"=>false, "format"=>"text");
 //                $code  = array("data"=>"code", "total"=>false, "format"=>"text");
 //                $foc  = array("data"=>"foc", "total"=>false, "format"=>"text");
 //                $qun  = array("data"=>"quantity", "total"=>false, "format"=>"text");
 //                $pri  = array("data"=>"price", "total"=>false, "format"=>"text");
 //                $dis  = array("data"=>"dis", "total"=>false, "format"=>"text");
 //                $total  = array("data"=>"net", "total"=>false, "format"=>"text");
                
 //                $field = array($code,$name, $date, $foc,$qun, $pri,$dis, $total);
                
 //                $page_rec = 28;
                
 //                $header  = array("data"=>$this->useclass->r_header("Cash Sales Discount List Detail Report - ".$this->sd['from']." To ".$this->sd['to']), "style"=>"");
 //                $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
 //                $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
                
 //                $data = array(
 //                              "dbtem"=>$this->useclass->report_style(),
 //                              "data"=>$res,
 //                              "field"=>$field,
 //                              "heading"=>$heading,
 //                              "page_rec"=>$page_rec,
 //                              "height"=>$this->h,
 //                              "width"=>$this->w,
 //                              "header"=>40,
 //                              "header_txt"=>$header,
 //                              "footer_txt"=>$footer,
 //                              "page_no"=>$page_no,
 //                              "header_of"=>false
 //                              );
 //                //print_r($data); exit;
 //                $this->load->library('greport', $data);
                
 //                $resu = $this->greport->_print();
 //            }
 //        }else{
 //            $resu = "<span style='color:red;'>There is no data to load.</span>";
 //        }
        
 //        return $resu;
 //    }
 //    else{
	// /********** Credit Salse Report**********************/
	// if($this->sd['type'] == 'sum'){
 //            $sql = "SELECT
 //                        t_sales_sum.no,
	// 		t_sales_sum.date,
	// 		t_sales_sum.ref_no,
 //                        m_customer.name AS full_name,
 //                        m_sales_ref.name,
 //                        m_stores.description,
 //                        pri.cost,
 //                        pri.dis+IFNULL(t_sales_sum.`discount`,0) AS dis,
 //                        (pri.cost-(pri.dis+IFNULL(t_sales_sum.`discount`,0))) AS net
 //                      FROM t_sales_sum
 //                        INNER JOIN m_customer
 //                          ON (m_customer.code = t_sales_sum.customer)
 //                        INNER JOIN m_stores
 //                          ON (m_stores.code = t_sales_sum.stores)
 //                        INNER JOIN m_sales_ref
 //                          ON (m_sales_ref.code = t_sales_sum.sales_ref)
 //                        LEFT OUTER JOIN (SELECT
 //                                           SUM(quantity*cost)        AS cost,
 //                                           id,
 //                                           SUM(discount) AS dis
 //                                         FROM t_sales_det
 //                                         GROUP BY id) AS pri
 //                          ON (pri.id = t_sales_sum.id)
 //                      WHERE t_sales_sum.date BETWEEN '".$this->sd['from']."'
 //                          AND '".$this->sd['to']."'
 //                          AND t_sales_sum.is_cancel = 0
 //                          AND bc = '".$this->sd['bc']."'
 //                      ORDER BY t_sales_sum.id";
		      
 //        }else{
 //            $sql = "SELECT
 //                        t_sales_sum.no,
	// 		t_sales_sum.date,
	// 		t_sales_sum.discount,
	// 		t_sales_sum.ref_no,
 //                        name AS full_name,
 //                        item_code,
 //                        m_items.description AS item_name,
 //                        quantity,
 //                        t_sales_det.cost,
 //                         t_sales_det.`discount` AS dis,
 //                        (quantity*t_sales_det.cost)-t_sales_det.`discount` AS net
 //                      FROM t_sales_det
 //                        INNER JOIN t_sales_sum
 //                          ON (t_sales_sum.id = t_sales_det.id)
 //                        INNER JOIN m_customer
 //                          ON (m_customer.code = t_sales_sum.customer)
 //                        INNER JOIN m_items
 //                          ON (m_items.code = t_sales_det.item_code)
 //                      WHERE t_sales_sum.date BETWEEN '".$this->sd['from']."'
 //                          AND '".$this->sd['to']."'
 //                          AND t_sales_sum.is_cancel = 0
 //                          AND bc = '".$this->sd['bc']."'
 //                      ORDER BY t_sales_sum.id";
 //        }
        
 //        $query = $this->db->query($sql);
        
 //        if($query->num_rows){
 //            if($this->sd['type'] == 'sum'){
 //                $result = $query->result();
 //                $t = 0;
 //                $tt = 0;
 //                foreach($result as $rr){
 //                    $t += $rr->net;
 //                    $tt += $rr->cost;
 //                }
                
 //                $r = new stdClass();
 //                $r->no = "";
	// 	$r->date = "";
	// 	$r->ref_no = "";
 //                $r->full_name = "";
 //                $r->name = "";
 //                $r->description = array("data"=>"Total", "style"=>"text-align: right; font-weight: bold; border: none;");
 //                $r->dis = "";
 //                $r->cost = "";
 //                $r->net = array("data"=>$t, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
                
 //                array_push($result, $r);
                
 //                $inv_no = array("data"=>"INV. No", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:80px");
 //                $job_no = array("data"=>"Customer", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
	// 	$date = array("data"=>"Date", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
	// 	$rn = array("data"=>"Ref No", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
 //                $vehi = array("data"=>"Sales Ref","style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
 //                $cus = array("data"=>"Stores", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
 //                $dis = array("data"=>"Dis", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
 //                $cost = array("data"=>"Cost", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
 //                $total = array("data"=>"Total", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
                
 //                $heading = array($inv_no,$date, $cus, $cost,$dis,$total);
                
 //                $inv_no = array("data"=>"no", "total"=>false, "format"=>"text");
	// 	$date  = array("data"=>"date", "total"=>false, "format"=>"text");
	// 	$rn  = array("data"=>"ref_no", "total"=>false, "format"=>"text");
 //                $job_no  = array("data"=>"full_name", "total"=>false, "format"=>"text");
 //                $vehi  = array("data"=>"name", "total"=>false, "format"=>"text");
 //                $cus  = array("data"=>"description", "total"=>false, "format"=>"text");
 //                $dis  = array("data"=>"dis", "total"=>false, "format"=>"text");
 //                $cost  = array("data"=>"cost", "total"=>false, "format"=>"text");
 //                $total  = array("data"=>"net", "total"=>false, "format"=>"amount");
                
 //                $field = array($inv_no,$date, $cus,$cost,$dis, $total);
                
 //                $page_rec = 30;
                
 //                $header  = array("data"=>$this->useclass->r_header("Credit Sales Discount List Report - ".$this->sd['from']." To ".$this->sd['to']), "style"=>"");
 //                $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
 //                $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
                
 //                $data = array(
 //                              "dbtem"=>$this->useclass->report_style(),
 //                              "data"=>$result,
 //                              "field"=>$field,
 //                              "heading"=>$heading,
 //                              "page_rec"=>$page_rec,
 //                              "height"=>$this->h,
 //                              "width"=>$this->w,
 //                              "header"=>30,
 //                              "header_txt"=>$header,
 //                              "footer_txt"=>$footer,
 //                              "page_no"=>$page_no,
 //                              "header_of"=>false
 //                              );
 //                //print_r($data); exit;
 //                $this->load->library('greport', $data);
                
 //                $resu = $this->greport->_print();
 //            }else{
 //                $result = $query->result();
 //                $d_tt=$st_d=$t_d=$tt=$d_t=$d_st=$t = $st = $inv = 0; $res = array();
 //                foreach($result as $rr){
 //                    if($inv == 0 || $inv != $rr->no){
 //                        if($inv != 0){
 //                            $r = new stdClass();
 //                            $r->code = "";
	// 		    $r->date = "";
 //                            $r->ref_no = "";
 //                            $r->name = "";
	// 		    $r->quantity = "";
 //                            $r->price = "";
 //                            $r->dis = "";
 //                            $r->total = array("data"=>number_format($st, 2, '.', ','), "style"=>"border-bottom: 1px solid #000; font-weight: bold; text-align:right;");
                            
 //                            $res[] = $r;
 //                            $st = 0;
 //                            $st_d = 0;
 //                        }
                        
 //                        $inv = $rr->no;                      
 //                        $r = new stdClass();
 //                        $r->code = "";
	// 		$r->date = array("data"=>"INV No : ".$rr->no,
 //                                         "style"=>"font-weight: bold; border-bottom: 1px solid #000; ");
 //                        $r->ref_no = "";
 //                        $r->name = array("data"=>"Name : ".$rr->full_name,
 //                                         "style"=>"font-weight: bold; border-bottom: 1px solid #000; ");
 //                        $r->quantity = "";
 //                        $r->price = "";
 //                        $r->dis =array("data"=>"".number_format($rr->discount, 2, '.', ','),
 //                                         "style"=>"font-weight: bold;text-align:right; ");
 //                        $r->total = "";
                        
 //                        $res[] = $r;
                        
 //                    }
                    
 //                    $tt = ($rr->quantity*$rr->cost)-($rr->dis+$rr->discount);
 //                    $d_tt=$rr->dis;
                    
                    
 //                    $t += $tt; $st += $tt;
 //                    $d_t += $d_tt; $d_st += $d_tt;
                    
                    
                    
 //                    $r = new stdClass();
 //                    $r->code = $rr->item_code;
	// 	    $r->date = $rr->date;
 //                    $r->ref_no = $rr->ref_no;
 //                    $r->name = $rr->item_name;
 //                    $r->quantity = number_format($rr->quantity, 0, '.', ',');
 //                    $r->price = number_format($rr->cost, 2, '.', ',');
 //                    $r->dis = number_format($rr->dis, 2, '.', ',');
 //                    $r->total = number_format($tt, 2, '.', ',');
                    
 //                    $res[] = $r;
 //                }
                
 //                $r = new stdClass();
 //                $r->code = "";
	// 	$r->date = "";
	// 	$r->ref_no = "";
 //                $r->name = "";
 //                $r->quantity = "";
 //                $r->price = "";
 //                $r->dis = array("data"=>number_format($st_d+($rr->discount), 2, '.', ','), "style"=>"border-bottom: 2px solid #000; font-weight: bold; text-align:right;");
 //                $r->total = array("data"=>number_format($st-($rr->discount), 2, '.', ','), "style"=>"border-bottom: 1px solid #000; font-weight: bold; text-align:right;");
                
 //                $res[] = $r;
                
 //                $r = new stdClass();
 //                $r->code = "";
	// 	$r->date = "";
	// 	$r->ref_no = "";
 //                $r->name = "";
 //                $r->quantity = "";
 //                $r->price = "";
 //                $r->dis = array("data"=>number_format($d_tt+($rr->discount), 2, '.', ','), "style"=>"border-bottom: 2px solid #000; font-weight: bold; text-align:right;");
 //                $r->total = array("data"=>number_format($t-($rr->discount), 2, '.', ','), "style"=>"border-bottom: 2px solid #000; font-weight: bold; text-align:right;");
                
 //                $res[] = $r;
                
 //                $name = array("data"=>"Item_name", "style"=>"text-align: left; ", "chalign"=>"text-align: left;width:220px");
 //                $code = array("data"=>"Item_code", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:80px");
	// 	$date = array("data"=>"Date", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
	// 	$rn = array("data"=>"Ref No", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
 //                $qun = array("data"=>"Quantity","style"=>"text-align: right; ", "chalign"=>"text-align: right; width:20px");
 //                $pri = array("data"=>"Price", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:80px");
 //                $dis = array("data"=>"Dis", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:80px");
 //                $total = array("data"=>"Total", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:80px");
                
 //                $heading = array($code,$name,$date,  $qun, $pri,$dis, $total);
                
 //                $name = array("data"=>"name", "total"=>false, "format"=>"text");
	// 	$date  = array("data"=>"date", "total"=>false, "format"=>"text");
	// 	$rn  = array("data"=>"ref_no", "total"=>false, "format"=>"text");
 //                $code  = array("data"=>"code", "total"=>false, "format"=>"text");
 //                $qun  = array("data"=>"quantity", "total"=>false, "format"=>"text");
 //                $pri  = array("data"=>"price", "total"=>false, "format"=>"text");
 //                $dis  = array("data"=>"dis", "total"=>false, "format"=>"text");
 //                $total  = array("data"=>"total", "total"=>false, "format"=>"text");
                
 //                $field = array($code,$name,$date,$qun, $pri,$dis, $total);
                
 //                $page_rec = 28;
                
 //                $header  = array("data"=>$this->useclass->r_header("Credit Sales Discount List Report - ".$this->sd['from']." To ".$this->sd['to']), "style"=>"");
 //                $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
 //                $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
                
 //                $data = array(
 //                              "dbtem"=>$this->useclass->report_style(),
 //                              "data"=>$res,
 //                              "field"=>$field,
 //                              "heading"=>$heading,
 //                              "page_rec"=>$page_rec,
 //                              "height"=>$this->h,
 //                              "width"=>$this->w,
 //                              "header"=>40,
 //                              "header_txt"=>$header,
 //                              "footer_txt"=>$footer,
 //                              "page_no"=>$page_no,
 //                              "header_of"=>false
 //                              );
 //                //print_r($data); exit;
 //                $this->load->library('greport', $data);
                
 //                $resu = $this->greport->_print();
 //            }
 //        }else{
 //            $resu = "<span style='color:red;'>There is no data to load.</span>";
 //        }
        
 //        return $resu;
	// /********** End of Credit Salse Report**********/
	// }
 //    }
    
}
?>