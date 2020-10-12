<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class f_find_item_img extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
	
    }
    
    public function base_details(){
	$a['branch']=$this->get_branch();
	return $a;
    }
    
    public function save(){
    }

  	private function set_delete(){
    }
    
    
    public function check_code(){
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
    }
    
    public function select(){
    }

    public function item_list_all(){
    	$cl=$this->sd['cl'];
    	$bc=$this->sd['branch'];

        if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
               
        $sql = "SELECT `m_item`.`code`,
						`m_item`.`description` ,
						`m_item`.`model`, 
						`m_item`.`min_price`,
						`m_item`.`max_price`, 
				IFNULL (SUM(`qry_current_stock`.`qty`),0) AS qty,
				IFNULL (`qry_current_stock`.`batch_no`,0) AS batch_no
				FROM m_item 
				LEFT JOIN `qry_current_stock` ON `m_item`.`code`=`qry_current_stock`.`item`
				WHERE  
				`m_item`.`description` LIKE '$_POST[search]%' OR CODE LIKE '$_POST[search]%' AND 
				inactive='0' 
				GROUP BY `m_item`.`code`,`qry_current_stock`.`batch_no`
				LIMIT 25";
        $query = $this->db->query($sql);
        
        $a = "<table id='item_list' style='width : 100%' border='0'>";
        
       

            foreach($query->result() as $r){
                $a .= "<tr class='cl'>";
                    $a .= "<td style='width:100px;'><input type='text' readonly='readonly' class='g_input_txt fo' id='' name='' value='".$r->code."' title='".$r->code."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:250px'><input type='text' readonly='readonly' class='g_input_txt' value='".$r->description."' title='".$r->description."' style='border:dotted 1px #ccc; background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:90px'><input type='text' readonly='readonly' class='g_input_txt ' id='' name='' value='".$r->model."' title='".$r->model."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:70px'> <input type='text' readonly='readonly' class='g_input_num' id='' name='' value='".$r->min_price."' title='".$r->min_price."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:70px'><input type='text' readonly='readonly' class='g_input_num' id='' name='' value='".$r->max_price."' title='".$r->max_price."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:70px'><input type='text' readonly='readonly' class='g_input_num ' id='' name='' value='".$r->qty."' title='".$r->qty."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:70px'><input type='text' readonly='readonly' class='g_input_num ' id='' name='' value='".$r->batch_no."' title='".$r->batch_no."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;' /></td>";
                   
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

    public function load_img(){
        $item = $_POST['code'];

        $sql="SELECT * 
                FROM m_item_picture
                WHERE item_code='$item'";

        $query=$this->db->query($sql);

        if($query->num_rows()>0){
            $a=$query->result();
        }else{
            $a="2";
        }

        echo json_encode($a);
    }
}