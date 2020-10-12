<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_supp_debit_credit extends CI_Model {
    
    private $sd;
    private $mtb;
    private $tb_acc_trance;
    private $tb_cus;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->mtb = $this->tables->tb['t_supplier_debit_credit_note'];
	$this->tb_acc_trance = $this->tables->tb['t_supplier_acc_trance'];
	$this->tb_cus = $this->tables->tb['m_supplier'];
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
	$acc = $this->load->database("account", true);
	
	$acc->like("description", $_GET['q']);
	$acc->or_like("code", $_GET['q']);
	$acc->limit(25);
	
	foreach($acc->get($this->mtb)->result() as $r){
	    echo $r->code."|".$r->description."\n";
	}
    }
    
    public function save(){
	$a = array(
	    "type"=>$_POST['type'],
	    "amount"=>$_POST['amount'],
	    "supplier"=>$_POST['supplier'],
	    "date"=>$_POST['date'],
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
	
	if($_POST['type'] == 1){
	    $cr_amount = 0; $dr_amount = $_POST['amount'];
	    $type = "DEBIT_NOTE";
	}elseif($_POST['type'] == 2){
	    $cr_amount = $_POST['amount']; $dr_amount = 0;
	    $type = "CREDIT_NOTE";
	}
	
	$a1 = array(
	    "id"=>$lid,
	    "module"=>'D_C_NOTE',
	    "supplier"=>$_POST['supplier'],
	    "dr_trnce_code"=>$type,
	    "dr_trnce_no"=>$a['no'],
	    "cr_trnce_code"=>$type,
	    "cr_trnce_no"=>$a['no'],
	    "cr_amount"=>$cr_amount,
	    "dr_amount"=>$dr_amount,
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc'],
	    "date"=>$_POST['date']
	);
	
	$this->db->insert($this->tb_acc_trance, $a1);
	
	redirect(base_url()."?action=t_supp_debit_credit");
    }
    
    public function load(){
	$this->db->select(array('id', 'date', 'full_name AS name', 'name AS outlet_name', 'supplier', 'is_cancel', 'amount', 'type'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->mtb.".supplier", "INNER");
	
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->mtb)->first_row();
	
	echo json_encode($a);
    }
    
    public function delete(){
	$a = true;
	
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'D_C_NOTE');
	if(! $this->db->delete($this->tb_acc_trance)){
	    $a = false;
	}
        
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->mtb, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
	
        echo $a;
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'D_C_NOTE');
	$this->db->delete($this->tb_acc_trance);
    }
}