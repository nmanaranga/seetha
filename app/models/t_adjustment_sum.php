<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_adjustment_sum extends CI_Model {
    
    private $sd;
    private $mtb;
    private $max_no;
    private $mod = '003';
    
    function __construct(){
      parent::__construct();

      $this->sd = $this->session->all_userdata();
      $this->load->database($this->sd['db'], true);
      $this->mtb = $this->tables->tb['m_items'];
      $this->load->model('user_permissions');
    }
    
    public function base_details(){

      $this->db->select_max('nno', 'max_no');
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);      
      $query =$this->db->get("t_adjustment_sum");

       foreach($query->result() as $value) {
             $max_no=$value->max_no;
       }
     
      $this->db->select(array('code','description'));
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);    
      $query =$this->db->get('m_stores');


      $st = "<select name='to_store' id='to_store'>";
      $st .= "<option value='0'>---</option>";
      foreach($query->result() as $r){
          $st .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."-".$r->description."</option>";
      }
      $st .= "</select>";

      //$a['max_no']=$this->get_next_no();  
      $a['max_no']= $this->utility->get_max_no("t_adjustment_sum","nno");
      $a['store']=$st;
      return $a;
    }
    
    public function validation(){
      $status=1;

	    $this->max_no=$this->utility->get_max_no("t_adjustment_sum","nno");

      $check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_adjustment_sum');
      if($check_is_delete!=1){
        return "Stock adjustment already deleted";
      }

      return $status;
    }

    public function save_first(){       
      $this->db->trans_begin();
      error_reporting(E_ALL); 
      function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
          throw new Exception($errLine); 
      }
      set_error_handler('exceptionThrower'); 
      try { 

        $validation_status=$this->validation();
        if($validation_status==1){
          $t_adjustment_sum=array(
            "cl"        =>$this->sd['cl'],
            "bc"        =>$this->sd['branch'],
            "nno"       =>$this->max_no,
            "ddate"     =>$_POST['ddate'],
            "ref_no"    =>$_POST['ref_no'],
            "memo"      =>$_POST['memo'],
            "store"     =>$_POST['to_store'],
            "net_amount"=>$_POST['net_amount'],
            "oc"        =>$this->sd['oc']
          );
        
          for($x = 0; $x<25; $x++){
            if(isset($_POST['0_'.$x])){
              if($_POST['0_'.$x] != ""){

                $t_adjustment_det[]= array(
                  "cl"      =>$this->sd['cl'],
                  "bc"      =>$this->sd['branch'],
                  "nno"     =>$this->max_no,
                  "code"    =>$_POST['0_'.$x],
                  "cur_qty" =>$_POST['2_'.$x],
                  "f_qty"   =>$_POST['1count_'.$x],
                  "s_qty"   =>$_POST['2count_'.$x],
                  "t_qty"   =>$_POST['3count_'.$x],
                  "difference"=>$_POST['4_'.$x],
                  "cost"    =>$_POST['5_'.$x],
                  "price"   =>$_POST['t_'.$x],
                  "batch_no"=>$_POST['1_'.$x],
                  "is_serial"=>$_POST['serialhid_'.$x]
                );  
              }
            }
          }    

          if($_POST['hid'] == "0" || $_POST['hid'] == ""){
            if($this->user_permissions->is_add('t_adjustment_sum')){
              
              $this->db->insert("t_adjustment_sum",$t_adjustment_sum);
              if(count($t_adjustment_det)){ $this->db->insert_batch("t_adjustment_det", $t_adjustment_det);}
              echo $this->db->trans_commit();
            }else{
              echo "No permission to save records";
              $this->db->trans_commit();
            }
          }else{
            if($this->user_permissions->is_edit('t_adjustment_sum')){
              
              $this->db->where('nno',$this->max_no);
              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->update("t_adjustment_sum",$t_adjustment_sum); 

              $this->db->where("nno", $this->max_no);
              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']); 
              $this->db->delete("t_adjustment_det");
              if(count($t_adjustment_det)){ $this->db->insert_batch("t_adjustment_det", $t_adjustment_det);}

              echo $this->db->trans_commit();
            }else{
              echo "No permission to edit records";
              $this->db->trans_commit();
            }
          }
        }else{
          echo $validation_status;
        }
      }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo $e->getMessage()."Operation fail please contact admin"; 
      } 
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
           $t_adjustment_sum=array(
            "cl"        =>$this->sd['cl'],
            "bc"        =>$this->sd['branch'],
            "nno"       =>$this->max_no,
            "ddate"     =>$_POST['ddate'],
            "ref_no"    =>$_POST['ref_no'],
            "memo"      =>$_POST['memo'],
            "store"     =>$_POST['to_store'],
            "net_amount"=>$_POST['net_amount'],
            "oc"        =>$this->sd['oc']
          );
        
          for($x = 0; $x<25; $x++){
            if(isset($_POST['0_'.$x])){
              if($_POST['0_'.$x] != ""){

                $t_adjustment_det[]= array(
                  "cl"      =>$this->sd['cl'],
                  "bc"      =>$this->sd['branch'],
                  "nno"     =>$this->max_no,
                  "code"    =>$_POST['0_'.$x],
                  "cur_qty" =>$_POST['2_'.$x],
                  "f_qty"   =>$_POST['1count_'.$x],
                  "s_qty"   =>$_POST['2count_'.$x],
                  "t_qty"   =>$_POST['3count_'.$x],
                  "difference"=>$_POST['4_'.$x],
                  "cost"    =>$_POST['5_'.$x],
                  "price"   =>$_POST['t_'.$x],
                  "batch_no"=>$_POST['1_'.$x],
                  "is_serial"=>$_POST['serialhid_'.$x],
                  "status"  =>1
                );  
              }
            }
          }    

          if($_POST['hid'] == "0" || $_POST['hid'] == ""){

            if($this->user_permissions->is_add('t_adjustment_sum')){
              $account_update=$this->account_update(0);
              if($account_update==1){
                $this->save_sub_items();
                $this->db->insert("t_adjustment_sum",$t_adjustment_sum);
                if(count($t_adjustment_det)){ $this->db->insert_batch("t_adjustment_det", $t_adjustment_det);}

                for($x = 0; $x<25; $x++){
                  if(isset($_POST['0_'.$x])){
                    if($_POST['0_'.$x] != ""){
                      $difference=(int)$_POST['4_'.$x];
                      if($_POST['4_'.$x]<0){
                        $qty = abs($_POST['4_'.$x]);
                      }else{
                        $qty = $_POST['4_'.$x];
                      }
                  
                      if($difference<0){ //Remove Items           
                        $this->trans_settlement->save_item_movement('t_item_movement',
                        $_POST['0_'.$x],
                        '15',
                        $this->max_no,
                        $_POST['ddate'],
                        0,
                        $qty,
                        $_POST['to_store'],
                        $this->utility->get_cost_price($_POST['0_'.$x]),
                        $_POST['1_'.$x],
                        $_POST['5_'.$x],
                        $this->utility->get_max_price($_POST['0_'.$x]),
                        $this->utility->get_cost_price($_POST['0_'.$x]),
                        '001');
                      }

                      if($difference>0) { // Add  Items
                        $this->trans_settlement->save_item_movement('t_item_movement',
                        $_POST['0_'.$x],
                        '15',
                        $this->max_no,
                        $_POST['ddate'],
                        $qty,
                        0,
                        $_POST['to_store'],
                        $this->utility->get_cost_price($_POST['0_'.$x]),
                        $_POST['1_'.$x],
                        $_POST['5_'.$x],
                        $this->utility->get_max_price($_POST['0_'.$x]),
                        $this->utility->get_cost_price($_POST['0_'.$x]),
                        '001');
                      }
                    }
                  }     
                } 
                $this->account_update(1);

                $this->db->where('cl',$this->sd['cl']);
                $this->db->where('bc',$this->sd['branch']);
                $this->db->where('nno',$this->max_no);
                $this->db->update("t_adjustment_sum",array("is_approve"=>1));

                /*if(isset($t_item_movement)){if(count($t_item_movement)){ $this->db->insert_batch("t_item_movement", $t_item_movement);}}
                if(isset($t_item_movement2)){ if(count($t_item_movement2)){ $this->db->insert_batch("t_item_movement", $t_item_movement2);}}     */
                $this->utility->save_logger("SAVE",15,$this->max_no,$this->mod);
                echo $this->db->trans_commit();
              }else{
                echo "Invalid account entries";
                $this->db->trans_commit();
              }
            }else{
              echo "No permission to save records";
              $this->db->trans_commit();
            }
          }else{
            if($this->user_permissions->is_edit('t_adjustment_sum')){
              $account_update=$this->account_update(0);
              if($account_update==1){
                $this->account_update(1);
                $this->save_sub_items();
                $this->db->where('cl',$this->sd['cl']);
                $this->db->where('bc',$this->sd['branch']);
                $this->db->where('nno',$this->max_no);
                $this->db->update("t_adjustment_sum",$t_adjustment_sum); 

                $this->db->where('cl',$this->sd['cl']);
                $this->db->where('bc',$this->sd['branch']);
                $this->db->where('nno',$this->max_no);
                $this->db->update("t_adjustment_sum",array("is_approve"=>1)); 

                $this->set_delete();

                if(count($t_adjustment_det)){$this->db->insert_batch("t_adjustment_det", $t_adjustment_det);}

                for($x = 0; $x<25; $x++){
                  if(isset($_POST['0_'.$x])){
                    if($_POST['0_'.$x] != ""){
                      $difference=(int)$_POST['4_'.$x];
                      if($_POST['4_'.$x]<0){
                        $qty = abs($_POST['4_'.$x]);
                      }else{
                        $qty = $_POST['4_'.$x];
                      }
                  
                      if($difference<0){ //Remove Items           
                        $this->trans_settlement->save_item_movement('t_item_movement',
                        $_POST['0_'.$x],
                        '15',
                        $this->max_no,
                        $_POST['ddate'],
                        0,
                        $qty,
                        $_POST['to_store'],
                        $this->utility->get_cost_price($_POST['0_'.$x]),
                        $_POST['1_'.$x],
                        $_POST['5_'.$x],
                        $this->utility->get_max_price($_POST['0_'.$x]),
                        $this->utility->get_cost_price($_POST['0_'.$x]),
                        '001');
                      }

                      if($difference>0) { // Add  Items
                        $this->trans_settlement->save_item_movement('t_item_movement',
                        $_POST['0_'.$x],
                        '15',
                        $this->max_no,
                        $_POST['ddate'],
                        $qty,
                        0,
                        $_POST['to_store'],
                        $this->utility->get_cost_price($_POST['0_'.$x]),
                        $_POST['1_'.$x],
                        $_POST['5_'.$x],
                        $this->utility->get_max_price($_POST['0_'.$x]),
                        $this->utility->get_cost_price($_POST['0_'.$x]),
                        '001');
                      }
                    }
                  }     
                } 
                
                //if(isset($t_item_movement)){if(count($t_item_movement)){ $this->db->insert_batch("t_item_movement", $t_item_movement);}}
                //if(isset($t_item_movement2)){ if(count($t_item_movement2)){ $this->db->insert_batch("t_item_movement", $t_item_movement2);}}    
                echo $this->db->trans_commit();
              }else{
                echo "Invalid account entries";
                $this->db->trans_commit();
              }
            }else{
              echo "No permission to edit records";
              $this->db->trans_commit();
            }
          }
        }else{
          echo $validation_status;
        }
      }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo $e->getMessage()."Operation fail please contact admin"; 
      } 
    }


    public function account_update($condition){
      $this->db->where("trans_no", $this->max_no);
      $this->db->where("trans_code", 15);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("bc", $this->sd['branch']);
      $this->db->delete("t_check_double_entry");

      if($_POST['hid']=="0"||$_POST['hid']==""){

      }else{
        if($condition=="1"){
          $sql="SELECT is_approve FROM t_adjustment_sum WHERE cl='".$this->sd['cl']."' 
          AND bc='".$this->sd['branch']."' AND nno='".$this->max_no."' LIMIT 1";
          $query=$this->db->query($sql);
          if($query->first_row()->is_approve!=0){
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->where('trans_code',15);
            $this->db->where('trans_no',$this->max_no);
            $this->db->delete('t_account_trans');
          }
        }
      }

      $config = array(
        "ddate" => $_POST['ddate'],
        "trans_code" => 15,
        "trans_no" => $this->max_no,
        "op_acc" => 0,
        "reconcile" => 0,
        "cheque_no" => 0,
        "narration" => "",
        "ref_no" => $_POST['ref_no']
        );

      $this->load->model('account');
      $this->account->set_data($config);

      $des = "Stock Adjustment - " . $_POST['memo'];

      $total_plus=0;
      $total_min=0;

      for($x=0; $x<25; $x++){
        if($_POST['4_'.$x]!="" && $_POST['0_'.$x]!=""){
          if((float)$_POST['4_'.$x]>0){
            $total_plus+=(float)$_POST['4_'.$x] * (float)$_POST['5_'.$x];
          }else if((int)$_POST['4_'.$x]<0){
            $total_min+=(float)$_POST['4_'.$x] * (float)$_POST['5_'.$x];
          }
        }
      }
      if($total_min<0){
        $total_min = abs($total_min);
      }
      $f_tot = $total_plus - $total_min;
      if($f_tot>0){
        $acc_code = $this->utility->get_default_acc('STOCK_ACC');
        $this->account->set_value2($des, $f_tot, "dr", $acc_code,$condition);

        $acc_code = $this->utility->get_default_acc('STOCK_INCOME');
        $this->account->set_value2($des, $f_tot, "cr", $acc_code,$condition);
      }else if($f_tot<0){
        $acc_code = $this->utility->get_default_acc('STOCK_EXPENSE');
        $this->account->set_value2($des, abs($f_tot), "dr", $acc_code,$condition);

        $acc_code = $this->utility->get_default_acc('STOCK_ACC');
        $this->account->set_value2($des, abs($f_tot), "cr", $acc_code,$condition);
      }

      if($condition==0){
       $query = $this->db->query("
         SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
         FROM `t_check_double_entry` t
         LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
         WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='15'  AND t.`trans_no` ='" . $this->max_no . "' AND 
         a.`is_control_acc`='0'");

       if($query->row()->ok == "0") {
        $this->db->where("trans_no", $this->max_no);
        $this->db->where("trans_code", 15);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_check_double_entry");
        return "0";
      } else {
        return "1";
      }
    }
  }

    public function save_sub_items(){        
        
      for ($x = 0; $x < 25; $x++) {
        if (isset($_POST['0_' . $x])) {
          if ($_POST['0_' . $x] != "") {

            $item_code=$_POST['0_'.$x];
            $qty=$_POST['4_'.$x];
            $batch=$_POST['1_'.$x];
            $date=$_POST['ddate'];
            $store=$_POST['to_store'];
            $price=$_POST['5_'.$x];
            $max=$this->max_no;

            $sql="SELECT s.sub_item , r.qty 
                FROM t_item_movement_sub s
                JOIN r_sub_item r ON r.`code`=s.`sub_item`
                WHERE s.`item`='$item_code' AND s.cl='".$this->sd['cl']."' AND s.bc ='".$this->sd['branch']."' AND s.`batch_no`='$batch' 
                GROUP BY r.`code`";
            $query=$this->db->query($sql);

            $difference=(int)$_POST['4_'.$x];
            if($_POST['4_'.$x]<0){
              $qty = abs($_POST['4_'.$x]);
            }else{
              $qty = $_POST['4_'.$x];
            }

            if($difference<0){ 
              foreach($query->result() as $row) {
                $total_qty=$qty*(int)$row->qty;
                $t_sub_item_movement[] = array(
                "cl"                => $this->sd['cl'],
                "bc"                => $this->sd['branch'],
                "item"              => $item_code,
                "sub_item"          => $row->sub_item,
                "trans_code"        => 15,
                "trans_no"          => $max,
                "ddate"             => $date,
                "qty_in"            => 0,
                "qty_out"           => $total_qty,
                "store_code"        => $store,
                "avg_price"         => $this->utility->get_cost_price($item_code),
                "batch_no"          => $batch,
                "sales_price"       => $price,
                "last_sales_price"  => $this->utility->get_min_price($item_code),
                "cost"              => $this->utility->get_cost_price($item_code),
                "group_sale_id"     => 1,
                );
              }  
            }

            if($difference>0) {
              foreach($query->result() as $row) {
                $total_qty=$qty*(int)$row->qty;
                $t_sub_item_movement2[] = array(
                "cl"                => $this->sd['cl'],
                "bc"                => $this->sd['branch'],
                "item"              => $item_code,
                "sub_item"          =>$row->sub_item,
                "trans_code"        => 15,
                "trans_no"          => $max,
                "ddate"             => $date,
                "qty_in"            => $total_qty,
                "qty_out"           => 0,
                "store_code"        => $store,
                "avg_price"         => $this->utility->get_cost_price($item_code),
                "batch_no"          => $batch,
                "sales_price"       => $price,
                "last_sales_price"  => $this->utility->get_min_price($item_code),
                "cost"              => $this->utility->get_cost_price($item_code),
                "group_sale_id"     => 1,
                );
              }  
            }
          }
        }
      }
      if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
        if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}
        if(isset($t_sub_item_movement2)){if(count($t_sub_item_movement2)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement2);}}
      }else{
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->where("trans_code", 15);
        $this->db->where("trans_no", $_POST['hid']);
        $this->db->delete("t_item_movement_sub");

        if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}
        if(isset($t_sub_item_movement2)){if(count($t_sub_item_movement2)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement2);}}
      }   
    }

    public function set_delete(){
      $this->db->where("nno", $this->max_no);
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']); 
      $this->db->delete("t_adjustment_det");

      $this->load->model('trans_settlement');
      $this->trans_settlement->delete_item_movement('t_item_movement',15,$_POST['hid']);
    }

    
    public function check_code(){
      $this->db->where('code', $_POST['code']);
    	$this->db->limit(1);
    	echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function delete(){
    	$this->db->trans_begin();
      error_reporting(E_ALL); 
      function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
          throw new Exception($errMsg); 
      }
      set_error_handler('exceptionThrower'); 
      try { 
        if($this->user_permissions->is_edit('t_adjustment_sum')){
          $data = array(
           "is_cancel" => '1'
          );
          $this->db->where("nno", $_POST['code']);
          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']); 
          $this->db->update("t_adjustment_sum",$data);

          $this->load->model('trans_settlement');
          $this->trans_settlement->delete_item_movement('t_item_movement',15,$_POST['code']);

          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->where('trans_code',15);
          $this->db->where('trans_no',$_POST['code']);
          $this->db->delete('t_account_trans');

          echo $this->db->trans_commit();
        }else{
          echo "No permission to delete records";
          $this->db->trans_commit();
        }

      }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo $e->getMessage()."Operation fail please contact admin"; 
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

    public function item_list_all(){
    
      if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}               
   
      $sql="SELECT IFNULL(`qry_current_stock`.qty,0) AS `cur_qty` ,
                  `m_item`.`code` AS Item,
                  `t_item_batch`.`purchase_price` AS `cost`,
                  IFNULL( `qry_current_stock`.`qty`,0) AS Qty ,
                  `qry_current_stock`.`batch_no` as batch_item,  
                  `m_item`.`code` , 
                  `m_item`.`description`
          FROM `m_item`  
          LEFT JOIN (SELECT * FROM `qry_current_stock` WHERE store_code='$_POST[stores]') as `qry_current_stock` ON `m_item`.`code`=`qry_current_stock`.`item`
          LEFT JOIN t_item_batch ON t_item_batch.`item` = m_item.`code` AND qry_current_stock.batch_no = t_item_batch.batch_no
          WHERE (`m_item`.`code` LIKE '%$_POST[search]%' 
            OR `m_item`.`purchase_price` LIKE '%$_POST[search]%'
            OR `m_item`.`description`  LIKE '%$_POST[search]%') 
          AND `m_item`.`inactive`='0'
          -- AND `m_item`.`serial_no` ='0'
          GROUP BY  `m_item`.`code`,t_item_batch.batch_no
          
          
          LIMIT 25";
  
      $query=$this->db->query($sql);
      $a = "<table id='item_list' style='width : 100%' >";
      
      $a .= "<thead><tr>";
          $a .= "<th class='tb_head_th'>Code</th>";
          $a .= "<th class='tb_head_th'>Item Name</th>";
          $a .= "<th class='tb_head_th'>Batch</th>";
          $a .= "<th class='tb_head_th'>Cur QTY</th>";
          $a .= "<th class='tb_head_th'>Cost</th>";
       
      $a .= "</thead></tr>";
          $a .= "<tr class='cl'>";
                 
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
                  $a .= "<td>".$r->batch_item."</td>";
                  $a .= "<td>".$r->cur_qty."</td>";
                  $a .= "<td>".$r->cost."</td>";
                  
              $a .= "</tr>";
          }
      $a .= "</table>";
      
      echo $a;
    }





    public function load(){
      $this->db->select(array(
        't_adjustment_sum.ddate' ,
        't_adjustment_sum.ref_no',
        't_adjustment_sum.memo',
        't_adjustment_sum.store',
        't_adjustment_sum.ref_no',
        't_adjustment_sum.net_amount', 
        't_adjustment_sum.is_cancel', 
      ));

      $this->db->from('t_adjustment_sum');
      $this->db->where('t_adjustment_sum.cl',$this->sd['cl'] );
      $this->db->where('t_adjustment_sum.bc',$this->sd['branch'] );
      $this->db->where('t_adjustment_sum.nno',$_POST['id']);
      $query=$this->db->get();
                
      $x=0;
      if($query->num_rows()>0){
        $a['sum']=$query->result();
      }else{
        $x=2;
      }

      $this->db->select(array(              
        't_adjustment_det.code',
        't_adjustment_det.cur_qty',
        't_adjustment_det.f_qty',
        't_adjustment_det.s_qty',
        't_adjustment_det.t_qty',
        't_adjustment_det.difference',
        't_adjustment_det.is_serial',
        't_adjustment_det.status',
        't_adjustment_det.price',
        't_adjustment_det.cost',
        't_adjustment_det.batch_no',
        'm_item.description'
      ));

      $this->db->from('t_adjustment_det'); 
      $this->db->join('m_item','t_adjustment_det.code=m_item.code');           
      $this->db->where('t_adjustment_det.cl',$this->sd['cl'] );
      $this->db->where('t_adjustment_det.bc',$this->sd['branch'] );
      $this->db->where('t_adjustment_det.nno',$_POST['id']);
      $query=$this->db->get();

      if($query->num_rows()>0){
        $a['det']=$query->result();
      }else{
        $x=2;
      }   
       
      $this->db->select(array(
        't_adjustment_sum.category' ,
        'r_category.description as cat_description' 
      ));

      $this->db->from('r_category'); 
      $this->db->join('t_adjustment_sum','r_category.code=t_adjustment_sum.category');  
      $this->db->where('t_adjustment_sum.cl',$this->sd['cl'] );
      $this->db->where('t_adjustment_sum.bc',$this->sd['branch'] );
      $this->db->where('t_adjustment_sum.nno',$_POST['id']);        
      $query=$this->db->get();

      if($query->num_rows()>0){
        $a['cat']=$query->result();
      }

      if($x==0){
        echo json_encode($a);
      }else{
        echo json_encode($x);
      }
    }

