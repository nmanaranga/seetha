<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_po_sum extends CI_Model {

  private $sd;
  private $mtb;
  private $tb_po_trans;
  private $max_no;
  private $mod = '003';
  private $trans_code;
  private $sub_trans_code;
  private $qty_out="0";

  function __construct(){
  	parent::__construct();
  	
  	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->mtb = $this->tables->tb['t_po_sum'];
    $this->load->model('user_permissions');
    $this->tb_po_trans= $this->tables->tb['t_po_trans'];

    $this->load->model('utility');
  }
  
  public function base_details(){
   	//$a['max_no'] = $this->utility->get_max_no("t_po_sum","nno");
    $a['max_no'] = $this->get_max_no_type1("t_po_sum","nno");
    $a['cluster']=$this->load_cluster();
    $a['branch'] =$this->load_branch();
    return $a;
  }

  public function load_branch(){
    $this->db->where("cl",$this->sd['cl']);
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
    $this->db->where("code",$this->sd['cl']);
    return $this->db->get('m_cluster')->row()->description;
  }

  public function validation(){
    $status=1;

    $this->max_no=$this->get_max_no_type1("t_po_sum","nno",$_POST['type']);

    $check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_po_sum');
    if($check_is_delete!=1){
      return "Purchase order already deleted";
    }
    $supplier_validation = $this->validation->check_is_supplier($_POST['supplier_id']);
    if ($supplier_validation != 1){
      return "Please enter valid supplier";   
    }
    return $status;
  }

  public function save(){

    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
      throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try {

      $this->max_no=$this->get_max_no_type1("t_po_sum","nno",$_POST['type']);
      if($_POST['type']==1){
        $this->trans_code="23";
        $this->sub_trans_code="23";
      }else if ($_POST['type']==2){
        $this->trans_code="46";
        $this->sub_trans_code="46";
      }
      

      $validation_status=$this->validation();
      
      if($validation_status==1){

        $_POST['cl']=$this->sd['cl'];
        $_POST['branch']=$this->sd['branch'];
        $_POST['oc']=$this->sd['oc']; 



        $dpo=isset($_POST['dpo'])?'1':'0';

        $t_po_sum=array(
         "cl"           =>$_POST['cl'],
         "bc"           =>$_POST['branch'],
         "nno"          =>$this->max_no,
         "ddate"        =>$_POST['date'],
         "approve_no"   =>$_POST['approve_no'],
         "ref_no"       =>$_POST['ref_no'],
         "supplier"     =>$_POST['supplier_id'],
         "comment"      =>$_POST['memo'],
         "total_amount" =>$_POST['total'],
         "ship_to_bc"   =>$_POST['ship_to_bc'],
         "deliver_date" =>$_POST['deliver_date'],
         "approved"     =>"",
         "approved_by"  =>"",
         "approved_date"=>"",
         "type"         =>$_POST['type'],
         "dpo"          =>$dpo,
         "oc"           =>$_POST['oc'],
       );

        for($x = 0; $x<25; $x++){
          if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['3_'.$x])){
            if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['3_'.$x] != "" ){
              $t_po_det[]= array(
                "cl"         =>$_POST['cl'],
                "bc"         =>$_POST['branch'],
                "nno"        =>$this->max_no,
                "type"       =>$_POST['type'], 
                "item"       =>$_POST['0_'.$x],
                "current_qty"=>$_POST['2_'.$x],
                "qty"        =>$_POST['3_'.$x],
                "cost"       =>$_POST['4_'.$x],
                "amount"     =>$_POST['5_'.$x],
                "sub_cl"     =>$_POST['cl_'.$x],
                "sub_bc"     =>$_POST['bc_'.$x],
                "sub_nno"    =>$_POST['nno_'.$x]
              ); 
            }
          }
        }


        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('t_po_sum')){

           $this->db->insert($this->mtb,  $t_po_sum);
           if(count($t_po_det)){$this->db->insert_batch("t_po_det",$t_po_det);}  

           for($x = 0; $x<25; $x++){
             if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['3_'.$x])){
               if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['3_'.$x] != "" ){
                 $this->load->model('utility');
                 $this->utility->save_po_trans2($this->tb_po_trans, $_POST['0_'.$x], $this->trans_code, $this->max_no, $this->sub_trans_code, $this->max_no, $_POST['3_'.$x], $this->qty_out,$_POST['type']);

                 $this->db->where('cl',$this->sd['cl']);
                 $this->db->where('bc',$this->sd['branch']);
                 $this->db->where('item',$_POST['0_'.$x]);
                 $this->db->update('t_req_det', array("orderd"=>1, "orderd_no"=>$this->max_no)); 
               }
             }
           }

           $this->update_is_ordered_field();
           $this->utility->save_logger("SAVE",33,$this->max_no,$this->mod);
           echo $this->db->trans_commit()."@".$this->max_no;
         }else{
          echo $this->db->trans_commit();
          echo "No permission to save records";
        }  
      }else{

        if($this->user_permissions->is_edit('t_po_sum')){

          $this->db->where("cl", $this->sd['cl']);
          $this->db->where("bc", $this->sd['branch']);
          $this->db->where('nno',$_POST['hid']);
          $this->db->where('type',$_POST['type']);
          $this->db->update($this->mtb, $t_po_sum);

          $this->set_delete();

          if(count($t_po_det)){$this->db->insert_batch("t_po_det",$t_po_det);}

          $this->utility->delete_po_trans2($this->tb_po_trans, $this->trans_code, $this->max_no,$_POST['type']);

          for($x = 0; $x<25; $x++){
           if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['3_'.$x])){
            if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['3_'.$x] != "" ){
              $this->load->model('utility');
              $this->utility->save_po_trans2($this->tb_po_trans, $_POST['0_'.$x], $this->trans_code, $this->max_no, $this->sub_trans_code, $this->max_no, $_POST['3_'.$x], $this->qty_out,$_POST['type']);

              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->where('orderd_no',$_POST['id']);
              $this->db->update('t_req_det', array("orderd"=>0)); 

              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->where('item',$_POST['0_'.$x]);
              $this->db->update('t_req_det', array("orderd"=>1, "orderd_no"=>$this->max_no)); 
            }
          }
        }
        $this->update_is_ordered_field();
        $this->utility->save_logger("EDIT",33,$this->max_no,$this->mod); 
        echo $this->db->trans_commit();          
      }else{
        $this->db->trans_commit();
        echo "No permission to edit records";
      }  
    }
  }else{
    echo $validation_status;
    $this->db->trans_commit();
  }
}catch(Exception $e){ 
  $this->db->trans_rollback();
  echo $e->getMessage()."Operation fail please contact admin".$e; 
}  
}


