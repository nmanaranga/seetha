<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_account_report_last_yer extends CI_Model {
    
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
    	$this->load->model('m_branch');
    	return $a;
	}


	

    

	public function PDF_report(){

        $cluster=$_POST['cluster'];
        $branch=$_POST['branch'];

        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];

        $sql="SELECT * FROM m_branch WHERE bc='$branch'";
        $query = $this->db->query($sql);
        $r_detail['s_branch']=$query->first_row()->cl.' - '.$query->first_row()->bc.' ('.$query->first_row()->name.')';


        $this->db->select(array('name','address','tp','fax','email'));
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $r_detail['branch']=$this->db->get('m_branch')->result();

        $this->db->where('code',$_POST['acc_code']);

        $query=$this->db->get('m_account');
        foreach($query->result() as $row){
            $r_detail['account_det']=$row->description;
        }

        $sql="SELECT SUM(dr_amount-cr_amount) AS op, SUM(dr_amount) AS dr, SUM(cr_amount) AS cr FROM t_account_trans_20180331
         WHERE acc_code='$_POST[acc_code]' AND ddate<'$_POST[from]'";
        if(!empty($cluster)){
            $sql.=" AND `cl` = '$cluster'";
        }if(!empty($branch)){
            $sql.=" AND `bc` = '$branch'";
        }

        //$r_detail['op']=$this->db->query($sql)->first_row()->op;
        $r_detail['op']=$this->db->query($sql)->result();


        $sqll="SELECT t_account_trans_20180331.*,
                    t_trans_code.`description` AS det,
                    t_account_trans_20180331
                    .description AS description, 
                    t_trans_code.`code` as t_code,
                    CONCAT(t_account_trans_20180331
                    .cl,t_account_trans_20180331
                    .`bc`,LPAD(t_account_trans_20180331
                    .trans_no,6,0)) AS tno 
                FROM t_account_trans_20180331
                 LEFT JOIN t_trans_code ON t_trans_code.`code`= t_account_trans_20180331
                 .`trans_code` 
                WHERE ddate!='' 
               AND ddate BETWEEN '$_POST[from]' AND '$_POST[to]'
               ";
        if(!empty($cluster)){
            $sqll.=" AND `cl` = '$cluster'";
        }
        if(!empty($branch)){
            $sqll.=" AND `bc` = '$branch'";
        }  
        if($_POST['acc_code']!=""){
            $sqll.=" AND acc_code='$_POST[acc_code]'";
        }     
        $sqll.=" ORDER BY ddate,auto_no";
        $r_detail['all_acc_det']=$this->db->query($sqll)->result();

		$cl=$this->sd['cl'];
		$bc=$this->sd['branch'];
        $r_detail['acc_code']=$_POST['acc_code'];
        $r_detail['page']='A4';
        $r_detail['orientation']='P';  
        $r_detail['dfrom']=$_POST['from'];
        $r_detail['dto']=$_POST['to'];

        // var_dump($sqll);
        // exit();
               
        if($this->db->query($sqll)->num_rows()>0){
			$exTy=($RepTyp=="")?'pdf':'excel';
            $this->load->view($_POST['by'].'_'.$exTy,$r_detail);
		}else{
		 	echo "<script>alert('No Data');window.close()</script>";
		}
	}


     public function Excel_report()
    {
        $this->PDF_report("Excel");
    }

}	
?>