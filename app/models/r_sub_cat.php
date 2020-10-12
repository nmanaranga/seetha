<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_sub_cat extends CI_Model {
    
    private $sd;
    private $mtb;
    private $tb_main_cat;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');	
	$this->mtb = $this->tables->tb['r_sub_category'];
	$this->tb_main_cat = $this->tables->tb['r_category'];
	
    }
    
    public function base_details(){
	$this->load->model('r_category');
	$a['table_data'] = $this->data_table();
	$a['main_cat'] = $this->r_category->select();
	return $a;
    }
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Main Category", "style"=>"width: 75px; cursor : pointer;", "onclick"=>"set_short(1)");
        $des = array("data"=>"Code", "style"=>"cursor : pointer;width: 75px;", "onclick"=>"set_short(2)");
        $dt = array("data"=>"Description", "style"=>"width: 150px;");
        $action = array("data"=>"Action", "style"=>"width: 100px;");
	
        $this->table->set_heading($code, $des, $dt,$action);
        $this->db->limit(10);
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){            
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."-".$r->main_category."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('r_sub_cat')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."-".$r->main_category."\")' title='Delete' />";}
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 108px;");
            $main_category = array("data"=>$r->main_category, "style"=>"text-align: center;  width: 75px;");
            $dis = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");
            $code = array("data"=>$r->code, "style"=>"text-align: left; width: 75px; ", "value"=>"code");
	    
            $this->table->add_row( $main_category,$code,$dis, $ed);
        }         
        return $this->table->generate();
    }
    
    public function check_exist($root){
	$this->db->select('code');
	$this->db->where('description', $root);
	$this->db->or_where('code', $root);
	$this->db->limit(1);
	$query = $this->db->get($this->mtb);
	
	if($query->num_rows){
	    return $query->first_row()->code;
	}else{
	    return false;
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
            $_POST['oc']=$this->sd['oc'];
            $_POST['code']=strtoupper($_POST['code']);
            $_POST['code_gen']=strtoupper($_POST['code_gen']);
            $update_ar=array(
                "code_gen"=> $_POST['code_gen'],
                "description"=> $_POST['description'],
                //"main_category"=> $_POST['main_category'],
                );
            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('r_sub_cat')){
                    unset($_POST['code_']);
                    $this->db->insert($this->mtb, $_POST);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }
            }else{  
                if($this->user_permissions->is_edit('r_sub_cat')){
                    $this->db->where("code", $_POST['code_']);
                    unset($_POST['code_']);
                    $this->db->update($this->mtb, $update_ar);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to edit records";
                    $this->db->trans_commit();
                } 
            }
            
        } catch ( Exception $e ) { 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        } 
    }
    public function check_code(){
	$this->db->where('code', $_POST['code']);
	$this->db->limit(1);
	echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function load(){
        $sql=" SELECT r_category.`description` AS m_des, r_category.`code` AS m_code ,r_sub_category.`code` AS s_code,r_sub_category.`description` AS s_des, r_sub_category.`code_gen`,r_sub_category.max_no FROM
        r_sub_category JOIN r_category ON r_sub_category.`main_category`= r_category.`code` WHERE `r_sub_category`.code='$_POST[code]' AND `r_sub_category`.main_category='$_POST[main]'";
	
        echo json_encode($this->db->query($sql)->first_row());
	
    }
    
    public function delete_validation(){
        $status=1;
        $codes=$_POST['code'];
        $check_cancellation = $this->utility->check_account_trans($codes,'Sub Category','r_sub_cat','m_item','category');
        if ($check_cancellation != 1) {
          return $check_cancellation;
        }
        return $status;
    }

    public function delete(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {
            if($this->user_permissions->is_delete('r_sub_cat')){
                $delete_validation_status=$this->delete_validation();
                if($delete_validation_status==1){
                	$this->db->where('code', $_POST['code']);
                    $this->db->where('main_category', $_POST['main']);
                	$this->db->limit(1);
                	$this->db->delete($this->mtb);
                    echo $this->db->trans_commit();
                }else{
                    echo $delete_validation_status;
                    $this->db->trans_commit();
                }   
            }else{
                echo "No permission to delete records";
                $this->db->trans_commit();
            }
        } catch ( Exception $e ) { 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        } 
    }
    
	   
    public function get_data_table(){
    echo $this->data_table();
    }
    
    public function select(){
        $query = $this->db->get($this->mtb);
        $s = "<select name='category' id='category'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."-".$r->description."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }
    
    public function sub_cat_list(){
	$this->db->select(array($this->tb_main_cat.'.code AS main_cat', $this->mtb.'.code', $this->mtb.'.description'));
	$this->db->join($this->tb_main_cat, $this->tb_main_cat.'.code = '.$this->mtb.'.main_cat');
	$this->db->order_by($this->tb_main_cat.'.code');
	
	//echo json_encode($this->db->get($this->mtb)->result()); exit;
	
	$res = $tres = array(); $mcat = "";
	foreach($this->db->get($this->mtb)->result() as $r){
	    if($mcat != $r->mcat && $mcat != ""){
		$res[$mcat] = $tres;
		$tres = array();
	    }
	    $mcat = $r->mcat;
	    
	    $tres[] = array($r->code, $r->description);
	}
	$res[$mcat] = $tres;
	
	echo json_encode($res);
    }

     public function auto_com(){
        $this->db->like('code', $_GET['q']);
        $this->db->or_like($this->mtb.'.description', $_GET['q']);
        $query = $this->db->select(array('code', 'code_gen', $this->mtb.'.description'))->get($this->mtb);

        if ($query->num_rows() > 0) {
            $this->db->like('code', $_GET['q']);
            $this->db->or_like($this->mtb.'.description', $_GET['q']);
            $query2 = $this->db->select(array('code', 'code_gen', $this->mtb.'.description'))->get($this->mtb);
        }else{
            $query2 = $this->db->select(array('code', 'code_gen', $this->mtb.'.description'))->get($this->mtb);
        }
        
        $abc = "";
            foreach($query2->result() as $r){
                $abc .= $r->code."|".$r->description."_".$r->code_gen;
            $abc .= "\n";
            }
        
        echo $abc;
        }  

public function PDF_report(){

    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch']=$this->db->get('m_branch')->result();

    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];
 
    $r_detail['type']=$_POST['type'];        
    $r_detail['dd']=$_POST['dd'];
    $r_detail['qno']=$_POST['qno'];

    $r_detail['page']=$_POST['page'];
    $r_detail['header']=$_POST['header'];
    $r_detail['orientation']=$_POST['orientation'];
    

    $sql="SELECT s.main_category,c.`description` AS main,s.code,s.description FROM `r_sub_category` s
JOIN `r_category` c ON c.`code`=s.`main_category`";

    $r_detail['sub_cat_det']=$this->db->query($sql)->result();  

        if($this->db->query($sql)->num_rows()>0){
      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }else{
      echo "<script>alert('No Data');window.close();</script>";
    }

  }    
    

}