<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_jurnal_entry extends CI_Model {
    
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


	public function PDF_report()
	{
		 //-----------------------------------------------------------------------------------------------------------

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
        $r_detail['orientation']="L";
        $r_detail['dfrom']=$_POST['from'];
        $r_detail['dto']=$_POST['to'];
        $r_detail['trans_code']=$_POST['t_type'];
        $r_detail['trans_code_des']=$_POST['t_type_des'];
        $r_detail['trans_no_from']=$_POST['t_range_from'];
        $r_detail['trans_no_to']=$_POST['t_range_to'];
        $cluster=$_POST['cluster'];
        $branch=$_POST['branch'];

        $sql="SELECT 
                  JES.`no`,
                  JES.`date`,
                  JES.`journal_type`,
                  JES.`description`,
                  MA.`code`,
                  MA.`description`,
                  JED.`account_code`,
                  JED.`memo`,
                  JED.`cr_amount`,
                  JED.`dr_amount`,
                  JED.`bc`,
                  JED.`cl`
             FROM
                  `t_journal_entry_det` AS JED
                    INNER JOIN `m_account` AS MA
                        ON (JED.`account_code` = MA.`code`)
                    INNER JOIN `t_journal_entry_sum` AS JES
                        ON (JED.`no` = JES.`no`) AND (JED.`bc` = JES.`bc`) AND (JED.`cl` = JES.`cl`)
                     WHERE JES.`date` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'";


      if(!empty($cluster))
        {
            $sql.=" AND JED.cl= '$cluster'";
        }
        if(!empty($branch))
        {
            $sql.=" AND JED.bc = '$branch'";
        } 

     $r_detail['t_jurnal_sum']=$this->db->query($sql)->result();    //pass as the variable in pdf page t_jurnal_entry

     if($this->db->query($sql)->num_rows()>0)
        {
            $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
        }
        else
        {
            echo "<script>alert('No Data');window.close();</script>";
        }

	}
}	
?>