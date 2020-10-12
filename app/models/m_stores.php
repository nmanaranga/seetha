<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_stores extends CI_Model {
    
    private $sd;
    private $mtb;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	$this->mtb = $this->tables->tb['m_stores'];
    $this->load->model('user_permissions');
    }
    
    public function base_details(){
	$a['table_data'] = $this->data_table();
    $a['store_code'] = $this->store_code();
    $a['get_next_code']=$this->get_next_code();
	return $a;
    }
    



    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 100px; cursor : pointer;", "onclick"=>"set_short(1)");
        $des = array("data"=>"Description", "style"=>"cursor : pointer;", "onclick"=>"set_short(2)");
        $action = array("data"=>"Action", "style"=>"width: 100px;");
	
        $this->table->set_heading($code, $des, $action);
        
        $this->db->select(array('action_date', 'description', 'code'));
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $this->db->limit(10);
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){

           
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_stores')){ $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
            $ed = array("data"=>$but, "style"=>"text-align: center; width: 100px;");
            $dis = array("data"=>$this->useclass->limit_text($r->description, 50), "style"=>"text-align: left;");
            $code = array("data"=>$r->code, "style"=>"text-align: left; width: 100px; ", "value"=>"code");
	    
            $this->table->add_row($code, $dis, $ed);
        }
        
        return $this->table->generate();
    }

       public function get_data_table(){
     echo $this->data_table();
   }

    public function get_next_code(){
        $sql="SELECT LPAD((SELECT IFNULL(MAX(SUBSTRING(CODE,5)),0)+1 FROM m_stores WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'),3,0) as v";

        $code=$this->db->query($sql)->first_row()->v;
        // $cus_code = $this->sd['cl'].$this->sd['branch'].$code;
        return $code; 

    }


    
    public function save(){

        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 
            $_POST['cl']=$this->sd['cl'];
            $_POST['bc']=$this->sd['branch'];
            $_POST['oc']=$this->sd['oc'];
    		$_POST['code'] = strtoupper($_POST['code']);
            $code=$_POST['pre_code'].$_POST['code'];
            
    		if(! isset($_POST['sales'])){ $_POST['sales'] = 0; }
    		if(! isset($_POST['purchase'])){ $_POST['purchase'] = 0; }
            if(! isset($_POST['group_sale'])){ $_POST['group_sale'] = 0; }
            if(! isset($_POST['transfer_location'])){ $_POST['transfer_location'] = 0; }

            $a=array(
                "cl"=>$this->sd['cl'],
                "bc"=>$this->sd['branch'],
                "code"=>$code,
                "description"=>$_POST['description'],
                "group_sale"=>$_POST['group_sale'],
                "transfer_location"=>$_POST['transfer_location'],
                "purchase"=>$_POST['purchase'],
                "sales"=>$_POST['sales'],
                "oc"=>$this->sd['oc']
            );


            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('m_stores')){
                    unset($_POST['code_']);
                    $this->db->insert($this->mtb, $a);
                    echo $this->db->trans_commit();
    			}else{
                    $this->db->trans_commit();
                    echo "No permission to save records";
                }
            }else{             
                if($this->user_permissions->is_edit('m_stores')){
                    $this->db->where("code", $code);
                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc",$this->sd['branch']);
                    unset($_POST['code_']);
                    $this->db->update($this->mtb, $a);
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to edit records";
                    $this->db->trans_commit();
                }   
            }              
        }catch( Exception $e ){ 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        } 
    }
    
    public function check_code(){

    $code=$_POST['code'];  
	$this->db->where('code', $code);
    $this->db->where('cl',$this->sd['cl']);
    $this->db->where('bc',$this->sd['branch']);
	$this->db->limit(1);
	echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function load(){
    $code=$_POST['code'];    
	$this->db->where('code', $code);
    $this->db->where('cl',$this->sd['cl']);
    $this->db->where('bc',$this->sd['branch']);    
	$this->db->limit(1);
	echo json_encode($this->db->get($this->mtb)->first_row());
    }

    public function delete_validation(){
        $status=1;
        $codes=$_POST['code'];
        $check_cancellation = $this->utility->check_account_trans($codes,'Store','m_store','t_item_movement','store_code');
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
            if($this->user_permissions->is_delete('m_stores')){
                $delete_validation_status=$this->delete_validation();
                if($delete_validation_status==1){
                    $this->db->where('code', $_POST['code']);
                    $this->db->where('cl',$this->sd['cl']);
                    $this->db->where('bc',$this->sd['branch']);
                	$this->db->limit(1);
                	$this->db->delete($this->mtb);
                    echo $this->db->trans_commit();
                }else{
                    echo $delete_validation_status;
                    $this->db->trans_commit();
                }    
            }else{
                 echo "No permission to delete records";
            }
        } catch ( Exception $e ) { 
        $this->db->trans_rollback();
        echo $e->getMessage()."Operation fail please contact admin"; 
        } 
    }

      
    public function select(){
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $sql="SELECT * FROM $this->mtb WHERE cl='$cl' AND bc='$bc' AND (sales='1' OR group_sale='1')";
        $query = $this->db->query($sql);

        $s = "<select name='stores' id='stores' class='store11'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.'-'.$r->description."</option>";
	    }
        $s .= "</select>";
        
        return $s;
    }


    public function select2(){
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $this->db->where("purchase","1");
        $query = $this->db->get($this->mtb);

        $s = "<select name='stores' id='stores' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.'-'.$r->description."</option>";
        }
        $s .= "</select>";
        return $s;
    }

    public function select3(){
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $query = $this->db->get($this->mtb);
        $s = "<select name='stores' id='stores' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.' | '.$r->description."</option>";
        }
        $s .= "</select>";
        return $s;
    }

    public function store_code(){
        $code = $this->sd['cl'].$this->sd['branch'];
        return $code;
    }


}