<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_payment_option extends CI_Model {
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
	$this->mtb = $this->tables->tb['r_payment_option'];
    $this->load->model('user_permissions');
    }
    
    public function base_details(){
	$a['select'] = $this->select();

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
        if($this->user_permissions->is_add('r_payment_option')){
            if(isset($_POST['cash'])){$_POST['cash']=1;}else{$_POST['cash']=0;}
            if(isset($_POST['chq_receive'])){$_POST['chq_receive']=1;}else{$_POST['chq_receive']=0;}
            if(isset($_POST['chq_issue'])){$_POST['chq_issue']=1;}else{$_POST['chq_issue']=0;}
            if(isset($_POST['credit_card'])){$_POST['credit_card']=1;}else{$_POST['credit_card']=0;}
            if(isset($_POST['crn'])){$_POST['crn']=1;}else{$_POST['crn']=0;}
            if(isset($_POST['drn'])){$_POST['drn']=1;}else{$_POST['drn']=0;}
            if(isset($_POST['bank_deposit'])){$_POST['bank_deposit']=1;}else{$_POST['bank_deposit']=0;}
            if(isset($_POST['discount'])){$_POST['discount']=1;}else{$_POST['discount']=0;}
            if(isset($_POST['advance'])){$_POST['advance']=1;}else{$_POST['advance']=0;}
            if(isset($_POST['gift_voucher'])){$_POST['gift_voucher']=1;}else{$_POST['gift_voucher']=0;}
            if(isset($_POST['credit'])){$_POST['credit']=1;}else{$_POST['credit']=0;}
            if(isset($_POST['privilege_card'])){$_POST['privilege_card']=1;}else{$_POST['privilege_card']=0;}
            if(isset($_POST['installment'])){$_POST['installment']=1;}else{$_POST['installment']=0;}
            if(isset($_POST['post_dated_cheques'])){$_POST['post_dated_cheques']=1;}else{$_POST['post_dated_cheques']=0;}

        
            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                unset($_POST['code_']);
                $this->db->insert($this->mtb, $_POST);
            }else{
                $this->db->where("type", $_POST['code_']);
                unset($_POST['code_']);
                $this->db->update($this->mtb, $_POST);
            }
            echo $this->db->trans_commit();
        }else{
           echo "No permission to save records";
           $this->db->trans_commit(); 
        }
    }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo "Operation fail please contact admin"; 
    }    

 }
    
    public function check_code(){
	$this->db->where('type', $_POST['type']);
	$this->db->limit(1);
	
	echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function load(){
	$this->db->where('type', $_POST['type']);
	$this->db->limit(1);
	
	echo json_encode($this->db->get($this->mtb)->first_row());
    }
    
    public function delete(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 

        	if($this->user_permissions->is_delete('r_payment_option')){
        	    $this->db->where('code', $_POST['code']);
        	    $this->db->limit(1);
        	    
        	    $this->db->delete($this->mtb);
                echo $this->db->trans_commit();
        	}else{
                echo "No permission to delete records";
                $this->db->trans_commit();
            }
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        } 
    }
    
    public function select(){
        $query = $this->db->get($this->mtb);
        
        $s = "<select name='type' id='type'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->type."' value='".$r->type."'>".$r->type."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }
}