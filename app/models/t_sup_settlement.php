
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_sup_settlement extends CI_Model {

  private $sd;
  private $mtb;
  private $max_no;
  private $mod = '003';

  function __construct(){
    parent::__construct();
    
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');
    $this->mtb = $this->tables->tb['t_sup_settlement'];
  }

  public function base_details(){
   $this->load->model("utility");
   $a['max_no']=$this->utility->get_max_no("t_supplier_settlement_sum","nno");
   return $a;
 }

 public function validation(){
  $status=1;

  $this->max_no=$this->utility->get_max_no("t_supplier_settlement_sum","nno"); 

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

  $check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_supplier_settlement_sum');
  if($check_is_delete!=1){
    return "Supplier settlement already deleted";
  }
  $supplier_validation = $this->validation->check_is_supplier($_POST['supplier']);
  if($supplier_validation != 1){
    return "Please enter valid supplier";
  }
  $check_valid_cr_no=$this->validation->check_valid_trans_no('supplier','hh_','nn_');
  if($check_valid_cr_no!=1){
    return $check_valid_cr_no;
  }
  $check_valid_dr_no=$this->validation->check_valid_trans_no('supplier','h_','n_');
  if($check_valid_dr_no!=1){
    return $check_valid_dr_no;
  }
  $check_valid_trans_settle_status=$this->validation->check_valid_trans_settle('supplier','hh_','nn_','44_');
  if($check_valid_trans_settle_status!=1){
    return $check_valid_trans_settle_status;
  }  
  $check_valid_trans_settle_status2=$this->validation->check_valid_trans_settle('supplier','h_','n_','4_');
  if($check_valid_trans_settle_status2!=1){
    return $check_valid_trans_settle_status2;
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
    $validation_status=$this->validation();
    if($validation_status==1){

     $t_supplier_settlement_dr_temp=array(
       "cl"=>$this->sd['cl'],
       "bc"=>$this->sd['branch'],
       "oc"=>$this->sd['oc'],
       "type"=>$_POST['h_0'],
       "trans_no"=>$_POST['n_0'],
       "nno"=>$this->max_no,
       "date"=>$_POST['date'],
       "balance"=>$_POST['3_0'],
       "settle"=>$_POST['4_0'],
       "description"=>$_POST['5_0'],
       "amount"=>$_POST['2_0']
       );

     $t_supplier_settlement_sum=array(
       "cl"=>$this->sd['cl'],
       "bc"=>$this->sd['branch'],
       "oc"=>$this->sd['oc'],                  
       "nno"=>$this->max_no,
       "ddate"=>$_POST['date'],
       "supplier"=>$_POST['supplier'],                  
       "is_cancel"=>0,                 
       "amount"=>$_POST['ttl_dr']
       );

     for($i=0 ; $i < 100 ; $i++){
      if(!empty($_POST['44_'.$i])){
        $t_supplier_settlement_cr_temp[]=array(
         "cl"=>$this->sd['cl'],
         "bc"=>$this->sd['branch'],
         "oc"=>$this->sd['oc'],
         "type"=>$_POST['hh_'.$i],
         "trans_no"=>$_POST['nn_'.$i],
         "nno"=>$this->max_no,
         "date"=>$_POST['date'],
         "balance"=>$_POST['33_'.$i],
         "settle"=>$_POST['44_'.$i],
         "description"=>$_POST['55_'.$i],
         "amount"=>$_POST['22_'.$i]
         );
      }
    }

    if($_POST['hid'] == "0" || $_POST['hid'] == ""){
      if($this->user_permissions->is_add('t_sup_settlement')){
        $this->db->insert("t_supplier_settlement_sum",$t_supplier_settlement_sum);
        $this->db->insert("t_supplier_settlement_dr_temp",$t_supplier_settlement_dr_temp);
        if(count($t_supplier_settlement_cr_temp)){$this->db->insert_batch("t_supplier_settlement_cr_temp",$t_supplier_settlement_cr_temp);}
        $this->load->model('trans_settlement');

        if($_POST['h_0']=="19"){
          $this->trans_settlement->save_settlement("t_sup_settlement",$_POST['supplier'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],22,$_POST['id'],"0",$_POST['4_0']);
          $this->utility->update_voucher_op_balance($_POST['supplier']);  
        }else if($_POST['h_0']=="18"){
          $this->trans_settlement->save_settlement("t_debit_note_trans",$_POST['supplier'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],22,$_POST['id'],"0",$_POST['4_0']);
          $this->utility->update_debit_note_balance($_POST['supplier']);
        }else if($_POST['h_0']=="1"){
          $this->trans_settlement->save_settlement_multi("t_opening_bal_trans",$_POST['supplier'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],22,$_POST['id'],"0",$_POST['4_0'],$this->sd['cl'],$this->sd['branch']);  
        } 

        for($x=0; $x<100; $x++){
          if(isset($_POST['44_'.$x])){
            if($_POST['44_'.$x] != ""){
              if($_POST['hh_'.$x]=="3"){
                $this->trans_settlement->save_settlement("t_sup_settlement",$_POST['supplier'],$_POST['date'],$_POST['hh_'.$x],$_POST['nn_'.$x],22,$_POST['id'],"0",$_POST['44_'.$x]);  
              }else if($_POST['hh_'.$x]=="17"){
                $this->trans_settlement->save_settlement("t_credit_note_trans",$_POST['supplier'],$_POST['date'],$_POST['hh_'.$x],$_POST['nn_'.$x],22,$_POST['id'],"0",$_POST['44_'.$x]);  
              }
            }
          }
        }
        $this->utility->update_purchase_balance($_POST['supplier']);
        $this->utility->update_credit_note_balance($_POST['supplier']);  
        $this->utility->save_logger("SAVE",22,$this->max_no,$this->mod); 
        echo $this->db->trans_commit();
      }else{
        echo "No permission to save records";
        $this->db->trans_commit();
      }
    }else{
      if($this->user_permissions->is_edit('t_sup_settlement')){
       $this->set_delete(); 

       $this->db->where("cl",$this->sd['cl']);
       $this->db->where("bc",$this->sd['branch']);
       $this->db->where("nno",$this->max_no);
       $this->db->update("t_supplier_settlement_sum",$t_supplier_settlement_sum);

       $this->db->where("cl",$this->sd['cl']);
       $this->db->where("bc",$this->sd['branch']);
       $this->db->where("nno",$this->max_no);
       $this->db->update("t_supplier_settlement_dr_temp",$t_supplier_settlement_dr_temp);

       if(count($t_supplier_settlement_cr_temp)){$this->db->insert_batch("t_supplier_settlement_cr_temp",$t_supplier_settlement_cr_temp);}

       $this->load->model('trans_settlement');
       $this->trans_settlement->delete_settlement_sub("t_debit_note_trans",22,$this->max_no);   
       $this->trans_settlement->delete_settlement_sub("t_sup_settlement",22,$this->max_no);  
       $this->trans_settlement->delete_settlement_sub("t_opening_bal_trans",22,$this->max_no);

       if($_POST['h_0']=="19"){
        $this->trans_settlement->save_settlement("t_sup_settlement",$_POST['supplier'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],22,$_POST['id'],"0",$_POST['4_0']);
        $this->utility->update_voucher_op_balance($_POST['supplier']);  
      }else if($_POST['h_0']=="18"){
        $this->trans_settlement->save_settlement("t_debit_note_trans",$_POST['supplier'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],22,$_POST['id'],"0",$_POST['4_0']);
        $this->utility->update_debit_note_balance($_POST['supplier']);
      }else if($_POST['h_0']=="1"){
        $this->trans_settlement->save_settlement_multi("t_opening_bal_trans",$_POST['supplier'],$_POST['date'],$_POST['h_0'],$_POST['n_0'],22,$_POST['id'],"0",$_POST['4_0'],$this->sd['cl'],$this->sd['branch']);  
      }


      for($x=0; $x<100; $x++){
        if(isset($_POST['44_'.$x])){
          if($_POST['44_'.$x] != ""){
            $this->trans_settlement->delete_settlement_sub("t_sup_settlement",$_POST['hh_'.$x],$this->max_no);
            $this->trans_settlement->delete_settlement_sub("t_credit_note_trans",$_POST['hh_'.$x],$this->max_no);
            if($_POST['hh_'.$x]=="3"){
              $this->trans_settlement->save_settlement("t_sup_settlement",$_POST['supplier'],$_POST['date'],$_POST['hh_'.$x],$_POST['nn_'.$x],22,$_POST['id'],"0",$_POST['44_'.$x]);  
            }else if($_POST['hh_'.$x]=="17"){
              $this->trans_settlement->save_settlement("t_credit_note_trans",$_POST['supplier'],$_POST['date'],$_POST['hh_'.$x],$_POST['nn_'.$x],22,$_POST['id'],"0",$_POST['44_'.$x]);  
            }              
          }
        }
      }

      $this->utility->update_purchase_balance($_POST['supplier']);
      $this->utility->update_credit_note_balance($_POST['supplier']);  

      $this->utility->save_logger("EDIT",22,$this->max_no,$this->mod); 
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
  echo "Operation fail please contact admin"; 
}      

}



public function set_delete(){
  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $this->db->where("nno",$this->max_no);
  $this->db->delete("t_supplier_settlement_cr_temp");
}

public function check_code(){
  $this->db->where('code', $_POST['code']);
  $this->db->limit(1);
  echo $this->db->get($this->mtb)->num_rows;
}

public function load(){
  $this->db->where('code', $_POST['code']);
  $this->db->limit(1);
  echo json_encode($this->db->get($this->mtb)->first_row());
}

public function delete(){

  $this->db->trans_begin();
  error_reporting(E_ALL); 
  function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
    throw new Exception($errMsg); 
  }
  set_error_handler('exceptionThrower'); 
  try { 
    if($this->user_permissions->is_delete('t_sup_settlement')){
      $trans_no=$_POST['trans_no'];

      $this->load->model('trans_settlement');
      $this->trans_settlement->delete_settlement_sub("t_sup_settlement",22,$trans_no);
      $this->trans_settlement->delete_settlement_sub("t_credit_note_trans",22,$trans_no); 
      $this->trans_settlement->delete_settlement_sub("t_debit_note_trans",22,$trans_no);   
      $this->trans_settlement->delete_settlement_sub("t_opening_bal_trans",22,$trans_no);   
      
      $data=array('is_cancel'=>'1');
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);
      $this->db->where('nno',$_POST['trans_no']);
      $this->db->update('t_supplier_settlement_sum',$data);  

      $sql="SELECT supplier FROM t_supplier_settlement_sum WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['trans_no']."'";
      $sup_id=$this->db->query($sql)->first_row()->supplier;

      $this->utility->update_purchase_balance($sup_id);
      $this->utility->update_credit_note_balance($sup_id);  
      $this->utility->update_voucher_op_balance($sup_id); 
      $this->utility->update_debit_note_balance($sup_id);

      $this->utility->save_logger("CANCEL",22,$this->max_no,$this->mod);  

      echo $this->db->trans_commit(); 
    }else{
      echo "No permission to delete records";
      $this->db->trans_commit();
    }
  }catch(Exception $e){ 
    $this->db->trans_rollback();
    echo "Operation fail please contact admin"; 
  }
}

public function select(){
  $query = $this->db->get($this->mtb);
  $s = "<select name='sales_ref' id='sales_ref'>";
  $s.= "<option value='0'>---</option>";
  foreach($query->result() as $r){
    $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code." | ".$r->name."</option>";
  }
  $s .= "</select>";
  return $s;
}

public function load_item_cr(){
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];
  $acc_code=$_POST['cus'];
  if($_POST['cus']){

    $sql= "SELECT  tc.`description`, d.* FROM (
    SELECT t.sub_cl, t.sub_bc, t.trans_code AS TYPE,t.trans_no, MIN(t.ddate) ddate, s.net_amount AS amount,s.balance 
    FROM `t_sup_settlement` t
    INNER JOIN `t_grn_sum` s ON t.trans_no=s.nno AND t.sub_cl= s.cl AND t.sub_bc = s.bc
    WHERE (t.trans_code='3') AND (acc_code='$_POST[cus]') GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code
    HAVING balance > 0
    UNION ALL 
    SELECT t.sub_cl, t.sub_bc, t.trans_code AS trans_code_no,t.trans_no, MIN(t.ddate) ddate, s.amount ,s.balance 
    FROM `t_credit_note_trans` t
    INNER JOIN `t_credit_note` s ON t.trans_no=s.nno AND t.sub_cl= s.cl AND t.sub_bc = s.bc
    WHERE (t.trans_code='17') AND (t.acc_code='$_POST[cus]') GROUP BY t.sub_cl,t.sub_bc, t.trans_no, t.trans_code
    
  UNION ALL

    SELECT  tr.sub_cl, tr.sub_bc, tr.trans_code AS trans_code_no,tr.trans_no, MIN(tr.ddate) ddate, SUM(tr.cr) AS amount ,SUM(tr.cr)-SUM(tr.dr) AS balance 
    FROM t_opening_bal_trans tr 
    JOIN t_opening_bal_sum s ON s.cl=tr.cl AND s.bc=tr.bc AND s.no=tr.trans_no
    WHERE tr.acc_code='$_POST[cus]' AND tr.sub_cl='$cl' AND tr.sub_bc='$bc'
    GROUP BY tr.sub_cl,tr.sub_bc,tr.trans_no, tr.trans_code,s.no
    HAVING balance >0

    ) d INNER JOIN t_trans_code tc ON tc.`code` = d.type WHERE d.sub_cl ='$cl' AND d.sub_bc = '$bc'
    HAVING balance > 0";

/*            $sql="SELECT tc.`description`, d.* FROM( 
              SELECT s.cl, s.bc, s.type as TYPE, s.nno AS trans_no, s.memo, s.ddate, s.net_amount AS amount,t.balance 
              FROM t_grn_sum s  JOIN (
                      SELECT t.sub_cl AS cl,t.sub_bc AS bc,
                        t.trans_code,
                        t.trans_no,
                        t.acc_code,
                        SUM(dr)-SUM(cr) balance
                      FROM t_sup_settlement t
                      WHERE t.acc_code='$acc_code' AND t.sub_cl='$cl' AND t.sub_bc='$bc'
                      GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code
                      HAVING balance > 0
                    )t 
              ON t.trans_no=s.nno AND  t.acc_code=s.supp_id WHERE s.supp_id='$acc_code' AND t.trans_code='3' 
              AND s.bc =t.bc AND s.cl = t.cl AND s.cl='$cl' AND s.bc='$bc'
              UNION ALL 
              SELECT s.cl, s.bc, s.type as TYPE,s.nno AS trans_no, s.memo, s.ddate, s.amount,t.balance 
              FROM t_credit_note s INNER JOIN (
                      SELECT t.sub_cl AS cl,t.sub_bc AS bc,
                        t.trans_code,
                        t.trans_no,
                        t.acc_code,
                        SUM(dr)-SUM(cr) balance
                      FROM t_credit_note_trans t
                      WHERE t.acc_code='$acc_code' AND t.sub_cl='$cl' AND t.sub_bc='$bc'
                      GROUP BY t.sub_cl,t.sub_bc,t.trans_no, t.trans_code
                      HAVING balance > 0  
                    ) t 
              ON t.trans_no=s.nno AND t.acc_code=s.code WHERE s.code='$acc_code' 
              AND s.bc =t.bc AND s.cl = t.cl AND s.cl='$cl' AND s.bc='$bc' 
            ) d 
            INNER JOIN t_trans_code tc ON tc.`code` = d.type"; */



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
        $acc_code=$_POST['cus'];
        if($_POST['cus']){

         $sql="SELECT  tc.`description`, ttc.`description` as sub_des, d.* FROM (
         SELECT  t.`sub_trans_code`, t.`sub_trans_no`, t.sub_cl, t.sub_bc, t.trans_code AS trans_code_no,t.trans_no, MIN(t.ddate) ddate, s.amount ,s.balance 
         FROM `t_debit_note_trans` t
         INNER JOIN `t_debit_note` s ON t.trans_no=s.nno AND t.sub_cl= s.cl AND t.sub_bc = s.bc
         WHERE (t.trans_code='18') AND (t.acc_code='$_POST[cus]') GROUP BY t.sub_cl,t.sub_bc,trans_no, t.trans_code  
         ) d INNER JOIN t_trans_code tc ON tc.`code` = d.trans_code_no 
         INNER JOIN t_trans_code ttc ON ttc.`code` = d.sub_trans_code
         WHERE d.sub_cl ='$cl' AND d.sub_bc = '$bc' HAVING balance>0

         UNION ALL

         SELECT tc.`description`, ttc.`description` as sub_des, d.* FROM (
         SELECT  t.`sub_trans_code`, t.`sub_trans_no`, t.sub_cl, t.sub_bc, t.trans_code AS trans_code_no,t.trans_no, MIN(t.ddate) ddate, s.settle_balance AS amount ,s.voucher_balance as balance 
         FROM `t_sup_settlement` t INNER JOIN `t_voucher_sum` s ON t.trans_no=s.nno AND t.sub_cl= s.cl AND t.sub_bc = s.bc
         WHERE (t.trans_code='19') AND (t.acc_code='$_POST[cus]') GROUP BY t.sub_cl,t.sub_bc, trans_no, t.trans_code 
         ) d INNER JOIN t_trans_code tc ON tc.`code` = d.trans_code_no 
         INNER JOIN t_trans_code ttc ON ttc.`code` = d.sub_trans_code
         WHERE d.sub_cl ='$cl' AND d.sub_bc = '$bc' HAVING balance>0

         UNION ALL

         SELECT tc.`description`, ttc.`description` AS sub_des, d.* FROM (SELECT  tr.`sub_trans_code`, tr.`sub_trans_no`, tr.sub_cl, tr.sub_bc, 
         tr.trans_code AS trans_code_no,tr.trans_no, MIN(tr.ddate) ddate, SUM(tr.dr) AS amount ,SUM(tr.dr)-SUM(tr.cr) AS balance 
         FROM t_opening_bal_trans tr 
         JOIN t_opening_bal_sum s ON s.cl=tr.cl AND s.bc=tr.bc
         WHERE tr.acc_code='$acc_code' AND tr.sub_cl='$cl' AND tr.sub_bc='$bc'
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
         $a .= "<th class='tb_head_th'>Ref Trans Type</th>";
         $a .= "<th class='tb_head_th'>Ref No</th>";
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

    public function load_data(){
     $this->db->select(array(
      "ddate",
      "amount",
      "is_cancel"
      ));
     $this->db->where('nno',$_POST['id']);
     $this->db->where('cl',$this->sd['cl']);
     $this->db->where('bc',$this->sd['branch']);

     $query=$this->db->get('t_supplier_settlement_sum');
     $a['sum']=$query->result();

     $this->db->select(array(
      "dt.type as TYPE",
      "dt.amount",
      "dt.balance",
      "dt.settle",
      "dt.trans_no",
      "dt.description as des",
      "dt.date",
      "tc.description as description"
      ));

     $this->db->from('t_supplier_settlement_dr_temp dt');
     $this->db->join('t_trans_code tc','dt.type=tc.code',"L");             
     $this->db->where('dt.nno',$_POST['id']);
     $this->db->where('cl',$this->sd['cl']);
     $this->db->where('bc',$this->sd['branch']);
     $query=$this->db->get();
     $a['dr_det']=$query->result();

     $this->db->select(array( 
      "dt.type as TYPE",         
      "dt.amount",
      "dt.balance",
      "dt.settle",
      "dt.trans_no",
      "dt.description",
      "dt.date",
      "tc.description as des"
      ));
     $this->db->from('t_supplier_settlement_cr_temp dt');
     $this->db->join('t_trans_code tc','dt.type=tc.code',"L");             
     $this->db->where('dt.nno',$_POST['id']);
     $this->db->where('cl',$this->sd['cl']);
     $this->db->where('bc',$this->sd['branch']);
     $query=$this->db->get();  

     $a['cr_det']=$query->result();

     $this->db->select(array(
      "s.name",
      "m.supplier"

      ));
     $this->db->from('t_supplier_settlement_sum m');
     $this->db->join('m_supplier s','s.code=m.supplier',"L");             
     $this->db->where('m.nno',$_POST['id']);
     $this->db->where('cl',$this->sd['cl']);
     $this->db->where('bc',$this->sd['branch']);
     $this->db->limit(1);
     $query=$this->db->get(); 
     $a['sup_det']=$query->result();
     echo json_encode($a);
   }
 }
