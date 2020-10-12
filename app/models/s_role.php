<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class s_role extends CI_Model {
    
    private $sd;
    private $mtb;
    private $tb_sum;
    private $tb_det;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	
	$this->mtb = $this->tables->tb['u_modules'];
	$this->tb_det = $this->tables->tb['u_user_role_detail'];
	$this->tb_sum = $this->tables->tb['u_user_role'];
    }
    
    public function base_details(){
 //        $this->load->model('s_branch');
	// $a['table_data'] = $this->data_table();
 //        $a['branch'] = $this->s_branch->select();
	
	// return $a;
    }
	
// 	public function check_role(){
// 		$q = $this->db->where('role_id', $this->input->post('code'))
// 				->get($this->tb_sum);
		
// 		echo $q->num_rows;
// 	}
    
//     public function data_table(){
// 	return $this->db->get($this->mtb)->result();
//     }
    
//     public function save(){
//	   $_POST['code']=strtoupper($_POST['code']);
// 	$a_sum = array(
// 	    "role_id"=>$this->input->post('code'),
// 	    "description"=>$this->input->post('des'),
// 	    "bc"=>$this->input->post('bc'),
// 	    "oc"=>$this->sd['oc']
// 	);

// 	//if($this->input->post('code_') == "0"){
// 	  //  $this->db->insert($this->tb_sum, $a_sum);
// 	    //$lid = $this->db->insert_id();
// 	//}else{
// 	  //  $this->db->update($this->tb_sum, $a_sum);
// 	   // $lid = $this->input->post('code_');
	   

//             //$this->set_delete();
// 	//}
        
// 	//if($this->input->post('code_') == "0"){
//             $this->set_delete();
// 	    $this->db->insert($this->tb_sum, $a_sum);
// 	    $lid = $this->db->insert_id();
	

// //}else{
// 	  //  $this->db->update($this->tb_sum, $a_sum);
// 	   // $lid = $this->input->post('code_');
	   

//             //$this->set_delete();
// 	//}
	
// 	$a_det = array();
// 	foreach($this->data_table() as $r){
// 	    $a_det[] = array(
// 		"id"=>$lid,
// 		"role_id"=>$this->input->post('code'),
// 		"module_id"=>$this->input->post('m_code'.$r->m_code),
// 		"is_view"=>$this->input->post('is_view'.$r->m_code),
// 		"is_add"=>$this->input->post('is_add'.$r->m_code),
// 		"is_edit"=>$this->input->post('is_edit'.$r->m_code),
// 		"is_delete"=>$this->input->post('is_delete'.$r->m_code),
// 		"is_print"=>$this->input->post('is_print'.$r->m_code),
// 		"is_re_print"=>$this->input->post('is_re_print'.$r->m_code),
// 		"is_back_date"=>$this->input->post('is_back_date'.$r->m_code)
// 	    );
// 	}
	
    
        
// 	if(count($a_det)){ $this->db->insert_batch($this->tb_det, $a_det); }
	
// 	redirect(base_url()."?action=s_role");
//     }
    
//     public function auto_com(){
// 	$this->db->like("role_id", $_GET['q']);
// 	$this->db->or_like("description", $_GET['q']);
// 	$this->db->limit(25);
	
// 	foreach($this->db->get($this->tb_sum)->result() as $r){
// 	    echo $r->id."|".$r->role_id."|".$r->description."|".$r->bc."\n";
// 	}
//     }
    
//     public function dates(){
// 	$t = time(); $a = array(); $x = 0;
// 	foreach($this->db->get($this->mtb)->result() as $r){
// 	    $std = new stdClass;
	    
// 	    if($r->type == 2){ $r->range *= 7; }
// 	    $tt = $t - ($r->range * 84600);
	    
// 	    $std->date = date("Y-m-d", $tt);
// 	    $std->description = $r->description;
// 	    $std->key = substr(md5($r->description.$t), 0, 5);
	    
// 	    $a[] = $std; $x++;
// 	}
	
// 	return $a;
//     }
    
//     private function set_delete(){
// 	$this->db->where('id', $this->input->post('code_'))
// 		->delete($this->tb_sum);
       
        
//     }
    
//     public function load(){
// 	$q = $this->db->where('id', $this->input->post('id'))
// 		    ->get($this->tb_det);
	
// 	echo json_encode($q->result());
//     }
}