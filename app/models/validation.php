<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class validation extends CI_Model {
	private $sd;

  function __construct(){
    parent::__construct();
    $this->sd = $this->session->all_userdata();
  }

  public function serial_update($item_pre,$quantity_pre,$serial){


    if($_POST['df_is_serial']!=1){
      return 1;
    }

    $status=1;
    for($x = 0; $x<25; $x++){
      if($this->check_is_serial_items($_POST[$item_pre.$x])==1 && $_POST[$quantity_pre.$x]>0){
        $serial_count=0;
        $all_serial=$_POST[$serial.$x];
        $item=$_POST[$item_pre.$x];
        $quantity=$_POST[$quantity_pre.$x];

        $pp=explode(",",$all_serial);

        for($i = 0; $i < count($pp); $i++){
          $p=explode("-", $pp[$i]);
          $serial_count++;
        }
        if($serial_count==$quantity){
          $status=1;
        }else{
          return "Please check the serial(s) in ".$item;
        }
      }
    }
    return $status;
  }

  public function serial_update_gift($item_pre,$quantity_pre,$serial){


    if($_POST['df_is_serial']!=1){
      return 1;
    }

    $status=1;
    for($x = 0; $x<25; $x++){
      if($_POST[$item_pre.$x]!=""  && $_POST[$quantity_pre.$x]>0){


        $serial_count=0;
        $all_serial=$_POST[$serial.$x];
        $item=$_POST[$item_pre.$x];
        $quantity=$_POST[$quantity_pre.$x];

        $pp=explode(",",$all_serial);

        for($i = 0; $i < count($pp); $i++){
          $p=explode("-", $pp[$i]);
          $serial_count++;
        }
        if($serial_count==$quantity){
          $status=1;
        }else{
          return "Please check the serial(s) in ".$item;
        }
      }
    }
    return $status;
  }

  public function check_is_cancel($no,$tbl){
    $status=1;
    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];

    $this->db->select(array('is_cancel'));
    $this->db->where('nno',$no);
    $this->db->where('cl',$cl);
    $this->db->where('bc',$bc);
    $result=$this->db->get($tbl);
    foreach ($result-> result() as $row) {
     $is_cancel=$row->is_cancel;     
     if($is_cancel==1){
      $status=0;
    }
  }
  return $status;
}

public function check_is_group($group_id){
  $status=1;
  $date=date("Y-m-d");
  $sql="SELECT code FROM r_groups WHERE `fdate` <= '$date' AND '$date' <= `tdate` AND inactive='0' AND code='$group_id'";
  if($this->db->query($sql)->num_rows()>0){
    return $status;
  }
  return "Invalid group no, Please check the group no";      
}





public function check_is_serial_items($code){
 $this->db->select(array('serial_no'));
 $this->db->where("code",$code);
 $this->db->limit(1);
 $result=$this->db->get("m_item");
 foreach($result->result() as $row){
  return $row->serial_no;
}
}

public function batch_update($item_pre,$batch_pre,$qty_pre,$foc_pre){
  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];
  $store=$_POST['stores'];

  for($x = 0; $x<25; $x++){
    if(isset($_POST[$item_pre.$x]) && !empty($_POST[$item_pre.$x])) {
      $batch_no=$_POST[$batch_pre.$x];
      $item=$_POST[$item_pre.$x];
      $quantity=(int)$_POST[$qty_pre.$x];
      if(isset($_POST[$foc_pre.$x])){
        $foc=(int)$_POST[$foc_pre.$x];
      }else{
        $foc=(int)0;
      }    				
      $total_qty=$quantity+$foc;
      $no=$_POST['hid'];

      switch($_POST['transtype']){
        case 'CASH':
        $table='t_cash_sales_det';
        break;  
        case 'CREDIT':
        $table='t_credit_sales_det';
        break;
        case '17':
        $table='t_credit_note_trans';
        $trans_det="credit note";
        break;
        case '3':
        $table='t_sup_settlement';
        $trans_det="purchase";
        break;
        case 'PURCHASE RETURN':
        $table='t_pur_ret_det';
        break;
        default:$table="";
        break;   
      }


      if(isset($_POST['hid']) && $_POST['hid']=="0"){
        $sql="SELECT IFNULL(qty,0) as qty FROM m_item_batch_stock WHERE batch_no='$batch_no' AND store_code='$store' AND item_code='$item' AND cl='$cl' and bc='$bc'";

        $query=$this->db->query($sql);
        if($query->num_rows()>0){
          $actual_qty=(int)$query->first_row()->qty; 
        }else{
          $actual_qty=(int)0;
        }

        if($actual_qty<$total_qty){
         $status="This (".$item.") does not have enough quantity in this batch(".$batch_no.")";
       }

     }else{

      if($_POST['transtype']=="PURCHASE RETURN"){
        $sql="SELECT qty FROM m_item_batch_stock WHERE batch_no='$batch_no' AND store_code='$store' AND item_code='$item' AND cl='$cl' and bc='$bc'";
      }else{
        $sql="SELECT ifnull(SUM(m_item_batch_stock.`qty`)+SUM(c.qty),0) as qty FROM (`m_item_batch_stock`)  
        INNER JOIN (SELECT qty,code,batch_no,cl,bc FROM $table WHERE  `batch_no` = '$batch_no'  AND  nno='$no' AND `code` = '$item') c 
        ON c.code=m_item_batch_stock.`item_code` AND c.cl=m_item_batch_stock.cl AND c.bc=m_item_batch_stock.bc AND c.batch_no=m_item_batch_stock.batch_no
        WHERE m_item_batch_stock.`batch_no` = '$batch_no' AND m_item_batch_stock.`store_code` = '$store' AND `item_code` = '$item'
        ";
      }

      $query=$this->db->query($sql);
      if($query->num_rows()>0){
        $actual_qty=(int)$query->first_row()->qty; 
      }else{
        $actual_qty=(int)0;
      }
      if($actual_qty<$total_qty){
        $status="This (".$item.") does not have enough quantity in this batch(".$batch_no.")";
      }
    }
  }
}
return $status;
}

public function is_batch_item($code){
  $this->db->select(array('batch_item'));
  $this->db->where("code",$code);
  $this->db->limit(1);
  $result=$this->db->get("m_item");
  foreach($result->result() as $row){
   return $row->batch_item;
 }
}

public function purchase_requisition_status(){
  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];

  $this->db->where('cl',$this->sd['cl']);
  $this->db->where('bc',$this->sd['branch']);
  $this->db->where('nno',$_POST['id']);
  $result=$this->db->get('t_req_sum')->first_row()->is_level_2_approved;

  if($result=='0'){
    return $status;
  }else{
    return 0;
  }
}

public function purchase_requisition_approve_status(){
  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];

  $this->db->where('cl',$this->sd['cl']);
  $this->db->where('bc',$this->sd['branch']);
  $this->db->where('nno',$_POST['id']);
  $result=$this->db->get('t_req_sum')->first_row()->is_level_1_approved;

  if($result==0){
    return $status;
  }else{
    return 0;
  }
}

