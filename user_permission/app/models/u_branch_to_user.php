
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class u_branch_to_user extends CI_Model {

  private $sd;
  private $mtb;
  private $tb_po_trans;
  private $max_no;
  private $mod = '003';
  private $trans_code="23";
  private $sub_trans_code="23";
  private $qty_out="0";

  function __construct(){
  	parent::__construct();
  	
  	$this->sd = $this->session->all_userdata();
    // $this->load->database($this->sd['up_db'], true);
  	$this->mtb = $this->tables->tb['u_user_role'];
  	$this->tb_po_trans= $this->tables->tb['t_po_trans'];
    $this->load->model('user_permissions');

    $this->load->model('utility');
  }

  public function base_details(){

    $a['max_no']= $this->utility->get_max_no2("u_branch_to_user","nno");

    return $a;
  }

  public function load_branch(){
    $this->db->where("cl",$this->sd['up_cl']);
    $query = $this->db->get('m_branch');

    $A = "<select name='ship_to_bc' id='ship_to_bc'>";
    $B="";
    foreach($query->result() as $r){
      if($r->bc==$this->sd['branch']){
        $C = "<option title='".$r->name."'  value='".$r->bc."' selected='selected'>".$r->bc." | ".$r->name."</option>";
      }else{
        $B .= "<option title='".$r->name."' value='".$r->bc."' >".$r->bc." | ".$r->name."</option>";
      }     
    }       
    $D = "</select>";
    $s = $A.$C.$B.$D;
    return $s;
  }

  public function load_cluster(){
    $this->db->select(array("description"));
    $this->db->where("code",$this->sd['up_cl']);
    return $this->db->get('m_cluster')->row()->description;
  }

  public function validation(){
    $status=1;

    return $status;
  }

  public function save(){
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
      throw new Exception($errLine); 
    }
    set_error_handler('exceptionThrower'); 
    try {

      $this->max_no = $this->utility->get_max_no2("u_branch_to_user", "nno");
      $validation_status=$this->validation();
      
      if($validation_status==1){

        $this->db->trans_start();

        $_POST['cl']=$this->sd['up_cl'];
        $_POST['branch']=$this->sd['up_branch'];
        $_POST['oc']=$this->sd['up_oc']; 


        for($x = 0; $x<$_POST['hid_tot']; $x++){
          if(isset($_POST['u_roll'],$_POST['u_user'],$_POST['u_cluster'],$_POST['active_'.$x])){
            if($_POST['active_'.$x] == 1){
              $u_branch_to_user[]=array(
               "nno"=>$this->max_no,
               "ddate"=>$_POST['date'],
               "user_id"=>$_POST['u_user'],
               "cl"=>$_POST['cl_'.$x],
               "bc"=>$_POST['bc_'.$x],
               "is_active"=>$_POST['active_'.$x],
               "from_date"=> $_POST['datefrom_'.$x],
               "to_date"=> $_POST['dateto_'.$x]         
             );    
            }
          }
        }

        for($x = 0; $x<$_POST['hid_tot']; $x++){
          if(isset($_POST['u_roll'],$_POST['u_user'],$_POST['u_cluster'],$_POST['active_'.$x])){
            if($_POST['active_'.$x] == 1){
              $u_branch_to_user_history[]=array(
               "nno"=>$this->max_no,
               "ddate"=>$_POST['date'],
               "user_id"=>$_POST['u_user'],
               "cl"=>$_POST['cl_'.$x],
               "bc"=>$_POST['bc_'.$x],
               "is_active"=>$_POST['active_'.$x],
               "from_date"=> $_POST['datefrom_'.$x],
               "to_date"=> $_POST['dateto_'.$x]         
             );    
            }
          }
        }
      //echo '<pre>'.print_r($u_branch_to_user,true).'</pre>';
        if($_POST['hid_user'] == "1"){
          if($this->user_permissions->is_add('u_branch_to_user')){
            if (isset($u_branch_to_user)) {
              if(count($u_branch_to_user)){$this->db->insert_batch("u_branch_to_user",$u_branch_to_user);}
            }
            if (isset($u_branch_to_user_history)) {
              if(count($u_branch_to_user_history)){$this->db->insert_batch("u_branch_to_user_history",$u_branch_to_user_history);}
            }
          // $this->utility->save_logger("SAVE",33,$this->max_no,$this->mod);
            echo $this->db->trans_commit();
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          }

        }else if($_POST['hid_user'] == "2"){
          if($this->user_permissions->is_edit('u_branch_to_user')){
            $this->set_delete();
            if (isset($u_branch_to_user)) {
              if(count($u_branch_to_user)){$this->db->insert_batch("u_branch_to_user",$u_branch_to_user);}
            }
            if (isset($u_branch_to_user_history)) {
              if(count($u_branch_to_user_history)){$this->db->insert_batch("u_branch_to_user_history",$u_branch_to_user_history);}
            }
          //$this->utility->save_logger("EDIT",33,$this->max_no,$this->mod);          
            echo $this->db->trans_commit();     
          }else{
            echo "No permission to edit records";
            $this->db->trans_commit();
          }   
        }
      }else{
        echo $validation_status;
        $this->db->trans_commit(); 
      }
    }catch(Exception $e){ 
      $this->db->trans_rollback();
      echo $e->getMessage()."Operation fail please contact admin";
    }         
  }





  private function set_delete(){
    $this->db->where("user_id", $_POST['u_user']);
    $this->db->delete("u_branch_to_user");

  }

  public function check_code(){
   $this->db->where('code', $_POST['code']);
   $this->db->limit(1);

   echo $this->db->get($this->mtb)->num_rows;
 }


 public function load(){

  $x=0;  

  $this->db->select(array(
    't_internal_transfer_order_sum.ddate' ,
    't_internal_transfer_order_sum.ref_no' ,
    't_internal_transfer_order_sum.cl',
    'm_cluster.description',
    't_internal_transfer_order_sum.bc',
    'm_branch.name',
    't_internal_transfer_order_sum.note',

  ));

  $this->db->join('m_branch','m_branch.bc=t_internal_transfer_order_sum.bc');
  $this->db->join('m_cluster','m_cluster.code=t_internal_transfer_order_sum.cl');
  $this->db->where('t_internal_transfer_order_sum.cl',$this->sd['up_cl'] );
  $this->db->where('t_internal_transfer_order_sum.bc',$this->sd['up_branch'] );
  $this->db->where('t_internal_transfer_order_sum.nno',$_POST['id']);
  $query=$this->db->get('t_internal_transfer_order_sum');




  if($query->num_rows()>0){

    $a['sum']=$query->result();
  }else{

   $x=0;
 } 


 $this->db->select(array(
  't_internal_transfer_order_det.nno',
  't_internal_transfer_order_det.item_code',
  'm_item.description',
  'm_item.model',
  't_internal_transfer_order_det.item_cost',
  'm_item.min_price',
  'm_item.max_price',
  'qry_current_stock.qty',
  't_internal_transfer_order_det.qty as quantity'

));

 $this->db->from('t_internal_transfer_order_det');
 $this->db->join('m_item','m_item.code=t_internal_transfer_order_det.item_code');
 $this->db->join('qry_current_stock','m_item.code=qry_current_stock.item');

 $this->db->where('t_internal_transfer_order_det.cl',$this->sd['cl'] );
 $this->db->where('t_internal_transfer_order_det.bc',$this->sd['branch'] );
 $this->db->where('t_internal_transfer_order_det.nno',$_POST['id']);
 $query=$this->db->get();




 if($query->num_rows()>0){
  $a['det']=$query->result();
}else{
  $x=2;
}           

if($x==0){
  echo json_encode($a);
}else{
  echo json_encode($x);
}


}



