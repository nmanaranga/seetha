
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_hp extends CI_Model {
    
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

    $this->load->model('utility');
  }
    
  public function base_details(){

    $this->load->model('m_stores');
    $a['stores'] = $this->m_stores->select();


  	//$a['nno'] = $this->get_next_no();
   	$a['max_no']= $this->utility->get_max_no("t_internal_transfer_order_sum","nno");

   	// $a['cluster']=$this->load_cluster();
    //  $a['branch']=$this->load_branch();

    $a['company'] = $this->s_company->get_company_name();
    $a['cluster']=$this->loder->select();
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

    $this->max_no=$this->utility->get_max_no("t_internal_transfer_order_sum","nno");

   
   
    return $status;
  }

  public function save(){
  
  

    $validation_status=$this->validation();
    
    if($validation_status==1){

    $this->db->trans_start();
    $_POST['cl']=$this->sd['cl'];
    $_POST['branch']=$this->sd['branch'];
    $_POST['oc']=$this->sd['oc']; 

    $t_internal_transfer_order_sum=array(
       "cl"=>$_POST['cl'],
       "bc"=>$_POST['branch'],
       "nno"=>$this->max_no,
       "ddate"=>$_POST['date'],
       "ref_no"=>$_POST['ref_no'],
       "to_bc"=>$_POST['to_bc'],
       "note"=>$_POST['note'],
       "oc"=>$_POST['oc'],
       "is_approved"=> 0
      
       
    );

    for($x = 0; $x<25; $x++){
      if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['3_'.$x])){
        if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['3_'.$x] != "" ){
          $t_internal_transfer_order_det[]= array(
            "cl"=>$_POST['cl'],
            "bc"=>$_POST['branch'],
            "nno"=>$this->max_no,
            "item_code"=>$_POST['0_'.$x],
            "item_cost"=>$_POST['7_'.$x],
            "qty"=>$_POST['4_'.$x],
            
          ); 

                 
        }
      }
    }



    if($_POST['hid'] == "0" || $_POST['hid'] == ""){
      $this->db->insert("t_internal_transfer_order_sum",  $t_internal_transfer_order_sum);
        if(count($t_internal_transfer_order_det)){$this->db->insert_batch("t_internal_transfer_order_det",$t_internal_transfer_order_det);}  

        for($x = 0; $x<25; $x++){
        	if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['3_'.$x])){
          	if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['3_'.$x] != "" ){
            	$this->load->model('utility');
        			$this->utility->save_po_trans($this->tb_po_trans, $_POST['0_'.$x], $this->trans_code, $_POST['id'], $this->sub_trans_code, $_POST['id'], $_POST['3_'.$x], $this->qty_out);

              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->where('item',$_POST['0_'.$x]);
              $this->db->update('t_req_det', array("orderd"=>1, "orderd_no"=>$this->max_no));   
           	}
          }
        }

       $this->utility->save_logger("SAVE",33,$this->max_no,$this->mod);

    }else{

      $this->db->where('nno',$_POST['hid']);
      $this->db->update("t_internal_transfer_order_sum", $t_internal_transfer_order_sum);
      $this->set_delete();
      if(count($t_internal_transfer_order_det)){$this->db->insert_batch("t_internal_transfer_order_det",$t_internal_transfer_order_det);}

      $this->utility->delete_po_trans($this->tb_po_trans, $this->trans_code, $_POST['id']);
     
      for($x = 0; $x<25; $x++){
      	if(isset($_POST['0_'.$x],$_POST['2_'.$x],$_POST['3_'.$x])){
         	if($_POST['0_'.$x] != "" && $_POST['2_'.$x] != "" && $_POST['3_'.$x] != "" ){
             	$this->load->model('utility');
        			$this->utility->save_po_trans($this->tb_po_trans, $_POST['0_'.$x], $this->trans_code, $_POST['id'], $this->sub_trans_code, $_POST['id'], $_POST['3_'.$x], $this->qty_out);

              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->where('orderd_no',$_POST['id']);
              $this->db->update('t_req_det', array("orderd"=>0)); 

              $this->db->where('cl',$this->sd['cl']);
              $this->db->where('bc',$this->sd['branch']);
              $this->db->where('item',$_POST['0_'.$x]);
              $this->db->update('t_req_det', array("orderd"=>1, "orderd_no"=>$this->max_no)); 
         	}
        }
      }

      $this->utility->save_logger("EDIT",33,$this->max_no,$this->mod);           

    }        
    echo  $this->db->trans_complete();
   }else{
    echo $validation_status;
   }
  }


    public function PDF_report(){


     
      $r_detail['deliver_date'];
      $r_detail['ship_to_bc'];
      $r_detail['supplier'];
      $r_detail['ddate'];
      $r_detail['total_amount'];
      $r_detail['ref_no'];
      $r_detail['branch'];
     

      $this->db->join('m_branch','t_internal_transfer_order_sum.bc=m_branch.bc');
      $this->db->where("nno",$_POST['qno']);
      $query= $this->db->get('t_internal_transfer_order_sum'); 
      if ($query->num_rows() > 0){
          foreach ($query->result() as $row){
                $r_detail['deliver_date']=$row->deliver_date;
                $r_detail['ship_to_bc']=$row->ship_to_bc;
                $r_detail['supplier']=$row->supplier;
                $r_detail['ddate']=$row->ddate;
                $r_detail['total_amount']=$row->total_amount;
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

     



      $sql="SELECT 
      `m_supplier`.`code`, 
      `m_supplier`.`name`, 
      `m_supplier`.`address1`, 
      IFNULL (`m_supplier`.`address2`,'') address2,
      IFNULL (`m_supplier`.`address3`,'') address3, 
      IFNULL (`m_supplier`.`email`,'') email,
      IFNULL (`m_supplier_contact`.`tp`,'') tp FROM (`m_supplier`) 
      LEFT JOIN `m_supplier_contact` ON `m_supplier_contact`.`code`=`m_supplier`.`code` WHERE `m_supplier`.`code`='".$r_detail['supplier']."' LIMIT 1";

      
      $r_detail['suppliers']=$this->db->query($sql)->result();


       $sql="SELECT 
        `m_item`.`code`,
         `m_item`.`description`,
         `m_item`.`model`,
         `m_item`.`purchase_price`,
         `m_item`.`min_price`,
         `m_item`.`max_price`,
         `t_internal_transfer_order_det`.`qty`
        
         FROM `t_internal_transfer_order_det` 
         JOIN m_item ON m_item.`code`=t_internal_transfer_order_det.`item_code`
         WHERE `t_internal_transfer_order_det`.`nno`=".$_POST['qno']." AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";

      $query=$this->db->query($sql);
      if($query->num_rows>0){
        $r_detail['det']=$query->result();
        
      }else{
        $r_detail['det']=2;
      }

   
      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);

    }


       private function set_delete(){
        $this->db->where("nno", $_POST['id']);
        $this->db->where("cl", $_POST['cl']);
        $this->db->where("bc", $_POST['branch']);
        $this->db->delete("t_internal_transfer_order_det");

       }
        
        public function check_code(){
        	$this->db->where('code', $_POST['code']);
        	$this->db->limit(1);
    	
    	    echo $this->db->get($this->mtb)->num_rows;
        }
        
        
         public function load(){

                $x=0;  

               $this->db->select(array(
                    't_internal_transfer_order_sum.ddate' ,
                    't_internal_transfer_order_sum.ref_no' ,
                    't_internal_transfer_order_sum.cl',
                    'm_cluster.description',
                    't_internal_transfer_order_sum.bc',
                    'm_branch.name',
                    't_internal_transfer_order_sum.note',
                    
                  ));

                $this->db->join('m_branch','m_branch.bc=t_internal_transfer_order_sum.bc');
                $this->db->join('m_cluster','m_cluster.code=t_internal_transfer_order_sum.cl');
                $this->db->where('t_internal_transfer_order_sum.cl',$this->sd['cl'] );
                $this->db->where('t_internal_transfer_order_sum.bc',$this->sd['branch'] );
                $this->db->where('t_internal_transfer_order_sum.nno',$_POST['id']);
                $query=$this->db->get('t_internal_transfer_order_sum');

                      
                     

                if($query->num_rows()>0){

                      $a['sum']=$query->result();
                 }else{
                 
                     $x=0;
                } 
                     
  
                $this->db->select(array(
                    't_internal_transfer_order_det.nno',
                    't_internal_transfer_order_det.item_code',
                    'm_item.description',
                    'm_item.model',
                    't_internal_transfer_order_det.item_cost',
                    'm_item.min_price',
                    'm_item.max_price',
                    'qry_current_stock.qty',
                    't_internal_transfer_order_det.qty as quantity'
                   
                  ));

                $this->db->from('t_internal_transfer_order_det');
                $this->db->join('m_item','m_item.code=t_internal_transfer_order_det.item_code');
                $this->db->join('qry_current_stock','m_item.code=qry_current_stock.item');
               
                $this->db->where('t_internal_transfer_order_det.cl',$this->sd['cl'] );
                $this->db->where('t_internal_transfer_order_det.bc',$this->sd['branch'] );
                $this->db->where('t_internal_transfer_order_det.nno',$_POST['id']);
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

          //$this->utility->delete_po_trans($this->tb_po_trans, $this->trans_code, $_POST['id']);

          //$this->utility->cancel_trans('t_req_sum', $_POST['id']);

          //$this->utility->cancel_trans('t_internal_transfer_order_sum', $_POST['id']);

          $this->db->where('cl',$this->sd['cl']);
          $this->db->where('bc',$this->sd['branch']);
          $this->db->where('nno',$_POST['id']);
          echo $this->db->update('t_internal_transfer_order_sum', array("is_cancel"=>1));    


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
            $sql = "SELECT * FROM m_item   JOIN qry_current_stock ON m_item.code = qry_current_stock.item
            WHERE m_item.description LIKE '$_POST[search]%' OR code LIKE '$_POST[search]%' AND inactive='1' LIMIT 25";
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
          // $sql="SELECT
          //    m_item.`description`,
          //    m_item.`model`,
          //    m_item.`purchase_price`,
          //   `t_req_det`.`Item`,
          //    t_req_det.`nNo`,
          //    t_req_det.`Cur_Qty`,
          //    t_req_det.`Approve_Qty`,
          //    t_req_det.`Approve_Qty`*m_item.`purchase_price` AS total
          //    FROM `t_req_det` 
          //    JOIN m_item ON m_item.`code`=t_req_det.`Item`
          //    WHERE `t_req_det`.`nNo` 
          //    IN (SELECT `t_req_sum`.`nNo` FROM `t_req_sum` WHERE `t_req_sum`.`Approved`='1' AND t_req_sum.`Orderd`='0'
          //    AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND `t_req_det`.`Supplier`='". $supplier."')
          //    AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'
          // ";

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
          }
          else
          {

            $a['det']=2;
            echo json_encode($a);
             
          }
          
          
        }

        public function is_batch_item() {

        
        $this->db->select(array("batch_no", "qty"));
        $this->db->where("cl", $this->sd['cl']);
        $this->db->where("bc", $this->sd['branch']);
        $this->db->where("item", $_POST['code']);
        $this->db->where("store_code", $_POST['store']);
        $this->db->where("qty >", "0");
        $query = $this->db->get("qry_current_stock");

        if ($query->num_rows() == 1) {
            foreach ($query->result() as $row) {
                echo $row->batch_no . "-" . $row->qty;
            }
        } else if ($query->num_rows() > 0) {
            echo "1";
        } else {
            echo "0";
        }
    }

        
}