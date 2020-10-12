<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_supplier_opening_balance extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_items;
    private $tb_cost_log;
    private $tb_branch;
    private $tb_acc_trance;
    private $tb_cus;
    private $h = 297;
    private $w = 210;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_sup_opening_bal_sum'];
	$this->tb_det = $this->tables->tb['t_sup_opening_bal_det'];
	$this->tb_trance = $this->tables->tb['t_item_movement'];
	$this->tb_items = $this->tables->tb['m_items'];
	$this->tb_cost_log = $this->tables->tb['t_cost_log'];
	$this->tb_branch = $this->tables->tb['s_branches'];
	$this->tb_acc_trance = $this->tables->tb['t_supplier_acc_trance'];
	$this->tb_cus = $this->tables->tb['m_supplier'];
    }
    
    public function base_details(){	
	$a['max_no'] = $this->max_no();
	$a['sd'] = $this->sd;
	
	return $a;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['bc']);
	$this->db->select_max("no");
	
	return $this->db->get($this->tb_sum)->first_row()->no+1;
    }
    
    public function save(){
	$this->db->trans_start();
	
	$a = array(
	    "date"=>$_POST['date'],
	    "memo"=>$_POST['memo'],
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
	    $lid = $_POST['hid']; $a["no"] = $_POST['id'];
	    $this->set_delete();
	}
        
	$b = array();
	for($x=0; $x<25; $x++){
	    if($_POST['h_'.$x] != "0"){
		$b[] = array(
		    "id"=>$lid,
		    "module"=>'OPEN',
		    "supplier"=>$_POST['h_'.$x],
		    "dr_trnce_code"=>"OPEN",
		    "dr_trnce_no"=>$a['no'],
		    "cr_trnce_code"=>"OPEN",
		    "cr_trnce_no"=>$a['no'],
		    "cr_amount"=>$_POST['2_'.$x],
		    "dr_amount"=>$_POST['1_'.$x],
		    "bc"=>$this->sd['bc'],
		    "oc"=>$this->sd['oc'],
		    "date"=>$_POST['date']
		);
                
                $a_det[] = array(
		    "id"=>$lid,
		    "supplier"=>$_POST['h_'.$x],
		    "dr"=>$_POST['1_'.$x],
		    "cr"=>$_POST['2_'.$x]
                    );
	    }
	}
        
	if(count($a_det)){$this->db->insert_batch($this->tb_det, $a_det);}
	if(count($b)){ $this->db->insert_batch($this->tb_acc_trance, $b); }
        
        $config = array(
		"id" => $lid,
		"cid" => $this->sd['db_code'],
		"no" => $a['no'],
		"type" => "OP_SUP",
		"date" => $_POST['date'],
		"ref_no" =>'0'
	);
	    
        $this->load->model('account');
        $this->account->set_data($config);

	 $des = "Open Supplier Balance";
         
         if($_POST['dr_total']>0)
         {    
         $this->account->set_value($des, $_POST['dr_total'], "dr", "purchase");
         $this->account->set_value($des, $_POST['dr_total'], "cr", "creditor_control");
         }
         if($_POST['cr_total'])
         {
         $this->account->set_value($des, $_POST['cr_total'], "cr", "purchase");
         $this->account->set_value($des, $_POST['cr_total'], "dr", "creditor_control");    
         }
         
         $this->account->send();
      
	$this->db->trans_complete();
	
	redirect(base_url()."?action=t_supplier_opening_balance");
    }
    
    public function cost_max_no(){
        $this->db->select_max('auto_id');
        $query = $this->db->get($this->tb_cost_log);
        $max_no = $query->first_row()->auto_id+1;
        
        $this->db->query("ALTER TABLE `".$this->tb_cost_log."` AUTO_INCREMENT=".$max_no);
    }
    
    public function load(){
        
	$this->db->select(array('id', 'no', 'date','is_cancel', 'memo'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);	
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
	
        if(isset($a["sum"]->id)){
            $this->db->select(array( $this->tb_det.'.supplier', $this->tb_det.'.dr', $this->tb_det.'.cr', $this->tb_cus.'.name'));
        $this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_det.".supplier", "INNER");
        $this->db->where($this->tb_det.".id", $a["sum"]->id);
        $a['det'] = $this->db->get($this->tb_det)->result();
                }
        echo json_encode($a);
    }
    
    public function set_delete(){
        
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'OPEN');
	$this->db->delete($this->tb_acc_trance);
        
        $this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
        
        $this->load->model('account');
	$this->account->delete($_POST['id'], "OP_SUP", $this->sd['db_code']);
	$this->load->database($this->sd['db'], true);
    }
    
    public function delete(){
        
        $a = true;
        
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
	$this->db->where('cr_trnce_no', $_POST['id']);
	$this->db->where('module', 'OPEN');
	if(!$this->db->delete($this->tb_acc_trance))
        {
            $a = false;
        }
                
        $this->load->model('account');
	$this->account->delete($_POST['id'], "OP_SUP", $this->sd['db_code']);
	$this->load->database($this->sd['db'], true);  
        
        echo $a;
	
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