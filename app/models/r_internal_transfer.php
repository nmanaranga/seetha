<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_internal_transfer extends CI_Model 
{
    
    private $sd;
    private $w = 210;
    private $h = 297;
    
	private $mtb;
    private $tb_client;
    private $tb_branch;
   
    function __construct()
	{
        parent::__construct();
        
        
        $this->sd = $this->session->all_userdata();
        
		// $this->mtb = $this->tables->tb['t_lo_loan'];
		// $this->tb_client = $this->tables->tb['m_client'];
		// $this->tb_branch = $this->tables->tb['s_branches'];
    }
    
    public function base_details()
	{
        $a['cluster']=$this->get_cluster_name();
        $a['branch']=$this->get_branch_name();
        $a['d_cl']=$this->sd['cl'];
        $a['d_bc']=$this->sd['branch'];
    		
        /*
        $this->load->model('m_client_application'); 
        $this->load->model('m_loan_type');
        $a['bc'] = $this->get_branch();
		$a['loan_type'] = $this->m_loan_type->select();
		$a['client_no'] = $this-> m_client_application->select();
		*/
		return $a;
        
        
    }

    public function get_cluster_name(){
        $sql="  SELECT `code`,description 
                FROM m_cluster m
                JOIN u_branch_to_user u ON u.cl = m.code
                WHERE user_id='".$this->sd['oc']."'
                GROUP BY m.code";
        $query=$this->db->query($sql);

        $s = "<select name='cluster' id='cluster' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.'-'.$r->description."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }


    public function get_branch_name(){
        $sql="  SELECT m.`bc`,name 
                FROM m_branch m
                JOIN u_branch_to_user u ON u.bc = m.bc
                WHERE user_id='".$this->sd['oc']."'
                GROUP BY m.bc";
        $query=$this->db->query($sql);

        $s = "<select name='branch' id='branch' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc.'-'.$r->name."</option>";
        }
        $s .= "</select>";
        
        return $s;

    }

    public function get_branch_name2(){
        $sql="  SELECT m.`bc`,name 
                FROM m_branch m
                JOIN u_branch_to_user u ON u.bc = m.bc
                WHERE user_id='".$this->sd['oc']."' AND m.cl='".$_POST['cl']."'
                GROUP BY m.bc";
        $query=$this->db->query($sql);

        $s = "<select name='branch' id='branch' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc.'-'.$r->name."</option>";
        }
        $s .= "</select>";
        
        echo $s;
    }

    public function get_stores_cl(){
        $this->db->select(array('code','description'));
        $this->db->where("cl",$_POST['cl']);
        $query = $this->db->get('m_stores');

        $s = "<select name='store' id='store' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.'-'.$r->description."</option>";
        }
        $s .= "</select>";
        
        echo $s;

    }

    public function get_branch_name3(){
        $sql="  SELECT m.`bc`,name 
                FROM m_branch m
                JOIN u_branch_to_user u ON u.bc = m.bc
                WHERE user_id='".$this->sd['oc']."'
                GROUP BY m.bc";
        $query=$this->db->query($sql);

        $s = "<select name='branch' id='branch' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc.'-'.$r->name."</option>";
        }
        $s .= "</select>";
        
        echo $s;
    }

    public function get_stores_default(){
        $this->db->select(array('code','description'));
        $query = $this->db->get('m_stores');

        $s = "<select name='store' id='store' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.'-'.$r->description."</option>";
        }
        $s .= "</select>";
        
        echo $s;

    }
}
?>