<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_credit_note extends CI_Model {
    
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


		$cl=$this->sd['cl'];
		$bc=$this->sd['branch'];

		$r_detail['store_code']=$_POST['stores'];	
		$r_detail['type']=$_POST['type'];        
		$r_detail['dd']=$_POST['dd'];
		$r_detail['qno']=$_POST['qno'];

		$r_detail['page']=$_POST['page'];
		$r_detail['header']=$_POST['header'];
		$r_detail['orientation']=$_POST['orientation'];
		$r_detail['dfrom']=$_POST['from'];
        $r_detail['dto']=$_POST['to'];
        $r_detail['trans_code']=$_POST['t_type'];
        $r_detail['trans_code_des']=$_POST['t_type_des'];
        $r_detail['trans_no_from']=$_POST['t_range_from'];
        $r_detail['trans_no_to']=$_POST['t_range_to'];
        $cluster=$_POST['cluster'];
		$branch=$_POST['branch'];


		$sql="SELECT cn.balance,cn.nno,cn.ddate,cn.ref_no,cn.memo,cn.`code` as cus_code,cn.acc_code,cn.amount ,a1.`description`  customer,a2.`description` AS cr_account
			FROM t_credit_note cn
			INNER JOIN m_account  AS a1 ON cn.`code` = a1.`code` 
			INNER JOIN m_account  AS a2 ON cn.`acc_code` = a2.`code` 
			WHERE cn.`ddate` BETWEEN '".$_POST[from]."' AND '".$_POST[to]."' AND cn.is_cancel=0 ";
			
	
		if($_POST['acc_code']!=""){
			$sql.=" AND cn.code ='".$_POST[acc_code]."'";	
		}

		if($_POST['t_type']!="")
		{
			$sql.=" AND a.`trans_code` = $_POST[t_type]";
		}

		if($_POST['t_range_from']!="" && $_POST['t_range_to']!="")
		{
			$sql.=" AND cn.`nno` BETWEEN '$_POST[t_range_from]' AND '$_POST[t_range_to]'";
		}
		
		if(!empty($cluster))
		{
            $sql.=" AND cn.cl = '$cluster'";
        }
        if(!empty($branch))
        {
            $sql.=" AND cn.bc = '$branch'";
        }   

        if($_POST['statuss']=='2'){
			$sql.=" AND cn.balance >= cn.amount ";
		}

		if($_POST['statuss']=='3'){
			$sql.=" AND cn.balance < cn.amount";
		}
	
		$r_detail['credit_note']=$this->db->query($sql)->result();	

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