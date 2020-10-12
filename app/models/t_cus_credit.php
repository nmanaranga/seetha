<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_cus_credit extends CI_Model {
    
    private $sd;
    private $mtb;
    private $tb_acc_trance;
    private $tb_cus;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->mtb = $this->tables->tb['t_customer_credit_note'];
	$this->tb_acc_trance = $this->tables->tb['t_customer_acc_trance'];
	$this->tb_cus = $this->tables->tb['m_customer'];
    }
    
    public function base_details(){
	$a['accounts'] = "<select></select>";
	$a['max_no'] = $this->max_no();
	
	return $a;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['bc']);
	$this->db->select_max("no");
	
	return $this->db->get($this->mtb)->first_row()->no+1;
    }
    
    public function auto_com(){
	$acc = $this->load->database($this->sd['db_acc'], true);
	
	$acc->like("description", $_GET['q']);
	$acc->or_like("code", $_GET['q']);
	$acc->limit(25);
	
	foreach($acc->get($this->mtb)->result() as $r){
	    echo $r->code."|".$r->description."\n";
	}
    }
    
    public function save(){
	$this->db->trans_start();
	
	$a = array(
	    "amount"=>$_POST['amount'],
	    "custmer"=>$_POST['customer'],
	    "date"=>$_POST['date'],
	    "cr_account"=>$_POST['cr_account'],
	    "cr_account_des"=>$_POST['cr_account_des'],
	    "dr_account"=>$_POST['dr_account'],
	    "dr_account_des"=>$_POST['dr_account_des'],
	    "description"=>$_POST['description'],
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc']
	);
	
	if($_POST['hid'] == "" || $_POST['hid'] == 0){
	    $a["no"] = $this->max_no();
	    $this->db->insert($this->mtb, $a);
	    $lid = $this->db->insert_id();
	}else{
	    $this->db->where("id", $_POST['hid']);
	    $this->db->limit(1);
	    $this->db->update($this->mtb, $a);
	    $lid = $_POST['hid']; $a['no'] = $_POST['id'];
	    $this->set_delete();
	}
	
	$a1 = array(
	    "id"=>$lid,
	    "module"=>'CREDIT_NOTE',
	    "customer"=>$_POST['customer'],
	    "dr_trnce_code"=>'CREDIT_NOTE',
	    "dr_trnce_no"=>$a['no'],
	    "cr_trnce_code"=>'CREDIT_NOTE',
	    "cr_trnce_no"=>$a['no'],
	    "cr_amount"=>$_POST['amount'],
	    "dr_amount"=>0,
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc'],
	    "date"=>$_POST['date']
	);
	
	$this->db->insert($this->tb_acc_trance, $a1);
	
	////Account Section ---------------------------------------------------------------------//
	$confg = array(
	    "id" => $lid,
	    "cid" => $this->sd['db_code'],
	    "no" => $a['no'],
	    "type" => "CREDIT_NOTE",
	    "date" => $_POST['date'],
	    "ref_no" => ''
	);
	
	$this->load->model('account');
	$this->account->set_data($confg);
	
	$des = "Customer Credit Note : ".$_POST['customer'];
	
	$this->account->set_val_cd($des, $_POST['amount'], "cr", $_POST['cr_account'], "", true);
	$this->account->set_val_cd($des, $_POST['amount'], "dr", $_POST['dr_account'], "", true);
	
	$this->account->send();
	
	////End---------------------------------------------------------------------------------//
	$this->db->trans_complete();
	
	redirect(base_url()."?action=t_cus_credit");
    }
    
    public function load(){
	$this->db->select(array('id', 'date', 'name', 'description', 'outlet_name', 'custmer', 'is_cancel', 'amount', 'cr_account', 'cr_account_des', 'dr_account', 'dr_account_des' ));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->mtb.".custmer", "INNER");
	
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->mtb)->first_row();
	
	echo json_encode($a);
    }
    
    public function delete(){
	$a = true;
	
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'CREDIT_NOTE');
	if(! $this->db->delete($this->tb_acc_trance)){
	    $a = false;
	}
        
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->mtb, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
        $this->load->model('account');
	$this->account->delete($_POST['id'], "CREDIT_NOTE");
	
	$this->load->database("default", true);
        
        echo $a;
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'CREDIT_NOTE');
	$this->db->delete($this->tb_acc_trance);
	
	$this->load->model('account');
	$this->account->delete($_POST['hid'], "CREDIT_NOTE", $this->sd['db_code']);
	
	$this->load->database($this->sd['db'], true);
    }
}