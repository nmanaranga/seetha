<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_grn_sum_gift extends CI_Model {
    
    private $sd;
    private $mtb;
    private $max_no;
    private $tb_po_trans;

    private $mod = '060';
    private $trans_code="60";
    private $sub_trans_code="60";
    private $qty_in="0";
    
    function __construct(){
      parent::__construct();
      $this->sd = $this->session->all_userdata();
      $this->load->database($this->sd['db'], true);
      $this->mtb = $this->tables->tb['g_t_grn_sum'];
      $this->load->model('user_permissions');
      $this->tb_po_trans= $this->tables->tb['t_po_trans'];
    }
    
    public function base_details(){
      $this->load->model("m_stores");

      $a['stores']=$this->m_stores->select2();
      $a['max_no']=$this->utility->get_max_no("g_t_grn_sum","nno");
      return $a;
    }
  
    public function validation(){
      $this->max_no=$this->utility->get_max_no("g_t_grn_sum","nno"); 
      $status=1;
      $check_is_delete=$this->validation->check_is_cancel($this->max_no,'g_t_grn_sum');
      if($check_is_delete!=1){
        return "This purchase has been already cancelled";
      }

      /*$purchase_update_status=$this->trans_cancellation->purchase_update_status($this->max_no);
      if($purchase_update_status!="OK"){
        return $purchase_update_status;
      }*/

      $supplier_validation = $this->validation->check_is_supplier($_POST['supplier_id']);
      if ($supplier_validation != 1) {
        return $supplier_validation; 
      } 
      $serial_validation_status = $this->validation->serial_update_gift('0_', '2_','all_serial_');
      if ($serial_validation_status != 1) {
        return $serial_validation_status;
      }
      /*$check_zero_value=$this->validation->empty_net_value($_POST['net_amount']);
      if($check_zero_value!=1){
        return $check_zero_value;
      }

      $account_update=$this->account_update(0);
      if($account_update!=1){
          return "Invalid account entries";
      }    
      */
      return $status;
    }


    public function save(){
     $this->db->trans_begin();
      error_reporting(E_ALL); 
      function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
          throw new Exception($errLine); 
      }
      set_error_handler('exceptionThrower'); 
      try {
        $validation_status=$this->validation();

        if($validation_status==1){
         
          $t_grn_sum_gift=array(
            "cl"=>$this->sd['cl'],
            "bc"=>$this->sd['branch'],
            "oc"=> $this->sd['oc'],
            "nno"=>$this->max_no,
            "ddate"=> $_POST['date'],
            "ref_no"=> $_POST['ref_no'],
            "supp_id"=> $_POST['supplier_id'],
            "inv_no"=> $_POST['inv_no'],
            "inv_date"=> $_POST['ddate'],
            "memo"=> $_POST['memo'],
            "store"=> $_POST['stores'],
            "net_amount"=> $_POST['net_amount'],
          );

          for($x =0; $x<25; $x++){
            if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['4_'.$x])){
              if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['4_'.$x] != "" ){
                $t_grn_det_gift[]= array(
                  "cl"=>$this->sd['cl'],
                  "bc"=>$this->sd['branch'],
                  "nno"=>$this->max_no,
                  "code"=>$_POST['0_'.$x],
                  "qty"=>$_POST['2_'.$x],
                  "price"=>$_POST['4_'.$x],
                  "max_price"=>$_POST['max_'.$x],    
                  "amount"=>$_POST['t_'.$x],                   
                ); 

                $change[]= array(
                    "code"    =>$_POST['0_'.$x],
                    "price"   =>$_POST['max_'.$x],
                    "cost"    =>$_POST['4_'.$x],
                );    
              }
            }
          }

          $wordChunks = explode(",",$_POST['srls']);
          $execute=0;
          $subs=0;

          for($x = 0; $x<25; $x++){
            if(isset($_POST['0_'.$x])){
              if($_POST['0_'.$x] != ""){
                $t_item_movement_gift[]=array(
                  "cl"=>$this->sd['cl'],
                  "bc"=>$this->sd['branch'],
                  "item"=>$_POST['0_'.$x],
                  "trans_code"=>60,
                  "trans_no"=>$this->max_no,
                  "ddate"=>$_POST['date'],
                  "qty_in"=>$_POST['2_'.$x],
                  "qty_out"=>0,
                  "store_code"=>$_POST['stores'],
                  "sales_price"=>$_POST['max_'.$x],
                  "cost"=>$_POST['4_'.$x]
                );
              }
            }       
          }


        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('t_grn_sum_gift')){
            $account_update=$this->account_update(0);
            if($account_update==1){
              if($_POST["df_is_serial"]=='1') {
                $this->save_serial();
              }          
              $this->account_update(1);
              $this->db->insert($this->mtb,  $t_grn_sum_gift);
              if(count($t_grn_det_gift)){$this->db->insert_batch("g_t_grn_det",$t_grn_det_gift);}
              if(count($t_item_movement_gift)){$this->db->insert_batch("g_t_item_movement",$t_item_movement_gift);}
              
              $this->load->model('trans_settlement');
              $this->trans_settlement->save_settlement("t_sup_settlement",$_POST['supplier_id'],$_POST['ddate'],60,$this->max_no,60,$this->max_no,$_POST['net_amount'],"0");

              for($x =0; $x<25; $x++){
                if(isset($_POST['0_'.$x],$_POST['2_'.$x])){
                  if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" ){

                    for($y=0;$y<sizeof($change);$y++){
                      $item=array(
                        "cost"        =>$change[$y]['cost'],
                        "price"       =>$change[$y]['price'],
                        );
                      $this->db->where('code',$change[$y]['code']);
                      $this->db->update('g_m_gift_voucher',$item);
                    }
                  }
                }
              }
              $this->utility->save_logger("SAVE",60,$this->max_no,$this->mod);
              echo $this->db->trans_commit();
            }else{
              $this->db->trans_commit();
              echo "Invalid account entries";
            }
          }else{
            $this->db->trans_commit();
            echo "No permission to save records";
          }  
        }else{
          if($this->user_permissions->is_edit('t_grn_sum_gift')){
            $status=$this->trans_cancellation->purchase_update_status_gift($this->max_no);     
            if($status=="OK"){
              $account_update=$this->account_update(0);
              if($account_update==1){
                if($_POST["df_is_serial"]=='1') {
                  $this->save_serial();
                }
                $this->account_update(1);
                $this->set_delete();
                for($x =0; $x<25; $x++){
                  if(isset($_POST['0_'.$x],$_POST['2_'.$x])){
                    if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" ){
                      for($y=0;$y<sizeof($change);$y++){
                        $item=array(
                          "cost"        =>$change[$y]['cost'],
                          "price"       =>$change[$y]['price'],
                          );
                        $this->db->where('code',$change[$y]['code']);
                        $this->db->update('g_m_gift_voucher',$item);
                      }
                    }
                  }
                }
                $this->db->where("cl",$this->sd['cl']);
                $this->db->where("bc",$this->sd['branch']);
                $this->db->where('nno',$_POST['hid']);
                $this->db->update($this->mtb, $t_grn_sum_gift);

                $this->load->model('trans_settlement');
                $this->trans_settlement->delete_settlement("t_sup_settlement",60,$this->max_no);
                $this->trans_settlement->save_settlement("t_sup_settlement",$_POST['supplier_id'],$_POST['ddate'],60,$this->max_no,60,$this->max_no,$_POST['net_amount'],"0");

                if(count($t_grn_det_gift)){$this->db->insert_batch("g_t_grn_det",$t_grn_det_gift);}
                if(count($t_item_movement_gift)){$this->db->insert_batch("g_t_item_movement",$t_item_movement_gift);}
                $this->utility->save_logger("EDIT",60,$this->max_no,$this->mod);  
                echo $this->db->trans_commit();  
              }else{
                $this->db->trans_commit();
                echo "Invalid account entries";
              }
            }else{
              echo $status;
              $this->db->trans_commit();
            }
          }else{
            $this->db->trans_commit();
            echo "No permission to edit records";
          }  
        }
      }else{
        echo $validation_status;
        $this->db->trans_commit();
      }
    }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo  $e->getMessage()." - Operation fail please contact admin"; 
    }  
  }


  public function account_update($condition){

    $this->db->where("trans_no", $this->max_no);
    $this->db->where("trans_code",60);
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $this->db->delete("t_check_double_entry");

    if($_POST['hid']=="0"||$_POST['hid']==""){
            
    }else{
      if($condition=="1"){
        $this->db->where("trans_no", $this->max_no);
        $this->db->where("trans_code", 60);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_account_trans");
      }
    }

    $config = array(
      "ddate" => $_POST['date'],
      "trans_code"=>60,
      "trans_no"=>$this->max_no,
      "op_acc"=>0,
      "reconcile"=>0,
      "cheque_no"=>0,
      "narration"=>"",
      "ref_no" => $_POST['ref_no']
    );
          
    $des = "GIFT VOUCHER PURCHASE - ".$_POST['supplier_id'];
    $this->load->model('account');
    $this->account->set_data($config);

    $acc_code=$this->utility->get_default_acc('GIFT_STOCK_ACC');  

    $this->account->set_value2($des, $_POST['net_amount'], "dr", $acc_code,$condition);  
    $this->account->set_value2($des, $_POST['net_amount'], "cr", $_POST['supplier_id'],$condition);
    
    if($condition==0){
      $query = $this->db->query("
       SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
       FROM `t_check_double_entry` t
       LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
       WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='60'  AND t.`trans_no` ='".$this->max_no."' AND 
       a.`is_control_acc`='0'");
      
      if($query->row()->ok == "0") {
        $this->db->where("trans_no", $this->max_no);
        $this->db->where("trans_code",60);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_check_double_entry");
        return "0";
      }else {
        return "1";
      }
    }
  }  

  public function check_code() {
      $this->db->where('code', $_POST['code']);
      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $this->db->limit(1);
      echo $this->db->get($this->mtb)->num_rows;
    }

  public function save_serial(){

    for($x = 0; $x<25; $x++){
      if(isset($_POST['0_'.$x])){
        if($_POST['0_'.$x] != "" || !empty($_POST['0_' . $x])){
          if($this->check_is_serial_items($_POST['0_'.$x])==1){
            $serial = $_POST['all_serial_'.$x];
            $pp=explode(",",$serial);
            
            for($t=0; $t<count($pp); $t++){
            $p = explode("-",$pp[$t]);
           
              $t_serial[]=array(
              "cl"=>$this->sd['cl'],
              "bc"=>$this->sd['branch'],
              "trans_type"=>60,
              "trans_no"=>$this->max_no,
              "date"=>$_POST['ddate'],
              "item"=>$_POST['0_'.$x],
              "serial_no"=>$p[0],
              "other_no1"=>$p[1],
              "other_no2"=>$p[2],
              "cost"=>$_POST['4_'.$x],
              "max_price"=>$_POST['max_'.$x],
              "store_code"=>$_POST['stores'],
              "engine_no"=>"",
              "chassis_no"=>'',
              "out_doc"=>'',
              "out_no"=>'',
              "out_date"=>''
              );

              $t_serial_movement[]=array(
              "cl"=>$this->sd['cl'],
              "bc"=>$this->sd['branch'],
              "trans_type"=>60,
              "trans_no"=>$this->max_no,
              "item"=>$_POST['0_'.$x],
              "serial_no"=>$p[0],
              "qty_in"=>1,
              "qty_out"=>0,
              "cost"=>$_POST['4_'.$x],
              "store_code"=>$_POST['stores'],
              "computer"=>$this->input->ip_address(),
              "oc"=>$this->sd['oc'],
              ); 
            }                  
          }
        }
      }
    }

    if($_POST['hid'] == "0" || $_POST['hid'] == ""){
        if(isset($t_serial)){if(count($t_serial)){  $this->db->insert_batch("g_t_serial", $t_serial);}}
        if(isset($t_serial_movement)){if(count($t_serial_movement)){  $this->db->insert_batch("g_t_serial_movement", $t_serial_movement);}}

      }else{

        $this->db->where("bc",$this->sd['branch']);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("trans_type",60);
        $this->db->where("trans_no", $this->max_no);
        $this->db->delete("g_t_serial_movement");

        $this->db->where("bc",$this->sd['branch']);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("trans_type",60);
        $this->db->where("trans_no", $this->max_no);
        $query=$this->db->get("g_t_serial");

        foreach($query->result() as $row){
          $this->db->where("bc",$this->sd['branch']);
          $this->db->where("cl", $this->sd['cl']);
          $this->db->where("item",$row->item);
          $this->db->where("serial_no", $row->serial_no);
          $this->db->delete("g_t_serial");

          $this->db->where("bc",$this->sd['branch']);
          $this->db->where("cl", $this->sd['cl']);
          $this->db->where("item",$row->item);
          $this->db->where("serial_no", $row->serial_no);
          $this->db->delete("g_t_serial_movement");
        } 
        
        if(isset($t_serial)){if(count($t_serial)){  $this->db->insert_batch("g_t_serial", $t_serial);}}
        if(isset($t_serial_movement)){if(count($t_serial_movement)){  $this->db->insert_batch("g_t_serial_movement", $t_serial_movement);}}
      }
   }   

  private function set_delete(){ 
    if($_POST['hid'] != "0" || $_POST['hid'] != ""){   
      $this->db->where("nno",  $this->max_no);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->delete("g_t_grn_det");

      $this->db->where("trans_code", 60);
      $this->db->where("trans_no",  $this->max_no);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->delete("g_t_item_movement");

    }  
  }

    
   public function load(){

    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];
      $this->db->select(array(
          'm_supplier.code as scode' ,
          'm_supplier.name',
          'm_stores.code as stcode',
          'm_stores.description',
          'g_t_grn_sum.nno',
          'g_t_grn_sum.ddate',
          'g_t_grn_sum.ref_no',
          'g_t_grn_sum.inv_no',
          'g_t_grn_sum.inv_date',
          'g_t_grn_sum.memo',
          'g_t_grn_sum.gross_amount',
          'g_t_grn_sum.net_amount',
          'g_t_grn_sum.is_cancel'
        ));

    $this->db->from('m_supplier');
    $this->db->join('g_t_grn_sum','m_supplier.code=g_t_grn_sum.supp_id');
    $this->db->join('m_stores','m_stores.code=g_t_grn_sum.store');
    $this->db->where('g_t_grn_sum.cl',$this->sd['cl'] );
    $this->db->where('g_t_grn_sum.bc',$this->sd['branch'] );
    $this->db->where('g_t_grn_sum.nno',$_POST['id']);
    $query=$this->db->get();
                
     $x=0;
     if($query->num_rows()>0){
      $a['sum']=$query->result();
         }else{
        $x=2;
      }

     $this->db->select(array(              
        'g_m_gift_voucher.code as icode',
        'g_m_gift_voucher.description as idesc',
        'g_t_grn_det.nno',
        'g_t_grn_det.qty',
        'g_t_grn_det.price',
        'g_t_grn_det.max_price',
        'g_t_grn_det.amount'
    ));

    $this->db->from('g_m_gift_voucher'); 
    $this->db->join('g_t_grn_det','g_m_gift_voucher.code=g_t_grn_det.code');           
    $this->db->where('g_t_grn_det.cl',$this->sd['cl'] );
    $this->db->where('g_t_grn_det.bc',$this->sd['branch'] );
    $this->db->where('g_t_grn_det.nno',$_POST['id']);
    $this->db->order_by('g_t_grn_det.auto_num','asc');
    $query=$this->db->get();

    if($query->num_rows()>0){
      $a['det']=$query->result();
         }else{
        $x=2;
      }   

    $sql="SELECT auto_no,g_t_serial.`item`, g_t_serial.`serial_no`,g_t_serial.`other_no1`, g_t_serial.`other_no2` 
          FROM `g_t_serial_movement` 
          INNER JOIN g_t_serial ON g_t_serial.`serial_no`=g_t_serial_movement.`serial_no` 
          WHERE g_t_serial_movement.trans_no = '$_POST[id]' AND g_t_serial_movement.trans_type='60' AND g_t_serial_movement.`cl` = '$cl' AND g_t_serial_movement.`bc` = '$bc'
          UNION ALL
          SELECT auto_no,g_t_serial.`item`, g_t_serial.`serial_no`,g_t_serial.`other_no1`, g_t_serial.`other_no2`
          FROM `g_t_serial_movement_out` 
          INNER JOIN g_t_serial ON g_t_serial.`serial_no`=g_t_serial_movement_out.`serial_no` 
          WHERE g_t_serial_movement_out.trans_no = '$_POST[id]' AND g_t_serial_movement_out.trans_type='60' AND g_t_serial_movement_out.`cl` = '$cl' AND g_t_serial_movement_out.`bc` = '$bc'
          ORDER BY auto_no";


    $query=$this->db->query($sql);

    if($query->num_rows()>0){
      $a['serial']=$query->result();
    }else{
      $a['serial']=2;
    }


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
      if($this->user_permissions->is_delete('t_grn_sum_gift')){
        $status=$this->trans_cancellation->purchase_update_status_gift($_POST['trans_no']);     
        if($status=="OK"){

        $trans_no=$_POST['trans_no'];

        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('trans_code','60');
        $this->db->where('trans_no',$_POST['trans_no']);
        $this->db->delete('t_sup_settlement');

        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('trans_type','60');
        $this->db->where('trans_no',$_POST['trans_no']);
        $this->db->delete('g_t_serial_movement');

        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('trans_type','60');
        $this->db->where('trans_no',$_POST['trans_no']);
        $this->db->delete('g_t_serial');

        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('trans_code','60');
        $this->db->where('trans_no',$_POST['trans_no']);
        $this->db->delete('g_t_item_movement');

        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('trans_code','60');
        $this->db->where('trans_no',$_POST['trans_no']);
        $this->db->delete('t_account_trans');

        $data=array('is_cancel'=>'1');
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('nno',$_POST['trans_no']);
        $this->db->update('g_t_grn_sum',$data);

        $sql="SELECT supp_id FROM g_t_grn_sum WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['trans_no']."'";
        $sup_id=$this->db->query($sql)->first_row()->supp_id;

        $this->utility->update_purchase_balance($sup_id);     

        $this->utility->save_logger("CANCEL",60,$_POST['trans_no'],$this->mod);  
        
        echo $this->db->trans_commit();

        }else{
          echo $status;
        }
      }else{
        $this->db->trans_commit();
        echo "No permission to delete records";
      }
    }catch(Exception $e){ 
      $this->db->trans_rollback();
      echo "Operation fail please contact admin"; 
    } 
  }

  public function get_item(){
    $this->db->select(array('code','description','cost','price'));
    $this->db->where("code",$this->input->post('code'));
    if($_POST['supp']!=""){
      $this->db->where("supplier",$_POST['supp']);
    }
    $this->db->limit(1);
    $query=$this->db->get('g_m_gift_voucher');
    if($query->num_rows() > 0){
        $data['a']=$query->result();
    }else{
        $data['a']=2;
    }
    echo json_encode($data);
  }



    public function item_list_all(){
      if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
      if($_POST['supp']==""){
        $sql = "SELECT * 
                FROM g_m_gift_voucher 
                WHERE description LIKE '$_POST[search]%' 
                OR code LIKE '$_POST[search]%' 
                LIMIT 25";
      }else{
         $sql = "SELECT * 
                FROM g_m_gift_voucher 
                WHERE supplier = '".$_POST['supp']."' AND (description LIKE '$_POST[search]%' 
                OR code LIKE '$_POST[search]%') 
                LIMIT 25";
      }

      $query = $this->db->query($sql);
      $a = "<table id='item_list' style='width : 100%' >";
      $a .= "<thead><tr>";
      $a .= "<th class='tb_head_th'>Code</th>";
      $a .= "<th class='tb_head_th'>Item Name</th>";
      $a .= "<th class='tb_head_th'>Price</th>";
      $a .= "<th class='tb_head_th'>Max Price</th>";
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
			    $a .= "<td>".$r->code."</td>";
			    $a .= "<td>".$r->description."</td>";
			    $a .= "<td>".$r->cost."</td>";
          $a .= "<td>".$r->price."</td>";
			    $a .= "</tr>";
      }

      $a .= "</table>";
      echo $a;
    }
    
    
 public function check_is_serial_items($code){
   /*$this->db->select(array('serial_no'));
   $this->db->where("code",$code);
   $this->db->limit(1);
   return $this->db->get("m_item")->first_row()->serial_no;*/
   return "1";
 }



 public function PDF_report(){
 
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
      $r_detail['p_serial']=$_POST['is_print_serial'];
     
      $sql="SELECT s.* ,
                    m.`name`,
                    CONCAT(m.`address1`,m.`address2`,m.`address3`) AS address,
                    m.`email`,
                    t.`description`
            FROM g_t_grn_sum s
            JOIN m_supplier m ON m.`code` = s.`supp_id`
            JOIN m_stores t ON t.`code` = s.`store` AND t.cl = s.cl AND t.bc =  s.bc
            WHERE s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."' AND s.nno='".$_POST['qno']."'";
     
      $r_detail['sum']=$this->db->query($sql)->result();


      $this->db->SELECT(array('serial_no','item'));
      $this->db->FROM('g_t_serial');
      $this->db->WHERE('g_t_serial.cl', $this->sd['cl']);
      $this->db->WHERE('g_t_serial.bc', $this->sd['branch']);
      $this->db->WHERE('g_t_serial.trans_type','60');
      $this->db->WHERE('g_t_serial.trans_no',$_POST['qno']);
      $r_detail['serial'] = $this->db->get()->result();

      $sql="SELECT m.`description`,
                   d.* 
                  FROM g_t_grn_det d
                  JOIN g_m_gift_voucher m ON m.code = d.code
                  WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['qno']."'
                  ORDER BY d.auto_num";

      $r_detail['det']=$this->db->query($sql)->result();
   
      $this->db->select(array('loginName'));
      $this->db->where('cCode',$this->sd['oc']);
      $r_detail['user']=$this->db->get('users')->result();

      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);

    }

}