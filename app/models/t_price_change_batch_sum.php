<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_price_change_batch_sum extends CI_Model {

  private $sd;
  private $mtb;
  private $max_no;
  private $mod = '003';

  function __construct(){
    parent::__construct();
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');
      //$this->max_no = $this->get_max_no("t_price_change_batch_sum", "nno");
  }

  public function base_details(){
   $this->load->model('m_customer');
   $a['max_no'] =$this->get_max_no("t_price_change_batch_sum", "nno"); 

   return $a;
 }


 public function validation(){
  $this->max_no=$this->get_max_no("t_price_change_batch_sum","nno"); 
  $status=1;
  $new_min_price=$this->utility->check_min_price('0_','4_','purchase_price','p');
  if($new_min_price!=1){
    return $new_min_price;
  }
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

    $validation_status=$this->validation();
    if($validation_status==1){
      $_POST['cl']=$this->sd['cl'];
      $_POST['bc']=$this->sd['branch'];
      $_POST['oc']=$this->sd['oc'];

      $a=array(
        "cl" => $this->sd['cl'],
        "bc" => $this->sd['branch'],
        "nno" =>$this->max_no,
        "ddate" =>$_POST['date'],
        "ref_no"=>$_POST['ref_no'],
        "reason"=>$_POST['reason'],
        "oc"  =>$_POST['oc'],
        );

      for($x = 0; $x<25; $x++){
        if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['3_'.$x])){
          if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['3_'.$x] != "" ){
            $b[]= array(
              "cl" => $this->sd['cl'],
              "bc" => $this->sd['branch'],
              "nno" =>$this->max_no,
              "item"  =>$_POST['0_'.$x],
              "cost"  =>$_POST['2_'.$x],
              "batch"  =>$_POST['bt_'.$x],
              "last_price"=>$_POST['3_'.$x],
              "max_price"=>$_POST['5_'.$x],
              "last_price_new"=>$_POST['4_'.$x],
              "max_price_new"=>$_POST['t_'.$x],
              );     

            $c[]= array(
              "code"  =>$_POST['0_'.$x],
              "min_price"=>$_POST['4_'.$x],
              "max_price"=>$_POST['t_'.$x],
              "batch_no"=>$_POST['bt_'.$x],
              );                    
          }
        }
      }


      if($_POST['hid'] == "0" || $_POST['hid'] == ""){
        if($this->user_permissions->is_add('t_price_change_batch_sum')){

          $this->db->insert('t_price_change_batch_sum', $a);
          if(count($b)){$this->db->insert_batch("t_price_change_batch_det",$b);}

          for($y=0;$y<sizeof($c);$y++){
           $item=array(
            "min_price"=>$c[$y]['min_price'],
            "max_price"=>$c[$y]['max_price'],	
            );

           $this->db->where('batch_no',$c[$y]['batch_no']);
           $this->db->where('item',$c[$y]['code']);
           $this->db->update('t_item_batch',$item);
         }

         $this->utility->save_logger('SAVE','29',$this->max_no,$this->mod);
         echo $this->db->trans_commit();
         $this->emails();
       }else{
         echo "No permission to save records";
         $this->db->trans_commit();
       }

     }else{
      if($this->user_permissions->is_edit('t_price_change_batch_sum')){

        $this->db->where('nno',$this->max_no);
        $this->db->update('t_price_change_batch_sum', $a);

        $this->set_delete();

        if(count($b)){$this->db->insert_batch("t_price_change_batch_det",$b);}

        for($y=0;$y<sizeof($c);$y++){
          $item=array(
            "min_price"=>$c[$y]['min_price'],
            "max_price"=>$c[$y]['max_price'],	
            );

          $this->db->where('batch_no',$c[$y]['batch_no']);
          $this->db->where('item',$c[$y]['code']);
          $this->db->update('t_item_batch',$item);
        }

        $this->utility->save_logger('EDIT','29',$this->max_no,$this->mod);
        echo $this->db->trans_commit();
        $this->emails();
      }else{
        echo "No permission to edit records";
        $this->db->trans_commit();
      }    		
    }
  }else{
    echo $validation_status;
  } 

} catch ( Exception $e ) { 
  $this->db->trans_rollback();
  echo $e->getMessage()."Operation fail please contact admin"; 
} 
}


