<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class t_bankrec_error_made_bank extends CI_Model {
    
  private $sd;
  private $tb_sum;
  private $mtb;
  private $mod = '003';
  private $tb_det;
  private $max_no;
    
  function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
  $this->load->database($this->sd['db'], true);
  $this->load->model('user_permissions');
    }
    
  public function base_details(){
  	$a['id'] = '0'; 
  	return $a;
  }

  public function validation() {
    $status = 1;
   
    $account_update=$this->account_update(0);
    if($account_update!=1){
        return "Invalid account entries";
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
      $validation_status = $this->validation();
      if ($validation_status == 1){
          for($x = 0; $x<25; $x++){    
            if(isset($_POST['6_'.$x])){  
              if($_POST['6_'.$x]!=""){  
                $t_bank_rec_add_det[]= array(
                    "cl"          =>$this->sd['cl'],
                    "bc"          =>$this->sd['branch'],
                    "type"        =>2,
                    "nno"         =>$_POST['id'],
                    "rec_ddate"   =>$_POST['date'],
                    "date"        =>$_POST['1_'.$x],
                    "description" =>$_POST['2_'.$x],
                    "remarks"     =>$_POST['3_'.$x],
                    "dr_amount"   =>$_POST['4_'.$x],
                    "cr_amount"   =>$_POST['5_'.$x],
                    "account"     =>$_POST['6_'.$x],
                    "oc"          =>$this->sd['oc'],
                );
              }        
            }
          }

        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('t_bankrec_error_made_bank')){ 
            if(count($t_bank_rec_add_det)){
              $this->db->insert_batch("t_bank_rec_additional_det",$t_bank_rec_add_det);
            }  
            $this->account_update(1);
            $this->utility->save_logger("SAVE",86,$_POST['id'],$this->mod);
            echo $this->db->trans_commit();
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          } 
        }else{
          if($this->user_permissions->is_edit('t_bankrec_error_made_bank')){ 
            $this->db->where('nno',$_POST['hid']);
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->where('type',2);
            $this->db->delete("t_bank_rec_additional_det");
            if(count($t_bank_rec_add_det)){
              $this->db->insert_batch("t_bank_rec_additional_det",$t_bank_rec_add_det);
            }  
            $this->account_update(1);
            $this->utility->save_logger("EDIT",86,$_POST['id'],$this->mod); 
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

 public function account_update($condition)
  {
    $this->db->where("trans_no", $_POST['id']);
    $this->db->where("trans_code", 86);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_check_double_entry");
    
    if ($condition == 1) {
      if ($_POST['hid'] != "0" || $_POST['hid'] != "") {
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('trans_code', 86);
        $this->db->where('trans_no', $_POST['id']);
        $this->db->delete('t_account_trans');
      }
    }
    $config = array(
      "ddate" => $_POST['date'],
      "trans_code" => 86,
      "trans_no" => $_POST['id'],
      "op_acc" => 0,
      "reconcile" => 0,
      "cheque_no" => 0,
      "narration" => "",
      "ref_no" => ""
    );
    
    $this->load->model('account');
    $this->account->set_data($config);
    $bank_acc=$_POST['rec_bank'];

    for ($i = 0; $i < 25; $i++) {
      if ($_POST['2_' . $i] != "" && $_POST['6_' . $i] != "0") {
        $dess = "ERRORS MADE BANK - " . $_POST['2_' . $i];

        //-----dr>0----
        if($_POST['4_'.$i]>0){
          $this->account->set_value2($dess, $_POST['4_'.$i], "dr", $bank_acc,$condition);
          $this->account->set_value2($dess, $_POST['4_'.$i], "cr", $_POST['6_'.$i],$condition);
        }

        //-----cr>0----
        if($_POST['5_'.$i]>0){
          $this->account->set_value2($dess, $_POST['5_'.$i], "cr", $bank_acc,$condition);
          $this->account->set_value2($dess, $_POST['5_'.$i], "dr", $_POST['6_'.$i],$condition);
        }

      }
    }
    
    if ($condition == 0) {
      $query = $this->db->query("
           SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
           FROM `t_check_double_entry` t
           LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
           WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='86'  AND t.`trans_no` ='" .$_POST['id']. "' AND 
           a.`is_control_acc`='0'");
      
      if($query->row()->ok == "0"){
        $this->db->where("trans_no", $_POST['id']);
        $this->db->where("trans_code", 86);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_check_double_entry");
        return "0";
      } else {
        return "1";
      }
    }
  }  
    
public function load(){
  $sql="SELECT d.* , a.description as account_des
        FROM t_bank_rec_additional_det d
        LEFT JOIN m_account a ON a.code = d.account
        WHERE d.`nno`='".$_POST['code']."' 
        AND d.`cl`='".$this->sd['cl']."' 
        AND d.type='2'
        AND d.`bc`='".$this->sd['branch']."' 
        ORDER BY d.auto_no";
  $query=$this->db->query($sql);
  if($query->num_rows()>0){
    $a['det']=$query->result();
  }else{
    $a=2;
  }
    echo json_encode($a);
}
    
public function select_description(){
  if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
   
    $sql = "SELECT description FROM `r_additional_item`
            WHERE type='3'
            AND (description LIKE '%$_POST[search]%') LIMIT 25";
   
    $query = $this->db->query($sql);
    $a  = "<table id='item_list' style='width : 100%' >";
    $a .= "<thead><tr>";
    $a .= "<th class='tb_head_th' colspan='2'>Description</th>";
    $a .= "</thead></tr><tr class='cl'><td colspan='2'>&nbsp;</td></tr>";

  foreach($query->result() as $r){
    $a .= "<tr class='cl'>";
    $a .= "<td>".$r->description."</td>";
    $a .= "</tr>";
  }
    $a .= "</table>";
    echo $a;
}

public function PDF_report(){
  
   $this->db->select(array(
      'name',
      'address',
      'tp',
      'fax',
      'email'
    ));
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $r_detail['branch'] = $this->db->get('m_branch')->result();
    
    $invoice_number      = $this->utility->invoice_format($_POST['qno']);
    $session_array       = array(
      $this->sd['cl'],
      $this->sd['branch'],
      $invoice_number
    );

    $this->db->select(array(
      'loginName'
    ));
    $this->db->where('cCode', $this->sd['oc']);
    $r_detail['user'] = $this->db->get('users')->result();

    $cl = $this->sd['cl'];
    $bc = $this->sd['branch'];
    $id = $_POST['qno'];

    $r_detail['session'] = $session_array;
    $r_detail['page']        = $_POST['page'];
    $r_detail['header']      = $_POST['header'];
    $r_detail['orientation'] = $_POST['orientation'];      
     

    $sql="SELECT s.nno,s.ddate,s.account_id,a.description,s.date_from,s.date_to FROM `t_bank_reconcil_sum` s
JOIN  m_account a ON a.code=s.account_id where nno='$id' ORDER BY auto_no";

    $sum = $this->db->query($sql);            
    $r_detail['sum'] = $sum->result(); 


    $sql="SELECT * from t_bank_reconcil_det where nno='$id'";

    $sum = $this->db->query($sql);            
    $r_detail['det'] = $sum->result(); 
    
      // $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    if($sum->num_rows()>0){            
        $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }else{
        echo "<script>alert('No data found');close();</script>";
    }
                
  }

  public function chk_is_update(){
    $sql="SELECT nno 
          FROM `t_bank_rec_additional_det`
          WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['code']."' AND type='2'";
    $query=$this->db->query($sql);

    if($query->num_rows()>0){
      $a=1;
    }else{
      $a=2;
    }
    echo ($a);
  }
}


  
