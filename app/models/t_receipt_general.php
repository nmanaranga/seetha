<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class t_receipt_general extends CI_Model
{
  
  private $sd;
  private $mtb;
  
  private $mod = '003';
  
  function __construct()
  {
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    //$this->mtb = $this->tables->tb['t_receipt_gl_sum'];
    $this->load->model('user_permissions');
    $this->max_no = $this->utility->get_max_no_sub2("t_receipt_gl_sum", "nno");
  }
  
  public function base_details()
  {
    $a['table_data'] = $this->data_table();
    $this->load->model('r_groups');
    $a['groups'] = $this->r_groups->select();
    $this->load->model('r_sales_category');
    $a['sales_category'] = $this->r_sales_category->select();
    $a['type']           = 'RECEIPT_GENERAL';
    $a['max_no']= $this->utility->get_max_no("t_receipt_gl_sum", "nno");
    
    return $a;
  }
  
  
  
  public function data_table()
  {
    $this->load->library('table');
    $this->load->library('useclass');
    
    $this->table->set_template($this->useclass->grid_style());
    
    $code        = array(
      "data" => "Code",
      "style" => "width: 50px;"
      );
    $description = array(
      "data" => "Description",
      "style" => ""
      );
    $action      = array(
      "data" => "Action",
      "style" => "width: 60px;"
      );
    
    $this->table->set_heading($code, $description, $action);
    
    
    return $this->table->generate();
  }
  
  public function get_data_table()
  {
    echo $this->data_table();
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
  
  
  
  
  
  
  
  
  public function save()
  {
    $this->db->trans_begin();
    error_reporting(E_ALL);
    
    function exceptionThrower($type, $errMsg, $errFile, $errLine)
    {
      throw new Exception($errMsg);
    }
    
    set_error_handler('exceptionThrower');
    try {
      
      $validation_status = $this->validation();
      if ($validation_status == 1) {
        
        //$p = $this->user_permissions->get_permission($this->mod, array('is_edit', 'is_add'))
        
        $acc_code = $this->utility->get_default_acc('CASH_IN_HAND');
        
        $sum = array(
          "cl" => $this->sd['cl'],
          "bc" => $this->sd['branch'],
          "nno" => $this->max_no,
          "type" => $_POST['type2'],
          "sub_no" => $_POST['id'],
          "ref_no" => $_POST['ref_no'],
          "ddate" => $_POST['date'],
          "paid_acc" => $_POST['0_0'],
          "note" => $_POST['description'],
          "pay_post_dated_chq" =>isset($_POST['pdchq'])?$_POST['pdchq']:'0.00',
          "cash_amount" => $_POST['cash'],
          "pay_card" => $_POST['credit_card'],
          "cash_acc" => $acc_code,
          "cheque_amount" => $_POST['cheque_recieve'],
          "cheque_acc" => $_POST['0_0'],
          "oc" => $this->sd['oc']
          
          );
        
        
        
        for ($i = 0; $i < 25; $i++) {
          if ($_POST['0_' . $i] != "" && $_POST['0_' . $i] != "0") {
            
            $det[] = array(
              "bc" => $this->sd['branch'],
              "cl" => $this->sd['cl'],
              "nno" => $this->max_no,
              "acc_code" => $_POST['0_' . $i],
              "amount" => $_POST['1_' . $i],
              "ref_no" => $_POST['2_' . $i],
              "type" => $_POST['type2'],
              "sub_no" => $_POST['id']
              
              );
          }
        }
        
        if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
          if ($this->user_permissions->is_add('t_receipt_general')) {
            $account_update = $this->account_update(0);
            if ($account_update == 1) {
              $this->db->insert("t_receipt_gl_sum", $sum);
              
              if (count($det)) {
                $this->db->insert_batch("t_receipt_gl_det", $det);
              }
              $this->account_update(1);
              
              $this->load->model('t_payment_option');
              //$this->t_payment_option->delete_all_payments_opt(49, $this->max_no);
              $this->t_payment_option->save_payment_option($this->max_no, 49);
              $this->utility->save_logger("SAVE",49,$this->max_no,$this->mod,$_POST['id']);
              echo $this->db->trans_commit()."@".$this->max_no;
            }else{
              echo "invalid account entries";
              $this->db->trans_commit();
            }
          } else {
            echo "No permission to save records";
            $this->db->trans_commit();
          }
          
        } else {
          if ($this->user_permissions->is_edit('t_receipt_general')) {
            $account_update = $this->account_update(0);
            if ($account_update == 1) {

              $this->db->where('sub_no', $_POST['hid']);
              $this->db->where("type", $_POST['type2']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->delete("t_receipt_gl_det");
              
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->where('sub_no', $_POST['hid']);
              $this->db->where("type", $_POST['type2']);
              $this->db->update("t_receipt_gl_sum", $sum);
              
              if (count($det)) {
                $this->db->insert_batch("t_receipt_gl_det", $det);
              }
              
              $this->account_update(1);
              $this->load->model('t_payment_option');
              $this->t_payment_option->update_pd_chq_status(49, $this->max_no);
              $this->t_payment_option->delete_all_payments_opt(49, $this->max_no);
              $this->t_payment_option->save_payment_option($this->max_no, 49);

              $this->utility->save_logger("EDIT",49,$this->max_no,$this->mod,$_POST['id']);
              
              echo $this->db->trans_commit();
            }else{
              echo "invalid account entries";
              $this->db->trans_commit();
            }
          } else {
            echo "No permission to save records";
            $this->db->trans_commit();
          }
        }
        
      } else {
        echo $validation_status;
        $this->db->trans_commit();
      }
    }
    catch (Exception $e) {
      $this->db->trans_rollback();
      echo $e->getMessage()."Operation fail please contact admin";
    }
  }
  
  
  
  
  
  
  
  public function account_update($condition)
  {
    
    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_code", 49);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_check_double_entry");
    
    if($_POST['hid']=="0"||$_POST['hid']==""){
      
    }else{
      if($condition=="1"){
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('trans_code', 49);
        $this->db->where('trans_no', $this->max_no);
        $this->db->delete('t_account_trans');
      }
    }

    
    $config = array(
      "ddate" => $_POST['date'],
      "trans_code" => 49,
      "trans_no" => $this->max_no,
      "op_acc" => 0,
      "reconcile" => 0,
      "cheque_no" => 0,
      "narration" => "",
      "ref_no" => $_POST['ref_no']
      );
    
    
    $this->load->model('account');
    $this->account->set_data($config);
    
    for ($i = 0; $i < 25; $i++) {
      if ($_POST['0_' . $i] != "" && $_POST['0_' . $i] != "0") {
        $ser_acc = $this->utility->get_default_acc('SERVICE_INCOME');
        if($_POST['0_'.$i]==$ser_acc){
          $dess = $_POST['description'];
        }else{
          $dess = $_POST['description'];
          //$dess = $_POST['n_' . $i];
        }
        $this->account->set_value4($dess, $_POST['1_' . $i], "cr", $_POST['0_' . $i], $condition, $_POST['id']);
      }
    }
    
    $des = $_POST['description'];
    
    if (isset($_POST['cash']) && !empty($_POST['cash']) && $_POST['cash'] > 0) {
      $acc_code = $this->utility->get_default_acc('CASH_IN_HAND');
      $this->account->set_value4($des, $_POST['cash'], "dr", $acc_code, $condition, $_POST['id']);
    }
    
    if (isset($_POST['cheque_recieve']) && !empty($_POST['cheque_recieve']) && $_POST['cheque_recieve'] > 0) {
      $acc_code = $this->utility->get_default_acc('CHEQUE_IN_HAND');
      $this->account->set_value4($des, $_POST['cheque_recieve'], "dr", $acc_code, $condition, $_POST['id']);
    }

    if(isset($_POST['pdchq']) && !empty($_POST['pdchq']) && $_POST['pdchq']>0){
      $acc_code = $this->utility->get_default_acc('CHEQUE_IN_HAND');
      $this->account->set_value4($des, $_POST['pdchq'], "dr", $acc_code, $condition, $_POST['id']);
    }
    
    /*if (isset($_POST['credit_card']) && !empty($_POST['credit_card']) && $_POST['credit_card'] > 0) {
      $acc_code = $this->utility->get_default_acc('CREDIT_CARD_IN_HAND');
      $this->account->set_value2($des, $_POST['credit_card'], "dr", $acc_code, $condition);
    }*/

    if(isset($_POST['credit_card']) && !empty($_POST['credit_card']) && $_POST['credit_card']>0){
      for($x = 0; $x<25; $x++){
        if(isset($_POST['type1_'.$x]) && isset($_POST['amount1_'.$x]) && isset($_POST['bank1_'.$x]) && isset($_POST['no1_'.$x])){
          if(!empty($_POST['type1_'.$x]) && !empty($_POST['amount1_'.$x]) && !empty($_POST['bank1_'.$x]) && !empty($_POST['no1_'.$x])){
            $acc_code = $_POST['acc1_'.$x];
            $this->account->set_value4($des, $_POST['amount1_'.$x], "dr", $acc_code,$condition,$_POST['id']);    
            
            $acc_code_dr = $this->utility->get_default_acc('CREDIT_CARD_INTEREST');
            $this->account->set_value4('Merchant commission', $_POST['amount_rate1_'.$x], "dr", $acc_code_dr,$condition,$_POST['id']);    

            $acc_code_cr = $this->utility->get_default_acc('CREDIT_CARD_IN_HAND');
            $this->account->set_value4('Merchant commission', $_POST['amount_rate1_'.$x], "cr", $acc_code_cr,$condition,$_POST['id']); 

          }
        }
      }  
    }
    
    if (isset($_POST['cheque_issue']) && !empty($_POST['cheque_issue'])) {
      $acc_code = $this->utility->get_default_acc('ISSUE_CHEQUES');
      $this->account->set_value4($des, $_POST['cheque_issue'], "dr", $acc_code, $condition,$_POST['id']);
    }
    
    
    if ($condition == 0) {
      $query = $this->db->query("
       SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
       FROM `t_check_double_entry` t
       LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
       WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='49'  AND t.`trans_no` ='" . $this->max_no . "' AND 
       a.`is_control_acc`='0'");
      
      if ($query->row()->ok == "0") {
        $this->db->where("trans_no", $this->max_no);
        $this->db->where("trans_code", 49);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_check_double_entry");
        return "0";
      } else {
        return "1";
      }
    }
  }
  
  
  
  
  public function check_code()
  {
    $this->db->where('code', $_POST['code']);
    $this->db->limit(1);
    
    echo $this->db->get($this->mtb)->num_rows;
  }
  
  
  
  public function delete()
  {
    
    $this->db->trans_begin();
    error_reporting(E_ALL);
    
    function exceptionThrower($type, $errMsg, $errFile, $errLine)
    {
      throw new Exception($errMsg);
    }
    
    set_error_handler('exceptionThrower');
    try {
      if ($this->user_permissions->is_delete('t_receipt_general')) {
        $this->db->where("sub_no", $_POST['code']);
        $this->db->where("type", $_POST['type2']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->limit(1);
        $this->db->update("t_receipt_gl_sum", array(
          "is_cancel" => 1
          ));
        
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('trans_code', 49);
        $this->db->where('trans_no', $_POST['hid']);
        $this->db->delete('t_account_trans');

        $this->db->query("INSERT INTO t_cheque_received_cancel SELECT * FROM t_cheque_received WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND trans_code='49' AND trans_no ='".$_POST['hid']."'");
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $this->db->where("trans_code",49);
        $this->db->where("trans_no",$_POST['hid']);
        $this->db->delete("t_cheque_received"); 

        $this->load->model('t_payment_option');
        $this->t_payment_option->update_pd_chq_status(49, $_POST['hid']);

        $this->utility->save_logger("CANCEL",49,$_POST['hid'],$this->mod);
        echo $this->db->trans_commit();
      } else {
        echo "No permission to delete records";
        $this->db->trans_commit();
      }
    }
    catch (Exception $e) {
      $this->db->trans_rollback();
      echo $e->getMessage() . "Operation fail please contact admin";
    }
  }
  
  public function select()
  {
    $query = $this->db->get($this->mtb);
    
    $s = "<select name='sales_ref' id='sales_ref'>";
    $s .= "<option value='0'>---</option>";
    foreach ($query->result() as $r) {
      $s .= "<option title='" . $r->name . "' value='" . $r->code . "'>" . $r->code . " | " . $r->name . "</option>";
    }
    $s .= "</select>";
    
    return $s;
  }
  
  public function get_max_no_type()
  {
    
    $table_name = $_POST['table'];
    $field_name = $_POST['nno'];
    $type       = $_POST['type'];
    
    if (isset($_POST['hid'])) {
      if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
        $this->db->select_max($field_name);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->where("type", $type);
        echo (int)$this->db->get($table_name)->first_row()->$field_name + 1;
      } else {
        echo $_POST['hid'];
      }
    } else {
      $this->db->select_max($field_name);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->where("type", $type);
      echo $this->db->get($table_name)->first_row()->$field_name + 1;
    }
  }
  
  // private function max_no() {
  
  //     $this->db->where('cl', $this->sd['cl']);
  //     $this->db->where('bc', $this->sd['branch']);
  //     $this->db->select_max("nno");
  //     return $this->db->get("t_receipt_gl_sum")->first_row()->nno + 1;
  // }
  
  public function get_data()
  {


    $sql      = "SELECT 
    a.`code` AS paid_acc_code,
    a.`description` AS paid_acc_des,
    v.`note`,
    v.`sub_no` as nno,
    v.`nno` as hid_nno,
    v.`ref_no` as ref,
    v.`type`,
    v.`ddate`,
    v.`cash_amount`,
    v.is_cancel,
    v.`cheque_amount`,
    v.pay_card,
    v.pay_post_dated_chq,
    (v.`cash_amount` + v.cheque_amount) AS tot 
    FROM
    t_receipt_gl_sum v 
    JOIN m_account a ON a.code = v.paid_acc 

    WHERE v.bc = '" . $this->sd['branch'] . "' 
    AND v.cl = '" . $this->sd['cl'] . "' ";
    if($_POST['i']==1){
      $sql.= " AND v.nno = '" . $_POST['id'] . "'";
    }else{
      $sql.= " AND v.type = '" . $_POST['type'] . "'
      AND v.sub_no = '" . $_POST['id'] . "'";
    }

    $sql.= " LIMIT 1";
    $query    = $this->db->query($sql);
    $a['sum'] = $query->first_row();
    
    $n_no = 0;
    $n_no = $query->row()->hid_nno;
    
    $sql_cheque  = "SELECT * FROM opt_receive_cheque_det  
    WHERE cl='" . $this->sd['cl'] . "' AND bc='" . $this->sd['branch'] . "'
    AND trans_code='49' AND trans_no='$n_no'";
    $queryy      = $this->db->query($sql_cheque);
    $a['cheque'] = $queryy->result();
    
    
    
    $sql      = "SELECT 
    d.`acc_code`,
    a.`description`,
    d.`amount`,
    d.`ref_no`
    FROM t_receipt_gl_sum 

    JOIN t_receipt_gl_det d
    ON t_receipt_gl_sum.`sub_no` = d.`sub_no` AND t_receipt_gl_sum.`type` = d.`type`
    AND t_receipt_gl_sum.`cl` = d.`cl`
    AND t_receipt_gl_sum.`bc` = d.`bc`
    JOIN m_account a ON a.code = d.`acc_code`
    WHERE t_receipt_gl_sum.bc='" . $this->sd['branch'] . "' AND t_receipt_gl_sum.cl='" . $this->sd['cl'] . "' ";

    if($_POST['i']==1){
      $sql.= " AND d.nno = '" . $_POST['id'] . "'";
    }else{
      $sql.= " AND d.type = '" . $_POST['type'] . "'
      AND d.sub_no = '" . $_POST['id'] . "'";
    }


    $query    = $this->db->query($sql);
    $a['det'] = $query->result();
    
    
    echo json_encode($a);
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
    $r_detail['duplicate'] = $_POST['is_duplicate'];
    
    
    $invoice_number      = $this->utility->invoice_format($_POST['qno']);
    $invoice_number2      = $this->utility->invoice_format($_POST['p_hid_nno']);

    
    $session_array       = array(
      $this->sd['cl'],
      $this->sd['branch'],
      $invoice_number
      );
    $r_detail['session'] = $session_array;

    $session_array2       = array(
      $this->sd['cl'],
      $this->sd['branch'],
      $invoice_number2
      );
    $r_detail['session2'] = $session_array2;
    
    
    $r_detail['type']         = $_POST['type'];
    $r_detail['dt']           = $_POST['dt'];
    $r_detail['qno']          = $_POST['qno'];
    $r_detail['receipt_type'] = $_POST['receipt_type'];
    
    $r_detail['receipt_no'] = $_POST['qno'];
    
    $r_detail['ddate'] = $_POST['ddate'];
    $r_detail['tot']   = $_POST['tot'];
    
    $r_detail['num'] = $_POST['tot'];
    
    $num = $_POST['tot'];
    
    $this->utility->num_in_letter($num);
    $r_detail['rec'] = convertNum($num);
    ;
    
    
    $r_detail['page']        = $_POST['page'];
    $r_detail['header']      = $_POST['header'];
    $r_detail['orientation'] = $_POST['orientation'];
    


    $r_detail['acc_code'] = $_POST['acc_code'];
    $r_detail['acc_des']  = $_POST['acc_des'];
    $r_detail['vou_des']  = $_POST['vou_des'];
    
    $this->db->select(array(
      'name'
      ));
    $this->db->where("code", $_POST['salesp_id']);
    $query = $this->db->get('m_employee');
    

    foreach ($query->result() as $row) {
      $r_detail['employee'] = $row->name;
    }
    
    
    $sql="SELECT t_receipt_gl_det.acc_code,
    t_receipt_gl_det.amount, 
    m_account.description
    FROM t_receipt_gl_det 
    JOIN m_account on t_receipt_gl_det.acc_code = m_account.code
    WHERE t_receipt_gl_det.cl='".$this->sd['cl']."' AND t_receipt_gl_det.bc='".$this->sd['branch']."' 
    AND sub_no = '".$_POST['qno']."' AND t_receipt_gl_det.`type`= '".$_POST['receipt_type']."'";
    $r_detail['dets'] = $this->db->query($sql)->result(); 

    $sql="SELECT * FROM t_receipt_gl_sum 
    WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' 
    AND sub_no = '".$_POST['qno']."' AND `type`= '".$_POST['receipt_type']."'";
    $r_detail['sum'] = $this->db->query($sql)->result(); 
    
    
    $sql="SELECT * 
    FROM opt_receive_cheque_det 
    JOIN m_bank ON m_bank.code = opt_receive_cheque_det.bank WHERE trans_code='49' 
    AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'
    AND trans_no = '".$_POST['p_hid_nno']."'";
    $r_detail['cheque'] = $this->db->query($sql)->result();  


    $sql="SELECT * 
    FROM `opt_credit_card_det` 
    JOIN m_bank ON m_bank.code = `opt_credit_card_det`.bank_id 
    WHERE trans_code='49' 
    AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'
    AND trans_no = '".$_POST['p_hid_nno']."'";
    $r_detail['card'] = $this->db->query($sql)->result();  

    //---------credit card details        

    $this->db->select(array('t_receipt_gl_sum.oc', 's_users.discription','action_date'));
    $this->db->from('t_receipt_gl_sum');
    $this->db->join('s_users', 't_receipt_gl_sum.oc=s_users.cCode');
    $this->db->where('t_receipt_gl_sum.cl', $this->sd['cl']);
    $this->db->where('t_receipt_gl_sum.bc', $this->sd['branch']);
    $this->db->where('t_receipt_gl_sum.sub_no', $_POST['qno']);
    $this->db->where('t_receipt_gl_sum.type', $_POST['receipt_type']);
    $r_detail['user'] = $this->db->get()->result();
    
    
    $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
  }
}