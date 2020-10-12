<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_stock_in_hand_batch extends CI_Model {
    
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
	}


	public function PDF_report(){
		$this->db->select(array('name','address','tp','fax','email'));
		$this->db->where("cl",$this->sd['cl']);
		$this->db->where("bc",$this->sd['branch']);
		$r_detail['branch']=$this->db->get('m_branch')->result();

		$cl=$this->sd['cl'];
		$bc=$this->sd['branch'];
		$date=Date("y-m-d");

		$r_detail['type']='r_stock_in_hand_batch';       
		$r_detail['page']=$_POST['page'];
		$r_detail['header']=$_POST['header'];
		$r_detail['orientation']='P';

		$sql1="SELECT def_sales_store from m_branch where cl='$cl' AND bc='$bc'";
		$r_detail['sales_store']=$this->db->query($sql1)->row();
		$store_code=$r_detail['sales_store']->def_sales_store;
				if($store_code!=""){
					$store.="AND store_code='$store_code'";
				}
				

		$sql="SELECT m.`code` AS item, 
				IFNULL(i.batch_no,0) AS batch_no, 
				IFNULL(c.item_tot,0) AS item_tot, 
				IFNULL(i.store_code,'') AS store_code, 
				IFNULL(i.qty,0) AS qty, 
				IFNULL(i.cost,0) AS cost, 
				m.`description`, 
				m.model, 
				IFNULL(i.p_date,'') AS p_date, 
				m.purchase_price AS p_price,
				m.`max_price` AS m_price,
				m.`min_price` AS l_price,
				e.purchase_price As b_price,
				e.min_price AS b_min,
  				e.max_price AS b_max
				FROM `m_item` `m`
					LEFT JOIN  (SELECT item, batch_no, cost, store_code, 
						SUM(qty_in) - SUM(qty_out) AS qty, MIN(ddate) AS p_date FROM t_item_movement 
						WHERE ddate <='$date' AND cl='$cl' AND bc='$bc' $store
						GROUP BY item, batch_no, store_code) i   ON ((i.`item` = m.`code`)) 
					JOIN (SELECT item,batch_no,purchase_price,min_price,max_price FROM t_item_batch 
						GROUP BY item,batch_no,purchase_price) e ON e.item = m.code AND e.batch_no=i.batch_no
					LEFT JOIN   (SELECT item,SUM(qty_in - qty_out) AS item_tot,`store_code`,cl,bc 
						FROM t_item_movement 
						WHERE (t_item_movement.bc = '$bc' AND t_item_movement.cl = '$cl' $store) 
						GROUP BY item, cl, bc) c ON m.`code` = c.item
					WHERE IFNULL(i.qty,0)>0 ";
// ln 69 should be left join
				
		$r_detail['item_det']=$this->db->query($sql)->result();	

        if($this->db->query($sql)->num_rows()>0){
           
      		$this->load->view($_POST['by'].'_'.'pdf',$r_detail);
		}else{
			echo "<script>alert('No Data');window.close();</script>";
		}
	}


}	

?>