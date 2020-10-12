<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
class t_cheque_issue_trans extends CI_Model {

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
    $this->max_no = $this->utility->get_max_no("t_cheque_withdraw_sum", "nno");
    
  }

  public function base_details() {
    $this->load->model('m_option_setup');        
    $a['id'] = $this->utility->get_max_no("t_cheque_withdraw_sum", "nno"); 
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
          $this->max_no = $this->utility->get_max_no("t_cheque_withdraw_sum", "nno");
          $sum = array(
            "cl" => $this->sd['cl'],
            "bc" => $this->sd['branch'],
            "nno" => $this->max_no,
            "ddate" => $_POST['date'],
            "bank_id" => $_POST['banck_acc'],
            "oc" => $this->sd['oc'],
            "balance" => $_POST['tot_balance'],
            "total" => $_POST['total'],
            "settle" => $_POST['tot_settle'],             
            );

          $sum_update = array(
            "cl" => $this->sd['cl'],
            "bc" => $this->sd['branch'],         
            "ddate" => $_POST['date'],
            "bank_id" => $_POST['banck_acc'],
            "oc" => $this->sd['oc'],
            "balance" => $_POST['tot_balance'],
            "total" => $_POST['total'],
            "settle" => $_POST['tot_settle'],             
            );
          
          for ($i = 0; $i < 50; $i++) {
            if(isset($_POST['5_'.$i] , $_POST['0_'.$i], $_POST['4_'.$i])){
              if ($_POST['0_' . $i] != "" && $_POST['0_' . $i] != "0") {   
                $det[] = array(
                  "bc" => $this->sd['branch'],
                  "cl" => $this->sd['cl'],
                  "nno" => $this->max_no,
                  "account_no" => $_POST['0_'.$i],
                  "to_acc_code" => $_POST['1_'.$i],
                  "cheque_no" => $_POST['2_' . $i],
                  "account_no" => $_POST['0_'.$i],
                  "amount" => $_POST['4_'.$i],  
                  "bank_date" => $_POST['3_'.$i],            
                  );
              }
            }
          }

          $update_cheque_received=array(
           "status" => "D"
           );
          
          


          if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
            if ($this->user_permissions->is_add('t_cheque_issue_trans')) {
              $account_update = $this->account_update(0);
              if ($account_update == 1) {

                $this->db->insert("t_cheque_withdraw_sum", $sum);

                if(isset($det)){
                  if (count($det)) {
                    $this->db->insert_batch("t_cheque_withdraw_det", $det);
                  }
                }

                for ($i = 0; $i < 50; $i++) {
                  if(isset($_POST['5_'.$i] , $_POST['0_'.$i], $_POST['4_'.$i])){
                    if ($_POST['0_' . $i] != "" && $_POST['0_' . $i] != "0") {                   
                      $this->db->where('trans_code', $_POST['t_code_'.$i]);
                      $this->db->where("trans_no", $_POST['t_no_'.$i]);
                      $this->db->where('account', $_POST['0_'.$i]);
                      $this->db->where("cheque_no", $_POST['2_'.$i]);
                      $this->db->where("cl", $this->sd['cl']);
                      $this->db->where("bc", $this->sd['branch']);
                      $this->db->update("t_cheque_issued", $update_cheque_received);                  
                    }
                  }
                }
                $this->account_update(1);
                $this->utility->save_logger("SAVE",54,$this->max_no,$this->mod);
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
            if ($this->user_permissions->is_edit('t_cheque_issue_trans')){
              $account_update = $this->account_update(0);
              if ($account_update == 1) {
                $this->db->where("nno", $this->max_no);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->update("t_cheque_withdraw_sum", $sum_update);  

                $this->db->where("nno", $this->max_no);
                $this->db->where("cl", $this->sd['cl']);
                $this->db->where("bc", $this->sd['branch']);
                $this->db->delete("t_cheque_withdraw_det");   

                if(isset($det)){
                  if (count($det)) {
                    $this->db->insert_batch("t_cheque_withdraw_det", $det);
                  }
                }  

                for ($i = 0; $i < 50; $i++) {
                  if(isset($_POST['0_'.$i], $_POST['4_'.$i])){
                    if ($_POST['0_' . $i] != "" && $_POST['0_' . $i] != "0") {                   
                      $this->db->where('trans_code', $_POST['t_code_'.$i]);
                      $this->db->where("trans_no", $_POST['t_no_'.$i]);
                      $this->db->where('account', $_POST['0_'.$i]);
                      $this->db->where("cheque_no", $_POST['2_'.$i]);
                      $this->db->where("cl", $this->sd['cl']);
                      $this->db->where("bc", $this->sd['branch']);
                      $this->db->update("t_cheque_issued", array("status"=>"P"));                  
                    }
                  }
                }   

                for ($i = 0; $i < 50; $i++) {
                  if(isset($_POST['5_'.$i] , $_POST['0_'.$i], $_POST['4_'.$i])){
                    if ($_POST['0_' . $i] != "" && $_POST['0_' . $i] != "0") {                   
                      $this->db->where('trans_code', $_POST['t_code_'.$i]);
                      $this->db->where("trans_no", $_POST['t_no_'.$i]);
                      $this->db->where('account', $_POST['0_'.$i]);
                      $this->db->where("cheque_no", $_POST['2_'.$i]);
                      $this->db->where("cl", $this->sd['cl']);
                      $this->db->where("bc", $this->sd['branch']);
                      $this->db->update("t_cheque_issued", $update_cheque_received);                  
                    }
                  }
                } 
                $this->account_update(1);
                $this->utility->save_logger("EDIT",54,$this->max_no,$this->mod);
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
      $this->db->where("trans_code", 54);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->delete("t_check_double_entry");
      
      if($_POST['hid']=="0"||$_POST['hid']==""){

      }else{
        if($condition=="1"){
          $this->db->where('cl', $this->sd['cl']);
          $this->db->where('bc', $this->sd['branch']);
          $this->db->where('trans_code', 54);
          $this->db->where('trans_no', $this->max_no);
          $this->db->delete('t_account_trans');
        }
      }
      
      $config = array(
        "ddate" => $_POST['date'],
        "trans_code" => 54,
        "trans_no" => $this->max_no,
        "op_acc" => 0,
        "reconcile" => 0,
        "cheque_no" => 0,
        "narration" => "",
        "ref_no" => ""
        );
      
      
      $this->load->model('account');
      $this->account->set_data($config);

      
      for ($i = 0; $i < 50; $i++) {
        if(isset($_POST['5_'.$i] , $_POST['0_'.$i] )){
          if ($_POST['0_' . $i] != "" && $_POST['0_' . $i] != "0") {

            $des2 = "CHEQUE WITHDRAW - " . $_POST['0_' . $i];
            $des = "CHEQUE WITHDRAW - " . $_POST['2_' . $i];

            $cheque_no = $_POST['2_' . $i];
            $op_acc = $_POST['0_' . $i];
            
            $this->account->set_value2("CHEQUE NO - ".$_POST['2_' . $i]." VOUCHER NO - ".$_POST['t_no_' . $i] , $_POST['4_' . $i], "cr", $_POST['banck_acc'],$condition,$cheque_no);
            $acc_code = $this->utility->get_default_acc('ISSUE_CHEQUES');
            $this->account->set_value3($op_acc, $cheque_no, $des, $_POST['4_' . $i], "dr", $acc_code, $condition);
            //$this->account->set_value3($acc_code, $cheque_no, $des2, $_POST['4_' . $i], "cr", $op_acc, $condition);
          }
        }
      }
      
      if ($condition == 0) {
        $query = $this->db->query("
         SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
         FROM `t_check_double_entry` t
         LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
         WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='54'  AND t.`trans_no` ='" . $this->max_no . "' AND 
         a.`is_control_acc`='0'");
        
        if ($query->row()->ok == "0") {
          $this->db->where("trans_no", $this->max_no);
          $this->db->where("trans_code", 54);
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

      $sql="SELECT cd.account_no AS account,
      cd.cheque_no AS cheque,
      cd.bank_date AS bank_date,
      cd.amount,
      cr.`trans_code`,
      cr.`trans_no`,
      m.`description` AS acc_name,
      mm.`description` AS to_acc_name,
      cd.`to_acc_code` AS to_acc_code   
      FROM t_cheque_withdraw_det cd 
      JOIN m_account m ON m.`code` = cd.`account_no`
      JOIN m_account mm ON mm.`code` = cd.`to_acc_code`
      LEFT JOIN t_cheque_issued cr ON cr.`cl` = cd.cl AND cr.`bc` = cd.bc AND cr.`account` = cd.`account_no` AND cr.`cheque_no` = cd.cheque_no 
      WHERE cd.cl ='$cl' AND cd.bc='$bc' AND cd.nno='$id'";

      $query = $this->db->query($sql);

      if ($query->num_rows() > 0) {
        $a['det'] = $query->result();
      } else {
        $x = 2;
      }  

      $sql_sum="SELECT c.nno,c.ddate,c.bank_id,a.`description`,c.`is_cancel`, c.balance, c.total,c.settle 
      FROM t_cheque_withdraw_sum c 
      LEFT JOIN m_account a ON a.`code` = c.`bank_id` 
      WHERE c.cl = '$cl' AND c.bc='$bc' AND c.nno='$id'";

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
        if($this->user_permissions->is_delete('t_cheque_issue_trans')){

          $this->db->where("nno", $id);
          $this->db->where("bc", $this->sd['branch']);
          $this->db->where("cl", $this->sd['cl']);
          $this->db->limit(1);
          $this->db->update("t_cheque_withdraw_sum", array("is_cancel"=>1));

          $this->db->where('cl', $this->sd['cl']);
          $this->db->where('bc', $this->sd['branch']);
          $this->db->where('trans_code', 54);
          $this->db->where('trans_no', $id);
          $this->db->delete('t_account_trans');
          
          $sql="SELECT cd.account_no AS account,
          cd.cheque_no AS cheque,
          cd.bank_date AS bank_date,
          cd.amount,
          cr.`trans_code`,
          cr.`trans_no`,
          m.`description` AS acc_name,
          mm.`description` AS to_acc_name,
          cd.`to_acc_code` AS to_acc_code   
          FROM t_cheque_withdraw_det cd 
          JOIN m_account m ON m.`code` = cd.`account_no`
          JOIN m_account mm ON mm.`code` = cd.`to_acc_code`
          JOIN t_cheque_issued cr ON cr.`cl` = cd.cl AND cr.`bc` = cd.bc AND cr.`account` = cd.`account_no` AND cr.`cheque_no` = cd.cheque_no 
          WHERE cd.cl ='".$this->sd['cl']."' AND cd.bc='".$this->sd['branch']."' AND cd.nno='$id'";

          foreach ($this->db->query($sql)->result() as $row) {
            $this->db->where('trans_code', $row->trans_code);
            $this->db->where("trans_no", $row->trans_no);
            $this->db->where('account', $row->account);
            $this->db->where("cheque_no", $row->cheque);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->update("t_cheque_issued", array("status"=>"P"));  
          } 
          
          $this->utility->save_logger("CANCEL", 54, $this->max_no, $this->mod);

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



    public function acc_list(){  

      if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
      
      $sql = "SELECT * FROM m_account  WHERE is_bank_acc ='1' AND (code LIKE '%$_POST[search]%' OR description LIKE '%$_POST[search]%') LIMIT 50";
      
      $query = $this->db->query($sql);

      $a  = "<table id='item_list' style='width : 100%' >";
      $a .= "<thead><tr>";
      $a .= "<th class='tb_head_th'>Code</th>";
      $a .= "<th class='tb_head_th' colspan='2'>Descripiton</th>";
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


    public function load_cheques(){

    /*$sql="SELECT t.trans_code, t.trans_no, t.`account`, t.bank, t.branch,t.`cheque_no`, t.`bank_date`,t.`amount`,b.description AS bank_name, h.`description` AS branch_name
          FROM t_cheque_received t
          JOIN m_bank b ON b.code=t.`bank`
          LEFT JOIN m_bank_branch h ON h.code = t.`branch`
          WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND bank_date <= '".$_POST['date']."' AND (STATUS='P' OR STATUS='R')";
    */
          if($_POST['chq']!=""){
            $chq = " AND t.`cheque_no` ='".$_POST['chq']."' ";
          }else{
            $chq = "";
          }

          $sql="SELECT  t.trans_code,
          t.trans_no,
          t.`account`,
          t.`cheque_no`,
          t.`bank_date`,
          t.`amount`,
          b.description AS acc_name,
          v.acc_code AS to_acc_code,
          bb.description AS to_acc_name
          FROM t_cheque_issued t 
          JOIN m_account b ON b.code = t.`bank`
          LEFT JOIN (SELECT cl, bc, nno, 19 AS trans_code, acc_code 
          FROM  t_voucher_sum 
          WHERE cl = '".$this->sd['cl']."' 
          AND bc = '".$this->sd['branch']."' 
          UNION ALL 
          SELECT cl, bc, nno, 131 AS trans_code, acc_code 
          FROM  t_internal_trans_voucher_sum 
          WHERE cl = '".$this->sd['cl']."' 
          AND bc = '".$this->sd['branch']."' 
          UNION ALL 
          SELECT s.cl, s.bc, s.nno, 48 AS trans_code, d.acc_code 
          FROM t_voucher_gl_sum s
          INNER JOIN 
          (SELECT cl, bc, nno,sub_no, acc_code FROM t_voucher_gl_det  
          WHERE cl = '".$this->sd['cl']."' 
          AND bc = '".$this->sd['branch']."'  GROUP BY cl, bc, nno, acc_code) d 
          ON (s.cl=d.cl) AND (s.bc=d.bc) AND (s.nno=d.nno)) v  
          ON (t.trans_code=v.trans_code) AND (t.trans_no=v.nno) AND (t.cl=v.cl) AND (t.bc=v.bc)
          left JOIN m_account bb ON bb.`code`=v.acc_code 
          WHERE t.cl = '".$this->sd['cl']."' 
          AND t.bc = '".$this->sd['branch']."' 
          AND t.bank_date <= '".$_POST['date']."' 
          AND (t.STATUS = 'P' OR t.STATUS = 'R') $chq";
          
          if($_POST['acc']!=""){
            $sql .= "AND t.account ='".$_POST['acc']."'";
          }       
          $sql.=" GROUP BY t.cl, t.bc, t.trans_no, t.`cheque_no` " ;  
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
         
         $sql="SELECT c.nno,c.ddate,c.bank_id,a.`description`,c.`is_cancel`, c.balance, c.total,c.settle 
         FROM t_cheque_withdraw_sum c 
         LEFT JOIN m_account a ON a.`code` = c.`bank_id` 
         WHERE c.cl = '$cl' AND c.bc='$bc' AND c.nno='$id'";

         $sum = $this->db->query($sql);            
         $r_detail['sum'] = $sum->result(); 


         $sql_det="SELECT cd.account_no AS account,
         cd.cheque_no AS cheque,
         cd.bank_date AS bank_date,
         cd.amount,
         cr.`trans_code`,
         cr.`trans_no`,
         m.`description` AS acc_name,
         mm.`description` AS to_acc_name,
         cd.`to_acc_code` AS to_acc_code   
         FROM t_cheque_withdraw_det cd 
         JOIN m_account m ON m.`code` = cd.`account_no`
         JOIN m_account mm ON mm.`code` = cd.`to_acc_code`
         JOIN t_cheque_issued cr ON cr.`cl` = cd.cl AND cr.`bc` = cd.bc AND cr.`account` = cd.`account_no` AND cr.`cheque_no` = cd.cheque_no 
         WHERE cd.cl ='$cl' AND cd.bc='$bc' AND cd.nno='$id'";

         $det = $this->db->query($sql_det);            
         $r_detail['det'] = $det->result();         
         
         $this->db->select(array('t_cheque_withdraw_sum.oc', 's_users.discription','action_date'));
         $this->db->from('t_cheque_withdraw_sum');
         $this->db->join('s_users', 't_cheque_withdraw_sum.oc=s_users.cCode');
         $this->db->where('t_cheque_withdraw_sum.cl', $this->sd['cl']);
         $this->db->where('t_cheque_withdraw_sum.bc', $this->sd['branch']);
         $this->db->where('t_cheque_withdraw_sum.nno', $id);
         $r_detail['user'] = $this->db->get()->result();    

         if($sum->num_rows()>0){            
          $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
        }else{
          echo "<script>alert('No data found');close();</script>";
        }
        
      }

    }           


