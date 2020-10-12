<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class r_cus_ledger_card_inv extends CI_Model {
    
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

		$sql="SELECT acc_code,s.ddate,c.name, trans_code, trans_no,SUM(dr)-SUM(cr) AS balance
				FROM t_cus_settlement s
				JOIN m_customer c ON s.acc_code=c.code
				WHERE s.ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'
					AND trans_code='5' 
				";

		if(!empty($_POST['cluster'])){
          	$sql.=" AND s.`cl` = '".$_POST['cluster']."'";
        }if(!empty($_POST['branch'])){
          	$sql.=" AND s.`bc` = '".$_POST['branch']."'";
        }if($_POST['cus_id']!=""){
        	$sql.=" AND s.`acc_code` = '".$_POST['cus_id']."'";
        }  	
        $sql.=" GROUP BY acc_code,trans_code,trans_no
				HAVING balance>0
				ORDER BY acc_code,ddate,trans_no
				";
		
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