<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_cus_settlement extends CI_Model {

  private $sd;
  private $mtb;
  private $max_no;
  private $mod = '003';

  function __construct(){
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->mtb = $this->tables->tb['t_cus_settlement'];
    $this->load->model('user_permissions');
  }

  public function base_details(){
    $this->load->model("utility");
    $a['max_no']=$this->utility->get_max_no("t_customer_settlement_sum","nno");
    return $a;
  }

  public function validation(){
    $status=1;
    
    $this->load->model("utility");
    $this->max_no=$this->utility->get_max_no("t_customer_settlement_sum","nno");
    
    $total_cr=0;
    $total_dr=0;
    if(isset($_POST['ttl_cr']) && !empty($_POST['ttl_cr'])){
      $total_cr=(float)$_POST['ttl_cr'];
    }

    if(isset($_POST['ttl_dr']) && !empty($_POST['ttl_dr'])){
      $total_dr=(float)$_POST['ttl_dr'];
    }  

    if(($total_cr==0 && $total_dr==0) || $total_dr!=$total_cr){
      return "Please check the CR Amount with DR Amount";
    }   
    $check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_customer_settlement_sum');
    if($check_is_delete!=1){
      return "Customer settlement already deleted";
    } 
    $customer_validation = $this->validation->check_is_customer($_POST['customer']);
    if ($customer_validation != 1) {
      return "Please enter valid customer";
    }
    $check_valid_cr_no=$this->validation->check_valid_trans_no('customer','hh_','nn_');
    if($check_valid_cr_no!=1){
      return $check_valid_cr_no;
    }
    $check_valid_dr_no=$this->validation->check_valid_trans_no('customer','h_','n_');
    if($check_valid_dr_no!=1){
      return $check_valid_dr_no;
    }
    $check_valid_trans_settle_status=$this->validation->check_valid_trans_settle('customer','hh_','nn_','44_');
    if($check_valid_trans_settle_status!=1){
      return $check_valid_trans_settle_status;
    }
    $check_valid_trans_settle_status2=$this->validation->check_valid_trans_settle('customer','h_','n_','4_');
    if($check_valid_trans_settle_status2!=1){
      return $check_valid_trans_settle_status2;
    }             
    return $status;
  }


  public function save(){

    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
      throw new Exception($errMsg."==".$errFile."==".$errLine); 
    }
    set_error_handler('exceptionThrower'); 
    try {

      $this->load->model("utility");

      $validation_status=$this->validation();

      if($validation_status==1){

        $t_customer_settlement_sum=array(
         "ddate"=>$_POST['date'],
         "nno"=> $this->max_no,
         "customer"=>$_POST['customer'],
         "is_cancel"=>0,
         "amount"=>$_POST['ttl_dr'],
         "cl"=>$this->sd['cl'],
         "bc"=>$this->sd['branch'],
         "oc"=>$this->sd['oc']
       );

        $t_customer_settlement_dr_temp=array(
         "nno"=>$this->max_no,
         "type"=>$_POST['h_0'],
         "trans_no"=>$_POST['n_0'],
         "date"=>$_POST['1_0'],
         "amount"=>$_POST['2_0'],
         "balance"=>$_POST['3_0'],
         "settle"=>$_POST['4_0'],
         "description"=>$_POST['5_0'],
         "cl"=>$this->sd['cl'],
         "bc"=>$this->sd['branch'],
         "oc"=>$this->sd['oc']
       );

        for($x = 0; $x<300; $x++){
          if(isset($_POST['44_'.$x])){
            if($_POST['44_'.$x] != ""){

              $t_customer_settlement_cr_temp[]=array(
               "nno"=>$this->max_no,
               "type"=>$_POST['hh_'.$x],
               "trans_no"=>$_POST['nn_'.$x],
               "date"=>$_POST['11_'.$x],
               "amount"=>$_POST['22_'.$x],
               "balance"=>$_POST['33_'.$x],
               "settle"=>$_POST['44_'.$x],
               "description"=>$_POST['55_'.$x],
               "cl"=>$this->sd['cl'],
               "bc"=>$this->sd['branch'],
               "oc"=>$this->sd['oc']
             );
            }
          }
        }

        
        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('t_cus_settlement')){
            $this->db->insert('t_customer_settlement_sum',  $t_customer_settlement_sum);
            $this->db->insert('t_customer_settlement_dr_temp',  $t_customer_settlement_dr_temp);
            
            if(count($t_customer_settlement_cr_temp)){ $this->db->insert_batch("t_customer_settlement_cr_temp",$t_customer_settlement_cr_temp);}
            $this->load->model('trans_settlement');

            if($_POST['h_0']=="17"){
              $this->trans_settlement->save_settlement("t_credit_note_trans",$_POST['customer'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],21,$_POST['id'],"0",$_POST['4_0']);
              $this->utility->update_credit_note_balance($_POST['customer']);
            }else if($_POST['h_0']=="16"){
              $this->trans_settlement->save_settlement("t_cus_settlement",$_POST['customer'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],21,$_POST['id'],"0",$_POST['4_0']);  
              $this->utility->update_receipt_op_balance($_POST['customer']);
            }else if($_POST['h_0']=="1"){
              $this->trans_settlement->save_settlement_multi("t_opening_bal_trans",$_POST['customer'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],21,$_POST['id'],$_POST['4_0'],'0',$this->sd['cl'],$this->sd['branch']);   
            } 

            for($x=0; $x<300; $x++){
              if(isset($_POST['44_'.$x])){
                if($_POST['44_'.$x] != ""){
                  if($_POST['hh_'.$x]=="5"){
                    $this->trans_settlement->save_settlement("t_cus_settlement",$_POST['customer'],$_POST['date'],$_POST['hh_'.$x],$_POST['nn_'.$x],21,$this->max_no,"0",$_POST['44_'.$x]);  
                  }else if($_POST['hh_'.$x]=="18"){
                    $this->trans_settlement->save_settlement("t_debit_note_trans",$_POST['customer'],$_POST['date'],$_POST['hh_'.$x],$_POST['nn_'.$x],21,$this->max_no,"0",$_POST['44_'.$x]);  
                  }   
                }
              }
            }

            $this->utility->update_credit_sale_balance($_POST['customer']);
            $this->utility->update_debit_note_balance($_POST['customer']);

            $this->utility->save_logger("SAVE",21,$this->max_no,$this->mod); 
            echo $this->db->trans_commit();
          }else{
            $this->db->trans_commit();
            echo "No permission to save records";
          }  

        }else{
          if($this->user_permissions->is_edit('t_cus_settlement')){
            $this->db->where("cl",$this->sd['cl']);
            $this->db->where("bc",$this->sd['branch']);
            $this->db->where("nno",$this->max_no);
            $this->db->update("t_customer_settlement_sum",$t_customer_settlement_sum);

            $this->db->where("cl",$this->sd['cl']);
            $this->db->where("bc",$this->sd['branch']);
            $this->db->where("nno",$this->max_no);
            $this->db->update("t_customer_settlement_dr_temp",$t_customer_settlement_dr_temp);

            $this->set_delete(); 

            $this->load->model('trans_settlement');
            $this->trans_settlement->delete_settlement_sub("t_cus_settlement",$_POST['h_0'],$this->max_no); 
            $this->trans_settlement->delete_settlement_sub("t_credit_note_trans",$_POST['h_0'],$this->max_no);   
            $this->trans_settlement->delete_settlement_sub("t_opening_bal_trans",$_POST['h_0'],$this->max_no);
            
            if($_POST['h_0']=="17"){
              $this->trans_settlement->save_settlement("t_credit_note_trans",$_POST['customer'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],21,$_POST['id'],"0",$_POST['4_0']);
              $this->utility->update_credit_note_balance($_POST['customer']);
            }else if($_POST['h_0']=="16"){
              $this->utility->update_receipt_op_balance($_POST['customer']);
              $this->trans_settlement->save_settlement("t_cus_settlement",$_POST['customer'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],21,$_POST['id'],"0",$_POST['4_0']);  
            } else if($_POST['h_0']=="1"){
              $this->trans_settlement->save_settlement_multi("t_opening_bal_trans",$_POST['customer'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],21,$_POST['id'],$_POST['4_0'],"0",$this->sd['cl'],$this->sd['branch']);   
            } 

            for($x=0; $x<300; $x++){
              if(isset($_POST['44_'.$x])){
                if($_POST['44_'.$x] != ""){
                  $this->trans_settlement->delete_settlement_sub("t_cus_settlement",$_POST['hh_'.$x],$this->max_no);
                  $this->trans_settlement->delete_settlement_sub("t_debit_note_trans",$_POST['hh_'.$x],$this->max_no);
                  if($_POST['hh_'.$x]=="5"){
                    $this->trans_settlement->save_settlement("t_cus_settlement",$_POST['customer'],$_POST['date'],$_POST['hh_'.$x],$_POST['nn_'.$x],21,$this->max_no,"0",$_POST['44_'.$x]);  
                  }else if($_POST['hh_'.$x]=="18"){
                    $this->trans_settlement->save_settlement("t_debit_note_trans",$_POST['customer'],$_POST['date'],$_POST['hh_'.$x],$_POST['nn_'.$x],21,$this->max_no,"0",$_POST['44_'.$x]);  
                  }                      
                }
              }
            } 
            echo $this->db->trans_commit(); 
          }else{
            $this->db->trans_commit();
            echo "No permission to edit records";
          } 
        } 
        $this->utility->save_logger("EDIT",21,$this->max_no,$this->mod);                 
        
      }else{
        echo $validation_status;
        $this->db->trans_commit();
      } 
    }catch(Exception $e){ 
      $this->db->trans_rollback();
      echo $e->getMessage()."Operation fail please contact admin"; 
    }       
  }


  public function set_delete(){
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $this->db->where("nno",$this->max_no);
    $this->db->delete("t_customer_settlement_cr_temp");
  }

  public function check_code(){
    $this->db->where('code', $_POST['code']);
    $this->db->limit(1);
    echo $this->db->get($this->mtb)->num_rows;
  }

  public function load(){
    $bc=$this->sd['branch'];
    $cl=$this->sd['cl'];


    $this->db->select(array(
      't_customer_settlement_dr_temp.nno' ,
      't_customer_settlement_dr_temp.trans_no',
      't_customer_settlement_dr_temp.type',
      't_customer_settlement_dr_temp.date',
      't_customer_settlement_dr_temp.amount',
      't_customer_settlement_dr_temp.balance',
      't_customer_settlement_dr_temp.settle',
      't_customer_settlement_dr_temp.description as description_dr',
      'tc.description'
    ));
    $this->db->from('t_customer_settlement_dr_temp');
    $this->db->join('t_trans_code tc','t_customer_settlement_dr_temp.type=tc.code',"L");  
    $this->db->where('t_customer_settlement_dr_temp.nno',$_POST['id']);
    $this->db->where('t_customer_settlement_dr_temp.bc',$bc);
    $this->db->where('t_customer_settlement_dr_temp.cl',$cl);
    $query=$this->db->get();

    $x=0;
    if($query->num_rows()>0){
      $a['dr']=$query->result();
    }else{
      $x=2;
    }

    $this->db->select(array(
      't_customer_settlement_cr_temp.nno as nno_cr'  ,
      't_customer_settlement_cr_temp.trans_no trans_no_cr',
      't_customer_settlement_cr_temp.type type_cr',
      't_customer_settlement_cr_temp.date date_cr',
      't_customer_settlement_cr_temp.amount amount_cr',
      't_customer_settlement_cr_temp.balance balance_cr',
      't_customer_settlement_cr_temp.settle settle_cr',
      't_customer_settlement_cr_temp.description description_cr',
      'tc.description'
    ));

    $this->db->from('t_customer_settlement_cr_temp');
    $this->db->join('t_trans_code tc','t_customer_settlement_cr_temp.type=tc.code',"L");  
    $this->db->where('t_customer_settlement_cr_temp.nno',$_POST['id']);
    $this->db->where('t_customer_settlement_cr_temp.bc',$bc);
    $this->db->where('t_customer_settlement_cr_temp.cl',$cl);
    $query=$this->db->get();

    if($query->num_rows()>0){
      $a['cr']=$query->result();
    }else{
      $x=2;
    }  


    $this->db->select(array(
      't_customer_settlement_sum.ddate as sum_date'  ,
      't_customer_settlement_sum.nno as sum_nno',
      't_customer_settlement_sum.customer',
      't_customer_settlement_sum.amount',
      't_customer_settlement_sum.is_cancel',
    ));


    $this->db->from('t_customer_settlement_sum');
    $this->db->where('t_customer_settlement_sum.nno',$_POST['id']);
    $this->db->where('t_customer_settlement_sum.bc',$bc);
    $this->db->where('t_customer_settlement_sum.cl',$cl);
    $query=$this->db->get();


    if($query->num_rows()>0){
      $a['sum']=$query->result();
    }else{
      $x=2;
    }

    $this->db->select(array(
      "s.name",
      "m.customer"
    ));

    $this->db->from('t_customer_settlement_sum m');
    $this->db->join('m_customer s','s.code=m.customer',"L");             
    $this->db->where('m.nno',$_POST['id']);
    $this->db->limit(1);
    $query=$this->db->get(); 
    $a['cus_det']=$query->result();

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
      if($this->user_permissions->is_delete('t_cus_settlement')){
        $trans_no=$_POST['trans_no'];

        $this->load->model('trans_settlement');
        $this->trans_settlement->delete_settlement_sub("t_cus_settlement",21,$trans_no);
        $this->trans_settlement->delete_settlement_sub("t_debit_note_trans",21,$trans_no);
        $this->trans_settlement->delete_settlement_sub("t_credit_note_trans",21,$trans_no); 
        $this->trans_settlement->delete_settlement_sub("t_opening_bal_trans",21,$trans_no);
        $data=array('is_cancel'=>'1');
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('nno',$_POST['trans_no']);
        $this->db->update('t_customer_settlement_sum',$data); 

        $sql="SELECT customer FROM t_customer_settlement_sum WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['trans_no']."'";
        $cus_id=$this->db->query($sql)->first_row()->customer;

        $this->utility->update_credit_note_balance($cus_id);
        $this->utility->update_receipt_op_balance($cus_id);
        $this->utility->update_credit_sale_balance($cus_id);
        $this->utility->update_debit_note_balance($cus_id);

        $this->utility->save_logger("CANCEL",21,$_POST['trans_no'],$this->mod); 

        echo $this->db->trans_commit(); 
      }else{
        $this->db->trans_commit();
        echo "No permission to delete records";
      }
    }catch(Exception $e){ 
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

  public function load_item_cr(){
    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];

    if(isset($_POST['cus'])){
      $cus_code=$_POST['cus'];  
      $sql="SELECT  tc.`description`, d.* FROM (
      SELECT t.sub_cl, t.sub_bc, t.trans_code AS TYPE,t.trans_no, MIN(t.ddate) ddate, s.net_amount AS amount,s.balance 
      FROM `t_cus_settlement` t
      INNER JOIN `t_credit_sales_sum` s ON t.trans_no=s.nno AND t.sub_cl= s.cl AND t.sub_bc = s.bc AND s.is_cancel='0'
      WHERE (t.trans_code='5') AND (acc_code='$cus_code') GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code   
      HAVING balance > 0
      UNION ALL 
      SELECT t.sub_cl, t.sub_bc, t.trans_code AS trans_code_no,t.trans_no, MIN(t.ddate) ddate, s.amount ,s.balance 
      FROM `t_debit_note_trans` t
      INNER JOIN `t_debit_note` s ON t.trans_no=s.nno AND t.sub_cl= s.cl AND t.sub_bc = s.bc AND s.is_cancel='0'
      WHERE (t.trans_code='18') AND (t.acc_code='$cus_code') GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code
      UNION ALL 
      SELECT t.sub_cl,t.sub_bc,t.trans_code AS TYPE,t.trans_no,MIN(t.ddate) ddate,SUM(t.cr) AS amount,SUM(t.dr) - SUM(t.cr) AS balance 
FROM t_opening_bal_trans t 
JOIN t_opening_bal_sum s  ON s.cl = t.cl AND s.bc = t.bc AND s.no=t.sub_trans_no
      WHERE (t.acc_code='$cus_code') GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code
      ) d INNER JOIN t_trans_code tc ON tc.`code` = d.type WHERE d.sub_cl ='$cl' AND d.sub_bc = '$bc'
      HAVING balance > 0";

      $query=$this->db->query($sql);

      if($query->num_rows() > 0){
        $a['det']=$query->result();
        echo json_encode($a);
      }else{
        $a['det']=2;
        echo json_encode($a);
      }
    }
  }


  public function item_list_all(){

    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}               
    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];
    if($_POST['cus']){

      $sql="SELECT
      tc.`description`, ttc.`description` as sub_des, d.* FROM (           
      SELECT t.`sub_trans_code`, t.`sub_trans_no`, t.sub_cl, t.sub_bc, t.trans_code AS trans_code_no,t.trans_no, MIN(t.ddate) ddate, s.amount ,s.balance 
      FROM `t_credit_note_trans` t
      INNER JOIN `t_credit_note` s ON t.trans_no=s.nno
      WHERE (t.trans_code='17') AND (t.acc_code='$_POST[cus]') AND t.sub_cl= s.cl AND t.sub_bc = s.bc AND s.is_cancel='0' GROUP BY t.sub_cl,t.sub_bc, t.trans_no, t.trans_code
      ) d INNER JOIN t_trans_code tc ON tc.`code` = d.trans_code_no 
      INNER JOIN t_trans_code ttc ON ttc.`code` = d.sub_trans_code
      WHERE d.sub_cl ='$cl' AND d.sub_bc = '$bc' HAVING balance>0

      UNION ALL

      SELECT tc.`description`, ttc.`description` as sub_des, d.* FROM 
      ( SELECT t.`sub_trans_code`, t.`sub_trans_no`, t.sub_cl, t.sub_bc, t.trans_code AS trans_code_no,t.trans_no, MIN(t.ddate) ddate, s.settle_balance AS amount , s.receipt_balance AS balance 
      FROM `t_cus_settlement` t INNER JOIN `t_receipt` s ON t.trans_no=s.nno 
      WHERE (t.trans_code='16') AND (t.acc_code='$_POST[cus]') AND t.sub_cl= s.cl AND t.sub_bc = s.bc AND s.is_cancel='0' GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code
      ) d INNER JOIN t_trans_code tc ON tc.`code` = d.trans_code_no 
      INNER JOIN t_trans_code ttc ON ttc.`code` = d.sub_trans_code
      WHERE d.sub_cl ='$cl' AND d.sub_bc = '$bc' HAVING balance>0
      UNION ALL

      SELECT tc.`description`, ttc.`description` AS sub_des, d.* FROM (SELECT  tr.`sub_trans_code`, tr.`sub_trans_no`, tr.sub_cl, tr.sub_bc, 
      tr.trans_code AS trans_code_no,tr.trans_no, MIN(tr.ddate) ddate, SUM(tr.cr) AS amount ,SUM(tr.cr)-SUM(tr.dr) AS balance 
      FROM t_opening_bal_trans tr 
      JOIN t_opening_bal_sum s ON s.cl=tr.cl AND s.bc=tr.bc AND s.no=tr.trans_no
      WHERE tr.acc_code='$_POST[cus]' AND tr.sub_cl='$cl' AND tr.sub_bc='$bc'
      GROUP BY tr.sub_cl,tr.sub_bc,tr.trans_no, tr.trans_code,s.no
      HAVING balance >0) d INNER JOIN t_trans_code tc ON tc.`code` = d.trans_code_no 
      INNER JOIN t_trans_code ttc ON ttc.`code` = d.sub_trans_code
      WHERE d.sub_cl ='$cl' AND d.sub_bc = '$bc' HAVING balance>0
      ";




      $query=$this->db->query($sql);        



      $a = "<table id='item_list' style='width : 100%' >";

      $a .= "<thead><tr>";
      $a .= "<th class='tb_head_th'>Type</th>";
      $a .= "<th class='tb_head_th'>No</th>";
      $a .= "<th class='tb_head_th'>Description</th>";
      $a .= "<th class='tb_head_th'>No</th>";
      $a .= "<th class='tb_head_th'>Amount</th>";
      $a .= "<th class='tb_head_th'>Balance</th>";

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
        $a .= "<td style='display:none;'>".$r->trans_code_no."</td>";
        $a .= "<td style='display:none;'>".$r->trans_no."</td>";
        $a .= "<td style='display:none;'>".$r->ddate."</td>";
        $a .= "<td style='display:none;'>".$r->amount."</td>";
        $a .= "<td style='display:none;'>".$r->balance."</td>";
        $a .= "<td style='display:none;'>".$r->description."</td>";   


        $a .= "<td>".$r->description."</td>";
        $a .= "<td>".$r->trans_no."</td>";
        $a .= "<td>".$r->sub_des."</td>";
        $a .= "<td>".$r->sub_trans_no."</td>";
        $a .= "<td>".$r->amount."</td>";
        $a .= "<td>".$r->balance."</td>";

        $a .= "</tr>";
      }
      $a .= "</table>";

      echo $a;
    }

  }
  public function load_item_dr(){
    $_POST['cl']=$this->sd['cl'];
    $_POST['branch']=$this->sd['branch'];
    if($_POST['cus']){
      $sql="SELECT `t_trans_code`.`description` AS type, SUM(`t_cus_settlement`.`dr`) AS amount, 
      `t_cus_settlement`.`trans_no`, `t_cus_settlement`.`ddate`, 
      SUM(`t_cus_settlement`.`dr`-`t_cus_settlement`.`cr`) AS balance, `t_cus_settlement`.`description` 
      FROM (`t_cus_settlement`) 
      JOIN `t_trans_code` 
      ON `t_cus_settlement`.`type`=`t_trans_code`.`code` 
      WHERE `t_cus_settlement`.`acc_code` = '$_POST[cus]' AND `cl` = '$_POST[cl]' AND `bc` = '$_POST[branch]' 
      GROUP BY  `t_cus_settlement`.`trans_code`, `t_cus_settlement`.`trans_no`
      HAVING SUM(`t_cus_settlement`.`dr`)  >SUM(`t_cus_settlement`.`cr`) ";
      $query=$this->db->query($sql);


      if($query->num_rows() > 0){
       $a['det']=$query->result();
       echo json_encode($a);
     }
     else{
      echo json_encode(2);
    }
  }
}

