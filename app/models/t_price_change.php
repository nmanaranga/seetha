<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_price_change extends CI_Model {
    
    private $sd;
    private $mtb;
    private $tb_items;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->mtb = $this->tables->tb['t_price_change'];
	$this->tb_items = $this->tables->tb['m_items'];
    }
    
    public function base_details(){
	$this->load->model('m_department');
	$this->load->model('m_units');
	$this->load->model('m_main_cat');
	$this->load->model('m_sub_cat');
	
	$a['main_cat'] = $this->m_main_cat->select();
	$a['sub_cat'] = $this->m_sub_cat->select();
	$a['max_no'] = $this->max_no();
	
	return $a;
    }
    
    private function max_no(){
	$this->db->select_max("id");
	
	return $this->db->get($this->mtb)->first_row()->id+1;
    }
    




    public function save(){

	$a = array(
	    "main_cat"=>$_POST['main_cat'],
	    "sub_cat"=>$_POST['sub_cat'],
	    "type"=>$_POST['type'],
	    "value"=>$_POST['value'],
	    "oc"=>$this->sd['oc'],
	    "bc"=>$this->sd['bc']
	);
	
  

	$this->db->insert($this->mtb, $a);
	
	$this->db->where('main_cat', $_POST['main_cat']);
	$this->db->where('sub_cat', $_POST['sub_cat']);
	
	foreach($this->db->get($this->tb_items)->result() as $r){
	   
	    if($_POST['type'] == 1){
			$p = $r->cost_price - ($r->cost_price * ($_POST['value']/100));
	    }else{
			$p = $r->cost_price + ($r->cost_price * ($_POST['value']/100));
	    }

	    $this->db->where('code', $r->code);
	    $this->db->update($this->tb_items, array("cost_price"=>$p));
	}
	
	redirect(base_url()."?action=t_price_change");

   }
    
    public function load(){
	
    }
}