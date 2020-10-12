<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_barcode_print extends CI_Model {
	
	private $sd;
	private $mtb;

	
	function __construct(){
		parent::__construct();
		$this->sd = $this->session->all_userdata();
		$this->load->database($this->sd['db'], true);
		$this->load->model('user_permissions');
	}
	
	public function base_details(){

		$a['mxNo']=$this->utility->get_max_no("m_barcode_print_sum", "nno");
		$a['nme']=$this->db->query("SELECT `name` FROM `s_company`")->first_row()->name;

		return $a;
	}
	

	public function PDF_report() {
		if (!empty($_POST['totItms']) AND !empty($_POST['totQty'])) {


			$this->max_no = $this->utility->get_max_no("m_barcode_print_sum", "nno");

			$cl=$this->sd['cl'];
			$bc=$this->sd['branch'];
			$hno=$this->max_no;
			$supCd=$_POST['supCd'];
			$lGrnNo=$_POST['lGrnNo'];
			$Date=$_POST['date'];
			$totItms=$_POST['totItms'];
			$totQty=$_POST['totQty'];

			$pr_name = (isset($_POST['pr_name']))?1:0;
			$pr_icode = (isset($_POST['pr_icode']))?1:0;
			$pr_btcno = (isset($_POST['pr_btcno']))?1:0;
			$pr_price = (isset($_POST['pr_price']))?1:0;
			$pr_coscode= (isset($_POST['pr_coscode']))?1:0;
			$pr_comlogo = (isset($_POST['pr_comlogo']))?1:0;
			$model = (isset($_POST['model']))?1:0;

			$sql="INSERT INTO `m_barcode_print_sum` (`cl`,`bc`,`nno`,`supplier`,`grn_no`,`ddate`,`tot_item`,`tot_itm_qty`, `pr_name`, `pr_icode`, `pr_btcno`, `pr_price`, `pr_comlogo`,`model`,pr_coscode)
			VALUES('".$cl."', '".$bc."', '".$hno."', '".$supCd."', '".$lGrnNo."', '".$Date."', '".$totItms."', '".$totQty."','".$pr_name."','".$pr_icode."','".$pr_btcno."','".$pr_price."','".$pr_comlogo."','".$model."','".$pr_coscode."')";
			$query=$this->db->query($sql);

			for ($x = 0; $x < $_POST['rCount']; $x++) {
				if (isset($_POST['0_' . $x])) {
					if (!empty($_POST['0_' . $x])) {

						$m_barcode_print_det[] = array(
							"cl" => $this->sd['cl'],
							"bc" => $this->sd['branch'],
							"nno" => $hno,
							"item_id" => $_POST['0_' . $x],
							"batch" => $_POST['btcno_' . $x],	
							"sel_pr" => $_POST['selPr_' . $x],
							"cos_code" => $_POST['cosPr_' . $x],
							"qty" => $_POST['qty_' . $x],
							"serials"=>$_POST['all_serial_' . $x],
							);
					}
				}
			}

			if (isset($m_barcode_print_det)) {
				if (count($m_barcode_print_det)) {
					$this->db->insert_batch("m_barcode_print_det", $m_barcode_print_det);
				}
			}

			//$this->db->join('r_color', 'r_color.code = m_barcode_print_det.color', 'left');
			$this->db->join('m_item', 'm_item.code = m_barcode_print_det.item_id', 'left');
			$this->db->join('m_barcode_print_sum', 'm_barcode_print_sum.nno = m_barcode_print_det.nno AND m_barcode_print_sum.bc = m_barcode_print_det.bc AND m_barcode_print_sum.cl = m_barcode_print_det.cl', 'left');
			$this->db->where('m_barcode_print_sum.cl', $this->sd['cl']);
			$this->db->where('m_barcode_print_sum.bc', $this->sd['branch']);
			$this->db->where('m_barcode_print_sum.nno', $hno);
			$this->db->select("m_barcode_print_det.*, m_item.`description`,`pr_name`,`pr_icode`,`pr_btcno`,`pr_price`,pr_coscode,`pr_comlogo`,m_item.`model`,m_barcode_print_sum.`model` as md");
			$r_detail['det'] =$this->db->get('m_barcode_print_det')->result_array();

			if($_POST['ptype']=='l'){
				$this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
			}else{
				$this->load->view($_POST['by'] . '_small_' . 'pdf', $r_detail);
			}
		}else{
			echo "<script>alert('No Data to Print');window.close();</script>";
		}
	}


	public function item_selection_list(){


		if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

		$sql = "SELECT i.`code`,i.`description`,i.`model`,ib.purchase_price AS cost,'Cost Pr.Code' AS cp_cd,ib.`max_price` , IFNULL(SUM(cs.`qty`),0) AS aqty,cs.batch_no FROM `m_item` i
		JOIN t_item_batch ib ON ib.`item`=i.`code` 
		JOIN (SELECT * FROM `qry_current_stock`  WHERE `cl`='".$this->sd['cl']."' AND `bc`='".$this->sd['branch']."' ) cs ON cs.`item`=i.`code` AND cs.`batch_no`=ib.`batch_no`
		WHERE (i.description LIKE '%$_POST[search]%' OR i.code LIKE '%$_POST[search]%' OR i.model LIKE '%$_POST[search]%')";
		$sql .=(!empty($_POST['supCd']))?"  AND i.`supplier`='".$_POST['supCd']."'":"";
		$sql .= "GROUP BY i.`code`,ib.`batch_no` LIMIT 25";
		//Having aqty>0 // Remove for Inquary - 6957



		// $sql = "SELECT * FROM m_item  WHERE description LIKE '%$_POST[search]%' OR code LIKE '%$_POST[search]%' LIMIT 25";

		$query = $this->db->query($sql);
		$a  = "<table id='item_list' style='width : 100%' >";
		$a .= "<thead><tr>";
		$a .= "<th class='tb_head_th'>Code</th>";
		$a .= "<th class='tb_head_th'>Description</th>";
		$a .= "<th class='tb_head_th'>Model</th>";		
		$a .= "<th class='tb_head_th'>Batch No</th>";		
		$a .= "<th class='tb_head_th'>Price</th>";		
		$a .= "<th class='tb_head_th'>Current Qty</th>";		
		$a .= "</thead></tr><tbody><tr class='cl' style='visibility: hidden;' ><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

		foreach($query->result() as $r){
			$a .= "<tr class='cl'>";
			$a .= "<td>".$r->code."</td>";
			$a .= "<td>".$r->description."</td>";
			$a .= "<td>".$r->model."</td>";
			$a .= "<td style='text-align: right;'>".$r->batch_no."</td>";
			$a .= "<td style='text-align: right;'>".$r->max_price."</td>";
			$a .= "<td style='text-align: right;'>".$r->aqty."</td>";
			$a .= "<td style='text-align: right; display: none;' >".$r->cost."</td>";

			$a .= "</tr>";
		}
		$a .= "</tbody></table>";
		echo $a;
	}    


	public function load(){

		$hno=$_POST['id'];
		$typ=$_POST['typ'];
		$a['er']="2";

		if ($typ=='grn') {
			$a['er']="";
			$a['det']=$this->db->query("SELECT 
				gs.`nno` AS nno,
				gs.`supp_id` AS sup_id,
				s.`name` AS sup_des,
				gd.`code` AS itmId,
				i.`description` AS itmNm,
				i.`model` AS itmMod,
				gd.`price` AS cost,
				'coPrCod',
				gd.`batch_no` AS batch_no,	
				gd.`max_price` AS selPr,
				gd.`qty` AS qty
				FROM
				`t_grn_det` gd
				LEFT JOIN `t_grn_sum` gs
				ON gd.`cl` = gs.`cl`
				AND gd.`bc` = gs.`bc`
				AND gd.`nno` = gs.`nno`
				LEFT JOIN `m_supplier` s
				ON gs.`supp_id` = s.`code`
				LEFT JOIN `m_item` i
				ON  i.`code` = gd.`code`
				WHERE gs.`cl`='".$this->sd['cl']."' AND gs.`bc`='".$this->sd['branch']."' AND gs.`nno`='".$hno."'
				")->result();
		}else{//clrdes_

			$a['er']="";
			$a['det']=$this->db->query("SELECT 
				bpd.`nno` AS nno,
				bps.`supplier` AS sup_id,
				bps.`pr_name`,
				bps.`pr_price`,
				bps.`pr_coscode`,
				bps.`pr_btcno`,
				bps.`pr_comlogo`,
				bps.`pr_icode`,
				s.`name` AS sup_des,
				bpd.`item_id` AS itmId,
				i.`description` AS itmNm,
				i.`model` AS itmMod,
				i.`purchase_price` AS cost,
				bpd.`cost_pr_code` AS coPrCod ,
				bpd.`batch` AS batch_no ,				
				bpd.`color` AS clrCd ,	
				bpd.company,
				bpd.`sel_pr` AS selPr,
				bpd.`qty` AS qty,
				bpd.`serials`,
				bpd.cos_code
				FROM
				`m_barcode_print_sum` bps 
				LEFT JOIN `m_barcode_print_det` bpd 
				ON bpd.`nno` = bps.`nno` 
				LEFT JOIN `m_item` i
				ON i.`code` = bpd.`item_id` 
				LEFT JOIN `m_supplier` s 
				ON  bps.`supplier`= s.`code`
				WHERE bps.`cl`='".$this->sd['cl']."' AND bps.`bc`='".$this->sd['branch']."' AND bps.`nno`='".$hno."'
				")->result();

		}

		// var_dump($a);exit();

		echo json_encode($a);
		// itmId
		// itmNm
		// itmMod
		// cost
		// coPrCod
		// selPr
		// qty


	}

	

	public function DelOrClearHis(){

		$id=$_POST['id'];
		$_POST['dlWht']=(isset($_POST['dlWht']))?$_POST['dlWht']:'0';

		if ($_POST["dlWht"]=="ClHis") {
			$this->db->empty_table("m_barcode_print_det");
			$this->db->empty_table("m_barcode_print_sum");
			echo "History Cleared Successfully";
		} else {
			$this->db->delete('m_barcode_print_det', array('nno' => $id)); 
			$this->db->delete('m_barcode_print_sum', array('nno' => $id)); 
			echo "Successfully Deleted";			
		}
		

	}

	
	public function is_batch_item() {
		$this->db->select(array("batch_no", "qty"));
		$this->db->where("cl", $this->sd['cl']);
		$this->db->where("bc", $this->sd['branch']);
		$this->db->where("item", $_POST['code']);
		$this->db->where("qty >", "0");
		$query = $this->db->get("qry_current_stock");

		if ($query->num_rows() == 1) {
			foreach ($query->result() as $row) {
				echo $row->batch_no . "-" . $row->qty;
			}
		} else if ($query->num_rows() > 0) {
			echo "1";
		} else {
			echo "0";
		}
	}


	public function batch_item() {
		$sql = "SELECT qry_current_stock.`qty`,
		qry_current_stock.`batch_no`,
		t_item_batch.`purchase_price` AS cost,
		t_item_batch.`min_price` AS min,
		t_item_batch.`max_price` AS max		

		FROM qry_current_stock 
		JOIN m_item ON qry_current_stock.`item`=m_item.`code` 
		JOIN t_item_batch ON t_item_batch.`item` = m_item.`code` AND t_item_batch.`batch_no`= qry_current_stock.`batch_no`
		WHERE qry_current_stock.`qty`>'0'
		AND qry_current_stock.`item`='$_POST[search]' 
		AND qry_current_stock.cl = '".$this->sd['cl']."' 
		AND qry_current_stock.bc = '".$this->sd['branch']."'
		group by t_item_batch.`batch_no`";

		$query = $this->db->query($sql);

		$a = "<table id='batch_item_list' style='width : 100%' >";

		$a .= "<thead><tr>";
		$a .= "<th class='tb_head_th'>Batch No</th>";
		$a .= "<th class='tb_head_th'>Available Qty</th>";
		$a .= "<th class='tb_head_th'>Max Price</th>";
		$a .= "<th class='tb_head_th'>Min Price</th>";
		$a .= "<th class='tb_head_th'>Cost</th>";



		$a .= "</thead></tr>";
		$a .= "<tr class='cl'>";
		$a .= "<td>&nbsp;</td>";
		$a .= "<td>&nbsp;</td>";
		$a .= "<td>&nbsp;</td>";
		$a .= "<td>&nbsp;</td>";
		$a .= "<td>&nbsp;</td>";



		$a .= "</tr>";
		foreach ($query->result() as $r) {
			$a .= "<tr class='cl'>";
			$a .= "<td>" . $r->batch_no . "</td>";
			$a .= "<td>" . $r->qty . "</td>";
			$a .= "<td style='text-align:right;'>" . $r->max . "</td>";
			$a .= "<td style='text-align:right;'>" . $r->min . "</td>";
			$a .= "<td style='text-align:right;'>" . $r->cost . "</td>";

			$a .= "</tr>";
		}
		$a .= "</table>";

		echo $a;
	}


	public function f1_selection_list_load_grn(){


		$table         = $_POST['data_tbl'];
		$field         = isset($_POST['field'])?$_POST['field']:'code';
		$field2        = isset($_POST['field2'])?$_POST['field2']:'description';
		$hid_field     = isset($_POST['hid_field'])?$_POST['hid_field']:"0";
		$add_query     = isset($_POST['add_query'])?$_POST['add_query']:"";
		$preview_name1 = isset($_POST['preview1'])?$_POST['preview1']:'Code';
		$preview_name2 = isset($_POST['preview2'])?$_POST['preview2']:'Description';

		if (isset($_POST['chkClBc'])) {
			$add_query = "AND `cl`='".$this->sd['cl']."' AND `bc`='".$this->sd['branch']."' ".$add_query;       
		}

		if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
		if($add_query!=""){
			$sql = "SELECT * FROM $table  WHERE ($field2 LIKE '%$_POST[search]' OR $field LIKE '%$_POST[search]%' ) ".$add_query." LIMIT 25";
		}else{
			$sql = "SELECT * FROM $table  WHERE $field2 LIKE '%$_POST[search]' OR $field LIKE '%$_POST[search]%' LIMIT 25";
		}
		$query = $this->db->query($sql);
		$a  = "<table id='item_list' style='width : 100%' >";
		$a .= "<thead><tr>";
		$a .= "<th class='tb_head_th'>".$preview_name1."</th>";
		$a .= "<th class='tb_head_th' colspan='2'>".$preview_name2."</th>";
		$a .= "</thead></tr><tr class='cl' style='visibility: hidden;'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

		foreach($query->result() as $r){
			$a .= "<tr class='cl'>";
			$a .= "<td>".$r->{$field}."</td>";
			$a .= "<td>".$r->{$field2}."</td>";
			if($hid_field!="0"){
				$a .= "<td><input type='hidden' class='code_gen' value='".$r->{$hid_field}."' title='".$r->{$hid_field}."' /></td>";        
			}
			$a .= "</tr>";
		}
		$a .= "</table>";
		echo $a;
	}



}