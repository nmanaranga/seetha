<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class r_cus_ledger_card extends CI_Model {
    
    private $tb_items;
    private $tb_storse;
    private $tb_department;
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
    	$a['store_list']=$this->m_stores->select3();
    	$this->load->model('m_branch');
    	$a['branch']=$this->get_branch_name();
    	return $a;
	}


	public function get_branch_name(){
		$this->db->select('name');
		$this->db->where('bc',$this->sd['branch']);
		return $this->db->get('m_branch')->row()->name;
	}


	public function PDF_report($RepTyp=""){

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
		$r_detail['from']=$_POST['from'];
		$r_detail['to']=$_POST['to'];
		$to=$_POST['to'];
		$r_detail['cus_code']=$_POST['cus_id'];
		$r_detail['cus_name']=$_POST['customer'];

		$sql="SELECT code,name,nic FROM m_customer WHERE code='".$_POST['cus_id']."'";
		$query=$this->db->query($sql);
		if($query->num_rows()>0){
			$r_detail['cus_code']=$query->first_row()->code;
			$r_detail['cus_name']=$query->first_row()->name;
			$r_detail['cus_nic']=$query->first_row()->nic;
		}else{
			$r_detail['cus_code']="";
		}

		$sql_pd="SELECT t.cheque_no,
						t.amount
		          FROM t_receipt_temp_cheque_det t
		          JOIN t_receipt_temp_cheque_sum s on s.cl=t.cl and s.bc=t.bc and s.nno=t.nno
		          WHERE realize_date <= '$to'
		          AND t.status='P' ";

          	if(!empty($_POST['cluster'])){

          		$sql_pd.=" AND t.`cl` = '".$_POST['cluster']."'";
        	}if(!empty($_POST['branch'])){
          		$sql_pd.=" AND t.`bc` = '".$_POST['branch']."'";
        	}if($_POST['cus_id']!=""){
	        	$sql_pd.=" AND s.`customer` = '".$_POST['cus_id']."'";
	        } 

        $query_pd=$this->db->query($sql_pd);
        $r_detail['pd_chq']=$query_pd->result();		


		$sql="  SELECT  t.`ddate`,
						t.`ref_no`,
						tc.description AS trans_type,
						t.`description`,
						t.`dr_amount`,
						t.`cr_amount`
				FROM t_account_trans t
				JOIN t_trans_code tc ON t.`trans_code` = tc.code
				WHERE t.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'	";

		if(!empty($_POST['cluster'])){
          	$sql.=" AND t.`cl` = '".$_POST['cluster']."'";
        }if(!empty($_POST['branch'])){
          	$sql.=" AND t.`bc` = '".$_POST['branch']."'";
        }if($_POST['cus_id']!=""){
        	$sql.=" AND t.`acc_code` = '".$_POST['cus_id']."'";
        }  	

		$r_detail['cus_det']=$this->db->query($sql)->result();	

        if($this->db->query($sql)->num_rows()>0){
        	$exTy=($RepTyp=="")?'pdf':'excel';
			$this->load->view($_POST['by'].'_'.$exTy,$r_detail);
		}else{
			echo "<script>alert('No Data');window.close();</script>";
		}

	}

	public function Excel_report(){
        $this->PDF_report("Excel");
    }

}	
?>