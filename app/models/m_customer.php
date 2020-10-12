 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class m_customer extends CI_Model {

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
       $this->mtb = $this->tables->tb['m_customer'];

   }

   public function base_details(){
      $this->load->model('r_cus_category');
	// $this->load->model('r_area');
	// $this->load->model('r_town');
	// $this->load->model('r_nationality');
	// $this->load->model('r_root');

      $a['table_data'] = $this->data_table();
      $a['type']=$this->select_cus_type();
      $a['get_next_code']=$this->get_next_code();

      $a['show_category'] = $this->load_category();
      $a['show_town'] = $this->load_town();
      $a['show_area'] = $this->load_area();
      $a['show_root'] = $this->load_root();
      $a['show_nationality'] = $this->load_nationality();
      $a['show_status'] = $this->load_status();


      return $a;
  }

  public function data_table(){
    $this->load->library('table');
    $this->load->library('useclass');

    $this->table->set_template($this->useclass->grid_style());

    $code = array("data"=>"Code", "style"=>"width: 60px;");
    $name = array("data"=>"Name", "style"=>"");
    $nic = array("data"=>"NIC", "style"=>"");
    $action = array("data"=>"Action", "style"=>"width: 80px;");
    $this->table->set_heading($code, $name,$nic, $action);

    $this->db->select(array('code', 'name', 'nic'));
    $this->db->where('cl',$this->sd['cl']);
    $this->db->where('bc',$this->sd['branch']);
    $this->db->limit(10);
    $query = $this->db->get($this->mtb);

    foreach($query->result() as $r){

        $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
        if($this->user_permissions->is_delete('m_customer')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";}

        $code = array("data"=>$r->code, "value"=>"code");
        $name = array("data"=>$this->useclass->limit_text($r->name, 25), "style"=>" text-align: left;");
        $nic = array("data"=>$this->useclass->limit_text($r->nic, 25));

        $action = array("data"=>$but, "style"=>" text-align: center;");

        $this->table->add_row($code, $name,$nic, $action);
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
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try {
       $acc_code=$this->account_code();
       $acc_type=$this->account_type($acc_code);
       $acc_category=$this->account_category($acc_code);

       $this->db->trans_start();
       if(! isset($_POST['inactive'])){ $_POST['inactive'] = 0; }else{ $_POST['inactive'] = 1;}
       if(! isset($_POST['bl'])){ $_POST['bl'] = 0; }else{ $_POST['bl'] = 1;}
       if(! isset($_POST['is_tax'])){ $_POST['is_tax'] = 0; }else{ $_POST['is_tax'] = 1;}
       if(! isset($_POST['is_customer'])){ $_POST['is_customer'] = 0; }else{ $_POST['is_customer'] = 1;}
       if(! isset($_POST['is_guarantor'])){ $_POST['is_guarantor'] = 0; }else{ $_POST['is_guarantor'] = 1;}

       if($_POST['code_'] == "0" || $_POST['code_'] == ""){            
        if($_POST['type']=='1'){
            $cus_type=$_POST['nic'];
        }else{
            $cus_type=$this->get_next_code();
        }
        $cc=$this->get_next_code();
    }else{
        if($_POST['type']=='1'){
            $cus_type=$_POST['nic'];
        }else{
            $cus_type=$_POST['code'];
        }
        $cc=$_POST['code'];
    }          

    $a=array(
        "type"            =>$_POST['type'],
        "code"            =>$this->get_next_code(),
        "nic"             =>$cus_type,
        "name"            =>$_POST['name'],
        "company_name"    =>$_POST['company_name'],
        "address1"        =>$_POST['address1'],
        "address2"        =>$_POST['address2'],
        "address3"        =>$_POST['address3'],
        "email"           =>$_POST['email'],
        "doj"             =>$_POST['doj'],
        "dob"             =>$_POST['dob'],
        "category"        =>$_POST['category_id'],
        "area"            =>$_POST['area_id'],
        "credit_limit"    =>$_POST['credit_limit'],
        "credit_period"   =>$_POST['credit_period'],
        "is_tax"          =>$_POST['is_tax'],
        "tax_reg_no"      =>$_POST['tax_reg_no'],
        "inactive"        =>$_POST['inactive'],
        "is_customer"     =>$_POST['is_customer'],
        "is_guarantor"    =>$_POST['is_guarantor'],
    /*          "tp"=>$_POST['tp'],
    "mobile"=>$_POST['mobile'],*/
    "bl"              =>$_POST['bl'],
    "bl_reason"       =>$_POST['bl_reason'],
    "bl_officer"      =>$_POST['bl_officer'],
    "bl_date"         =>$_POST['bl_date'],
    "occupation"      =>$_POST['occupation'],
    "salary"          =>$_POST['salary'],
    "nationality"     =>$_POST['nationality_id'],
    "town"            =>$_POST['town_id'],
    "oc"              =>$this->sd['oc'],
    "root"            =>$_POST['root_id'],
    "cl"              =>$this->sd['cl'],
    "bc"              =>$this->sd['branch'],
    "cn_name"         =>$_POST['cont_name'],
    "cn_address"      =>$_POST['cont_address'],
    "cn_tel"          =>$_POST['cont_tel'],
    "cn_email"        =>$_POST['cont_email'],
    "postal_code"     =>$_POST['po_code'],
    "title"           =>$_POST['name_title'],
    "surname"         =>$_POST['sur_name'],
    "other_name"      =>$_POST['other_name'],
    "initials"        =>$_POST['initials'],
    "full_name"       =>$_POST['f_name'],
    "customer_status" =>$_POST['cus_status'],
);

    $resident=$mobile=$fax=$office="";

    for($x = 0; $x<10; $x++){
        if(isset($_POST['type1_'.$x])){
            if($_POST['type1_'.$x] != ""){
                if($_POST['contact1_'.$x]=="OFFICE"){
                    $office=$_POST['tp_'.$x];
                }
                if($_POST['contact1_'.$x]=="MOBILE"){
                    $mobile=$_POST['tp_'.$x];
                }
                if($_POST['contact1_'.$x]=="FAX"){
                    $fax=$_POST['tp_'.$x];
                }
                if($_POST['contact1_'.$x]=="RESIDENT"){
                    $resident=$_POST['tp_'.$x];
                }                       
            }
        }
    }   

    $guarantor=array(
        "code"=>$cc,
        "name"=>$_POST['name'],
        "full_name"=>$_POST['name'],
        "address1"=>$_POST['address1'],
        "address2"=>$_POST['address2'],
        "address3"=>$_POST['address3'],
        "email"=>$_POST['email'],
        "area"=>$_POST['area_id'],
        "epf"=>$_POST['tax_reg_no'],
        "is_black_list"=>$_POST['bl'],
        "reason"=>$_POST['bl_reason'],
        "officer"=>$_POST['bl_officer'],
        "date"=>$_POST['bl_date'],
        "occupation"=>$_POST['occupation'],
        "salary"=>$_POST['salary'],
        "office_tp"=>$office,           
        "mobile"=>$mobile,
        "fax"=>$fax,
        "tp"=>$resident
    );

    $a_update=array(
        "type"            =>$_POST['type'],
        "code"            =>$_POST['code'],
        "nic"             =>$cus_type,
        "name"            =>$_POST['name'],
        "company_name"    =>$_POST['company_name'],
        "address1"        =>$_POST['address1'],
        "address2"        =>$_POST['address2'],
        "address3"        =>$_POST['address3'],
        "email"           =>$_POST['email'],
        "doj"             =>$_POST['doj'],
        "dob"             =>$_POST['dob'],
        "category"        =>$_POST['category_id'],
        "area"            =>$_POST['area_id'],
        "credit_limit"    =>$_POST['credit_limit'],
        "credit_period"   =>$_POST['credit_period'],
        "is_tax"          =>$_POST['is_tax'],
        "tax_reg_no"      =>$_POST['tax_reg_no'],
        "inactive"        =>$_POST['inactive'],
        "is_customer"     =>$_POST['is_customer'],
        "is_guarantor"    =>$_POST['is_guarantor'],
/*            "tp"=>$_POST['tp'],
"mobile"=>$_POST['mobile'],*/
"bl"              =>$_POST['bl'],
"bl_reason"       =>$_POST['bl_reason'],
"bl_officer"      =>$_POST['bl_officer'],
"bl_date"         =>$_POST['bl_date'],
"occupation"      =>$_POST['occupation'],
"salary"          =>$_POST['salary'],
"nationality"     =>$_POST['nationality_id'],
"town"            =>$_POST['town_id'],
"oc"              =>$this->sd['oc'],
"root"            =>$_POST['root_id'],
"cl"              =>$this->sd['cl'],
"bc"              =>$this->sd['branch'],
"cn_name"         =>$_POST['cont_name'],
"cn_address"      =>$_POST['cont_address'],
"cn_tel"          =>$_POST['cont_tel'],
"cn_email"        =>$_POST['cont_email'],
"postal_code"     =>$_POST['po_code'],
"title"           =>$_POST['name_title'],
"surname"         =>$_POST['sur_name'],
"other_name"      =>$_POST['other_name'],
"initials"        =>$_POST['initials'],
"full_name"       =>$_POST['f_name'],
"customer_status" =>$_POST['cus_status'],
);


    $m_account=array(
     "type"=>$acc_type,
     "code"=>$this->get_next_code(),
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
        if(isset($_POST['type1_'.$x])){
            if($_POST['type1_'.$x] != ""){
                $c[]= array(
                    "code"=>$cc,
                    "type"=>strtoupper($_POST['type1_'.$x]),
                    "description"=>$_POST['des_'.$x],
                    "tp"=>$_POST['tp_'.$x]
                );              
            }
        }
    }   


    for($x = 0; $x<15; $x++){
      if($_POST['type_'.$x] != ""){
         $b[]= array(
            "code"=>$cc,
            "type"=>$_POST['tcode_'.$x],
            "ddate"=>$_POST['ddate_'.$x],
            "comments"=>$_POST['comment_'.$x]
        );				
     }
 }


 if($_POST['code_'] == "0" || $_POST['code_'] == ""){
    if($this->user_permissions->is_add('m_customer')){
        unset($_POST['code_']);
        $this->db->insert($this->mtb,$a);
        $this->db->insert('m_account',$m_account);
        if($_POST['is_guarantor']==1){
            $this->db->insert('m_guarantor',$guarantor);
        }
        if(isset($b)){if(count($b)){ $this->db->insert_batch("m_customer_events", $b ); }}
        if(isset($c)){if(count($c)){ $this->db->insert_batch("m_customer_contact", $c );}}
        echo $this->db->trans_commit();
    }else{
        echo "No permission to save records";
        $this->db->trans_commit();
    }

}else{
    if($this->user_permissions->is_edit('m_customer')){
      $this->set_delete();
      $this->db->where("code", $_POST['code_']);
      $this->db->update($this->mtb, $a_update);

      $this->db->where("code", $_POST['code_']);
      $this->db->update('m_account',$m_account_update);

      if($_POST['is_guarantor']==1){
        $this->db->insert('m_guarantor',$guarantor);
    }
    unset($_POST['code_']);            
    if(isset($b)){if(count($b)){ $this->db->insert_batch("m_customer_events", $b ); }}  
    if(isset($c)){if(count($c)){ $this->db->insert_batch("m_customer_contact", $c );}}
    echo $this->db->trans_commit();
}else{
    echo "No permission to edit records";
    $this->db->trans_commit();
}      
}

} catch ( Exception $e ) { 
    $this->db->trans_rollback();
    echo $e->getMessage()." - Operation fail please contact admin"; 
} 
}

private function set_delete(){
	$this->db->where("code", $_POST['code_']);
	$this->db->delete("m_customer_events");

    $this->db->where("code", $_POST['code_']);
    $this->db->delete("m_customer_contact");

    $this->db->where("code", $_POST['code_']);
    $this->db->delete("m_guarantor");


}

public function check_code(){
    $sql="SELECT * FROM m_customer WHERE nic ='".$_POST['code']."' limit 1 ";            
    $query=$this->db->query($sql);
    if($query->num_rows()>0){           
        if($query->row()->cl == $this->sd['cl'] && $query->row()->bc == $this->sd['branch']){
            $a['data']=$query->result();
            $a['edit']=1;
        }else{
            $a['data']=$query->result();
            $a['edit']=0;
        }
    }else{
        $a['data']=2;
    }    	
    echo json_encode($a);
}

public function check_nic(){
    $sql="SELECT * FROM m_customer WHERE nic ='".$_POST['code']."' limit 1 ";            
    $query=$this->db->query($sql);
    if($query->num_rows()>0){
        $a=$query->result();
    }else{
        $a=2;
    }       
    echo json_encode($a);
}

public function load(){

    $sql="SELECT
    MC.`code` AS code_id,
    `nic`,
    is_customer,
    is_guarantor,
    `name`,
    `company_name`,
    `address1`,
    `address2`,
    `address3`,
    `email`,
    `doj`,
    `dob`,
    `category`,
    `area`,
    `control_acc`,
    `credit_limit`,
    `credit_period`,
    `is_tax`,
    `tax_reg_no`,
    `inactive`,
 /*             `tp`,
 `mobile`,*/
 `bl`,
 `bl_reason`,
 `bl_officer`,
 `bl_date`,
 `occupation`,
 `salary`,
 `nationality`,
 `cl`,
 `bc`,
 `town`,
 `root`,
 MC.`type`,
             -- `auto_num`,
             `cn_name`,
             `cn_address`,
             `cn_tel`,
             `cn_email`,
             `postal_code`,
             title,
             surname,
             other_name,
             initials,
             full_name,
             RCC.`description` AS category_name,
             RA.`description` AS area_name,
             RN.`description` AS nationality_name,
             RT.`description` AS town_name,
             RR.`description` AS root_name,
             MC.`customer_status`,
             cs.`color`,
             cs.`description` AS cus_status

             FROM `m_customer` MC
             JOIN r_cus_category RCC ON MC.category=RCC.`code`
             JOIN r_area RA ON MC.`area`=RA.`code`
             JOIN r_nationality RN ON MC.`nationality`=RN.`code`
             JOIN r_town RT ON MC.`town`=RT.`code`
             JOIN r_root RR ON MC.`root`=RR.`code`
             LEFT JOIN m_customer_status cs ON MC.`customer_status` = cs.`code`
             WHERE MC.`code`='".$_POST['code']."' ";

             $query = $this->db->query($sql);

             if ($query->num_rows() > 0) {
                $a["data"] = $this->db->query($sql)->first_row();
            } else {
                $a["data"] = 2;
            }


    //$this->db->where('code', $_POST['code']);
	//$this->db->limit(1);
	//$a["data"] = $this->db->get($sql)->first_row();

	//$this->db->where("code", $_POST['code']);
            $sql_event="SELECT c.* , r.`description` 
            FROM m_customer_events c
            JOIN r_return_reason r ON r.`code` = c.`type`
            WHERE c.code='".$_POST['code']."'";

            $a['c'] = $this->db->query($sql_event)->result();

            $this->db->where("code", $_POST['code']);
            $a['b'] = $this->db->get("m_customer_contact")->result();

            $a['acc']=$this->utility->get_account_balance($_POST['code']);	

            echo json_encode($a);
        }

        public function delete_validation(){
            $status=1;
            $codes=$_POST['code'];
            $check_cancellation = $this->utility->check_account_trans($codes,'Customer','m_customer','t_account_trans','acc_code');
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
            if($this->user_permissions->is_delete('m_customer')){
                $delete_validation_status=$this->delete_validation();
                if($delete_validation_status==1){
                	$this->db->where('code', $_POST['code']);
                	$this->db->limit(1);
                	$this->db->delete($this->mtb);

                    $this->db->where('code', $_POST['code']);
                    $this->db->limit(1);
                    $this->db->delete('m_account');

                    $this->db->where("code", $_POST['code']);
                    $this->db->delete("m_guarantor");

                    $this->db->where("code", $_POST['code']);
                    $this->db->delete("m_customer_contact");
                    echo $this->db->trans_commit();
                }else{
                    echo $delete_validation_status;
                    $this->db->trans_commit();
                }    
            }else{
                echo "No permission to delete records";
                $this->db->trans_commit();
            }
        }catch( Exception $e ){ 
            $this->db->trans_rollback();
            echo $e->getMessage()."Operation fail please contact admin"; 
        }
    }
    
    
    
    
    
    public function auto_com(){
        $q=$_GET['q'];
        $sql="SELECT `code`, `m_customer`.`name` FROM (`m_customer`) 
        WHERE (`bl` = '0'AND `inactive` = '0')
        AND (`code` LIKE '%$q%' OR `m_customer`.`name` LIKE '%$q%' )";
        $query=$this->db->query($sql);

        $abc = "";
        foreach($query->result() as $r){
            $abc .= $r->code."|".$r->name;
            $abc .= "\n";
        }
        
        echo $abc;
    }  
    


    public function select(){
/*        $query = $this->db->limit(100)->get($this->mtb);
*/        
$s = "<select name='customer' id='customer' >";
$s .= "<option value='0'>---</option>";


/*	   $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code."</option>";
*/        $s .= "</select>";

return $s;
}

public function get_next_code(){
    $sql="SELECT LPAD((SELECT IFNULL(MAX(SUBSTRING(CODE,5)),0)+1 FROM m_customer WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'),6,0) as v";

    $code=$this->db->query($sql)->first_row()->v;
    $cus_code = $this->sd['cl'].$this->sd['branch'].$code;

    return $cus_code; 

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

public function select_category(){

}



public function PDF_report(){
  $this->db->select(array('name','address','tp','fax','email'));
  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $r_detail['branch']=$this->db->get('m_branch')->result();
  $r_detail['page']='A4';
  $r_detail['orientation']='L'; 

  $sql="SELECT c.`code`,
  c.`name`,
  c.`nic`, 
  CONCAT( c.`address1`   ,c.address2 ,c.address3) as address,
  c.`email`,
  c.`category`,
  t.`description` AS CategoryName,
 /*                  c.`tp`,
 c.`mobile`*/
 FROM `m_customer` c
 LEFT JOIN `r_cus_category` t ON c.`category`=t.`code`
 WHERE bc='".$this->sd['branch']."' 
 ORDER BY  c.`code`";      

 $query=$this->db->query($sql);
 if ($query->num_rows() > 0) {
     $r_detail['customer']=$query->result();  
     $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
 }else{
    echo"<script>alert('No records');history.go(-1);</script>";
}



}




public function load_category(){
    $sql = "SELECT IFNULL(def_category,0) AS cus_cat , r_cus_category.`description`
    FROM m_branch 
    LEFT JOIN r_cus_category ON r_cus_category.code = m_branch.def_category 

    WHERE bc='".$this->sd['branch']."'
    AND cl ='".$this->sd['cl']."' limit 1";
    $result = $this->db->query($sql)->row();
    return ($result);

}

public function load_town(){
    $sql = "SELECT IFNULL(def_town,0) AS cus_town , r_town.`description` 
    FROM m_branch 
    LEFT JOIN r_town ON r_town.code = m_branch.def_town
    WHERE bc='".$this->sd['branch']."'
    AND cl ='".$this->sd['cl']."' limit 1";

    $result = $this->db->query($sql)->row();
    return ($result);
}

public function load_area(){
    $sql = "SELECT IFNULL(def_area,0) AS cus_area,r_area.`description`
    FROM m_branch 
    LEFT JOIN `r_area` ON `r_area`.code = m_branch.def_area
    WHERE bc='".$this->sd['branch']."'
    AND cl ='".$this->sd['cl']."' limit 1";
    $result = $this->db->query($sql)->row();

    return ($result);
}
public function load_root(){
    $sql = " SELECT IFNULL(def_route,0) AS cus_route , r_root.`description`
    FROM m_branch 
    LEFT JOIN r_root ON r_root.code = m_branch.def_route
    WHERE bc='".$this->sd['branch']."'
    AND cl ='".$this->sd['cl']."' limit 1";
    $result = $this->db->query($sql)->row();

    return ($result);
}
public function load_nationality(){
    $sql = "SELECT IFNULL(def_nationality,0) AS cus_nationality , r_nationality.description
    FROM m_branch
    LEFT JOIN `r_nationality`  ON `r_nationality`.`code` = m_branch.def_nationality
    WHERE bc='".$this->sd['branch']."'
    AND cl ='".$this->sd['cl']."' limit 1";

    $result = $this->db->query($sql)->row();

    return ($result);
}

public function load_status(){
    $sql = "SELECT IFNULL(def_status,0) AS cus_status , m_customer_status.description,m_customer_status.color
    FROM m_branch
    LEFT JOIN `m_customer_status`  ON `m_customer_status`.`code` = m_branch.def_status
    WHERE bc='".$this->sd['branch']."'
    AND cl ='".$this->sd['cl']."' limit 1";

    $result = $this->db->query($sql)->row();

    return ($result);
}




}