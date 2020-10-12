<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_batch_in_hand extends CI_Model {

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


	public function PDF_report($RepTyp=""){
		$this->db->select(array('name','address','tp','fax','email'));
		$this->db->where("cl",$this->sd['cl']);
		$this->db->where("bc",$this->sd['branch']);
		$r_detail['branch']=$this->db->get('m_branch')->result();

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
		$r_detail['orientation']=$_POST['orientation'];
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
			$clu="AND `cl` = '".$cluster."'";
		}
		if(!empty($branch))
		{
			$bru="AND bc = '".$branch."'";
		}

		if(!empty($branch))
		{
			$bru2="$branch";
		}

		if(!empty($cluster))
		{
			$clu2="$cluster";
		}
		
		if(!empty($store))
		{
			$str="AND store_code='".$store."'";
		}

		if(!empty($department))
		{
			$dep="AND department='".$department."'";
		}

		if(!empty($main_category))
		{
			$main_cat="AND main_category='".$main_category."'";
		}

		if(!empty($sub_category))
		{
			$sub_cat="AND category='".$sub_category."'";
		}

		if(!empty($item))
		{
			$itm="AND item='".$item."'";
		}

		if(!empty($unit))
		{
			$unit="AND unit='".$unit."'";
		}

		if(!empty($brand))
		{
			$brand="AND brand='".$brand."'";
		}

		if(!empty($supplier))
		{
			$sup="AND supplier='".$supplier."'";
		}

		if(isset($_POST['minus'])){
			$qty_chk='IFNULL(i.qty,0)<0';
		}else{
			$qty_chk='IFNULL(i.qty,0)>0';
		}

		/*$sql="SELECT m.`code` AS item, 
		IFNULL(i.batch_no,0) AS batch_no, 
		IFNULL(c.item_tot,0) AS item_tot, 
		IFNULL(i.store_code,'') AS store_code, 
		IFNULL(i.qty,0) AS qty, 
		IFNULL(sr.sr_count,0) AS sr_count,
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
		WHERE ddate <='$to' $clu $bru $str $itm
		GROUP BY item, batch_no, store_code) i   ON ((i.`item` = m.`code`)) 
		JOIN (SELECT item,batch_no,purchase_price,min_price,max_price FROM t_item_batch 
		GROUP BY item,batch_no) e ON e.item = m.code AND e.batch_no=i.batch_no
		LEFT JOIN   (SELECT item,SUM(qty_in - qty_out) AS item_tot,`store_code`,cl,bc 
		FROM t_item_movement WHERE (
		t_item_movement.bc = '$bru2' 
		AND t_item_movement.cl = '$clu2' $str  ) 
		GROUP BY item, cl, bc) c ON m.`code` = c.item
		LEFT JOIN (SELECT item,batch, COUNT(serial_no) AS sr_count
		FROM t_serial 
		WHERE available='1' $clu $bru $str GROUP BY item,batch) AS sr ON sr.item=m.`code` AND sr.batch=i.batch_no  

		WHERE $qty_chk $dep $main_cat $sub_cat $unit $brand $sup";*/


		$sql="SELECT m.item,
		IFNULL(i.batch_no, 0) AS batch_no,
		IFNULL(c.item_tot, 0) AS item_tot,
		IFNULL(i.store_code, '') AS store_code,
		IFNULL(i.qty, 0) AS qty,
		IFNULL(i.cost, 0) AS cost,
		m.`description`,
		m.model,
		IFNULL(i.p_date, '') AS p_date,
		m.`cost` AS p_price,
		m.`max_price` AS m_price,
		m.`min_price` AS l_price,
		e.purchase_price AS b_price,
		e.min_price AS b_min,
		e.max_price AS b_max 
		FROM `qry_current_stock` `m` 
		LEFT JOIN  (SELECT item, batch_no, cost, store_code, 
		SUM(qty_in) - SUM(qty_out) AS qty, MIN(ddate) AS p_date FROM t_item_movement 
		WHERE ddate <='$to' $clu $bru $str $itm
		GROUP BY item, batch_no, store_code) i   ON (i.`item` = m.`item` AND i.batch_no=m.`batch_no`) 
		JOIN (SELECT item,batch_no,purchase_price,min_price,max_price FROM t_item_batch 
		GROUP BY item,batch_no) e ON e.item = m.item AND e.batch_no=m.batch_no
		LEFT JOIN   (SELECT item,batch_no,SUM(qty_in - qty_out) AS item_tot,`store_code`,cl,bc 
		FROM t_item_movement WHERE (
		t_item_movement.bc = '$bru2' 
		AND t_item_movement.cl = '$clu2' $str  ) 
		GROUP BY item, cl, bc) c ON m.`item` = c.item AND c.batch_no=m.`batch_no`

		WHERE $qty_chk $dep $main_cat $sub_cat $unit $brand $sup";


		$r_detail['item_det']=$this->db->query($sql)->result();	

		$tot=0;
		foreach($this->db->query($sql)->result() as $row){
			$tot+=$row->b_price*$row->qty;
		}
		$r_detail['f_tot'] = $tot;

		if($this->db->query($sql)->num_rows()>0){
			$exTy=($RepTyp=="")?'pdf':'excel';
			$this->load->view($_POST['by'].'_'.$exTy,$r_detail);
		}else{
			echo "<script>alert('No Data');window.close();</script>";
		}
	}

	public function Excel_report()
	{
		$this->PDF_report("Excel");
	}

}	

?>