<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_open_stock extends CI_Model {
    
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

    //////////////////

    $this->db->select(array('description','code'));
    $this->db->where("code",$this->sd['cl']);
    $r_detail['clus']=$this->db->get('m_cluster')->result();

    
    $this->db->select(array('name','bc'));
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['bran']=$this->db->get('m_branch')->result();

    

    ///////



    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];

    $r_detail['store_code']=$_POST['stores']; 
    $r_detail['type']=$_POST['type'];        
    $r_detail['dd']=$_POST['dd'];
    $r_detail['qno']=$_POST['qno'];

    $r_detail['page']=$_POST['page'];
    $r_detail['header']=$_POST['header'];
    $r_detail['orientation']="P";
    $r_detail['from']=$_POST['from'];
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
    $supplier=$_POST['supplier'];

    
      $sql="SELECT s.ddate , d.item_code, m.`description`, m.`model`, d.qty, d.cost, d.min_price, d.max_price, t.description as store 
                FROM t_op_stock_det d
                JOIN t_op_stock s ON s.`nno` = d.`nno` AND s.`bc`=d.`bc` AND s.`cl` = d.`cl`
                JOIN m_item m ON m.`code` = d.`item_code`
                JOIN m_stores t ON t.code = s.store
                WHERE d.item_code !=''";
   
        if(!empty($cluster))
        {
            $sql.=" AND s.`cl` = '$cluster'";
        }
        
        if(!empty($branch))
        {
            $sql.=" AND s.`bc` = '$branch'";
        }
        
        if(!empty($store))
        {
            $sql.=" AND s.`store` = '$store'";
        }

        $sql.="GROUP BY d.item_code, d.min_price, d.max_price";

    $r_detail['det']=$this->db->query($sql)->result(); 

    if($this->db->query($sql)->num_rows()>0){
      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }else{
      echo "<script>alert('No Data');window.close();</script>";
    }
    
  }
}
?>