public function load_data(){

  $this->db->select(array(

    "dt.amount",
    "dt.balance",
    "dt.settle",
    "dt.trans_no",
    "dt.description",
    "dt.date",
    "tc.description as des"

  ));

  $this->db->from('t_customer_settlement_dr_temp dt');
  $this->db->join('t_trans_code tc','dt.type=tc.code',"L");             
  $this->db->where('dt.nno',$_POST['id']);
  $query=$this->db->get();               

  $a['dr_det']=$query->result();
  $this->db->select(array(
    "dt.amount",
    "dt.balance",
    "dt.settle",
    "dt.trans_no",
    "dt.description",
    "dt.date",
    "tc.description as des"
  ));

  $this->db->from('t_customer_settlement_cr_temp dt');
  $this->db->join('t_trans_code tc','dt.type=tc.code',"L");             
  $this->db->where('dt.nno',$_POST['id']);
  $query=$this->db->get();  

  $a['cr_det']=$query->result();
  $this->db->select(array(
    "s.name",
    "m.customer"
  ));

  $this->db->from('t_customer_settlement_sum m');
  $this->db->join('m_customer s','s.code=m.customer',"L");             
  $this->db->where('m.nno',$_POST['id']);
  $this->db->limit(1);
  $query=$this->db->get();  

  $a['sup_det']=$query->result();
  echo json_encode($a);
}
}