public function delete(){


  $this->db->where('cl',$this->sd['up_cl']);
  $this->db->where('bc',$this->sd['up_branch']);
  $this->db->where('nno',$_POST['id']);
  echo $this->db->update('t_internal_transfer_order_sum', array("is_cancel"=>1));    


}



public function load_bc(){

  $cluster=$_POST['cluster'];

  if($cluster==""){
    $sql="SELECT cl, bc, name,description FROM m_branch JOIN m_cluster ON m_cluster.`code` = m_branch.`cl`";
  }else{
    $sql="SELECT cl, bc, name,description FROM m_branch JOIN m_cluster ON m_cluster.`code` = m_branch.`cl` WHERE cl = '$cluster'";
  }

  $query=$this->db->query($sql);
  if($query->num_rows>0){
    $a=$query->result();

  }else{
    $a=2;
  }

  echo json_encode($a);

}

public function check_user(){
  $user = $_POST['user_id'];

  $sql="SELECT * FROM u_branch_to_user WHERE user_id='$user'";

  $query=$this->db->query($sql);
  if($query->num_rows>0){
    $a=1;

  }else{
    $a=2;
  }
  echo json_encode($a);

}

public function load_exist_bc_detail(){
  $cluster = $_POST['cluster'];
  $user = $_POST['user_id'];

  if($cluster==""){
    $sql="SELECT  m_branch.cl, 
    m_cluster.`description`,
    m_branch.bc, 
    m_branch.name, 
    n.is_active,
    n.from_date,
    n.to_date
    FROM u_branch_to_user b
    RIGHT JOIN m_branch ON m_branch.`bc` = b.`bc` 
    LEFT JOIN m_cluster ON m_cluster.`code` = m_branch.`cl`
    LEFT JOIN (SELECT cl,bc,is_active,from_date,to_date FROM u_branch_to_user WHERE user_id='$user') n ON n.cl= b.cl 
    AND n.bc= b.bc
    WHERE m_branch.inactive=0
    GROUP BY cl,bc";
  }else{
    $sql="SELECT  m_branch.cl, 
    m_cluster.`description`,
    m_branch.bc, 
    m_branch.name, 
    n.is_active,
    n.from_date,
    n.to_date
    FROM u_branch_to_user b
    RIGHT JOIN m_branch ON m_branch.`bc` = b.`bc` 
    LEFT JOIN m_cluster ON m_cluster.`code` = m_branch.`cl`
    LEFT JOIN (SELECT cl,bc,is_active,from_date,to_date FROM u_branch_to_user WHERE user_id='$user') n ON n.cl= b.cl 
    AND n.bc= b.bc
    where m_branch.cl='$cluster' AND m_branch.inactive=0
    GROUP BY cl,bc";
  }



  $query=$this->db->query($sql);
  if($query->num_rows>0){
    $a=$query->result();

  }else{
    $a=2;
  }

  echo json_encode($a);
}