public function purchase_requisition_save_after_approve(){
  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];

  $this->db->where('cl',$this->sd['cl']);
  $this->db->where('bc',$this->sd['branch']);
  $this->db->where('nno',$_POST['id']);
  $result=$this->db->get('t_req_sum')->num_rows();

  if($result>0){
    return $status;
  }else{
    return 0;
  }
}




public function purchase_req_approve_status($code){
  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];

  $this->db->where('cl',$this->sd['cl']);
  $this->db->where('bc',$this->sd['branch']);
  $this->db->where('nno',$code);
  $result=$this->db->get('t_req_approve_sum')->first_row()->is_ordered;

  if($result=='0'){
    return $status;
  }else{
    return 0;
  }
}

public function purchase_order_status($code){
  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];

  $this->db->where('cl',$this->sd['cl']);
  $this->db->where('bc',$this->sd['branch']);
  $this->db->where('po_no',$code);
  $result=$this->db->get('t_grn_sum')->num_rows();

  if($result>0){
    return $status;
  }else{
    return 0;
  }
}



public function purchase_update_status(){
 $status=1;
 $cl=$this->sd['cl'];
 $bc=$this->sd['branch'];
 $trans_no=$_POST['trans_no'];
 $item=$_POST['item'];


 $sql="SELECT COUNT(*) as records FROM t_sup_settlement WHERE sub_trans_code<>'3' AND trans_code='3' AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc'";
 foreach($this->db->query($sql)->result() as $row){
  $records=(int)$row->records;
}


if($records!=0){
 $status="sup settled";
}

if($status==1){
  $sql="SELECT COUNT(*) as records FROM t_debit_note_trans WHERE trans_no IN (SELECT drn_no FROM t_pur_ret_sum WHERE grn_no='$trans_no' AND cl='$cl' AND bc='$bc')";
  foreach($this->db->query($sql)->result() as $row){
   $records=(int)$row->records;
 }
 if($records!=0){
   $status="debit note";
 }
}

if($status==1){
 $sql="SELECT COUNT(*) as records FROM t_serial_movement_out WHERE  serial_no IN 
 (SELECT serial_no FROM t_serial_movement_out WHERE  item='$item'  AND trans_type='3'  AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc') AND trans_type<>'3'";	
 foreach($this->db->query($sql)->result() as $row){
   $records=(int)$row->records;
 }
 if($records!=0){
   $status="serial movement out";
 }
}

if($status==1){
 $sql="SELECT COUNT(*) as records FROM t_serial_movement WHERE  serial_no IN 
 (SELECT serial_no FROM t_serial_movement WHERE  item='$item'  AND trans_type='3'  AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc') AND trans_type<>'3'";	
 foreach($this->db->query($sql)->result() as $row){
   $records=(int)$row->records;
 }
 if($records!=0){
   $status=$status="serial movement";
 }
}

if($status==1){
 $sql="SELECT COUNT(*) as records FROM t_item_movement WHERE batch_no IN (SELECT batch_no FROM t_item_movement WHERE item='$item' AND trans_code='3' AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc') AND trans_code<>'3'";	
 foreach($this->db->query($sql)->result() as $row){
   $records=(int)$row->records;
 }
 if($records!=0){
   $status="batch case";
 }
}

return $status;
}


public function check_is_customer($code){
 $status=1;
 $this->db->where('code',$code);
 $count=$this->db->get('m_customer')->num_rows();
 if($count!=1){
   $status="Invalid customer,Please check the customer";
 }
 return $status;
}   

public function check_is_supplier($code){
 $status=1;
 $this->db->where('code',$code);
 $this->db->limit(1);
 $count=$this->db->get('m_supplier')->num_rows();
 if($count!=1){
  $status="Invalid supplier, Please check the supplier";
}
return $status;
}

public function check_is_supplier2($item_pre,$code){
  $status=1;
  for($x = 0; $x<25; $x++){

    $sup_code=$_POST[$code.$x];
    $item=$_POST[$item_pre.$x];

    if(isset($_POST[$item_pre.$x]) && !empty($_POST[$code.$x])){
      $this->db->where('code',$sup_code);
      $count=$this->db->get('m_supplier')->num_rows();
      if($count != 1){
       $status="Invalid supplier, Please check the supplier";
     }

     $this->db->where('code',$item);
     $count2=$this->db->get('m_item')->num_rows();
     if($count2 != 1){
      $status="Invalid item code (".$item.")" ;
    }
  }
}
return $status;
}

public function check_item_with_store($store_code,$item_pre){
  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];

  for($x = 0; $x<25; $x++){
    $item_code=$_POST[$item_pre.$x];
    $is_free_item=$this->is_free_item($item_code);
    if($is_free_item!=0){
      return $status;
    }else{
      if(isset($_POST[$item_pre.$x]) && !empty($_POST[$item_pre.$x])){
        $this->db->where('bc',$bc);
        $this->db->where('cl',$cl);
        $this->db->where('store_code',$store_code);
        $this->db->where('item',$item_code);
        $count=$this->db->get('t_item_movement')->num_rows();
        if($count==0){
          $status="Invalid item( ".$item_code. ") with stores";
        }   
      }
    }
  }
  return $status; 
}

public function check_item_with_store_transfer($store_code,$item_pre){
 $status=1;
 $cl=$this->sd['cl'];
 $bc=$this->sd['branch'];

 for($x = 0; $x<25; $x++){
  $item_code=$_POST[$item_pre.$x];
  $is_free_item=$this->is_free_item($item_code);
  if($is_free_item!=0){
    return $status;
  }else{
    if(isset($_POST[$item_pre.$x]) && !empty($_POST[$item_pre.$x])){

      $this->db->where('store_code',$store_code);
      $this->db->where('item',$item_code);
      $count=$this->db->get('t_item_movement')->num_rows();
      if($count==0){
        $status="Invalid item( ".$item_code. ") with stores";
      }   
    }
  }
}
return $status; 
}

public function is_free_item($item){
  $sql="SELECT COUNT(*) AS record FROM m_item_free f INNER JOIN m_item_free_det d ON  d.`nno`=f.`nno`
  WHERE d.`item`='$item' AND inactive='0'
  AND '".$_POST['date']."' BETWEEN f.`date_from` AND f.`date_to` LIMIT 1";
  return $this->db->query($sql)->first_row()->record;
}



public function check_min_price($item_pre,$price_pre,$free_price=0){
  $status=1;

  for($x = 0; $x<25; $x++){
    if(isset($_POST[$item_pre.$x])){
      if(isset($_POST[$free_price.$x])){
        $free = $_POST[$free_price.$x];
      }else{
        $free=0;
      }

      if($free==1){
        return $status;
      }else{
        $item_code=$_POST[$item_pre.$x];
        $price=(float)$_POST[$price_pre.$x];
        $this->db->select(array('min_price'));
        $this->db->where('code',$item_code);
        $query=$this->db->get('m_item');
        if($query->num_rows()>0){
          foreach($query->result() as $row){
            $min_price=(float)$row->min_price;
            if($price<$min_price){
              $status="This ".$item_code." already reached minimum price (Rs ".$min_price."/=)";
            }            
          }
        } 
      } 
    }
  }
  return $status; 
}



public function check_min_price2($item_pre,$price_pre,$free_price=0,$discount,$is_free,$bt){
  $status=1;

  for($x = 0; $x<25; $x++){
    if($_POST[$item_pre.$x]!=""){
      if(isset($_POST[$is_free.$x])){
        $free = $_POST[$is_free.$x];
      }else{
        $free=0;
      }

      if($free==1){
        return $status;
      }else{

        $is_batch="SELECT batch_item FROM m_item WHERE code ='".$_POST[$item_pre.$x]."' LIMIT 1";
        $b_query = $this->db->query($is_batch)->result();

        $item_code=$_POST[$item_pre.$x];
        $discountt=$_POST[$discount.$x];
        $batch = $_POST[$bt.$x];

        $price=(float)$_POST[$price_pre.$x]-(float)$_POST[$discount.$x];

        $this->db->select(array('min_price'));
        $this->db->where("item",$item_code);
        $this->db->where("batch_no",$batch);
        $this->db->limit(1);
        $query=$this->db->get("t_item_batch");

        if($query->num_rows()>0){
          foreach($query->result() as $row){
            $min_price=(float)$row->min_price;

            if($price<$min_price){
              $status="This ".$item_code." already reached minimum price (Rs ".$min_price."/=)";
            }            
          }
        } 
      } 
    }
  }
  return $status; 
}

public function check_is_employer($code){
  $status=1;
  $this->db->where('code',$code);
  $count=$this->db->get('m_employee')->num_rows();
  if($count!=1){
    $status=0;
  }
  return $status;
}

public function payment_option_validation($type,$col){
  $this->db->select(array($col));
  $this->db->where('type',$type);
  $query=$this->db->get('r_payment_option');
  foreach($query->result() as $row){
    return $row->$col;
  }
} 


public function check_is_account($code){
  $status=1;
  $this->db->where('code',$code);
  $count=$this->db->get('m_account')->num_rows();
  if($count!=1){
    $status="Invalid account";
  }
  return $status;
}

public function check_is_priviledge_card($code){
  $status=1;
  $this->db->where('card_no',$code);
  $count=$this->db->get('t_loyalty_card')->num_rows();
  if($count<0){
    $status="Invalid previledge card";
  }
  return $status;
}

public function check_multi_payment(){

  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];
  if(! isset($_POST['is_multi_branch'])){ $_POST['is_multi_branch'] = 0; }else{$_POST['is_multi_branch'] = 1;}
  if($_POST['is_multi_branch'] == 0){
    for($x=0;$x<100;$x++){
      if(!empty($_POST['cl0_'.$x])){

        if($_POST['cl0_'.$x]!=$cl || $_POST['bc0_'.$x]!=$bc){
          return "Invalid Branch Payment";
        }
      }
    }
  }
  return $status;
}


