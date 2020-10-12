<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_sales_credit extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_acc_trance;
    private $tb_items;
    private $tb_cheque;
    private $tb_bank;
    private $tb_bank_branch;
    private $tb_cus;
    private $tb_branch;
    private $h = 140;
    private $w = 216;
    
    function __construct(){
	parent::__construct();
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_credit_sales_sum'];
	$this->tb_det = $this->tables->tb['t_credit_sales_det'];
	$this->tb_trance = $this->tables->tb['t_item_movement'];
	$this->tb_acc_trance = $this->tables->tb['t_customer_acc_trance'];
	$this->tb_items = $this->tables->tb['m_items'];
	$this->tb_cheque = $this->tables->tb['t_customer_cheques'];
	$this->tb_bank = $this->tables->tb['m_banks'];
	$this->tb_bank_branch = $this->tables->tb['m_bank_branch'];
	$this->tb_cus = $this->tables->tb['m_customer'];
	$this->tb_branch = $this->tables->tb['s_branches'];
    }
    
    public function base_details(){
	$this->load->model('m_customer');
	$this->load->model('m_sales_ref');
	$this->load->model('t_purchase_order');
	$this->load->model('m_stores');
	$this->load->model('m_area');
	
	$a['cus'] = $this->m_customer->select('fielter');
	$a['ref'] = $this->m_sales_ref->select();
	$a['max_no'] = $this->max_no();
	$a['po_no'] = $this->t_purchase_order->select();
	$a['stores'] = $this->m_stores->select();
	$a['area'] = $this->m_area->select('name', 'width : 300px;');
	$a['sd'] = $this->sd;
	
	return $a;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['bc']);
	$this->db->select_max("no");
	
	return $this->db->get($this->tb_sum)->first_row()->no+1;
    }
    
    public function check_cheque_no(){
	$this->db->where('cheque_no', $_POST['c_no']);
	$this->db->where('account_no', $_POST['a_no']);
	$this->db->limit(1);
	
	echo $this->db->get($this->tb_cheque)->num_rows;	
    }
    
    public function save(){
	$this->load->database("default", true);
	$this->db->trans_start();
	
	if($_POST['cash'] == "Cash Payment"){ $_POST['cash'] = 0; }
	if($_POST['credit'] == "Cheque Payment"){ $_POST['credit'] = 0; }
	if($_POST['cheque'] == "Credit"){ $_POST['cheque'] = 0; }
	
	if($_POST['pay_method'] == 1){
	    $_POST['cash'] = $_POST['net_amount'];
	    $_POST['credit'] = 0;
	}
	
	$payment = $_POST['cash'] + $_POST['cheque'];
	$a = array(
	    "date"=>$_POST['date'],
	    "ref_no"=>$_POST['ref_no'],
	    "memo"=>$_POST['memo'],
	    "customer"=>$_POST['customer'],
	    "sales_ref"=>$_POST['sales_ref'],
	    "discount"=>$_POST['discount'],
	    "balance"=>$_POST['balance'],
	    "so_no"=>$_POST['so_no'],
	    "cash"=>$_POST['cash'],
	    "credit"=>$_POST['credit'],
	    "cheque"=>$_POST['cheque'],
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc']
	);
	
	$sa = array(
	    "sales_ref"=>$_POST['sales_ref']
	);
	
	if(isset($_POST['stores'])){ $a["stores"] = $_POST['stores']; $sa["stores"] = $_POST['stores']; }
	
	$this->session->set_userdata($sa);
	
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
	
        $a1 = $a2 = $a3 = array();
	$net_amount = 0; $cheque_no = array(); $dis = $_POST['discount'];
        for($i=0; $i<25; $i++){
	    if($i < 10){
		if($_POST['qbh_'.$i] != 0 && (double)$_POST['q3_'.$i] > 0){
		    $a3[] = array(
			"id"=>$lid,
			"module"=>"CR_SALES",
			"cheque_no"=>$_POST['q1_'.$i],
			"account_no"=>$_POST['q2_'.$i],
			"cheque_amount"=>$_POST['q3_'.$i],
			"bank"=>$_POST['qbh_'.$i],
			"bank_branch"=>$_POST['qbbh_'.$i],
			"realize_date"=>$_POST['q4_'.$i]
		    );
		    
		    $cheque_no[] = $_POST['q1_'.$i];
		}
	    }
	    
            if($_POST['h_'.$i] != "" && $_POST['h_'.$i] != "0"){
		$a1[] = array(
		    "id"=>$lid,
		    "item_code"=>$_POST['h_'.$i],
		    "cost"=>$_POST['2_'.$i],
		    "quantity"=>$_POST['1_'.$i],
		    "discount"=>$_POST['3_'.$i],
		    "discount_pre"=>$_POST['4_'.$i]
		);
		
		$a2[] = array(
		    "id"=>$lid,
		    "trance_id"=>$a['no'],
		    "trance_type"=>'CR_SALES',
		    "item_code"=>$_POST['h_'.$i],
		    "out_quantity"=>$_POST['1_'.$i],
		    "date"=>$_POST['date'],
		    "bc"=>$this->sd['bc'],
		    "stores"=>$_POST['stores'],
		    "ref_no"=>$_POST['ref_no']
		);
		
		$net_amount += ($_POST['2_'.$i]*$_POST['1_'.$i]) - $_POST['3_'.$i];
		$dis += $_POST['3_'.$i];
	    }
        }
	
	$net_amount -= $_POST['discount'];
        
	if(count($a1)){ $this->db->insert_batch($this->tb_det, $a1); }
	
	if(count($a2)){ $this->db->insert_batch($this->tb_trance, $a2);	}
	
	if(count($a3)){ $this->db->insert_batch($this->tb_cheque, $a3);	}
	
	$a4 = array(
	    "id"=>$lid,
	    "module"=>'CR_SALES',
	    "customer"=>$_POST['customer'],
	    "dr_trnce_code"=>"CR_SALES",
	    "dr_trnce_no"=>$a['no'],
	    "cr_trnce_code"=>"CR_SALES",
	    "cr_trnce_no"=>$a['no'],
	    "dr_amount"=>$net_amount,
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc'],
	    "date"=>$_POST['date']
	);
	
	$this->db->insert($this->tb_acc_trance, $a4);
	
	if($payment > 0){
	    $a5 = array(
		"id"=>$lid,
		"module"=>'CR_SALES',
		"customer"=>$_POST['customer'],
		"dr_trnce_code"=>"CR_SALES",
		"dr_trnce_no"=>$a['no'],
		"cr_trnce_code"=>"CR_SALES",
		"cr_trnce_no"=>$a['no'],
		"cr_amount"=>$payment,
		"bc"=>$this->sd['bc'],
		"oc"=>$this->sd['oc'],
		"date"=>$_POST['date']
	    );
	    
	    $this->db->insert($this->tb_acc_trance, $a5);
	}
	//
	////Account Section ---------------------------------------------------------------------//
	$config = array(
	    "id" => $lid,
	    "no" => $a['no'],
	    "type" => "CR_SALES",
	    "date" => $_POST['date'],
	    "ref_no" => $_POST['ref_no']
	);
	
	$this->load->model('account');
	$this->account->set_data($config);
	
	$des = "Sale Customer : ".$_POST['customer'];
	
	$this->account->set_value($des, $net_amount, "dr", "debtor_control");
	$this->account->set_value($des, $net_amount, "cr", "sales");
	if($dis > 0){
	    $this->account->set_value($des, $dis, "dr", "discount");
	}
	
	$this->account->set_value($des, $payment, "cr", "debtor_control");
	$this->account->set_value($des, $_POST['cash'], "dr", "cash_in_hand");
	
	if((double)$_POST['cheque'] > 0){
	    $this->account->set_value($des, $_POST['cheque'], "dr", "cheque_in_hand", join(", ", $cheque_no));
	}
	
	$this->account->send();
	
	////End---------------------------------------------------------------------------------//
	
	$this->db->trans_complete();
	
	redirect(base_url()."?action=t_sales_credit&print=".$lid);
    }
    
    public function load(){
	$this->db->select(array('id', 'date', 'name', 'outlet_name', 'customer', 'sales_ref', 'is_cancel', 'cash', 'credit', 'cheque', 'ref_no', 'memo', 'discount', 'balance', 'stores', 'so_no', 'r_margin', 'c_margin', 'posting'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
	
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){
	    $this->db->select(array('quantity', $this->tb_det.'.cost', $this->tb_det.'.item_code', $this->tb_det.'.discount', $this->tb_det.'.discount_pre', $this->tb_items.'.description', $this->tb_items.'.is_measure'));
	    $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
	    
	    $this->db->select(array('cheque_no', 'account_no', 'cheque_amount', 'bank', 'bank_branch', 'realize_date', 'description', 'bank_name'));
	    $this->db->join($this->tb_bank, $this->tb_bank.".code = ".$this->tb_cheque.".bank", "INNER");
	    $this->db->join($this->tb_bank_branch, $this->tb_bank_branch.".code = ".$this->tb_cheque.".bank_branch", "INNER");
	    $this->db->where($this->tb_cheque.".id", $a["sum"]->id);
	    $this->db->where($this->tb_cheque.".module", 'SALES');
	    $a['chq'] = $this->db->get($this->tb_cheque)->result();
	    
	    $this->load->model('m_customer');
	    $a['levels'] = $this->m_customer->get_levels($a['sum']->customer);
	}
	
        echo json_encode($a);
    }
    
    public function delete(){
	$a = true;
        
        $this->db->where("id", $_POST['id']);
        $this->db->where("trance_type", "CR_SALES");
        if(! $this->db->delete($this->tb_trance)){
            $a = false;
        }
	
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'CR_SALES');
	if(! $this->db->delete($this->tb_acc_trance)){
	    $a = false;
	}
        
	$this->db->where("id", $_POST['id']);
	$this->db->where("module", "CR_SALES");
	if(! $this->db->update($this->tb_cheque, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
	$this->load->model('account');
	$this->account->delete($_POST['id'], "CR_SALES");
	
	$this->load->database("default", true);
	
        echo $a;
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'CR_SALES');
	$this->db->delete($this->tb_acc_trance);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('trance_type', 'CR_SALES');
	$this->db->delete($this->tb_trance);
	
	$this->db->where("id", $_POST['hid']);
	$this->db->where("module", 'CR_SALES');
	$this->db->delete($this->tb_cheque);
	
	$this->load->model('account');
	$this->account->delete($_POST['hid'], "CR_SALES");
	
	$this->load->database("default", true);
    }
    
    public function print_view(){
        $this->load->library('useclass');
	$a['title'] = 'Sales';
        
        $this->db->select(array(
            'code',
            'description',
            'quantity',
            $this->tb_det.'.cost',
	    'discount',
            '(quantity * '.$this->tb_det.'.cost) AS total'
        ));
        $this->db->where($this->tb_det.'.id', $_GET['id']);
        $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
        $query = $this->db->get($this->tb_det);
        
        if($query->num_rows){
            $this->db->select(array("no", "date", "name", "outlet_name", "is_cancel", "discount"));
            $this->db->where('id', $_GET['id']);
            $this->db->limit(1);
	    $this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
            $query2 = $this->db->get($this->tb_sum);
            
            $result = $query->result();
            $result2 = $query2->first_row();
	    
	    $a['cancel'] = $result2->is_cancel;
            
            $t = $ct = $dt = 0;
            foreach($result as $rr){
                $t += $rr->total;
                $ct += $rr->quantity;
		$dt += $rr->discount;
            }
	    
            $code = array("data"=>"Item Code", "style"=>"text-align: left; width:80px", "chalign"=>"text-align: left;");
            $name = array("data"=>"Item Name","style"=>"text-align: left;", "chalign"=>"text-align: left;");
            $qun = array("data"=>"Qty", "style"=>"text-align: right; width:80px ", "chalign"=>"text-align: right;");
            $cost = array("data"=>"Price", "style"=>"text-align: right; width:80px ", "chalign"=>"text-align: right;");
            $discount = array("data"=>"Dis(%)", "style"=>"text-align: right; width:80px ", "chalign"=>"text-align: right;");
            $total = array("data"=>"Total", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
            
            $heading = array($code, $name, $qun, $cost, $discount, $total);//
            
            $code = array("data"=>"code", "total"=>false, "format"=>"text");
            $name  = array("data"=>"description", "total"=>false, "format"=>"text");
            $qun  = array("data"=>"quantity", "total"=>false, "format"=>"number");
            $cost  = array("data"=>"cost", "total"=>false, "format"=>"amount");
            $discount  = array("data"=>"discount", "total"=>false, "format"=>"amount");
            $total  = array("data"=>"total", "total"=>false, "format"=>"amount");
            
            $field = array($code, $name, $qun, $cost, $discount, $total);//
            
            $page_rec = 6;
            
	    $inv_info = "<table style='width : 100%; font-size : 12px;' border='0'>
		<tr>
		    <td style='width:100px;'>Invoice No</td>
		    <td>: ".$result2->no."</td>
		    <td rowspan='2' style='width:100px;' valign='top'>Customer</td>
		    <td rowspan='2' style='width:250px;' valign='top'>: ".$result2->outlet_name." (".$result2->name.")</td>
		</tr><tr>
		    <td>Date</td>
		    <td>: ".$result2->date."</td>
		</tr>
	    </table>";
	    
	    $page_end = "<br /><table style='width : 100%; font-size : 12px; font-weight : bold; font-family : Courier;' border='0'>
		<tr>
		    <td style='text-align: right;'>Total Amount :</td>
		    <td style='width:100px; text-align: right;'>".number_format($t, 2, '.', ',')."</td>
		</tr><tr>
		    <td style='text-align: right;'>Discount :</td>
		    <td style='text-align: right;'>".number_format($result2->discount, 2, '.', ',')."</td>
		</tr><tr>
		    <td style='text-align: right;'>Item Discount :</td>
		    <td style='text-align: right;'>".number_format($dt, 2, '.', ',')."</td>
		</tr><tr>
		    <td style='text-align: right;'>Net Amount :</td>
		    <td style='text-align: right; border-top: 1px solid #000; border-bottom: 2px solid #000;'>".number_format(($t - ($result2->discount + $dt)), 2, '.', ',')."</td>
		</tr>
	    </table>";
	    
	    $sig = "<table style='width: 80%; font-size : 12px; font-weight : normal; font-family : Courier;' >
		<tr>
		    <td style='text-align : center;'>.....................</td>
		    <td style='text-align : center;'>.....................</td>
		    <td style='text-align : center;'>.....................</td>
		</tr><tr>
		    <td style='text-align : center;'>Cashier</td>
		    <td style='text-align : center;'>Manager</td>
		    <td style='text-align : center;'>Customer</td>
		</tr>
	    </table>";
	    
            $header  = array("data"=>$this->useclass->r_header("CREDIT INVOICE <hr />", $inv_info), "style"=>"font-weight: normal; font-family: Courier;");
            $footer  = array("data"=>"<div style='text-align: right; font-size: 12px;'>".$sig."</div><hr />Soft-Master Technologies (pvt) LTD / 0812-204130, 0773-889082/3. / ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
            $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
            $data = array(
                        "dbtem"=>$this->useclass->report_style(),
                        "data"=>$result,
                        "field"=>$field,
                        "heading"=>$heading,
                        "page_rec"=>$page_rec,
                        "height"=>$this->h,
                        "width"=>$this->w,
                        "header"=>45,
                        "footer"=>20,
                        "header_txt"=>$header,
                        "footer_txt"=>$footer,
                        "page_no"=>$page_no,
                        "header_of"=>false,
			"page_end"=>$page_end
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