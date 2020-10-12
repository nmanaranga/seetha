<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_quotation_sum extends CI_Model {
    
  private $sd;
  private $mtb;
  private $max_no;
  private $mod = '003';
    
  function __construct(){
	  parent::__construct();
	
	  $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');
	  $this->mtb = $this->tables->tb['t_quotation_sum'];
  }
    
  public function base_details(){
	
	  $this->load->model('m_customer');
    $a['customer']=$this->m_customer->select();
    //$a['max_no'] = $this->get_next_no();
    $a['max_no']= $this->utility->get_max_no("t_quotation_sum","nno");
	  return $a;
  }
  
  public function validation(){
    $this->max_no=$this->utility->get_max_no("t_quotation_sum","nno");

    $status=1;

    $check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_quotation_sum');
      if($check_is_delete!=1){
        return "This quotation has been already cancelled";
    }
   

    $minimum_price_validation = $this->validation->check_min_price('0_', '3_');
    if ($minimum_price_validation != 1){
    return $minimum_price_validation;
    }

    $check_zero_value=$this->validation->empty_net_value($_POST['net_amount']);
    if($check_zero_value!=1){
      return $check_zero_value;
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

      $_POST['cl']=$this->sd['cl'];
      $_POST['bc']=$this->sd['branch'];
      $_POST['oc']=$this->sd['oc'];


      $a=array(
        "cl"=>$_POST['cl'],
        "bc"=>$_POST['bc'],
        "oc"=>$_POST['oc'],
        "nno"=>$_POST['id'],
        "ddate"=>$_POST['date'],
        "ref_no"=>$_POST['ref_no'],
        "cus_id"=>$_POST['customer'],
        "memo"=>$_POST['memo'],
        "gross_amount"=>$_POST['gross'],
        "discount"=>$_POST['discount'],
        "net_amount"=>$_POST['net_amount'],
        "emp_id"=>$_POST['officer'],
        "validity_period"=>$_POST['validity_period'],
        "cus_name" => $_POST['customer_id'],
        "cus_address" => $_POST['address']
      );


      for($x = 0; $x<25; $x++){
        if(isset($_POST['2_'.$x],$_POST['3_'.$x])){
          if($_POST['2_'.$x] != "" && $_POST['3_'.$x] != "" ){
            $b[]= array(
              "cl"=>$_POST['cl'],
              "bc"=>$_POST['bc'],
              "nno"=>$_POST['id'],
              "code"=>$_POST['0_'.$x],
              "description"=>$_POST['n_'.$x],
              "model"=>$_POST['1_'.$x],
              "qty"=>$_POST['2_'.$x],
              "price"=>$_POST['3_'.$x],
              "discountp"=>$_POST['4_'.$x],
              "discount"=>$_POST['5_'.$x],
               "item_discription"=>$_POST['6_'.$x]
            );              
          }
        }
      }


      if($_POST['hid'] == "0" || $_POST['hid'] == ""){
        if($this->user_permissions->is_add('t_quotation_sum')){
          $this->db->insert($this->mtb, $a);
          if(count($b)){$this->db->insert_batch("t_quotation_det",$b);}
          $this->utility->save_logger("SAVE",30,$this->max_no,$this->mod);
          echo $this->db->trans_commit()."@".$this->max_no;
        }else{
          $this->db->trans_commit();
          echo "No permission to save records";
        }
      }else{
        if($this->user_permissions->is_edit('t_quotation_sum')){
          $this->db->where('nno',$_POST['hid']);
          $this->db->where('cl',$_POST['cl']);
          $this->db->where('bc',$_POST['bc']);
          $this->db->update($this->mtb, $a);
          $this->set_delete();
          if(count($b)){$this->db->insert_batch("t_quotation_det",$b);}
          $this->utility->save_logger("EDIT",30,$this->max_no,$this->mod);
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


  private function set_delete(){
    $this->db->where('nno', $_POST['id']);
    $this->db->where('cl', $this->sd['cl']);
    $this->db->where('bc',$this->sd['branch']);

    $this->db->delete("t_quotation_det");

  }
    
  public function check_code(){
    $this->db->where('cl',$this->sd['cl']);
    $this->db->where('bc',$this->sd['branch']);
	  $this->db->where('nno', $_POST['id']);
	  $this->db->limit(1);
		echo $this->db->get($this->mtb)->num_rows;
  }
    
  public function load(){

    $this->db->select (array(
      't_quotation_sum.cus_id as ccode' ,
      't_quotation_sum.cus_name',
      't_quotation_sum.cus_address as address' ,
      'm_employee.name as e_name',
      't_quotation_sum.cl',
      't_quotation_sum.bc',
      't_quotation_sum.nno',
      't_quotation_sum.ddate',
      't_quotation_sum.ref_no',
      't_quotation_sum.emp_id',
      't_quotation_sum.memo',
      't_quotation_sum.gross_amount',
      't_quotation_sum.discount as dis',
      't_quotation_sum.net_amount',
      't_quotation_sum.validity_period',
      't_quotation_sum.is_cancel',
      't_quotation_det.code',
      't_quotation_det.description',
      't_quotation_det.model',
      't_quotation_det.qty',
      't_quotation_det.price',
      't_quotation_det.discountp',
      't_quotation_det.discount',
      't_quotation_det.item_discription'  
    ));

    $this->db->from('t_quotation_sum');
    $this->db->join('m_employee','m_employee.code=t_quotation_sum.emp_id','LEFT');
    $this->db->join('t_quotation_det','t_quotation_det.nno=t_quotation_sum.nno AND t_quotation_det.cl=t_quotation_sum.cl AND t_quotation_det.bc=t_quotation_sum.bc' );
    $this->db->where('t_quotation_det.cl',$this->sd['cl']);
    $this->db->where('t_quotation_det.bc',$this->sd['bc']);  
    $this->db->where('t_quotation_sum.nno',$_POST['id']);
    //$this->db->group_by(array('t_quotation_det.cl','t_quotation_det.bc'));
    
    $query=$this->db->get();

    if($query->num_rows()>0){
      $a['det']=$query->result();
      echo json_encode($a);
    }else{
      echo json_encode("2");
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
      if($this->user_permissions->is_delete('t_quotation_sum')){
        $this->db->where('nno', $_POST['id']);
        $this->db->where('cl', $this->sd['cl']);
        $this->db->where('bc',$this->sd['branch']);
        $this->db->update('t_quotation_sum', array("is_cancel"=>1));
        $this->utility->save_logger("CANCEL",30,$_POST['id'],$this->mod);
        echo $this->db->trans_commit();
      }else{
        $this->db->trans_commit();
        echo "No permission to delete records";
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
             
      $sql = "SELECT DISTINCT(m_item.code), 
                m_item.`description`,
                m_item.`model`,
                t_item_batch.`max_price`, 
                t_item_batch.`min_price`,
                t_item_batch.purchase_price
              FROM m_item 
              JOIN t_item_batch ON t_item_batch.`item` = m_item.`code` 
              WHERE description LIKE '$_POST[search]%' 
              OR `t_item_batch`.`min_price` LIKE '$_POST[search]%'  
              OR `t_item_batch`.`max_price` LIKE '$_POST[search]%' 
              OR code LIKE '$_POST[search]%' 
              AND inactive='1' 
              LIMIT 25";

      $query = $this->db->query($sql);
        
      $a = "<table id='item_list' style='width : 100%' >";
        
      $a .= "<thead><tr>";
        $a .= "<th class='tb_head_th'>Code</th>";
        $a .= "<th class='tb_head_th'>Item Name</th>";
        $a .= "<th class='tb_head_th'>Model</th>";
        $a .= "<th class='tb_head_th'>Price</th>";       
      $a .= "</thead></tr>";

      $a .= "<tr class='cl'>";
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
          $a .= "<td>".$r->max_price."</td>";            
        $a .= "</tr>";
      }
      $a .= "</table>";
        
      echo $a;
    }

  public function get_item(){
    $this->db->select(array('code','description','model','max_price'));
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


   public function PDF_report(){


    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch']=$this->db->get('m_branch')->result();
      
     $this->db->select(array('nno','ddate','ref_no'));
     $this->db->where("nno",$_POST['qno']);
     $query=$this->db->get('t_quotation_sum');
     $r_detail['ddate']=$query->first_row()->ddate; 
     $r_detail['nno']=$query->first_row()->nno; 
     $r_detail['ref_no']=$query->first_row()->ref_no; 

    $invoice_number= $this->utility->invoice_format($_POST['qno']);
    $session_array = array(
         $this->sd['cl'],
         $this->sd['branch'],
         $invoice_number
    );
    $r_detail['session'] = $session_array;




      $r_detail['type']=$_POST['type'];        
      $r_detail['dt']=$_POST['dt'];
      $r_detail['qno']=$_POST['qno'];


      $r_detail['page']=$_POST['page'];
      $r_detail['header']=$_POST['header'];
      $r_detail['orientation']=$_POST['orientation'];

      $this->db->select(array('cus_id','cus_name','cus_address'));
      $this->db->from('t_quotation_sum');
      $this->db->where('t_quotation_sum.cl',$this->sd['cl'] );
      $this->db->where('t_quotation_sum.bc',$this->sd['branch']);
      $this->db->where('t_quotation_sum.nno',$_POST['qno']);
      $r_detail['customer']=$this->db->get()->result();



      $this->db->select(array('t_quotation_det.code','t_quotation_det.description','t_quotation_det.description','t_quotation_det.qty','t_quotation_det.price','t_quotation_det.discountp','t_quotation_det.discount','t_quotation_det.price * t_quotation_det.qty as net_amount','t_quotation_det.item_discription','t_quotation_sum.validity_period'));
      $this->db->from('t_quotation_det');
      $this->db->join('t_quotation_sum','t_quotation_sum.nno=t_quotation_det.nno AND t_quotation_sum.cl=t_quotation_det.cl AND t_quotation_sum.bc=t_quotation_det.bc');
      $this->db->where('t_quotation_det.cl',$this->sd['cl'] );
      $this->db->where('t_quotation_det.bc',$this->sd['branch']);
      $this->db->where('t_quotation_sum.nno',$_POST['qno']);
      $r_detail['items']=$this->db->get()->result();
      

      $this->db->select(array('gross_amount','net_amount'));
      $this->db->where('t_quotation_sum.cl',$this->sd['cl'] );
      $this->db->where('t_quotation_sum.bc',$this->sd['branch']);
      $this->db->where('t_quotation_sum.nno',$_POST['qno']);
      $r_detail['amount']=$this->db->get('t_quotation_sum')->result();

      $this->db->select_sum("discount");
      $this->db->where('cl',$this->sd['cl'] );
      $this->db->where('bc',$this->sd['branch']);
      $this->db->where('nno',$_POST['qno']);
      $r_detail['discount']=$this->db->get('t_quotation_sum')->result();

      $this->db->select(array('loginName'));
      $this->db->where('cCode',$this->sd['oc']);
      $r_detail['user']=$this->db->get('users')->result();

     $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }

  public function cus_address(){
    $sql="SELECT CONCAT(address1,' ',address2,' ',address3) as address FROM m_customer WHERE code='".$_POST['code']."' ";
    $result = $this->db->query($sql)->row()->address; 
    echo $result;
  }    
}