public function payment_option_calculation(){
  $status=1;
  if(isset($_POST['load_opt'])){
    if(!empty($_POST['load_opt']) && $_POST['load_opt']=='1'){
      $cash=(float)$_POST['hid_cash'];
      $cheque_issue=(float)$_POST['hid_cheque_issue'];
      $cheque_recieve=(float)$_POST['hid_cheque_recieve'];
      $credit_card=(float)$_POST['hid_credit_card'];
      $credit_note=(float)$_POST['hid_credit_note'];
      $debit_note=(float)$_POST['hid_debit_note'];
      $bank_debit=(float)$_POST['hid_bank_debit'];
      $discount=(float)$_POST['hid_discount'];
      $advance=(float)$_POST['hid_advance'];
      $gv=(float)$_POST['hid_gv'];
      $credit=(float)$_POST['hid_credit'];
      $pc=(float)$_POST['hid_pc'];
      $installment=(float)$_POST['hid_installment'];
      $net=(float)$_POST['net'];
      if(isset($_POST['pdchq'])){
        $pd_chq=(float)$_POST['pdchq'];
      }else{
        $pd_chq=(float)0;
      }
      $total=$cash+$cheque_issue+$cheque_recieve+$credit_card+$credit_note+$debit_note+$bank_debit+$advance+$discount+$gv+$credit+$pc+$installment+$pd_chq;

      $total_cheque_issue=0;
      $total_cheque_recieve=0;
      $total_credit_card=0;
      $total_credit_note=0;
      $total_debit_note=0;
      $total_bank_debit=0;
      $total_advance=0;
      $total_gv=0;
      $total_pc=0;
      $total_installment=0;
      $total_pd_chq=0;

      for($x=0;$x<25;$x++){

        if(isset($_POST['amount7_'.$x]) && !empty($_POST['amount7_'.$x])){
          if(isset($_POST['bank7_'.$x]) && !empty($_POST['bank7_'.$x])){
                  //if($this->check_is_valid_bank($_POST['bank7_'.$x])){              
            if(isset($_POST['chqu7_'.$x]) && !empty($_POST['chqu7_'.$x])){
              if(isset($_POST['cdate7_'.$x]) && !empty($_POST['cdate7_'.$x])){
                $total_cheque_issue=$total_cheque_issue+(float)$_POST['amount7_'.$x];
              }else{
                $status=($status!=1)?$status:"Invalid date in cheque issue";
              }
            }else{
              $status=($status!=1)?$status:"Invalid cheque no in cheque issue";
            }
                  //}else{
                  //  $status=($status!=1)?$status:"Invalid bank code in cheque issue";
                  //}
          }else{
            $status=($status!=1)?$status:"Invalid bank code in cheque issue";
          }    
        }

        if(isset($_POST['amount9_'.$x]) && !empty($_POST['amount9_'.$x])){
          if(isset($_POST['bank9_'.$x]) && !empty($_POST['bank9_'.$x]) && isset($_POST['branch9_'.$x]) && !empty($_POST['branch9_'.$x]) ){
            if($this->check_is_valid_bank_with_branch($_POST['bank9_'.$x],$_POST['branch9_'.$x])){              
              if(isset($_POST['acc9_'.$x]) && !empty($_POST['acc9_'.$x])){
                if(isset($_POST['cheque9_'.$x]) && !empty($_POST['cheque9_'.$x])){
                  if(isset($_POST['date9_'.$x]) && !empty($_POST['date9_'.$x])){
                    $total_cheque_recieve=$total_cheque_recieve+(float)$_POST['amount9_'.$x];
                  }else{
                    $status=($status!=1)?$status:"Invalid date in cheque recieve";  
                  }
                }else{
                  $status=($status!=1)?$status:"Invalid cheque no in cheque recieve";
                }
              }else{
                $status=($status!=1)?$status:"Invalid account no in cheque recieve";
              }
            }else{
              $status=($status!=1)?$status:"Invalid bank with branch in cheque recieve";
            }
          }else{
            $status=($status!=1)?$status:"Invalid bank with branch in cheque recieve";
          }  
        }

        if(isset($_POST['pdamount9_'.$x]) && !empty($_POST['pdamount9_'.$x])){
          if(isset($_POST['pdbank9_'.$x]) && !empty($_POST['pdbank9_'.$x]) && isset($_POST['pdbranch9_'.$x]) && !empty($_POST['pdbranch9_'.$x]) ){
            if($this->check_is_valid_bank_with_branch($_POST['pdbank9_'.$x],$_POST['pdbranch9_'.$x])){              
              if(isset($_POST['pdacc9_'.$x]) && !empty($_POST['pdacc9_'.$x])){
                if(isset($_POST['pdcheque9_'.$x]) && !empty($_POST['pdcheque9_'.$x])){
                  if(isset($_POST['pddate9_'.$x]) && !empty($_POST['pddate9_'.$x])){
                    $total_pd_chq=$total_pd_chq+(float)$_POST['pdamount9_'.$x];
                  }else{
                    $status=($status!=1)?$status:"Invalid date in cheque recieve";  
                  }
                }else{
                  $status=($status!=1)?$status:"Invalid cheque no in cheque recieve";
                }
              }else{
                $status=($status!=1)?$status:"Invalid account no in cheque recieve";
              }
            }else{
              $status=($status!=1)?$status:"Invalid bank with branch in cheque recieve";
            }
          }else{
            $status=($status!=1)?$status:"Invalid bank with branch in cheque recieve";
          }  
        }


        if(isset($_POST['amount1_'.$x]) && !empty($_POST['amount1_'.$x])){
          if(isset($_POST['type1_'.$x]) && !empty($_POST['type1_'.$x])){
            if(isset($_POST['no1_'.$x]) && !empty($_POST['no1_'.$x])){
              if($this->check_is_valid_credit_card_type($_POST['type1_'.$x])){
                $total_credit_card=$total_credit_card+(float)$_POST['amount1_'.$x];  
              }else{
                $status=($status!=1)?$status:"Please enter valid credit card type";
              }
            }else{
              $status=($status!=1)?$status:"Please enter valid number in credit card";  
            }
          }else{
            $status=($status!=1)?$status:"Please enter valid credit card type";
          }
        }

        if(isset($_POST['settle2_'.$x]) && !empty($_POST['settle2_'.$x])){
          if(isset($_POST['no2_'.$x]) && !empty($_POST['no2_'.$x])){
                  //if(isset($_POST['date2_'.$x]) && !empty($_POST['date2_'.$x])){
            if(isset($_POST['amount2_'.$x]) && !empty($_POST['amount2_'.$x])){
              if(isset($_POST['balance2_'.$x]) && !empty($_POST['balance2_'.$x])){
                $total_credit_note=$total_credit_note+(float)$_POST['settle2_'.$x];
              }else{
                $status=($status!=1)?$status:"Invalid balance in credit note";
              }
            }else{
              $status=($status!=1)?$status:"Invalid amount in credit note";
            }
                  //}else{
                   // $status=($status!=1)?$status:"Invalid date in credit note";
                  //}
          }else{
            $status=($status!=1)?$status:"Invalid no in credit note";
          }
        }

        if(isset($_POST['settle3_'.$x]) && !empty($_POST['settle3_'.$x])){
          if(isset($_POST['no3_'.$x]) && !empty($_POST['no3_'.$x])){
                  //if(isset($_POST['date3_'.$x]) && !empty($_POST['date3_'.$x])){
            if(isset($_POST['amount3_'.$x]) && !empty($_POST['amount3_'.$x])){
              if(isset($_POST['balance3_'.$x]) && !empty($_POST['balance3_'.$x])){
                $total_debit_note=$total_debit_note+(float)$_POST['settle3_'.$x];
              }else{
                $status=($status!=1)?$status:"Invalid balance in debit note";
              }
            }else{
              $status=($status!=1)?$status:"Invalid amount in debit note";
            }
                  //}else{
                    //$status=($status!=1)?$status:"Invalid date in debit note";
                  //}
          }else{
            $status=($status!=1)?$status:"Invalid no in debit note";
          }
        }

        if(isset($_POST['amount4_'.$x]) && !empty($_POST['amount4_'.$x])){
          if(isset($_POST['code4_'.$x]) && !empty($_POST['code4_'.$x])){
            if($this->check_is_account($_POST['code4_'.$x])){
              $total_bank_debit=$total_bank_debit+(float)$_POST['amount4_'.$x];  
            }else{
              $status=($status!=1)?$status:"Please enter valid account in bank debit";
            }

          }else{
            $status=($status!=1)?$status:"Invalid account in bank debit";
          }
        }



        if(isset($_POST['cdate6_'.$x]) && !empty($_POST['cdate6_'.$x])){
          if(isset($_POST['no6_'.$x]) && !empty($_POST['no6_'.$x])){
            if(isset($_POST['date6_'.$x]) && !empty($_POST['date6_'.$x])){
              if(isset($_POST['amount6_'.$x]) && !empty($_POST['amount6_'.$x])){
                if(isset($_POST['balance6_'.$x]) && !empty($_POST['balance6_'.$x])){
                  $total_advance=$total_advance+(float)$_POST['cdate6_'.$x];
                }else{
                  $status=($status!=1)?$status:"Invalid balance in advance note";
                }
              }else{
                $status=($status!=1)?$status:"Invalid amount in advance note";
              }
            }else{
              $status=($status!=1)?$status:"Invalid date in advance note";
            }
          }else{
            $status=($status!=1)?$status:"Invalid no in advance note";
          }
        }



        if(isset($_POST['amount5_'.$x]) && !empty($_POST['amount5_'.$x])){
          if(isset($_POST['type5_'.$x]) && !empty($_POST['type5_'.$x])){
            $total_gv=$total_gv+(float)$_POST['amount5_'.$x];

          }else{
            $status=($status!=1)?$status:"Invalid gift voucher";
          }
        }

      }

      if(isset($_POST['amount8_0']) && !empty($_POST['amount8_0'])){
        if($this->check_is_priviledge_card($_POST['type8_0'])=='1'){
         $total_pc=(float)$_POST['amount8_0'];
       }else{
        $status=($status!=1)?$status:"Invalid previledge card";
      }
    }


    if(number_format($net,2)!=number_format($total,2) ){

      $status=($status!=1)?$status:$net.' Payment, Not tally with the net-amount '.$total;
    }else if($total_cheque_issue!=$cheque_issue){
      $status=($status!=1)?$status:'Cheque issues amount is not tally with the total amount';
    }else if($total_cheque_recieve!=$cheque_recieve){
      $status=($status!=1)?$status:'Cheque recieves amount is not tally with the total amount';
    }else if($total_credit_card!=$credit_card){
      $status=($status!=1)?$status:'Credit card amount is not tally with the total amount';
    }else if($total_credit_note!=$credit_note){
      $status=($status!=1)?$status:'redit note amount is not tally with the total amount';
    }else if($total_debit_note!=$debit_note){
      $status=($status!=1)?$status:'Debit note amount is not tally with the total amount';
    }else if($total_bank_debit!=$bank_debit){
      $status=($status!=1)?$status:'Bank debit amount is not tally with the total amount';
    }else if($total_advance!=$advance){
      $status=($status!=1)?$status:'Advance amount is not tally with the total amount';
    }else if($total_gv!=$gv){
      $status=($status!=1)?$status:'Gift voucher amount is not tally with the total amount';
    }else if($pc!=$total_pc){
      $status=($status!=1)?$status:'Privilege card amount, Not tally with the total amount';
    }
  } 
}
return $status;
}
public function credit_card_validation(){
  for($x=0;$x<10;$x++){
        //if(isset($_POST['amount1_']))
  }
}

