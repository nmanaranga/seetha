<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class item_list extends CI_Model {
    
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
          $r_detail['page']=$_POST['page'];
          $r_detail['type']=$_POST['type']; 
        
  
          $query=$this->db->query("SELECT i.`code` ,i.`description` ,i.`brand` ,i.`model` ,i.`max_price` ,i.`min_price` ,c.`description` AS Category 
   ,i.`department`  ,d.`description` AS Department_Name
FROM `m_item` i
  LEFT JOIN `r_category` c ON i.`category`=c.`code`
  LEFT JOIN `r_department` d ON i.`department`=d.`code` order by i.`code`");

          $r_detail['customer']=$query->result();  
          $this->load->view($_POST['by'].'_'.'pdf',$r_detail);

    
     }
}
?>