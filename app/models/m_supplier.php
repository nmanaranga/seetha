<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_supplier extends CI_Model {

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
     $this->mtb = $this->tables->tb['m_supplier'];


 }

 public function base_details(){
     $this->load->model("r_sup_category");
     //$this->load->model('m_account');

     $a['category']=$this->r_sup_category->select();
     $a['table_data'] = $this->data_table();
    // $a['Contral_acc_list'] = $this->m_account->select_Control();
     $a['sup_code']=$this->generate_code();
     $a['sup_code_gen'] = substr($this->generate_code(), -3);

     /* $a['contact']=$this->get_contact_type();*/
     return $a;
 }


   /* public function get_contact_type(){
        $this->db->select(array('type'));
        $query = $this->db->get('m_supplier_contact');

        $s = "<select name='contact' id='contact' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->type."' value='".$r->type."'>".$r->type."</option>";
        }
        $s .= "<option value='1'>Other</option>";
        $s .= "</select>";
        
        return $s;
    }
*/

    public function generate_code(){
        $sql="SELECT max(`auto_num`) as auto_num FROM (`m_supplier`) ORDER BY `auto_num` DESC LIMIT 1";
        $query=$this->db->query($sql); 

        if($query->num_rows()>0){
           $result=$query->last_row()->auto_num;   
       }else{
        $result=0;
    }

    $result=(int)$result+1;
    if($result<9){
        return "VEN000000".$result;
    }else if($result<99){
        return "VEN00000".$result;
    }else if($result<999){
        return "VEN0000".$result;
    }else if($result<9999){
        return "VEN000".$result;
    }else if($result<99999){
        return "VEN00".$result;
    }else if($result<999999){
        return "VEN0".$result;
    }else if($result<9999999){
        return "VEN".$result;
    }

}

public function generate_int_code(){

    $sql="SELECT max(`auto_num`) as auto_num FROM (`m_supplier`) ORDER BY `auto_num` DESC LIMIT 1";
    $query=$this->db->query($sql); 

    if($query->num_rows()>0){
       $result=$query->last_row()->auto_num;   
   }else{
    $result=0;
}

$result=(int)$result+1;
return $result;

}


public function data_table(){
    $this->load->library('table');
    $this->load->library('useclass');
    $this->table->set_template($this->useclass->grid_style());
    $code = array("data"=>"Code", "style"=>"width: 150px;");
    $name = array("data"=>"Name", "style"=>"width: 450px;");
    $action = array("data"=>"Action", "style"=>"width: 100px;");

    $this->table->set_heading($code, $name, $action);

    $this->db->select(array('code','name'));
    $this->db->from('m_supplier');
    $this->db->limit(10);
    $query = $this->db->get();

    foreach($query->result() as $r){
        $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
        if($this->user_permissions->is_delete('m_supplier')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}
        $code = array("data"=>$r->code, "value"=>"code");
        $name = array("data"=>$this->useclass->limit_text($r->name, 50));
        $action = array("data"=>$but, "style"=>"text-align: center;");
        $this->table->add_row($code, $name,  $action);
    } 
    return $this->table->generate();
}



