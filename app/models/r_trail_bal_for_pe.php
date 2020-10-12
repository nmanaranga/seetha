<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_trail_bal_for_pe extends CI_Model {

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

		if(!empty($cluster) && empty($branch))
		{
			$sql="SELECT m_account.code,
			m_account.description,
			'0' AS dr_Asat,
			'0' AS cr_Asat,
			IFNULL(t_account_trans1.Dr_Amount, 0) AS dr_range,
			IFNULL(t_account_trans1.Cr_Amount, 0) AS cr_range 
			FROM
			m_account 
		
			LEFT JOIN (SELECT Acc_Code,ddate,
			IF(SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount) > 0,
			SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount),0) AS Dr_Amount,
			IF(SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount) < 0,
			SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount) * - 1,0) AS cr_Amount,
			Description AS Description 
			FROM t_account_trans 
			WHERE cl = '$cl'
			AND ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' 
			GROUP BY Acc_Code) t_account_trans1 
			ON m_account.code = t_account_trans1.Acc_Code 
			WHERE (control_Acc = '' 
			OR control_Acc IS NULL 
			OR control_Acc = '0'
			) 
			GROUP BY m_account.code 
			ORDER BY m_account.code ASC ";

		}


		else if(!empty($cluster) && !empty($branch))
		{
			$sql="SELECT m_account.code,
			m_account.description,
			'0' AS dr_Asat,
			'0' AS cr_Asat,
			IFNULL(t_account_trans1.Dr_Amount, 0) AS dr_range,
			IFNULL(t_account_trans1.Cr_Amount, 0) AS cr_range 
			FROM
			m_account 
			LEFT JOIN (SELECT Acc_Code,ddate,
			IF(SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount) > 0,
			SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount),0) AS Dr_Amount,
			IF(SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount) < 0,
			SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount) * - 1,0) AS cr_Amount,
			Description AS Description 
			FROM t_account_trans 
			WHERE cl = '$cluster' AND bc = '$branch' 
			AND ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' 
			GROUP BY Acc_Code) t_account_trans1 
			ON m_account.code = t_account_trans1.Acc_Code 
			WHERE (control_Acc = '' 
			OR control_Acc IS NULL 
			OR control_Acc = '0'
			) 
			GROUP BY m_account.code 
			ORDER BY m_account.code ASC ";

		}


		else if(!empty($branch) && empty($cluster) )
		{
			$sql="SELECT m_account.code,
			m_account.description,
			'0' AS dr_Asat,
			'0' AS cr_Asat,
			IFNULL(t_account_trans1.Dr_Amount, 0) AS dr_range,
			IFNULL(t_account_trans1.Cr_Amount, 0) AS cr_range 
			FROM
			m_account 
		
			LEFT JOIN (SELECT Acc_Code,ddate,
			IF(SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount) > 0,
			SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount),0) AS Dr_Amount,
			IF(SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount) < 0,
			SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount) * - 1,0) AS cr_Amount,
			Description AS Description 
			FROM t_account_trans 
			WHERE bc = '$branch' 
			AND ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' 
			GROUP BY Acc_Code) t_account_trans1 
			ON m_account.code = t_account_trans1.Acc_Code 
			WHERE (control_Acc = '' 
			OR control_Acc IS NULL 
			OR control_Acc = '0'
			) 
			GROUP BY m_account.code 
			ORDER BY m_account.code ASC ";

		}

		else if(empty($cluster) && empty($branch))
		{
			$sql="SELECT m_account.code,
			m_account.description,
			'0' AS dr_Asat,
			'0' AS cr_Asat,
			IFNULL(t_account_trans1.Dr_Amount, 0) AS dr_range,
			IFNULL(t_account_trans1.Cr_Amount, 0) AS cr_range 
			FROM
			m_account 
		
			LEFT JOIN (SELECT Acc_Code,ddate,
			IF(SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount) > 0,
			SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount),0) AS Dr_Amount,
			IF(SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount) < 0,
			SUM(t_account_trans.Dr_Amount - t_account_trans.Cr_Amount) * - 1,0) AS cr_Amount,
			Description AS Description 
			FROM t_account_trans 
			WHERE ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' 
			GROUP BY Acc_Code) t_account_trans1 
			ON m_account.code = t_account_trans1.Acc_Code 
			WHERE (control_Acc = '' 
			OR control_Acc IS NULL 
			OR control_Acc = '0'
			) 
			GROUP BY m_account.code 
			ORDER BY m_account.code ASC ";

		} 

		

		$r_detail['trial_balance']=$this->db->query($sql)->result();	

		if($this->db->query($sql)->num_rows()>0)
		{
			$exTy=($RepTyp=="")?'pdf':'excel';
			$this->load->view($_POST['by'].'_'.$exTy,$r_detail);
			// $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
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