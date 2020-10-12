<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class chq_print extends CI_Model
{
  
  private $sd;
  private $mtb;
  
  private $mod = '003';
  
  function __construct(){
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');
    $this->max_no = $this->utility->get_max_no("chq_printed", "nno");
  }
  
  public function base_details()
  {
    $a['max_no'] = $this->utility->get_max_no("chq_printed", "nno");
    return $a;
  }
  
  
  public function validation(){
    $status         = 1;    
    return $status;
  }
  
    
  public function save(){
    $this->db->trans_begin();
    error_reporting(E_ALL);
    
    function exceptionThrower($type, $errMsg, $errFile, $errLine)
    {
      throw new Exception($errLine);
    }
    
    set_error_handler('exceptionThrower');
    try {
      
      $validation_status = $this->validation();
      if ($validation_status == 1) {
        if(isset($_POST['bank_date_id'])){
          $bdate=1;
        }else{
          $bdate=0;
        }
        if(isset($_POST['cross_cheq_id'])){
          $cross=1;
        }else{
          $cross=0;
        }
        if(isset($_POST['cash_cheque_id'])){
          $cash=1;
        }else{
          $cash=0;
        }

        $sum = array(
          "cl"            => $this->sd['cl'],
          "bc"            => $this->sd['branch'],
          "nno"           => $this->max_no,
          "ddate"         => $_POST['date'],
          "voucher_no"    => $_POST['voucher_id'],
          "acc_no"        => $_POST['acc_No'],
          "acc_name"      => $_POST['acc_No_des'],
          "chq_no"        => $_POST['cheque_id'],
          "is_cross_cheq" => $cross,
          "cross_word"    => $_POST['cross_word'],
          "is_omit_bearer"=> "",
          "is_bank_date"  => $bdate,
          "bank_date"     => $_POST['bank_date'],
          "payee_id"      => $_POST['Payee_No'],
          "payee_name"    => $_POST['Payee_No_des'],
          "description"   => $_POST['description'],
          "amount"        => $_POST['amount'],
          "is_cash_cheque"=> $cash          
        );

        $update = array(
          "is_chq_print"  => 1
        );
        
                
        if($_POST['hid'] == "0" || $_POST['hid'] == "") {          
          if($this->user_permissions->is_add('chq_print')) {            
            $this->db->insert('chq_printed', $sum); 

            // $this->db->where("trans_code", 19);
            $this->db->where("trans_no", $_POST['voucher_id']);
            $this->db->where("cheque_no", $_POST['cheque_id']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->update('opt_issue_cheque_det', $update);

            $this->utility->save_logger("SAVE",57,$this->max_no,$this->mod);
            echo $this->db->trans_commit();            
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          }
        }else{
          if($this->user_permissions->is_edit('chq_print')) {
              $this->db->where("nno", $this->max_no);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->update('chq_printed', $sum);

              // $this->db->where("trans_code", 19);
              $this->db->where("trans_no", $_POST['voucher_id']);
              $this->db->where("cheque_no", $_POST['cheque_id']);
              $this->db->where("cl", $this->sd['cl']);
              $this->db->where("bc", $this->sd['branch']);
              $this->db->update('opt_issue_cheque_det', $update);
              $this->utility->save_logger("EDIT",57,$this->max_no,$this->mod);
              echo $this->db->trans_commit();
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          }
        }
      }else{
        echo $validation_status;
        $this->db->trans_commit();
      }
    }
    catch (Exception $e) {
      $this->db->trans_rollback();
      echo $e->getMessage()."Operation fail please contact admin";
    }    
  }
  
  
  
  public function delete(){
    
    $this->db->trans_begin();
    error_reporting(E_ALL);
    
    function exceptionThrower($type, $errMsg, $errFile, $errLine)
    {
      throw new Exception($errMsg);
    }
    
    set_error_handler('exceptionThrower');
    try {
      if($this->user_permissions->is_delete('chq_print')) {
        $this->db->where("nno", $_POST['hid']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->limit(1);
        $this->db->update("chq_printed", array(
          "is_cancel" => 1
        ));        
        $this->utility->save_logger("CANCEL", 57, $_POST['hid'], $this->mod);
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
  
  public function load_voucher(){
    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];
    $no = $_POST['id'];
    $chq_no = $_POST['chq_no'];
    $sql = "SELECT  v.nno,
                    v.ddate,
                    c.amount AS `payment`,
                    c.cheque_date,
                    c.cheque_no,
                    c.bank,
                    c.description,
                    v.acc_code,
                    s.payee_name AS name,
                    c.trans_code
            FROM t_voucher_sum v
            JOIN (SELECT * FROM `opt_issue_cheque_det` ic 
                  WHERE cl='$cl' AND bc='$bc' AND `trans_no`='$no' AND cheque_no ='$chq_no' ) c 
            ON c.cl = v.cl 
            AND c.bc = v.`bc` 
            AND c.trans_no = v.`nno`
            JOIN m_supplier s ON s.code = v.`acc_code`  
            WHERE c.`is_chq_print` ='0' 
              AND v.cl='$cl'
              AND v.bc='$bc'
              AND v.nno = '$no'";

    $query  = $this->db->query($sql);

    if($query->num_rows()>0){
      $a = $query->result();
    }else{
      $a = "2";
    }

    echo json_encode($a);

  } 

  public function load_transfer_voucher(){
    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];
    $no = $_POST['id'];
    $chq_no = $_POST['chq_no'];
    $sql = "SELECT  v.nno,
                    v.ddate,
                    v.`payment`,
                    c.cheque_date,
                    c.cheque_no,
                    c.bank,
                    c.description,
                    v.acc_code,
                    s.description AS name,
                    c.trans_code
            FROM t_internal_trans_voucher_sum v
            JOIN (SELECT * FROM `opt_issue_cheque_det` ic 
                  WHERE cl='$cl' AND bc='$bc' AND `trans_no`='$no' AND cheque_no ='$chq_no' ) c 
            ON c.cl = v.cl 
            AND c.bc = v.`bc` 
            AND c.trans_no = v.`nno`
            JOIN m_account s ON s.code = v.`acc_code`  
            WHERE c.`is_chq_print` ='0' 
              AND v.cl='$cl'
              AND v.bc='$bc'
              AND v.nno = '$no'";

    $query  = $this->db->query($sql);

    if($query->num_rows()>0){
      $a = $query->result();
    }else{
      $a = "2";
    }

    echo json_encode($a);

  } 

   public function load_genaral_voucher(){
    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];
    $no = $_POST['id'];
    $chq_no = $_POST['chq_no'];
    /*$sql = "SELECT  v.nno,
                    v.ddate,
                    v.`payment`,
                    c.cheque_date,
                    c.cheque_no,
                    c.bank,
                    c.description,
                    v.acc_code,
                    s.name,
                    c.trans_code
            FROM t_voucher_sum v
            JOIN (SELECT * FROM `opt_issue_cheque_det` ic 
                  WHERE cl='$cl' AND bc='$bc' AND `trans_no`='$no' AND cheque_no ='$chq_no' ) c 
            ON c.cl = v.cl 
            AND c.bc = v.`bc` 
            AND c.trans_no = v.`nno`
            JOIN m_supplier s ON s.code = v.`acc_code`  
            WHERE c.`is_chq_print` ='0' 
              AND v.cl='$cl'
              AND v.bc='$bc'
              AND v.nno = '$no'";
*/
    $sql="SELECT v.nno ,
                v.ddate,
                c.amount AS`payment`,
                c.cheque_date,
                c.cheque_no,
                c.bank,
                c.description,
                c.trans_code 
          FROM t_voucher_gl_sum v 
          JOIN (SELECT * FROM `opt_issue_cheque_det` ic 
                    WHERE cl = '$cl' AND bc = '$bc' AND `trans_no` = '$no' AND cheque_no = '$chq_no') c 
                  ON c.cl = v.cl 
                  AND c.bc = v.`bc` 
                  AND c.trans_no = v.`nno` 
          WHERE c.`is_chq_print` = '0' 
          AND v.cl = '$cl' 
          AND v.bc = '$bc' 
          AND v.nno = '$no'";

    $query  = $this->db->query($sql);

    if($query->num_rows()>0){
      $a = $query->result();
    }else{
      $a = "2";
    }

    echo json_encode($a);

  } 
 
  
  public function get_data(){    
    $sql  = "SELECT * FROM chq_printed 
         WHERE cl ='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['no']."' ";
    
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
      $a = $query->result();
    }else{
      $a = '2';
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
    $r_detail['session'] = $session_array;
    
    $sql_amount="SELECT amount FROM chq_printed WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['qno']."'";
    $c_amount = $this->db->query($sql_amount)->row()->amount;

    $r_detail['num'] = $c_amount;

    var_dump($c_amount);
    //exit();
    $num = $c_amount;
    
    function convertNum($num){
      $safeNum = $num;
      $num     = (int) $num; // make sure it's an integer
      
      if ($num < 0)
        return "negative" . convertTri(-$num, 0);
      if ($num == 0)
        return "zero";
      
      $pos         = strpos($safeNum, '.');
      $len         = strlen($safeNum);
      $decimalPart = substr($safeNum, $pos + 1, $len - ($pos + 1));
      
      if ($pos > 0) {
        if($decimalPart=="00"){
          return convertTri($num, 0) ." Only ";
        }else{
          return convertTri($num, 0) . " and Cents" . convertTri($decimalPart, 0) ." Only ";
        }
      } else {
        return convertTri($num, 0);
      }
    }
    
    function convertTri($num, $tri)
    {
      $ones     = array(
        "",
        " One",
        " Two",
        " Three",
        " Four",
        " Five",
        " Six",
        " Seven",
        " Eight",
        " Nine",
        " Ten",
        " Eleven",
        " Twelve",
        " Thirteen",
        " Fourteen",
        " Fifteen",
        " Sixteen",
        " Seventeen",
        " Eighteen",
        " Nineteen"
      );
      $tens     = array(
        "",
        "",
        " Twenty",
        " Thirty",
        " Forty",
        " Fifty",
        " Sixty",
        " Seventy",
        " Eighty",
        " Ninety"
      );
      $triplets = array(
        "",
        " Thousand",
        " Million",
        " Billion",
        " Trillion",
        " Quadrillion",
        " Quintillion",
        " Sextillion",
        " Septillion",
        " Octillion",
        " Nonillion"
      );
      
      // chunk the number, ...rxyy
      $r = (int) ($num / 1000);
      $x = ($num / 100) % 10;
      $y = $num % 100;
      
      // init the output string
      $str = "";
      
      // do hundreds      
      if ($x > 0)
        $str = $ones[$x] . " Hundred";
      
      // do ones and tens
      if ($y < 20)
        $str .= $ones[$y];
      else
        $str .= $tens[(int) ($y / 10)] . $ones[$y % 10];
      
      // add triplet modifier only if there
      // is some output to be modified...
      if ($str != "")
        $str .= $triplets[$tri];
      
      // continue recursing?
      if ($r > 0)
        return convertTri($r, $tri + 1) . $str;
      else
        return $str;
    }
    
    
    $r_detail['rec'] = convertNum($num);
    /*var_dump(convertNum($num));
    exit();*/
    ;
    
    $r_detail['page']        = $_POST['page'];
    $r_detail['header']      = $_POST['header'];
    $r_detail['orientation'] = $_POST['orientation'];
       
    
    $sql="SELECT * 
          FROM chq_account_setup st
          JOIN chq_print_scheme cs ON cs.`code` = st.`scheme_code`
          WHERE st.`code`='".$_POST['acc_code']."' ";

    $r_detail['chq'] = $this->db->query($sql)->result(); 

    $sql="SELECT * 
          FROM chq_printed cp
          WHERE cp.`nno`='".$_POST['qno']."' AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";
    
    $r_detail['print'] = $this->db->query($sql)->result();

    $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
  }
}