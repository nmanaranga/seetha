    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class s_permission_level_order extends CI_Model {
    
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
    	$this->mtb = $this->tables->tb['s_permission_level_sum'];
    	$this->load->model('user_permissions');
    }



    
    public function base_details(){
    	$this->load->model('s_users');
        $this->load->model('utility');
        $a['table_data'] = $this->data_table();
        return $a;
    }


    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 100px;");
        $des  = array("data"=>"Description", "style"=>"");
        $action = array("data"=>"Action", "style"=>"width: 100px;");
        
        $this->table->set_heading($code, $des, $action);//, $phone
        
        $this->db->select(array('code', 'description'));
        $query = $this->db->get('s_permission_level_sum');
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('s_users')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
            $code = array("data"=>$r->code, "value"=>"code");
            $description = array("data"=>$r->description);
            $action = array("data"=>$but, "style"=>"text-align: center;");
            $this->table->add_row($code, $description, $action);
        }
        return $this->table->generate();
    }
    

    
    public function delete(){ 
        $this->load->model('user_permissions');
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {
            if($this->user_permissions->is_delete('s_permission_level_order')){
                $this->db->where("code", $this->input->post('code')); 
                $this->db->delete('s_permission_level_det'); 

                $this->db->where("code", $this->input->post('code')); 
                $this->db->delete('s_permission_level_sum'); 

                $this->utility->save_logger("DELETE",46,$_POST['code'],$this->mod);
                echo $this->db->trans_commit();
            }else{
                echo "No permission to delete records";
                $this->db->trans_commit();
            }
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo $e->getMessage()." Operation fail please contact admin"; 
        }   
    }
    
    public function save(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 
            if(!isset($_POST['all_role'])){ $_POST['all_role'] = 0; }
            if(!isset($_POST['is_active'])){ $_POST['is_active'] = 0; }

            $s_permission_level_sum=array(
                    'code'=>$_POST['code'],
                    'name'=>$_POST['c_name'],
                    'description'=>$_POST['description'],
                    'is_check_all_role'=>$_POST['all_role'],
                    'is_active'=>$_POST['is_active'],
                    'oc'=>$this->sd['up_oc']
                   
            );

  
            for($x = 0; $x < 10; $x++) {
                if (isset($_POST['rolid_' . $x])) {
                    if ($_POST['rolid_' . $x] != "") {
                        $p_order_det[] = array(
                            "code" => $_POST['code'],
                            "role_id" => $_POST['rolid_' . $x],
                            "role_description" => $_POST['rolname_' . $x],
                            "num" => $_POST['no_'.$x]
                        );
                     }
                 }
             }
           

            if($_POST['code_']=="0" || $_POST['code_']==""){
                if($this->user_permissions->is_add('s_permission_level_order')){
                    $this->db->insert('s_permission_level_sum', $s_permission_level_sum);
                    if (isset($p_order_det)) {
                        if (count($p_order_det)) {
                            $this->db->insert_batch("s_permission_level_det", $p_order_det);
                        }
                    }
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }
            }else{
                if($this->user_permissions->is_edit('s_permission_level_order')){
                    $this->db->where("code", $_POST['code']);
                    $this->db->update('s_permission_level_sum', $s_permission_level_sum);

                    $this->db->where("code", $_POST['code']);   
                    $this->db->delete('s_permission_level_det'); 

                    if (isset($p_order_det)) {
                        if (count($p_order_det)) {
                            $this->db->insert_batch("s_permission_level_det", $p_order_det);
                        }
                    }
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to edit records";
                    $this->db->trans_commit();
                }
            }
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo $e->getMessage();
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
        if($this->db->where('code',$this->input->post('code'))->get('s_permission_level_sum')->num_rows()>0){
            $a['sum']=$this->db->where('code',$this->input->post('code'))->get('s_permission_level_sum')->result();
        }else{
            $a['sum']=2;
        }

        if($this->db->where('code',$this->input->post('code'))->get('s_permission_level_det')->num_rows()>0){
            $a['det']=$this->db->where('code',$this->input->post('code'))->get('s_permission_level_det')->result();
        }
        echo json_encode($a);
    }

    public function roll_list_all(){
        $sql="SELECT role_id,description
              FROM u_user_role
              WHERE description LIKE '%$_POST[search]%' OR role_id LIKE '%$_POST[search]%' 
              ";
        
        $query=$this->db->query($sql);

        $a  = "<table id='user_list' style='width:100%' >";
        $a .= "<thead><tr>";
        $a .= "<th class='tb_head_th'>Code</th>";
        $a .= "<th class='tb_head_th'>Description</th>";
        $a .= "</thead></tr>";
        $a .= "<tr class='cl'>";
        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>";
        $a .= "</tr>";

        foreach($query->result() as $r){
            $a .= "<tr class='cl'>";
            $a .= "<td style='cursor: pointer;'>".$r->role_id."</td>";
            $a .= "<td style='cursor: pointer;'>".$r->description."</td>";
            $a .= "</tr>";
        }
        $a .= "</table>";
        
        echo $a;
    }
}