public function emails(){
  $this->load->library('email');
  $this->db->select(array('email'));
  $r_details=$this->db->get('m_branch')->result();
  $em_sub="Price Changes On  ". date('Y-m-d');
  $em_body="<table>";
  $em_body.="<tr>";
  $em_body.="<th>Code</th>";
  $em_body.="<th>New Last Price</th>";
  $em_body.="<th>New Max Price</th>";
  $em_body.="</tr>";

  for($y=0;$y<25;$y++){
    $em_body.="<tr>";
    $em_body.="<td>".$_POST['0_'.$y]."</td>";
    $em_body.="<td>".$_POST['4_'.$y]."</td>";
    $em_body.="<td>".$_POST['t_'.$y]."</td>";
    $em_body.="</tr>";
  }

  $em_body.="</table>";


  $config['protocol'] = 'sendmail';
  $config['mailpath'] = '/usr/sbin/sendmail';
  $config['charset'] = 'iso-8859-1';
  $config['wordwrap'] = TRUE;
  $config['mailtype'] = 'html';
  $this->email->initialize($config);
  $this->email->from('system@sunmatch.lk', 'Price Change');
  $this->email->subject($em_sub);
  $this->email->message($em_body);

  foreach($r_details as $row){
   $row->email;
   $this->email->to($row->email);
   $this->email->send();
 }
}

public function get_item_detail(){
  $code=$_POST['code'];
  $from=$_POST['date_from'];
  $to=$_POST['date_to'];

  $sql="SELECT m_item.model,
  t_price_change_batch_det.`batch`, 
  t_price_change_batch_det.*,
  t_price_change_batch_sum.ddate,
  t_price_change_batch_sum.action_date,
  su.discription AS oc_name 
  FROM t_price_change_batch_det 
  JOIN m_item ON m_item.`code` = t_price_change_batch_det.item 
  JOIN t_price_change_batch_sum ON t_price_change_batch_sum.nno = t_price_change_batch_det.nno 
  JOIN s_users su ON su.cCode = t_price_change_batch_sum.oc
  JOIN t_item_batch ON t_item_batch.item = t_price_change_batch_det.item AND t_item_batch.`batch_no` = t_price_change_batch_det.batch
  WHERE t_price_change_batch_det.item='$code' 
  AND  t_price_change_batch_sum.ddate BETWEEN '$from' 
  AND '$to' 
  GROUP BY batch,nno 
  order by nno asc 
  LIMIT 25 ";
  if($this->db->query($sql)->num_rows()>0){
   $a['data']=$this->db->query($sql)->result();
 }else{
   $a['data']="0";
 }
 echo json_encode($a);
}

private function set_delete(){
  $this->db->where('nno', $_POST['id']);
  $this->db->delete("t_price_change_batch_det");
}

public function check_code(){
 $this->db->where('nno', $_POST['id']);
 $this->db->limit(1);
 echo $this->db->get($this->mtb)->num_rows;
}