public function check_is_valid_credit_card_type($code){
  $this->db->where('card_type',$code);
  $query=$this->db->get('m_credit_card_type');
  return $query->num_rows();
}

public function check_is_valid_bank($code){
  $this->db->where('code',$code);
  $query=$this->db->get('m_bank');
  return $query->num_rows();
}

public function check_is_valid_bank_with_branch($bank,$branch){
  $this->db->where('bank',$bank);
  $this->db->where('code',$branch);
  $query=$this->db->get('m_bank_branch');
  return $query->num_rows();
}

public function check_valid_trans_no($code,$pre_trans_code,$pre_trans_no){
  $status=1;
  $trans_det="";
  $table="";
  for($x=0;$x<100;$x++){
    if(isset($_POST[$pre_trans_code.$x]) && !empty($_POST[$pre_trans_code.$x])){
      switch($_POST[$pre_trans_code.$x]){
        case '5':
        $table='t_cus_settlement';
        $trans_det="credit sales";
        break;  
        case '18':
        $table='t_debit_note_trans';
        $trans_det="debit note";
        break;
        case '17':
        $table='t_credit_note_trans';
        $trans_det="credit note";
        break;
        case '3':
        $table='t_sup_settlement';
        $trans_det="purchase";
        break;

        case '16':
        $table='t_cus_settlement';
        $trans_det="receipt";
        break;

        case '19':
        $table='t_sup_settlement';
        $trans_det="voucher";
        break;  
        case '1':
        $table='t_opening_bal_trans';
        $trans_det="opening_balance";
        break;       

        default:$table="";
        break;   
      }

      if($table!=""){
        $this->db->where('acc_code',$_POST[$code]);
        $this->db->where('trans_code',$_POST[$pre_trans_code.$x]);
        $this->db->where('trans_no',$_POST[$pre_trans_no.$x]);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $result=$this->db->get($table)->num_rows();
        if($result<=0){
          $status="Invalid ".$trans_det." no ".$_POST[$pre_trans_no.$x]; 
        } 
      }else{
        $status="Please reset page and check the transaction";
      }

    }
  }  
  return $status;
}

