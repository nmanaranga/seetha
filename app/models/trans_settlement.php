<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class trans_settlement extends CI_Model {

  private  $sd;
  
  function __construct(){
  	parent::__construct();
  	$this->sd = $this->session->all_userdata();
  	$this->load->database($this->sd['db'], true);
  }

  public function save_settlement($table_name,$code,$date,$trans_code,$trans_no,$sub_trans_code,$sub_trans_no,$dr,$cr){

    $data=array(
     "cl"=>$this->sd['cl'],
     "bc"=>$this->sd['branch'],
     "sub_cl"=>$this->sd['cl'],
     "sub_bc"=>$this->sd['branch'],
     "ddate"=>$date,
     "acc_code"=>$code,
     "trans_code"=>$trans_code,
     "trans_no"=>$trans_no,
     "sub_trans_code"=>$sub_trans_code,
     "sub_trans_no"=>$sub_trans_no,
     "dr"=>$dr,
     "cr"=>$cr
   );

    $this->db->insert($table_name,$data);
    
  }

  public function save_iternal_transfer_trans($table_name,$cl,$bc,$sub_cl,$sub_bc,$code,$date,$trans_code,$trans_no,$sub_trans_code,$sub_trans_no,$dr,$cr,$trans_mode,$sub_no){

    $data=array(
      "cl"=>$cl,
      "bc"=>$bc,
      "sub_cl"=>$sub_cl,
      "sub_bc"=>$sub_bc,
      "ddate"=>$date,
      "acc_code"=>$code,
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "sub_trans_code"=>$sub_trans_code,
      "sub_trans_no"=>$sub_trans_no,
      "dr"=>$dr,
      "cr"=>$cr,
      "trans_mode"=>$trans_mode,
      "sub_no" =>$sub_no
    );

    $this->db->insert($table_name,$data);
    
  }

  public function delete_internal_trans_settlement($table_name,$trans_code,$trans_no){
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $this->db->where("trans_code",$trans_code);
    $this->db->where("trans_no",$trans_no);
    $this->db->delete($table_name);
  }

  public function delete_internal_trans_settlement_sub_bc($table_name,$trans_code,$trans_no,$sub_cl,$sub_bc){
    $this->db->where("cl",$sub_cl);
    $this->db->where("bc",$sub_bc);
    $this->db->where("trans_code",$trans_code);
    $this->db->where("trans_no",$trans_no);
    $this->db->delete($table_name);
  }

  public function delete_internal_trans_sub_settlement($table_name,$trans_code,$trans_no){
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $this->db->where("sub_trans_code",$trans_code);
    $this->db->where("sub_trans_no",$trans_no);
    $this->db->delete($table_name);
  }

  public function save_settlement_multi($table_name,$code,$date,$trans_code,$trans_no,$sub_trans_code,$sub_trans_no,$dr,$cr,$cl,$bc){
    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "sub_cl"=>$cl,
      "sub_bc"=>$bc,
      "ddate"=>$date,
      "acc_code"=>$code,
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "sub_trans_code"=>$sub_trans_code,
      "sub_trans_no"=>$sub_trans_no,
      "dr"=>$dr,
      "cr"=>$cr
    );

    $this->db->insert($table_name,$data);
    
  }


  public function save_install_payment_trans($table_name,$code,$date,$trans_code,$trans_no,$sub_trans_code,$sub_trans_no,$dr,$cr,$agr_no,$due_date,$ins_no){

    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "ddate"=>$date,
      "acc_code"=>$code,
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "sub_trans_code"=>$sub_trans_code,
      "sub_trans_no"=>$sub_trans_no,
      "dr"=>$dr,
      "cr"=>$cr,
      "agr_no"=>$agr_no,
      "due_date"=>$due_date,
      "ins_no"=>$ins_no
    );

    $this->db->insert($table_name,$data);
    
  }

  
  public function save_item_movement($table_name,$item,$t_code,$t_no,$ddate,$qty_in,$qty_out,$store,$avg_price,$batch,$cost,$s_price,$l_price,$group,$cl='0',$bc='0'){

    if($cl=='0'){
      $cluster =$this->sd['cl'];
      $branch  =$this->sd['branch'];
    }else{
      $cluster =$cl;
      $branch  =$bc;
    }

    if($t_code==42){
      $sql="SELECT sub_no FROM t_internal_transfer_sum 
      WHERE cl='$cluster' AND bc='$branch' AND nno='$t_no' AND trans_code='$t_code'";
      $query=$this->db->query($sql);
      if($query->num_rows()>0){
        $sub_no=$query->first_row()->sub_no;
      }else{
        $sub_no=$t_no;
      }
    }else if($t_code==43){
      if($cl=='0'){
        $sql="SELECT sub_no FROM t_internal_transfer_sum 
        WHERE cl='$cluster' AND bc='$branch' AND nno='$t_no' AND trans_code='$t_code'";
      }else{

        $sql="SELECT bc FROM m_stores WHERE code='$store'";
        $query=$this->db->query($sql);
        if($query->num_rows()>0){
          $issue_bc = $query->first_row()->bc;
        }else{
          $issue_bc = $branch;
        }

        $sql="SELECT sub_no FROM t_internal_transfer_sum 
        WHERE location_store='$store' AND nno='$t_no' AND trans_code='$t_code' 
        AND issue_bc ='$issue_bc'";
      }
      $query=$this->db->query($sql);
      if($query->num_rows()>0){
        $sub_no=$query->first_row()->sub_no;
      }else{
        $sub_no=$t_no;
      }   
    }else{
      $sub_no=$t_no;
    }
    
    $data=array(
      "cl"              =>$cluster,
      "bc"              =>$branch,
      "item"            =>$item,
      "trans_code"      =>$t_code,
      "trans_no"        =>$t_no,
      "ddate"           =>$ddate,
      "qty_in"          =>$qty_in,
      "qty_out"         =>$qty_out,
      "store_code"      =>$store,
      "avg_price"       =>$avg_price,
      "batch_no"        =>$batch,
      "cost"            =>$cost,
      "sales_price"     =>$s_price,
      "last_sales_price"=>$l_price,
      "group_sale_id"   =>$group,
      "sub_trans_no"    =>$sub_no
    );
    $this->db->insert($table_name,$data);

    $sql="SELECT * 
    FROM m_item_batch_stock m 
    WHERE m.item_code ='$item' AND batch_no='$batch' AND cl='$cluster' 
    AND bc='$branch' AND store_code='$store'";

    $query=$this->db->query($sql);
    if($query->num_rows()>0){
      $res=1;
    }else{
      $res=2;
    }

    if($res==1){
      if($qty_in>0){
        $sql_update="UPDATE m_item_batch_stock
        SET qty = qty+$qty_in
        WHERE cl='$cluster' AND bc='$branch'
        AND batch_no='$batch' AND store_code='$store' AND item_code='$item'";
        $this->db->query($sql_update);
      }
      if($qty_out>0){
        $sql_update="UPDATE m_item_batch_stock
        SET qty = qty-$qty_out
        WHERE cl='$cluster' AND bc='$branch'
        AND batch_no='$batch' AND store_code='$store' AND item_code='$item'";
        $this->db->query($sql_update);
      }
    }else{
      $insert_data=array(
        'cl'              =>$cluster,
        'bc'              =>$branch,
        'store_code'      =>$store,
        'item_code'       =>$item,
        'batch_no'        =>$batch,
        'qty'             =>$qty_in,
        'purchase_price'  =>$cost,
        'min_price'       =>$l_price,
        'max_price'       =>$s_price
      );
      $this->db->insert('m_item_batch_stock',$insert_data);
    }

  }

  public function delete_item_movement($table_name,$trans_code,$trans_no){

    $sql="SELECT * FROM $table_name
    WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' 
    AND trans_code='$trans_code' AND trans_no='$trans_no'";

    $query=$this->db->query($sql);
    
    foreach($query->result() as $row){
      if((int)$row->qty_in>0){
        $qty=$row->qty_in;
        $sql_update="UPDATE m_item_batch_stock
        SET qty = qty-$qty
        WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'
        AND batch_no='".$row->batch_no."' AND store_code='".$row->store_code."' 
        AND item_code='".$row->item."'";
        $this->db->query($sql_update);
      }
      if((int)$row->qty_out>0){
        $qty=$row->qty_out;
        $sql_update="UPDATE m_item_batch_stock
        SET qty = qty+$qty
        WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'
        AND batch_no='".$row->batch_no."' AND store_code='".$row->store_code."' 
        AND item_code='".$row->item."'";
        $this->db->query($sql_update);
      }
    }

    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $this->db->where("trans_code",$trans_code);
    $this->db->where("trans_no",$trans_no);
    $this->db->delete($table_name);
  }



  public function delete_settlement($table_name,$trans_code,$trans_no){
   $this->db->where("cl",$this->sd['cl']);
   $this->db->where("bc",$this->sd['branch']);
   $this->db->where("trans_code",$trans_code);
   $this->db->where("trans_no",$trans_no);
   $this->db->delete($table_name);
 }

 public function delete_settlement_sub($table_name,$sub_trans_code,$sub_trans_no){
  $this->db->where("cl",$this->sd['cl']);
  $this->db->where("bc",$this->sd['branch']);
  $this->db->where("sub_trans_code",$sub_trans_code);
  $this->db->where("sub_trans_no",$sub_trans_no);
  $this->db->delete($table_name);
}


