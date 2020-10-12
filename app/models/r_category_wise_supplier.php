<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_category_wise_supplier extends CI_Model {
    
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
          $r_detail['page']="A4";
          $r_detail['orientation']='L'; 
        
  
       $query=$this->db->query("SELECT s.`code` ,s.`name` ,c.`description` AS Category, s.`address1`   ,s.`email` ,t.`type` ,t.`tp` 
            FROM `m_supplier`  s
            LEFT JOIN `r_sup_category` c ON s.`category`=c.`code`
            LEFT JOIN `m_supplier_contact` t ON s.`code` = t.`code` ORDER BY  `Category` ,s.`code` ");

         $r_detail['customer']=$query->result();  
         $this->load->view($_POST['by'].'_'.'pdf',$r_detail);

    
     }
}
?>