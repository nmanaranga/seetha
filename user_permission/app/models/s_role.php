<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class s_role extends CI_Model {

	private $sd;
	private $mtb;
	private $tb_sum;
	private $tb_det;
	private $mod = '018';

	function __construct(){
		parent::__construct();
		$this->sd = $this->session->all_userdata();
		$this->mtb = $this->tables->tb['u_modules'];
		$this->tb_det = $this->tables->tb['u_user_role_detail'];
		$this->tb_sum = $this->tables->tb['u_user_role'];
		$this->load->model('user_permissions');
		$this->load->model('s_module');
	}

	public function base_details(){
		$a['table_data'] = $this->data_table();
		$a['def_mod']=$this->s_module->default_category();
		return $a;
	}

	public function check_role(){
		$q = $this->db->where('role_id', $this->input->post('code'))->get($this->tb_sum);
		echo $q->num_rows;
	}

	public function data_table(){
		return $this->db->get($this->mtb)->result();
	}

	public function save(){
		$this->db->trans_begin();
		if(!isset($_POST['is_active'])){ $_POST['is_active'] = 0; }
		error_reporting(E_ALL); 
		function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
			throw new Exception($errMsg); 
		}
		set_error_handler('exceptionThrower'); 
		try { 
			if($this->user_permissions->is_add('s_role')){
				$a_sum = array(
					"role_id"=>$this->input->post('code'),
					"description"=>$this->input->post('des'),
					"is_active"=>$_POST['is_active'],
					"oc"=>$this->sd['up_oc'],
					"category"=>$_POST['package']
					);

				$this->set_delete();
				$this->db->insert($this->tb_sum, $a_sum);
				$lid = $this->db->insert_id();
				$a_det = array();
				$x=0;
				foreach($this->data_table() as $r){
					if(isset($_POST['m_code_'.$x])){
						$a_det[] = array(
							"id"=>$lid,
							"role_id"=>$this->input->post('code'),
							"module_id"=>$this->input->post('m_code_'.$x),
							"module_name"=>$this->input->post('m_name_'.$x),
							"is_view"=>$this->input->post('is_view_'.$x),
							"is_add"=>$this->input->post('is_add_'.$x),
							"is_edit"=>$this->input->post('is_edit_'.$x),
							"is_delete"=>$this->input->post('is_delete_'.$x),
							"is_approve"=>$this->input->post('is_approve_'.$x),
							"is_print"=>$this->input->post('is_print_'.$x),
							"is_re_print"=>$this->input->post('is_re_print_'.$x),
							"is_back_date"=>$this->input->post('is_back_date_'.$x),
							);
					}
					$x++;
				}
				//var_dump($a_det);
				if(count($a_det)){ $this->db->insert_batch($this->tb_det, $a_det); }
				echo $this->db->trans_commit();
			}else{
				echo "No permission to save records";
				$this->db->trans_commit();
			}
		}catch( Exception $e ){ 
			$this->db->trans_rollback();
			echo "Operation fail please contact admin"; 
		} 

	}



	public function auto_com(){
		$this->db->like("role_id", $_GET['q']);
		$this->db->or_like("description", $_GET['q']);
		$this->db->limit(25);
		
		foreach($this->db->get($this->tb_sum)->result() as $r){
			echo $r->id."|".$r->role_id."|".$r->description."\n";
		}
	}

	public function dates(){
		$t = time(); $a = array(); $x = 0;
		foreach($this->db->get($this->mtb)->result() as $r){
			$std = new stdClass;
			if($r->type == 2){ $r->range *= 7; }
			$tt = $t - ($r->range * 84600);
			$std->date = date("Y-m-d", $tt);
			$std->description = $r->description;
			$std->key = substr(md5($r->description.$t), 0, 5);
			$a[] = $std; $x++;
		}
		return $a;
	}

	private function set_delete(){
		$sql="DELETE u_user_role_detail 
		FROM u_user_role_detail
		JOIN u_modules m ON m.`m_code` = u_user_role_detail.`module_id`
		WHERE package='".$_POST['package']."' 
		AND role_id='".$_POST['code']."'";

		$this->db->query($sql);	  

		// $this->db->where('role_id', $this->input->post('code'))->delete($this->tb_det);
		$this->db->where('role_id', $this->input->post('code_'))->delete($this->tb_sum);
	}

	public function load(){
		$q2=$this->db->where('role_id',$this->input->post('id'))->get($this->tb_sum);
		$category=$q2->row()->category;


    	// $a['data_tbl']=$this->module_det($category);
		$a['data_tbl']=$this->module_det($_POST['pack']);

		$a['sum']=$q2->result();

     	/*$this->db->order_by('module_id','asc');
     	$q = $this->db->where('role_id', $this->input->post('id'))->get($this->tb_det);*/
     	$sql="SELECT u.*, m.`package` FROM u_user_role_detail u
     	JOIN u_modules m ON m.`m_code` = u.`module_id`
     	
     	WHERE package='".$_POST['pack']."' AND role_id='".$_POST['id']."' ";
     	
		if ($this->sd['oc']!='0' && $this->sd['name']!='SuperAdmin') {

			$sql.="AND m.is_active='1' AND m.is_special='0'";
		}

     	$sql.=" ORDER BY module_id asc";
     	$q=$this->db->query($sql);
     	$a['det']=$q->result();		
     	echo json_encode($a);
     }

     public function load_module_det(){
     	$html="";
     	$this->db->where('package',$this->input->post('package'));
     	$this->db->order_by("m_code", "desc"); 
     	$query=$this->db->get($this->mtb)->result();
     	echo json_encode($query);   	
     }


     public function module_det($category){
     	$html="";
     	$this->db->where('package',$category);
     	$this->db->order_by("m_code", "desc"); 
     	return $this->db->get($this->mtb)->result();	
     }

     public function load_user(){


     	if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}


     	$sql = "SELECT * FROM u_user_role  WHERE role_id LIKE '%$_POST[search]%' OR description LIKE '%$_POST[search]%' LIMIT 25";


     	$query = $this->db->query($sql);
     	$a = "<table id='item_list' style='width : 100%' >";
     	$a .= "<thead><tr>";
     	$a .= "<th class='tb_head_th'>Code</th>";
     	$a .= "<th class='tb_head_th' colspan='2'>Description</th>";
     	$a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

     	foreach($query->result() as $r){
     		$a .= "<tr class='cl'>";
     		$a .= "<td>".$r->role_id."</td>";
     		$a .= "<td>".$r->description."</td>";
     		$a .= "<td style='display:none'>".$r->is_active."</td>";        
     		$a .= "</tr>";
     	}
     	$a .= "</table>";
     	echo $a;

     }

     public function default_modules(){

     	$sql="SELECT def_use_seettu,def_use_hp,def_use_service,def_use_cheque,def_use_giftV FROM `def_option_sales`";
     	$query = $this->db->query($sql)->first_row();

     	$s = "<select name='package' id='package'>";
     	$s .= "<option value='0'>---</option>";

     	$s .= "<option value='1'>Master Form</option>";
     	$s .= "<option value='2'>Transactions</option>";
     	if($query->def_use_hp==1){
     		$s .= "<option value='3'>HP</option>";
     	}
     	$s .= "<option value='4'>Reports</option>";
     	$s .= "<option value='5'>Settings</option>";
     	$s .= "<option value='6'>Find</option>";
     	if($query->def_use_seettu==1){
     		$s .= "<option value='7'>Settu</option>";
     	}
     	if($query->def_use_cheque==1){
     		$s .= "<option value='8'>Cheques</option>";
     	}
     	$s .= "<option value='9'>User Permition</option>";
     	if($query->def_use_service==1){
     		$s .= "<option value='10'>Service</option>";
     	}
     	if($query->def_use_giftV==1){
     		$s .= "<option value='11'>Gift Voucher</option>";
     	}
     	if($query->def_use_hp==1){
     		$s .= "<option value='12'>HP Reports</option>";
     	}
     	if($query->def_use_seettu==1){
     		$s .= "<option value='13'>Seettu Reports</option>";
     	}
     	if($query->def_use_service==1){
     		$s .= " <option value='14'>Service Reports</option>";
     	}
     	if($query->def_use_cheque==1){
     		$s .= "<option value='15'>Cheque Reports</option>";
     	}
     	if($query->def_use_giftV==1){
     		$s .= "<option value='16'>Gift Voucher Reports</option>";
     	}

     	$s .= "</select>";

     	return $s;

     }
 }