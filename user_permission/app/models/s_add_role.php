    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class s_add_role extends CI_Model {
    
    private $sd;
    private $mtb;
    private $mtbl;
    private $mtbl2;
    private $mod = '019';
    
    function __construct(){
    	parent::__construct();
    	$this->load->library('session');
        $this->load->library('useclass');
        $this->sd = $this->session->all_userdata();
    	$this->mtbl = $this->tables->tb['u_add_user_role'];
        $this->mtbl2 = $this->tables->tb['u_add_user_role_log'];
    	$this->mtb = $this->tables->tb['u_user_role'];
    	$this->load->model('user_permissions');
    }
    
    public function base_details(){
    	$this->load->model('s_users');
        $a['table_data'] = $this->data_table();
        $a['table_data_new'] = $this->data_table_new();
    	$a['user'] = $this->s_users->select();
    	return $a;
    }
    
    public function data_table(){
       $this->db->where('is_active',1);
	   return $this->db->get($this->mtb)->result();
    }
    
    public function data_table_new(){
	   return $this->db->get($this->mtbl)->result();
    }
    
    private function delete(){  
       /* $this->db->where("cl", $this->sd['up_cl']); 
    	$this->db->where("bc", $this->sd['up_branch']);*/
    	$this->db->where("user_id", $this->input->post('loginName'));	
        $this->db->delete($this->mtbl); 
    }
    
    public function save(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 
            if($this->user_permissions->is_add('s_add_role')){
                $this->delete();    
            	$a = array();
            	foreach($this->data_table() as $r){
                    if($this->input->post('is_active'.$r->role_id)==1){
                        $a = array(
                            "user_id"=>$this->input->post('loginName'),
                            "date_from"=>$this->input->post('date_to'.$r->role_id),
                            "date_to"=>$this->input->post('date_from'.$r->role_id),
                            "role_id"=>$this->input->post('role_id'.$r->role_id),
                            "bc"=>$this->sd['up_branch'],
                            "cl"=>$this->sd['up_cl']
                        );
                        $this->db->insert($this->mtbl, $a);
                        $this->db->insert($this->mtbl2, $a);
                    }
            	}
            	echo $this->db->trans_commit();
            }else{
                echo "No permission to save records";
                $this->db->trans_commit();
            }
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo"<script>alert('Operation fail please contact admin');history.go(-1);</script>";
        }       
    }
    
    public function dates(){
    	$t = time(); 
        $a = array(); 
        $x = 0;
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
    
    public function load(){
    	$q = $this->db->where('user_id', $this->input->post('id'))->get($this->mtbl);
    	echo json_encode($q->result());
    }
}