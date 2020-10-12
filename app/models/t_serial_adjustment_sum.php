<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class t_serial_adjustment_sum extends CI_Model {
  private $max_no;
  private $mod='003';
  function __construct() {
    parent::__construct();
    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->m_stores          = $this->tables->tb['m_stores'];
    $this->qry_current_stock = $this->tables->tb['qry_current_stock'];
    $this->load->model("utility");
    $this->max_no = $this->utility->get_max_no("t_serial_adjustment_sum", "nno");
    $this->load->model('user_permissions');
  }



  public function base_details() {
    $a['id'] = $this->utility->get_max_no("t_serial_adjustment_sum", "nno");
    $this->db->select_max('nno', 'max_no');
    $this->db->where('cl', $this->sd['cl']);
    $this->db->where('bc', $this->sd['branch']);
    $query = $this->db->get("t_serial_adjustment_sum");
    foreach ($query->result() as $value) {
      $max_no = $value->max_no;
    }
    return $a;
  }



  public function validation() {
    $status  = 1;
    
    // $check_is_delete = $this->validation->check_is_cancel($this->max_no, 't_serial_adjustment_sum');
    // if ($check_is_delete != 1) {
    //   return "Damages already deleted";
    // }

    $check_item = $this->validation->check_is_item($_POST['item']);
    if ($check_item != 1) {
      return $check_item;
    }
    $check_store = $this->validation->check_is_store($_POST['store']);
    if ($check_store != 1) {
      return $check_store;
    }
    $check_qty = $this->check_save_limit($_POST['n_stock_hid'],$_POST['n_stock']);
    if ($check_qty != 1) {
      return $check_qty;
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
      $validation_status=$this->validation();
      if($validation_status==1){

       $sum=array(
        "cl" => $this->sd['cl'],
        "bc" => $this->sd['branch'],
        "nno" => $this->max_no,
        "ddate" => $_POST['ddate'],
        "store_id" => $_POST['store'],
        "item_id" => $_POST['item'],
        "description" => $_POST['item_des'],
        "batch_no" => $_POST['batch'],
        "current_stock" => $_POST['c_stock'],
        "new_stock" => $_POST['n_stock'],
        "adjustment_no"=> $_POST['adj_no'],
        "oc" => $this->sd['oc']
      );

       for($x = 0; $x<$_POST['no_row']; $x++){
        if(isset($_POST['serial_'.$x])){
          if($_POST['serial_'.$x] != ""){ 

            $det[]= array(
              "cl" => $this->sd['cl'],
              "bc" => $this->sd['branch'],
              "nno" => $this->max_no,
              "serial_no" => $_POST['serial_'.$x],
              "other_no1" => $_POST['other1_'.$x],
              "other_no2" => $_POST['other2_'.$x] 
            );
          }
        }     
      }
      $s_qty_zero=1;
      $new_sserials=2;

      if(isset($det)){
        $s_qty_zero=1;
      }else{
        $s_qty_zero=2;
      }

      if($s_qty_zero==1){
        
        $sql="SELECT serial_no
        FROM t_serial
        WHERE item='".$_POST['item']."'
        AND store_code = '".$_POST['store']."'
        AND batch='".$_POST['batch']."'
        AND cl='".$this->sd['cl']."'
        AND bc='".$this->sd['branch']."'
        AND available = 1";

        $query=$this->db->query($sql);
        $result = $query->result_array();
        
        if(count($result)==0){
          $new_sserials = 1;
        }

        $count = count($det);
        $count2 = count($result);

        $res=array();
        $res_temp=array();
        $res_temp4=array();
        $xy=0;
        foreach($query->result() as $row){
          for($x = 0; $x<$_POST['no_row']; $x++){
              //var_dump($row->serial_no."!=".$_POST['serial_'.$x]."---".$xy);
            if($xy==1){
              $sa=count($res);
              for($y=0;$sa>$y;$y++){
               if($row->serial_no!=$res[$y]["serial_no"] && $res[$y]["serial_no"]!=""){
                 $res_temp[]=$res[$y];
                 $res_temp4[]=$serial_movement_in[$y];
               }      
             }
             $res=$res_temp;
             $serial_movement_in=$res_temp4;
             unset($res_temp);
             unset($res_temp4);
             $res_temp=array();
             $res_temp4=array();
           }else{

            if($row->serial_no!=$_POST['serial_'.$x]){
              $res[]= array(
                "cl" => $this->sd['cl'],
                "bc" => $this->sd['branch'],
                "trans_type" => 39,
                "trans_no" => $this->max_no,
                "date" => $_POST['ddate'],
                "item" => $_POST['item'],
                "batch" => $_POST['batch'],
                "serial_no" => $_POST['serial_'.$x],
                "other_no1" => $_POST['other1_'.$x],
                "other_no2" => $_POST['other2_'.$x],
                "cost"=>$this->utility->get_cost_price($_POST['item']),
                "max_price"=>$this->utility->get_max_price($_POST['item']),
                "last_price"=>$this->utility->get_min_price($_POST['item']),
                "store_code"=>$_POST['store'],
                "engine_no"=>"",
                "chassis_no"=>'',
                "available" => 1,
                "out_doc"=>'',
                "out_no"=>'',
                "out_date"=>''        
              );

              $serial_movement_in[]= array(
                "cl"=>$this->sd['cl'],
                "bc"=>$this->sd['branch'],
                "trans_type"=>39,
                "trans_no"=>$this->max_no,
                "item"=>$_POST['item'],
                "batch_no"=>$_POST['batch'],
                "serial_no"=>$_POST['serial_'.$x],
                "qty_in"=>1,
                "qty_out"=>0,
                "cost"=>$this->utility->get_cost_price($_POST['item']),
                "store_code"=>$_POST['store'],
                "computer"=>$this->input->ip_address(),
                "oc"=>$this->sd['oc']   
              );
            }    
                //}
          }
        }
        $xy=1;
      }
      


      $res2=array();
      $res3=array();
      $res_temp2=array();
      $res_temp3=array();
      $xy2=0;
      for($xx = 0; $xx<$_POST['no_row']; $xx++){
        foreach($query->result() as $row){
          if($xy2==1){
            $sa2=count($res2);
            for($yy=0;$sa2>$yy;$yy++){
              if($_POST['serial_'.$xx]!=$res2[$yy]["serial_no"] && $res2[$yy]["serial_no"]!=""){
                $res_temp2[]=$res2[$yy];
                $res_temp3[]=$serial_movement_out[$yy];
              }      
            }
            $res2=$res_temp2;
            $serial_movement_out=$res_temp3;
            unset($res_temp2);
            unset($res_temp3);
            $res_temp2=array(); 
            $res_temp3=array();   
          }else{
                //if(isset($_POST['serial_'.$xx])){
            if($row->serial_no!=$_POST['serial_'.$xx]){
              $res2[]= array(
                "cl" => $this->sd['cl'],
                "bc" => $this->sd['branch'],
                "trans_type" => 39,
                "trans_no" => $this->max_no,
                "date" => $_POST['ddate'],
                "item" => $_POST['item'],
                "batch" => $_POST['batch'],
                "serial_no" => $row->serial_no,
                "other_no1" => $_POST['other1_'.$xx],
                "other_no2" => $_POST['other2_'.$xx],
                "cost"=>$this->utility->get_cost_price($_POST['item']),
                "max_price"=>$this->utility->get_max_price($_POST['item']),
                "last_price"=>$this->utility->get_min_price($_POST['item']),
                "store_code"=>$_POST['store'],
                "engine_no"=>"",
                "chassis_no"=>'',
                "out_doc"=>'',
                "out_no"=>'',
                "out_date"=>''
              );  


              $serial_movement_out[]= array(
                "cl"=>$this->sd['cl'],
                "bc"=>$this->sd['branch'],
                "trans_type"=>39,
                "trans_no"=>$this->max_no,
                "item"=>$_POST['item'],
                "batch_no"=>$_POST['batch'],
                "serial_no"=>$row->serial_no,
                "qty_in"=>0,
                "qty_out"=>1,
                "cost"=>$this->utility->get_cost_price($_POST['item']),
                "store_code"=>$_POST['store'],
                "computer"=>$this->input->ip_address(),
                "oc"=>$this->sd['oc']
              ); 

              
            }
                //}  
          }
        }
        $xy2=1;
      }
      
      $data=array(
        "available"=>0,
        "out_doc"=>39,
        "out_no"=>$this->max_no,
        "out_date"=>$_POST['ddate'],
      );

      $current_qty =(int)$_POST['c_stock'];
      $availabale_qty =(int)$_POST['n_stock'];
      $acctual = (int)0;
      $acctual = $current_qty - $availabale_qty;

      if($acctual < 0){
            //item movement in;
        $t_item_movement=array(
          "cl"=>$this->sd['cl'],
          "bc"=>$this->sd['branch'],
          "item"=>$_POST['item'],
          "trans_code"=>39,
          "trans_no"=>$this->max_no,
          "ddate"=>$_POST['ddate'],
          "qty_in"=>abs($acctual),
          "qty_out"=>0,
          "store_code"=>$_POST['store'],
          "batch_no"=>$_POST['batch'],
          "avg_price"=>$this->utility->get_cost_price($_POST['item']),
          "sales_price"=>$this->utility->get_cost_price($_POST['item']),
          "last_sales_price"=>$this->utility->get_min_price($_POST['item']),
          "cost"=>$this->utility->get_cost_price($_POST['item']),
        );
      }else if ($acctual > 0){
            //item movement out;
        $t_item_movement=array(
          "cl"=>$this->sd['cl'],
          "bc"=>$this->sd['branch'],
          "item"=>$_POST['item'],
          "trans_code"=>39,
          "trans_no"=>$this->max_no,
          "ddate"=>$_POST['ddate'],
          "qty_in"=>0,
          "qty_out"=>abs($acctual),
          "store_code"=>$_POST['store'],
          "batch_no"=>$_POST['batch'],
          "avg_price"=>$this->utility->get_cost_price($_POST['item']),
          "sales_price"=>$this->utility->get_cost_price($_POST['item']),
          "last_sales_price"=>$this->utility->get_min_price($_POST['item']),
          "cost"=>$this->utility->get_cost_price($_POST['item']),
        );
      }
    }
    if($_POST['hid'] == "0" || $_POST['hid'] == ""){
      if($this->user_permissions->is_add('t_serial_adjustment_sum')){

        if($s_qty_zero!=1 && $_POST['n_stock']==0){
          $this->remove_all_serials();
        }else if($new_sserials==1){
          $this->add_all_serials();
        }else{
          $this->db->insert('t_serial_adjustment_sum',$sum);
          if(count($det)){$this->db->insert_batch("t_serial_adjustment_det",$det);}
              //if(isset($t_item_movement)){$this->db->insert('t_item_movement',$t_item_movement);}
          if(isset($res)){if(count($res)){$this->db->insert_batch("t_serial", $res);}}
          
          if(isset($res2)){
            foreach($res2 as $row){
              $this->db->where("cl",$row['cl']);
              $this->db->where("bc",$row['bc']);
              $this->db->where("item",$row['item']);
              $this->db->where("serial_no",$row['serial_no']);
              $this->db->update("t_serial",$data);  
            }
          }

          if(isset($serial_movement_in)){
            if(count($serial_movement_in)){
              $this->db->insert_batch("t_serial_movement", $serial_movement_in);
            }
          }

          if(isset($serial_movement_out)){
            foreach ($serial_movement_out as $key) {     
              $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='".$key['item']."' AND serial_no='".$key['serial_no']."'");
              
              $this->db->where("item", $key['item']);
              $this->db->where("serial_no", $key['serial_no']);
              $this->db->delete("t_serial_movement");
            }
          }

          if(isset($serial_movement_out)){
            if(count($serial_movement_out)){
              $this->db->insert_batch("t_serial_movement_out", $serial_movement_out);
            }
          }
        }
            //----check above serial movement section and add to update section--
        $this->utility->save_logger("SAVE",39,$this->max_no,$this->mod);
        echo $this->db->trans_commit(); 
      }else{
        echo "No permission to save records";
        $this->db->trans_commit();
      }   
    }else{
      if($this->user_permissions->is_edit('t_serial_adjustment_sum')){
        $this->db->where("nno", $_POST['hid']);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->delete("t_serial_adjustment_det");

        if(count($det)){$this->db->insert_batch("t_serial_adjustment_det",$det);}

        $this->db->where("nno", $_POST['hid']);
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->update("t_serial_adjustment_sum", $sum);

        if(isset($res)){if(count($res)){$this->db->insert_batch("t_serial", $res);}}

        if(isset($res2)){
          foreach($res2 as $row){
            $this->db->where("cl",$row['cl']);
            $this->db->where("bc",$row['bc']);
            $this->db->where("item",$row['item']);
            $this->db->where("serial_no",$row['serial_no']);
            $this->db->update("t_serial",$data);  
          }
        }

        if(isset($serial_movement_in)){
          if(count($serial_movement_in)){
            $this->db->insert_batch("t_serial_movement", $serial_movement_in);
          }
        }

        if(isset($serial_movement_out)){
          foreach ($serial_movement_out as $key) {     
            $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='".$key['item']."' AND serial_no='".$key['serial_no']."'");
            
            $this->db->where("item", $key['item']);
            $this->db->where("serial_no", $key['serial_no']);
            $this->db->delete("t_serial_movement");
          }
        }

        if(isset($serial_movement_out)){
          if(count($serial_movement_out)){
            $this->db->insert_batch("t_serial_movement_out", $serial_movement_out);
          }
        }
        $this->utility->save_logger("EDIT",39,$this->max_no,$this->mod);
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
  echo $e->getMessage()."Operation fail please contact admin"; 
} 
}

public function add_all_serials(){
  for($x = 0; $x<$_POST['no_row']; $x++){
    $res[]= array(
      "cl" => $this->sd['cl'],
      "bc" => $this->sd['branch'],
      "trans_type" => 39,
      "trans_no" => $this->max_no,
      "date" => $_POST['ddate'],
      "item" => $_POST['item'],
      "batch" => $_POST['batch'],
      "serial_no" => $_POST['serial_'.$x],
      "other_no1" => $_POST['other1_'.$x],
      "other_no2" => $_POST['other2_'.$x],
      "cost"=>$this->utility->get_cost_price($_POST['item']),
      "max_price"=>$this->utility->get_max_price($_POST['item']),
      "last_price"=>$this->utility->get_min_price($_POST['item']),
      "store_code"=>$_POST['store'],
      "engine_no"=>"",
      "chassis_no"=>'',
      "available" => 1,
      "out_doc"=>'',
      "out_no"=>'',
      "out_date"=>''        
    );

    $serial_movement_in[]= array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_type"=>39,
      "trans_no"=>$this->max_no,
      "item"=>$_POST['item'],
      "batch_no"=>$_POST['batch'],
      "serial_no"=>$_POST['serial_'.$x],
      "qty_in"=>1,
      "qty_out"=>0,
      "cost"=>$this->utility->get_cost_price($_POST['item']),
      "store_code"=>$_POST['store'],
      "computer"=>$this->input->ip_address(),
      "oc"=>$this->sd['oc']   
    );
  }

  if(isset($res)){if(count($res)){$this->db->insert_batch("t_serial", $res);}}
  if(isset($serial_movement_in)){
    if(count($serial_movement_in)){
      $this->db->insert_batch("t_serial_movement", $serial_movement_in);
    }
  }

}

public function remove_all_serials(){
  $sum=array(
    "cl" => $this->sd['cl'],
    "bc" => $this->sd['branch'],
    "nno" => $this->max_no,
    "ddate" => $_POST['ddate'],
    "store_id" => $_POST['store'],
    "item_id" => $_POST['item'],
    "description" => $_POST['item_des'],
    "batch_no" => $_POST['batch'],
    "current_stock" => $_POST['c_stock'],
    "new_stock" => $_POST['n_stock'],
    "adjustment_no"=> $_POST['adj_no'],
    "oc" => $this->sd['oc']
  );
  $data1=array(
    "available"=>0,
    "out_doc"=>39,
    "out_no"=>$this->max_no,
    "out_date"=>$_POST['ddate'],
  );
  $this->db->insert('t_serial_adjustment_sum',$sum);

  $sql="SELECT cl,bc,item,serial_no,other_no1,other_no2
  FROM t_serial
  WHERE item='".$_POST['item']."'
  AND store_code = '".$_POST['store']."'
  AND batch='".$_POST['batch']."'
  AND cl='".$this->sd['cl']."'
  AND bc='".$this->sd['branch']."'
  AND available = 1";

  $query = $this->db->query($sql);

  foreach($query->result() as $r){
    $det[]= array(
      "cl" => $this->sd['cl'],
      "bc" => $this->sd['branch'],
      "nno" => $this->max_no,
      "serial_no" => $r->serial_no,
      "other_no1" => $r->other_no1,
      "other_no2" => $r->other_no2 
    );

    $serial_movement_out[]= array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_type"=>39,
      "trans_no"=>$this->max_no,
      "item"=>$_POST['item'],
      "batch_no"=>$_POST['batch'],
      "serial_no"=>$r->serial_no,
      "qty_in"=>0,
      "qty_out"=>1,
      "cost"=>$this->utility->get_cost_price($_POST['item']),
      "store_code"=>$_POST['store'],
      "computer"=>$this->input->ip_address(),
      "oc"=>$this->sd['oc']
    ); 

    $this->db->where("cl",$r->cl);
    $this->db->where("bc",$r->bc);
    $this->db->where("item",$r->item);
    $this->db->where("serial_no",$r->serial_no);
    $this->db->update("t_serial",$data1); 

    $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='".$r->item."' AND serial_no='".$r->serial_no."'");

    $this->db->where("item", $r->item);
    $this->db->where("serial_no", $r->serial_no);
    $this->db->delete("t_serial_movement"); 
  }
  if(count($det)){$this->db->insert_batch("t_serial_adjustment_det",$det);}
  if(isset($serial_movement_out)){
    if(count($serial_movement_out)){
      $this->db->insert_batch("t_serial_movement_out", $serial_movement_out);
    }
  }
}



  /*public function save(){
    
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
      throw new Exception($errLine); 
    }
    set_error_handler('exceptionThrower'); 
    try { 
      $validation_status=$this->validation();
        if($validation_status==1){

         $sum=array(
          "cl" => $this->sd['cl'],
          "bc" => $this->sd['branch'],
          "nno" => $this->max_no,
          "ddate" => $_POST['ddate'],
          "store_id" => $_POST['store'],
          "item_id" => $_POST['item'],
          "description" => $_POST['item_des'],
          "batch_no" => $_POST['batch'],
          "current_stock" => $_POST['c_stock'],
          "new_stock" => $_POST['n_stock'],
          "adjustment_no"=> $_POST['adj_no'],
          "oc" => $this->sd['oc']
        );

        for($x = 0; $x<=$_POST['no_row']; $x++){
          if(isset($_POST['serial_'.$x])){
            if($_POST['serial_'.$x] != ""){ 

              $det[]= array(
                "cl" => $this->sd['cl'],
                "bc" => $this->sd['branch'],
                "nno" => $this->max_no,
                "serial_no" => $_POST['serial_'.$x],
                "other_no1" => $_POST['other1_'.$x],
                "other_no2" => $_POST['other2_'.$x] 
              );
            }
          }     
        }

        $s_qty_zero=1;
        $new_sserials=2;

        if(isset($det)){
          $s_qty_zero=1;
        }else{
          $s_qty_zero=2;
        }

              
         $sql="SELECT serial_no
          FROM t_serial
          WHERE item='".$_POST['item']."'
          AND store_code = '".$_POST['store']."'
          AND batch='".$_POST['batch']."'
          AND cl='".$this->sd['cl']."'
          AND bc='".$this->sd['branch']."'
          AND available = 1";

          $query=$this->db->query($sql);
          $result = $query->result_array();

          $count = count($det);
          $count2 = count($result);

          $res=array();
          $res_temp=array();
          $res_temp4=array();
          $xy=0;
          foreach($query->result() as $row){
            for($x = 0; $x<$_POST['no_row']+1; $x++){
              if($xy==1){
                $sa=count($res);
                 for($y=0;$sa>$y;$y++){
                   if($row->serial_no!=$res[$y]["serial_no"] && $res[$y]["serial_no"]!=""){
                     $res_temp[]=$res[$y];
                     $res_temp4[]=$serial_movement_in[$y];
                   }      
                 }
                 $res=$res_temp;
                 $serial_movement_in=$res_temp4;
                 unset($res_temp);
                 unset($res_temp4);
                 $res_temp=array();
                 $res_temp4=array();
              }else{
               // if(isset($_POST['serial_'.$x])){


                  if($row->serial_no!=$_POST['serial_'.$x]){

                    $res[]= array(
                      "cl" => $this->sd['cl'],
                      "bc" => $this->sd['branch'],
                      "trans_type" => 39,
                      "trans_no" => $this->max_no,
                      "date" => $_POST['ddate'],
                      "item" => $_POST['item'],
                      "batch" => $_POST['batch'],
                      "serial_no" => $_POST['serial_'.$x],
                      "other_no1" => $_POST['other1_'.$x],
                      "other_no2" => $_POST['other2_'.$x],
                      "cost"=>$this->utility->get_cost_price($_POST['item']),
                      "max_price"=>$this->utility->get_max_price($_POST['item']),
                      "last_price"=>$this->utility->get_min_price($_POST['item']),
                      "store_code"=>$_POST['store'],
                      "engine_no"=>"",
                      "chassis_no"=>'',
                      "available" => 1,
                      "out_doc"=>'',
                      "out_no"=>'',
                      "out_date"=>''        
                    );

                    $serial_movement_in[]= array(
                      "cl"=>$this->sd['cl'],
                      "bc"=>$this->sd['branch'],
                      "trans_type"=>39,
                      "trans_no"=>$this->max_no,
                      "item"=>$_POST['item'],
                      "batch_no"=>$_POST['batch'],
                      "serial_no"=>$_POST['serial_'.$x],
                      "qty_in"=>1,
                      "qty_out"=>0,
                      "cost"=>$this->utility->get_cost_price($_POST['item']),
                      "store_code"=>$_POST['store'],
                      "computer"=>$this->input->ip_address(),
                      "oc"=>$this->sd['oc']   
                    );
                  }    
                //}
              }
            }
            $xy=1;
          }
         


          $res2=array();
          $res3=array();
          $res_temp2=array();
          $res_temp3=array();
          $xy2=0;
            for($xx = 0; $xx<$_POST['no_row']+1; $xx++){
              foreach($query->result() as $row){
              if($xy2==1){
                $sa2=count($res2);
                  for($yy=0;$sa2>$yy;$yy++){
                    if($_POST['serial_'.$xx]!=$res2[$yy]["serial_no"] && $res2[$yy]["serial_no"]!=""){
                      $res_temp2[]=$res2[$yy];
                      $res_temp3[]=$serial_movement_out[$yy];
                    }      
                  }
                 $res2=$res_temp2;
                 $serial_movement_out=$res_temp3;
                 unset($res_temp2);
                 unset($res_temp3);
                 $res_temp2=array(); 
                 $res_temp3=array();   
              }else{
                //if(isset($_POST['serial_'.$xx])){
                  if($row->serial_no!=$_POST['serial_'.$xx]){
                    $res2[]= array(
                      "cl" => $this->sd['cl'],
                      "bc" => $this->sd['branch'],
                      "trans_type" => 39,
                      "trans_no" => $this->max_no,
                      "date" => $_POST['ddate'],
                      "item" => $_POST['item'],
                      "batch" => $_POST['batch'],
                      "serial_no" => $row->serial_no,
                      "other_no1" => $_POST['other1_'.$xx],
                      "other_no2" => $_POST['other2_'.$xx],
                      "cost"=>$this->utility->get_cost_price($_POST['item']),
                      "max_price"=>$this->utility->get_max_price($_POST['item']),
                      "last_price"=>$this->utility->get_min_price($_POST['item']),
                      "store_code"=>$_POST['store'],
                      "engine_no"=>"",
                      "chassis_no"=>'',
                      "out_doc"=>'',
                      "out_no"=>'',
                      "out_date"=>''
                    );  


                    $serial_movement_out[]= array(
                      "cl"=>$this->sd['cl'],
                      "bc"=>$this->sd['branch'],
                      "trans_type"=>39,
                      "trans_no"=>$this->max_no,
                      "item"=>$_POST['item'],
                      "batch_no"=>$_POST['batch'],
                      "serial_no"=>$row->serial_no,
                      "qty_in"=>0,
                      "qty_out"=>1,
                      "cost"=>$this->utility->get_cost_price($_POST['item']),
                      "store_code"=>$_POST['store'],
                      "computer"=>$this->input->ip_address(),
                      "oc"=>$this->sd['oc']
                    ); 

                    
                  }
                //}  
              }
            }
            $xy2=1;
          }
          
          $data=array(
            "available"=>0,
            "out_doc"=>39,
            "out_no"=>$this->max_no,
            "out_date"=>$_POST['ddate'],
          );

          $current_qty =(int)$_POST['c_stock'];
          $availabale_qty =(int)$_POST['n_stock'];
          $acctual = (int)0;
          $acctual = $current_qty - $availabale_qty;

          if($acctual < 0){
            //item movement in;
            $t_item_movement=array(
              "cl"=>$this->sd['cl'],
              "bc"=>$this->sd['branch'],
              "item"=>$_POST['item'],
              "trans_code"=>39,
              "trans_no"=>$this->max_no,
              "ddate"=>$_POST['ddate'],
              "qty_in"=>abs($acctual),
              "qty_out"=>0,
              "store_code"=>$_POST['store'],
              "batch_no"=>$_POST['batch'],
              "avg_price"=>$this->utility->get_cost_price($_POST['item']),
              "sales_price"=>$this->utility->get_cost_price($_POST['item']),
              "last_sales_price"=>$this->utility->get_min_price($_POST['item']),
              "cost"=>$this->utility->get_cost_price($_POST['item']),
            );
          }else if ($acctual > 0){
            //item movement out;
            $t_item_movement=array(
              "cl"=>$this->sd['cl'],
              "bc"=>$this->sd['branch'],
              "item"=>$_POST['item'],
              "trans_code"=>39,
              "trans_no"=>$this->max_no,
              "ddate"=>$_POST['ddate'],
              "qty_in"=>0,
              "qty_out"=>abs($acctual),
              "store_code"=>$_POST['store'],
              "batch_no"=>$_POST['batch'],
              "avg_price"=>$this->utility->get_cost_price($_POST['item']),
              "sales_price"=>$this->utility->get_cost_price($_POST['item']),
              "last_sales_price"=>$this->utility->get_min_price($_POST['item']),
              "cost"=>$this->utility->get_cost_price($_POST['item']),
            );
          }

        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('t_serial_adjustment_sum')){
            $this->db->insert('t_serial_adjustment_sum',$sum);
            if(count($det)){$this->db->insert_batch("t_serial_adjustment_det",$det);}
            //if(isset($t_item_movement)){$this->db->insert('t_item_movement',$t_item_movement);}
            if(isset($res)){if(count($res)){$this->db->insert_batch("t_serial", $res);}}
           
            if(isset($res2)){
              foreach($res2 as $row){
                $this->db->where("cl",$row['cl']);
                $this->db->where("bc",$row['bc']);
                $this->db->where("item",$row['item']);
                $this->db->where("serial_no",$row['serial_no']);
                $this->db->update("t_serial",$data);  
              }
            }

            if(isset($serial_movement_in)){
              if(count($serial_movement_in)){
                $this->db->insert_batch("t_serial_movement", $serial_movement_in);
              }
            }

            if(isset($serial_movement_out)){
              foreach ($serial_movement_out as $key) {     
                $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='".$key['item']."' AND serial_no='".$key['serial_no']."'");
         
                $this->db->where("item", $key['item']);
                $this->db->where("serial_no", $key['serial_no']);
                $this->db->delete("t_serial_movement");
              }
            }

            if(isset($serial_movement_out)){
              if(count($serial_movement_out)){
                $this->db->insert_batch("t_serial_movement_out", $serial_movement_out);
              }
            }
            //----check above serial movement section and add to update section--
            $this->utility->save_logger("SAVE",39,$this->max_no,$this->mod);
            echo $this->db->trans_commit(); 
          }else{
              echo "No permission to save records";
              $this->db->trans_commit();
          }   
        }else{
          if($this->user_permissions->is_edit('t_serial_adjustment_sum')){
            $this->db->where("nno", $_POST['hid']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->delete("t_serial_adjustment_det");

            if(count($det)){$this->db->insert_batch("t_serial_adjustment_det",$det);}

            $this->db->where("nno", $_POST['hid']);
            $this->db->where("cl", $this->sd['cl']);
            $this->db->where("bc", $this->sd['branch']);
            $this->db->update("t_serial_adjustment_sum", $sum);

            if(isset($res)){if(count($res)){$this->db->insert_batch("t_serial", $res);}}

            if(isset($res2)){
              foreach($res2 as $row){
                $this->db->where("cl",$row['cl']);
                $this->db->where("bc",$row['bc']);
                $this->db->where("item",$row['item']);
                $this->db->where("serial_no",$row['serial_no']);
                $this->db->update("t_serial",$data);  
              }
            }

            if(isset($serial_movement_in)){
              if(count($serial_movement_in)){
                $this->db->insert_batch("t_serial_movement", $serial_movement_in);
              }
            }

            if(isset($serial_movement_out)){
              foreach ($serial_movement_out as $key) {     
                $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='".$key['item']."' AND serial_no='".$key['serial_no']."'");
         
                $this->db->where("item", $key['item']);
                $this->db->where("serial_no", $key['serial_no']);
                $this->db->delete("t_serial_movement");
              }
            }

            if(isset($serial_movement_out)){
              if(count($serial_movement_out)){
                $this->db->insert_batch("t_serial_movement_out", $serial_movement_out);
              }
            }
            $this->utility->save_logger("EDIT",39,$this->max_no,$this->mod);
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
        echo $e->getMessage()."Operation fail please contact admin"; 
    } 
  }

*/


  public function load() {
    $x=0;
    $id = $_POST['id'];
    
    $sql="SELECT  j.nno, 
    j.adjustment_no,
    j.ddate,
    j.store_id,
    j.item_id,
    j.description,
    j.batch_no,
    j.current_stock,
    j.new_stock,
    ms.`description` as store_des,
    i.`max_price`
    FROM t_serial_adjustment_sum j
    JOIN m_stores ms ON ms.`code` = j.`store_id` AND ms.`cl` = j.`cl` AND ms.`bc` = j.`bc`
    JOIN m_item i ON i.`code` = j.`item_id`
    WHERE j.nno = '$id' AND
    j.cl = '".$this->sd['cl']."' AND
    j.bc = '".$this->sd['branch']."'
    ";
    $query=$this->db->query($sql);

    if ($query->num_rows() > 0) {
      $a['sum'] = $query->result();
    } else {
      $x = 2;
    }

    $sql="SELECT * FROM t_serial_adjustment_det
    WHERE nno = '$id' AND
    cl = '".$this->sd['cl']."' AND
    bc = '".$this->sd['branch']."'
    ";

    $query=$this->db->query($sql);
    if ($query->num_rows() > 0) {
      $a['det'] = $query->result();
    } else {
      $x = 2;
    }


    if ($x == 0) {
      echo json_encode($a);
    } else {
      echo json_encode($x);
    }
  }




  public function serial_list_all(){
    $item=$_POST['item'];
    $batch=$_POST['batch'];
    $store=$_POST['store'];

    $sql="SELECT serial_no, other_no1, other_no2
    FROM t_serial
    WHERE item='$item'
    AND store_code = '$store'
    AND batch='$batch'
    AND cl='".$this->sd['cl']."'
    AND bc='".$this->sd['branch']."'
    AND available = 1";

    $query=$this->db->query($sql);  
    
    if($query->num_rows()>0){
      $a="";
      foreach ($query->result() as $r) {
        $a .= "<tr class='cl'>";
        $a .= "<td style='width:275px;'>".$r->serial_no."</td>";
        $a .= "<td style='width:300px;'>&nbsp;".$r->other_no1."</td>";
        $a .= "<td style='width:275px;'>&nbsp;".$r->other_no2."</td>";
        $a .="</tr>";
      }


      echo $a;
    }else{
      echo "0";
    }
  }

  public function serial_list_add(){
    $item=$_POST['item'];
    $batch=$_POST['batch'];
    $store=$_POST['store'];

    $sql="SELECT serial_no, other_no1, other_no2
    FROM t_serial
    WHERE item='$item'
    AND store_code = '$store'
    AND batch='$batch'
    AND cl='".$this->sd['cl']."'
    AND bc='".$this->sd['branch']."'
    AND available = 1";

    $query=$this->db->query($sql);  
    
    if($query->num_rows()>0){
      $a="";
      $x=(int)0;
      foreach ($query->result() as $r) {
        $a .= "<tr class='cl2'>";
        $a .= "<td style='width:275px;'>&nbsp;<input type='text' readonly='reaonly' class='se' name='serial_".$x."' id='serial_".$x."' class='g_input_txt' value='".$r->serial_no."'/></td>";
        $a .= "<td style='width:300px;'>&nbsp;<input type='text' readonly='reaonly' name='other1_".$x."' id='other1_".$x."' class='g_input_txt' value='".$r->other_no1."'/></td>";
        $a .= "<td style='width:275px;'>&nbsp;<input type='text' readonly='reaonly' name='other2_".$x."' id='other2_".$x."' class='g_input_txt' value='".$r->other_no2."'/></td>";
        $a .="</tr>";
        $x++;
      }


      echo $a;
    }else{
      echo "0";
    }
  }

  public function load_batch(){


    $item=$_POST['item'];
    $store=$_POST['store'];

    $sql="SELECT DISTINCT(batch_no) as batch,qty
    FROM m_item_batch_stock
    WHERE item_code='$item'
    AND store_code = '$store'
    AND cl='".$this->sd['cl']."'
    AND bc='".$this->sd['branch']."'
    HAVING qty>0";

    $query=$this->db->query($sql);  
    $x=(int)1; 

    $a=""; 
    $a .= "<option value='0' class='selctbtch'>-- Select Batch-- </option>";  
    foreach ($query->result() as $r) {
      $a .= "<option value='".$r->batch."' class='selctbtch'>".$r->batch."</option>";
      $x++;
    }


    echo $a;

    


    /*
    $item=$_POST['item'];
    $store=$_POST['store'];

    $sql="SELECT DISTINCT(batch)
          FROM t_serial
          WHERE item='$item'
          AND store_code = '$store'
          AND cl='".$this->sd['cl']."'
          AND bc='".$this->sd['branch']."'";

    $query=$this->db->query($sql);  
    $x=(int)1; 

    $a=""; 
    $a .= "<option value='0' class='selctbtch'>-- Select Batch-- </option>";  
      foreach ($query->result() as $r) {
        $a .= "<option value='".$r->batch."' class='selctbtch'>".$r->batch."</option>";
        $x++;
      }


      echo $a;
  
  */}
      
      public function auto_store(){
        $sql="SELECT `code`, `description` 
        FROM (`m_stores`) 
        WHERE `cl` = '".$this->sd['cl']."' AND `bc` = '".$this->sd['branch']."' 
        AND (`code` LIKE '%".$_GET['q']."%' OR `description` LIKE '%".$_GET['q']."%')";

        $query=$this->db->query($sql);
        $abc = "";
        foreach($query->result() as $r){
          $abc .= $r->code."|".$r->description;
          $abc .= "\n";
        }
        echo $abc;
      }

      public function auto_item(){
        $sql="SELECT `code`, `description` 
        FROM (`m_item`) 
        WHERE inactive='0' AND serial_no='1'
        AND (`code` LIKE '%".$_GET['q']."%' OR `description` LIKE '%".$_GET['q']."%')";

        $query=$this->db->query($sql);
        $abc = "";
        foreach($query->result() as $r){
          $abc .= $r->code."|".$r->description;
          $abc .= "\n";
        }
        echo $abc;
      }


      public function get_current(){
        $item=$_POST['item'];
        $batch=$_POST['batch'];
        $store_code=$_POST['store'];

        $sql="SELECT qry_current_stock.qty, qry_current_stock.max_price FROM qry_current_stock
        JOIN m_item ON m_item.`code`=qry_current_stock.`item`
        WHERE item='$item' AND
        cl='".$this->sd['cl']."' AND
        bc ='".$this->sd['branch']."' AND
        batch_no='$batch' AND
        store_code='$store_code'
        ";
        $query=$this->db->query($sql);


        if ($query->num_rows() > 0) {
          $a= $query->result();
        } else {
          $a= 2;
        }
        echo json_encode($a);
      }
      
      public function load_stores(){
        if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

          $sql="SELECT * from m_stores
          WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' 
          AND (code LIKE '%$_POST[search]%' OR description LIKE '%$_POST[search]%')";

          $query = $this->db->query($sql);
          $a  = "<table id='item_list' style='width : 100%' >";
          $a .= "<thead><tr>";
          $a .= "<th class='tb_head_th'>Code</th>";
          $a .= "<th colspan='2' class='tb_head_th' >Name</th>";
          
          $a .= "</thead></tr><tr class='cl'><td colspan='2'>&nbsp;</td></tr>";
          foreach($query->result() as $r){
            $a .= "<tr class='cl'>";
            $a .= "<td>".$r->code."</td>";
            $a .= "<td colspan='2'>".$r->description."</td>";
            $a .= "</tr>";
          }
          $a .= "</table>";  
          echo $a;
        }




        public function load_adjustment(){
          $no = $_POST['id'];
          $cl = $this->sd['cl'];
          $bc = $this->sd['branch'];
          if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
            $sql="SELECT
            s.nno,
            s.memo,
            d.`code`,
            d.`batch_no`,
            s.store,
            IFNULL(d.`f_qty`,0) AS f_qty,
            IFNULL(d.`s_qty`,0) AS s_qty,
            IFNULL(d.`t_qty`,0) AS t_qty,
            st.description as store_des,
            i.description as item_des
            FROM t_adjustment_sum s
            JOIN t_adjustment_det d ON d.`cl` = s.cl AND d.`bc` = s.bc AND d.`nno` = s.nno
            JOIN m_stores st ON st.`code` = s.store
            JOIN m_item i ON i.`code` = d.code
            WHERE s.nno='$no' AND s.cl='$cl' AND s.bc='$bc' and is_serial = '1' AND d.status='0'
            AND (s.nno LIKE '%$_POST[search]%' OR s.memo LIKE '%$_POST[search]%' OR d.code LIKE '%$_POST[search]%')
            GROUP BY d.`code`";

            $query = $this->db->query($sql);
            $a  = "<table id='item_list' style='width : 100%' >";
            $a .= "<thead><tr>";
            $a .= "<th class='tb_head_th'>No</th>";
            $a .= "<th class='tb_head_th' >Description</th>";
            $a .= "<th class='tb_head_th'>Item</th>";
            $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

            foreach($query->result() as $r){
              $qty="";
              if($r->t_qty != 0){
                $qty=$r->t_qty;
              }else if($r->s_qty != 0) {
                $qty=$r->s_qty;
              }else if($r->f_qty != 0) {
                $qty=$r->f_qty;
              }

              $a .= "<tr class='cl'>";
              $a .= "<td>".$r->nno."</td>";
              $a .= "<td>".$r->memo."</td>";
              $a .= "<td>".$r->code."</td>";
              $a .= "<td style='display:none;'>".$r->batch_no."</td>
              <td style='display:none;'>".$r->store."</td>
              <td style='display:none;'>".$qty."</td>
              <td style='display:none;'>".$r->store_des."</td>
              <td style='display:none;'>".$r->item_des."</td>";       
              $a .= "</tr>";
            }
            $a .= "</table>";
            echo $a;
          }

          public function check_save_limit($save_limit,$new_qty){
            $status = 1;

            if((int)$save_limit!=(int)$new_qty){
              $status="New Serial Quantity Should Be ('".$save_limit."')";
            }

            return $status;
          }























        }
        ?>