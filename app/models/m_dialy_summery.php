<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class m_dialy_summery extends CI_Model {

  private $sd;    
  private $mod = '003';

  function __construct() {
    parent::__construct();
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');
    $this->max_no = $this->utility->get_max_no("t_daily_collection_sum", "nno");
    $this->max_date = $this->max_date(); 
  }

  public function base_details() {
    $a['id'] = $this->utility->get_max_no("t_daily_collection_sum", "nno");
    $a['acc_code'] = $this->aac_code();
    $a['opb'] = $this->opening_balance();
    $a['cash_f'] = $this->cash_float();
    $a['cash'] = $this->cash_sale();
    $a['credit'] = $this->credit_sale();
    $a['receipt'] = $this->receipt();
    $a['finance'] = $this->finance();
    $a['s_return'] = $this->sales_return();
    $a['receipt_cancel'] = $this->receipt_cancellation();
    $a['cash_voucher'] = $this->cash_payment_voucher();
    $a['j_section'] = $this->j_section_receipts();
    $a['cl_name'] = $this->cl_name();
    $a['bc_name'] = $this->bc_name();
    $a['max_date'] = $this->max_date();
    $a['advance_amount']= $this->advance_amount();
    $a['cash_only']=$this->cash_sale_olny();
    $a['gift_only']=$this->gift_sale_only();
    $a['ccard_sales']=$this->ccard_sales();
    return $a;
  }  

  public function max_date(){

    $sql="SELECT ddate FROM `t_daily_collection_sum`
    WHERE cl='".$this->sd['cl']."' 
    AND bc='".$this->sd['branch']."'
    limit 1";  
    $query = $this->db->query($sql);        
    $r_detail['details'] = $query->result();
    if($query->num_rows()>0){
     $table_date='1';
   }else{
    $table_date='0';
  } 
  if($table_date=="0"){
   $sql="SELECT IFNULL(MAX(ddate),CURRENT_DATE) AS date             		
   FROM `t_daily_collection_sum`
   WHERE cl='".$this->sd['cl']."' 
   AND bc='".$this->sd['branch']."'
   limit 1"; 
            /*$sql="SELECT DATE_ADD(IFNULL(MAX(ddate),CURRENT_DATE),INTERVAL -1 DAY) AS date 
            		FROM `t_daily_collection_sum`
                  WHERE cl='".$this->sd['cl']."' 
                  AND bc='".$this->sd['branch']."'
                  limit 1";   */
                  return $this->db->query($sql)->first_row()->date; 
                }else{
                  $sql="SELECT DATE_ADD(IFNULL(MAX(ddate),CURRENT_DATE),INTERVAL 1 DAY) AS date 
                  FROM t_daily_collection_sum 
                  WHERE cl='".$this->sd['cl']."' 
                  AND bc='".$this->sd['branch']."'
                  limit 1";  
                  return $this->db->query($sql)->first_row()->date;    
                }
              }

              public function cash_float(){
                $sql="SELECT cash_float FROM m_branch WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' ";
                $query = $this->db->query($sql)->row()->cash_float;
                return $query;
              }

              public function cl_name(){
                $sql="SELECT `name` FROM m_branch WHERE cl='".$this->sd['cl']."' and bc='".$this->sd['branch']."'";
                $query = $this->db->query($sql)->row()->name;
                return $query;
              }

              public function bc_name(){
                $sql="SELECT description FROM m_cluster where code ='".$this->sd['cl']."'";
                $query = $this->db->query($sql)->row()->description;
                return $query;
              }

              public function aac_code(){
                $sql="SELECT acc_code FROM `m_default_account` WHERE CODE='CASH_IN_HAND'";
                $query = $this->db->query($sql);
                return $query->row()->acc_code;
              }  

              public function opening_balance(){
                $sql="SELECT IFNULL( SUM( t.`dr_amount`),0) - IFNULL(SUM(t.`cr_amount`),0) AS OPB 
                FROM `t_account_trans` t 
                WHERE t.`cl`='".$this->sd['cl']."' AND t.`bc`='".$this->sd['branch']."' AND t.`ddate` < '".$this->max_date."' AND 
                t.`acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='CASH_IN_HAND')";
                $query = $this->db->query($sql);

                return $query->row()->OPB;
              }

              public function bank_entry(){

        //-------CORRECT QUERY-----
               $sql="SELECT  `nno` ,`draccId` , `amount`
               FROM `t_bank_entry`
               WHERE `cl`='".$this->sd['cl']."' AND `bc`='".$this->sd['branch']."' AND `dDate`='".$this->max_date."' AND 
               `craccId` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='CASH_IN_HAND')";


       /* //------TEST QUERY--------
        $sql="SELECT  `nno` ,`draccId` , `amount`
            FROM `t_bank_entry`
            ";*/

            $query = $this->db->query($sql);
            $a="";
            $x=0;
            $tot=(float)0;
            foreach($query->result() as $r){
              $a.="<tr>";
              $a.="<td><input type='text' class='input_active g_input_num' readonly='readonly' name='nno_".$x."' id='nno_".$x."' value='".$r->nno."' title='".$r->nno."'> </td>";
              $a.="<td><input type='text' class='input_active g_input_num' readonly='readonly' name='acc_".$x."' id='acc_".$x."' value='".$r->draccId."' title='".$r->draccId."'> </td>";
              $a.="<td><input type='text' class='input_active g_input_num' readonly='readonly' name='amount_".$x."' id='amount_".$x."' value='".$r->amount."' title='".$r->amount."'> </td>";
              $a.="</tr>";
              $x++;
              $tot=$tot+(float)$r->amount;
            }
            $c['table'] = $a;
            $c['tot'] = $tot;

            echo json_encode($c); 
          }

          public function finance(){
            $sql="SELECT IFNULL(SUM(`net_amount`), 0) AS FinanceCompany 
            FROM  `t_credit_sales_sum` 
            WHERE `cl` = '".$this->sd['cl']."' 
            AND `bc` = '".$this->sd['branch']."' 
            AND `ddate` = '".$this->max_date."' 
            AND `category` = 'FCS' ";

            $query = $this->db->query($sql);
            return $query->row()->FinanceCompany; 
          }

          public function j_section_receipts(){
            $sql="SELECT  IFNULL( SUM( `pay_cash`),0)  AS Cash, 
            IFNULL( SUM(`pay_ccard`),0) AS CreditCard, 
            IFNULL( SUM( `pay_receive_chq`) ,0) AS Cheque
            FROM `t_receipt`  
            WHERE `cl`='".$this->sd['cl']."' AND `bc`='".$this->sd['branch']."' AND `ddate`='".$this->max_date."'
            AND is_cancel='0'";

            $query = $this->db->query($sql);

            $data['cash'] = $query->row()->Cash;
            $data['credit'] = $query->row()->CreditCard;
            $data['cheque'] = $query->row()->Cheque;

            return $data;
          }

          public function sales_return(){
            $sql="SELECT IFNULL( SUM( s.`net_amount` ),0) AS  SalesReturn
            FROM `t_sales_return_sum` s
            WHERE s.`cl`='".$this->sd['cl']."'  AND s.`bc`='".$this->sd['branch']."' AND s.`ddate`='".$this->max_date."' ";

            $query = $this->db->query($sql);
            return $query->row()->SalesReturn; 
          }

          public function cash_sale(){
            $sql_cash="SELECT  IFNULL(SUM( `pay_cash`),0.00) AS CasSales
            FROM `t_cash_sales_sum` WHERE `cl`='".$this->sd['cl']."' AND `bc`='".$this->sd['branch']."' 
            AND `dDate`='".$this->max_date."' 
            ";
            $query_cash = $this->db->query($sql_cash)->row()->CasSales;

            $sql_gift="SELECT IFNULL(SUM( `pay_cash`),0.00) AS GiftSales FROM `g_t_sales_sum`
            WHERE `cl`='".$this->sd['cl']."' AND `bc`='".$this->sd['branch']."' 
            AND `dDate`='".$this->max_date."' ";
            $query_gift = $this->db->query($sql_gift)->row()->GiftSales;

            $total =(float)$query_cash+(float)$query_gift;


            return $total;
          }

          public function cash_sale_olny(){
            $sql_cash="SELECT  IFNULL(SUM( `pay_cash`),0.00) AS CasSales
            FROM `t_cash_sales_sum` WHERE `cl`='".$this->sd['cl']."' AND `bc`='".$this->sd['branch']."' 
            AND `dDate`='".$this->max_date."' 
            ";
            $query_cash = $this->db->query($sql_cash)->row()->CasSales;
            $total = $query_cash;
            return $total;
          }

          public function gift_sale_only(){
            $sql_gift="SELECT IFNULL(SUM( `pay_cash`),0.00) AS GiftSales FROM `g_t_sales_sum`
            WHERE `cl`='".$this->sd['cl']."' AND `bc`='".$this->sd['branch']."' 
            AND `dDate`='".$this->max_date."' ";
            $query_gift = $this->db->query($sql_gift)->row()->GiftSales;
            $total = $query_gift;
            return $total;
          }

          public function credit_sale(){
            $sql="SELECT  IFNULL( SUM( `net_amount` ),0) AS CreSales
            FROM `t_credit_sales_sum` WHERE `cl`='".$this->sd['cl']."' AND `bc`='".$this->sd['branch']."' AND `dDate`='".$this->max_date."' 
            ";

            $query = $this->db->query($sql);

            return $query->row()->CreSales;
          }

          public function advance_amount(){
            $sql="SELECT IFNULL(SUM(cash_amount),0) AS advance FROM t_advance_sum
            WHERE cl='".$this->sd['cl']."'
            AND bc='".$this->sd['branch']."'
            AND ddate='".$this->max_date."'";


            $query = $this->db->query($sql);
            return $query->row()->advance;
          }

          public function ccard_sales(){
            $sql="SELECT IFNULL(SUM(pay_ccard),0.00) AS ccard FROM t_cash_sales_sum 
            WHERE cl='".$this->sd['cl']."'
            AND bc='".$this->sd['branch']."' AND ddate='".$this->max_date."'";
            $query=$this->db->query($sql);
            return $query->row()->ccard;
          }


          public function receipt(){
       /* $sql="SELECT  IFNULL(SUM(`pay_cash`),0) AS receipt
                FROM `t_receipt` 
                WHERE `cl`='".$this->sd['cl']."' AND `bc`='".$this->sd['branch']."' 
                AND `dDate`='".$this->max_date."' ";*/
                $sql="SELECT SUM(receipt) AS receipt FROM (
                SELECT  IFNULL(SUM(`pay_cash`), 0) AS receipt 
                FROM  `t_receipt` 
                WHERE `cl` = '".$this->sd['cl']."' 
                AND `bc` = '".$this->sd['branch']."' 
                AND `dDate` = '".$this->max_date."' 
                AND is_cancel='0'                  
                UNION ALL        
                SELECT IFNULL(SUM(cash_amount),0) AS gl_r FROM t_receipt_gl_sum
                WHERE cl='".$this->sd['cl']."'
                AND bc='".$this->sd['branch']."'
                AND is_cancel='0'
                AND ddate='".$this->max_date."') t";


                $query = $this->db->query($sql);

                return $query->row()->receipt;
              }

              public function receipt_cancellation(){
                $sql="SELECT IFNULL( SUM( r.`payment` ),0) AS CancelAmt
                FROM  `t_log_det` d
                LEFT JOIN  `t_receipt`  r ON d.cl=r.`cl` AND d.bc = r.`bc` AND d.trans_no = r.`nno`
                WHERE d.`trans_code`=16 AND d.`cl`='".$this->sd['cl']."' 
                AND d.`bc`='".$this->sd['branch']."' 
                AND d.`action_date`='".$this->max_date."'
                AND d.`action`='CANCEL'";

                $query=$this->db->query($sql);

                $am = $query->row()->CancelAmt;
                return (float)$am;
              }

              public function cash_payment_voucher(){    

                $sql="SELECT IFNULL( SUM(CashVoucher),0) AS CashVoucher
                FROM
                (SELECT IFNULL( SUM( `cash_amount`),0) AS CashVoucher  FROM `t_voucher_gl_sum` WHERE `cl`='".$this->sd['cl']."' AND `bc`='".$this->sd['branch']."' AND `dDate`='".$this->max_date."'  
                UNION ALL 
                SELECT  IFNULL( SUM( `pay_cash` ),0) AS CashVoucher  FROM `t_voucher_sum`   WHERE `cl`='".$this->sd['cl']."' AND `bc`='".$this->sd['branch']."' AND `dDate`='".$this->max_date."' 
                UNION ALL
                SELECT  IFNULL(sum(amount),0) as debit FROM t_debit_note 
                WHERE   cl='".$this->sd['cl']."' 
                AND bc='".$this->sd['branch']."' 
                AND ddate = '".$this->max_date."'
                AND `acc_code` IN ( SELECT acc_code FROM `m_default_account` WHERE CODE='CASH_IN_HAND')
              )AS a";

              $query= $this->db->query($sql);
              $am =$query->row()->CashVoucher;
              return (float)$am;
            }


            public function save(){        

              $this->db->trans_begin();
              error_reporting(E_ALL); 
              function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
                throw new Exception($errLine); 
              }
              set_error_handler('exceptionThrower'); 
              try {

                for($x = 0; $x<500; $x++){
                  if(isset($_POST['nno_'.$x],$_POST['acc_'.$x],$_POST['amount_'.$x])){
                    if($_POST['nno_'.$x] != "" && $_POST['acc_'.$x] != "" && $_POST['amount_'.$x] != "" ){
                      $b[]= array(
                        "cl"=>$this->sd['cl'],
                        "bc"=>$this->sd['branch'],
                        "nno"=>$this->max_no,
                        "entry_no"=>$_POST['nno_'.$x],
                        "bank_acc"=>$_POST['acc_'.$x],
                        "amount"=>$_POST['amount_'.$x]
                      );              
                    }
                  }
                }       

                $data=array(
                  "cl" => $this->sd['cl'],
                  "bc" => $this->sd['branch'],
                  "nno" => $this->max_no,
                  "ddate" => $_POST['ddate'],
                  "cash_acc" => $_POST['acc_code'],
                  "opb" => $_POST['opb'],
                  "cash_float" => $_POST['cash_f'],
                  "cash_sales_system" => $_POST['cash'],
                  "cash_sales_manual" => $_POST['cash_m'],
                  "rcp" => $_POST['receipt'],
                  "rcp_transport" => $_POST['receipt_t'],
                  "rcp_advance" => $_POST['receipt_a'],
                  "rcp_others" => $_POST['receipt_o'],
                  "rcp_cancel" => $_POST['receipt_cancel'],
                  "cash_voucher" => $_POST['cash_voucher'],
                  "rcp_manual" => $_POST['receipt_manual'],
                  "dn_5000" => $_POST['5000_tot'],
                  "dn_2000" => $_POST['2000_tot'],
                  "dn_1000" => $_POST['1000_tot'],
                  "dn_500" => $_POST['500_tot'],
                  "dn_100" => $_POST['100_tot'],
                  "dn_50" => $_POST['50_tot'],
                  "dn_20" => $_POST['20_tot'],
                  "dn_10" => $_POST['10_tot'],
                  "dn_coints" => $_POST['coints_tot'],
                  "inv_cash" => $_POST['i_cash_sale'],
                  "inv_credit" => $_POST['i_credit_sale'],
                  "inv_finance" => $_POST['finance'],
                  "inv_card" => $_POST['ccard_sales'],
                  "inv_internal" => "",
                  "inv_return" => $_POST['s_return'],
                  "rcp_cash" => $_POST['j_cash'],
                  "rcp_card" => $_POST['j_credit'],
                  "rcp_cheque" => $_POST['j_cheque'],
                  "shortage" => $_POST['ttt'],
                  "cash_sale_tot"=>$_POST['c_sales'],
                  "gift_sale_tot"=>$_POST['g_sales'],
                  "note"=>$_POST['note']
                );


                $data_update=array(          
                  "ddate" => $_POST['ddate'],
                  "cash_acc" => $_POST['acc_code'],
                  "opb" => $_POST['opb'],
                  "cash_float" => $_POST['cash_f'],
                  "cash_sales_system" => $_POST['cash'],
                  "cash_sales_manual" => $_POST['cash_m'],
                  "rcp" => $_POST['receipt'],
                  "rcp_transport" => $_POST['receipt_t'],
                  "rcp_advance" => $_POST['receipt_a'],
                  "rcp_others" => $_POST['receipt_o'],
                  "rcp_cancel" => $_POST['receipt_cancel'],
                  "cash_voucher" => $_POST['cash_voucher'],
                  "rcp_manual" => $_POST['receipt_manual'],
                  "dn_5000" => $_POST['5000_tot'],
                  "dn_2000" => $_POST['2000_tot'],
                  "dn_1000" => $_POST['1000_tot'],
                  "dn_500" => $_POST['500_tot'],
                  "dn_100" => $_POST['100_tot'],
                  "dn_50" => $_POST['50_tot'],
                  "dn_20" => $_POST['20_tot'],
                  "dn_10" => $_POST['10_tot'],
                  "dn_coints" => $_POST['coints_tot'],
                  "inv_cash" => $_POST['i_cash_sale'],
                  "inv_credit" => $_POST['i_credit_sale'],
                  "inv_finance" => $_POST['finance'],
                  "inv_card" => "",
                  "inv_internal" => "",
                  "inv_return" => $_POST['s_return'],
                  "rcp_cash" => $_POST['j_cash'],
                  "rcp_card" => $_POST['j_credit'],
                  "rcp_cheque" => $_POST['j_cheque'],
                  "shortage" => $_POST['ttt'],
                  "cash_sale_tot"=>$_POST['c_sales'],
                  "gift_sale_tot"=>$_POST['g_sales'],
                  "note"=>$_POST['note']
                );


                if($_POST['hid'] == "0" || $_POST['hid'] == ""){
                  if($this->user_permissions->is_add('m_dialy_summery')){ 

                    $this->db->insert('t_daily_collection_sum',$data);
                    if(isset($b)){if(count($b)){$this->db->insert_batch("t_daily_collection_det",$b);}}

                    $this->utility->save_logger("SAVE",50,$this->max_no,$this->mod);
                    echo $this->db->trans_commit();
                  }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                  }   
                }else{
                  if($this->user_permissions->is_edit('m_dialy_summery')){

                   $this->db->where("nno", $_POST['hid']);
                   $this->db->where("cl", $this->sd['cl']);
                   $this->db->where("bc", $this->sd['branch']);
                   $this->db->delete("t_daily_collection_det");

                   $this->db->where("nno", $_POST['hid']);
                   $this->db->where("cl", $this->sd['cl']);
                   $this->db->where("bc", $this->sd['branch']);
                   $this->db->update("t_daily_collection_sum", $data_update);

                   if(isset($b)){if(count($b)){$this->db->insert_batch("t_daily_collection_det",$b);}}
                   $this->utility->save_logger("EDIT",50,$this->max_no,$this->mod);
                   echo $this->db->trans_commit();   

                 }else{
                  echo "No permission to edit records";
                  $this->db->trans_commit();
                }   
              }

            }catch(Exception $e){ 
              $this->db->trans_rollback();
              echo $e->getMessage().$e->getline()."Operation fail please contact admin"; 
            } 

          }



          public function get_display() {

            $id = $_POST['id'];

            $x     = 0;

            $sql="select * from t_daily_collection_sum where cl ='".$this->sd['cl']."' and bc='".$this->sd['branch']."' and nno='$id' ";
            $query = $this->db->query($sql);

            if ($query->num_rows() > 0) {
              $a['sum'] = $query->result();
            } else {
              $x = 2;
            }


            $sql2="select * from t_daily_collection_det where cl ='".$this->sd['cl']."' and bc='".$this->sd['branch']."' and nno='$id' ";
            $query2 = $this->db->query($sql2);

            if ($query->num_rows() > 0) {
              $a['det'] = $query2->result();
            } else {
              $x = 2;
            }


            if ($x == 0) {
              echo json_encode($a);
            } else {
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
              if($this->user_permissions->is_delete('m_dialy_summery')){

                $no =$_POST['id'];   

                $this->db->where('nno',$no);
                $this->db->where('cl',$this->sd['cl']);
                $this->db->where('bc',$this->sd['branch']);
                $this->db->update('t_daily_collection_sum', array("is_cancel"=>1));

                $this->utility->save_logger("CANCEL",50,$no,$this->mod);
                echo  $this->db->trans_commit();

              }else{
                echo "No permission to delete records";
                $this->db->trans_commit();
              }
            }catch(Exception $e){ 
              $this->db->trans_rollback();
              echo $e->getMessage()."Operation fail please contact admin"; 
            }   
          }


          public function PDF_report(){
            $r_detail['deliver_date'];
            $r_detail['ship_to_bc'];
            $r_detail['supplier'];
            $r_detail['ddate'];
            $r_detail['total_amount'];

            $invoice_number= $this->utility->invoice_format($_POST['qno']);
            $session_array = array(
             $this->sd['cl'],
             $this->sd['branch'],
             $invoice_number
           );
            $r_detail['session'] = $session_array;



            $this->db->select(array('name','address','tp','fax','email'));
            $this->db->where("cl",$this->sd['cl']);
            $this->db->where("bc",$this->sd['branch']);
            $r_detail['branch']=$this->db->get('m_branch')->result();



            $r_detail['qno']=$_POST['qno'];
            $r_detail['page']=$_POST['page'];
            $r_detail['header']=$_POST['header'];
            $r_detail['orientation']=$_POST['orientation'];
            $r_detail['type']=$_POST['type'];
            $r_detail['inv_date']=$_POST['inv_date'];
            $r_detail['inv_nop']=$_POST['inv_nop'];
            $r_detail['po_nop']=$_POST['po_nop'];
            $r_detail['po_dt']=$_POST['po_dt'];
            $r_detail['credit_prd']=$_POST['credit_prd'];



            $sql="select * from t_daily_collection_sum 
            JOIN m_account on m_account.code=t_daily_collection_sum.cash_acc
            where cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' and nno='".$_POST['qno']."'";

            $query=$this->db->query($sql);
            if($query->num_rows>0){
              $r_detail['sum']=$query->result();
            }else{
              $r_detail['sum']=2;
            }


            $sql2="SELECT entry_no,CONCAT(bank_acc,'-',a.`description`)AS bank_acc,amount from t_daily_collection_det 
            JOIN `m_account` a ON a.`code`=t_daily_collection_det.`bank_acc`
            WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' and nno='".$_POST['qno']."'";

            $query2=$this->db->query($sql2);
            if($query->num_rows>0){
              $r_detail['bank_entry']=$query2->result();
            }else{
              $r_detail['bank_entry']=2;
            }


            $sql3="SELECT b.name, l.description 
            FROM m_branch b
            JOIN m_cluster l ON l.code = b.cl
            where cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";

            $query3=$this->db->query($sql3);
            if($query3->num_rows>0){
              $r_detail['cl']=$query3->result();
            }else{
              $r_detail['cl']=2;
            }



            $this->db->select(array('loginName'));
            $this->db->where('cCode',$this->sd['oc']);
            $r_detail['user']=$this->db->get('users')->result();



            $this->load->view($_POST['by'].'_'.'pdf',$r_detail);

          }

        }
