<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_req_sum_update extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
	$this->mtb = $this->tables->tb['m_items'];
    }
    
    public function base_details(){
	$a['cluster']=$this->sd['cl'];
	$a['branch']=$this->count_branches();
	return $a;
    }   
    
    

    public function save(){
	
    $p = $this->user_permissions->get_permission($this->mod, array('is_edit', 'is_add'));

        if($_POST['code_'] == "0" || $_POST['code_'] == ""){
        if($p->is_add){
		unset($_POST['code_']);
        echo $this->db->insert($this->mtb, $_POST);
        }else{
		echo 2;
	    }
        }else{
	    if($p->is_edit){
		$this->db->where("code", $_POST['code_']);
		unset($_POST['code_']);
		echo $this->db->update($this->mtb, $_POST);
	    }else{
		echo 3;
	    }
        }	
 }
    
    public function check_code(){
	$this->db->where('code', $_POST['code']);
	$this->db->limit(1);
	
	echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function load(){
	$this->db->where('code', $_POST['code']);
	$this->db->limit(1);
	
	echo json_encode($this->db->get($this->mtb)->first_row());
    }
    
public function delete(){
	$p = $this->user_permissions->get_permission($this->mod, array('is_delete'));
	
	if($p->is_delete){
	    $this->db->where('code', $_POST['code']);
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

    public function count_branches(){        
        $this->db->distinct();
        $this->db->select('BC');
        $this->db->where('cl',$this->sd['cl']);      
        $query = $this->db->get("t_req_det");
        return (float)$query->num_rows();   

    }

    public function num_of_branches(){        
        $this->db->distinct();
        $this->db->select('BC');
        $this->db->where('cl',$this->sd['cl']);    
        $this->db->where('Item',$_POST['item']);    
        $query = $this->db->get("t_req_det");
        echo json_encode($query->num_rows());   
        
    }


    public function load_data(){
        $_POST['cl']=$this->sd['cl'];
        $sql="SELECT `t_req_det`.`CL`,`t_req_det`.`BC`,`t_req_det`.`rol`,
            `t_req_det`.`roq`,`t_req_det`.`Item`,`t_req_det`.`cur_qty`,
            `t_req_det`.`total`,`m_item`.`model`,`m_item`.`description`
            FROM `t_req_det`
            LEFT JOIN `m_item` 
            ON `t_req_det`.`Item`=`m_item`.`code`
            WHERE `t_req_det`.`CL`='$_POST[cl]'
            ORDER BY `t_req_det`.`Item`";
            $query=$this->db->query($sql);
             
            if($query->num_rows()>0){
              $a['det']=$query->result();
              echo json_encode($a);
           }
           else{
            echo json_encode(1);
           }
    }

    public function set_different_branches_values(){
        $_POST['cl']=$this->sd['cl'];
        $sql="SELECT `t_req_det`.`CL`,`t_req_det`.`BC`,`t_req_det`.`rol`,
            `t_req_det`.`roq`,`t_req_det`.`Item`,`t_req_det`.`cur_qty`,
            `t_req_det`.`total`,`m_item`.`model`,`m_item`.`description`
            FROM `t_req_det`
            LEFT JOIN `m_item` 
            ON `t_req_det`.`Item`=`m_item`.`code`
            WHERE `t_req_det`.`CL`='$_POST[cl]' AND `t_req_det`.`Item`='$_POST[item]'
            ORDER BY `t_req_det`.`Item`,`t_req_det`.`BC`";
            $query=$this->db->query($sql);
             
            if($query->num_rows()>0){
              $a['det']=$query->result();
              echo json_encode($a);
           }
           else{
            echo json_encode(1);
           }

    }

    public function get_item_total(){
        $this->db->select_sum('total');
        $this->db->where('cl',$this->sd['cl']);  
        $this->db->group_by("Item");   
        $this->db->order_by("Item", "asc"); 
      
        $query = $this->db->get('t_req_det');

        $a['tt']=$query->result();     
        echo json_encode($a);
    }

    public function namal_test(){
        $_POST['cl']=$this->sd['cl'];
        $sql="SELECT COUNT(DISTINCT(`t_req_det`.`BC`))
              AS `b_count`,SUM(`t_req_det`.`total`)
              AS `total` ,`t_req_det`.`Item` ,GROUP_CONCAT(DISTINCT(`t_req_det`.`rol`) as 'v' 
              ORDER BY `t_req_det`.`BC` ,`t_req_det`.`item`  ,'')
              FROM `t_req_det`    
              WHERE  `t_req_det`.`CL`='c1'         
              GROUP BY `t_req_det`.`Item`
              ORDER BY bc,`t_req_det`.`Item`";
            $query=$this->db->query($sql);
             
            if($query->num_rows()>0){
              $a['det1']=$query->result();
              echo json_encode($a);
           }
           else{
            echo json_encode(1);
           }

    }
}





// SELECT `t_req_det`.`CL`,`t_req_det`.`BC`,`t_req_det`.`Item`,`t_req_det`.`cur_qty`,`t_req_det`.`total`,
// `m_item`.`model`,`m_item`.`description`
// FROM `t_req_det`
// LEFT JOIN `m_item` 
// ON `t_req_det`.`Item`=`m_item`.`code`
// WHERE `t_req_det`.`CL`='C1'
// ORDER BY `t_req_det`.`Item`