public function check_valid_trans_settle($code,$pre_trans_code,$pre_trans_no,$pre_balance){
  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];
  $code=$_POST[$code];
  $trans_det="";
  $table="";

  for($x=0;$x<100;$x++){
    if(isset($_POST[$pre_balance.$x]) && !empty($_POST[$pre_balance.$x]) && $_POST[$pre_balance.$x]!=""){

      $trans_code=$_POST[$pre_trans_code.$x];
      $trans_no=$_POST[$pre_trans_no.$x];

      switch($_POST[$pre_trans_code.$x]){
        case '5':
        $table='t_cus_settlement';
        $trans_det="credit sales";
        break;  
        case '18':
        $table='t_debit_note_trans';
        $trans_det="debit note";
        break;
        case '17':
        $table='t_credit_note_trans';
        $trans_det="credit note";
        break;
        case '3':
        $table='t_sup_settlement';
        $trans_det="purchase";
        break;
        case '16':
        $table='t_cus_settlement';
        $trans_det="receipt";
        break;
        case '19':
        $table='t_sup_settlement';
        $trans_det="voucher";
        break;   
        case '1':
        $table='t_opening_bal_trans';
        $trans_det="opening_balance";
        break;          

        default:$table="";
        break;   
      }
      if($_POST[$pre_trans_code.$x]=='1'){
        $sql="SELECT SUM(cr)-SUM(dr) as balance FROM $table WHERE acc_code='$code' AND trans_code='$trans_code' AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc'";
      }else{
        $sql="SELECT SUM(dr)-SUM(cr) as balance FROM $table WHERE acc_code='$code' AND trans_code='$trans_code' AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc'";
      }

      //$sql="SELECT SUM(dr)-SUM(cr) as balance FROM $table WHERE acc_code='$code' AND trans_code='$trans_code' AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc'";
      $query=$this->db->query($sql);
      $result=$query->row()->balance;

      foreach($query->result() as $row){
        $balance=(float)$_POST[$pre_balance.$x];
        $actual_balance=(float)$row->balance;
       // var_dump($balance."==".$actual_balance);
        if($balance>$actual_balance){
          $status="Invalid ".$trans_det." no ".$trans_no;   
        }
      }  
    }
  }
  return $status; 
}

public function check_valid_trans_no2($code,$pre_trans_code,$pre_trans_no,$cl,$bc){
  $status=1;
  $trans_det="";
  $table="";

  for($x=0;$x<100;$x++){
    if(isset($_POST[$pre_trans_code.$x]) && !empty($_POST[$pre_trans_code.$x])){

      switch($_POST[$pre_trans_code.$x]){
        case '5':
        $table='t_cus_settlement';
        $trans_det="credit sales";
        break;  
        case '18':
        $table='t_debit_note_trans';
        $trans_det="debit note";
        break;
        case '17':
        $table='t_credit_note_trans';
        $trans_det="credit note";
        break;
        case '3':
        $table='t_sup_settlement';
        $trans_det="purchase";
        break;
        case '131':
        $table='t_gtn_settlement';
        $trans_det="gtn";
        break;                       
        default:$table="";
        break;   
      }

      if($table!=""){
        $this->db->where('acc_code',$_POST[$code]);
        $this->db->where('trans_code',$_POST[$pre_trans_code.$x]);
        $this->db->where('trans_no',$_POST[$pre_trans_no.$x]);
        $this->db->where('cl',$_POST[$cl.$x]);
        $this->db->where('bc',$_POST[$bc.$x]);
        $this->db->limit(1);
        $result=$this->db->get($table)->num_rows();

        if($result<=0){
          $status="Invalid ".$trans_det." no ".$_POST[$pre_trans_no.$x]; 
        } 
      }else{
        $status=1;
      }

    }
  }  
  return $status;
}

public function check_valid_trans_settle2($code,$pre_trans_code,$pre_trans_no,$pre_balance,$c,$b){
  $status=1;

  $code=$_POST[$code];
  $trans_det="";
  $table="";

  for($x=0;$x<100;$x++){
    if(isset($_POST[$pre_balance.$x]) && !empty($_POST[$pre_balance.$x]) && $_POST[$pre_balance.$x]!=""){

      $trans_code=$_POST[$pre_trans_code.$x];
      $trans_no=$_POST[$pre_trans_no.$x];
      $cl=$_POST[$c.$x];
      $bc=$_POST[$b.$x];

      switch($_POST[$pre_trans_code.$x]){
        case '5':
        $table='t_cus_settlement';
        $trans_det="credit sales";
        break;  
        case '18':
        $table='t_debit_note_trans';
        $trans_det="debit note";
        break;
        case '17':
        $table='t_credit_note_trans';
        $trans_det="credit note";
        break;
        case '3':
        $table='t_sup_settlement';
        $trans_det="purchase";
        break;

        case '16':
        $table='t_cus_settlement';
        $trans_det="receipt";
        break;     
        case '131':
        $table='t_gtn_settlement';
        $trans_det="gtn";
        break;                     
        default:$table="";
        break;   
      }
      if($table!=""){
        if($_POST['hid'] == "0" || $_POST['hid'] == "")
        {

          $sql="SELECT SUM(dr)-SUM(cr) as balance FROM $table WHERE acc_code='$code' AND trans_code='$trans_code' AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc'";
        }
        else
        {
          $sql="SELECT SUM(balance) AS balance FROM(
          SELECT SUM(dr)-SUM(cr) as balance FROM $table WHERE acc_code='$code' AND trans_code='$trans_code' AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc'         
          UNION ALL 
          SELECT SUM(cr) as balance FROM $table WHERE acc_code='$code' AND trans_code='$trans_code' AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc'         
        ) AS a";
      }

      $query=$this->db->query($sql);
      $result=$query->row()->balance;

      foreach($query->result() as $row){
        $balance=(float)$_POST[$pre_balance.$x];
        $actual_balance=(float)$row->balance;

        if($balance>$actual_balance){

          $status="Invalid ".$trans_det." no ".$trans_no;   
        }
      }  

    }
  }
}
return $status; 
}

