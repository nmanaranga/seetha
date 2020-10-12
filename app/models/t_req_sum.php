<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_req_sum extends CI_Model {
    
    private $sd;
    private $mtb;
    private $max_no;
    private $mod = '003';
    
    function __construct(){
	    parent::__construct();
  	  $this->sd = $this->session->all_userdata();
      $this->load->model('user_permissions');
      $this->load->database($this->sd['db'], true);
  	  $this->mtb = $this->tables->tb['m_items'];
      $this->tb_sum= $this->tables->tb['t_req_sum'];
      $this->load->model('utility');
    }
    
    public function base_details(){
      $a['max_no']= $this->utility->get_max_no("t_req_sum","nno");
      $a['branch']=$this->branch();
      $a['det_box']=$this->pending_requisition();
      return $a;
    }

    public function branch(){
      $this->db->select(array('name'));
      $this->db->where('bc',$this->sd['branch']);
      $query=$this->db->get('m_branch');
      if($query->num_rows()>0){
          return $query->first_row()->name;
      }
    }

    public function validation(){
      $status=1;
      $this->max_no=$this->utility->get_max_no("t_req_sum","nno");
      

      $check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_req_sum');
      if($check_is_delete!=1){
        return "Purchase request already deleted.";
      }

      if(isset($_POST['approve'])){
        $prsaf=$this->validation->purchase_requisition_save_after_approve($this->max_no);
        if($prsaf!=1){
          return "Please Save this form, Before Approve";
        }
      }

      $check_is_cancel=$this->validation->request_is_approve($this->max_no);
      if($check_is_cancel!=1){
        return $check_is_cancel;
      }

      $check_supplier_validation2 = $this->validation->check_is_supplier2('0_','sup_');
      if($check_supplier_validation2!=1){
        return $check_supplier_validation2;
      }

      return $status;
  }
  

  public function save(){
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errMsg, $errLine); 
    }
    set_error_handler('exceptionThrower'); 
      try{
        $validation_status=$this->validation();
        if($validation_status==1){
          $_POST['cl']=$this->sd['cl'];
          $_POST['bc']=$this->sd['branch'];

          if(isset($_POST['approve'])){
            $data=array(
              "cl"=>$this->sd['cl'],
              "bc"=>$this->sd['branch'],
              "nno"=>$this->max_no,                    
              "comment"=>$_POST['comment'],
              "ddate"=>$_POST['date'],
              "OC"=>$this->sd['oc'],
              "is_level_1_approved"=>'1'
            );
          }else{
            $data=array(
              "cl"=>$this->sd['cl'],
              "bc"=>$this->sd['branch'],
              "nno"=>$this->max_no,                    
              "comment"=>$_POST['comment'],
              "ddate"=>$_POST['date'],
              "OC"=>$this->sd['oc'],
              "type" => $_POST['type'],
              "is_level_0_approved"=>'1'
            );
          }


        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('t_req_sum')){
          for($x = 0; $x<25; $x++){
            if(isset($_POST['0_'.$x],$_POST['8_'.$x])){
              if($_POST['0_'.$x] != "" && $_POST['8_'.$x] != ""){
                $b[]= array(
                  "cl"=>$this->sd['cl'],
                  "bc"=>$this->sd['branch'],
                  "nno"=>$this->max_no,  
                  "item"=>$_POST['0_'.$x],
                  "cur_qty"=>$_POST['2_'.$x],
                  "rol"=>$_POST['3_'.$x],
                  "week1"=>$_POST['4_'.$x],
                  "week2"=>$_POST['5_'.$x],
                  "week3"=>$_POST['6_'.$x],
                  "supplier"=>$_POST['supplier_id_'.$x],
                  "week4"=>$_POST['7_'.$x],
                  "total_qty"=>$_POST['8_'.$x],
                  "comment"=>$_POST['c_'.$x],
                  "roq"=>$_POST['roq_'.$x],
                  "level_0_approve_qty"=>$_POST['8_'.$x]
                );              
              }
            }
          }
        
            $this->db->insert($this->tb_sum,$data);
            if(isset($b)){if(count($b)){$this->db->insert_batch("t_req_det",$b);}}
            $this->utility->save_logger("SAVE",31,$this->max_no,$this->mod);
            echo $this->db->trans_commit();
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          }

          }else{ 

            if($this->user_permissions->is_edit('t_req_sum')){
              $status=$this->validation->purchase_requisition_approve_status();
              if($status==1){
              for($x = 0; $x<25; $x++){
                if(isset($_POST['0_'.$x],$_POST['8_'.$x])){
                  if(isset($_POST['approve']) && $_POST['0_'.$x] != "" && $_POST['8_'.$x] != ""){ 
                      $b= array(
                        "level_1_approve_qty"=>$_POST['8_'.$x],
                        "total_qty"=>$_POST['8_'.$x]
                      ); 
                       
                      $this->db->where('item',$_POST['0_'.$x]);
                      $this->db->where('nno',$this->max_no);
                      $this->db->where('cl',$this->sd['cl']);
                      $this->db->where('bc',$this->sd['branch']);
                      $this->db->update("t_req_det",$b);

                  }else if($_POST['0_'.$x] != "" && $_POST['8_'.$x] != ""){
                     
                      $b[]= array(
                      "cl"=>$this->sd['cl'],
                      "bc"=>$this->sd['branch'],
                      "nno"=>$this->max_no, 
                      "item"=>$_POST['0_'.$x],
                      "cur_qty"=>$_POST['2_'.$x],
                      "level_0_approve_qty"=>$_POST['8_'.$x],
                      "rol"=>$_POST['3_'.$x],
                      "week1"=>$_POST['4_'.$x],
                      "week2"=>$_POST['5_'.$x],
                      "week3"=>$_POST['6_'.$x],
                      "supplier"=>$_POST['supplier_id_'.$x],
                      "week4"=>$_POST['7_'.$x],
                      "total_qty"=>$_POST['8_'.$x],
                      "comment"=>$_POST['c_'.$x],
                      "roq"=>$_POST['roq_'.$x]
                    );  
                  }
                }
              }     

              if(!isset($_POST['approve'])){
                $this->set_delete();
                $this->db->insert_batch("t_req_det",$b);
                $this->utility->save_logger("EDIT",31,$this->max_no,$this->mod);
              } 

              $this->db->where('nno',$_POST['id']);
              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->update($this->tb_sum,$data);
              echo $this->db->trans_commit();  
            }else{
              echo "This requisition already approved";
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


public function set_delete(){
    $this->db->where('CL',$this->sd['cl']);
    $this->db->where('BC',$this->sd['branch']);
    $this->db->where('nno',$_POST['id']);
    $this->db->delete('t_req_det');
}
    
public function check_code(){
    $this->db->where('code', $_POST['code']);
    $this->db->limit(1);
    echo $this->db->get($this->mtb)->num_rows;
}


public function load(){
    $this->db->select(array(
            "t_req_sum.cl",
            "t_req_sum.bc",
            "t_req_sum.nno",                    
            "t_req_sum.comment",
            "t_req_det.comment as cmnt",
            "m_supplier.name",
            "m_supplier.code",
            "m_branch.name as bname",
            "m_item.model",
            "m_item.min_price",
            "m_item.max_price",
            "t_req_sum.ddate",
            "t_req_det.item",
            "t_req_det.cur_qty",
            "t_req_det.rol",
            "t_req_det.week1",
            "t_req_det.week2",
            "t_req_det.week3",
            "t_req_det.supplier",
            "m_item.description",
            "t_req_det.week4",
            "t_req_sum.is_level_1_approved",
            "t_req_sum.is_cancel",
            "t_req_sum.type",
      ));

    $this->db->from('t_req_det');
    $this->db->join('m_supplier','m_supplier.code=t_req_det.Supplier');
    $this->db->join('t_req_sum','t_req_det.nno=t_req_sum.nno and t_req_det.bc=t_req_sum.bc and t_req_det.cl=t_req_sum.cl');
    $this->db->join('m_item','m_item.code=t_req_det.item');
    $this->db->join('m_branch','t_req_sum.bc=m_branch.bc and t_req_sum.cl=m_branch.cl');
    $this->db->where('t_req_det.cl',$this->sd['cl'] );
    $this->db->where('t_req_det.bc',$this->sd['branch'] );
    $this->db->where('t_req_sum.nno',$_POST['id']);
    $this->db->order_by("t_req_det.auto_no");
    $query=$this->db->get();

  if($query->num_rows()>0){
    $a['det']=$query->result();
    echo json_encode($a);
  }else{
    echo json_encode("2");
  }
}

public function loads2(){
    $itemC = $_POST['item_code'];
    $dateF = $_POST['date_from'];
    $dateT = $_POST['date_to'];
    $storeC= $_POST['store_code'];	
    $cl=$this->sd['cl'];
    $br=$this->sd['branch'];

	$sql="SELECT `t_item_movement`.`ddate`, `t_trans_code`.`description`, `t_item_movement`.`trans_no`, `t_item_movement`.`qty_in`,
 	      `t_item_movement`.`qty_out`, `t_item_movement`.`cost`
        FROM `t_item_movement`  INNER JOIN `t_trans_code` ON `t_trans_code`.`code`= `t_item_movement`.`trans_code`
        WHERE (`t_item_movement`.`item` = '$itemC' AND `t_item_movement`.`store_code` = '$storeC' AND`t_item_movement`.`cl` = '$cl' AND  `t_item_movement`.`bc` = '$br' AND `t_item_movement`.`ddate` BETWEEN '$dateF' AND '$dateT')
        LIMIT 25 ";  

  $query=$this->db->query($sql);

  if($query->num_rows()>0){
    $a['det']=$query->result();
    echo json_encode($a);
  }else{
    echo json_encode("2");
  }
}
  
public function loads3(){
    $itemC = $_POST['item_code'];
    $dateF = $_POST['date_from'];
    $dateT = $_POST['date_to'];
    $storeC= $_POST['store_code'];	
    $cl=$this->sd['cl'];
    $br=$this->sd['branch'];


    $sql="SELECT IFNULL (SUM(`t_item_movement`.`qty_in`)-SUM(`t_item_movement`.`qty_out`),0) AS opb
    FROM `t_item_movement` 
    WHERE (`t_item_movement`.`item` = '$itemC' AND `t_item_movement`.`store_code` = '$storeC' AND`t_item_movement`.`cl` = '$cl' AND  `t_item_movement`.`bc` = '$br' AND `t_item_movement`.`ddate`<='$dateF')
    LIMIT 25 ";   

    $query=$this->db->query($sql);

    if($query->num_rows()>0){
    $a['det']=$query->result();
    echo json_encode($a);
  }else{
    echo json_encode("2");
  }
}


public function loads4(){
    $itemC = $_POST['item_code'];
    $dateF = $_POST['date_from'];
    $dateT = $_POST['date_to'];
    $storeC= $_POST['store_code'];	
    $cl=$this->sd['cl'];
    $br=$this->sd['branch'];

  

    $sql="SELECT IFNULL (SUM(`t_item_movement`.`qty_in`),0) AS opb
    FROM `t_item_movement` 
    WHERE (`t_item_movement`.`item` = '$itemC' AND `t_item_movement`.`store_code` = '$storeC' AND`t_item_movement`.`cl` = '$cl' AND  `t_item_movement`.`bc` = '$br' AND `t_item_movement`.`trans_code`='3')
    LIMIT 25 "; 

$query=$this->db->query($sql);

    if($query->num_rows()>0){
    $a['det']=$query->result();
    echo json_encode($a);
  }else{
    echo json_encode("2");
  }
}



public function loads5(){
    $itemC = $_POST['item_code'];
    $dateF = $_POST['date_from'];
    $dateT = $_POST['date_to'];
    $storeC= $_POST['store_code'];	
    $cl=$this->sd['cl'];
    $br=$this->sd['branch'];
 

    $sql="SELECT IFNULL (SUM(`t_item_movement`.`qty_in`),0) AS opb
    FROM `t_item_movement` 
    WHERE (`t_item_movement`.`item` = '$itemC' AND `t_item_movement`.`store_code` = '$storeC' AND`t_item_movement`.`cl` = '$cl' AND  `t_item_movement`.`bc` = '$br' AND `t_item_movement`.`trans_code`='9')
    LIMIT 25 ";  

$query=$this->db->query($sql);

    if($query->num_rows()>0){
    $a['det']=$query->result();
    echo json_encode($a);
  }else{
    echo json_encode("2");
  }
}





public function delete(){

 if($this->user_permissions->is_delete('t_req_sum')){ 
  $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try{ 
      $status=$this->validation->purchase_requisition_status($_POST['id']);
        if($status==1){
          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->where('nno',$_POST['id']);
          $this->db->update($this->tb_sum, array("is_cancel"=>1));
           echo $this->db->trans_commit();  
        }else{
          $this->db->trans_commit();    
        }
      }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo $e->getMessage()."Operation fail please contact admin"; 
      } 
  }else{
    $this->db->trans_commit();
    echo "No permission to cancel records";
  }

}

    public function email(){
        $this->load->library('email');

        $em_sub="vehicle-accident ";
        $em_body="<h3>Accident &nbsp;&nbsp;&nbsp; :: <br><br><br><br><br>";
        $em_body.="<br><br>Accident Date : ";
        $em_body.="<br><br>Vehicle : ";
        $em_body.="<br><br>Driver\Officer : ";        
        $em_body.="<br><br>Place : ";      
        $em_body.="<br><br>Note : ";
        
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $this->email->from('system@sunmatch.lk', 'Vehicle Management');
        

        $this->email->to("roshankandy@gmail.com");


        $this->email->subject("subject");
        $this->email->message($em_body);
        $this->email->send();

        echo $this->email->print_debugger();

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


    public function get_item(){
      $cl=$this->sd['cl'];
      $bc=$this->sd['branch'];
      $supplier=$_POST['supplier'];
      $code=$_POST['code'];

      if($supplier==""){
        $sql="SELECT `m_item`.`code`,
             IFNULL(c.`qty`,'0') as qty, `m_item`.`model`, 
             `c`.`item`, `m_item`.`description`,IFNULL (r.`rol`,'') as rol, IFNULL(r.`roq`,'') as roq 
             FROM `m_item` 
             LEFT JOIN (SELECT * FROM `qry_current_stock` WHERE cl='$cl' AND bc='$bc')AS  c ON `m_item`.`code`=c.`item` 
             LEFT JOIN (SELECT * FROM `m_item_rol` WHERE `bc`='$bc' AND `cl`='$cl' )AS R ON `m_item`.`code`=r.`code`
             WHERE inactive='0' AND m_item.code='$code'
             GROUP BY m_item.code
             LIMIT 25 ";    
      }else{
       $sql="SELECT `m_item`.`code`,
             IFNULL(c.`qty`,'0') as qty, `m_item`.`model`, 
             `c`.`item`, `m_item`.`description`,IFNULL (r.`rol`,'') as rol, IFNULL(r.`roq`,'') as roq 
             FROM `m_item` 
             LEFT JOIN (SELECT * FROM `qry_current_stock` WHERE cl='$cl' AND bc='$bc')AS  c ON `m_item`.`code`=c.`item` 
             LEFT JOIN (SELECT * FROM `m_item_rol` WHERE `bc`='$bc' AND `cl`='$cl' )AS R ON `m_item`.`code`=r.`code`
             WHERE inactive='0' AND m_item.supplier='$supplier' AND m_item.code='$code'
             GROUP BY m_item.code
             LIMIT 25 ";    
      }
       $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $data['a'] = $this->db->query($sql)->result();
        } else {
            $data['a'] = 2;
        }

        echo json_encode($data);
    }


  public function item_list_all(){
    
    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}               

    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];
    $supplier ="";
    if(isset($_POST['supplier'])){
      $supplier=$_POST['supplier'];      
    }
    if($supplier==""){
      $sql="SELECT `m_item`.`code`,`m_item`.`purchase_price`,`m_item`.`min_price`,`m_item`.`max_price`,
           IFNULL(SUM(c.`qty`),'0') AS qty, `m_item`.`model`, 
           `c`.`item`, `m_item`.`description`,IFNULL (r.`rol`,'') as rol, IFNULL(r.`roq`,'') as roq 
           FROM `m_item` 
           LEFT JOIN qry_current_stock c ON c.cl='$cl' AND c.bc='$bc' AND c.`item`=m_item.`code`
           -- LEFT JOIN (SELECT * FROM `qry_current_stock` WHERE cl='$cl' AND bc='$bc')AS  c ON `m_item`.`code`=c.`item` 
           LEFT JOIN (SELECT * FROM `m_item_rol` WHERE `bc`='$bc' AND `cl`='$cl' )AS R ON `m_item`.`code`=r.`code`
           WHERE (`m_item`.`model` LIKE '$_POST[search]%' OR `m_item`.`code` LIKE '$_POST[search]%' OR `m_item`.`min_price` LIKE '$_POST[search]%'  OR `m_item`.`max_price` LIKE '$_POST[search]%' OR `m_item`.`description`  LIKE '$_POST[search]%' )AND inactive='0' 
           GROUP BY m_item.code
           LIMIT 25 ";    
    }else{
     $sql="SELECT `m_item`.`code`,`m_item`.`min_price`,`m_item`.`max_price`,`m_item`.`purchase_price`,
           IFNULL(SUM(c.`qty`),'0') AS qty, `m_item`.`model`, 
           `c`.`item`, `m_item`.`description`,IFNULL (r.`rol`,'') as rol, IFNULL(r.`roq`,'') as roq 
           FROM `m_item` 
           LEFT JOIN qry_current_stock c ON c.cl='$cl' AND c.bc='$bc' AND c.`item`=m_item.`code`
           -- LEFT JOIN (SELECT * FROM `qry_current_stock` WHERE cl='$cl' AND bc='$bc')AS  c ON `m_item`.`code`=c.`item` 
           LEFT JOIN (SELECT * FROM `m_item_rol` WHERE `bc`='$bc' AND `cl`='$cl' )AS R ON `m_item`.`code`=r.`code`
           WHERE (`m_item`.`model` LIKE '$_POST[search]%' OR `m_item`.`code` LIKE '$_POST[search]%' OR `m_item`.`min_price` LIKE '$_POST[search]%'  OR `m_item`.`max_price` LIKE '$_POST[search]%' OR `m_item`.`description`  LIKE '$_POST[search]%' )AND inactive='0' AND m_item.supplier='$supplier'
           GROUP BY m_item.code
            ";    
    }
        
        $query=$this->db->query($sql);
             $a = "<table id='item_list' style='width : 100%' >";
            $a .= "<thead><tr>";
            $a .= "<th class='tb_head_th'>Code</th>";
            $a .= "<th class='tb_head_th'>Description</th>";
            $a .= "<th class='tb_head_th'>Model</th>";
            $a .= "<th class='tb_head_th'>Purchase Price</th>";
            $a .= "<th class='tb_head_th'>Last Price</th>";
            $a .= "<th class='tb_head_th'>Max Price</th>";
            $a .= "<th class='tb_head_th'>Cur QTY</th>";
            $a .= "<th class='tb_head_th'>ROL</th>";
            $a .= "<th class='tb_head_th'>ROQ</th>";
            $a .= "<th class='tb_head_th'>Load</th>";

            $a .= "</thead></tr>";
            $a .= "<tr class='cl'>";
            $a .= "<td>&nbsp;</td>";
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
                    $a .= "<td>".$r->model."</td>";
                    $a .= "<td>".$r->purchase_price."</td>";
                    $a .= "<td>".$r->min_price."</td>";
                    $a .= "<td>".$r->max_price."</td>";
                    $a .= "<td>".$r->qty."</td>";
                    $a .= "<td>".$r->rol."</td>";
                    $a .= "<td>".$r->roq."</td>"; 
                    
                $a .= "</tr>";
            }
        $a .= "</table>";
        echo $a;
    }


    public function item_list_all_range(){
    
    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}               

    $cl=$this->sd['cl'];
    $bc=$this->sd['branch'];
    $supplier ="";
    $price_type="";

    if(isset($_POST['supplier'])){
      $supplier=$_POST['supplier'];      
    }

    if($_POST['type']=="1"){
      $price_type="AND m_item.purchase_price BETWEEN '".$_POST['price_from']."' AND '".$_POST['price_to']."'";
    }else if($_POST['type']=="2"){
      $price_type="AND m_item.min_price BETWEEN '".$_POST['price_from']."' AND '".$_POST['price_to']."'";
    }else if($_POST['type']=="3"){
      $price_type="AND m_item.max_price BETWEEN '".$_POST['price_from']."' AND '".$_POST['price_to']."'";
    }

    if($supplier==""){
      $sql="SELECT `m_item`.`code`,`m_item`.`purchase_price`,`m_item`.`min_price`,`m_item`.`max_price`,
           IFNULL(c.`qty`,'0') as qty, `m_item`.`model`, 
           `c`.`item`, `m_item`.`description`,IFNULL (r.`rol`,'') as rol, IFNULL(r.`roq`,'') as roq 
           FROM `m_item` 
           LEFT JOIN (SELECT * FROM `qry_current_stock` WHERE cl='$cl' AND bc='$bc')AS  c ON `m_item`.`code`=c.`item` 
           LEFT JOIN (SELECT * FROM `m_item_rol` WHERE `bc`='$bc' AND `cl`='$cl' )AS R ON `m_item`.`code`=r.`code`
           WHERE (`m_item`.`code` LIKE '$_POST[search]%' OR `m_item`.`min_price` LIKE '$_POST[search]%'  OR `m_item`.`max_price` LIKE '$_POST[search]%' OR `m_item`.`description`  LIKE '$_POST[search]%' )AND inactive='0' 
           ";    
    }else{
     $sql="SELECT `m_item`.`code`,`m_item`.`min_price`,`m_item`.`max_price`,`m_item`.`purchase_price`,
           IFNULL(c.`qty`,'0') as qty, `m_item`.`model`, 
           `c`.`item`, `m_item`.`description`,IFNULL (r.`rol`,'') as rol, IFNULL(r.`roq`,'') as roq 
           FROM `m_item` 
           LEFT JOIN (SELECT * FROM `qry_current_stock` WHERE cl='$cl' AND bc='$bc')AS  c ON `m_item`.`code`=c.`item` 
           LEFT JOIN (SELECT * FROM `m_item_rol` WHERE `bc`='$bc' AND `cl`='$cl' )AS R ON `m_item`.`code`=r.`code`
           WHERE (`m_item`.`code` LIKE '$_POST[search]%' OR `m_item`.`min_price` LIKE '$_POST[search]%'  OR `m_item`.`max_price` LIKE '$_POST[search]%' OR `m_item`.`description`  LIKE '$_POST[search]%' )AND inactive='0' AND m_item.supplier='$supplier'
           
            ";    
    }

    $sql.="$price_type  
          GROUP BY m_item.code
          LIMIT 25 ";
        
        $query=$this->db->query($sql);
             $a = "<table id='item_list' style='width : 100%' >";
            $a .= "<thead><tr>";
            $a .= "<th class='tb_head_th'>Code</th>";
            $a .= "<th class='tb_head_th'>Description</th>";
            $a .= "<th class='tb_head_th'>Model</th>";
            $a .= "<th class='tb_head_th'>Purchase Price</th>";
            $a .= "<th class='tb_head_th'>Last Price</th>";
            $a .= "<th class='tb_head_th'>Max Price</th>";
            $a .= "<th class='tb_head_th'>Cur QTY</th>";
            $a .= "<th class='tb_head_th'>ROL</th>";
            $a .= "<th class='tb_head_th'>ROQ</th>";
            $a .= "<th class='tb_head_th'>Load</th>";

            $a .= "</thead></tr>";
            $a .= "<tr class='cl'>";
            $a .= "<td>&nbsp;</td>";
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
                    $a .= "<td>".$r->model."</td>";
                    $a .= "<td>".$r->purchase_price."</td>";
                    $a .= "<td>".$r->min_price."</td>";
                    $a .= "<td>".$r->max_price."</td>";
                    $a .= "<td>".$r->qty."</td>";
                    $a .= "<td>".$r->rol."</td>";
                    $a .= "<td>".$r->roq."</td>"; 
                    
                $a .= "</tr>";
            }
        $a .= "</table>";
        echo $a;
    }

    public function pending_requisition(){
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $sql="SELECT * FROM t_req_sum WHERE cl='$cl' AND bc='$bc' AND is_level_1_approved='0' AND is_cancel='0'";
        $query=$this->db->query($sql);

        $html="<div style='margin:5px auto;width:800px;border:1px solid #ccc;'><table><tr>";
        $html.="<td>Is User Available For Approve</td>";
        $html.="<td>&nbsp;</td>";
        $html.="<td></td>";
        $html.="</tr></table><hr>";
        $html.="<table border='1' style='width:100%;'>

                <tr><td colspan='5' style='background:#AAA;color:#fff;font-weight:bolder;'>PENDING REQUISITION LIST</td></tr>
                <tr>
                <td style='background:#ccc;width:75px;'>&nbsp;Invoice No</td>
                <td style='background:#ccc; width:400px;'>&nbsp;Approved By</td>
                <td style='background:#ccc; width:100px;'>&nbsp;Date</td>
                <td style='background:#ccc; width:100px;'>&nbsp;Time</td>
                <td style='background:#ccc; width:100px;'>&nbsp;</td>
                </tr>";
        foreach($query->result() as $row){
            $time=explode(" ",$row->action_date);
            $html.="<tr>
                    <td style='width:75px;text-align:center;'>".$row->nno."</td>
                    <td >&nbsp;</td>
                    <td style='width:100px;'>&nbsp;".$row->ddate."</td>
                    <td style='width:100px;'>&nbsp;".$time[1]."</td>
                    <td style='width:100px;text-align:center;'><input type='button' title='Load' onclick='load_data_form(\"".$row->nno."\"),disable_form()' /></td>
                    </tr>";    
        }        
        $html.="</table>";
        $html.="</div>";
        return $html;
    }

    public function get_stock(){

      $cl = $this->sd['cl'];
      $code = $_POST['code'];

      /*$sql = "SELECT qty,c.tot as tot FROM qry_current_stock
      JOIN (SELECT SUM(qty) AS tot,item FROM qry_current_stock WHERE qry_current_stock.item = '$code' ) c ON c.item = qry_current_stock.`item`
      WHERE qry_current_stock.cl = '$cl' AND qry_current_stock.item = '$code'";
      */

      $sql="SELECT  qry_current_stock.cl,
                    qry_current_stock.bc,
                    m_branch.name,
                    m_cluster.description,
                    v.bc_qty AS bc_qty,
                    cc.cl_qty AS cl_qty,
                    c.tot AS tot 
          FROM qry_current_stock 
          JOIN m_branch ON m_branch.bc = qry_current_stock.bc 
          JOIN m_cluster ON m_cluster.code = qry_current_stock.cl 
          JOIN (SELECT SUM(qty) AS bc_qty,item,cl,bc FROM qry_current_stock WHERE item='$code' GROUP BY bc)v 
                ON v.item = qry_current_stock.item
                AND v.cl = qry_current_stock.cl 
                AND v.bc = qry_current_stock.bc 
          JOIN (SELECT SUM(qty) AS tot, item,cl,bc FROM qry_current_stock 
              WHERE qry_current_stock.item = '$code') c 
              ON c.item = qry_current_stock.`item`   
        JOIN (SELECT cl,bc,SUM(qty) AS cl_qty,item FROM qry_current_stock 
              WHERE qry_current_stock.item = '$code' GROUP BY qry_current_stock.cl) cc 
              ON cc.item = qry_current_stock.`item` 
              AND cc.cl = qry_current_stock.cl 
        WHERE qry_current_stock.item = '$code' 
        GROUP BY qry_current_stock.bc  ";

      $query = $this->db->query($sql);
  

      $a = "<table id='item_list' style='width : 100%' >";
      $a .= "<thead><tr>";
      $a .= "<th class='tb_head_th'><b>Cluster </b></th>";
      $a .= "<th class='tb_head_th'><b>Branch </b></th>";
      $a .= "<th class='tb_head_th'><b>Branch Stock</b></th>";
      $a .= "<th class='tb_head_th'><b>Cluster Stock</b></th>";
      $a .= "<th class='tb_head_th'><b>Group Stock</b></th>";
      $a .= "</thead></tr>";
   

      if($query->num_rows() > 0){
        foreach($query->result() as $row){        
            $bc_qty=$row->bc_qty;     
            $cl_qty=$row->cl_qty;       
            $tot=$row->tot;          
            $a .= "<tr class='cl'>";
            $a .= "<td>".$row->description."</td>";
            $a .= "<td>".$row->name."</td>";
            $a .= "<td style='text-align:right'>".$bc_qty."</td>";
            $a .= "<td style='text-align:right'>".$cl_qty."</td>";
            $a .= "<td style='text-align:right'>".$tot."</td>";       
            $a .= "</tr>";                       
        }
      }else{
         $a .= "<tr class='cl'>";
            $a .= "<td>0</td>";
            $a .= "<td>0</td>";
            $a .= "<td style='text-align:right'>0</td>";
            $a .= "<td style='text-align:right'>0</td>";
            $a .= "<td style='text-align:right'>0</td>";       
            $a .= "</tr>";       
      }

   
      $a .= "</table>";
      echo $a;
     
    }

}