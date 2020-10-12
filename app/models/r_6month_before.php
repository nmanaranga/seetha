<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class R_6month_before extends CI_Model {
    
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

		$this->db->select(array('code','description'));
		$this->db->where("cl",$this->sd['cl']);
		$this->db->where("bc",$this->sd['branch']);
		$this->db->where("code",$_POST['stores']);
		$r_detail['store_des']=$this->db->get('m_stores')->row()->description;

		$cl=$this->sd['cl'];
		$bc=$this->sd['branch'];

		$r_detail['store_code']=$_POST['stores'];	
		$r_detail['type']="r_stock_in_hand8";       
		$r_detail['dd']=$_POST['dd'];
		$r_detail['qno']=$_POST['qno'];
		$re_type = 0;
		if($_POST['to']==""){
			$_POST['to']=date('Y-m-d');
			$to=date('Y-m-d');
			$re_type = 1;
		}

		$r_detail['page']="A4";
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

		if($_POST['cluster']!="0"){
			$cl=" AND s.`cl`='".$_POST['cluster']."'";
		}else{
			$cl=" ";
		}

		if($_POST['branch']!="0"){
			$bc=" AND s.`bc`='".$_POST['branch']."'";
		}else{
			$bc=" ";
		}

		if($_POST['store']!="0"){
			$store=" AND s.`store_code`='".$_POST['store']."'";
		}else{
			$store=" ";
		}

		if($_POST['item']!=""){
			$item=" AND s.`item`='".$_POST['item']."'";
		}else{
			$item=" ";
		}
		
		$sql="	SELECT 	s.cl,
						s.`bc`,
						s.`date`,
						GROUP_CONCAT(s.serial_no) as serials,
						-- t.`description` AS trans_type,
						s.`trans_no`,
						s.`item`,
						m.model,
						m.`description` AS item_des,
						s.`batch`,
						q.qty AS qty,
						b.`purchase_price`,
						b.`min_price`,
						b.`max_price`
				FROM t_serial s
				JOIN m_item m ON m.`code` = s.`item`
				-- JOIN t_trans_code t ON t.`code`=s.`trans_type`
				 JOIN t_item_batch b ON b.`item` = s.`item` AND b.`batch_no`=s.`batch`
				JOIN qry_current_stock q ON q.`cl`=s.`cl` AND q.`bc`=s.`bc` AND q.`store_code`=s.`store_code` 
					AND q.`item`=s.`item` AND q.`batch_no`=s.`batch`
				WHERE s.`date` < DATE_FORMAT((DATE_SUB(NOW(), INTERVAL 6 MONTH)),'%Y-%m-%d')
				AND available='1'
				 $cl $bc $store $item
				GROUP BY s.`item`, s.`batch`
				-- LIMIT 1";
		
		$r_detail['item_det']=$this->db->query($sql)->result();	

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