<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_cheque_list extends CI_Model {

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


    }
    
    public function base_details(){

    	return $a;
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
        $r_detail['orientation']="P";
        $r_detail['dfrom']=$_POST['from'];
        $r_detail['dto']=$_POST['to'];
        $r_detail['trans_code']=$_POST['t_type'];
        $r_detail['trans_code_des']=$_POST['t_type_des'];
        $r_detail['trans_no_from']=$_POST['t_range_from'];
        $r_detail['trans_no_to']=$_POST['t_range_to'];
        $cluster=$_POST['cluster'];
        $branch=$_POST['branch'];

        $r_detail['status']=$_POST['status'];


        if($_POST['status']!="0"){

            $status = "AND tr.status = '".$_POST['status']."'";
        }else{
            $status="";
        }


        $sql = "SELECT 
        tr.ddate,
        tr.bank_date,
        tr.cheque_no,
        tr.amount,
        tc.description AS trans_type,
        tr.trans_no 
        FROM 
        t_cheque_received tr
        JOIN t_trans_code tc ON tc.code = tr.trans_code
         WHERE tr.cl='".$this->sd['cl']."' AND tr.bc='".$this->sd['branch']."' 
         AND tr.ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'
         $status
         ";

  
        
  

        $r_detail['r_bank_entry']=$this->db->query($sql)->result();    //pass as the variable in pdf page t_bank_entry_list



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