public function account_code(){
    $this->db->select(array('acc_code'));
    $this->db->where('code','creditor_control');
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
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try { 

        $acc_code=$this->account_code();
        $acc_type=$this->account_type($acc_code);
        $acc_category=$this->account_category($acc_code);
        
        if(!isset($_POST['is_tax'])){$_POST['is_tax']=0;}else{$_POST['is_tax']=1;}
        if(! isset($_POST['is_inactive'])){ $_POST['is_inactive'] = 0; }else{ $_POST['is_inactive'] = 1;}
        if(! isset($_POST['is_blacklist'])){ $_POST['is_blacklist'] = 0; }else{ $_POST['is_blacklist'] = 1;}

        if($_POST['code_'] == "0" || $_POST['code_'] == ""){            
            $cc=$this->generate_code();
        }else{            
            $cc=$_POST['code'];
        }          

        $m_supplier_data=array(
               // "code"=>strtoupper($_POST['code']),
           "code"=> $this->generate_code(),
               //"code_gen"=>substr($this->generate_code(), -4),
           "code_gen"=>strtoupper($_POST['code_gen']),
           "name"=>$_POST['name'],
           "payee_name"=>$_POST['payee_name'],
           "contact_name"=>$_POST['contact_name'],
           "address1"=>$_POST['address1'],
           "address2"=>$_POST['address2'],
           "address3"=>$_POST['address3'],
           "email"=>$_POST['email'],
           "doj"=>$_POST['doj'],
           "category"=>$_POST['category'],
           "control_acc"=> $acc_code, 
           "credit_limit"=> $_POST['credit_limit'],
           "credit_period"=>$_POST['credit_period'],
           "is_tax"=>$_POST['is_tax'],
           "is_inactive"=> $_POST['is_inactive'],
           "is_blacklist"=> $_POST['is_blacklist'],
           "bl_reason"=> $_POST['bl_reason'],
           "bl_officer"=> $_POST['bl_officer'],
           "bl_date"=> $_POST['bl_date'],
           "tax_reg_no"=>$_POST['tax_reg_no'],
           "auto_num"=>$this->generate_int_code(),
           "postal_code" => $_POST['postal_code']
       );

        $m_supplier_data_update=array(
               // "code"=>strtoupper($_POST['code']),
           "code"=>$_POST['code'],
           "code_gen"=>strtoupper($_POST['code_gen']),
           "name"=>$_POST['name'],
           "payee_name"=>$_POST['payee_name'],
           "contact_name"=>$_POST['contact_name'],
           "address1"=>$_POST['address1'],
           "address2"=>$_POST['address2'],
           "address3"=>$_POST['address3'],
           "email"=>$_POST['email'],
           "doj"=>$_POST['doj'],
           "category"=>$_POST['category'],
           "control_acc"=> $acc_code, 
           "credit_limit"=> $_POST['credit_limit'],
           "credit_period"=>$_POST['credit_period'],
           "is_tax"=>$_POST['is_tax'],
           "is_inactive"=> $_POST['is_inactive'],
           "is_blacklist"=> $_POST['is_blacklist'],
           "bl_reason"=> $_POST['bl_reason'],
           "bl_officer"=> $_POST['bl_officer'],
           "bl_date"=> $_POST['bl_date'],
           "tax_reg_no"=>$_POST['tax_reg_no'],
           "postal_code" => $_POST['postal_code']

       );


        $m_account=array(
            "type"=>$acc_type,
            "code"=>$this->generate_code(),
            "description"=>$_POST['name'],
            "control_acc"=>$acc_code,
            "is_control_acc"=>0,
            "is_bank_acc"=>0,
            "category"=>$acc_category,
            "display_text"=>$_POST['name'],
            "oc"=>$this->sd['oc'],
            "is_sys_acc"=>0,
            "order_no"=>0
        );

        $m_account_update=array(
            "type"=>$acc_type,
            "code"=>$_POST['code'],
            "description"=>$_POST['name'],
            "control_acc"=>$acc_code,
            "is_control_acc"=>0,
            "is_bank_acc"=>0,
            "category"=>$acc_category,
            "display_text"=>$_POST['name'],
            "oc"=>$this->sd['oc'],
            "is_sys_acc"=>0,
            "order_no"=>0
        );
        

        for($x = 0; $x<10; $x++){
            if(isset($_POST['type_'.$x])){
                if($_POST['type_'.$x] != ""){
                    $b[]= array(
                        "code"=>$cc,
                        "type"=>strtoupper($_POST['type_'.$x]),
                        "description"=>$_POST['des_'.$x],
                        "tp"=>$_POST['tp_'.$x]
                    );              
                }
            }
        }   

        for($x = 0; $x<10; $x++){
            if(isset($_POST['comment_'.$x],$_POST['date_'.$x])){
                if($_POST['comment_'.$x] != "" || $_POST['date_'.$x] != ""){
                    $c[]= array(
                        "code"=>$cc,
                        "ddate"=>$_POST['date_'.$x],
                        "comment"=>$_POST['commenttt_'.$x]
                    );              
                }
            }
        }


        if($_POST['code_'] == "0" || $_POST['code_'] == ""){
            if($this->user_permissions->is_add('m_supplier')){
                $this->db->insert($this->mtb,$m_supplier_data);
                $this->db->insert('m_account',$m_account);
                if(isset($b)){if(count($b)){ $this->db->insert_batch("m_supplier_contact", $b );}}
                if(isset($c)){if(count($c)){ $this->db->insert_batch("m_supplier_comment", $c );}} 
                echo $this->db->trans_commit();  
            }else{
                echo "No permission to save records";
                $this->db->trans_commit();
            }
        }else{
            if($this->user_permissions->is_edit('m_supplier')){
                $this->db->where("code", $_POST['code_']);
                $this->db->update($this->mtb, $m_supplier_data_update);

                $this->db->where("code", $_POST['code_']);
                $this->db->update('m_account',$m_account_update);
                
                $this->set_delete();
                if(isset($c)){if(count($c)){ $this->db->insert_batch("m_supplier_comment", $c );}}  
                if(isset($b)){if(count($b)){ $this->db->insert_batch("m_supplier_contact", $b );}}

                unset($_POST['code_']);
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

private function set_delete(){
	$this->db->where("code", $_POST['code_']);
	$this->db->delete("m_supplier_contact");

	$this->db->where("code", $_POST['code_']);
	$this->db->delete("m_supplier_comment");
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
	
	$this->db->where("code", $_POST['code']);
	$a['c'] = $this->db->get("m_supplier_contact")->result();
	
	$this->db->where("code", $_POST['code']);
	$a['comment'] = $this->db->get("m_supplier_comment")->result();

    $a['acc']=$this->utility->get_account_balance($_POST['code']);

    echo json_encode($a);
}

public function delete_validation(){
    $status=1;
    $codes=$_POST['code'];
    $check_cancellation = $this->utility->check_account_trans($codes,'Supplier','m_supplier','t_account_trans','acc_code');
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
        if($this->user_permissions->is_delete('m_supplier')){
            $delete_validation_status=$this->delete_validation();
            if($delete_validation_status==1){
             $this->db->where('code', $_POST['code']);
             $this->db->limit(1);
             $this->db->delete($this->mtb);

             $this->db->where('code', $_POST['code']);
             $this->db->limit(1);
             $this->db->delete('m_account');

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
    $s = "<select name='supplier' id='supplier'>";
    $s .= "<option value='0'>---</option>";
    foreach($query->result() as $r){
        $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code."-".$r->name."</option>";
    }
    $s .= "</select>";
    return $s;
}



public function auto_com(){
    $q=$_GET['q'];
    $sql="SELECT `code`, `m_supplier`.`name`,code_gen FROM (`m_supplier`) 
    WHERE (`is_blacklist` = '0' AND `is_inactive` = '0')
    AND (`code` LIKE '%$q%' OR `m_supplier`.`name` LIKE '%$q%' )";
        // $this->db->like('code', $_GET['q']);
        // $this->db->or_like($this->mtb.'.name', $_GET['q']);
        // $query = $this->db->select(array('code', 'code_gen', $this->mtb.'.name'))->get($this->mtb);
    $query = $this->db->query($sql);
    $abc = "";
    foreach($query->result() as $r){
        $abc .= $r->code."|".$r->name."_".$r->code_gen;
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

    $r_detail['store_code']=$_POST['stores']; 
    $r_detail['type']=$_POST['type'];        
    $r_detail['dd']=$_POST['dd'];
    $r_detail['qno']=$_POST['qno'];

    $r_detail['page']=$_POST['page'];
    $r_detail['header']=$_POST['header'];
    $r_detail['orientation']=$_POST['orientation'];
    $r_detail['from']=$_POST['from'];
    $r_detail['to']=$_POST['to'];
    $to=$_POST['to'];



    $sql="SELECT ms.code,ms.name,ms.contact_name,
    IFNULL(mc_off.mob, '') AS office,
    IFNULL(mc_fax.mob, '') AS fax,
    IFNULL(mc_mob.mob, '') AS mobile 
    FROM `m_supplier` ms 
    LEFT JOIN 
    (SELECT `code`,tp AS mob FROM
    `m_supplier_contact` 
    WHERE TYPE = 'MOBILE 1' OR TYPE = 'MOBILE 2') mc_mob 
    ON (ms.`code` = mc_mob.`code`) LEFT JOIN 
    (SELECT `code`,tp AS mob FROM `m_supplier_contact` 
    WHERE TYPE = 'FAX') mc_fax ON (ms.`code` = mc_fax.`code`) 
    LEFT JOIN (SELECT `code`,tp AS mob FROM `m_supplier_contact` 
    WHERE TYPE = 'OFFICE TEL 1' or TYPE = 'OFFICE TEL 2') mc_off 
    ON (ms.`code` = mc_off.`code`) group by ms.`code` ";

    $r_detail['sup_det']=$this->db->query($sql)->result();  

    if($this->db->query($sql)->num_rows()>0){
      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
  }else{
      echo "<script>alert('No Data');window.close();</script>";
  }

}    

}