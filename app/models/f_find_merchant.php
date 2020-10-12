<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class f_find_merchant extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
	$this->mtb = $this->tables->tb['m_item_rol'];
    }
    
    public function base_details(){
	$a['branch']=$this->get_branch();
	return $a;
    }
    
    public function save(){
            $this->db->trans_start();

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
			if(count($b)){ echo $this->db->insert_batch("m_item_rol", $b );}
			
            $this->db->trans_complete();
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
	$p = $this->user_permissions->get_permission($this->mod, array('is_delete'));
	
	if($p->is_delete){
	    $this->db->where('bc', $_POST['code']);
	    $this->db->limit(1);
	    
	    echo $this->db->delete($this->mtb);
	}else{
	    echo 2;
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
    	$cl=$this->sd['cl'];
    	$bc=$this->sd['branch'];

        if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
               
        $sql = "SELECT r.`bank_id`, 
                        b.`description`,
                        r.`merchant_id` 
                FROM r_credit_card_rate r
                JOIN m_bank b ON b.`code` = r.`bank_id`
                WHERE (bank_id LIKE '%$_POST[search]%' 
                           OR description LIKE '%$_POST[search]%' 
                           OR merchant_id LIKE '%$_POST[search]%')
                GROUP BY merchant_id
				LIMIT 50";
        $query = $this->db->query($sql);
        $a = "<table id='item_list' style='width : 100%' border='0'>";
            foreach($query->result() as $r){
                $a .= "<tr class='cl'>";
                    $a .= "<td style='width:100px;'><input type='text' readonly='readonly' class='g_input_txt fo' id='' name='' value='".$r->bank_id."' title='".$r->bank_id."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td><input type='text' readonly='readonly' class='g_input_txt' value='".$r->description."' title='".$r->description."' style='border:dotted 1px #ccc; background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:120px'><input type='text' readonly='readonly' class='g_input_txt ' id='' name='' value='".$r->merchant_id."' title='".$r->merchant_id."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
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