public function payment_calculation($ttl_pay,$pre_pay,$sttl,$blnc){
  $status=1;

  $total_payment=(float)$_POST[$ttl_pay];

  $settle=(float)$_POST[$sttl];

  $balance=(float)$_POST[$blnc];

  $total_pre_pay=(float)0;


  for($x=0;$x<300;$x++){      
    if ( $_POST[$pre_pay.$x] != ""){
      $total_pre_pay += (float)$_POST[$pre_pay.$x]; 
    }
  }


  if((string)$settle==(string)$total_pre_pay){
    if($total_payment!=($settle+$balance)){
      $status="Total payments (Rs: ".$total_payment.") not tally with the settle amount (Rs: ".$settle.") and balance (Rs: ".$balance.")";
    }
  }else{
    $status="Part payments (Rs: ".$total_pre_pay.") not tally with the settle amount (Rs: ".$settle.")";
  }
  return $status;
}

public function check_grn_supplier($supplier,$grn_no){
  $status=1;
  $this->db->where('cl',$this->sd['cl']);
  $this->db->where('bc',$this->sd['branch']);
  $this->db->where('nno',$grn_no);
  $this->db->where('supp_id',$supplier);
  $query=$this->db->get('t_grn_sum');
  if($query->num_rows()<=0){
    $status="Please check the grn no with supplier";
  }
  return $status;
}

public function check_gift_grn_supplier($supplier,$grn_no){
  $status=1;
  $this->db->where('cl',$this->sd['cl']);
  $this->db->where('bc',$this->sd['branch']);
  $this->db->where('nno',$grn_no);
  $this->db->where('supp_id',$supplier);
  $query=$this->db->get('g_t_grn_sum');
  if($query->num_rows()<=0){
    $status="Please check the grn no with supplier";
  }
  return $status;
}

public function check_inv_customer($customer,$inv_no,$type){
  $status=1;

  if($type=='4')
  {
    $tbl="t_cash_sales_sum";
  }
  else
  {
    $tbl="t_credit_sales_sum";
  }

  $this->db->where('cl',$this->sd['cl']);
  $this->db->where('bc',$this->sd['branch']);
  $this->db->where('nno',$inv_no);
  $this->db->where('cus_id',$customer);
  $query=$this->db->get($tbl);
  if($query->num_rows()<=0){
    $status="Please check the invoice no with customer";
  }
  return $status;
}

public function check_grn_serial($grn_no,$store,$trans_no){
  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];
  $wordChunks = explode(",", $_POST['srls']);
  $execute = 0;

  if($_POST['df_is_serial']!=1){
    return 1;
  }

  for($x=0;$x<25;$x++){
    if (isset($_POST['0_'.$x])) {
      if ($this->check_is_serial_items($_POST['0_' . $x]) == 1) {
        $batch=$_POST['3_'.$x];

        $serial = $_POST['all_serial_'.$x];
        $p=explode(",",$serial);
        for($i=0; $i<count($p); $i++){

          $sql="SELECT `item` FROM `t_serial` WHERE `item` = '".$_POST['0_'.$x]."' AND `serial_no` = '".$p[$i]."' AND `store_code` = '$store' 
          AND `cl` = '$cl' AND `bc` = '$bc' AND `available` = '1' AND `trans_type` = '3' AND `trans_no` = '$grn_no' AND `batch` = '$batch'
          UNION ALL
          SELECT `item` FROM `t_serial`  WHERE `item` = '".$_POST['0_'.$x]."' AND `serial_no` = '".$p[$i]."' AND `store_code` = '$store' 
          AND `cl` = '$cl' AND `bc` = '$bc' AND `trans_type` = '3' AND `trans_no` = '$grn_no' AND `batch` = '$batch' AND out_doc='10' AND out_no='$trans_no'";
          $query=$this->db->query($sql);
//var_dump($query->num_rows()."==".$p[$i])
          if($query->num_rows()<=0){
            $status="Invalid serial no in Item ( ".$_POST['0_'.$x]." )";
          }

        }
      }
    } 
  }
  return $status;
}

public function check_invoice_serial($trans_no,$trans_type){
  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];
  $wordChunks = explode(",", $_POST['srls']);
  $execute = 0;

  if($_POST['df_is_serial']!=1){
    return 1;
  }

  for($x=0;$x<25;$x++){
    if (isset($_POST['0_' . $x])) {
      if ($this->check_is_serial_items($_POST['0_' . $x]) == 1) {
        $batch=$_POST['bt_'.$x];

        for ($i = 0; $i < count($wordChunks); $i++) {


          $p = explode("-", $wordChunks[$i]);


          if($p[0]==$_POST['0_' . $x]){

            if($_POST['hid']=='0' && $_POST['hid']==''){
              $sql="SELECT t_serial_movement_out.`serial_no`
              FROM t_serial_movement_out 
              WHERE t_serial_movement_out.`item`='$p[0]'
              AND t_serial_movement_out.`serial_no`='$p[1]' 
              AND t_serial_movement_out.`cl`='$cl' 
              AND t_serial_movement_out.`bc`='$bc' 
              AND t_serial_movement_out.trans_type='$trans_type' 
              AND t_serial_movement_out.trans_no='$trans_no'
              AND t_serial_movement_out.batch_no ='$batch'
              ";
            }else{
             $sql="SELECT t_serial_movement_out.`serial_no`
             FROM t_serial_movement_out 
             WHERE t_serial_movement_out.`item`='$p[0]'
             AND t_serial_movement_out.`serial_no`='$p[1]' 
             AND t_serial_movement_out.`cl`='$cl' 
             AND t_serial_movement_out.`bc`='$bc' 
             AND t_serial_movement_out.trans_type='$trans_type' 
             AND t_serial_movement_out.trans_no='$trans_no'
             AND t_serial_movement_out.batch_no ='$batch'
             UNION ALL 
             SELECT t_serial_movement.`serial_no`
             FROM t_serial_movement 
             WHERE t_serial_movement.`item`='$p[0]'
             AND t_serial_movement.`serial_no`='$p[1]' 
             AND t_serial_movement.`cl`='$cl' 
             AND t_serial_movement.`bc`='$bc' 
             AND t_serial_movement.trans_type='8' 
             AND t_serial_movement.trans_no='$trans_no'
             AND t_serial_movement.batch_no ='$batch' 
             ";                   
           } 

           $status = $this->db->query($sql)->num_rows();
           
           if($this->db->query($sql)->num_rows()<=0){
            $status="Invalid serial no in Item ( ".$p[0]." )";
            return $status;
          }
        }        
      }
    }
  } 
}
return $status;
}

