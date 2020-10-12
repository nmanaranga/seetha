<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_account_analysis extends CI_Model {

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
    	
    }



    

    public function PDF_report($RepTyp=""){


        $cluster=$_POST['cluster'];
        $branch=$_POST['branch'];

        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];

        $sql="SELECT * FROM m_branch WHERE bc='$branch'";
        $query = $this->db->query($sql);
        $r_detail['s_branch']=$query->first_row()->cl.' - '.$query->first_row()->bc.' ('.$query->first_row()->name.')';

        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $r_detail['acc_code']=$_POST['acc_code'];
        $r_detail['page']='A4';
        $r_detail['orientation']='L';  
        $r_detail['dfrom']=$_POST['from'];
        $r_detail['dto']=$_POST['to'];


        $this->db->select(array('name','address','tp','fax','email'));
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $r_detail['branch']=$this->db->get('m_branch')->result();

        $sqll="SELECT t.`trans_code`,t.`ddate`,IFNULL(d.`cheque_no`,'')AS cheque_no ,IFNULL(IFNULL(gs.note,vs.`memo`),t.description) AS`description`,
        t.`dr_amount` ,t.`cr_amount` ,d.`to_acc_code`,IFNULL(s.`description`,gs.payee_name) AS `name`,
        CONCAT(t.cl,t.`bc`,LPAD(t.trans_no,6,0)) AS tno,t_trans_code.`description` AS det
        FROM `t_account_trans` t 
        LEFT JOIN `t_cheque_withdraw_det` d ON d.`cl`=t.`cl` AND d.`bc`=t.`bc` AND t.`trans_no`=d.`nno` AND t.`cr_amount`=d.`amount`

        LEFT JOIN `m_account` s ON s.`code`=d.`to_acc_code`
        LEFT JOIN t_trans_code ON t_trans_code.`code`= t.`trans_code` 
        LEFT JOIN t_cheque_issued c ON c.`cheque_no`=d.`cheque_no` AND c.bc=d.bc
        LEFT JOIN `t_voucher_gl_sum` gs ON gs.`cl`=c.`cl` AND gs.`bc`=c.`bc` AND gs.`nno`=c.`trans_no`
        LEFT JOIN `t_voucher_sum` vs ON vs.`cl`=c.`cl` AND vs.`bc`=c.`bc` AND vs.`nno`=c.`trans_no`
        WHERE t.ddate BETWEEN '$_POST[from]' AND '$_POST[to]' ";

        if(!empty($cluster)){
            $sqll.=" AND t.`cl` = '$cluster'";
        }
        if(!empty($branch)){
            $sqll.=" AND t.`bc` = '$branch'";
        }  
        if($_POST['acc_code']!=""){
            $sqll.=" AND t.acc_code='$_POST[acc_code]'";
        }   
        $sqll.="  GROUP BY t.`trans_code`,t.`trans_no`,d.`cheque_no`";
        $sqll.=" ORDER BY t.ddate,t.auto_no";
        $r_detail['all_acc_det']=$this->db->query($sqll)->result();




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