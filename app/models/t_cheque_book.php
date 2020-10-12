<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_cheque_book extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	
	$this->tb_sum = $this->tables->tb['t_cheque_book_sum'];
	$this->tb_det = $this->tables->tb['t_cheque_book_det'];
	$this->tb_acc = $this->tables->tb['m_accounts'];
    }
    
    public function base_details(){
	$a['accounts'] = $this->get_accounts();
	$a['max_no'] = $this->max_no();
	
	return $a;
    }
    
    private function get_accounts($type = "code", $style=""){
	$this->load->database('account', true);
	$this->db->where('is_Bank_acc', 1);
	$query = $this->db->get($this->tb_acc);
        
        $s = "<select name='bank_account' id='bank_account' style='".$style."'>";
        $s .= "<option value='0'>---</option>";
	if($type == 'code'){
	    foreach($query->result() as $r){
		$s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."</option>";
	    }
	}elseif($type == 'name'){
	    foreach($query->result() as $r){
		$s .= "<option title='".$r->description."' value='".$r->code."'>".$r->description."</option>";
	    }
	}
        $s .= "</select>";
        $this->load->database($this->sd['db'], true);
        return $s;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['bc']);
	$this->db->select_max("no");
	
	return $this->db->get($this->tb_sum)->first_row()->no+1;
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
	$this->db->trans_start();
	
	$a_sum = array(
	    "bank_account"=>$_POST['bank_account'],
	    "pages"=>$_POST['pages'],
	    "start_no"=>$_POST['start_no'],
	    "date"=>$_POST['date'],
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc']
	);
	
	if($_POST['hid'] == "" || $_POST['hid'] == 0){
	    $a_sum["no"] = $this->max_no();
	    $this->db->insert($this->tb_sum, $a_sum);
	    $lid = $this->db->insert_id();
	}else{
	    $this->db->where("id", $_POST['hid']);
	    $this->db->limit(1);
	    $this->db->update($this->tb_sum, $a_sum);
	    $lid = $_POST['hid']; $a_sum["no"] = $_POST['id'];
	    $this->set_delete();
	}
	
	$a_det = array();
	for($x=(int)$_POST['start_no']; $x < ($_POST['start_no'] + $_POST['pages']); $x++){
	    $a_det[] = array(
		"id"=>$lid,
		"cheque_no"=>$x
	    );
	}
	
	if(count($a_det)){ $this->db->insert_batch($this->tb_det, $a_det); }
	$this->db->trans_complete();
	
	redirect(base_url()."?action=t_cheque_book");
    }
    
    private function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
    }
    
    public function load(){
	$this->db->where('bc', $this->sd['bc']);
	$this->db->where('no', $_POST['id']);
	$this->db->limit(1);
	
	$a['sum'] = $this->db->get($this->tb_sum)->first_row();
	
	echo json_encode($a);
    }
    
    public function delete(){
	$this->db->where('is_avalable', 0);
	$this->db->where('id', $_POST['id']);
	
	if($this->db->get($this->tb_det)->num_rows > 0){
	    echo 0;
	}else{
	    $this->db->where('id', $_POST['id']);
	    $this->db->delete($this->tb_det);
	    
	    $this->db->limit('id', $_POST['id']);
	    $this->db->where('id', $_POST['id']);
	    $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']));
	    echo 1;
	}
    }
}