public function load(){

  $this->db->select(array(
    't_price_change_batch_sum.nno' ,
    't_price_change_batch_sum.ddate',
    't_price_change_batch_sum.ref_no',
    't_price_change_batch_sum.reason',
    'm_item.code',
    'm_item.description' ,
    'm_item.model',
    'm_item.purchase_price',
    't_price_change_batch_det.batch',
    't_price_change_batch_det.last_price',
    't_price_change_batch_det.last_price_new',
    't_price_change_batch_det.max_price',
    't_price_change_batch_det.max_price_new',
    ));

  $this->db->from('t_price_change_batch_sum');
  $this->db->join('t_price_change_batch_det','t_price_change_batch_sum.nno=t_price_change_batch_det.nno');
  $this->db->join('m_item','m_item.code=t_price_change_batch_det.item');
  $this->db->where('t_price_change_batch_sum.nno',$_POST['id']);
  $this->db->order_by('t_price_change_batch_det.auto_no', 'asc');
  $query=$this->db->get();

  if($query->num_rows()>0){
    $a['det']=$query->result();
    echo json_encode($a);
  }else{
    echo json_encode("2");
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
    if($this->user_permissions->is_delete('t_price_change_batch_sum')){

     $this->db->where('nno', $_POST['id']);
     $this->db->where('cl', $this->sd['cl']);
     $this->db->where('bc',$this->sd['branch']);
     $this->db->limit(1);
     $this->db->delete($this->mtb);

     echo $this->db->trans_commit();
   }else{
    echo "No permission to delete records";
    $this->db->trans_commit();
  }
} catch ( Exception $e ) { 
  $this->db->trans_rollback();
  echo "Operation fail please contact admin"; 
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



public function item_list_all(){
 if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
 $sql = "SELECT m.`code`, 
 m.`description`, 
 m.`model`,
 t.`batch_no`,
 t.`purchase_price`,
 t.`min_price`,
 t.`max_price` 
 FROM t_item_batch t 
 JOIN m_item m ON m.`code` = t.`item` 
 WHERE (description LIKE '%$_POST[search]%' OR code LIKE '%$_POST[search]%') AND inactive='0' ORDER BY batch_no DESC  LIMIT 50";
 $query = $this->db->query($sql);
 $a = "<table id='item_list' style='width : 100%' >";
 $a .= "<thead><tr>";
 $a .= "<th class='tb_head_th'>Code</th>";
 $a .= "<th class='tb_head_th'>Item Name</th>";
 $a .= "<th class='tb_head_th'>Model</th>";
 $a .= "<th class='tb_head_th'>Batch</th>";
 $a .= "<th class='tb_head_th'>Cost</th>";
 $a .= "<th class='tb_head_th'>Last Price</th>";
 $a .= "<th class='tb_head_th'>Price</th>";
 $a .= "</thead></tr>";
 $a .= "<tr class='cl'>";
 $a .= "<td>&nbsp;</td>";
 $a .= "<td>&nbsp;</td>";
 $a .= "<td>&nbsp;</td>";
 $a .= "<td>&nbsp;</td>";
 $a .= "<td>&nbsp;</td>";
 $a .= "<td>&nbsp;</td>";
 $a .= "<td>&nbsp;</td>";
 $a .= "</tr>";
 foreach($query->result() as $r){
  $a .= "<tr class='cl'>";
  $a .= "<td>".$r->code."</td>";
  $a .= "<td>".$r->description."</td>";
  $a .= "<td>".$r->model."</td>";
  $a .= "<td>".$r->batch_no."</td>";
  $a .= "<td>".$r->purchase_price."</td>";
  $a .= "<td>".$r->min_price."</td>";
  $a .= "<td>".$r->max_price."</td>";
  $a .= "</tr>";
}
$a .= "</table>";
echo $a;
}


public function get_item(){
  $sql="SELECT m.`code`, 
  m.`description`, 
  m.`model`,
  t.`batch_no`,
  t.`purchase_price`,
  t.`min_price`,
  t.`max_price` 
  FROM t_item_batch t 
  JOIN m_item m ON m.`code` = t.`item` 
  WHERE code = '".$_POST['code']."' ";


  $query=$this->db->query($sql);

  if($query->num_rows() > 0){
    $data['a']=$query->result();
  }else{
    $data['a']=2;
  }
  echo json_encode($data);
}


public function PDF_report(){

  $this->db->select(array('name','address','tp','fax','email'));
  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $r_detail['branch']=$this->db->get('m_branch')->result();



  $this->db->select(array('nno','ddate','ref_no'));
  $this->db->where("nno",$_POST['qno']);
  $query=$this->db->get('t_price_change_batch_sum');
  $r_detail['ddate']=$query->first_row()->ddate; 
  $r_detail['nno']=$query->first_row()->nno; 
  $r_detail['ref_no']=$query->first_row()->ref_no; 

  $r_detail['type']=$_POST['type'];        
  $r_detail['dt']=$_POST['dt'];
  $r_detail['qno']=$_POST['qno'];

  $r_detail['page']=$_POST['page'];
  $r_detail['header']=$_POST['header'];
  $r_detail['orientation']=$_POST['orientation'];

  $this->db->select(array(
    't_price_change_batch_sum.nno' ,
    't_price_change_batch_sum.ddate',
    't_price_change_batch_sum.ref_no',
    'm_item.code',
    'm_item.description' ,
    'm_item.model',
    'm_item.purchase_price',
    't_price_change_batch_det.last_price',
    't_price_change_batch_det.last_price_new',
    't_price_change_batch_det.max_price',
    't_price_change_batch_det.max_price_new',

    ));

  $this->db->from('t_price_change_batch_sum');
  $this->db->join('t_price_change_batch_det','t_price_change_batch_sum.nno=t_price_change_batch_det.nno');
  $this->db->join('m_item','m_item.code=t_price_change_batch_det.item');
  $this->db->where('t_price_change_batch_sum.nno',$_POST['qno']);
  $r_detail['items']=$this->db->get()->result();

  $this->db->select(array('loginName'));
  $this->db->where('cCode',$this->sd['oc']);
  $r_detail['user']=$this->db->get('users')->result();



  $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
}




}