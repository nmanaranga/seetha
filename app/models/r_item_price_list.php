<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_item_price_list extends CI_Model {
    
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
          $r_detail['orientation']='P'; 

          //////////////////

    $this->db->select(array('description','code'));
    $this->db->where("code",$_POST['cluster']);
    $r_detail['clus']=$this->db->get('m_cluster')->result();

    $this->db->select(array('name','bc'));
    $this->db->where("bc",$_POST['branch']);
    $r_detail['bran']=$this->db->get('m_branch')->result();

    $this->db->select(array('description','code'));
    $this->db->where("cl",$_POST['cluster']);
    $this->db->where("code",$_POST['store']);
    $r_detail['str']=$this->db->get('m_stores')->result();

    $this->db->select(array('description','code'));
    $this->db->where("code",$_POST['department']);
    $r_detail['dp']=$this->db->get('r_department')->result();

    $this->db->select(array('description','code'));
    $this->db->where("code",$_POST['main_category']);
    $r_detail['cat']=$this->db->get('r_category')->result();

    $this->db->select(array('description','code'));
    $this->db->where("code",$_POST['sub_category']);
    $r_detail['scat']=$this->db->get('r_sub_category')->result();

    $this->db->select(array('description','code'));
    $this->db->where("code",$_POST['item']);
    $r_detail['itm']=$this->db->get('m_item')->result();

    $this->db->select(array('description','code'));
    $this->db->where("code",$_POST['unit']);
    $r_detail['unt']=$this->db->get('r_unit')->result();

    $this->db->select(array('description','code'));
    $this->db->where("code",$_POST['brand']);
    $r_detail['brnd']=$this->db->get('r_brand')->result();

    $this->db->select(array('name','code'));
    $this->db->where("code",$_POST['supplier']);
    $r_detail['sup']=$this->db->get('m_supplier')->result();

///////


    $this->db->select(array('code','description'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $this->db->where("code",$_POST['stores']);
    $r_detail['store_des']=$this->db->get('m_stores')->row()->description;

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
        
    if(!empty($cluster))
    {
       $sql="SELECT`code`AS `Item_Id`,`description` AS Item,`purchase_price` AS `Cost`,`max_price` AS `Max_Sales_Price`,(((max_price-purchase_price)/max_price)*100) AS `Margin`,`min_price` AS `Last_Sales_Price`,(((min_price-purchase_price)/min_price)*100) AS `Margin2`
          FROM  `m_item`";
    }
    else
    {
      $sql="SELECT`code`AS `Item_Id`,`description` AS Item,`purchase_price` AS `Cost`,`max_price` AS `Max_Sales_Price`,(((max_price-purchase_price)/max_price)*100) AS `Margin`,`min_price` AS `Last_Sales_Price`,(((min_price-purchase_price)/min_price)*100) AS `Margin2`
          FROM  `m_item`";
    }
    
/**
    if(!empty($cluster))
    {
      $sql.=" AND i.`cl` = '$cluster'";
    }

    if(!empty($branch))
    {
      $sql.=" AND i.`bc` = '$branch'";
    }

    if(!empty($store))
    {
      $sql.=" AND i.`store_code` = '$store'";
    }

    if(!empty($department))
    {
      $sql.=" AND `m`.`department` = '$department'";
    }

    if(!empty($main_category))
    {
      $sql.=" AND `m`.`main_category` = '$main_category'";
    }

    if(!empty($sub_category))
    {
      $sql.=" AND `m`.`category` = '$sub_category'";
    }

    if(!empty($item))
    {
      $sql.=" AND `m`.`code` = '$item'";
    }

    if(!empty($unit))
    {
      $sql.=" AND `m`.`unit` = '$unit'";
    }

    if(!empty($brand))
    {
      $sql.=" AND `m`.`brand` = '$brand'";
    }

    if(!empty($supplier))
    {
      $sql.=" AND `m`.`supplier` = '$supplier'";
    }

    $sql.= " GROUP BY  s.`code`";

    **/

    $r_detail['customer']=$this->db->query($sql)->result(); 

    if($this->db->query($sql)->num_rows()>0){
      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }else{
      echo "<script>alert('No Data');window.close();</script>";
    }

    
     }
}
?>