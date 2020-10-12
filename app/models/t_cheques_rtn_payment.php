<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class t_cheques_rtn_payment extends CI_Model {
    private $sd;
    private $mtb;
    private $mod = '003';
  function __construct(){
	parent::__construct();
	  $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');
    $this->max_no = $this->utility->get_max_no("t_cheque_payment_rtn_sum", "no");
    $this->credit_max_no = $this->utility->get_max_no("t_credit_note", "nno");
  }
    
  public function base_details(){   
    $a['id'] = $this->utility->get_max_no("t_cheque_payment_rtn_sum", "no"); 
    $a['credit_no'] = $this->utility->get_max_no("t_credit_note", "nno"); 
    $a['sd'] = $this->sd;
        
    return $a;
  }
    
  public function cheque_list(){  
      if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
      $status = "(STATUS='D') ";

      $sql = "SELECT  c.cheque_no,
                      c.bank,
                      b.description,
                      c.amount,
                      c.account,
                      cd.bank_id,
                      tr.memo,
                      c.status,
                      tr.acc_code AS sup_code,
                      c.`trans_code` AS t_code,
                      tr.pay_cash,
                      tr.pay_receive_chq AS chq_amount,
                      tr.nno AS trans_n,
                      tc.description AS t_des,
                      macc.description AS bank_des,
                      d.bank_date,
                      r.nno AS voucher_no  
                FROM t_cheque_issued c 
                JOIN m_account b ON b.code = c.bank 
                JOIN t_cheque_withdraw_det d  ON d.cl = c.cl  AND d.bc = c.bc  AND d.account_no = c.account  AND d.cheque_no = c.cheque_no 
                JOIN t_cheque_withdraw_sum cd  ON d.cl = cd.cl AND d.bc = cd.bc AND d.nno = cd.nno 
                JOIN m_account macc ON macc.code = cd.`bank_id`
                JOIN (SELECT * FROM t_voucher_det t 
                  WHERE trans_code='3' 
                    AND t.cl='".$this->sd['cl']."' 
                    AND t.bc='".$this->sd['branch']."')r ON r.cl = c.cl AND r.bc = c.bc AND r.nno = c.trans_no
                JOIN t_voucher_sum tr ON tr.cl = r.cl AND tr.bc = r.bc AND tr.nno = r.nno 
                JOIN t_trans_code tc ON tc.code =  c.`trans_code`
                WHERE $status 
                AND r.trans_code='3' 
                AND c.`cl` ='".$this->sd['cl']."' 
                AND c.bc='".$this->sd['branch']."'
                AND (c.cheque_no LIKE '%$_POST[search]%')
                GROUP BY c.`bank`, c.`cheque_no`
                LIMIT 20";
     
      $query = $this->db->query($sql);

      $a  = "<table id='item_list' style='width : 100%' >";
      $a .= "<thead><tr>";
      $a .= "<th class='tb_head_th'>Cheque No</th>";
      $a .= "<th class='tb_head_th'>Bank ID</th>";
      $a .= "<th class='tb_head_th' colspan='2'>Bank Name</th>";
      $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

      foreach($query->result() as $r){
        $a .= "<tr class='cl'>";
        $a .= "<td>".$r->cheque_no."</td>";
        $a .= "<td>".$r->bank."</td>";
        $a .= "<td>".$r->description."</td>";

        $a .= "<td style='display:none;'>".$r->amount."</td>";
        $a .= "<td style='display:none;'>".$r->account."</td>";
        $a .= "<td style='display:none;'>".$r->voucher_no."</td>";
        $a .= "<td style='display:none;'>".$r->bank_id."</td>";
        $a .= "<td style='display:none;'>".$r->bank_des."</td>";
        $a .= "<td style='display:none;'>".$r->memo."</td>";

        $a .= "<td style='display:none;'>".$r->pay_cash."</td>";
        $a .= "<td style='display:none;'>".$r->chq_amount."</td>";
        $a .= "<td style='display:none;'>".$r->trans_n."</td>";
        $a .= "<td style='display:none;'>".$r->t_des."</td>";
        $a .= "<td style='display:none;'>".$r->bank_date."</td>";
        $a .= "<td style='display:none;'>".$r->sup_code."</td>";
        $a .= "<td style='display:none;'>".$r->t_code."</td>";
        $a .= "<td style='display:none;'>".$r->status."</td>";
        $a .= "</tr>";
      }
      $a .= "</table>";
      echo $a;
  }  

  public function load_default_acc(){
    $def_acc = $this->utility->get_default_acc('CHEQUE_IN_HAND');
    $a['code'] = $def_acc;
    $a['des']  = 'CHEQUE_IN_HAND';


    if($def_acc==""){
      $a="2";
    }
    echo json_encode($a);
  }

  public function load_transactions(){
    $sql="SELECT  c.ddate,
                  c.nno,
                  c.net_amount,
                  t.balance,
                  t.payment 
          FROM t_voucher_det t 
          JOIN (SELECT * FROM t_grn_sum) c ON c.cl = t.cl AND c.bc = t.bc  AND t.trans_no = c.nno 
          WHERE t.nno ='".$_POST['no']."' AND t.cl='".$this->sd['cl']."' 
          AND t.bc='".$this->sd['branch']."' ";
    $query = $this->db->query($sql);
    if($query->num_rows()>0){
      $result = $query->result();
    }else{
      $result = 2;
    }
    echo json_encode($result);
  }

  public function validation(){
    $status         = 1;
    /*$account_update = $this->account_update(0);
    if ($account_update != 1) {
      return "Invalid account entries";
    }*/
    return $status;
  }

  public function save(){
    $this->db->trans_begin();
    error_reporting(E_ALL);
    function exceptionThrower($type, $errMsg, $errFile, $errLine){
      throw new Exception($errMsg);
    }
    set_error_handler('exceptionThrower');
    try {
      $validation_status = $this->validation();
      if($validation_status == 1) {
        $def_acc = $this->utility->get_default_acc('CHEQUE_IN_HAND');
        if(isset($_POST['refund_type'])){
          $settle = $_POST['refund_type'];
        }else{
          $settle = "0";
        }

        $chq_sum = array(
          "cl"            => $this->sd['cl'],
          "bc"            => $this->sd['branch'],
          "no"            => $this->max_no,
          "ddate"         => $_POST['ddate'],
          "ref_no"        => $_POST['ref_no'],
          "type"          => $_POST['type'],
          "chq_type"      => $_POST['chq_type'],
          "cheque_no"     => $_POST['cheque_no'],
          "trans_code"    => $_POST['trans_code_c'],
          "trans_no"      => $_POST['Trans_no'],
          "memo"          => $_POST['memo'],
          "description"   => $_POST['des'],
          "new_bank_date" => $_POST['new_ddate'],
          "amount"        => $_POST['tot_charge'],
          "dr_acc"        => $_POST['dr_acc'],
          "cr_acc"        => $_POST['cr_acc'],
          "credit_note_no"=> $_POST['credit_no'],
          "settle_type"   => $settle,
          "chq_return_amount" => $_POST['cqh_ret_charge'],
          "other_amount"  => $_POST['other_charge'],
          "supplier"      => $_POST['supplier'],
          "bank"          => $_POST['bank'],
          "realize_date"  => $_POST['realize_date'],
          "account"       => $_POST['account'], 
          "chq_amount"    => $_POST['cheque_val_1'], 
          "previous_chq_status" => $_POST['pre_status'],
          "oc"            => $this->sd['oc'],
        );

        $update_cheque=array(
           "status" => "R"
        );
        $update_refund_cheque=array(
           "status" => "F"
        );


        $memo="";

        $total =(float)$_POST['cheque_val_1'] + (float)$_POST['cqh_ret_charge'] + (float)$_POST['other_charge'];
        $memo  .="CHEQUE PAYMENT RETURN CHARGERS (CHQ NO - ".$_POST['cheque_no'].") (INVOICE NO - ";  
       
        for($t=0; $t<25; $t++){
          if($_POST['inv_'.$t] != ""){
            $memo .= $_POST['inv_'.$t].",";
          }
        }

        $memo .=")";

        /*$total =(float)$_POST['cheque_val_1'] + (float)$_POST['cqh_ret_charge'] + (float)$_POST['other_charge'];
        $memo  ="CHEQUE PAYMENT RETURN CHARGERS " . $this->max_no . "";*/

        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          $credit = $this->credit_max_no;
        }else{
          $credit = $_POST['credit_no'];
        }

        $t_credit_note = array(
          "cl" => $this->sd['cl'],
          "bc" => $this->sd['branch'],
          "nno" => $credit,
          "ddate" => $_POST['ddate'],
          "ref_no" => $_POST['ref_no'],
          "memo" => $memo,
          "is_customer" => 0,
          "code" => $_POST['supplier'],
          "acc_code" => $_POST['supplier'],
          "amount" => $total,
          "oc" => $this->sd['oc'],
          "post" => "",
          "post_by" => "",
          "post_date" => "",
          "is_cancel" => 0,
          "ref_trans_no" => $this->max_no,
          "ref_trans_code" =>89,
          "balance"=>$total
        );

        $t_credit_note_update = array(
          "cl" => $this->sd['cl'],
          "bc" => $this->sd['branch'],
          "nno" => $credit,
          "ddate" => $_POST['ddate'],
          "ref_no" => $_POST['ref_no'],
          "is_customer" => 0,
          "code" => $_POST['supplier'],
          "acc_code" => $_POST['supplier'],
          "amount" => $total,
          "oc" => $this->sd['oc'],
          "post" => "",
          "post_by" => "",
          "post_date" => "",
          "is_cancel" => 0,
          "ref_trans_no" => $this->max_no,
          "ref_trans_code" =>89,
          "balance"=>$total
        );

     
        if($_POST['hid'] == "0" || $_POST['hid'] == "") {
          if($this->user_permissions->is_add('t_cheques_rtn_payment')) {
            $account_update = $this->account_update(0);
            if ($account_update == 1) {
              $this->load->model('trans_settlement');
              $this->db->insert("t_cheque_payment_rtn_sum", $chq_sum);

              $this->account_update(1);

              if(isset($_POST['refund_type'])){
                if($_POST['refund_type']=="1"){
                  //-----cheque refund (credit note)------
                  $this->trans_settlement->save_settlement("t_credit_note_trans", $_POST['supplier'], $_POST['ddate'], 17, $this->credit_max_no, 17, $this->credit_max_no, $total, "0");
                  $this->db->insert('t_credit_note', $t_credit_note);
                  $this->utility->update_credit_note_balance($_POST['supplier']);

                  $this->db->where('cheque_no', $_POST['cheque_no']);
                  $this->db->where("bank", $_POST['bank']);
                  $this->db->where('account', $_POST['account']);
                  $this->db->where("cl",$this->sd['cl']);
                  $this->db->where("bc",$this->sd['branch']);
                  $this->db->update("t_cheque_issued", $update_refund_cheque); 
                }else{
                  //-----cheque refund (settle)------
                }
              }else{
                // -----cheque return-----
                if($total > 0){
                  $this->trans_settlement->save_settlement("t_credit_note_trans", $_POST['supplier'], $_POST['ddate'], 17, $this->credit_max_no, 17, $this->credit_max_no, $total, "0");
                  $this->db->insert('t_credit_note', $t_credit_note);
                  $this->utility->update_credit_note_balance($_POST['supplier']);
                }
                $this->db->where('cheque_no', $_POST['cheque_no']);
                $this->db->where("bank", $_POST['bank']);
                $this->db->where('account', $_POST['account']);
                $this->db->where("cl",$this->sd['cl']);
                $this->db->where("bc",$this->sd['branch']);
                $this->db->update("t_cheque_issued", $update_cheque); 
              }
              $this->utility->save_logger("SAVE",89,$this->max_no,$this->mod);
              echo $this->db->trans_commit();
            }else{
              echo "invalid account entries";
              $this->db->trans_commit();
            }
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          }
        }else{
          if($this->user_permissions->is_edit('t_cheques_rtn_payment')){
            $account_update = $this->account_update(0);
            if ($account_update == 1) {
            $this->load->model('trans_settlement');
            $this->db->where("no", $this->max_no);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->update("t_cheque_payment_rtn_sum", $chq_sum); 
  
            if(isset($_POST['refund_type'])){
              if($_POST['refund_type']=="1"){
                //-----cheque refund (credit note)------
                $this->trans_settlement->delete_settlement("t_credit_note_trans", 17, $credit);
                $this->trans_settlement->save_settlement("t_credit_note_trans", $_POST['supplier'], $_POST['ddate'], 17, $credit, 17, $credit, $total, "0");
                $this->utility->update_credit_note_balance($_POST['supplier']);

                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where('nno', $credit);
                $this->db->update('t_credit_note', $t_credit_note_update);

                $this->db->where('cheque_no', $_POST['cheque_no']);
                $this->db->where("bank", $_POST['bank']);
                $this->db->where('account', $_POST['account']);
                $this->db->where("cl",$this->sd['cl']);
                $this->db->where("bc",$this->sd['branch']);
                $this->db->update("t_cheque_issued", $update_refund_cheque); 
              }else{
                //-----cheque refund (settle)------
              }
            }else{
              // -----cheque return-----
              if($total > 0){
                $this->trans_settlement->delete_settlement("t_credit_note_trans", 17, $credit);
                $this->trans_settlement->save_settlement("t_credit_note_trans", $_POST['supplier'], $_POST['ddate'], 17, $credit, 17, $credit, $total, "0");
                $this->utility->update_credit_note_balance($_POST['supplier']);

                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->where('nno', $credit);
                $this->db->update('t_credit_note', $t_credit_note_update);
              }

              $this->db->where('cheque_no', $_POST['cheque_no']);
              $this->db->where("bank", $_POST['bank']);
              $this->db->where('account', $_POST['account']);
              $this->db->where("cl",$this->sd['cl']);
              $this->db->where("bc",$this->sd['branch']);
              $this->db->update("t_cheque_issued", $update_cheque); 
              }
              $this->account_update(1);
              $this->utility->save_logger("EDIT",89,$this->max_no,$this->mod);
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
      }else{
        echo $validation_status;
        $this->db->trans_commit();
      }
    }catch (Exception $e) {
      $this->db->trans_rollback();
      echo $e->getMessage()."- Operation fail please contact admin";
    }
  
  }
    
  public function account_update($condition){
    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_code", 88);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_check_double_entry");
      
    if($_POST['hid']=="0"||$_POST['hid']==""){
            
    }else{
      if($condition=="1"){
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('trans_code', 89);
        $this->db->where('trans_no', $this->max_no);
        $this->db->delete('t_account_trans');
      }
    }
    
    $config = array(
      "ddate" => $_POST['ddate'],
      "trans_code" => 89,
      "trans_no" => $this->max_no,
      "op_acc" => 0,
      "reconcile" => 0,
      "cheque_no" => 0,
      "narration" => "",
      "ref_no" => ""
    );
    $this->load->model('account');
    $this->account->set_data($config);
     
    $des = "Cheque Payment Return [" .$_POST['des']."]";

    $total = (float)$_POST['cheque_val_1'] + (float)$_POST['cqh_ret_charge'] + (float)$_POST['other_charge'];
    
    $supplier  = $_POST['supplier'];
    $bank_code = $_POST['dr_acc'];
    $bank_chg  = $this->utility->get_default_acc('CHQ_RTN_CHG_BANK_P');
    $other_chg = $this->utility->get_default_acc('CHQ_RTN_CHG_OTHER_P');

    if($total>0){
      $this->account->set_value2($des, $total, "cr", $supplier,$condition);
    }
    if($_POST['cheque_val_1']>0){
      $this->account->set_value2($des, $_POST['cheque_val_1'], "dr", $bank_code,$condition);
    }
    if($_POST['cqh_ret_charge']>0){
      $this->account->set_value2($des, $_POST['cqh_ret_charge'], "dr", $bank_chg,$condition);
    }
    if($_POST['other_charge']>0){
      $this->account->set_value2($des, $_POST['other_charge'], "dr", $other_chg,$condition);
    }
      
      if ($condition == 0) {
        $query = $this->db->query("
             SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
             FROM `t_check_double_entry` t
             LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
             WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='89'  AND t.`trans_no` ='" . $this->max_no . "' AND 
             a.`is_control_acc`='0'");
        
        if ($query->row()->ok == "0") {
          $this->db->where("trans_no", $this->max_no);
          $this->db->where("trans_code", 89);
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
  	$sql="SELECT  c.no,
                  c.ddate,
                  c.ref_no,
                  c.type,
                  b.`description` AS bank_name,
                  c.`account`,
                  c.chq_type,
                  c.cheque_no,
                  c.description,
                  c.memo,
                  c.`trans_code`,
                  c.`trans_no`,
                  t.`description` AS t_name,
                  c.realize_date,
                  c.settle_type,
                  c.new_bank_date,
                  c.credit_note_no,
                  c.chq_return_amount,
                  c.other_amount,
                  c.is_cancel,
                  c.supplier,
                  c.bank,
                  c.amount,
                  c.`chq_amount`,
                  m.`description` AS cr_des,
                  c.`cr_acc`,
                  mm.description AS dr_des,
                  c.`dr_acc`,
                  c.previous_chq_status 
        FROM `t_cheque_payment_rtn_sum` c 
        JOIN m_account b  ON b.`code` = c.`bank` 
        JOIN t_trans_code t ON t.`code` = c.`trans_code` 
        JOIN m_account m ON m.`code` = c.`cr_acc` 
        JOIN m_account mm ON mm.`code` = c.`dr_acc` 
        WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND no ='".$_POST['code']."'";
  	
    $query = $this->db->query($sql);

    if($query->num_rows()>0){
      $a = $query->result();
    }else{
      $a="2";
    }
  	echo json_encode($a);
  }
    
  public function delete(){
    $this->db->trans_begin();
      error_reporting(E_ALL); 
      function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
          throw new Exception($errMsg); 
      }
      set_error_handler('exceptionThrower'); 
      try {
        if($this->user_permissions->is_delete('t_cheques_rtn_payment')){

          $id = $_POST['code'];
          $credit = $_POST['credit'];

          $this->db->where("no", $id);
          $this->db->where("bc", $this->sd['branch']);
          $this->db->where("cl", $this->sd['cl']);
          $this->db->limit(1);
          $this->db->update("t_cheque_payment_rtn_sum", array("is_cancel"=>1));

          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->where('trans_code','89');
          $this->db->where('trans_no',$id);
          $this->db->delete('t_account_trans');

          $this->load->model('trans_settlement');
          $this->trans_settlement->delete_settlement("t_credit_note_trans", 17, $credit);

          $this->db->where("nno", $credit);
          $this->db->where("bc", $this->sd['branch']);
          $this->db->where("cl", $this->sd['cl']);
          $this->db->limit(1);
          $this->db->update("t_credit_note", array("is_cancel"=>1));

          $update_cheque=array("status" => $_POST['chq_pre']);

          $this->db->where('cheque_no', $_POST['chq_no']);
          $this->db->where("bank", $_POST['bank']);
          $this->db->where('account', $_POST['account']);
          $this->db->where("cl",$this->sd['cl']);
          $this->db->where("bc",$this->sd['branch']);
          $this->db->update("t_cheque_issued", $update_cheque);

          $this->utility->update_credit_note_balance($_POST['supplier']);
          $this->utility->save_logger("CANCEL",89,$id,$this->mod);
          echo $this->db->trans_commit();
        }else{
          $this->db->trans_commit();
          echo "No permission to delete records";
        }  
      }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo $e->getMessage()." - Operation fail please contact admin"; 
      }
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
       
    $sql="SELECT  c.no,
                  c.ddate,
                  c.ref_no,
                  c.type,
                  b.`description` AS bank_name,
                  c.`account`,
                  c.chq_type,
                  c.cheque_no,
                  c.description,
                  c.memo,
                  c.`trans_code`,
                  c.`trans_no`,
                  t.`description` AS t_name,
                  c.realize_date,
                  c.settle_type,
                  c.new_bank_date,
                  c.credit_note_no,
                  c.chq_return_amount,
                  c.other_amount,
                  c.is_cancel,
                  c.supplier,
                  c.bank,
                  c.amount,
                  c.`chq_amount`,
                  m.`description` AS cr_des,
                  c.`cr_acc`,
                  mm.description AS dr_des,
                  c.`dr_acc`,
                  c.previous_chq_status,
                  t.description as t_des 
        FROM `t_cheque_payment_rtn_sum` c 
        JOIN m_account b  ON b.`code` = c.`bank` 
        JOIN t_trans_code t ON t.`code` = c.`trans_code` 
        JOIN m_account m ON m.`code` = c.`cr_acc` 
        JOIN m_account mm ON mm.`code` = c.`dr_acc` 
          WHERE c.cl='".$this->sd['cl']."' 
          AND c.bc='".$this->sd['branch']."' 
          AND c.no='$id'";

    $sum = $this->db->query($sql);            
    $r_detail['sum'] = $sum->result(); 
    
    if($sum->num_rows()>0){            
        $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }else{
        echo "<script>alert('No data found');close();</script>";
    }
                
  }
    


}