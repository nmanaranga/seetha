<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_loyalty_card_issue extends CI_Model{

  private $sd;
  private $mtb;

  private $mod = '003';

  function __construct(){
   parent::__construct();

   $this->sd = $this->session->all_userdata();
   $this->load->database($this->sd['db'], true);
   $this->m_customer = $this->tables->tb['m_customer'];
   $this->t_sales_sum=$this->tables->tb['t_credit_sales_sum'];
   $this->load->model('user_permissions');
 }

 public function base_details(){

 }

 public function save(){ 

   $data= array(
    'cl'              =>$this->sd['cl']  ,
    'bc'              =>$this->sd['branch'] ,
    'card_no'         =>$_POST['card_no']  ,
    'customer_id'     =>$_POST['customer_id']  ,
    'issue_date'      =>$_POST['ddate']  ,
    'expire_date'     =>$_POST['edate']  ,
    'category'        =>$_POST['card_cat']  ,
    'oc'              =>$this->sd['oc']  
  );

   $update=array(
    'loyalty_card_no'=>$_POST['card_no']
  );



   if($_POST['code_'] == "0" || $_POST['code_'] == ""){
    if($this->user_permissions->is_add('t_loyalty_card_issue')){
      unset($_POST['code_']);
      $this->db->insert('t_loyalty_card', $data);
      echo $this->db->insert('t_loyalty_card_issue', $data);

      $this->db->where("code", $_POST['customer_id']);
      $this->db->update('m_customer', $update);
      $this->sendSms();
    }else{
      echo "No permission to save records";
    }    
  }else{
    if($this->user_permissions->is_edit('t_loyalty_card_issue')){
      $this->db->where("card_no", $_POST['card_no']);
      unset($_POST['code_']);
      echo $this->db->update('t_loyalty_card', $data);

      $this->db->where("card_no", $_POST['card_no']);
      unset($_POST['code_']);
      echo $this->db->update('t_loyalty_card_issue', $data);
    }else{
      echo "No permission to edit records";
    } 
  }
}

public function check_code(){

  $this->db->select('card_no,customer_id,issue_date,expire_date,t_loyalty_card_issue.category,m_customer.name,CONCAT(m_customer.address1,m_customer.address2,m_customer.address3 ) AS address,m_customer.email,m_customer.tp,t_loyalty_card_category.des');
  $this->db->from('t_loyalty_card_issue');
  $this->db->join('m_customer','m_customer.code=t_loyalty_card_issue.customer_id');
  $this->db->join('t_loyalty_card_category','t_loyalty_card_category.code=t_loyalty_card_issue.category');
  $this->db->where('card_no', $_POST['card_no']);
  $this->db->limit(1);
  $query = $this->db->get();
  if($query->num_rows()>0){
    echo json_encode($query->first_row());
  }else{
    echo 2;
  }
}

public function load(){

	$this->db->where('card_no', $_POST['code']);
	$this->db->limit(1);
	echo json_encode($this->db->get($this->t_loyalty_card_issue)->first_row());
}

public function delete(){

 if($this->user_permissions->is_delete('t_loyalty_card_issue')){
   $this->db->where('code', $_POST['code']);
   $this->db->limit(1);
   echo $this->db->delete($this->mtb);
 }else{
  echo "No permission to delete records";
}

}

public function customer_list(){

  if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

  $sql = "SELECT c.*,cc.tp as cus_tp FROM m_customer c
  JOIN(SELECT CODE,GROUP_CONCAT(tp)AS tp FROM `m_customer_contact` cc GROUP BY CODE)cc ON cc.code=c.code
  WHERE c.inactive = '0' AND (c.code LIKE '%$_POST[search]%' OR name LIKE '%$_POST[search]%' OR nic LIKE '%$_POST[search]%')
  LIMIT 20";


  $query = $this->db->query($sql);

  $a  = "<table id='item_list' style='width : 100%' >";
  $a .= "<thead><tr>";
  $a .= "<th class='tb_head_th'>Code</th>";
  $a .= "<th class='tb_head_th'>Name</th>";
  $a .= "<th class='tb_head_th'>NIC</th>";
  $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td><td>&nbsp;</td></tr>";

  foreach($query->result() as $r){
    if($r->bl!="0"){
      $a .= "<tr class='cl' style='background-color:#B5B491;' >";
    }else{
      $a .= "<tr class='cl'>";
    }
    $a .= "<td>".$r->code."</td>";
    $a .= "<td>".$r->name."</td>";
    $a .= "<td>".$r->nic."</td>";
    $a .= "<td style='display:none;'>".$r->address1." ".$r->address2." ".$r->address3."</td>";   
    $a .= "<td style='display:none;'><input type='hidden' class='' value='".$r->cus_tp."' title='".$r->cus_tp."' /></td>";        
    $a .= "<td style='display:none;'><input type='hidden'  value='".$r->email."' title='".$r->email."' /></td>"; 
    $a .= "</tr>";
  }
  $a .= "</table>";
  echo $a;
}


public function check_issue_card(){
 $sql = "SELECT * FROM m_customer c WHERE c.code='".$_POST['code']."' AND loyalty_card_no!=''";
 $query = $this->db->query($sql);

 if($query->num_rows()>0){
  echo 1;
}else{
  echo 0;
}

}


public function sendSms() {
  /*$sql="SELECT t.nno,
  REPLACE(REPLACE( REPLACE(SUBSTRING(cc.tp,2,10),'-','') ,' ',''),'/','')  AS tp,
  SUBSTRING(IFNULL(c.name,'Customer'),1,20) AS cus_name,
  t.`paid_amount` AS payment
  FROM t_hp_receipt_sum t
  LEFT JOIN m_customer c ON c.code=t.`customer`
  LEFT JOIN m_customer_contact cc ON cc.`code` = c.`code`
  WHERE t.cl='".$this->sd['cl']."' AND t.bc='".$this->sd['branch']."' 
  AND t.nno='".$noo."' 
  LIMIT 1";

  $query = $this->db->query($sql);
  $nno=$query->first_row()->nno;*/
  $sender='94778936829';
  $c_name="namal";
  $message="test Message";

  $module="LOYALTY CARD ISSUE";

  if($sender!="" ){

    $this->load->model('sms_generate');
    $result=$this->sms_generate->sendSmsForMessage();

  }else{
    $result="No Mobile Number";
  }

  return $result;

}



}