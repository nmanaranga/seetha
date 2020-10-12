<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_employee_activity extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');
    
    }
    
    public function base_details(){
    $a['max_no'] = $this->get_max_no("m_employee_activity", "nno");    
    $a['table_data'] = $this->get_data_table();
    return $a;
    }

    
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        $nno = array("data"=>"No", "style"=>"width: 60px; cursor : pointer;");
        $employee_id = array("data"=>"Employee", "style"=>"cursor : pointer;");
        $is_active = array("data"=>"Active", "style"=>"cursor : pointer;");
        $action = array("data"=>"Action", "style"=>"width: 60px;");
    
        $this->table->set_heading($nno,$employee_id,$is_active,$action);//
       
        $sql = "SELECT a.*, 
                    e.`name`, 
                    c.`description` 
              FROM m_employee_activity a
              JOIN m_employee e ON e.`code` = a.`employee_id`
              JOIN m_employee_category c ON c.`code` = a.`category`
              ORDER BY a.nno";
        $query = $this->db->query($sql);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->nno."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_employee_activity')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->nno."\")' title='Delete' />";}
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 68px;");
            $nno = array("data"=>$this->useclass->limit_text($r->nno, 20), "style"=>"text-align: left;  width: 50px;");
            $emp = array("data"=>$this->useclass->limit_text($r->name, 20), "style"=>"text-align: left;  width: 300px;");
            if($r->is_active=="1"){
                $active = "<img src='".base_url()."img/tick.png' title='Active' style='width:15px;height:15px;margin-left:15px;'/>&nbsp;&nbsp;";
            }else{
                $active = "<img src='".base_url()."img/no.png' title='Inactive' style='width:15px;height:15px;margin-left:15px;'/>&nbsp;&nbsp;";
            }
            
            $this->table->add_row($nno,$emp,$active,$ed);//
        } 
        return $this->table->generate();
    }
    
    public function validation(){
        $status=1;
        $check_emp = $this->check_emp($_POST['emp_id'],$_POST['category']);
        if ($check_emp != 1) {
            return $check_emp;
        }
        $employee_validation = $this->validation->check_is_employer($_POST['emp_id']);
        if ($employee_validation != 1) {
            return "Please enter valid sales rep";
        }
        $check_category = $this->check_category($_POST['category']);
        if ($check_category != 1) {
            return $check_category;
        }

        return $status;
    }

    public function check_emp($emp,$cat){
        $status=1;
        $sql="SELECT * 
              FROM m_employee_activity
              WHERE employee_id ='$emp' AND category='$cat'";

        $query=$this->db->query($sql);

        if($query->num_rows()>0){
            $status="Employee No '$emp' already in category id '$cat'";
        }
        return $status;
    }

    public function save(){
        $this->max_no=$this->get_max_no("m_employee_activity", "nno"); 
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {
            $validation = $this->validation();
            if($validation==1){
                if(isset($_POST['is_active'])){
                    $active="1";
                }else{
                    $active="0";
                }
                $data_arr=array(
                    'cl'            => $this->sd['cl'], 
                    'bc'            => $this->sd['branch'], 
                    'nno'           => $this->max_no,
                    'ddate'         => $_POST['date'], 
                    'employee_id'   => $_POST['emp_id'], 
                    'category'      => $_POST['category'], 
                    'designation'   => $_POST['designation'], 
                    'note'          => $_POST['note'],
                    'is_active'     => $active 

                );
                if($_POST['hid']=="0"||$_POST['hid']==""){  
                    if($this->user_permissions->is_add('m_employee_activity')){
                        $this->db->insert('m_employee_activity', $data_arr);
                        echo $this->db->trans_commit();
                    }else{
                        echo "No permission to save records";
                        $this->db->trans_commit();
                    }  
                }else{
                    if($this->user_permissions->is_edit('m_employee_activity')){
                        $this->db->where("nno", $this->max_no);
                        $this->db->where('cl', $this->sd['cl']);
                        $this->db->where('bc', $this->sd['branch']);
                        $this->db->update('m_employee_activity',$data_arr);
                        echo $this->db->trans_commit();
                    }else{
                        echo "No permission to update records";
                        $this->db->trans_commit();
                    }  
                }
            }else{
                echo $validation;
                $this->db->trans_commit();
            }
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo $e->getMessage()."Operation fail please contact admin"; 
        } 
    }
    

    public function load(){

        $nno=$_POST['code'];    
        $sql="SELECT a.*, 
                    e.`name`, 
                    c.`description` 
              FROM m_employee_activity a
              JOIN m_employee e ON e.`code` = a.`employee_id`
              JOIN m_employee_category c ON c.`code` = a.`category`
              WHERE a.nno ='$nno'";    

        $query=$this->db->query($sql);
           
        if($query->num_rows()>0){
          $a=$query->result();
        }else{
          $a=2;
        }
        echo json_encode($a);
    } 

    public function get_data_table(){
    return $this->data_table();
    }

    public function delete(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {
            if($this->user_permissions->is_delete('m_employee_activity')){
                $this->db->where('nno', $_POST['code']);
                $this->db->delete("m_employee_activity");     
                echo $this->db->trans_commit();
            }else{
                $this->db->trans_commit();
                echo "No permission to delete records";
            }
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo $e->getMessage()."Operation fail please contact admin"; 
        }  
    }

   public function get_max_no($table_name,$field_name){
        if(isset($_POST['hid'])){
          if($_POST['hid'] == "0" || $_POST['hid'] == ""){      
            $this->db->select_max($field_name);
            return $this->db->get($table_name)->first_row()->$field_name+1;
          }else{
            return $_POST['hid'];  
          }
        }else{
            $this->db->select_max($field_name);
            return $this->db->get($table_name)->first_row()->$field_name+1;
        }
    }

    public function search_result(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        $nno = array("data"=>"No", "style"=>"width: 60px; cursor : pointer;");
        $employee_id = array("data"=>"Employee", "style"=>"cursor : pointer;");
        $is_active = array("data"=>"Active", "style"=>"cursor : pointer;");
        $action = array("data"=>"Action", "style"=>"width: 60px;");
    
        $this->table->set_heading($nno,$employee_id,$is_active,$action);//
       
        $sql = "SELECT a.*, 
                    e.`name`, 
                    c.`description` 
              FROM m_employee_activity a
              JOIN m_employee e ON e.`code` = a.`employee_id`
              JOIN m_employee_category c ON c.`code` = a.`category`
              WHERE nno LIKE '%".$_POST['code']."%' OR name LIKE  '%".$_POST['code']."%'
              ORDER BY a.nno";
        $query = $this->db->query($sql);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->nno."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_employee_activity')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->nno."\")' title='Delete' />";}
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 68px;");
            $nno = array("data"=>$this->useclass->limit_text($r->nno, 20), "style"=>"text-align: left;  width: 50px;");
            $emp = array("data"=>$this->useclass->limit_text($r->name, 20), "style"=>"text-align: left;  width: 300px;");
            if($r->is_active=="1"){
                $active = "<img src='".base_url()."img/tick.png' title='Active' style='width:15px;height:15px;margin-left:15px;'/>&nbsp;&nbsp;";
            }else{
                $active = "<img src='".base_url()."img/no.png' title='Inactive' style='width:15px;height:15px;margin-left:15px;'/>&nbsp;&nbsp;";
            }
            
            $this->table->add_row($nno,$emp,$active,$ed);//
        } 
        echo $this->table->generate();
    }

    public function check_category($cat){
        $status=1;
        $sql="SELECT COUNT(code) as no
              FROM m_employee_category
              WHERE CODE='$cat'";

        $query=$this->db->query($sql);

        if($query->row()->no==0){
            $status="Please Enter Valid Employee Category";
        }
        return $status;
    }
}