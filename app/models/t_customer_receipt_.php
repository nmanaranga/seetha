<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_customer_receipt extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_cheque;
    private $tb_items;
    private $tb_bank;
    private $tb_cus;
    private $tb_bank_branch;
    private $tb_branch;
    private $h = 140;
    private $w = 216;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_customer_receipt_sum'];
	$this->tb_det = $this->tables->tb['t_customer_receipt_det'];
	$this->tb_trance = $this->tables->tb['t_customer_acc_trance'];
	$this->tb_cheque = $this->tables->tb['t_customer_cheques'];
	$this->tb_items = $this->tables->tb['m_items'];
	$this->tb_bank = $this->tables->tb['m_banks'];
	$this->tb_bank_branch = $this->tables->tb['m_bank_branch'];
	$this->tb_cus = $this->tables->tb['m_customer'];
	$this->tb_branch = $this->tables->tb['s_branches'];
    }
    
    public function base_details(){
	$a['max_no'] = $this->max_no();
	
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
	    "ref_no"=>$_POST['ref_no'],
	    "memo"=>$_POST['memo'],
	    "customer"=>$_POST['customer'],
	    "cheque_amount"=>$_POST['cheque'],
	    "cash_amount"=>$_POST['cash'],
	    "balance"=>$_POST['balance'],
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc']
	);
	
	$total = $_POST['cheque']+$_POST['cash'];
	
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
	
        $a_det = $a_acc = $a_cheq = $cheque_no = array();
	
        for($i=0; $i<25; $i++){
	    if($i < 10){
		if($_POST['qbh_'.$i] != "0" && $_POST['qbbh_'.$i] != "0" && (double)$_POST['q3_'.$i] > 0){
		    $a_cheq[] = array(
			"id"=>$lid,
			"module"=>"RECEIPT",
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
	    
            if($_POST['0_'.$i] != "" && $_POST['0_'.$i] != "0" && (double)$_POST['3_'.$i] > 0){
		$a_det[] = array(
		    "id"=>$lid,
		    "purchase_no"=>$_POST['0_'.$i],
		    "total"=>$_POST['1_'.$i],
		    "balance"=>$_POST['2_'.$i],
		    "paid"=>$_POST['3_'.$i]
		);
		
		$a_acc[] = array(
		    "id"=>$lid,
		    "module"=>"RECEIPT",
		    "customer"=>$_POST['customer'],
		    "dr_trnce_code"=>'SALES',
		    "dr_trnce_no"=>$_POST['0_'.$i],
		    "cr_trnce_code"=>'RECEIPT',
		    "cr_trnce_no"=>$a["no"],
		    "cr_amount"=>$_POST['3_'.$i],
		    "bc"=>$this->sd['bc'],
		    "oc"=>$this->sd['oc'],
		    "date"=>$_POST['date']
		);
		
		$total -= $_POST['3_'.$i];
	    }
        }
	
	if($total > 0){
	    $a_acc[] = array(
		"id"=>$lid,
		"module"=>"RECEIPT",
		"customer"=>$_POST['customer'],
		"dr_trnce_code"=>'RECEIPT',
		"dr_trnce_no"=>$a["no"],
		"cr_trnce_code"=>'RECEIPT',
		"cr_trnce_no"=>$a["no"],
		"cr_amount"=>$total,
		"bc"=>$this->sd['bc'],
		"oc"=>$this->sd['oc'],
		"date"=>$_POST['date']
	    );
	}
        
	
	if(count($a_det)){$this->db->insert_batch($this->tb_det, $a_det);}
	
	if(count($a_acc)){$this->db->insert_batch($this->tb_trance, $a_acc);}
	
	if(count($a_cheq)){$this->db->insert_batch($this->tb_cheque, $a_cheq);}
	////Account Section ---------------------------------------------------------------------//
	$config = array(
	    "id" => $lid,
	    "no" => $a['no'],
	    "type" => "RECEIPT",
	    "date" => $_POST['date'],
	    "ref_no" => $_POST['ref_no']
	);
	
	$this->load->model('account');
	$this->account->set_data($config);
	
	$des = "Sale Customer : ".$_POST['customer'];
	
	$this->account->set_value($des, $_POST['cheque']+$_POST['cash'], "cr", "debtor_control");
	$this->account->set_value($des, $_POST['cash'], "dr", "cash_in_hand");
	
	if((double)$_POST['cheque'] > 0){
	    $this->account->set_value($des, $_POST['cheque'], "dr", "cheque_in_hand", join(", ", $cheque_no));
	}
	
	$this->account->send();
	
	////End---------------------------------------------------------------------------------//
	$this->db->trans_complete();
	
	redirect(base_url()."?action=t_customer_receipt&print=".$lid);
    }
    
    public function load(){
	$this->db->select(array('id', 'no', 'date', 'ref_no', 'memo', 'cheque_amount', 'cash_amount', 'balance', 'customer', 'name', 'outlet_name', 'is_cancel', 'posting'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
        $this->db->limit(1);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
	    
	    $this->db->select(array('cheque_no', 'account_no', 'cheque_amount', 'bank', 'bank_branch', 'realize_date', 'description', 'bank_name'));
	    $this->db->where("id", $a["sum"]->id);
	    $this->db->where('module', 'RECEIPT');
	    $this->db->join($this->tb_bank, $this->tb_bank.".code = ".$this->tb_cheque.".bank", "INNER");
	    $this->db->join($this->tb_bank_branch, $this->tb_bank_branch.".code = ".$this->tb_cheque.".bank_branch", "INNER");
	    $a['cheque'] = $this->db->get($this->tb_cheque)->result();
	}
        
        echo json_encode($a);
    }
    
    public function delete(){
	$a = true;
        
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'RECEIPT');
        if(! $this->db->delete($this->tb_trance)){
            $a = false;
        }
	
	$this->db->where("id", $_POST['id']);
	$this->db->where("module", "RECEIPT");
	if(! $this->db->update($this->tb_cheque, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
	$this->load->model('account');
	$this->account->delete($_POST['id'], "RECEIPT");
	
	$this->load->database("default", true);
	
        echo $a;
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'RECEIPT');
	$this->db->delete($this->tb_trance);
	
	$this->db->where("id", $_POST['hid']);
	$this->db->where("module", 'RECEIPT');
	$this->db->delete($this->tb_cheque);
	
	$this->load->model('account');
	$this->account->delete($_POST['hid'], "RECEIPT");
	
	$this->load->database("default", true);
	
    }
    
    public function print_view(){
        $this->load->library('useclass');
	$a['title'] = 'Customer Receipt';
        
        $this->db->where($this->tb_det.'.id', $_GET['id']);
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
            
            $bt = $ct = $tt = 0;
            foreach($result as $rr){
                $bt += $rr->balance;
                $ct += $rr->paid;
                $tt += $rr->total;
            }
	    
	    $this->db->select(array($this->tb_bank.'.bank_name', $this->tb_bank_branch.".description", "cheque_no", "account_no", "cheque_amount", "realize_date"));
	    $this->db->where("id", $_GET['id']);
	    $this->db->where("module", 'RECEIPT');
	    $this->db->join($this->tb_bank, $this->tb_bank.".code = ".$this->tb_cheque.".bank", "INNER");
	    $this->db->join($this->tb_bank_branch, $this->tb_bank_branch.".code = ".$this->tb_cheque.".bank_branch", "INNER");
	    $query3 = $this->db->get($this->tb_cheque);
	    
	    $page_end = "<br />";
	    if($query3->num_rows){		
		$page_end .= "<table border='0'>";
		    $page_end .= "<tr>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier;'>Realize Date,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier;'>Bank,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier;'>Branch,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier;'>Cheque No,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier;'>Account No,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier;'>Amount,</td>
		    </tr>";
		    foreach($query3->result() as $result3){
			$page_end .= "<tr>
			    <td style='font-size : 11px; font-family : Courier;'>".$result3->realize_date."&nbsp;&nbsp;&nbsp;&nbsp;</td>
			    <td style='font-size : 11px; font-family : Courier;'>".$result3->bank_name."&nbsp;&nbsp;&nbsp;&nbsp;</td>
			    <td style='font-size : 11px; font-family : Courier;'>".$result3->description."&nbsp;&nbsp;&nbsp;&nbsp;</td>
			    <td style='font-size : 11px; font-family : Courier;'>".$result3->cheque_no."&nbsp;&nbsp;&nbsp;&nbsp;</td>
			    <td style='font-size : 11px; font-family : Courier;'>".$result3->account_no."&nbsp;&nbsp;&nbsp;&nbsp;</td>
			    <td style='font-size : 11px; font-family : Courier;'>".$result3->cheque_amount."&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>";
		    }
		$page_end .= "</table>";
	    }
	    
	    $page_end .= "<div style='text-align : right; padding-top : 25px;'>...........................<br />Signature&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
            
            $r = new stdClass();
            $r->purchase_no = array("data"=>"Total", "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            $r->total = array("data"=>$tt, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            $r->balance = array("data"=>$bt, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            $r->paid = array("data"=>$ct, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            //$r->total = array("data"=>$t, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            
            array_push($result, $r);
            
            $code = array("data"=>"Purchase No", "style"=>"text-align: left;", "chalign"=>"text-align: left;");
            $name = array("data"=>"Total Amount","style"=>"text-align: right;", "chalign"=>"text-align: right;");
            $qun = array("data"=>"Current Balance", "style"=>"text-align: right;", "chalign"=>"text-align: right;");
            $cost = array("data"=>"Payment", "style"=>"text-align: right;", "chalign"=>"text-align: right;");
            //$discount = array("data"=>"Discount(%)", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            //$total = array("data"=>"Total", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
            
            $heading = array($code, $name, $qun, $cost);//, $discount, $total
            
            $code = array("data"=>"purchase_no", "total"=>false, "format"=>"text");
            $name  = array("data"=>"total", "total"=>false, "format"=>"amount");
            $qun  = array("data"=>"balance", "total"=>false, "format"=>"amount");
            $cost  = array("data"=>"paid", "total"=>false, "format"=>"amount");
            //$discount  = array("data"=>"discount", "total"=>false, "format"=>"amount");
            //$total  = array("data"=>"total", "total"=>false, "format"=>"amount");
            
            $field = array($code, $name, $qun, $cost);//, $discount, $total
            
            $page_rec = 9;
            
            $header  = array("data"=>$this->useclass->r_header("Customer Receipt : ".$result2->no." | Branch : ".$result2->name." (".$result2->bc.")<br /> Date : ".$result2->date), "style"=>"");
            $footer  = array("data"=>"<hr />Softmaster (pvt) LTD. - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
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
                        "footer"=>10,
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