public function check_open_stock_item_validation(){
  $status=1;

    // if($_POST['hid'] != "0" || $_POST['hid'] != ""){
    //   return $status;
    // }

  for($x=0;$x<25;$x++){
    $this->db->where('cl',$this->sd['cl']);
    $this->db->where('bc',$this->sd['branch']);
    $this->db->where('trans_code',2);
    $this->db->where('item',$_POST['0_'.$x]);
    $query=$this->db->get('t_item_movement');
    if($query->num_rows()>0){
      $detail="";
      foreach($query->result() as $row){
        $detail=$row->trans_no;
      }
      $status="This item (".$_POST['0_'.$x].") already in stock. please check no(".$detail.")";
    }
  }

  return $status;
}


public function empty_net_value($value){
  $status=1;
  $v=(float)0;

  if((Float)$value <= 0)
  {
    $status="Calculation Error!(Net value can't be 0) ";
  }
  else if($value==empty($value))
  {
    $status="Net value can't empty";
  }

  return $status;
}

public function check_is_group_store(){
  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];
  $store=$_POST['store_code'];
  $sql="SELECT COUNT(*) AS count FROM m_stores WHERE cl='$cl' AND bc='$bc' AND code='$store' AND group_sale='1' ";
  $status=$this->db->query($sql)->row()->count;
  echo $status;
}

public function check_is_free($item, $quaty, $date, $free){
 $ff=1;
 $status=1;
 $item_array=array();

 for($x=0;$x<25;$x++){

  $item_code=$_POST[$item."_".$x];
  $quantity=$_POST[$quaty."_".$x];
  $is_free=$_POST[$free."_".$x];
  $ddate= $_POST[$date];

  if($item_code!=""){
    array_push($item_array,$item_code.'-'.$quantity."-".$is_free);
  }

  $arr_count=count($item_array);  

}

for ($i=0; $i<$arr_count; $i++) {

  $arr_explode = explode('-',$item_array[$i]);   
  $sql="SELECT  m.code ,mf.qty
  FROM m_item m
  JOIN m_item_free_det mfd ON mfd.`item` = m.`code`
  JOIN m_item_free mf ON mf.`nno` = mfd.`nno`
  WHERE mf.`code`='".$arr_explode[0]."' 
  AND mf.`qty`<='".$arr_explode[1]."' 
  AND date_from < '$ddate'
  AND date_to > '$ddate'";
  $query = $this->db->query($sql);
  if($query->num_rows() > 0) {           
    foreach($query->result() as $row){
      $db_item=$row->code;
      return $this->abc($row->code,$item_array);
    }  
  } 
} 
return $status;
}


public function abc($db_item,$item_array){
  $status=1;
  $arr_count=count($item_array); 
  for ($i=0; $i<$arr_count; $i++) {
    $arr_explode = explode('-',$item_array[$i]);
    if($arr_explode[0] != $db_item && ($arr_explode[2]!="" || $arr_explode[2]!="0")){
      $status="This item ( ".$arr_explode[0]." ) is not a free issue item";          
    }
  }   
  return $status; 
}


public function sale_is_cancel($no,$types){
  $status=1;
  $type="";
  $heading="";
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];

  if($types==4){
    $type="t_cash_sales_sum";
    $heading="CASH SALE NO";
  }else if($types==5){
    $type="t_credit_sales_sum";
    $heading="CREDIT SALE NO";
  }

  $sql="SELECT is_cancel FROM $type WHERE nno='$no' AND cl='$cl' AND bc='$bc' ";

  $query = $this->db->query($sql);
  if($query->num_rows() > 0) {           
    foreach($query->result() as $row){
      $cancel=$row->is_cancel;
      if($cancel=='1'){
        $status=$heading." (".$no.") already canceled";
      }
    }
  }
  return $status;
}


public function purchase_is_cancel($no){
  $status=1;

  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];


  $sql="SELECT is_cancel FROM t_grn_sum WHERE nno='$no' AND cl='$cl' AND bc='$bc' ";

  $query = $this->db->query($sql);
  if($query->num_rows() > 0) {           
    foreach($query->result() as $row){
      $cancel=$row->is_cancel;
      if($cancel=='1'){
        $status="grn no (".$no.") already canceled";
      }
    }
  }
  return $status;
}

public function gift_purchase_is_cancel($no){
  $status=1;

  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];


  $sql="SELECT is_cancel FROM g_t_grn_sum WHERE nno='$no' AND cl='$cl' AND bc='$bc' ";

  $query = $this->db->query($sql);
  if($query->num_rows() > 0) {           
    foreach($query->result() as $row){
      $cancel=$row->is_cancel;
      if($cancel=='1'){
        $status="grn no (".$no.") already canceled";
      }
    }
  }
  return $status;
}

public function is_purchase_return($item_pre,$batch_pre,$stores,$qty_pre){

  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];


  for($x=0;$x<25;$x++){

    $item=$_POST[$item_pre.$x];
    $batch=$_POST[$batch_pre.$x];
    $qty=$_POST[$qty_pre.$x];
    $store=$_POST[$stores];


    $sql="SELECT IFNULL(qty,0) AS qty, COUNT(qty) FROM`qry_current_stock` WHERE item='$item'
    AND batch_no='$batch' 
    AND store_code='$store' 
    AND cl='$cl' AND bc='$bc'
    ";
    $query = $this->db->query($sql); 

    foreach($query->result() as $row){
      $actual=$row->qty;

      if($actual<$qty){
        $status="This item (".$item.") not enough quantity";
      }
    }
  }
  return $status;


}



public function is_gift_purchase_return($item_pre,$stores,$qty_pre){
  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];
  for($x=0;$x<25;$x++){
    $item=$_POST[$item_pre.$x];
    $qty=$_POST[$qty_pre.$x];
    $store=$_POST[$stores];

    $sql="SELECT IFNULL(qty,0) AS qty, COUNT(qty) FROM `gift_qry_current_stock` WHERE item='$item'
    AND store_code='$store' 
    AND cl='$cl' AND bc='$bc'";
    $query = $this->db->query($sql); 
    $actual = $query->first_row()->qty;

    if($actual<$qty){
      $status="This item (".$item.") not enough quantity";
    }
  }
  return $status;
}



public function request_is_approve($no){
  $status=1;

  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];


  $sql="SELECT is_level_1_approved FROM t_req_sum WHERE nno='$no' AND cl='$cl' AND bc='$bc' ";

  $query = $this->db->query($sql);
  if($query->num_rows() > 0) {           
    foreach($query->result() as $row){
      $app =$row->is_level_1_approved;
      if($app =='1'){
        $status="Purchase request no (".$no.") cann't update or cancel";
      }
    }
  }
  return $status;
}


