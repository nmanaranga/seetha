<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_supplier_2 extends CI_Model {
    
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

   /* $sql="SELECT s.code,
                  s.`name`,
                  s.`contact_name`,
                  c.`type`,
                  c.`description`,
                  c.tp
          FROM m_supplier s
          JOIN m_supplier_contact c ON c.`code` = s.`code`";     */ 

     $sql="SELECT ms.code, 
  ms.name, 
  IFNULL(mc_mob.mob,'') AS mobile, 
  IFNULL(mc_mob.description,'') AS mobile_name, 
  IFNULL(mc_fax.mob,'') AS fax,
  IFNULL(mc_fax.description,'') AS fax_name, 
  IFNULL(mc_resident.mob,'') AS resident,
  IFNULL(mc_resident.description,'') AS resident_name, 
  IFNULL(mc_office.mob,'') AS office,
  IFNULL(mc_office.description,'') AS office_name 
FROM `m_supplier` ms
LEFT JOIN (SELECT `code`, tp AS mob, description FROM `m_supplier_contact` WHERE TYPE ='mobile') mc_mob ON (ms.`code`=mc_mob.`code`)
LEFT JOIN (SELECT `code`, tp AS mob, description FROM `m_supplier_contact` WHERE TYPE ='fax') mc_fax ON (ms.`code`=mc_fax.`code`)
LEFT JOIN (SELECT `code`, tp AS mob, description FROM `m_supplier_contact` WHERE TYPE ='resident') mc_resident ON (ms.`code`=mc_resident.`code`)
LEFT JOIN (SELECT `code`, tp AS mob, description FROM `m_supplier_contact` WHERE TYPE ='office') mc_office ON (ms.`code`=mc_office.`code`)";

    if($_POST['s_status']=="1"){
      $sql.="WHERE ms.is_inactive='0'";
    }else if($_POST['s_status']=="2"){
      $sql.="WHERE ms.is_inactive='1'";
    }else if($_POST['s_status']=="3"){
      $sql.="";
    } 
    
    $r_detail['sup_det']=$this->db->query($sql)->result();  

        if($this->db->query($sql)->num_rows()>0){
      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }else{
      echo "<script>alert('No Data');window.close();</script>";
    }

  }
} 


