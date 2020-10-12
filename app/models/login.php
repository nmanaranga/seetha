<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class login extends CI_Model {
	private $mtb;
	private $sd;

	function __construct(){
		parent::__construct();

		$this->sd = $this->session->all_userdata();
		$this->mtb = $this->tables->tb['m_branch'];

	}

	public function base_details(){
		$this->load->model('s_company');
		$a['company'] = $this->s_company->get_company_name();
		$a['cluster']=$this->loder->select();

		return $a;
	}

	public function get_subitem_data(){
		$this->db->where('code', $_POST['cluster']);
		$this->db->limit(1);

		echo json_encode($this->db->get($this->mtb)->first_row());
	}


	public function select(){

		$sql2="SELECT cCode FROM s_users WHERE loginName = '".$_POST['username']."'";
		$query2 = $this->db->query($sql2)->row();    

		$user_code = $query2->cCode;

		$sql=" SELECT u.`bc`,  m.`name`
		FROM u_branch_to_user u
		JOIN m_branch m ON m.`bc` = u.bc
		WHERE u.`is_active`='1' AND u.cl='".$_POST['cluster']."'";

		if ($user_code!=0) {
			$sql.=" AND m.inactive='0' AND user_id='$user_code'";
		}

		$sql.=" GROUP BY u.bc";

		$query = $this->db->query($sql);  

		//$query = $this->db->get_where($this->mtb,array('cls'=>$_POST['cluster']));

		$s = "<select name='branch' id='company' class='form-control'>";
		$s .= "<option value='0'>--Select Branch--</option>";
		foreach($query->result() as $r){

			$s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc." - ".$r->name."</option>";
		}
		$s .= "</select>";
		$data['result']=$s;
		echo json_encode($data);

	}


	public function select_cluster(){

		$sql2="SELECT cCode FROM s_users WHERE loginName = '".$_POST['username']."'";
		$query2 = $this->db->query($sql2);    
		if($query2->num_rows()>0){
			$user_code = $query2->row()->cCode;
		}else{
			$user_code="";
		}


		$sql="SELECT u.`cl`,  m.`description`
		FROM u_branch_to_user u
		JOIN m_cluster m ON m.`code` = u.`cl`";
		if ($user_code!=0) {
			$sql.="WHERE u.`is_active`='1' AND user_id='$user_code'";
		}
		$sql.=" GROUP BY u.`cl`";

		$query = $this->db->query($sql);  
		if($query->num_rows()>0){
			$s = "<select name='cluster' id='cl' class='form-control'>";
			$s .= "<option value='0'>--Select Cluster--</option>";
			foreach($query->result() as $r){
				$s .= "<option title='".$r->description."' value='".$r->cl."'>".$r->cl." - ".$r->description."</option>";
			}
			$s .= "</select>";
		}else{
			$s = "<select name='cluster' id='cl' class='form-control'>";
			$s .= "<option value='0'>--Select Cluster--</option>";
			$s .= "</select>";
		}      
		$data['result']=$s;
		echo json_encode($data);

	}

}