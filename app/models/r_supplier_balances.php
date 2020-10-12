<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_supplier_balances extends CI_Model {
    
    private $tb_items;
    private $tb_storse;
    private $tb_department;
    private $sd;
    private $w = 297;
    private $h = 210;
    
    function __construct()
    {
        parent::__construct();
        
        $this->load->database();
        $this->load->library('useclass');
        
        $this->sd = $this->session->all_userdata();
		$this->load->database($this->sd['db'], true);
	
        $this->tb_items = $this->tables->tb['m_items'];
        $this->tb_storse = $this->tables->tb['m_stores'];
    }
    
    public function base_details()
    {
    	$this->load->model('m_stores');
    	$a['store_list']=$this->m_stores->select3();
    	$this->load->model('m_branch');
    	$a['branch']=$this->get_branch_name();
    	return $a;
	}


	public function get_branch_name()
	{
		$this->db->select('name');
		$this->db->where('bc',$this->sd['branch']);
		return $this->db->get('m_branch')->row()->name;
	}


	
	public function PDF_report($RepTyp="")
	{
		$this->db->select(array('name','address','tp','fax','email'));
		$this->db->where("cl",$this->sd['cl']);
		$this->db->where("bc",$this->sd['branch']);
		$r_detail['branch']=$this->db->get('m_branch')->result();

		$this->db->select(array('code','description'));
		$this->db->where("cl",$this->sd['cl']);
		$this->db->where("bc",$this->sd['branch']);
		$this->db->where("code",$_POST['stores']);
		$r_detail['store_des']=$this->db->get('m_stores')->row()->description;

		$cl=$this->sd['cl'];
		$bc=$this->sd['branch'];

		$r_detail['store_code']=$_POST['stores'];	
		$r_detail['type']=$_POST['type'];        
		$r_detail['dd']=$_POST['dd'];
		$r_detail['qno']=$_POST['qno'];

		$r_detail['page']=$_POST['page'];
		$r_detail['header']=$_POST['header'];
		$r_detail['orientation']=$_POST['orientation'];
		$r_detail['from']=$_POST['from'];
		$r_detail['to']=$_POST['to'];
		$r_detail['supplier']=$_POST['supp'];

		$to=$_POST['to'];
		$from=$_POST['from'];
		$supplier=$_POST['supp'];
		$cluster=$_POST['cluster'];
		$branch=$_POST['branch'];


		$sql="SELECT m.`code`,
		m.`name`,
		c.`tp`,
		m.`contact_name`,
		SUM(t.`cr_amount`-t.`dr_amount`) AS balance
		FROM m_supplier m 
		LEFT JOIN (SELECT CODE,GROUP_CONCAT(tp)AS tp FROM m_supplier_contact GROUP BY CODE) c ON c.`code` = m.`code` 
		LEFT JOIN t_account_trans t ON t.`acc_code` = m.`code` 
		WHERE t.ddate <= '$to'	
		";

		if(!empty($supplier))
		{
			$sql.=" AND t.`acc_code` = '$supplier'";
		}
		if(!empty($cluster))
		{
			$sql.=" AND t.cl = '$cluster'";
		}

		if(!empty($branch))
		{
			$sql.=" AND t.bc = '$branch'";
		}

		$sql.= " GROUP BY m.`code` ";

		$r_detail['item_det']=$this->db->query($sql)->result();	

		if($this->db->query($sql)->num_rows()>0)
		{
			$exTy=($RepTyp=="")?'pdf':'excel';
			$this->load->view($_POST['by'].'_'.$exTy,$r_detail);
		}
		else
		{
			echo "<script>alert('No Data');window.close();</script>";
		}
	}


	public function Excel_report(){
		$this->PDF_report("Excel");
	}
}	
?>