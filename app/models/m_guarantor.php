 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_guarantor extends CI_Model {
    
    private $sd;
    private $mtb;
    private $tb_contact;
    private $tb_acc_trance;
    private $tb_sum;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	$this->load->model('user_permissions');
	$this->mtb = $this->tables->tb['m_guarantor'];
	
    }
    
    public function base_details(){
	$this->load->model('r_cus_category');
	$this->load->model('r_area');
	$this->load->model('r_town');
	$this->load->model('r_nationality');
	$this->load->model('r_root');

	$a['table_data'] = $this->data_table();
	$a['cat'] = $this->r_cus_category->select();
	$a['area'] = $this->r_area->select();
	$a['tn'] = $this->r_town->select();
	$a['nation']=$this->r_nationality->select();
	$a['root']=$this->r_root->select();
    $a['type']=$this->select_cus_type();
	
	return $a;
    }
    

    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 60px;");
        $name = array("data"=>"Name", "style"=>"");
        $phone = array("data"=>"T/P", "style"=>"width: 100px;");
        $action = array("data"=>"Action", "style"=>"width: 80px;");
        $this->table->set_heading($code, $name, $phone, $action);

        $this->db->select(array('code', 'name', 'tp'));
        $this->db->limit(10);
      	$query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
        	
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_customer')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
            
            $code = array("data"=>$r->code, "value"=>"code");
            $name = array("data"=>$this->useclass->limit_text($r->name, 25), "style"=>" text-align: left;");
           
            $tp = array("data"=>$this->useclass->limit_text($r->tp, 15),"style"=>" text-align: center;");
            $action = array("data"=>$but, "style"=>" text-align: center;");
            
	    $this->table->add_row($code, $name, $tp, $action);
        }
         
        return $this->table->generate();
    }
   


 
   public function account_code(){
        $this->db->select(array('acc_code'));
        $this->db->where('code','debtor_control');
        $query=$this->db->get('m_default_account');
    
        if($query->result()>0){
            return $query->first_row()->acc_code;
        }
    }


    public function account_type($acc_code){
        $this->db->select(array('type'));
        $this->db->where('code',$acc_code);
        $query=$this->db->get('m_account');
        if($query->result()>0){
            return $query->first_row()->type;
        }
    }

    public function account_category($acc_code){
        $this->db->select(array('category'));
        $this->db->where('code',$acc_code);
        $query=$this->db->get('m_account');
        if($query->result()>0){
            return $query->first_row()->category;
        }

    }
    
    public function save(){

        $this->db->trans_begin();
        // error_reporting(E_ALL); 
        // function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        //     throw new Exception($errMsg); 
        // }
        // set_error_handler('exceptionThrower'); 
        // try {
        	// $acc_code=$this->account_code();
         //    $acc_type=$this->account_type($acc_code);
         //    $acc_category=$this->account_category($acc_code);

        	$this->db->trans_start();
    	
    	
    		// $a=array(
    		// "code"=>$_POST['code'],
    		// "name"=>$_POST['name'],
    		// "full_name"=>$_POST['full_name'],
    		// "address1"=>$_POST['address1'],
    		// "address2"=>$_POST['address2'],
    		// "address3"=>$_POST['address3'],
      //       "tp"=>$_POST['tp'],
    		// "email"=>$_POST['email'],
      //       "relation"=>$_POST['relation'],
    		// "doj"=>$_POST['doj'],
    		// "dob"=>$_POST['dob'],
    		// "category"=>$_POST['category'],
    		// "area"=>$_POST['area'],
    		// "credit_limit"=>$_POST['credit_limit'],
    		// "credit_period"=>$_POST['credit_period'],
    		// "is_tax"=>$_POST['is_tax'],
    		// "tax_reg_no"=>$_POST['tax_reg_no'],
    		// "inactive"=>$_POST['inactive'],
    		// "tp"=>$_POST['tp'],
    		// "mobile"=>$_POST['mobile'],
    		// "bl"=>$_POST['bl'],
    		// "bl_reason"=>$_POST['bl_reason'],
    		// "bl_officer"=>$_POST['bl_officer'],
    		// "bl_date"=>$_POST['bl_date'],
    		// "occupation"=>$_POST['occupation'],
    		// "salary"=>$_POST['salary'],
    		// "nationality"=>$_POST['nationality'],
    		// "town"=>$_POST['town'],
    		// "oc"=>$this->sd['oc'],
    		// "root"=>$_POST['root'],
      //       "cl"=>$this->sd['cl'],
      //       "bc"=>$this->sd['branch']
    		// );



    	    if($_POST['code_'] == "0" || $_POST['code_'] == ""){
               
                unset($_POST['code_']);
        		$this->db->insert($this->mtb,$_POST);
        		
                echo $this->db->trans_commit();
                

    		}else{

                
        		$this->set_delete();
            	$this->db->where("code", $_POST['code_']);
                unset($_POST['code_']);
                $this->db->update($this->mtb, $_POST);
                echo $this->db->trans_commit();
           
    		}
            
        // } catch ( Exception $e ) { 
        //     $this->db->trans_rollback();
        //     echo "Operation fail please contact admin"; 
        // } 
    }
    
    private function set_delete(){
	$this->db->where("code", $_POST['code_']);
	$this->db->delete("m_customer_events");
    }
    
    public function check_code(){
	$this->db->where('code', $_POST['code']);
	$this->db->limit(1);
	
	echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function load(){

    $this->db->where('code', $_POST['code']);
	$this->db->limit(1);
	$a["data"] = $this->db->get($this->mtb)->first_row();


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
            if($this->user_permissions->is_delete('m_customer')){
            	$this->db->where('code', $_POST['code']);
            	$this->db->limit(1);
            	$this->db->delete($this->mtb);
                echo $this->db->trans_commit();
            }else{
                echo "No permission to delete records";
                $this->db->trans_commit();
            }
        }catch( Exception $e ){ 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        }
    }
    
    
    
    
    
    public function auto_com(){
       $this->db->like('code', $_GET['q']);
       $this->db->or_like($this->mtb.'.name', $_GET['q']);
       $query = $this->db->select(array('code', $this->mtb.'.name'))
                    ->get($this->mtb);
        $abc = "";
            foreach($query->result() as $r){
                $abc .= $r->code."|".$r->name;
            
            // if(isset($_GET['f'])){ $abc .= "|".$r->rate; }
            // if(isset($_GET['c'])){ $abc .= "|".$r->tire_count; }
            
            $abc .= "\n";
            }
        
        echo $abc;
        }  
    


    public function select(){
        $query = $this->db->get($this->mtb);
        
        $s = "<select name='customer' id='customer' >";
        $s .= "<option value='0'>---</option>";

        foreach($query->result() as $r){   
			$s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }


    public function select_cus_type(){
        $query=$this->db->get('r_customer_type');
        
        $cus = "<select name='type' id='cus_type'>";
        
        foreach($query->result() as $r)
            {
                $cus.="<option title='".$r->description."' value='".$r->code."'>".$r->description."</option>";   
            }
        $cus.="</select>";
        return $cus;
        }

   public function get_data_table(){
   	 echo $this->data_table();
   }



    public function PDF_report(){
      $this->db->select(array('name','address','tp','fax','email'));
      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $r_detail['branch']=$this->db->get('m_branch')->result();


      $r_detail['dt']=$_POST['dt'];
      $r_detail['qno']=$_POST['qno'];

      $r_detail['page']=$_POST['page'];
      $r_detail['header']=$_POST['header'];
      $r_detail['orientation']=$_POST['orientation'];
      $r_detail['pdf_page_type']=$_POST['type1'];
      



     $this->db->where('code', $_POST['code_find']);
	 $this->db->limit(1);
	 $r_detail["data"] = $this->db->get($this->mtb)->result();


	 $this->db->where("code", $_POST['code_find']);
	 $r_detail['c'] = $this->db->get("m_customer_events")->result();      
    
     $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }
}