<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reg extends CI_Model {
    
    private $sd;
    private $tb_reg;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	if(isset($this->sd['db'])){
	    $this->load->database($this->sd['db'], true);
	}
	
	$this->tb_reg = $this->tables->tb['s_reg'];
    }
}