<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_customer_list extends CI_Model {
    
    private $tb_items;
    private $tb_main_cat;
    private $tb_sub_cat;
    private $sd;
    private $w = 210;
    private $h = 297;
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('useclass');
        
        $this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
       
    }
    
    public function base_details(){
      
    }
    
     public function PDF_report(){
      $this->db->select(array('name','address','tp','fax','email'));
      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $r_detail['branch']=$this->db->get('m_branch')->result();
      $r_detail['page']='A4';
      $r_detail['orientation']='L'; 
      $type=$_POST['cus_type'];
      $cus_id=$_POST['cu_id'];
      $category=$_POST['cus_category'];
      $area=$_POST['area_code'];
      $town=$_POST['town_id'];
      $root=$_POST['root_id'];

    
      $sql="SELECT c.`code`,
                   c.`name`,
                   c.`nic`, 
                   CONCAT( c.`address1`   ,c.address2 ,c.address3) as address,
                   c.`email`,
                   c.`category`,
                   t.`description` AS CategoryName,
                   c.`tp`,
                   c.`mobile`,
                   c.dob
            FROM `m_customer` c
            LEFT JOIN `r_cus_category` t ON c.`category`=t.`code` 
            WHERE c.code != '' ";      
           
        if(!empty($_POST['cus_type'])){
          $sql.=" AND c.`type`='$type'";
        }if(!empty($_POST['cu_id'])){
          $sql.=" AND c.`code`='$cus_id'";
        }if(!empty($_POST['cus_category'])){
          $sql.=" AND c.`category`='$category'";
        }if(!empty($_POST['area_code'])){
          $sql.=" AND c.`area`='$area'";
        }if(!empty($_POST['town_id'])){
          $sql.=" AND c.`town`='$town'";
        }if(!empty($_POST['root_id'])){
          $sql.=" AND c.`root`='$root'";
        }if(!empty($_POST['cluster'])){
          $sql.=" AND c.`cl` = '".$_POST['cluster']."'";
        }if(!empty($_POST['branch'])){
          $sql.=" AND c.`bc` = '".$_POST['branch']."'";
        }

        if($_POST['s_status']=="1"){
          $sql.=" AND c.inactive='0'";
        }else if($_POST['s_status']=="2"){
          $sql.=" AND c.inactive='1'";
        }else if($_POST['s_status']=="3"){
          $sql.="";
        }             

        $sql .="ORDER BY  c.`category`";
        $query=$this->db->query($sql);
        if ($query->num_rows() > 0) {
          $r_detail['customer']=$query->result();  
          $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
        }else{
          echo"<script>alert('No records');history.go(-1);</script>";
        }
     }
}