public function PDF_report(){

  $r_detail['deliver_date'];
  $r_detail['ship_to_bc'];
  $r_detail['supplier'];
  $r_detail['ddate'];
  $r_detail['total_amount'];
  
  $r_detail['cost_print'] = $_POST['cost_prnt'];
  $r_detail['duplicate'] = $_POST['is_duplicate'];

  $this->db->where("nno",$_POST['qno']);
  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $query= $this->db->get('t_po_sum'); 

  if ($query->num_rows() > 0){
    foreach ($query->result() as $row){
      $r_detail['deliver_date']=$row->deliver_date;
      $r_detail['ship_to_bc']=$row->ship_to_bc;
      $r_detail['supplier']=$row->supplier;
      $r_detail['ddate']=$row->ddate;
      $r_detail['total_amount']=$row->total_amount;
      $r_detail['memo']=$row->comment;
    }
  } 

  $invoice_number= $this->utility->invoice_format($_POST['qno']);
  $session_array = array(
   $this->sd['cl'],
   $this->sd['branch'],
   $invoice_number
 );
  $r_detail['session'] = $session_array;

  
  $this->db->select(array('name','address','tp','fax','email'));
  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $r_detail['branch']=$this->db->get('m_branch')->result();

  $this->db->select(array('name','address','tp','fax','email'));
  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$r_detail['ship_to_bc']);
  $r_detail['ship_branch']=$this->db->get('m_branch')->result();
  
  
  $r_detail['qno']=$_POST['qno'];
  $r_detail['page']=$_POST['page'];
  $r_detail['header']=$_POST['header'];
  $r_detail['orientation']=$_POST['orientation'];
  $r_detail['type']=$_POST['type'];


  $sql="SELECT v.code,
  v.name,
  v.`address1`,
  IFNULL(v.`address2`, '') address2,
  IFNULL(v.`address3`, '') address3,
  IFNULL(v.`email`, '') email,
  IFNULL(vc.`tp`, '') tp FROM t_po_sum p
  JOIN m_supplier v
  ON v.code = p.supplier 
  LEFT JOIN `m_supplier_contact` vc 
  ON vc.`code` = v.`code` 
  WHERE p.nno='".$_POST['qno']."' and cl='".$this->sd['cl']."' and bc='".$this->sd['branch']."' LIMIT 1";


  $r_detail['suppliers']=$this->db->query($sql)->result();
  

  $sql="SELECT 
  `m_item`.`code`,
  `m_item`.`description`,
  `m_item`.`model`,
  `m_item`.`purchase_price`,
  `t_po_det`.`item`,
  `t_po_det`.`current_qty`,
  `t_po_det`.`qty`,
  `t_po_det`.`cost`,
  `t_po_det`.`amount`
  FROM `t_po_det` 
  JOIN m_item ON m_item.`code`=t_po_det.`item`
  WHERE `t_po_det`.`nno`=".$_POST['qno']." AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";

  $query=$this->db->query($sql);
  if($query->num_rows>0){
    $r_detail['det']=$query->result();
  }else{
    $r_detail['det']=2;
  }

  $this->load->view($_POST['by'].'_'.'pdf',$r_detail);

}


