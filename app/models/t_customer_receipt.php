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
    private $tb_adv_trance;
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
	$this->tb_adv_trance = $this->tables->tb['t_advance_pay_trance'];
	$this->tb_advance = $this->tables->tb['t_advance'];
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
	
	$ch = $cd = array();
	for($i=0; $i<10; $i++){
	    if($_POST['qbh_'.$i] != 0 && (double)$_POST['q3_'.$i] > 0){
		$ch[] = $_POST['q1_'.$i];
		$cd[] = $_POST['q4_'.$i];
	    }
	}
	
	if($_POST['cash']=='Amount'){$_POST['cash']=0.00;}
	
	$a = array(
	    "date"=>$_POST['date'],
	    "ref_no"=>$_POST['ref_no'],
	    "memo"=>$_POST['memo'],
	    "customer"=>$_POST['customer'],
	    "cheque_amount"=>$_POST['cheque'],
	    "cash_amount"=>$_POST['amt'],
            "advance_settlement"=>$_POST['advance'],
	    "balance"=>$_POST['balance'],
	    "cheque_no"=>join(", ", $ch),
	    "cheque_date"=>join(", ", $cd),
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc']
	);
	
	$total = $_POST['cheque']+$_POST['amt'];
	
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
	
        $advance=$a_ad=$a_det = $a_acc = $a_cheq = $cheque_no = array();
	
        for($i=0; $i<25; $i++){
	    if($i < 10){
		if($_POST['qbh_'.$i] != "0" && $_POST['qbbh_'.$i] != "0" && (double)$_POST['q3_'.$i] > 0){
		    $a_cheq[] = array(
			"id"=>$lid,
			"module"=>"RECEIPT",
			//"customer"=>$_POST['customer'],
			"cheque_no"=>$_POST['q1_'.$i],
			"account_no"=>$_POST['q2_'.$i],
			"cheque_amount"=>$_POST['q3_'.$i],
			"bank"=>$_POST['qbh_'.$i],
			"bank_branch"=>$_POST['qbbh_'.$i],
			"realize_date"=>$_POST['q4_'.$i]
			//"date"=>$_POST['date']
		    );
		    $cheque_no[] = $_POST['q1_'.$i];
		}
		
		if($_POST['p4_'.$i] != "" && $_POST['p4_'.$i] > 0){

		    $a_ad[] = array(
                        "id"=>$lid,
                        "module"=>"RECEIPT",
			"customer"=>$_POST['customer'],
			"dr_trance_code"=>'RECEIPT',
			"dr_trance_no"=>$a['no'],
			"dr_trance_amount"=>$_POST['p4_'.$i],
			"cr_trance_code"=>'ADPAY',
			"cr_trance_no"=>$_POST['p1_'.$i],
			"cr_trance_amount"=>0,
			"bc"=>$this->sd['bc'],
			"oc"=>$this->sd['oc'],
			"date"=>$_POST['date']
		    );

                    $advance[]=array(
                    "id"=>$lid,
                    "trans_code"=>'RECEIPT',
                    "receipt_no"=>$_POST['p1_'.$i],   
                    "total"=>$_POST['p2_'.$i],   
                    "balance"=>$_POST['p3_'.$i],   
                    "settle_amount"=>$_POST['p4_'.$i]       
                    );
		    }
	    }
	    
            if($_POST['0_'.$i] != "" && $_POST['0_'.$i] != "0" && (double)$_POST['3_'.$i] > 0){
		$a_det[] = array(
		    "id"=>$lid,
		    "receipt_no"=>$_POST['0_'.$i],
		    "total"=>$_POST['1_'.$i],
		    "balance"=>$_POST['2_'.$i],
		    "paid"=>$_POST['3_'.$i],
		    "trance_code"=>$_POST['4_'.$i],
		    "description"=>$_POST['5_'.$i]
                    );
		
		$a_acc[] = array(
		    "id"=>$lid,
		    "module"=>'RECEIPT',
		    "customer"=>$_POST['customer'],
		    "dr_trnce_code"=>$_POST['4_'.$i],
		    "dr_trnce_no"=>$_POST['0_'.$i],
		    "cr_trnce_code"=>'RECEIPT',
		    "cr_trnce_no"=>$a["no"],
		    "cr_amount"=>$_POST['3_'.$i],
		    "bc"=>$this->sd['bc'],
		    "oc"=>$this->sd['oc'],
		    "date"=>$_POST['date'],
		    "description"=>$_POST['5_'.$i],
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
	
	if(count($a_ad)){$this->db->insert_batch($this->tb_adv_trance, $a_ad);}
        
	if(count($advance)){$this->db->insert_batch($this->tb_advance, $advance);}
        
        
	////Account Section ---------------------------------------------------------------------//
	$config = array(
	    "id" => $lid,
	    "cid" => $this->sd['db_code'],
	    "no" => $a['no'],
	    "type" => "RECEIPT",
	    "date" => $_POST['date'],
	    "ref_no" => $_POST['ref_no']
	);
	
	$this->load->model('account');
	$this->account->set_data($config);
	
	$des = "Receipt Customer : ".$_POST['customer'];
	
	$this->account->set_value($des, $_POST['cheque']+$_POST['amt']+$_POST['advance'], "cr", "debtor_control");
        
	if((double)$_POST['amt'] > 0){ $this->account->set_value($des, $_POST['amt'], "dr", "cash_in_hand"); }
	if((double)$_POST['advance'] > 0){ $this->account->set_value($des, $_POST['advance'], "dr", "advance"); }
	if((double)$_POST['cheque'] > 0){ $this->account->set_value($des, $_POST['cheque'], "dr", "cheque_in_hand", join(", ", $cheque_no)); }
	
       
        
	if((double)$_POST['cheque'] != 0.00){
	    for($i=0; $i<10; $i++){

		if($_POST['qbh_'.$i] != '' && (double)$_POST['q3_'.$i] > 0){
		    $this->account->set_cheque('t', $_POST['qbh_'.$i], $_POST['qbbh_'.$i], $_POST['q2_'.$i], $_POST['q1_'.$i], $_POST['q4_'.$i], $_POST['q3_'.$i]);
		    $this->account->set_cheque('trance', $_POST['qbh_'.$i], $_POST['qbbh_'.$i], $_POST['q2_'.$i], $_POST['q1_'.$i], $_POST['q4_'.$i], $_POST['q3_'.$i]);
		}
	    }
	}
	
	$this->account->send();
	
	////End---------------------------------------------------------------------------------//
	$this->db->trans_complete();
	
	redirect(base_url()."?action=t_customer_receipt&print=".$lid);
    }
    
    public function load(){
	$this->db->select(array('id', 'no', 'date', 'ref_no', 'memo', 'cheque_amount', 'cash_amount', 'balance', 'customer', 'name', 'outlet_name', 'is_cancel', 'posting','advance_settlement'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
        $this->db->limit(1);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
	    
	    $this->db->select(array('cheque_no', 'account_no', 'cheque_amount', $this->tb_cheque.'.bank', 'bank_branch', 'realize_date', 'description', 'bank_name'));
	    $this->db->where("id", $a["sum"]->id);
	    $this->db->where('module', 'RECEIPT');
	    $this->db->join($this->tb_bank, $this->tb_bank.".code = ".$this->tb_cheque.".bank_branch", "INNER");
	    $this->db->join($this->tb_bank_branch, $this->tb_bank_branch.".BranchID = ".$this->tb_cheque.".bank", "INNER");
	    $a['cheque'] = $this->db->get($this->tb_cheque)->result();
	}
        
            $this->db->select(array('id','trans_code','receipt_no','total','balance','settle_amount'));
	    $this->db->where($this->tb_advance.".trans_code" ,  'RECEIPT');
	    $this->db->where($this->tb_advance.".id" ,  $a["sum"]->id);
	    $a['adv2'] = $this->db->get($this->tb_advance)->result();
        
        echo json_encode($a);
    }
    
    public function check_cheque_no(){
	$this->db->where('cheque_no', $_POST['c_no']);
	$this->db->where('account_no', $_POST['a_no']);
	$this->db->where('is_cancel', 0);
	$this->db->limit(1);
	
	echo $this->db->get($this->tb_cheque)->num_rows;	
    }
    
    public function delete(){
	$a = true;
        
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'RECEIPT');
        if(! $this->db->delete($this->tb_trance)){
            $a = false;
        }
        
	$this->db->where("id", $_POST['id']);
	$this->db->where("trans_code", "RECEIPT");
	if(! $this->db->update($this->tb_advance, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
	
        $this->db->where("id", $_POST['id']);
	$this->db->where("module", "RECEIPT");
	if(! $this->db->update($this->tb_cheque, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
        $this->db->where('id', $_POST['id']);
        $this->db->where('module', 'RECEIPT'); 
	if(!$this->db->delete($this->tb_adv_trance)){
           $a = false; 
        }
                
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
	$this->load->model('account');
	$this->account->delete($_POST['id'], "RECEIPT", $this->sd['db_code']);
	$this->load->database("tdmc", true);
	
        echo $a;
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'RECEIPT');
	$this->db->delete($this->tb_trance);
	
        $this->db->where('id', $_POST['hid']);
	$this->db->where('trans_code', 'RECEIPT');
        $this->db->delete($this->tb_advance);

        $this->db->where("id", $_POST['hid']);
	$this->db->where("module", 'RECEIPT');
	$this->db->delete($this->tb_cheque);
        
	$this->db->where("id", $_POST['hid']);
	$this->db->where("module", 'RECEIPT');
	$this->db->delete($this->tb_cheque);
	
        $this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'RECEIPT');        
	$this->db->delete($this->tb_adv_trance);
        
	$this->load->model('account');
	$this->account->delete($_POST['hid'], "RECEIPT", $this->sd['db_code']);
	
	$this->load->database($this->sd['db'], true);
	
    }
    
    public function print_view(){
        $this->load->library('useclass');
	$a['title'] = 'Customer Receipt';
        
        $this->db->where($this->tb_det.'.id', $_GET['id']);
        $query = $this->db->get($this->tb_det);
        
        if($query->num_rows){
            $this->db->select(array("no", "date", "cheque_amount", "cash_amount", "name", "outlet_name", "is_cancel"));
            $this->db->where('id', $_GET['id']);
            $this->db->limit(1);
	    $this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
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
	    
	    $this->db->select(array($this->tb_bank.'.bank_name', $this->tb_bank_branch.".Description", "cheque_no", "account_no", "cheque_amount", "realize_date"));
	    $this->db->where("id", $_GET['id']);
	    $this->db->where("module", 'RECEIPT');
	    $this->db->join($this->tb_bank, $this->tb_bank.".code = ".$this->tb_cheque.".bank", "INNER");
	    $this->db->join($this->tb_bank_branch, $this->tb_bank_branch.".Bank = ".$this->tb_cheque.".bank_branch", "INNER");
	    $query3 = $this->db->get($this->tb_cheque);
	    
	    $page_end = "<br />";
	    if($query3->num_rows){		
		$page_end .= "<table border='0'>";
		    $page_end .= "<tr>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier; border-bottom : 1px solid #000;'>Realize Date,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier; border-bottom : 1px solid #000;'>Bank,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier; border-bottom : 1px solid #000;'>Branch,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier; border-bottom : 1px solid #000;'>Cheque No,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier; border-bottom : 1px solid #000;'>Account No,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier; border-bottom : 1px solid #000;'>Amount,</td>
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
		$page_end .= "</table><div style='width: 100%; font-size : 14px; font-weight : bold; font-family : Courier; padding-top : 10px; border-top : 1px solid #000;' >
			Cash Total : ".$result2->cash_amount." | Cheque Total : ".$result2->cheque_amount."
			</div>";
	    }
	    
	    $sig = "
	    <table style='width: 100%; font-size : 12px; font-weight : normal; font-family : Courier;' >
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
            
            $r = new stdClass();
            $r->purchase_no = array("data"=>"Total", "style"=>"text-align: right; font-weight: bold; border-top: 1px solid #000; border-bottom: 2px solid #000;");
            $r->total = array("data"=>$tt, "style"=>"text-align: right; font-weight: bold; border-top: 1px solid #000; border-bottom: 2px solid #000;");
            $r->balance = array("data"=>$bt, "style"=>"text-align: right; font-weight: bold; border-top: 1px solid #000; border-bottom: 2px solid #000;");
            $r->paid = array("data"=>$ct, "style"=>"text-align: right; font-weight: bold; border-top: 1px solid #000; border-bottom: 2px solid #000;");
            //$r->total = array("data"=>$t, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            
            array_push($result, $r);
            
            $code = array("data"=>"Inv. No", "style"=>"text-align: left;", "chalign"=>"text-align: left;");
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
            
            $page_rec = 6;
	    
            $inv_info = "<table style='width : 100%; font-size : 12px;' border='0'>
		<tr>
		    <td style='width:100px;'>Receipt No</td>
		    <td>: ".$result2->no."&nbsp;&nbsp;&nbsp;&nbsp; Date.$result2->date</td>
		    <td rowspan='2' style='width:60px;' valign='top'>Customer</td>
		    <td rowspan='2' style='width:290px;' valign='top'>: ".$result2->outlet_name." (".$result2->name.")</td>
		</tr>
	    </table><br/>";
	    
            $header  = array("data"=>$this->useclass->r_header("Customer Receipt <hr />", $inv_info), "style"=>"font-weight: normal; font-family: Courier;");
            $footer  = array("data"=>"<div style='text-align: left; font-size: 12px;'>".$sig."</div><hr />Soft-Master Technologies (pvt) LTD / 0812-204130, 0773-889082/3. / ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
            $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
            $data = array(
                        "dbtem"=>$this->useclass->report_style(),
                        "data"=>$result,
                        "field"=>$field,
                        "heading"=>$heading,
                        "page_rec"=>$page_rec,
                        "height"=>$this->h,
                        "width"=>$this->w,
                        "header"=>42,
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