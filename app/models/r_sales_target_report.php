<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_sales_target_report extends CI_Model {
    
    private $tb_items;
    private $tb_storse;
    private $sd;
    private $w = 297;
    private $h = 210;
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('useclass');
        
        $this->sd = $this->session->all_userdata();
		$this->load->database($this->sd['db'], true);

		 $this->tb_items = $this->tables->tb['m_items'];
        $this->tb_storse = $this->tables->tb['m_stores'];
	
    }
    
    public function base_details(){
    	$this->load->model('m_stores');
    	$this->load->model('m_branch');
    	$a['cluster']=$this->get_cluster_name();
        $a['branch']=$this->get_branch_name();
    	$a['employee']=$this->get_employee_name();
    	return $a;
	}


	public function get_cluster_name(){
		$sql="  SELECT `code`,description 
                FROM m_cluster m
                JOIN u_branch_to_user u ON u.cl = m.code
                WHERE user_id='".$this->sd['oc']."'
                GROUP BY m.code";
        $query=$this->db->query($sql);

		$s = "<select style='width:250px' name='cluster' id='cluster' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.'-'.$r->description."</option>";
	    }
        $s .= "</select>";
        
        return $s;
    }


    public function get_branch_name(){
		$this->db->select(array('bc','name'));
		$query = $this->db->get('m_branch');

		$s = "<select style='width:250px' name='branch' id='branch' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc.'-'.$r->name."</option>";
	    }
        $s .= "</select>";
        
        return $s;

    }

      public function get_employee_name(){
        $this->db->select(array('auto_no','code','name'));
        $query = $this->db->get('m_employee');

        $s = "<select style='width:250px' name='employee' id='employee' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option   value='".$r->auto_no."'>".$r->code.'-'.$r->name."</option>";
        }
        $s .= "</select>";
        
        return $s;

    }


	


    



}	
?>