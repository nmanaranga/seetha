<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_customer_opening_cheque extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_items;
    private $tb_cost_log;
    private $tb_branch;
    private $tb_acc_trance;
    private $h = 297;
    private $w = 210;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_open_stock_sum'];
	$this->tb_det = $this->tables->tb['t_open_stock_det'];
	$this->tb_trance = $this->tables->tb['t_item_movement'];
	$this->tb_items = $this->tables->tb['m_items'];
	$this->tb_cost_log = $this->tables->tb['t_cost_log'];
	$this->tb_branch = $this->tables->tb['s_branches'];
	$this->tb_cheque = $this->tables->tb['t_customer_cheques'];
	$this->tb_bank = $this->tables->tb['m_banks'];
	$this->tb_bank_branch = $this->tables->tb['m_bank_branch'];
    }
    
    public function base_details(){
	$a['max_no'] = $this->max_no();
	$a['sd'] = $this->sd;
	
	return $a;
    }
    
    private function data(){
	$this->db->select(array('customer', 'cheque_no', 'account_no', 'cheque_amount', 'bank', 'bank_branch', 'realize_date', 'description as branch_name', 'bank_name'));
	$this->db->join($this->tb_bank, $this->tb_bank.'.code = '.$this->tb_cheque.'.bank', 'INNER');
	$this->db->join($this->tb_bank_branch, $this->tb_bank_branch.'.code = '.$this->tb_cheque.'.bank_branch', 'INNER');
	$this->db->where('id', 0);
	
	return $this->db->get($this->tb_cheque)->result();
    }
    
    private function max_no(){
	$this->db->where("module", "OPEN");
	$this->db->select_max("id");
	
	return $this->db->get($this->tb_cheque)->first_row()->id+1;
    }
    
    public function save(){
	$this->db->trans_start();
	
	if($_POST['hid'] != 0){
	    $this->set_delete();
	    $max_no = $_POST['hid'];
	}else{
	    $max_no = $this->max_no();
	}
	
	$config = array(
	    "id" => $max_no,
	    "cid" => $this->sd['db_code'],
	    "no" => $max_no,
	    "type" => "OPEN_CHEQU",
	    "date" => $_POST['date'],
	    "ref_no" => ""
	);
	
	$this->load->model('account');
	$this->account->set_data($config);
	
	$a = array(); 
	for($x=0; $x<25; $x++){
	    if($_POST['qbh_'.$x] != 0 && (double)$_POST['q3_'.$x] > 0){
		$a[] = array(
		    "id"=>$max_no,
		    "module"=>"OPEN",
		    "customer"=>$_POST['0_'.$x],
		    "cheque_no"=>$_POST['q1_'.$x],
		    "account_no"=>$_POST['q2_'.$x],
		    "cheque_amount"=>$_POST['q3_'.$x],
		    "bank"=>$_POST['qbh_'.$x],
		    "bank_branch"=>$_POST['qbbh_'.$x],
		    "realize_date"=>$_POST['q4_'.$x],
		    "date"=>$_POST['date']
		);
		
		$this->account->set_cheque('t', $_POST['qbh_'.$x], $_POST['qbbh_'.$x], $_POST['q2_'.$x], $_POST['q1_'.$x], $_POST['q4_'.$x], $_POST['q3_'.$x]);
		$this->account->set_cheque('trance', $_POST['qbh_'.$x], $_POST['qbbh_'.$x], $_POST['q2_'.$x], $_POST['q1_'.$x], $_POST['q4_'.$x], $_POST['q3_'.$x]);
	    }
	}
	$this->load->database($this->sd['db'], true);
        
	if(count($a)){ $this->db->insert_batch($this->tb_cheque, $a); }
	
	$this->db->trans_complete();
	
	$this->account->send();
	
	redirect(base_url()."?action=t_customer_opening_cheque");
    }
    
    public function cost_max_no(){
        $this->db->select_max('auto_id');
        $query = $this->db->get($this->tb_cost_log);
        $max_no = $query->first_row()->auto_id+1;
        
        $this->db->query("ALTER TABLE `".$this->tb_cost_log."` AUTO_INCREMENT=".$max_no);
    }
    
    public function load(){
	$this->db->select(array('id', 'date', 'customer', 'cheque_no', 'account_no', 'cheque_amount', 'bank', 'bank_branch', 'realize_date', 'description as branch_name', 'bank_name'));
	$this->db->join($this->tb_bank, $this->tb_bank.'.code = '.$this->tb_cheque.'.bank', 'INNER');
	$this->db->join($this->tb_bank_branch, $this->tb_bank_branch.'.code = '.$this->tb_cheque.'.bank_branch', 'INNER');
	$this->db->where('id', $_POST['id']);
	
	$a['sum'] = $this->db->get($this->tb_cheque)->result();
	
	echo json_encode($a);
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'OPEN');
	$this->db->delete($this->tb_cheque);
	
	$this->load->model('account');
	$this->account->delete($_POST['hid'], "OPEN_CHEQU", $this->sd['db_code']);
	
	$this->load->database($this->sd['db'], true);
    }
    
    public function delete(){
	$this->load->model('account');
	$this->account->delete($_POST['id'], "OPEN_CHEQUE", $this->sd['db_code']);
	
	$this->load->database($this->sd['db'], true);
	
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'OPEN');
	if($this->db->delete($this->tb_cheque)){
	    echo 1;
	}else{
	    echo 0;
	}
    }
    
    public function print_view(){
        $this->load->library('useclass');
	$a['title'] = 'Opening Stock';
        
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
            
            $header  = array("data"=>$this->useclass->r_header("Opening Stock : ".$result2->no." | Branch : ".$result2->name." (".$result2->bc.")<br /> Date : ".$result2->date), "style"=>"");
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
            $a['view'] = "No Record";
        }
        
        return $a;
    }
}