private function set_delete(){
  $this->db->where("nno", $_POST['id']);
  $this->db->where("cl", $_POST['cl']);
  $this->db->where("bc", $_POST['branch']);
  $this->db->delete("t_po_det");
}

public function check_code(){
 $this->db->where('code', $_POST['code']);
 $this->db->limit(1);
 
 echo $this->db->get($this->mtb)->num_rows;
}


public function load(){
  $x=0;

  $this->db->select(array(
    't_po_sum.supplier as supp_id' ,
    'm_supplier.name'
  ));

  $this->db->from('t_po_sum');
  $this->db->join('m_supplier','t_po_sum.supplier=m_supplier.code');
  $this->db->where('t_po_sum.cl',$this->sd['cl'] );
  $this->db->where('t_po_sum.bc',$this->sd['branch'] );
  $this->db->where('t_po_sum.nno',$_POST['id']);
  $query=$this->db->get();

  

  if($query->num_rows()>0){
    $a['supplier']=$query->result();
  }else{
    $x=2;
  }

  $this->db->select(array(
    't_po_sum.ddate' ,
    't_po_sum.ref_no' ,
    't_po_sum.comment',
    't_po_sum.total_amount',
    't_po_sum.deliver_date',
    't_po_sum.ship_to_bc',
    't_po_sum.approve_no',
    't_po_sum.is_cancel',
    't_po_sum.type', 
    't_po_sum.dpo',

  ));

  $this->db->where('t_po_sum.cl',$this->sd['cl'] );
  $this->db->where('t_po_sum.bc',$this->sd['branch'] );
  $this->db->where('t_po_sum.nno',$_POST['id']);
  $this->db->where('t_po_sum.type',$_POST['type']);
  $query=$this->db->get('t_po_sum');

  

  if($query->num_rows()>0){
    $a['sum']=$query->result();
  }else{
    $x=2;
  } 


  $this->db->select(array(
    't_po_det.item' ,
    't_po_det.qty',
    't_po_det.current_qty',
    't_po_det.cost',
    't_po_det.amount',
    'm_item.description',
    'm_item.model'
  ));

  $this->db->from('t_po_det');
  $this->db->join('m_item','m_item.code=t_po_det.item');
  $this->db->join('t_po_sum','t_po_sum.nno=t_po_det.nno AND `t_po_sum`.`cl` = `t_po_det`.`cl` AND `t_po_sum`.`bc` = `t_po_det`.`bc`');
  $this->db->where('t_po_det.cl',$this->sd['cl'] );
  $this->db->where('t_po_det.bc',$this->sd['branch'] );
  $this->db->where('t_po_det.nno',$_POST['id']);
  $this->db->where('t_po_sum.type',$_POST['type']);
  $this->db->where('t_po_det.type',$_POST['type']);
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

  $this->db->trans_begin();

  error_reporting(E_ALL); 
  function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
    throw new Exception($errMsg); 
  }
  set_error_handler('exceptionThrower'); 
  try {
   if($this->user_permissions->is_delete('t_po_sum')){

    if($_POST['type']==1){
      $this->trans_code="23";
      $this->sub_trans_code="23";
    }else if ($_POST['type']==2){
      $this->trans_code="46";
      $this->sub_trans_code="46";
    }

    $status=$this->validation->purchase_order_status($_POST['id']);
    if($status!=1){
      $this->utility->delete_po_trans2($this->tb_po_trans, $this->trans_code, $this->max_no,$_POST['type']);
      $this->utility->cancel_transs('t_po_sum', $_POST['id'], $_POST['type']);
      
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->where('orderd_no',$_POST['id']);
      $this->db->update('t_req_det', array("orderd"=>0));    

      $id=$_POST['id'];
      $cl=$this->sd['cl'];
      $bc=$this->sd['branch'];

      $sql="UPDATE t_req_approve_sum s, 
      (SELECT cl,bc,nno,approve_no FROM t_po_sum 
      WHERE nno='$id'  AND cl='$cl' AND bc='$bc')t
      SET s.`is_ordered`='0'
      WHERE s.cl=t.cl AND s.bc=t.bc AND s.nno=t.approve_no AND s.type='".$_POST['type']."'  ";  

      $this->db->query($sql); 
      echo $this->db->trans_commit();  
    }else{
      echo "This Purchase Order cannot update";
      $this->db->trans_commit();
    }
  }else{
    echo "No permission to cancel records";
    $this->db->trans_commit();
  } 
}catch(Exception $e){ 
  $this->db->trans_rollback();
  echo $e->getMessage()."Operation fail please contact admin".$e; 
}   
}

