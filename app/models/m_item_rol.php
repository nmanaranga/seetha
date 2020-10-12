

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_item_rol extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
	$this->mtb = $this->tables->tb['m_item_rol'];
    $this->load->model('user_permissions');

    }
    
    public function base_details(){
	$a['branch']=$this->get_branch();
	return $a;
    }
    

    

	
    public function save(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {
            if($this->user_permissions->is_add('m_item_rol')){

                $_POST['cl']=$this->sd['cl'];
                $_POST['branch']=$this->sd['branch'];
                $_POST['oc']=$this->sd['oc']; 

        	    for($x = 0; $x<12; $x++){  
        		    if(isset($_POST['0_'.$x])){
    				    if($_POST['0_'.$x] != "" && $_POST['n_'.$x] !=""){
    						$b[]= array(
    							"cl"=>$this->sd['cl'],
    							"bc"=>$this->sd['branch'],
    						    "code"=>$_POST['0_'.$x],
    						    "rol"=>$_POST['3_'.$x],
    						    "roq"=>$_POST['4_'.$x],
                                "oc"=>$this->sd['oc']
    						);				
    				    }
    				}
    			}
        		$this->set_delete();
                if(isset($b)){
        		    if(count($b)){ $this->db->insert_batch("m_item_rol", $b );}                    
                }else{
                   $this->db->query("DELETE  FROM `m_item_rol`");
                }
                echo $this->db->trans_commit();
            }else{
                echo "No permission to save records";
                $this->db->trans_commit();
            }
        } catch ( Exception $e ) { 
            $this->db->trans_rollback();
            echo $e->getMessage()."Operation fail please contact admin"; 
        }

 }

  	private function set_delete(){
		$this->db->where("bc", $_POST['bc']);
		$this->db->delete("m_item_rol");
    }
    
    
    public function check_code(){
	$this->db->where('bc', $_POST['bc']);
	$this->db->limit(1);
	
	echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function load(){
    	$this->db->select(array('m_item.code','m_item.model','m_item.description','m_item_rol.cl','m_item_rol.bc','m_item_rol.rol','m_item_rol.roq'));
        $this->db->join('m_item', 'm_item.code= m_item_rol.code');
        $this->db->where('bc', $_POST['bc']);
        $query=$this->db->get($this->mtb);
		$a['c'] = $query->result();	
		echo json_encode($a);
    }
    
public function delete(){

    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try { 


    	if($p->is_delete){
    	    $this->db->where('bc', $_POST['code']);
    	    $this->db->limit(1);
    	    
    	    $this->db->delete($this->mtb);
    	}else{
    	    echo 2;
    	}
        echo $this->db->trans_commit();
    }catch ( Exception $e ) { 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
    }
}
    
    public function select(){
        $query = $this->db->get($this->mtb);
        
        $s = "<select name='sales_ref' id='sales_ref'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code." | ".$r->name."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }

    public function item_list_all(){
        if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
               
        $sql = "SELECT * FROM m_item WHERE description LIKE '$_POST[search]%' OR code LIKE '$_POST[search]%' AND inactive='1' LIMIT 25";
        $query = $this->db->query($sql);
        
        $a = "<table id='item_list' style='width : 100%' border='0'>";
        
       $a .= "<thead><tr>";
            $a .= "<th class='tb_head_th'>Code</th>";
            $a .= "<th class='tb_head_th'>Item Name</th>";
            $a .= "<th class='tb_head_th'>Model</th>";
            $a .= "<th class='tb_head_th'>Re-Order Level</th>";
            $a .= "<th class='tb_head_th'>Re-Order Quantity</th>";
       
        $a .= "</thead></tr>
                <tr class='cl'>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
        ";
            foreach($query->result() as $r){
                $a .= "<tr class='cl'>";
                    $a .= "<td>".$r->code."</td>";
                    $a .= "<td>".$r->description."</td>";
                    $a .= "<td>".$r->model."</td>";
                    $a .= "<td>".$r->rol."</td>";
                    $a .= "<td>".$r->roq."</td>";
                    $a .= "</tr>";
            }
        $a .= "</table>";
        
        echo $a;
    }


    public function get_branch(){
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);   
        $result=$this->db->get("m_branch")->result_array();
        
        return $result; 
    }

    public function get_item(){
        $this->db->select(array('code','description','model','rol','roq'));
        $this->db->where("code",$this->input->post('code'));
        $this->db->limit(1);
        $query=$this->db->get('m_item');
        if($query->num_rows() > 0){
            $data['a']=$query->result();
        }else{
            $data['a']=2;
        }
        
        echo json_encode($data);
    }
}