<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class g_internel_transfer_order extends CI_Model {
    
  private $sd;
  private $mtb;
  private $tb_po_trans;
  private $max_no;
  private $mod = '003';
  private $trans_code="23";
  private $sub_trans_code="23";
  private $qty_out="0";

    function __construct(){
  	parent::__construct();
  	
  	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
  	$this->mtb = $this->tables->tb['t_po_sum'];
  	$this->tb_po_trans= $this->tables->tb['t_po_trans'];
    $this->load->model('user_permissions');

    $this->load->model('utility');
  }
    
  public function base_details(){
     
  	//$a['nno'] = $this->get_next_no();
   	$a['max_no']= $this->utility->get_max_no("g_t_internal_transfer_order_sum","nno");
    $a['sub_max_no']= $this->utility->get_max_no_in_type("g_t_internal_transfer_order_sum","sub_no","request");

  

   	// $a['cluster']=$this->load_cluster();
    //  $a['branch']=$this->load_branch();

    $a['company'] = $this->s_company->get_company_name();
    $a['cluster']=$this->select_cluster();
     return $a;
  }

  public function load_branch(){
    $this->db->where("cl",$this->sd['cl']);
    $query = $this->db->get('m_branch');

    $A = "<select name='ship_to_bc' id='ship_to_bc'>";
    $B="";
    foreach($query->result() as $r){
      if($r->bc==$this->sd['branch']){
        $C = "<option title='".$r->name."'  value='".$r->bc."' selected='selected'>".$r->bc." | ".$r->name."</option>";
      }else{
        $B .= "<option title='".$r->name."' value='".$r->bc."' >".$r->bc." | ".$r->name."</option>";
      }     
    }       
    $D = "</select>";
    $s = $A.$C.$B.$D;
    return $s;
  }

  public function load_cluster(){
    $this->db->select(array("description"));
    $this->db->where("code",$this->sd['cl']);
    return $this->db->get('m_cluster')->row()->description;
  }

  public function validation(){
    $status=1;
    $this->max_no=$this->utility->get_max_no("g_t_internal_transfer_order_sum","nno");
    $this->sub_max_no=$this->utility->get_max_no_in_type_echo2("g_t_internal_transfer_order_sum","sub_no",$_POST['types']);
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
        $_POST['cl']=$this->sd['cl'];
        $_POST['branch']=$this->sd['branch'];
        $_POST['oc']=$this->sd['oc']; 
        $g_t_internal_transfer_order_sum=array(
           "cl"=>$this->sd['cl'],
           "bc"=>$this->sd['branch'],
           "type"=>$_POST['types'],
           "sub_no"=>$this->sub_max_no,
           "nno"=>$this->max_no,
           "ddate"=>$_POST['date'],
           "ref_no"=>$_POST['ref_no'],
           "to_bc"=>$_POST['to_bc'],
           "note"=>$_POST['note'],
           "oc"=>$this->sd['oc'],
           "is_approved"=> 0       
        );
        for($x = 0; $x<25; $x++){
          if(isset($_POST['0_'.$x])){
            if($_POST['0_'.$x] != "" ){
              $g_t_internal_transfer_order_det[]= array(
                "cl"=>$this->sd['cl'],
                "bc"=>$this->sd['branch'],
                "nno"=>$this->max_no,
                "type"=>$_POST['types'],
                "sub_no"=>$this->sub_max_no,
                "item_code"=>$_POST['0_'.$x],
                "item_cost"=>$_POST['7_'.$x],
                "qty"=>$_POST['4_'.$x],      
              );                 
            }
          }
        }
        if($_POST['hid'] == "0" || $_POST['hid'] == ""){
          if($this->user_permissions->is_add('g_internel_transfer_order')){ 
            $this->db->insert("g_t_internal_transfer_order_sum",  $g_t_internal_transfer_order_sum);
            if(count($g_t_internal_transfer_order_det)){$this->db->insert_batch("g_t_internal_transfer_order_det",$g_t_internal_transfer_order_det);}  
            $this->utility->save_logger("SAVE",61,$this->max_no,$this->mod);
            echo $this->db->trans_commit();
          }else{
            echo "No permission to save records";
            $this->db->trans_commit();
          } 
        }else{
          if($this->user_permissions->is_add('g_internel_transfer_order')){ 
            $this->db->where('nno',$_POST['sub_hid']);
            $this->db->where('type',$_POST['types']);
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);
            $this->db->update("g_t_internal_transfer_order_sum", $g_t_internal_transfer_order_sum);
            $this->set_delete();
            if(count($g_t_internal_transfer_order_det)){$this->db->insert_batch("g_t_internal_transfer_order_det",$g_t_internal_transfer_order_det);}
            $this->utility->save_logger("EDIT",61,$this->max_no,$this->mod); 
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


  public function PDF_report(){

    $r_detail['ddate'];
    $r_detail['ref_no'];
    $r_detail['branch'];
   
    $this->db->join('m_branch','g_t_internal_transfer_order_sum.to_bc=m_branch.bc');
    $this->db->where("nno",$_POST['qno']);
    $this->db->where("g_t_internal_transfer_order_sum.cl",$_POST['cl']);
    $this->db->where("g_t_internal_transfer_order_sum.bc",$_POST['branch']);
    $query= $this->db->get('g_t_internal_transfer_order_sum'); 
    
    if ($query->num_rows() > 0){
      foreach ($query->result() as $row){
        $r_detail['to_bc']=$row->to_bc;
        $r_detail['ddate']=$row->ddate;
        $r_detail['branch'] = $row->name;   
      }
    } 

    $invoice_number= $this->utility->invoice_format($_POST['qno']);

    $qry = $this->db->query("SELECT name FROM m_branch WHERE bc='".$this->sd['branch']."'")->row();

    $session_array = array(
         $this->sd['cl'],
         $this->sd['branch'],
         $invoice_number,
         $qry->name
    );

    $r_detail['session'] = $session_array;

    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch']=$this->db->get('m_branch')->result();

    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$r_detail['ship_to_bc']);
    $r_detail['ship_branch']=$this->db->get('m_branch')->result();
   
    $r_detail['qno']=$_POST['qno'];
    $r_detail['page']=$_POST['page'];
    $r_detail['header']=$_POST['header'];
    $r_detail['orientation']=$_POST['orientation'];
    $r_detail['type']=$_POST['type'];

    $ss="SELECT b.`name` AS bc,
          bb.name AS to_bc,
          s.ddate,
          s.ref_no ,
          nno
          FROM g_t_internal_transfer_order_sum s 
          JOIN m_branch b ON b.`bc` = s.`bc` 
          JOIN m_branch bb ON bb.`bc` = s.`to_bc` 
          WHERE s.cl='".$this->sd['cl']."' AND s.bc='".$this->sd['branch']."' AND nno='".$_POST['qno']."'";
          
    $querye = $this->db->query($ss);
    $r_detail['cl']=$querye->result();

    $sql="SELECT 
       `g_m_gift_voucher`.`code`,
       `g_m_gift_voucher`.`description`,
       `g_m_gift_voucher`.`cost`,
       `g_m_gift_voucher`.`price`,
       `g_t_internal_transfer_order_det`.`qty`
      
       FROM `g_t_internal_transfer_order_det` 
       JOIN g_m_gift_voucher ON g_m_gift_voucher.`code`=g_t_internal_transfer_order_det.`item_code`
       WHERE `g_t_internal_transfer_order_det`.`nno`=".$_POST['qno']." AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";

    $query=$this->db->query($sql);
    if($query->num_rows>0){
      $r_detail['det']=$query->result();
      
    }else{
      $r_detail['det']=2;
    }

    if($this->db->query($sql)->num_rows()>0){
      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }else{
      echo "<script>alert('No Data');window.close();</script>";
    }

    //$this->load->view($_POST['by'].'_'.'pdf',$r_detail);
  }


  private function set_delete(){
    $this->db->where("sub_no", $_POST['sub_no']);
    $this->db->where("cl", $_POST['cl']);
    $this->db->where("bc", $_POST['branch']);
    $this->db->where("type", $_POST['types']);
    $this->db->delete("g_t_internal_transfer_order_det");

  }
        
  public function check_code(){
  	$this->db->where('code', $_POST['code']);
  	$this->db->limit(1);
    echo $this->db->get($this->mtb)->num_rows;
  }
        
        
  public function load(){

    $x=0;  

    $this->db->select(array(
    'g_t_internal_transfer_order_sum.ddate' ,
    'g_t_internal_transfer_order_sum.ref_no' ,
    'g_t_internal_transfer_order_sum.type' ,
    'g_t_internal_transfer_order_sum.sub_no' ,
    'm_cluster.code as cl',
    'g_t_internal_transfer_order_sum.is_cancel',
    'm_cluster.description',
    'g_t_internal_transfer_order_sum.bc',
    'g_t_internal_transfer_order_sum.to_bc',
    'm_branch.name',
    'g_t_internal_transfer_order_sum.note',
    'g_t_internal_transfer_order_sum.status',
    ));

    $this->db->join('m_branch','m_branch.bc=g_t_internal_transfer_order_sum.to_bc');
    $this->db->join('m_cluster','m_cluster.code=m_branch.cl');
    $this->db->where('g_t_internal_transfer_order_sum.cl',$this->sd['cl'] );
    $this->db->where('g_t_internal_transfer_order_sum.bc',$this->sd['branch'] );
    $this->db->where('g_t_internal_transfer_order_sum.sub_no',$_POST['id']);
    $this->db->where('g_t_internal_transfer_order_sum.type',$_POST['type']);
    $query=$this->db->get('g_t_internal_transfer_order_sum');

    if($query->num_rows()>0){
      $a['sum']=$query->result();
    }else{
      $x=0;
    } 

    $this->db->select(array(
    'g_t_internal_transfer_order_det.nno',
    'g_t_internal_transfer_order_det.item_code',
    'g_m_gift_voucher.description',
    'g_t_internal_transfer_order_det.item_cost',
    'g_m_gift_voucher.price',
    'gift_qry_current_stock.qty',
    'g_t_internal_transfer_order_det.qty AS quantity',
    ));

    $this->db->from('g_t_internal_transfer_order_det');
    $this->db->join('g_m_gift_voucher','g_m_gift_voucher.code=g_t_internal_transfer_order_det.item_code');
    $this->db->join('gift_qry_current_stock','g_m_gift_voucher.code=gift_qry_current_stock.item','left');
    $this->db->where('g_t_internal_transfer_order_det.cl',$this->sd['cl'] );
    $this->db->where('g_t_internal_transfer_order_det.bc',$this->sd['branch'] );
    $this->db->where('g_t_internal_transfer_order_det.sub_no',$_POST['id']);
    $this->db->where('g_t_internal_transfer_order_det.type',$_POST['type']);
    $this->db->group_by('g_m_gift_voucher.code');
    $query=$this->db->get();

    if($query->num_rows()>0){
      $a['det']=$query->result();
    }else{
      $x=2;
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
        throw new Exception($errLine); 
    }
    set_error_handler('exceptionThrower'); 
    try { 
      if($this->user_permissions->is_delete('g_internel_transfer_order')){ 
        $this->db->where('cl',$this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->where('nno',$_POST['id']);
        $this->db->where('type',$_POST['type']);
        $this->db->update('g_t_internal_transfer_order_sum', array("is_cancel"=>1)); 
        echo $this->db->trans_commit();
      }else{
        echo $validation_status;
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
    $s .= "<option value='0'>---</option>";
    foreach($query->result() as $r){
        $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code." | ".$r->name."</option>";
    }
    $s .= "</select>";
    
    return $s;
  }

   public function item_list_all(){
  
      if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}               
     
      // $sql="SELECT * 
      //       FROM m_item 
      //       JOIN qry_current_stock  ON m_item.`code` = qry_current_stock.`item`
      //       WHERE  bc = '".$_POST['branch']."' 
      //       AND (m_item.description LIKE '$_POST[search]%' OR code LIKE '$_POST[search]%')
      //       LIMIT 25 "; 

      $sql="SELECT 
            GF.code,
            GF.description AS item_name,
            GF.cost,
            GF.price,
            CS.qty 
            FROM
            g_m_gift_voucher GF 
            JOIN `gift_qry_current_stock` CS 
            ON `CS`.item = GF.`code` 
            WHERE (GF.code LIKE '$_POST[search]%' 
            OR `GF`.`description` LIKE '$_POST[search]%' 
            OR `GF`.`cost` LIKE '$_POST[search]%'  
            OR `GF`.`price` LIKE '$_POST[search]%'
            OR `CS`.`qty` LIKE '$_POST[search]%')
            GROUP BY GF.code
            limit 25" ;    


      $query = $this->db->query($sql);

      
      $a = "<table id='item_list' style='width : 100%' >";
      $a .= "<thead><tr>";
      $a .= "<th class='tb_head_th'>Code</th>";
      $a .= "<th class='tb_head_th'>Item Name</th>";
      $a .= "<th class='tb_head_th'>Cost</th>";
      $a .= "<th class='tb_head_th'>Max Price</th>";
      $a .= "<th class='tb_head_th'>Current Stock</th>";
      $a .= "</thead></tr>";

      $a .= "<tr class='cl'>";
      $a .= "<td>&nbsp;</td>";
      $a .= "<td>&nbsp;</td>";
      $a .= "<td>&nbsp;</td>";
      $a .= "</tr>";

      foreach($query->result() as $r){
          $a .= "<tr class='cl'>";
              $a .= "<td>".$r->code."</td>";
              $a .= "<td>".$r->item_name."</td>";
              $a .= "<td>".$r->cost."</td>";
              $a .= "<td>".$r->price."</td>";
              $a .= "<td>".$r->qty."</td>";
              
          $a .= "</tr>";
      }
      $a .= "</table>";
      echo $a;
  }

  public function get_item(){
    $this->db->select(array('code','description','purchase_price'));
    $this->db->where("code",$this->input->post('code'));
    $this->db->limit(1);
    $query=$this->db->get('m_item');
    if($query->num_rows() > 0){
        $data['a']=$query->result();
    }else{
        $data['a']=2;
    }
    
    echo json_encode($data);
  }


  function load_request_note(){
    $supplier=$this->input->post("supplier");


    $sql="SELECT m_item.`description`,
                m_item.`model`,
                m_item.`purchase_price`,
                `t_req_det`.`item`,
                t_req_det.`nno`,
                t_req_det.`cur_qty`,
                t_req_det.`approve_qty`,
                t_req_det.`approve_qty` * m_item.`purchase_price` AS total 
          FROM `t_req_det` 
          JOIN m_item ON m_item.`code` = t_req_det.`item` 
          WHERE `t_req_det`.`approved` = '1' 
          AND t_req_det.`orderd` = '0'
          AND cl = '".$this->sd['cl']."' 
          AND bc = '".$this->sd['branch']."' 
          ";

    $query=$this->db->query($sql);
    if($query->num_rows>0)
    {
      $a['det']=$query->result();
      echo json_encode($a);
    }else{
      $a['det']=2;
      echo json_encode($a);  
    }    
  }

  public function select_cluster(){
    $query = $this->db->get('m_cluster');
      $s = "<select name='cluster' id='cluster' style='width:185px;'>";
      $s .= "<option value='0'>---</option>";
      foreach($query->result() as $r){
       $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code." - ".$r->description."</option>";
      }
      $s .= "</select>";
      return $s;
  }


  public function select_branch(){
    $sql="SELECT * FROM m_branch WHERE cl = '".$_POST['cluster']."' AND bc != '".$this->sd['branch']."' ";
    $query = $this->db->query($sql);

    $s = "";
    $s .= "<option value='0'>---</option>";
    foreach($query->result() as $r){
     $s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc." - ".$r->name."</option>";
    }
    echo $s;
  }

        
}