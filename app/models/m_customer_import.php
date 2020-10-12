<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_customer_import extends CI_Model {
    
    private $sd;
    private $mtb;
    private $tb_contact;
    private $tb_acc_trance;
    private $tb_sum;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->mtb = $this->tables->tb['m_customer'];
	$this->tb_contact = $this->tables->tb['m_customer_contacts'];
	$this->tb_acc_trance = $this->tables->tb['t_customer_acc_trance'];
	$this->tb_sum = $this->tables->tb['t_sales_sum'];
    }
    
    public function base_details(){
	if(isset($_GET['key'])){
	    $a['table_data'] = $this->data_table();
	}else{
	    $a['table_data'] = "";
	}
	
	return $a;
    }
    
    private function max_no(){
	$this->db->select_max("code");
	
	return (int)$this->db->get($this->mtb)->first_row()->code;
    }
    
    public function data_table(){
        $h = fopen(base64_decode($_GET['key']), "r");
	$a = array();
	while($r = fgets($h)){
	    $a[] = explode(',', $r);
	}
	
	$this->load->library('table');
        $this->load->library('useclass');
	$row = array("<input type='checkbox' id='all' name='all' />", "code"); $row = array_merge($row, $a[0]);
        unset($a[0]); $id = $this->max_no();
	
        $this->table->set_template($this->useclass->grid_style('tgrid', '2500px'));
	$this->table->set_heading($row); //unset($row[0]);
	$y = 0;
	foreach($a as $aa){
	    $r = array(); $x = 0; $aa = array_merge(array("<input type='checkbox' name='check_".$y."' title='1' />", $this->code_format(++$id)), $aa);
	    foreach($row as $rr){
		if($rr == "route"){ $rr = "root"; }
		if($x == 0){
		    $r[] = $aa[$x];
		}else{
		    $r[] = "<input type='text' class='g_input_txt' name='".trim($rr)."_".$y."' title='".$aa[$x]."' style='width : 100%;' />";
		}
		$x++;
	    }
	    $y++;
	    $this->table->add_row($r);
	}
	
	return $this->table->generate()."<input type='hidden' name='count' title='".$y."' />";
    }
    
    private function code_format($code){
	if($code < 10000 && $code >= 1000){
	    return $code;
	}elseif($code < 1000 && $code >= 100){
	    return "0".$code;
	}elseif($code < 100 && $code >= 10){
	    return "00".$code;
	}elseif($code < 10){
	    return "000".$code;
	}
	
	//return 'Make code for 1';
    }
    
    public function save(){
	$a = $sql = $error = array();
	
	$fields = $this->db->list_fields($this->mtb);
	
	for($x=0; $x<$_POST['count']; $x++){
	    $val = true;
	    foreach($fields as $f){
		if(isset($_POST['check_'.$x]) && isset($_POST[$f.'_'.$x])){
		    if($f == "dob"){
			$a[$f] = date("Y-m-d", strtotime($_POST[$f.'_'.$x]));
		    }elseif($f == "root"){
			$root = $this->check_root(trim($_POST[$f.'_'.$x]));
			if($root != false){
			    $a[$f] = $root;
			}else{
			    $val = false;
			    $er = "Undefined route '".$_POST[$f.'_'.$x]."' in row : ".($x+1);
			}
		    }elseif($f == "code"){
			if($this->check_code_ex(trim($_POST[$f.'_'.$x]))){
			    $a[$f] = $_POST[$f.'_'.$x];
			}else{
			    $val = false;
			    $er = "Code '".$_POST[$f.'_'.$x]."' is already exist in row : ".($x+1);
			}
		    }else{
			$a[$f] = $_POST[$f.'_'.$x];
		    }
		}
	    }
	    if($val){
		if(count($a)){ $sql[] = $a; }
	    }else{
		$error[] = $er;
	    }
	    $a = array();
	}
	
	if(count($sql)){ $this->db->insert_batch($this->mtb, $sql); }
	
	redirect(base_url()."?action=m_customer_import&error=".base64_encode(join(', ', $error)));
    }
    
    private function check_root($root){
	$this->load->model('m_root');
	
	return $this->m_root->check_root($root);
    }
    
    public function check_code_ex($code){
	$this->db->where('code', $code);
	$this->db->limit(1);
	
	return $this->db->get($this->mtb)->num_rows;
    }
    
    public function check_code(){
	$this->db->where('code', $_POST['code']);
	$this->db->limit(1);
	
	echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function upload(){
	$config['upload_path'] = './imports/customers/';
	$config['allowed_types'] = 'csv';
	$config['max_size']	= '10000';
	
	$this->load->library('upload', $config);
	
	if ( ! $this->upload->do_upload()){
	    $error = $this->upload->display_errors();
	    redirect(base_url()."?action=m_customer_import&error=".base64_encode($error));
	}else{
	    $data = $this->upload->data();
	    redirect(base_url()."?action=m_customer_import&key=".base64_encode($data['full_path']));
	}
    }
}