public function select(){
  $query = $this->db->get($this->mtb);
  
  $s = "<select name='sales_ref' id='sales_ref'>";
  $s .= "<option value='0'>---</option>";
  foreach($query->result() as $r){
    $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code." | ".$r->name."</option>";
  }
  $s .= "</select>";
  
  return $s;
}

public function item_list_all(){

  if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}               
    $sql = "SELECT * FROM m_item  WHERE description LIKE '$_POST[search]%' OR code LIKE '$_POST[search]%' AND inactive='1' LIMIT 25";
    $query = $this->db->query($sql);
    $a = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>Code</th>";
    $a .= "<th class='tb_head_th'>Item Name</th>";
    $a .= "<th class='tb_head_th'>Price</th>";
    $a .= "</thead></tr>";

    $a .= "<tr class='cl'>";
    $a .= "<td>&nbsp;</td>";
    $a .= "<td>&nbsp;</td>";
    $a .= "<td>&nbsp;</td>";
    $a .= "</tr>";

    foreach($query->result() as $r){
      $a .= "<tr class='cl'>";
      $a .= "<td>".$r->code."</td>";
      $a .= "<td>".$r->description."</td>";
      $a .= "<td>".$r->purchase_price."</td>";
      $a .= "</tr>";
    }
    $a .= "</table>";
    echo $a;
  }

  public function get_item(){
    $this->db->select(array('code','description','purchase_price'));
    $this->db->where("code",$this->input->post('code'));
    $this->db->limit(1);
    $query=$this->db->get('m_item');
    if($query->num_rows() > 0){
      $data['a']=$query->result();
    }else{
      $data['a']=2;
    }

    echo json_encode($data);
  }


  function load_request_note(){
    $nno=$this->input->post("nno");
    $sql="SELECT 
    m_item.`description`,
    m_item.`model`,
    m_item.`purchase_price`,
    d.cl,
    s.supplier,
    m_supplier.name,
    d.bc,
    d.`code` AS item,
    d.`nno`,
    d.current_qty AS cur_qty,
    d.`approve_qty`,
    d.`approve_qty` * m_item.`purchase_price` AS total 
    FROM
    t_req_approve_det d 
    JOIN m_item ON m_item.`code` = d.`code` 
    JOIN t_req_approve_sum s ON s.cl=d.cl AND s.bc=d.bc AND s.nno=d.nno 
    JOIN m_supplier ON m_supplier.code=s.supplier  
    WHERE s.`is_level_3_approved` = '1' 
    AND s.is_cancel='0'
    AND s.is_ordered = '0'
    AND s.nno='$nno'

    ";

    if($_POST['type']==2){
      $sql .= "AND s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."' order by d.auto_num ASC";
    }else{
      $sql .="order by d.auto_num ASC";
    }

    $query=$this->db->query($sql);

    if($query->num_rows>0){
      $a['det']=$query->result();
      echo json_encode($a);
    }else{
      $a['det']=2;
      echo json_encode($a);
    }
  }


  public function update_is_ordered_field(){
    $sql="UPDATE t_req_approve_sum 
    SET `is_ordered`='1' 
    WHERE nno='$_POST[approve_no]' AND cl='".$this->sd['cl']."' and bc='".$this->sd['branch']."'";
    $this->db->query($sql);
  }    

  public function load_approve_no(){
    if($_POST['search'] == 'Key Word: code, name'){
      $_POST['search'] = "";
    }
    $sql = "SELECT * FROM $table  WHERE $field2 LIKE '%$_POST[search]%' OR $field LIKE '%$_POST[search]%' LIMIT 25";

    $query = $this->db->query($sql);
    $a  = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th'>".$preview_name1."</th>";
    $a .= "<th class='tb_head_th' colspan='2'>".$preview_name2."</th>";
    $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

    foreach($query->result() as $r){
      $a .= "<tr class='cl'>";
      $a .= "<td>".$r->{$field}."</td>";
      $a .= "<td>".$r->{$field2}."</td>";

      if($hid_field!=0){
        $a .= "<td><input type='hidden' class='code_gen' value='".$r->{$hid_field}."' title='".$r->{$hid_field}."' /></td>";        
      }
      $a .= "</tr>";
    }

    $a .= "</table>";
    echo $a;
  }  


  public function get_max_no_type(){

    $table_name=$_POST['table'];
    $field_name=$_POST['nno'];
    $type=$_POST['type'];

    if(isset($_POST['hid'])){
      if($_POST['hid'] == "0" || $_POST['hid'] == ""){      
        $this->db->select_max($field_name);
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $this->db->where("type",$type);    
        echo $this->db->get($table_name)->first_row()->$field_name+1;
      }else{
        echo $_POST['hid'];  
      }
    }else{
      $this->db->select_max($field_name);
      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']); 
      $this->db->where("type",$type);    
      echo $this->db->get($table_name)->first_row()->$field_name+1;
    }
  }

  public function get_max_no_type1($table_name,$field_name,$type=1){

    if(isset($_POST['hid'])){
      if($_POST['hid'] == "0" || $_POST['hid'] == ""){      
        $this->db->select_max($field_name);
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $this->db->where("type",$type);    
        return $this->db->get($table_name)->first_row()->$field_name+1;
      }else{
        return $_POST['hid'];  
      }
    }else{
      $this->db->select_max($field_name);
      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']); 
      $this->db->where("type",$type);    
      return $this->db->get($table_name)->first_row()->$field_name+1;
    }
  }



  public function f1_selection_list_po(){

    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}  
      if($_POST['supplier']==""){
        $sql="SELECT 
        `nno`,`ddate`,`supplier`,`name`
        FROM t_req_approve_sum 
        JOIN m_supplier ON m_supplier.`code` = t_req_approve_sum.`supplier`
        WHERE (ddate LIKE '%".$_POST['search']."%' OR nno LIKE '%".$_POST['search']."%') 
        AND TYPE = '".$_POST['type']."' 
        AND is_level_3_approved = '1' 
        AND is_ordered = '0' 
        AND is_cancel = '0' 
        AND is_expire='0'
        AND cl = '".$this->sd['cl']."' 
        AND bc = '".$this->sd['branch']."'
        LIMIT 25";
      }else{
        $sql="SELECT 
        `nno`,`ddate`,`supplier`,`name`
        FROM t_req_approve_sum 
        JOIN m_supplier ON m_supplier.`code` = t_req_approve_sum.`supplier`
        WHERE (ddate LIKE '%".$_POST['search']."%' OR nno LIKE '%".$_POST['search']."%') 
        AND TYPE = '".$_POST['type']."' 
        AND is_level_3_approved = '1' 
        AND is_ordered = '0' 
        AND is_cancel = '0' 
        AND is_expire='0'
        AND supplier ='".$_POST['supplier']."'
        AND cl = '".$this->sd['cl']."' 
        AND bc = '".$this->sd['branch']."'
        LIMIT 25";
      }



      $query = $this->db->query($sql);

      $a  = "<table id='item_list' style='width : 100%' >";
      $a .= "<thead><tr>";
      $a .= "<th class='tb_head_th'>No</th>";
      $a .= "<th class='tb_head_th'>Date</th>";
      $a .= "<th class='tb_head_th' colspan='2'>Supplier</th>";
      $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

      foreach($query->result() as $r){

        $a .= "<tr class='cl'><td>".$r->nno."</td>";
        $a .= "<td>".$r->ddate."</td>";
        $a .= "<td colspan='2'>".$r->supplier."-".$r->name."</td>";
        $a .= "</tr>";
      }

      $a .= "</table>";
      echo $a;
    }


    public function get_item_without_approve(){
      $sql = "SELECT  i.code AS item,
      i.description,
      i.model,
      i.purchase_price,
      i.max_price,
      i.min_price,
      SUM(q.`qty`) AS cur_qty
      FROM `m_item` i
      JOIN `qry_current_stock` q ON q.`item`=i.`code`
      WHERE (code LIKE '$_POST[search]%' OR i.description LIKE '$_POST[search]%' OR i.model LIKE '$_POST[search]%')
      GROUP BY i.`code`
      LIMIT 25";

      $query = $this->db->query($sql);
      $a = "<table id='item_list' style='width : 100%' >";
      $a .= "<thead><tr>";
      $a .= "<th class='tb_head_th'>Code</th>";
      $a .= "<th class='tb_head_th'>Item Name</th>";
      $a .= "<th class='tb_head_th'>Model</th>";
      $a .= "<th class='tb_head_th'>Price</th>";
      $a .= "<th class='tb_head_th'>Max Price</th>";
      $a .= "<th class='tb_head_th'>Min Price</th>";
      $a .= "<th class='tb_head_th'>Qty</th>";

      $a .= "</thead></tr>";
      $a .= "<tr class='cl'>";
      $a .= "<td>&nbsp;</td>";
      $a .= "<td>&nbsp;</td>";
      $a .= "<td>&nbsp;</td>";
      $a .= "<td>&nbsp;</td>";
      $a .= "<td>&nbsp;</td>";
      $a .= "<td>&nbsp;</td>";
      $a .= "</tr>";

      foreach($query->result() as $r){
        $a .= "<tr class='cl'>";
        $a .= "<td>".$r->item."</td>";
        $a .= "<td>".$r->description."</td>";
        $a .= "<td>".$r->model."</td>";
        $a .= "<td>".$r->purchase_price."</td>";
        $a .= "<td>".$r->max_price."</td>";
        $a .= "<td>".$r->min_price."</td>";
        $a .= "<td>".$r->cur_qty."</td>";
        $a .= "<td style='display:none;'>".$this->sd['cl']."</td>";
        $a .= "<td style='display:none;'>".$this->sd['branch']."</td>";
        $a .= "</tr>";
      }

      $a .= "</table>";
      echo $a;
    }

  }