public function PDF_report(){



  $r_detail['deliver_date'];
  $r_detail['ship_to_bc'];
  $r_detail['supplier'];
  $r_detail['ddate'];
  $r_detail['total_amount'];
  $r_detail['ref_no'];
  $r_detail['branch'];


  $this->db->join('m_branch','t_internal_transfer_order_sum.bc=m_branch.bc');
  $this->db->where("nno",$_POST['qno']);
  $query= $this->db->get('t_internal_transfer_order_sum'); 
  if ($query->num_rows() > 0){
    foreach ($query->result() as $row){
      $r_detail['deliver_date']=$row->deliver_date;
      $r_detail['ship_to_bc']=$row->ship_to_bc;
      $r_detail['supplier']=$row->supplier;
      $r_detail['ddate']=$row->ddate;
      $r_detail['total_amount']=$row->total_amount;
      $r_detail['branch'] = $row->name;

    }
  } 



  $invoice_number= $this->utility->invoice_format($_POST['qno']);

  $qry = $this->db->query("SELECT name FROM m_branch WHERE bc='".$this->sd['up_branch']."'")->row();



  $session_array = array(
   $this->sd['up_cl'],
   $this->sd['up_branch'],
   $invoice_number,
   $qry->name
 );


  $r_detail['session'] = $session_array;

  $this->db->select(array('name','address','tp','fax','email'));
  $this->db->where("cl",$this->sd['up_cl']);
  $this->db->where("bc",$this->sd['up_branch']);
  $r_detail['branch']=$this->db->get('m_branch')->result();


  $this->db->select(array('name','address','tp','fax','email'));
  $this->db->where("cl",$this->sd['up_cl']);
  $this->db->where("bc",$r_detail['ship_to_bc']);
  $r_detail['ship_branch']=$this->db->get('m_branch')->result();


  $r_detail['qno']=$_POST['qno'];
  $r_detail['page']=$_POST['page'];
  $r_detail['header']=$_POST['header'];
  $r_detail['orientation']=$_POST['orientation'];
  $r_detail['type']=$_POST['type'];





  $sql="SELECT 
  `m_supplier`.`code`, 
  `m_supplier`.`name`, 
  `m_supplier`.`address1`, 
  IFNULL (`m_supplier`.`address2`,'') address2,
  IFNULL (`m_supplier`.`address3`,'') address3, 
  IFNULL (`m_supplier`.`email`,'') email,
  IFNULL (`m_supplier_contact`.`tp`,'') tp FROM (`m_supplier`) 
  LEFT JOIN `m_supplier_contact` ON `m_supplier_contact`.`code`=`m_supplier`.`code` WHERE `m_supplier`.`code`='".$r_detail['supplier']."' LIMIT 1";


  $r_detail['suppliers']=$this->db->query($sql)->result();


  $sql="SELECT 
  `m_item`.`code`,
  `m_item`.`description`,
  `m_item`.`model`,
  `m_item`.`purchase_price`,
  `m_item`.`min_price`,
  `m_item`.`max_price`,
  `t_internal_transfer_order_det`.`qty`

  FROM `t_internal_transfer_order_det` 
  JOIN m_item ON m_item.`code`=t_internal_transfer_order_det.`item_code`
  WHERE `t_internal_transfer_order_det`.`nno`=".$_POST['qno']." AND cl='".$this->sd['up_cl']."' AND bc='".$this->sd['up_branch']."'";

  $query=$this->db->query($sql);
  if($query->num_rows>0){
    $r_detail['det']=$query->result();

  }else{
    $r_detail['det']=2;
  }


  $this->load->view($_POST['by'].'_'.'pdf',$r_detail);

}

}