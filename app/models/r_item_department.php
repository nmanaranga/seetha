<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_item_department extends CI_Model {
    
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


        $this->db->select(array('code','description'));
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $this->db->where("code",$_POST['stores']);
        $r_detail['store_des']=$this->db->get('m_stores')->row()->description;

        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];

        //$r_detail['store_code']=$_POST['stores']; 
        $r_detail['type']=$_POST['type'];        
        $r_detail['dd']=$_POST['dd'];
        $r_detail['qno']=$_POST['qno'];

        $r_detail['page']=$_POST['page'];
        $r_detail['header']=$_POST['header'];
        $r_detail['orientation']="P";
       /* $r_detail['from']=$_POST['from'];
        $r_detail['to']=$_POST['to'];
        $r_detail['cluster']=$_POST['cluster'];
        $r_detail['branchs']=$_POST['branch'];
        $r_detail['store']=$_POST['store'];
        $r_detail['department']=$_POST['department'];
        $r_detail['main_category']=$_POST['main_category'];
        $r_detail['sub_category']=$_POST['sub_category'];
        $r_detail['item']=$_POST['item'];
        $r_detail['unit']=$_POST['unit'];
        $r_detail['brand']=$_POST['brand'];
        $r_detail['supplier']=$_POST['supplier'];

        $to=$_POST['to'];
        $from=$_POST['from'];
        $cluster=$_POST['cluster'];
        $branch=$_POST['branch'];
        $store=$_POST['store'];
        $department=$_POST['department'];
        $main_category=$_POST['main_category'];
        $sub_category=$_POST['sub_category'];
        $item=$_POST['item'];
        $unit=$_POST['unit'];
        $brand=$_POST['brand'];
        $supplier=$_POST['supplier'];*/



        $sql="SELECT  `code`,  `description`,  `pv_card_rate`,code_gen FROM `r_department`" ;


        $r_detail['dep']=$this->db->query($sql)->result(); 

        if($this->db->query($sql)->num_rows()>0){
          $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
        }else{
          echo "<script>alert('No Data');window.close();</script>";
        }
  }
}
?>