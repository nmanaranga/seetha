<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_purchase extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_po_trance;
    private $tb_acc_trance;
    private $tb_items;
    private $tb_supplier;
    private $tb_cost_log;
    private $tb_branch;
    private $h = 297;
    private $w = 210;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_purchse_sum'];
	$this->tb_det = $this->tables->tb['t_purchse_det'];
	$this->tb_trance = $this->tables->tb['t_item_movement'];
	$this->tb_po_trance = $this->tables->tb['t_purchse_order_trance'];
	$this->tb_acc_trance = $this->tables->tb['t_supplier_acc_trance'];
	$this->tb_items = $this->tables->tb['m_items'];
	$this->tb_supplier = $this->tables->tb['m_supplier'];
	$this->tb_cost_log = $this->tables->tb['t_cost_log'];
	$this->tb_branch = $this->tables->tb['s_branches'];
	
    }
    
    public function base_details(){
	$this->load->model('m_supplier');
	$this->load->model('t_purchase_order');
	$this->load->model('m_stores');
	
	$a['sup'] = $this->m_supplier->select();
	$a['max_no'] = $this->max_no();
	$a['po_no'] = $this->t_purchase_order->select();
	$a['stores'] = $this->m_stores->select();
	$a['sd'] = $this->sd;
	
	return $a;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['bc']);
	$this->db->select_max("no");
	
	return $this->db->get($this->tb_sum)->first_row()->no+1;
    }
    
    public function save(){
	$sa = array(
	    "stores"=>$_POST['stores']
	);
	
	$this->session->set_userdata($sa);
	
	$this->db->trans_start();
	
	$a = array(
	    "date"=>$_POST['date'],
	    "ref_no"=>$_POST['ref_no'],
	    "memo"=>$_POST['memo'],
	    "supplier"=>$_POST['supplier'],
	    "discount"=>$_POST['discount'],
	    "stores"=>$_POST['stores'],
	    "invoice_no"=>$_POST['invoice_no'],
	    "po_no"=>$_POST['po_no'],
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc']
	);
	
	if($_POST['hid'] == "" || $_POST['hid'] == 0){
	    $a["no"] = $this->max_no();
	    $this->db->insert($this->tb_sum, $a);
	    $lid = $this->db->insert_id();
	}else{
	    $this->db->where("id", $_POST['hid']);
	    $this->db->limit(1);
	    $this->db->update($this->tb_sum, $a);
	    $lid = $_POST['hid']; $a['no'] = $_POST['id'];
	    $this->set_delete();
	}
        
	$a_det = $a_move = array();
	$net_amount = 0;
        for($i=0; $i<25; $i++){
            if($_POST['h_'.$i] != "" && $_POST['h_'.$i] != "0"){
                $a_det[] = array(
		    "id"=>$lid,
		    "item_code"=>$_POST['h_'.$i],
		    "cost"=>$_POST['2_'.$i],
		    "quantity"=>$_POST['1_'.$i],
		    "discount"=>$_POST['3_'.$i],
		    "discount_pre"=>$_POST['4_'.$i]
		);
		
                $cost = $this->cost_cal($lid, $_POST['h_'.$i], $_POST['1_'.$i], ($_POST['2_'.$i] - $_POST['3_'.$i]));
                
                $this->db->where('code', $_POST['h_'.$i]);
                $this->db->limit(1);
                $this->db->update($this->tb_items, array("purchase_price"=>$cost));
		
                $a_move[] = array(
		    "id"=>$lid,
		    "trance_id"=>$a['no'],
		    "trance_type"=>'PUR',
		    "item_code"=>$_POST['h_'.$i],
		    "in_quantity"=>$_POST['1_'.$i],
		    "date"=>$_POST['date'],
		    "stores"=>$_POST['stores'],
		    "bc"=>$this->sd['bc'],
		    "pur_price"=>$cost,
		    "sal_price"=>$_POST['2_'.$i],
		    "ref_no"=>$_POST['ref_no']
		);
		
		$net_amount += ($_POST['2_'.$i]*$_POST['1_'.$i]) - $_POST['3_'.$i];
            }
        }
        
	$net_amount -= $_POST['discount'];
	
	if(count($a_det)){ $this->db->insert_batch($this->tb_det, $a_det); }
	
	if(count($a_move)){ $this->db->insert_batch($this->tb_trance, $a_move); }
	
	$a2 = array(
	    "id"=>$lid,
	    "module"=>'PUR',
	    "supplier"=>$_POST['supplier'],
	    "dr_trnce_code"=>"PURCHASE",
	    "dr_trnce_no"=>$a['no'],
	    "cr_trnce_code"=>"PURCHASE",
	    "cr_trnce_no"=>$a['no'],
	    "dr_amount"=>$net_amount,
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc'],
	    "date"=>$_POST['date']
	);
	
	$this->db->insert($this->tb_acc_trance, $a2);
	////Account Section ---------------------------------------------------------------------//
	
	$config = array(
	    "id" => $lid,
	    "no" => $a['no'],
	    "type" => "PURCHASE",
	    "date" => $_POST['date'],
	    "ref_no" => $_POST['ref_no']
	);
	
	$this->load->model('account');
	$this->account->set_data($config);
	
	$des = "Sale Supplier : ".$_POST['supplier'];
	
	$this->account->set_value($des, $net_amount, "cr", "creditor_control");
	$this->account->set_value($des, $net_amount, "dr", "purchase");
	
	$this->account->send();
	
	////End---------------------------------------------------------------------------------//
	$this->db->trans_complete();
	
	redirect(base_url()."?action=t_purchase&print=".$lid);
    }
    
    private function cost_cal($id, $item_id, $quantity, $cost){
        $sql = "SELECT
                    IFNULL(cost.cost, 0) AS `cost`,
                    IFNULL((SUM(`in_quantity`)-SUM(`out_quantity`)), 0) AS `quantity`
                  FROM `".$this->tb_trance."`
                    LEFT OUTER JOIN (SELECT
                                       item_code,
                                       cost
                                     FROM `".$this->tb_cost_log."`
                                     WHERE item_code = '".$item_id."'
                                     ORDER BY auto_id DESC LIMIT 1) AS cost
                      ON (cost.item_code = `".$this->tb_trance."`.item_code)
                  WHERE `".$this->tb_trance."`.item_code = '".$item_id."'";
	
        $bc = $this->db->query($sql);
        $row = $bc->num_rows;
        $bc = $bc->first_row();
        
        if($bc->quantity < 0){
            $bc->quantity = 0;
        }
        
        $tq = $bc->quantity + $quantity;
        
        $tc = ($bc->cost * $bc->quantity) + ($quantity * $cost);
        $cost2 = round(($tc/$tq), 2);
        
        $a = array(
            "id"=>$id,
            "module"=>'PUR',
            "item_code"=>$item_id,
            "cost"=>$cost2
        );
        
        $this->db->insert($this->tb_cost_log, $a);
        
        return $cost2;
    }
    
    public function cost_max_no(){
        $this->db->select_max('auto_id');
        $query = $this->db->get($this->tb_cost_log);
        $max_no = $query->first_row()->auto_id+1;
        
        $this->db->query("ALTER TABLE `".$this->tb_cost_log."` AUTO_INCREMENT=".$max_no);
    }
    
    public function load(){
	$this->db->select(array('id', 'date', 'supplier', 'name', 'full_name', 'is_cancel', 'ref_no', 'memo', 'discount', 'stores', 'invoice_no', 'posting'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
        $this->db->limit(1);
	$this->db->join($this->tb_supplier, $this->tb_supplier.".code = ".$this->tb_sum.".supplier", "INNER");
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){
	    $this->db->select(array('quantity', $this->tb_det.'.cost', $this->tb_det.'.discount', $this->tb_det.'.discount_pre', $this->tb_det.'.item_code', $this->tb_items.'.description', $this->tb_items.'.is_measure'));
	    $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
	}
        
        echo json_encode($a);
    }
    
    public function delete(){
	$a = true;
        
        $this->db->where("id", $_POST['id']);
        $this->db->where("trance_type", "PUR");
        if(! $this->db->delete($this->tb_trance)){
            $a = false;
        }
	
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'PUR');
	if(! $this->db->delete($this->tb_acc_trance)){
	    $a = false;
	}
	
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'PUR');
	if(! $this->db->delete($this->tb_cost_log)){
	    $a = false;
	}else{
	    $this->cost_max_no();
	}
        
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
	$this->load->model('account');
	$this->account->delete($_POST['id'], "PURCHASE");
	
	$this->load->database("default", true);
	
        echo $a;
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'PUR');
	$this->db->delete($this->tb_acc_trance);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'PUR');
	$this->db->delete($this->tb_cost_log);
	$this->cost_max_no();
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('trance_type', 'PUR');
	$this->db->delete($this->tb_trance);
	
	$this->load->model('account');
	$this->account->delete($_POST['hid'], "PURCHASE");
	
	$this->load->database("default", true);
    }
    
    public function print_view(){
        $this->load->library('useclass');
	$a['title'] = 'Purchase';
        
        $this->db->select(array(
            'code',
            'description',
            'quantity',
            $this->tb_det.'.cost',
            '(quantity * '.$this->tb_det.'.cost) AS total'
        ));
        $this->db->where($this->tb_det.'.id', $_GET['id']);
        $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
        $query = $this->db->get($this->tb_det);
        
        if($query->num_rows){
            $this->db->select(array("no", "bc", "date", "name", "is_cancel"));
            $this->db->where('id', $_GET['id']);
            $this->db->limit(1);
	    $this->db->join($this->tb_branch, $this->tb_branch.".code = ".$this->tb_sum.".bc", "INNER");
            $query2 = $this->db->get($this->tb_sum);
            
            $result = $query->result();
            $result2 = $query2->first_row();
	    
	    $a['cancel'] = $result2->is_cancel;
            
            $t = $ct = 0;
            foreach($result as $rr){
                $t += $rr->total;
                $ct += $rr->quantity;
            }
            
            $r = new stdClass();
            $r->code = "";
            $r->description = "";
            $r->quantity = array("data"=>$ct, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            $r->cost = array("data"=>0, "style"=>"color : #FFF; border : none;");
            $r->total = array("data"=>$t, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            
            array_push($result, $r);
            
            $code = array("data"=>"Item Code", "style"=>"text-align: left; width:80px", "chalign"=>"text-align: left;");
            $name = array("data"=>"Item Name","style"=>"text-align: left;", "chalign"=>"text-align: left;");
            $qun = array("data"=>"Quantity", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $cost = array("data"=>"Cost", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            //$discount = array("data"=>"Discount(%)", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $total = array("data"=>"Total", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
            
            $heading = array($code, $name, $qun, $cost, $total);//, $discount
            
            $code = array("data"=>"code", "total"=>false, "format"=>"text");
            $name  = array("data"=>"description", "total"=>false, "format"=>"text");
            $qun  = array("data"=>"quantity", "total"=>false, "format"=>"number");
            $cost  = array("data"=>"cost", "total"=>false, "format"=>"amount");
            $discount  = array("data"=>"discount", "total"=>false, "format"=>"amount");
            $total  = array("data"=>"total", "total"=>false, "format"=>"amount");
            
            $field = array($code, $name, $qun, $cost, $total);//, $discount
            
            $page_rec = 30;
            
            $header  = array("data"=>$this->useclass->r_header("Purchase : ".$result2->no." | Branch : ".$result2->name." (".$result2->bc.")<br /> Date : ".$result2->date), "style"=>"");
            $footer  = array("data"=>"<div style='text-align: right; font-size: 12px;'>.....................<br />Signature</div><br /><br /><br /><br /><br /><hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
            $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
            $data = array(
                        "dbtem"=>$this->useclass->report_style(),
                        "data"=>$result,
                        "field"=>$field,
                        "heading"=>$heading,
                        "page_rec"=>$page_rec,
                        "height"=>$this->h,
                        "width"=>$this->w,
                        "header"=>35,
                        "footer"=>25,
                        "header_txt"=>$header,
                        "footer_txt"=>$footer,
                        "page_no"=>$page_no,
                        "header_of"=>false
                        );
            //print_r($data); exit;
            $this->load->library('greport', $data);
                
            $a['view'] = $this->greport->_print();
        }else{
            $a['view'] = "No Purchase Order";
        }
        
        return $a;
    }
}