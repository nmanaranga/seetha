<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class s_company extends CI_Model {
    
    private $sd;
    private $mtb;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	
	$this->mtb = $this->tables->tb['s_company'];
    }
    
    public function base_details(){
	return $this->db->get($this->mtb)->first_row();
    }
    
    public function save(){
    	$this->db->trans_begin();
		error_reporting(E_ALL); 
		function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
		    throw new Exception($errMsg); 
		}
		set_error_handler('exceptionThrower'); 
		try {
			$this->db->limit(1);
		    $this->db->update($this->mtb, $_POST);
			echo $this->db->trans_commit();
			redirect(base_url()."?action=s_company");
		}catch(Exception $e){ 
            $this->db->trans_rollback();
            echo"<script>alert('Operation fail please contact admin');history.go(-1);</script>";
        }
    }
    
    public function get_company_name(){
    	if(isset($this->sd['branch'])){
	$this->load->database('default', true);
	$this->db->select('name');
	$this->db->where("bc",$this->sd['branch']);
	$this->db->limit(1);
	$result=$this->db->get("m_branch")->first_row()->name;

	$this->db->select('description');
	$this->db->where("code",$this->sd['cl']);
	$this->db->limit(1);		
	$result2=$this->db->get('m_cluster')->first_row()->description;
	return $result2." - ".$result;
    }
}
}