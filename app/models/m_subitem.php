<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_subitem extends CI_Model {
    

    
    function __construct(){
	parent::__construct();
	
	
    }
    
    public function base_details(){

	
	$a['table_data'] = $this->data_table();

	
	return $a;
    }
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 100px; cursor : pointer;", "onclick"=>"set_short(1)");
        $des = array("data"=>"Description", "style"=>"cursor : pointer;", "onclick"=>"set_short(2)");
        $dt = array("data"=>"Description", "style"=>"width: 150px;");
        $action = array("data"=>"Action", "style"=>"width: 100px;");
	
        $this->table->set_heading($code, $des, $action);
     /*    
        $this->db->select(array($this->tb_main_cat.'.description AS main_cat', $this->mtb.'.code', $this->mtb.'.description'));
	$this->db->join($this->tb_main_cat, $this->tb_main_cat.'.code = '.$this->mtb.'.main_cat', 'INNER');
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 108px;");
            $dt = array("data"=>$r->main_cat, "style"=>"text-align: center;  width: 158px;");
            $dis = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");
            $code = array("data"=>$r->code, "style"=>"text-align: left; width: 108px; ", "value"=>"code");
	    
            $this->table->add_row($code, $dis, $ed);
        } */
        
        return $this->table->generate();
    }
   } 