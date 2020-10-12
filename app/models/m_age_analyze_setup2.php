<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_age_analyze_setup2 extends CI_Model {
    
    private $sd;
    private $mtb;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	
	$this->mtb = $this->tables->tb['m_age_analyze_2'];
    }
    
    public function base_details(){
	$a['table_data'] = $this->data_table();
	
	return $a;
    }
    
    public function data_table(){
	return $this->db->get($this->mtb)->result();
    }
    
    public function save(){
	$a = array(); $this->db->truncate($this->mtb);
	$ze = true; 
	for($x=0; $x<10; $x++){
	    if((int)$_POST['range'.$x] > 0 || $ze == true){
		if(! isset($_POST['type'.$x])){ $_POST['type'.$x] = 1; }
		$a[] = array(
		    "description"=>$_POST['description'.$x],
		    "range"=>$_POST['range'.$x],
		    "type"=>$_POST['type'.$x],
		    "oc"=>$this->sd['oc']
		);
		$ze = false;
	    }
	}
	
	if(count($a)){ $this->db->insert_batch($this->mtb, $a); }
	
	redirect(base_url()."?action=m_age_analyze_setup&key=".base64_encode("Save success."));
    }
    
    public function dates(){
	$t = time(); $a = array(); $x = 0;
	//print_r($this->db->get($this->mtb)->result());
	foreach($this->db->get($this->mtb)->result() as $r){
	    $std = new stdClass;
	    
	    if($r->type == 2){ $r->range *= 7; }
	    $tt = $t - ($r->range * 84600);
	    
	    $std->date = date("Y-m-d", $tt);
	    $std->description = $r->description;
	    $std->key = substr(md5($r->description.$t), 0, 5);
	    
	    $a[] = $std; $x++;
	}
	
	return $a;
    }
}