<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_stock_details extends CI_Model {

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
        $r_detail['dd']=$_POST['dd'];
        $r_detail['qno']=$_POST['qno'];

        $r_detail['page']=$_POST['page'];
        $r_detail['header']=$_POST['header'];
        $r_detail['type']='';
        $r_detail['orientation']="L";
        $r_detail['from']=$_POST['from'];
        $r_detail['to']=$_POST['to'];
        $r_detail['cluster']=$_POST['cluster'];
        $r_detail['branchs']=$_POST['branch'];
        $r_detail['store']=$_POST['store'];
        $r_detail['department']=$_POST['department'];
        $r_detail['main_category']=$_POST['main_category'];
        $r_detail['sub_category']=$_POST['sub_category'];
        $r_detail['item']=$_POST['item'];  

        $r_detail['department_des']=$_POST['department_des'];
        $r_detail['main_category_des']=$_POST['main_category_des'];
        $r_detail['sub_category_des']=$_POST['sub_category_des'];
        $r_detail['item_des']=$_POST['item_des'];

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

        
        $sql="SELECT i.`department`,
        i.`code`,
        i.`description`,
        i.`model`,
        IFNULL(opqty,0) AS opqty,
        IFNULL(trQty,0) AS trQty,
        IFNULL(grQty,0) AS grQty,
        IFNULL(diQty,0) AS diQty,
        IFNULL(srQty,0) AS srQty,
        IFNULL(irQty,0) AS irQty,
        IFNULL(tQty,0) AS tQty,
        IFNULL(prQty,0) AS prQty,
        IFNULL(doQty,0) AS doQty,
        IFNULL(csQty,0) AS csQty,
        (IFNULL(opqty,0)+IFNULL(trQty,0)+IFNULL(grQty,0)+IFNULL(diQty,0)+IFNULL(srQty,0)+IFNULL(irQty,0))-(IFNULL(tQty,0)+IFNULL(prQty,0)+IFNULL(doQty,0)+IFNULL(csQty,0)) AS clbal
        FROM `m_item` i 

        LEFT JOIN(SELECT i.`item`,SUM(i.qty_in)-SUM(i.qty_out) AS opqty FROM `t_item_movement` i
        WHERE i.`ddate` <'".$_POST['from']."' AND i.cl='".$_POST['cluster']."' AND i.bc='".$_POST['branch']."'  AND i.store_code='".$_POST['store']."'
        GROUP BY i.item)it ON it.item=i.`code`

        LEFT JOIN(SELECT d.`item_code`,SUM(d.`qty`) AS trQty FROM `t_internal_transfer_det` d
        JOIN `t_internal_transfer_sum` s ON s.cl=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
        WHERE s.`is_cancel`=0 AND s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND s.cl='".$_POST['cluster']."' AND s.bc='".$_POST['branch']."' AND s.`trans_code`=43 AND s.`store`='".$_POST['store']."'
        GROUP BY d.`item_code`)tr ON tr.item_code=i.`code`

        LEFT JOIN(SELECT d.`code`,SUM(d.`qty`) AS grQty FROM `t_grn_det` d
        JOIN `t_grn_sum` s ON s.cl=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
        WHERE s.`is_cancel`=0 AND s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND s.cl='".$_POST['cluster']."' AND s.bc='".$_POST['branch']."' AND s.store='".$_POST['store']."'
        GROUP BY d.`code`)gr ON gr.code=i.`code`

        LEFT JOIN(SELECT * FROM (SELECT d.`code`,SUM(d.`qty`) AS diQty FROM `t_dispatch_det` d
        JOIN `t_dispatch_sum` s ON s.cl=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
        WHERE s.`is_cancel`=0 AND s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND s.cl='".$_POST['cluster']."' AND s.bc='".$_POST['branch']."' AND s.`store_to`='".$_POST['store']."'
        GROUP BY d.`code`
        UNION ALL
        SELECT d.`code`,SUM(d.`qty`) AS diQty FROM `t_unloading_det` d
        JOIN `t_unloading_sum` s ON s.cl=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
        WHERE s.`is_cancel`=0 AND s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND s.cl='".$_POST['cluster']."' AND s.bc='".$_POST['branch']."' AND s.`store_to`='".$_POST['store']."'
        GROUP BY d.`code`)d)di ON di.code=i.`code`

        LEFT JOIN(SELECT d.`code`,SUM(d.`qty`) AS srQty FROM `t_sales_return_det` d
        JOIN `t_sales_return_sum` s ON s.cl=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
        WHERE s.`is_cancel`=0 AND s.is_approve='1' AND s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND s.cl='".$_POST['cluster']."' AND s.bc='".$_POST['branch']."' AND s.`store`='".$_POST['store']."'
        GROUP BY d.`code`)sr ON sr.code=i.`code`

        LEFT JOIN(SELECT d.`item_code`,SUM(d.`accept_qty`) AS irQty FROM `t_internal_transfer_return_det` d
        JOIN `t_internal_transfer_return_sum` s ON s.cl=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
        WHERE s.`is_cancel`=0 AND s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND s.cl='".$_POST['cluster']."' AND s.bc='".$_POST['branch']."' AND s.`to_store`='".$_POST['store']."'
        GROUP BY d.`item_code`)ir ON ir.item_code=i.`code`

        LEFT JOIN(SELECT d.`item_code`,SUM(d.`qty`) AS tQty FROM `t_internal_transfer_det` d
        JOIN `t_internal_transfer_sum` s ON s.cl=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
        WHERE s.`is_cancel`=0 AND s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND s.cl='".$_POST['cluster']."' AND s.bc='".$_POST['branch']."' AND s.`trans_code`=42 AND s.`store`='".$_POST['store']."'
        GROUP BY d.`item_code`)t ON t.item_code=i.`code`

        LEFT JOIN(SELECT d.`code`,SUM(d.`qty`) AS prQty FROM `t_pur_ret_det` d
        JOIN `t_pur_ret_sum` s ON s.cl=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
        WHERE s.`is_cancel`=0 AND s.is_approve='1' AND s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND s.cl='".$_POST['cluster']."' AND s.bc='".$_POST['branch']."' AND s.`store`='".$_POST['store']."'
        GROUP BY d.`code`)pr ON pr.code=i.`code`

        LEFT JOIN(SELECT * FROM (SELECT d.`code`,SUM(d.`qty`) AS doQty FROM `t_dispatch_det` d
        JOIN `t_dispatch_sum` s ON s.cl=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
        WHERE s.`is_cancel`=0 AND s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND s.cl='".$_POST['cluster']."' AND s.bc='".$_POST['branch']."' AND s.`store_from`='".$_POST['store']."'
        GROUP BY d.`code`
        UNION ALL
        SELECT d.`code`,SUM(d.`qty`) AS doQty FROM `t_loading_det` d
        JOIN `t_loading_sum` s ON s.cl=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
        WHERE s.`is_cancel`=0 AND s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND s.cl='".$_POST['cluster']."' AND s.bc='".$_POST['branch']."' AND s.`store_from`='".$_POST['store']."'
        GROUP BY d.`code`)d)DO ON do.code=i.`code`

        LEFT JOIN(SELECT * FROM (SELECT d.`code`,SUM(d.`qty`) AS csQty FROM `t_cash_sales_det` d
        JOIN `t_cash_sales_sum` s ON s.cl=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
        WHERE s.`is_cancel`=0 AND s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND s.cl='".$_POST['cluster']."' AND s.bc='".$_POST['branch']."' AND s.`store`='".$_POST['store']."'
        GROUP BY d.`code`
        UNION ALL
        SELECT d.`code`,SUM(d.`qty`) AS csQty FROM `t_credit_sales_det` d
        JOIN `t_credit_sales_sum` s ON s.cl=d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
        WHERE s.`is_cancel`=0 AND s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND s.cl='".$_POST['cluster']."' AND s.bc='".$_POST['branch']."' AND s.`store`='".$_POST['store']."'
        GROUP BY d.`code`)d)cs ON cs.code=i.`code`

        WHERE i.`inactive`=0";

        if($_POST['department']!=""){
           $sql.=" AND i.department='".$_POST['department']."'";
       }
       if($_POST['main_category']!=""){
           $sql.=" AND i.main_category='".$_POST['main_category']."'";
       }
       if($_POST['sub_category']!=""){
           $sql.=" AND i.category='".$_POST['sub_category']."'";
       }
       if($_POST['item']!=""){
           $sql.=" AND i.code='".$_POST['item']."'";
       }


       $sql.=" GROUP BY i.`code` 
       HAVING (opqty!='0' OR  trQty!='0' OR grQty!='0' OR diQty!='0' OR srQty!='0' OR irQty!='0' OR tQty!='0' OR prQty!='0' OR doQty!='0' OR csQty!='0' OR clbal!='0')";



       $r_detail['stock']=$this->db->query($sql)->result(); 

       if($this->db->query($sql)->num_rows()>0){
          $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
      }else{
          echo "<script>alert('No Data');window.close();</script>";
      }
      
  }
}
?>