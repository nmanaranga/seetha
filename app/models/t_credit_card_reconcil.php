<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class t_credit_card_reconcil extends CI_Model {

    private $sd;
    private $mtb;
    private $trans_code=50;
    private $mod = '003';
    private $tb_sum;
    private $tb_det;
    private $tb_chq_received;

    function __construct() {
        parent::__construct();
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->tb_chq_received=$this->tables->tb['t_cheque_issued'];
        $this->tb_check_double_entry = $this->tables->tb['t_check_double_entry'];
        $this->tb_branch = $this->tables->tb['s_branches'];
        $this->tb_users = $this->tables->tb['s_users'];
        $this->tb_trance = $this->tables->tb['t_account_trans'];        
        $this->tb_account = $this->tables->tb['m_account'];
        $this->load->model('user_permissions');
        $this->max_no = $this->utility->get_max_no("t_credit_card_reconcil_sum", "nno");
     
    }

    public function base_details() {
        $this->load->model('m_option_setup');        
        $a['id'] = $this->utility->get_max_no("t_credit_card_reconcil_sum", "nno"); 
        $a['sd'] = $this->sd;
        

        return $a;
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

        $sum = array(
          "cl" => $this->sd['cl'],
          "bc" => $this->sd['branch'],
          "nno" => $this->max_no,
          "ddate" => $_POST['date'],
          "merchant_id" => $_POST['merchant_id'],
          "from_date" => $_POST['from_date'],
          "to_date" => $_POST['to_date'],
          "description" => $_POST['description'],
          "tot_amount" => $_POST['chk_amount'],
          "sys_comm_tot" => $_POST['chk_sys'],
          "actual_comm_tot" => $_POST['chk_act'],
          "tot_balance" => $_POST['chk_bal'],
          "oc" => $this->sd['oc'],
          "bank_acc" => $_POST['bank_acc']
        );

        $sum_update = array(
          "cl" => $this->sd['cl'],
          "bc" => $this->sd['branch'],
          "ddate" => $_POST['date'],
          "merchant_id" => $_POST['merchant_id'],
          "from_date" => $_POST['from_date'],
          "to_date" => $_POST['to_date'],
          "description" => $_POST['description'],
          "tot_amount" => $_POST['chk_amount'],
          "sys_comm_tot" => $_POST['chk_sys'],
          "actual_comm_tot" => $_POST['chk_act'],
          "tot_balance" => $_POST['chk_bal'],
          "oc" => $this->sd['oc'],
          "bank_acc" => $_POST['bank_acc']       
        );
                
        for ($i = 0; $i < 25; $i++) {
          if(isset($_POST['cardn_'.$i] , $_POST['amnt_'.$i], $_POST['bal_'.$i])){
            if ($_POST['cardn_' . $i] != "" && $_POST['amnt_' . $i] != "0" && $_POST['bal_' . $i] != "0"){
              if(isset($_POST['5_'.$i])){
                $tick=1;
              }else{
                $tick=0;
              }
              $det[] = array(
                "bc" => $this->sd['branch'],
                "cl" => $this->sd['cl'],
                "no" => $this->max_no,
                "trans_cl" => $_POST['tcl_'.$i],
                "trans_bc" => $_POST['tbc_'.$i],
                "trans_code" => $_POST['tcode_'.$i],
                "trans_no" => $_POST['tno_'.$i],
                "trans_date" => $_POST['date_'.$i],
                "credit_card_no" => $_POST['cardn_'.$i],
                "actual_amount" => $_POST['amnt_'.$i],
                "actual_comm" => $_POST['acom_'.$i],
                "actual_balance" => $_POST['bal_' . $i],
                "is_reconcil" => $tick
              );
            }
          }
        }

        $update_credit_card=array(
           "is_reconcil" => "1",
           "reconcil_no" => $this->max_no,
        );
        

        if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
          if ($this->user_permissions->is_add('t_credit_card_reconcil')) {
            $account_update = $this->account_update(0);
            if ($account_update == 1) {
              $this->db->insert("t_credit_card_reconcil_sum", $sum);
              if(isset($det)){
                if (count($det)) {
                  $this->db->insert_batch("t_credit_card_reconcil_det", $det);
                }
              }

              for ($i = 0; $i < 25; $i++) {
                if(isset($_POST['cardn_'.$i] , $_POST['amnt_'.$i], $_POST['bal_'.$i], $_POST['5_'.$i])){
                  if ($_POST['cardn_' . $i] != "" && $_POST['amnt_' . $i] != "0" && $_POST['bal_' . $i] != "0" && $_POST['5_' . $i] != "0"){                  
                    $this->db->where('trans_code', $_POST['tcode_'.$i]);
                    $this->db->where("trans_no", $_POST['tno_'.$i]);
                    $this->db->where('card_no', $_POST['cardn_'.$i]);
                    $this->db->where("cl",$_POST['tcl_'.$i]);
                    $this->db->where("bc",$_POST['tbc_'.$i]);
                    $this->db->update("opt_credit_card_det", $update_credit_card);                  
                  }
                }
              }
              $this->account_update(1);
              $this->utility->save_logger("SAVE",58,$this->max_no,$this->mod);
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
          if ($this->user_permissions->is_edit('t_credit_card_reconcil')){
            $account_update = $this->account_update(0);
            if ($account_update == 1) {
              $this->account_update(1);

              $this->db->where("nno", $this->max_no);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->update("t_credit_card_reconcil_sum", $sum_update);  

              if($_POST['is_approve']=="1"){
                $this->db->where("nno", $this->max_no);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->update("t_credit_card_reconcil_sum", array('is_approve' => 1));  
              }

              $this->db->where("no", $this->max_no);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->delete("t_credit_card_reconcil_det");   

              if(isset($det)){
                if (count($det)) {
                  $this->db->insert_batch("t_credit_card_reconcil_det", $det);
                }
              }  

              for ($i = 0; $i < 25; $i++) {
                if(isset($_POST['cardn_'.$i] , $_POST['amnt_'.$i], $_POST['bal_'.$i])){
                  if ($_POST['cardn_' . $i] != "" && $_POST['amnt_' . $i] != "0" && $_POST['bal_' . $i] != "0" ){                
                    $this->db->where('trans_code', $_POST['tcode_'.$i]);
                    $this->db->where("trans_no", $_POST['tno_'.$i]);
                    $this->db->where('card_no', $_POST['cardn_'.$i]);
                    $this->db->where("cl",$_POST['tcl_'.$i]);
                    $this->db->where("bc",$_POST['tbc_'.$i]);
                    $this->db->update("opt_credit_card_det", array("is_reconcil" => "0","reconcil_no" =>'0'));                  
                  }
                }
              }   

              for ($i = 0; $i < 25; $i++) {
                if(isset($_POST['cardn_'.$i] , $_POST['amnt_'.$i], $_POST['bal_'.$i], $_POST['5_'.$i])){
                  if ($_POST['cardn_' . $i] != "" && $_POST['amnt_' . $i] != "0" && $_POST['bal_' . $i] != "0" && $_POST['5_' . $i] != "0"){            
                    $this->db->where('trans_code', $_POST['tcode_'.$i]);
                    $this->db->where("trans_no", $_POST['tno_'.$i]);
                    $this->db->where('card_no', $_POST['cardn_'.$i]);
                    $this->db->where("cl",$_POST['tcl_'.$i]);
                    $this->db->where("bc",$_POST['tbc_'.$i]);
                    $this->db->update("opt_credit_card_det", $update_credit_card);  
                  }
                }
              }
              
              $this->utility->save_logger("EDIT",58,$this->max_no,$this->mod);
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
        echo "Operation fail please contact admin";
      }
    }

    public function account_update($condition){
      $this->db->where("trans_no", $this->max_no);
      $this->db->where("trans_code", 58);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->delete("t_check_double_entry");
      
      if($_POST['hid']=="0"||$_POST['hid']==""){
            
      }else{
        if($condition=="1"){
          $this->db->where('cl', $this->sd['cl']);
          $this->db->where('bc', $this->sd['branch']);
          $this->db->where('trans_code', 58);
          $this->db->where('trans_no', $this->max_no);
          $this->db->delete('t_account_trans');
        }
      }

      $config = array(
        "ddate" => $_POST['date'],
        "trans_code" => 58,
        "trans_no" => $this->max_no,
        "op_acc" => 0,
        "reconcile" => 0,
        "cheque_no" => 0,
        "narration" => "",
        "ref_no" => ""
      );
      
      $this->load->model('account');
      $this->account->set_data($config);

      
      $bank_acc = $_POST['bank_acc'];
      $acc_code = $this->utility->get_default_acc('CREDIT_CARD_IN_HAND');
      $acc_code2 = $this->utility->get_default_acc('CREDIT_CARD_INTEREST');

      $this->account->set_value22($_POST['date'], "Credit card reconcilation", $_POST['chk_bal'], "dr", $bank_acc,$condition);
      $this->account->set_value22($_POST['date'], "Credit card reconcilation", $_POST['chk_bal'], "cr", $acc_code,$condition);

      for ($i = 0; $i < 25; $i++) {
        if(isset($_POST['cardn_'.$i] , $_POST['amnt_'.$i], $_POST['bal_'.$i], $_POST['5_'.$i])){
          if ($_POST['cardn_' . $i] != "" && $_POST['amnt_' . $i] != "0" && $_POST['bal_' . $i] != "0" && $_POST['5_' . $i] != "0"){
            $balance = (float)$_POST['scom_'.$i] - (float)$_POST['acom_'.$i];
            $des = "Commission Difference - ".$_POST['cardn_'.$i];
            if($balance>0){
              $this->account->set_value22($_POST['date_'.$i],$des, $balance, "dr", $acc_code,$condition);
              $this->account->set_value22($_POST['date_'.$i],$des, $balance, "cr", $acc_code2,$condition);
            }
            if($balance<0){
              $this->account->set_value22($_POST['date_'.$i],$des, abs($balance), "cr", $acc_code,$condition);
              $this->account->set_value22($_POST['date_'.$i],$des, abs($balance), "dr", $acc_code2,$condition);
            }
          }
        }
      }
      
      if ($condition == 0) {
        $query = $this->db->query("
             SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
             FROM `t_check_double_entry` t
             LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
             WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='58'  AND t.`trans_no` ='" . $this->max_no . "' AND 
             a.`is_control_acc`='0'");
        
        if ($query->row()->ok == "0") {
          $this->db->where("trans_no", $this->max_no);
          $this->db->where("trans_code", 58);
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
      $x = 0;
      $id=$_POST['id'];
      $cl=$this->sd['cl'];
      $bc=$this->sd['branch'];

      $sql="SELECT d.trans_date,
                   d.trans_code, 
                   d.trans_no, 
                   d.trans_cl, 
                   d.trans_bc,
                   d.credit_card_no,
                   d.actual_amount,
                   d.actual_comm,
                   d.actual_balance,
                   t.description,
                   m.`name`,
                   d.is_reconcil
            FROM t_credit_card_reconcil_det d
            JOIN t_trans_code t ON t.code = d.`trans_code`
            JOIN m_branch m ON m.bc = d.`trans_bc`
            WHERE d.cl ='$cl' AND d.bc='$bc' AND d.no='$id'
            ORDER BY d.auto_no";

      $query = $this->db->query($sql);

      if ($query->num_rows() > 0) {
        $a['det'] = $query->result();
      } else {
        $x = 2;
      }  

      $sql_sum="SELECT s.merchant_id, 
                        b.description AS bank_des, 
                        s.nno, 
                        s.`from_date`, 
                        s.`to_date`, 
                        s.`description`, 
                        c.acc_no,
                        s.tot_amount, 
                        s.`sys_comm_tot`,
                        s.`actual_comm_tot`,
                        s.`tot_balance`, 
                        s.is_cancel,
                        s.bank_acc,
                        a.`description` as bank_name,
                        s.ddate,
                        s.is_approve 
                  FROM t_credit_card_reconcil_sum s
                  JOIN r_credit_card_rate c ON c.`merchant_id` = s.`merchant_id`
                  JOIN m_bank b ON b.code = c.`bank_id`
                  JOIN m_account a ON a.`code` = s.`bank_acc`  
                  WHERE s.cl ='$cl' AND s.bc='$bc' AND s.nno='$id'
                  GROUP BY s.cl,s.bc,s.nno";

      $query2 = $this->db->query($sql_sum);               
      
      if ($query2->num_rows() > 0) {
        $a['sum'] = $query2->result();
      } else {
        $x = 2;
      }  

      if ($x == 0) {
        echo json_encode($a);
      } else {
        echo json_encode($x);
      }       
    }

    public function delete() {   
      $id = $_POST["id"];
      $this->db->trans_begin();
      error_reporting(E_ALL);
      function exceptionThrower($type, $errMsg, $errFile, $errLine){
        throw new Exception($errMsg);
      }
      set_error_handler('exceptionThrower');
      try {  
        if($this->user_permissions->is_delete('t_credit_card_reconcil')){

          $this->db->where("nno", $id);
          $this->db->where("bc", $this->sd['branch']);
          $this->db->where("cl", $this->sd['cl']);
          $this->db->limit(1);
          $this->db->update("t_credit_card_reconcil_sum", array("is_cancel"=>1));

          $this->db->where('cl', $this->sd['cl']);
          $this->db->where('bc', $this->sd['branch']);
          $this->db->where('trans_code', 58);
          $this->db->where('trans_no', $id);
          $this->db->delete('t_account_trans');
          
          $sql="SELECT * 
                FROM t_credit_card_reconcil_det
                WHERE cl='".$this->sd['cl']."' 
                AND bc='".$this->sd['branch']."' 
                AND is_reconcil = '1'
                AND NO='$id'";

          foreach ($this->db->query($sql)->result() as $row) {
            $this->db->where('cl', $row->trans_cl);
            $this->db->where("bc", $row->trans_bc);
            $this->db->where('trans_code', $row->trans_code);
            $this->db->where("trans_no", $row->trans_no);
            $this->db->where('card_no', $row->credit_card_no);
            $this->db->update("opt_credit_card_det", array("is_reconcil"=>"0","reconcil_no"=>"0"));  
          } 
          
          $this->utility->save_logger("CANCEL", 58, $this->max_no, $this->mod);

          echo $this->db->trans_commit();
        }else{
          echo "No permission to delete records";
          $this->db->trans_commit();
        }
      }catch (Exception $e) {
      $this->db->trans_rollback();
      echo "Operation fail please contact admin";
      }      
     
    }



    public function bank_list(){  

      if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
      
      $sql = "SELECT r.merchant_id,
                      r.bank_id,
                      b.description,
                      r.acc_no  
              FROM r_credit_card_rate r
              JOIN m_bank b ON b.code = r.bank_id
              WHERE r.merchant_id LIKE '%$_POST[search]%' OR b.description LIKE '%$_POST[search]%'
              group by r.merchant_id,r.bank_id
              LIMIT 25";

      $query = $this->db->query($sql);

      $a  = "<table id='item_list' style='width : 100%' >";
      $a .= "<thead><tr>";
      $a .= "<th class='tb_head_th'>Merchant ID</th>";
      $a .= "<th class='tb_head_th'>Bank ID</th>";
      $a .= "<th class='tb_head_th' colspan='2'>Name</th>";
      $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

      foreach($query->result() as $r){
        $a .= "<tr class='cl'>";
        $a .= "<td>".$r->merchant_id."</td>";
        $a .= "<td>".$r->bank_id."</td>";
        $a .= "<td>".$r->description."</td>";
        $a .= "<td style='display:none;'>".$r->acc_no."</td>";
        $a .= "</tr>";
      }
      $a .= "</table>";
      echo $a;
  }


  public function bank_acc_list(){  

      if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
      
      $sql = "SELECT code,
                     description 
              FROM m_account 
              WHERE is_bank_acc='1'
              AND (code LIKE '%$_POST[search]%' OR description LIKE '%$_POST[search]%')
              LIMIT 25";

      $query = $this->db->query($sql);

      $a  = "<table id='item_list' style='width : 100%' >";
      $a .= "<thead><tr>";
      $a .= "<th class='tb_head_th'>Code</th>";
      $a .= "<th class='tb_head_th' colspan='2'>Name</th>";
      $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

      foreach($query->result() as $r){
        $a .= "<tr class='cl'>";
        $a .= "<td>".$r->code."</td>";
        $a .= "<td>".$r->description."</td>";
        $a .= "</tr>";
      }
      $a .= "</table>";
      echo $a;
  }


  public function load_credit_cards(){


    $sql="SELECT c.`date`, 
                  t.description, 
                  c.`trans_code`, 
                  c.`trans_no`,
                  b.`name`,
                  c.`bc`,
                  c.`cl`,
                  c.`card_no`,
                  c.`amount`,
                  c.`int_amount`AS commission
          FROM opt_credit_card_det c
          JOIN t_trans_code t ON t.code = c.`trans_code`
          JOIN m_branch b ON b.`bc` = c.`bc`
          WHERE DATE BETWEEN '".$_POST['from_date']."' AND '".$_POST['to_date']."' 
          AND is_reconcil ='0'
          AND merchant_id ='".$_POST['merchant']."'
          ORDER BY c.date,c.auto_no";
    
  
    $query = $this->db->query($sql);

    if($query->num_rows() > 0){
      $a=$query->result();
    }else{
      $a=2;
    }

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
    
    $invoice_number      = $this->utility->invoice_format($_POST['qno']);
    $session_array       = array(
      $this->sd['cl'],
      $this->sd['branch'],
      $invoice_number
    );

    
    $cl = $this->sd['cl'];
    $bc = $this->sd['branch'];
    $id = $_POST['qno'];

    $r_detail['session'] = $session_array;
    $r_detail['page']        = $_POST['page'];
    $r_detail['header']      = $_POST['header'];
    $r_detail['orientation'] = $_POST['orientation'];      
       
    $sql="SELECT s.nno, 
                s.ddate, 
                s.merchant_id, 
                s.description, 
                s.bank_acc, 
                a.`description` as bank_des 
          FROM `t_credit_card_reconcil_sum` s
          JOIN m_account a ON a.`code` = s.`bank_acc`
          WHERE cl='".$this->sd['cl']."' 
          AND bc='".$this->sd['branch']."' 
          AND nno='$id'";

    $sum = $this->db->query($sql);            
    $r_detail['sum'] = $sum->result(); 

    $sql_det="SELECT t.description, 
                    d.no, 
                    d.`credit_card_no`, 
                    d.`actual_amount` AS amount,
                    d.`actual_comm` AS commision, 
                    d.`actual_balance` AS balance  
              FROM `t_credit_card_reconcil_det` d
              JOIN t_trans_code t ON d.trans_code = t.code
              WHERE cl='".$this->sd['cl']."' 
              AND bc='".$this->sd['branch']."' 
              AND is_reconcil='1' 
              AND NO='$id'";

    $det = $this->db->query($sql_det);            
    $r_detail['det'] = $det->result();     

    $this->db->select(array('t_credit_card_reconcil_sum.oc', 's_users.discription','action_date'));
    $this->db->from('t_credit_card_reconcil_sum');
    $this->db->join('s_users', 't_credit_card_reconcil_sum.oc=s_users.cCode');
    $this->db->where('t_credit_card_reconcil_sum.cl', $this->sd['cl']);
    $this->db->where('t_credit_card_reconcil_sum.bc', $this->sd['branch']);
    $this->db->where('t_credit_card_reconcil_sum.nno', $_POST['qno']);
    $r_detail['user'] = $this->db->get()->result();      
    
    if($sum->num_rows()>0){            
        $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }else{
        echo "<script>alert('No data found');close();</script>";
    }
                
  }

}           