public function request_is_approve2($no){
  $status=1;

  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];


  $sql="SELECT is_level_3_approved FROM t_req_approve_sum WHERE nno='$no' AND cl='$cl' AND bc='$bc' ";

  $query = $this->db->query($sql);
  if($query->num_rows() > 0) {           
    foreach($query->result() as $row){
      $app =$row->is_level_3_approved;
      if($app =='1'){
        $status="Purchase request no (".$no.") cann't update or cancel";
      }
    }
  }
  return $status;
}




public function check_is_account2($code,$islimit=0){
  $status=1;

  if($islimit!=0){
    for($x = 0; $x<$islimit; $x++){
      $acc_code=$_POST[$code.$x]; 
      if(isset($_POST[$code.$x]) && !empty($_POST[$code.$x])){
        $this->db->where('code',$acc_code);
        $count=$this->db->get('m_account')->num_rows();
        if($count != 1){
         $status="Invalid account, Please check the account";
       }
     }
   }
 }else{
  for($x = 0; $x<25; $x++){
    $acc_code=$_POST[$code.$x]; 
    if(isset($_POST[$code.$x]) && !empty($_POST[$code.$x])){
      $this->db->where('code',$acc_code);
      $count=$this->db->get('m_account')->num_rows();
      if($count != 1){
       $status="Invalid account, Please check the account";
     }
   }
 }        
}

return $status;
}

public function check_is_journal($code){
  $status=1;
  $this->db->where('code',$code);
  $this->db->where('payble_type','2');
  $count=$this->db->get('m_journal_type_sum')->num_rows();
  if($count!=1){
   $status="Invalid journal type,Please check the journal type";
 }
 return $status;
}  

public function check_is_payble($code){
  $status=1;
  $this->db->where('code',$code);
  $this->db->where('payble_type','1');
  $count=$this->db->get('m_journal_type_sum')->num_rows();
  if($count!=1){
   $status="Invalid payble type,Please check the payble type";
 }
 return $status;
}

public function check_is_receivable($code){
  $status=1;
  $this->db->where('code',$code);
  $this->db->where('payble_type','3');
  $count=$this->db->get('m_journal_type_sum')->num_rows();
  if($count!=1){
   $status="Invalid receivable type,Please check the receivable type";
 }
 return $status;
}
public function check_is_account3($code){
  $status=1;
  $this->db->where('code',$code);
  $count=$this->db->get('m_account')->num_rows();
  if($count != 1){
   $status="Invalid account, Please check the account";
 }
 return $status;
} 


public function check_is_item($code){
  $status=1;
  $this->db->where('code',$code);
  $count=$this->db->get('m_item')->num_rows();
  if($count!=1){
   $status="Invalid item,Please check the item code";
 }
 return $status;
}  

public function check_is_store($code){
  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];

  $this->db->where('code',$code);
  $this->db->where('cl',$cl);
  $this->db->where('bc',$bc);
  $count=$this->db->get('m_stores')->num_rows();

  if($count!=1){
   $status="Invalid store,Please check the store code";
 }
 return $status;
} 

public function sales_limit_with_customer($amount,$customer){
  $status =1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];

  $sql="SELECT cash_customer_limit,def_cash_customer FROM m_branch WHERE cl='$cl' AND  bc='$bc'"; 
  if($this->db->query($sql)->row()->def_cash_customer == $customer){
    if($this->db->query($sql)->num_rows() > 0){
      $cash_limit=$this->db->query($sql)->row()->cash_customer_limit;      
    }else{
      $cash_limit=0;
    }
    if($cash_limit<$amount){
      $status="Cash Customer Limit Over, Please create customer";
    }      
  }else{
    $status =1;
  }
  return $status;
}

public function gift_amount($net,$gift){
  $status=1;

  $net_amount = (float)$net;
  $gift_amount = (float)$gift;
    //var_dump($net_amount."<=".$gift_amount);
  if($gift_amount>"0"){
    if($net_amount>$gift_amount){
      $status="Net Value Should Be Greater Than Gift Voucher Amount (".$gift_amount."/=)";
    }
  } 
  return $status;
} 
public function gift_check_item_with_store($store_code,$item_pre){
  $status=1;
  $cl=$this->sd['cl'];
  $bc=$this->sd['branch'];

  for($x = 0; $x<25; $x++){
    $item_code=$_POST[$item_pre.$x];
    $is_free_item=$this->is_free_item($item_code);
    if($is_free_item!=0){
      return $status;
    }else{
      if(isset($_POST[$item_pre.$x]) && !empty($_POST[$item_pre.$x])){
        $this->db->where('bc',$bc);
        $this->db->where('cl',$cl);
        $this->db->where('store_code',$store_code);
        $this->db->where('item',$item_code);
        $count=$this->db->get('g_t_item_movement')->num_rows();
        if($count==0){
          $status="Invalid item( ".$item_code. ") with stores";
        }   
      }
    }
  }
  return $status; 
}

public function empty_qty($item_pre,$qty_pre,$is_free=0){
  $status=1;

  if($is_free!="0"){
    for($x=0; $x<25; $x++){
      if($_POST[$is_free.$x] > 0){
        $status = 1;
      }else{
        $item=$_POST[$item_pre.$x];
        $qty =$_POST[$qty_pre.$x];
        if($item!=""){
          if($qty=="0" or $qty==""){
            $status = "Item (".$item.") Quantity should be greater than 0";
          }
        }
      }
    }
  }else{
    for($x=0; $x<25; $x++){
      $item=$_POST[$item_pre.$x];
      $qty =$_POST[$qty_pre.$x];
      if($item!=""){
        if($qty=="0" or $qty==""){
          $status = "Item (".$item.") Quantity should be greater than 0";
        }
      }
    }
  }
  return $status;
}  

public function is_serial_exsit(){
  $status=1;
  $pop="<table id='item_list' style='width : 100%' >";
  $pop.="<tr class='tb_head_th' ><th colspan='2'>Below Serial Numbers Already Exists</th></tr>";
  $pop.="<tr>";
  $pop.="<th class='tb_head_th'>Item Code</th>";
  $pop.="<th class='tb_head_th'>Serial Number</th>";
  $pop.="</tr>";
  for($x = 0; $x<25; $x++){
    if($this->check_is_serial_items($_POST['0_'.$x])==1){
      $all_serial=$_POST['all_serial_'.$x];
      $item=$_POST['0_'.$x];
      $pp=explode(",",$all_serial);

      for($i = 0; $i < count($pp); $i++){
        $p=explode("-", $pp[$i]);
        $serial_no = $p[0];

        $sql="SELECT item FROM t_serial WHERE item='".$item."' AND serial_no='".$serial_no."' ";
        $count=$this->db->query($sql)->num_rows();

        if((int)$count>0){
          $status=2;
          $pop.="<tr class='cl'>";
          $pop.="<td>".$item."</td><td>".$serial_no."</td>";
          $pop.="</tr>";
        }

      }

    }
  }
  $pop.="</table>";
  $result=$status."|".$pop;
  echo $result;
}
}