public function is_serial(){
  $code = $_POST['item'];

  $sql = "SELECT serial_no FROM m_item WHERE code='$code'";

  $query = $this->db->query($sql);

  if($query->num_rows() > 0){
    if($query->row()->serial_no == "1"){
       $a = 1;
    }else{
      $a = 2;
    }   
  }else{
    $a = 2;
  }
  echo json_encode($a);
}

public function is_status(){
  $code = $_POST['code'];
  $item = $_POST['item'];

  $sql="SELECT adjustment_no FROM t_serial_adjustment_sum 
        WHERE adjustment_no='$code' 
        AND item_id='$item'
        AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";

  $query = $this->db->query($sql);

  if($query->num_rows()>0){
    $a=1;
  }else{
    $a=2;
  }
  echo json_encode($a);
}


public function PDF_report() {
    $this->db->select(array('name', 'address', 'tp', 'fax', 'email'));
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $r_detail['branch'] = $this->db->get('m_branch')->result();


    $invoice_number= $this->utility->invoice_format($_POST['qno']);
    $session_array = array(
         $this->sd['cl'],
         $this->sd['branch'],
         $invoice_number
    );
    $r_detail['session'] = $session_array;
   
    $sql_s="select * FROM m_stores where code='".$_POST['str']."'";
    $store_query= $this->db->query($sql_s);
    $r_detail['store'] = $store_query->result(); 

    $r_detail['type'] = $_POST['type'];
    $r_detail['dt'] = $_POST['dt'];
    $r_detail['qno'] = $_POST['qno'];
    $r_detail['memo'] = $_POST['mem'];

    $r_detail['page'] = $_POST['page'];
    $r_detail['header'] = $_POST['header'];
    $r_detail['orientation'] = $_POST['orientation'];

    $sql="SELECT s.nno,
                 d.`code`,
                 m.description,
                 d.`batch_no`,
                 d.cur_qty,
                 d.`f_qty`,
                 d.`s_qty`,
                 d.`t_qty`,
                 d.`difference`,
                 d.`cost`,
                 d.`price` AS total,
                 IF(d.`status`='1','OK','Pending')AS Status   
          FROM t_adjustment_sum s
          JOIN t_adjustment_det d ON d.`cl`=s.`cl` AND s.bc=d.`bc` AND s.`nno`=d.`nno`
          JOIN m_item m ON m.code = d.`code`
          WHERE s.`nno`='".$_POST['qno']."' AND s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."'";

    $query = $this->db->query($sql);
    $r_detail['items'] = $this->db->query($sql)->result();

    $this->db->select(array('loginName'));
    $this->db->where('cCode', $this->sd['oc']);
    $r_detail['user'] = $this->db->get('users')->result();

    $this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
}

public function get_cur_qty(){
  $sql="SELECT qty
        FROM qry_current_stock
        WHERE cl='".$this->sd['cl']."' 
        AND bc='".$this->sd['branch']."' 
        AND store_code='".$_POST['store_code']."' 
        AND batch_no='".$_POST['batch']."' 
        AND item='".$_POST['item']."'";

  $query= $this->db->query($sql);

  if($query->num_rows()>0){
    $result=$query->row()->qty;
  }else{
    $result=0;
  }
  echo $result;
}




















}
