<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class trans_cancellation extends CI_Model {
    private $sd;
    function __construct() {
        parent::__construct();
        $this->sd = $this->session->all_userdata();
    }
    
    
    
    public function cash_sales_update_status($no='no') {
        $status   = "OK";
        $trans_no = ($no=='no')?$_POST['trans_no']:$no;
        $cl       = $this->sd['cl'];
        $bc       = $this->sd['branch'];
        $sql      = "SELECT COUNT(*) as records FROM t_sales_return_sum WHERE inv_no='$trans_no'  AND sales_type='4' AND cl='$cl' AND bc='$bc' AND is_cancel='0' ";
        foreach ($this->db->query($sql)->result() as $row) {
            $records = (int) $row->records;
        }
        
        if ($records != 0) {
            return "This cash sale cannot update or cancel";
        }
        
        return $status;
    }


    public function receipt_update_status($no='no'){
        $status="OK";
        $trans_no=($no=='no')?$_POST['trans_no']:$no;
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];

        $sql="SELECT COUNT(*) as records FROM t_cus_settlement WHERE trans_code='16' AND trans_no='$trans_no' AND sub_trans_code='21' AND cl='$cl' AND bc='$bc'";
        foreach ($this->db->query($sql)->result() as $row) {
            $records = (int) $row->records;
        }
        if ($records != 0) {
            return "This receipt cannot update or cancel";
        }
        return $status;
    }

    public function voucher_update_status($no='no'){
        $status="OK";
        $trans_no=($no=='no')?$_POST['trans_no']:$no;
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];

        $sql="SELECT COUNT(*) as records FROM t_sup_settlement WHERE trans_code='19' AND trans_no='$trans_no' AND sub_trans_code='22' AND cl='$cl' AND bc='$bc'";
        foreach ($this->db->query($sql)->result() as $row) {
            $records = (int) $row->records;
        }
        if ($records != 0) {
            return "This voucher cannot update or cancel";
        }

        $sql="SELECT COUNT(*) as records FROM `t_cheque_issued` WHERE trans_code='19' AND trans_no='$no' AND ststus='D'  AND cl='$cl' AND bc='$bc'";
        $num=(int)$this->db->query($sql)->row()->records;
        if($num!=0){
            return "This Cheque Already Withdrawed";
        }
        return $status;
    }




    public function credit_sales_update_status($no='no') {

        $status="OK";
        $trans_no=($no=='no')?$_POST['trans_no']:$no;
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];

        $sql="SELECT COUNT(*) as records FROM t_sales_return_sum WHERE inv_no='$trans_no' AND sales_type='5'  AND cl='$cl' AND bc='$bc' ";
        foreach ($this->db->query($sql)->result() as $row) {
            $records = (int) $row->records;
        }
        
        if($records != 0){
            return "This credit sale cannot update or cancel";
        }

        $sql="SELECT COUNT(*) AS records FROM t_cus_settlement WHERE trans_code = '5' AND trans_no = '$trans_no' AND sub_trans_code != '5' AND sub_trans_no != '$trans_no' AND cl='$cl' AND bc='$bc'";
        foreach ($this->db->query($sql)->result() as $row) {
            $records = (int) $row->records;
        }

        if($records != 0){
            return "This credit sale cannot update or cancel";
        }

        $sql="SELECT COUNT(*) AS records FROM t_ins_trans WHERE sub_trans_code != '5' AND sub_trans_no != '$trans_no' AND cl='$cl' AND bc='$bc'";
        foreach ($this->db->query($sql)->result() as $row) {
            $records = (int) $row->records;
        }

        if($records != 0){
            return "This credit sale cannot update or cancel";
        }

        return $status;
    }

    public function branch_advance_status($no='no'){
        $status="OK";
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $trans_no=($no=='no')?$_POST['trans_no']:$no;
        $sql="SELECT COUNT(*) as records FROM `t_credit_note_trans_branch` WHERE trans_code='134' AND trans_no='$trans_no' AND sub_trans_code!='134'  AND cl='$cl' AND bc='$bc'";
        $num=(int)$this->db->query($sql)->row()->records;
        if($num!=0){
            return "This advance no [".$trans_no."] cannot update or cancel";
        }
        return $status;
    }
    
    
    public function purchase_update_status($no='no') {
        $status = "OK";
        $cl     = $this->sd['cl'];
        $bc     = $this->sd['branch'];
        $trans_no=($no=='no')?$_POST['trans_no']:$no;
        $this->db->select(array(
            'code'
        ));
        
        
        $sql = "SELECT COUNT(*) as records FROM t_sup_settlement WHERE sub_trans_code<>'3' AND trans_code='3' AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc'";
        foreach ($this->db->query($sql)->result() as $row) {
            $records = (int) $row->records;
        }
        
        if ($records != 0) {
            // $status="This purchase has been already settled";
            return "This purchase cannot update or cancel";
        }
        
        if ($status == "OK") {
            $sql = "SELECT COUNT(*) as records FROM t_debit_note_trans WHERE trans_no IN (SELECT drn_no FROM t_pur_ret_sum WHERE grn_no='$trans_no' AND cl='$cl' AND bc='$bc') AND cl='$cl' AND bc='$bc'";
            foreach ($this->db->query($sql)->result() as $row) {
                $records = (int) $row->records;
            }
            if ($records != 0) {
                // $status="This purchase has a purchase return";
                return "This purchase cannot update or cancel";
            }
        }

        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('nno', $trans_no);
        $query = $this->db->get('t_grn_det');
        
        foreach ($query->result() as $row) {

            $code = $row->code;
            if ($status == "OK") {
                $sql = "SELECT COUNT(*) as records FROM t_serial_movement_out WHERE  serial_no IN 
                (SELECT serial_no FROM t_serial_movement_out WHERE  item='$code'  AND trans_type='3'  AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc') AND cl='$cl' AND bc='$bc' AND trans_type<>'3'";
                foreach ($this->db->query($sql)->result() as $row) {
                    $records = (int) $row->records;
                }
                if ($records != 0) {
                    //  $status="serial movement out";
                    return "This purchase cannot update or cancel1";
                }
            }
            
            if ($status == "OK") {
                $sql = "SELECT COUNT(*) as records FROM t_serial_movement WHERE  serial_no IN 
                (SELECT serial_no FROM t_serial_movement WHERE  item='$code'  AND trans_type='3'  AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc') AND cl='$cl' AND bc='$bc' AND trans_type<>'3'";
                foreach ($this->db->query($sql)->result() as $row) {
                    $records = (int) $row->records;
                }
                if ($records != 0) {
                    // $status=$status="serial movement";
                    return "This purchase cannot update or cancel2";
                }
            }
            
           /* if ($status == "OK") {
                $sql = "SELECT COUNT(*) as records FROM t_item_movement WHERE batch_no IN (SELECT batch_no FROM t_item_movement WHERE item='$code' AND trans_code='3' AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc') AND cl='$cl' AND bc='$bc' AND item='$code' AND trans_code<>'3'";
                foreach ($this->db->query($sql)->result() as $row) {
                    $records = (int) $row->records;
                }
                if ($records != 0) {
                    // $status="batch case";
                    return "This purchase cannot update or cancel3";
                }
            }*/
        }
        
        return $status;
    }


    public function purchase_update_status_gift($no='no') {
        $status = "OK";
        $cl     = $this->sd['cl'];
        $bc     = $this->sd['branch'];
        $trans_no=($no=='no')?$_POST['trans_no']:$no;
        $this->db->select(array(
            'code'
        ));
        
        
        $sql = "SELECT COUNT(*) as records FROM t_sup_settlement WHERE sub_trans_code<>'60' AND trans_code='60' AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc'";
        foreach ($this->db->query($sql)->result() as $row) {
            $records = (int) $row->records;
        }
        
        if ($records != 0) {
            // $status="This purchase has been already settled";
            return "This purchase cannot update or cancel";
        }
        
        if ($status == "OK") {
            $sql = "SELECT COUNT(*) as records FROM t_debit_note_trans WHERE trans_no IN (SELECT drn_no FROM t_pur_ret_sum WHERE grn_no='$trans_no' AND cl='$cl' AND bc='$bc') AND cl='$cl' AND bc='$bc'";
            foreach ($this->db->query($sql)->result() as $row) {
                $records = (int) $row->records;
            }
            if ($records != 0) {
                // $status="This purchase has a purchase return";
                return "This purchase cannot update or cancel";
            }
        }

        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('nno', $trans_no);
        $query = $this->db->get('g_t_grn_det');
        
        foreach ($query->result() as $row) {

            $code = $row->code;
            if ($status == "OK") {
                $sql = "SELECT COUNT(*) as records FROM g_t_serial_movement_out WHERE  serial_no IN 
                (SELECT serial_no FROM g_t_serial_movement_out WHERE  item='$code'  AND trans_type='60'  AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc') AND cl='$cl' AND bc='$bc' AND trans_type<>'60'";
                foreach ($this->db->query($sql)->result() as $row) {
                    $records = (int) $row->records;
                }
                if ($records != 0) {
                    //  $status="serial movement out";
                    return "This purchase cannot update or cancel";
                }
            }
            
            if ($status == "OK") {
                $sql = "SELECT COUNT(*) as records FROM g_t_serial_movement WHERE  serial_no IN 
                (SELECT serial_no FROM g_t_serial_movement WHERE  item='$code'  AND trans_type='60'  AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc') AND cl='$cl' AND bc='$bc' AND trans_type<>'60'";
                foreach ($this->db->query($sql)->result() as $row) {
                    $records = (int) $row->records;
                }
                if ($records != 0) {
                    // $status=$status="serial movement";
                    return "This purchase cannot update or cancel";
                }
            }
            
        /*    if ($status == "OK") {
                $sql = "SELECT COUNT(*) as records FROM g_t_item_movement WHERE item IN (SELECT item FROM g_t_item_movement WHERE item='$code' AND trans_code='60' AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc') AND cl='$cl' AND bc='$bc' AND item='$code' AND trans_code<>'60'";
                foreach ($this->db->query($sql)->result() as $row) {
                    $records = (int) $row->records;
                }
                if ($records != 0) {
                    // $status="batch case";
                    return "This purchase cannot update or cancel";
                }
            }*/
        }
        
        return $status;
    }



    public function open_stock_update_status($no='no') {
        $status = "OK";
        $cl     = $this->sd['cl'];
        $bc     = $this->sd['branch'];
        $trans_no=($no=='no')?$_POST['trans_no']:$no;
        
        $this->db->select(array(
            'item_code'
        ));

        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc', $this->sd['branch']);
        $this->db->where('nno', $trans_no);
        $query = $this->db->get('t_op_stock_det');
        
        foreach ($query->result() as $row) {

            $code = $row->item_code;
            if ($status == "OK") {
                $sql = "SELECT COUNT(*) as records FROM t_serial_movement_out WHERE  serial_no IN 
                (SELECT serial_no FROM t_serial_movement_out WHERE  item='$code'  AND trans_type='2'  AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc') AND cl='$cl' AND bc='$bc' AND trans_type<>'2'";
                foreach ($this->db->query($sql)->result() as $row) {
                    $records = (int) $row->records;
                }
                if ($records != 0) {
                    //  $status="serial movement out";
                    return "This open stock cannot update or cancel";
                }
            }
            
            if ($status == "OK") {
                $sql = "SELECT COUNT(*) as records FROM t_serial_movement WHERE  serial_no IN 
                (SELECT serial_no FROM t_serial_movement WHERE  item='$code'  AND trans_type='2'  AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc') AND cl='$cl' AND bc='$bc' AND trans_type<>'2'";
                foreach ($this->db->query($sql)->result() as $row) {
                    $records = (int) $row->records;
                }
                if ($records != 0) {
                    // $status=$status="serial movement";
                    return "This open stock cannot update or cancel";
                }
            }
            
            if ($status == "OK") {
                $sql = "SELECT COUNT(*) as records FROM t_item_movement WHERE batch_no IN (SELECT batch_no FROM t_item_movement WHERE item='$code' AND trans_code='2' AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc') AND cl='$cl' AND bc='$bc' AND item='$code' AND trans_code<>'2'";
                foreach ($this->db->query($sql)->result() as $row) {
                    $records = (int) $row->records;
                }
                if ($records != 0) {
                    // $status="batch case";
                    return "This open stock cannot update or cancel";
                }
            }
        }
        
        return $status;
    }
    
    
    public function purchase_return_update_status($no='no') {
        $status="OK";
        $trans_no=($no=='no')?$_POST['trans_no']:$no;
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $sql="SELECT COUNT(*) as records FROM t_debit_note_trans WHERE trans_code='18' AND trans_no='$trans_no' AND sub_trans_code !='18' AND sub_trans_no !='$trans_no' AND cl='$cl' AND bc='$bc'"; 

        $this->db->query($sql);
        foreach($this->db->query($sql)->result() as $row){
            $records = (int) $row->records;
        }

        if($records != 0) {
            return "This purchase return cannot update or cancel";
        }
        
        return $status;
    }


    public function sales_return_update_status($no='no'){
        $status="OK";
        $balance="";
        $trans_no=($no=='no')?$_POST['trans_no']:$no;
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];



        $this->db->select(array('code','store','qty','crn_no'));
        $this->db->from('t_sales_return_sum');
        $this->db->join('t_sales_return_det','t_sales_return_sum.nno=t_sales_return_det.nno AND t_sales_return_sum.cl=t_sales_return_det.cl AND t_sales_return_sum.bc=t_sales_return_det.bc');
        $this->db->where('t_sales_return_det.cl', $this->sd['cl']);
        $this->db->where('t_sales_return_det.bc', $this->sd['branch']);
        $this->db->where('t_sales_return_det.nno', $trans_no);
        $this->db->where('t_sales_return_sum.is_approve', '1');
        $query = $this->db->get();
        if($query->num_rows()>0){
            $crn_no=$query->row()->crn_no;
        }else{
            $crn_no=0;
        }
        $sql="SELECT COUNT(*) as records FROM t_credit_note_trans WHERE trans_code='17' AND trans_no='$crn_no' AND sub_trans_code !='17' AND sub_trans_no !='$crn_no' AND cl='$cl' AND bc='$bc'"; 

        $this->db->query($sql);
        foreach($this->db->query($sql)->result() as $row){
            $records = (int) $row->records;
        }

        if($records != 0) {
            return "This sales return cannot update or cancel";
        }

        foreach ($query->result() as $row) {
            $qty=$row->qty;   
            $code = $row->code;
            $store_code=$row->store;
            if($status == "OK") {

                $sql = "SELECT COUNT(*) as records FROM t_serial_movement_out WHERE  serial_no IN 
                (SELECT serial_no FROM t_serial_movement_out WHERE  item='$code'  AND trans_type='8'  AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc') AND cl='$cl' AND bc='$bc' AND trans_type<>'8'";
                foreach ($this->db->query($sql)->result() as $row) {
                    $records = (int) $row->records;
                }

                if($records != 0) {
                    return "This sales return cannot update or cancel";
                }
            }

            if ($status == "OK") {
                $sql = "SELECT COUNT(*) as records FROM t_serial_movement WHERE  serial_no IN 
                (SELECT serial_no FROM t_serial_movement WHERE  item='$code'  AND trans_type='8'  AND trans_no='$trans_no' AND cl='$cl' AND bc='$bc') AND cl='$cl' AND bc='$bc' AND trans_type<>'8'";
                foreach ($this->db->query($sql)->result() as $row) {
                    $records = (int) $row->records;
                }

                if ($records != 0) {
                    return "This sales return cannot update or cancel";
                }
            }

            if ($status == "OK") {
                $sql = "SELECT COUNT(*) AS records ,qty-$qty AS balance FROM qry_current_stock WHERE item='$code' AND cl='$cl' AND bc='$bc' AND store_code='$store_code' HAVING balance>0";

                foreach ($this->db->query($sql)->result() as $row) {
                    $balance = (int) $row->balance;
                }

                if ($balance<0) {
            // $status="batch case";
                    return "This sales return cannot update or cancel";
                }
            }
        }

        return $status;

    }

    public function debit_note_status($no='no'){
        $status="OK";
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $trans_no=($no=='no')?$_POST['trans_no']:$no;
        $sql="SELECT COUNT(*) as records FROM `t_debit_note_trans` WHERE trans_code='18' AND trans_no='$trans_no' AND sub_trans_code!='18'  AND cl='$cl' AND bc='$bc'";
        $num=(int)$this->db->query($sql)->row()->records;
        if($num!=0){
            return "This debit note no [".$trans_no."] cannot update or cancel";
        }
        return $num;     

    }

    public function credit_note_status($no='no'){
        $status="OK";
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $trans_no=($no=='no')?$_POST['trans_no']:$no;
        $sql="SELECT COUNT(*) as records FROM `t_credit_note_trans` WHERE trans_code='17' AND trans_no='$trans_no' AND sub_trans_code!='17'  AND cl='$cl' AND bc='$bc'";
        $num=(int)$this->db->query($sql)->row()->records;
        if($num!=0){
            return "This credit note no [".$trans_no."] cannot update or cancel";
        }
        return $status;
    }


    public function advance_status($no='no'){
        $status="OK";
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $trans_no=($no=='no')?$_POST['trans_no']:$no;
        $sql="SELECT COUNT(*) as records FROM `t_credit_note_trans` WHERE trans_code='17' AND trans_no='$trans_no' AND sub_trans_code!='17'  AND cl='$cl' AND bc='$bc'";
        $num=(int)$this->db->query($sql)->row()->records;
        if($num!=0){
            return "This advance no [".$trans_no."] cannot update or cancel";
        }
        return $status;
    }

    public function damage_update_status($id,$trans_code,$table_sum,$table_det){

      $status=1;

      $this->db->select(array('code','qty as q','batch_no','store_to',$table_sum.'.nno'));
      $this->db->from($table_det);
      $this->db->join($table_sum, $table_det.'.nno='.$table_sum.'.nno');
      $this->db->where($table_sum.'.nno',$id);
      $this->db->where($table_sum.'.cl',$this->sd['cl']);
      $this->db->where($table_sum.'.bc',$this->sd['branch']);
      $query2 = $this->db->get();

      foreach($query2->result() as $row){

        $this->db->select(array('qty'));
        $this->db->where('item',$row->code);
        $this->db->where('batch_no',$row->batch_no);
        $this->db->where('store_code',$row->store_to);
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $query=$this->db->get('qry_current_stock');

        $r_qty=(float)$row->q;
        $item_code=$row->code;

        if($query->num_rows()>0){
          foreach($query->result() as $row){
            $quantity=(float)$row->qty;            
            if($r_qty>$quantity){
              $status="This transaction cannot update or cancel";
          }
      }
  }
  else
  {
      $status="2";
  }
}  

foreach($query2->result() as $row){
    $this->db->select(array('t_serial.serial_no', 't_serial.available' ));
    $this->db->from('t_serial_movement');
    $this->db->join('t_serial', 't_serial.serial_no=t_serial_movement.serial_no AND t_serial.cl=t_serial_movement.cl AND t_serial.bc=t_serial_movement.bc');
    $this->db->where('t_serial_movement.trans_type',$trans_code);
    $this->db->where('t_serial_movement.trans_no',$row->nno);
    $this->db->where('t_serial_movement.item',$row->code);
    $this->db->where('t_serial_movement.cl',$this->sd['cl']);
    $this->db->where('t_serial_movement.bc',$this->sd['branch']);
    $this->db->group_by("t_serial.serial_no"); 
    $query3 = $this->db->get();

    if($query3->num_rows()>0){
      foreach($query3->result() as $row){
        $available=(int)$row->available;
        $serial=$row->serial_no;

        if($available != '1'){
          $status="This serial number (".$serial.") not available";
      }
  }
}
} 
return $status; 
}


public function cheque_issued_status($no='no',$trans_code){
    $status="OK";
    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];
    $trans_no=($no=='no')?$_POST['trans_no']:$no;
    $sql="SELECT COUNT(*) as records FROM `t_cheque_issued` WHERE trans_code='$trans_code' AND trans_no='$no' AND ststus='D'  AND cl='$cl' AND bc='$bc'";
    $num=(int)$this->db->query($sql)->row()->records;
    if($num!=0){
        return "This Cheque Already Withdrawed";
    }
    return $status;
}

}