public function has_settled_trans($table_name, $trans_code,$trans_no){
 $this->db->select(array(
  $table_name.'.sub_trans_code'
  ,$table_name.'.sub_trans_no'        
));

 $this->db->from($table_name);      
 $this->db->where("trans_code",$trans_code);
 $this->db->where("trans_no",$trans_no);
 $this->db->where("sub_trans_code !=",$trans_code);
 $this->db->where("sub_trans_no !=",$trans_no);
 $this->db->where("cl",$this->sd['cl']);
 $this->db->where("bc",$this->sd['branch']);


 $query=$this->db->get();
 
 if($query->num_rows()){
  $tc=$query->row()->sub_trans_code;

  $this->db->from('t_trans_code'); 
  $this->db->where("code",$query->row()->sub_trans_code);
  $qtc=$this->db->get();

  $a['sub_trans_code'] = $qtc->row()->description;
  $a['sub_trans_no'] = $query->row()->sub_trans_no;
  return ($a);
}else{
 $b=0;
 return ($b);
} 
}

public function update_crdr_note($table_name,$is_add,$ddate,$nno,$memo,$amount,$ref_no,$code,$is_customer,$acc_code,$ref_trans_code,$ref_trans_no) {
  $data=array(
    'ddate'=>$ddate,
    'nno'=>$nno,
    'memo'=>$memo,
    'amount'=>$amount,
    'ref_no'=>$ref_no,
    'code'=>$code,
    'cl'=>$this->sd['cl'],
    'bc'=>$this->sd['branch'],
    'oc'=>$this->sd['oc'],
    'is_customer'=>$is_customer,
    'acc_code'=>$acc_code,
    'ref_trans_code'=>$ref_trans_code,
    'ref_trans_no'=>$ref_trans_no,
    'balance'=>$amount
  );
  if($is_add){
   $this->db->insert($table_name,$data);
 }else {
   $this->db->where('nno',$_POST['hid']);
   $this->db->where('cl',$this->sd['cl']);
   $this->db->where('bc',$this->sd['branch']);
   $this->db->update($table_name, $data);
 }
}

