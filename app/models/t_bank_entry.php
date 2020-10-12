<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class t_bank_entry extends CI_Model {
  
  private $sd;
  private $mtb;
  private $trans_code = 52;
  private $mod = '003';
  private $tb_sum;
  private $tb_det;
  
  function __construct() {
    parent::__construct();
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);    
    $this->mtb = $this->tables->tb['t_bank_entry'];     
    $this->tb_check_double_entry = $this->tables->tb['t_check_double_entry'];
    $this->tb_branch             = $this->tables->tb['s_branches'];
    $this->tb_users              = $this->tables->tb['s_users'];
    $this->tb_trance             = $this->tables->tb['t_account_trans'];
    $this->max_no = $this->utility->get_max_no("t_bank_entry", "nno");
    $this->tb_account = $this->tables->tb['m_account'];
    $this->load->model('user_permissions');
    
  }
  
  public function base_details() {
    $this->load->model('m_option_setup');
    
    $a['grid']   = $this->m_option_setup->get_grid();
    $a['max_no'] = $this->utility->get_max_no("t_bank_entry", "nno");
    $a['sd']     = $this->sd;
    
    return $a;
  }

  public function get_max() {
    $a['max_no'] = $this->max_no;
    echo json_encode($a);
  }

  public function validation()
  {
    $status         = 1;
    /*$account_update = $this->account_update(0);
    if ($account_update != 1) {
      return "Invalid account entries";
    }*/
    return $status;
  }
  
  
  public function save() {
    $this->db->trans_begin();
    error_reporting(E_ALL);
    
    function exceptionThrower($type, $errMsg, $errFile, $errLine) {
      throw new Exception($errMsg);
    }
    set_error_handler('exceptionThrower');
    try {    

      $validation_status = $this->validation();
      if ($validation_status == 1) {         
        $a = array(
          "nno" =>$this->max_no,
          "ddate" => $_POST['date'],
          "ref_no" => $_POST['ref_no'],
          "sub_no" => $_POST['sub_no'],
          "entry_code" => $_POST['entry_code'],
          "description" => $_POST['description'],
          "narration" => $_POST['narration'],
          "batch_code" => $_POST['batch'],
          "type" => $_POST['optConfirm'],
          "amount" => $_POST['amount'],
          "draccId" => $_POST['sdebit_acc'],
          "craccId" => $_POST['scredit_acc'],
          'bc' => $this->sd['branch'],
          'cl' => $this->sd['cl'],
          "oc" => $this->sd['oc'],
          "is_cancel" => 0
        );
        
        if ($_POST['hid'] == "" || $_POST['hid'] == 0) {
          if ($this->user_permissions->is_add('t_bank_entry')){
            $account_update = $this->account_update(0);
            if ($account_update == 1) {
              $this->db->insert($this->mtb, $a);
              $this->account_update(1);
              $this->utility->save_logger("SAVE", 52, $this->max_no, $this->mod);
              echo $this->db->trans_commit();
            }else{
              echo "invalid account entries";
              $this->db->trans_commit();
            }
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          }
        } else {
          if ($this->user_permissions->is_edit('t_bank_entry')){
            $account_update = $this->account_update(0);
            if ($account_update == 1) {
              $this->db->where("nno", $this->max_no);
              $this->db->where('cl', $this->sd['cl']);
              $this->db->where('bc', $this->sd['branch']);
              $this->db->limit(1);
              $this->db->update($this->mtb, $a);
              $a['no'] = $_POST['id'];
              $this->account_update(1);
              $this->utility->save_logger("EDIT", 52, $this->max_no, $this->mod);
              echo $this->db->trans_commit();
            }else{
              echo "invalid account entries";
              $this->db->trans_commit();
            }
          }else{
            echo "No permission to edit records";
            $this->db->trans_commit();
          }
        }
      } else {
        echo $validation_status;
        $this->db->trans_commit();
      }
    }catch (Exception $e) {
      $this->db->trans_rollback();
      echo $e->getMessage()."Operation fail please contact admin";
    }
  }
  
  
  
  public function account_update($condition) {    
    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_code", 52);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_check_double_entry");
    
    if($_POST['hid']=="0"||$_POST['hid']==""){
            
    }else{
      if($condition=="1"){
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('trans_code', 52);
        $this->db->where('trans_no', $this->max_no);
        $this->db->delete('t_account_trans');
      }
    }


    $config = array(
      "ddate" => $_POST['date'],
      "trans_code" => 52,
      "trans_no" => $this->max_no,
      "op_acc" => 0,
      "reconcile" => 0,
      "cheque_no" => 0,
      "narration" => "",
      "ref_no" => $_POST['ref_no']
    );
    
    $des = $_POST['description'];
    $this->load->model('account');
    $this->account->set_data($config);
    
    if ($_POST['sdebit_acc'] != "" && $_POST['sdebit_acc'] != "0") {
      $this->account->set_value2($des, $_POST['amount'], "dr", $_POST['sdebit_acc'], $condition);
      $this->account->set_value2($des, $_POST['amount'], "cr", $_POST['scredit_acc'], $condition);
    }
    
    if ($condition == 0) {
      $query = $this->db->query("
           SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
           FROM `t_check_double_entry` t
           LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
           WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='52'  AND t.`trans_no` ='" . $this->max_no . "' AND 
           a.`is_control_acc`='0'");
      
      if ($query->row()->ok == "0") {
        $this->db->where("trans_no", $this->max_no);
        $this->db->where("trans_code", 52);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_check_double_entry");
        return "0";
      } else {
        return "1";
      }
    }
  }
  
  
  
  public function load() {
    
    
    $this->db->select(array(
      $this->mtb . '.nno',
      $this->mtb . '.dDate',
      $this->mtb . '.`description`',
      $this->mtb . '.`amount`',
      $this->mtb . '.`draccId`',
      $this->mtb . '.`craccId`',
      'cra.description as craccdes',
      'dra.description as draccdes',
      $this->mtb . '.`type`',
      $this->mtb . '.`entry_code`',
      $this->mtb . '.`narration`',
      $this->mtb . '.ref_no',
      $this->mtb . '.batch_code',
      $this->mtb . '.sub_no',
      $this->mtb . '.is_cancel'
    ));
    $this->db->join($this->tb_account . " as cra", "cra.code=" . $this->mtb . ".craccId", "INNER");
    $this->db->join($this->tb_account . " as dra", "dra.code=" . $this->mtb . ".draccId", "INNER");
    $this->db->where($this->mtb . '.nno', $_POST['id']);
    $this->db->where('bc', $this->sd['branch']);
    $this->db->where('cl', $this->sd['cl']);
    $this->db->limit(1);
    $a['sum'] = $this->db->get($this->mtb)->first_row();
    
    
    echo json_encode($a);
  }
  
  public function delete() {
    $this->db->trans_begin();
    error_reporting(E_ALL);
    
    function exceptionThrower($type, $errMsg, $errFile, $errLine)
    {
      throw new Exception($errMsg);
    }
    
    set_error_handler('exceptionThrower');
    try {
      if ($this->user_permissions->is_delete('t_bank_entry')) {    
        $this->db->where("trans_no", $_POST['id']);
        $this->db->where("trans_code", $this->trans_code);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_account_trans");
        
        $this->db->where("nno", $_POST['id']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->where("cl", $this->sd['cl']);      
        $this->db->update($this->mtb, array("is_cancel" => 1 ));
        $this->utility->save_logger("CANCEL", 52, $_POST['id'], $this->mod);
        echo $this->db->trans_commit();
      } else {
        echo "No permission to delete records";
        $this->db->trans_commit();
      }
    }catch (Exception $e) {
      $this->db->trans_rollback();
      echo $e->getMessage() . "Operation fail please contact admin";
    }
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

        $invoice_number= $this->utility->invoice_format($_POST['qno']);
        $session_array = array(
             $this->sd['cl'],
             $this->sd['branch'],
             $invoice_number
        );
        $r_detail['session'] = $session_array;

        $r_detail['page']=$_POST['page'];
        $r_detail['header']=$_POST['header'];
        $r_detail['orientation']="P";
        $r_detail['dfrom']=$_POST['from'];
        $r_detail['dto']=$_POST['to'];
        $r_detail['trans_code']=$_POST['t_type'];
        $r_detail['trans_code_des']=$_POST['t_type_des'];
        $r_detail['trans_no_from']=$_POST['t_range_from'];
        $r_detail['trans_no_to']=$_POST['t_range_to'];
        $cluster=$_POST['cluster'];
        $branch=$_POST['branch'];

        $sql="SELECT  BE.`nno`,
                      BE.`dDate`,
                      BE.`description`,
                      BE.`amount`,
                      BE.`draccId`,
                      BE.`craccId`,
                      BE.`type`,
                      BE.`ref_no`,
                      BE.`narration`,
                      BE.`batch_code`,
                      dracc.description AS dracc_des,
                      cracc.description AS cracc_des
                      FROM t_bank_entry AS BE
                      INNER JOIN m_account AS dracc ON dracc.code = BE.draccId
                      INNER JOIN m_account AS cracc ON cracc.code = BE.craccId
                      WHERE BE.`nno` ='".$_POST['qno']."' AND BE.cl = '".$this->sd['cl']."' AND BE.bc = '".$this->sd['branch']."'";
                  
        $r_detail['r_bank_entry']=$this->db->query($sql)->result();    //pass as the variable in pdf page t_bank_entry_list

        $num = $this->db->query($sql)->row()->amount;

        $this->utility->num_in_letter($num);
      
        $r_detail['rec']=convertNum($num);;

        $this->db->select(array('t_bank_entry.oc', 's_users.discription','actionDate'));
        $this->db->from('t_bank_entry');
        $this->db->join('s_users', 't_bank_entry.oc=s_users.cCode');
        $this->db->where('t_bank_entry.cl', $this->sd['cl']);
        $this->db->where('t_bank_entry.bc', $this->sd['branch']);
        $this->db->where('t_bank_entry.nno', $_POST['qno']);
        $r_detail['user'] = $this->db->get()->result();


        if($this->db->query($sql)->num_rows()>0)
        {
            $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
        }
        else
        {
            echo "<script>alert('No Data');window.close();</script>";
        }
  }
  
}

