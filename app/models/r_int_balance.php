<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_int_balance extends CI_Model {
    
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


	public function PDF_report(){

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

		$sql="	SELECT MIN(s.`ddate`) AS ddate, 
						s.`acc_code` , 
						a.`description`, 
						s.`trans_no`,
						IFNULL(SUM(dr)-SUM(cr),0) AS balance,
						t.sub_no
				FROM `t_internal_transfer_trans` s
				JOIN m_account a ON a.`code`=s.`acc_code` 
				JOIN (SELECT cl,bc,nno,sub_no FROM t_internal_transfer_sum WHERE `type`='branch') t 
					ON t.cl=s.cl AND t.bc=s.bc AND t.nno=s.`trans_no`
				WHERE s.`trans_code`='42' AND s.ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'";
		if(!empty($_POST['cluster'])){
          $sql.=" AND s.`cl` = '".$_POST['cluster']."'";
        }if(!empty($_POST['branch'])){
          $sql.=" AND s.`bc` = '".$_POST['branch']."'";
        }  			
		$sql .= "GROUP BY acc_code,trans_code, trans_no
				HAVING balance >0
				ORDER BY s.acc_code,s.trans_no";

		$r_detail['cus_det']=$this->db->query($sql)->result();	

        if($this->db->query($sql)->num_rows()>0){
			$this->load->view($_POST['by'].'_'.'pdf',$r_detail);
		}else{
			echo "<script>alert('No Data');window.close();</script>";
		}
	}
}	
?>