public function cancel_crdr_note($type,$nno) {

  if($type=='cr') {
   $trans_code=17;
   $table_name='t_credit_note';
   $table_trans='t_credit_note_trans';
 }
 else if(($type=='dr')){
   $trans_code=18;
   $table_name='t_debit_note';
   $table_trans='t_debit_note_trans';
 }

 $this->delete_settlement($table_trans,$trans_code,$nno);

 $this->db->where('nno',$nno);
 $this->db->where('cl',$this->sd['cl']);
 $this->db->where('bc',$this->sd['branch']);
 echo $this->db->update($table_name, array("is_cancel"=>1));

}


public function t_penalty_trance($nno,$ddate,$cus_id,$dr_type,$dr_no,$cr_type,$cr_no,$dr,$cr,$cl,$bc){
  $data=array(
    "cl"=>$this->sd['cl'],
    "bc"=>$this->sd['branch'],
    "sub_cl"=>$cl,
    "sub_bc"=>$bc,
    "nno"=>$nno,
    "ddate"=>$ddate,
    "cus_id"=>$cus_id,
    "dr_type"=>$dr_type,
    "dr_no"=>$dr_no,
    "cr_type"=>$cr_type,
    "cr_no"=>$cr_no,
    "dr"=>$dr,
    "cr"=>$cr,
    "oc"=>$this->sd['oc']
  );

  $this->db->insert('t_penalty_trance',$data);
}


public function delete_penalty_trans($cr_no,$cr_type){
  $this->db->where('cr_no',$cr_no);
  $this->db->where('cr_type',$cr_type);
  $this->db->where('cl',$this->sd['cl']);
  $this->db->where('bc',$this->sd['branch']);
  $this->db->delete('t_penalty_trance');
}
}
