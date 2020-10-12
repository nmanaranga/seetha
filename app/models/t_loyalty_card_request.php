<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_loyalty_card_request extends CI_Model{

  private $sd;
  private $mtb;

  private $mod = '003';

  function __construct(){
   parent::__construct();

   $this->sd = $this->session->all_userdata();
   $this->load->database($this->sd['db'], true);
   $this->load->model('user_permissions');
   $this->max_no=$this->utility->get_max_no("t_loyalty_card_request","nno");
 }

 public function base_details(){
  $a['max_no']= $this->utility->get_max_no("t_loyalty_card_request","nno");
  return $a;
}

public function save(){ 

 $data= array(
  'cl'              =>$this->sd['cl']  ,
  'bc'              =>$this->sd['branch'] ,
  'nno'             =>$this->max_no,
  'ddate'           =>$_POST['ddate']  ,
  'cus_code'        =>$_POST['customer_id']  ,
  'from_cl'         =>$this->sd['cl']  ,
  'from_bc'         =>$this->sd['branch'],
  'to_cl'           =>'SH'  ,
  'to_bc'           =>'PE'  ,
  'comments'        =>$_POST['note']  ,
  'oc'              =>$this->sd['oc']  
);

 if($_POST['hid'] == "0" || $_POST['hid'] == ""){
  if($this->user_permissions->is_add('t_loyalty_card_request')){

    $this->db->insert('st_loyalty_card_request', $data);
    echo $this->db->trans_commit();

    
  }else{
    echo "No permission to save records";
  }    
}else{
  if($this->user_permissions->is_edit('t_loyalty_card_request')){

   $this->db->where("nno", $_POST['hid']);
   $this->db->where("cl", $this->sd['cl']);
   $this->db->where("bc", $this->sd['branch']);
   $this->db->delete("t_loyalty_card_request");

   $this->db->insert('t_loyalty_card_request', $data);
   echo $this->db->trans_commit();
 }else{
  echo "No permission to edit records";
}

}
}



public function load(){

 $x=0;  

 $this->db->select(array(
  't_loyalty_card_request.nno' ,
  't_loyalty_card_request.ddate' ,
  't_loyalty_card_request.cus_code' ,
  't_loyalty_card_request.comments',
  't_loyalty_card_request.is_cancel',
  't_loyalty_card_request.is_post',
  'm_customer.name',
  'm_customer.email',
  'CONCAT(m_customer.address1,m_customer.address2,m_customer.address3)as address',
  'GROUP_CONCAT(m_customer_contact.tp)AS tp'
));

 $this->db->join('m_customer','m_customer.code=t_loyalty_card_request.cus_code');
 $this->db->join('m_customer_contact','m_customer_contact.code=m_customer.code');
 $this->db->where('t_loyalty_card_request.cl',$this->sd['cl'] );
 $this->db->where('t_loyalty_card_request.bc',$this->sd['branch'] );
 $this->db->where('t_loyalty_card_request.nno',$_POST['id']);
 $this->db->GROUP_BY('t_loyalty_card_request.nno','t_loyalty_card_request.cus_code');
 $query=$this->db->get('t_loyalty_card_request');

 if($query->num_rows()>0){
  $a['sum']=$query->result();
}else{
  $x=0;
} 

if($x==0){
  echo json_encode($a);
}else{
  echo json_encode($x);
}
}

public function delete(){

 if($this->user_permissions->is_delete('t_loyalty_card_request')){
  $this->db->where('nno', $_POST['code']);
  $this->db->where('cl', $this->sd['cl']);
  $this->db->where('bc', $this->sd['branch']);
  $this->db->limit(1);
  echo $this->db->delete('t_loyalty_card_request');
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

}




