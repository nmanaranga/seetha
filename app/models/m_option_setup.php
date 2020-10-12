<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_option_setup extends CI_Model {
    
    private $sd;
    private $mtb;
    
    function __construct(){
	parent::__construct();

	$this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
	$this->mtb = $this->tables->tb['m_option_setup'];
    }
    
    public function base_details(){
	
	$a['table_data'] = $this->data_table();
	
	return $a;
    }
    
    public function data_table(){
	
	return $this->db->get($this->mtb)->result();
	

    }
    
    public function get_grid(){
	
	$this->db->where("option","Rows in a grid");
	$this->db->select("value");
	
	return $this->db->get($this->mtb)->first_row();
	 
    }
    public function get_option(){
	
	$this->db->select("autofill_payable");
	return $this->db->get($this->mtb)->first_row();
	 
    }
    
    public function get_costing_grid(){
	
	$this->db->select("grid_row");
	
	return $this->db->get($this->mtb)->first_row();
	 
    }
    
    public function save(){
	$this->db->limit(1);
	$this->db->update($this->tables->tb['m_option_setup'], $_POST);
	
	redirect(base_url()."?action=m_option_setup");
    }
}