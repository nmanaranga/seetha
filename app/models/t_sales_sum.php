

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_sales_sum extends CI_Model {
    
    private $sd;
    private $mtb;
    private $max_no;
    private $mod = '003';
    
    function __construct(){
    	 parent::__construct();    	
    	  $this->sd=$this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
    	  $this->mtb = $this->tables->tb['t_sales_sum'];
    }
    
    public function base_details(){
    $this->load->model('r_sales_category');
    $a['sales_category'] = $this->r_sales_category->select();
    
    $this->load->model('r_groups');
    $a['groups'] = $this->r_groups->select();

	  $this->load->model('m_customer');
    $a['customer'] = $this->m_customer->select();

    $this->load->model('m_stores');
    $a['stores'] = $this->m_stores->select();

    $this->load->model("utility");
    $a['max_no']= $this->utility->get_max_no("t_sales_sum","nno");
    return $a;
    }
    

    
   
	
    public function save(){
      $this->db->trans_start();
      $this->load->model("utility");
      $this->max_no=$this->utility->get_max_no("t_sales_sum","nno");
      $account_status=$this->account_update();
        
        if($account_status=="1"){
           $_POST['cl']=$this->sd['cl'];
           $_POST['branch']=$this->sd['branch'];
           $_POST['oc']=$this->sd['oc']; 
  
           $t_sales_sum=array(
             "cl"=>$_POST['cl'],
             "bc"=>$_POST['branch'],
             "oc"=> $_POST['oc'],
             "nno"=>$this->max_no,
             "type"=>$_POST['type'],
             "sub_no"=>$_POST['sub_no'],
             "ddate"=>$_POST['date'],
             "ref_no"=>$_POST['ref_no'],
             "cus_id"=>$_POST['customer'],
             "so_no"=>$_POST['serial_no'],
             "category"=>$_POST['sales_category'],
             "memo"=>$_POST['memo'],
             "store"=>$_POST['stores'],
             "rep"=>$_POST['sales_rep'],
             "gross_amount"=>$_POST['gross'],
             "group_no"=>$_POST['groups'],
             "additional"=>$_POST['additional_amount'],
             "net_amount"=>$_POST['net'],
             "post"=>"",
             "post_by"=>"",
             "previlliage_card_no"=>$_POST['privi_card'],
             "previlliage_point_add"=>$_POST['points'],
             "pay_cash"=>$_POST['hid_cash'],
             "pay_issue_chq"=>$_POST['hid_cheque_issue'],
             "pay_receive_chq"=>$_POST['hid_cheque_recieve'],
             "pay_ccard"=>$_POST['hid_credit_card'],
             "pay_cnote"=>$_POST['hid_credit_note'],
             "pay_dnote"=>$_POST['hid_debit_note'],
             "pay_bank_debit"=>$_POST['hid_bank_debit'],
             "pay_discount"=>$_POST['hid_discount'],
             "pay_advance"=>$_POST['hid_advance'],
             "pay_gift_voucher"=>$_POST['hid_gv'],
             "pay_credit"=>$_POST['hid_credit'],
             "pay_privi_card"=>$_POST['hid_pc']
           );

            for($x = 0; $x<25; $x++){
            if(isset($_POST['00_'.$x],$_POST['11_'.$x],$_POST['tt_'.$x])){
                    if($_POST['00_'.$x] != "" && $_POST['11_'.$x] != "" && $_POST['tt_'.$x] != ""){
                        $t_sales_additional_item[]= array(
                            "cl"=>$_POST['cl'],
                            "bc"=>$_POST['branch'],
                            "nno"=>$this->max_no,
                            "type"=>$_POST['00_'.$x],
                            "rate_p"=>$_POST['11_'.$x],
                            "amount"=>$_POST['tt_'.$x]
                        );              
                    }
                }
            }

         $wordChunks = explode(",",$_POST['srls']);
         $execute=0;

        for($x = 0; $x<25; $x++){
            if(isset($_POST['0_'.$x])){
            if($_POST['0_'.$x] != ""){
              $this->db->select(array("purchase_price","min_price","max_price"));
              $this->db->where("code",$_POST['0_'.$x]);
              $result=$this->db->get("m_item")->result();
                          
              foreach($result as $row){
                  $cost=$avg_price=$row->purchase_price;
                  $sales_price=$row->max_price;
                  $last_sales_price=$row->min_price;
              }
                          
                $t_item_movement[]=array(
                  "cl"=>$this->sd['cl'],
                  "bc"=>$this->sd['branch'],
                  "item"=>$_POST['0_'.$x],
                  "trans_code"=>9,
                  "trans_no"=>$this->max_no,
                  "ddate"=>$_POST['date'],
                  "qty_in"=>0,
                  "qty_out"=>(isset($_POST['4_'.$x])?(int)$_POST['5_'.$x]+(int)$_POST['4_'.$x]:$_POST['5_'.$x]),
                  "store_code"=>$_POST['stores'],
                  "avg_price"=>$cost,
                  "batch_no"=>$_POST['1_'.$x],
                  "sales_price"=>$_POST['3_'.$x],
                  "last_sales_price"=>$last_sales_price,
                  "cost"=>$cost
                );

                if($this->check_is_serial_items($_POST['0_'.$x])==1){
                if($execute==0){
                for($i = 0; $i < count($wordChunks); $i++){
                $p=explode("-", $wordChunks[$i]);

                 if($_POST['hid'] == "0" || $_POST['hid'] == ""){
                  
                  $t_seriall=array(
                    "engine_no"=>"",
                    "chassis_no"=>'',
                    "out_doc"=>9,
                    "out_no"=>$this->max_no,
                    "out_date"=>$_POST['date'],
                    "available"=>'0'
                  );


                  $this->db->where('serial_no',$p[1]);
                  $this->db->where("item", $p[0]);
                  $this->db->update("t_serial", $t_seriall);

                  $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='$p[0]' AND serial_no='$p[1]'");

                  $t_serial_movement_out[]=array(
                    "cl"=>$this->sd['cl'],
                    "bc"=>$this->sd['branch'],
                    "trans_type"=>9,
                    "trans_no"=>$this->max_no,
                    "item"=>$p[0],
                    "batch_no"=>$this->get_batch_serial_wise($p[0],$p[1]),
                    "serial_no"=>$p[1],
                    "qty_in"=>0,
                    "qty_out"=>1,
                    "cost"=>$_POST['3_'.$x],
                    "store_code"=>$_POST['stores'],
                    "computer"=>$this->input->ip_address(),
                    "oc"=>$this->sd['oc'],
                  );

                  $this->db->where("item",$p[0]);
                  $this->db->where("serial_no",$p[1]);
                  $this->db->delete("t_serial_movement");
                      
                  }else{
                      
                  }
                  $execute=1;
                    }//end serial for loop
                  } //end execute
               }//check is serial item
           }
       }     
   }
            

            for($x = 0; $x<25; $x++){
              if(isset($_POST['0_'.$x],$_POST['5_'.$x],$_POST['3_'.$x],$_POST['8_'.$x])){
                if($_POST['0_'.$x] != "" && $_POST['5_'.$x] != "" && $_POST['3_'.$x] != "" && $_POST['8_'.$x] != ""){
                    $t_sales_det[]= array(
                        "cl"=>$_POST['cl'],
                        "bc"=>$_POST['branch'],
                        "nno"=>$this->max_no,
                        "code"=>$_POST['0_'.$x],
                        "qty"=>$_POST['5_'.$x],
                        "price"=>$_POST['3_'.$x],
                        "discountp"=>$_POST['6_'.$x],
                        "discount"=>$_POST['7_'.$x],
                        "cost"=>$cost,
                        "foc"=>$_POST['4_'.$x],
                        "batch_no"=>$_POST['1_'.$x],
                        "warranty"=>$_POST['9_'.$x],
                        "amount"=>$_POST['8_'.$x],
                    );              
                }
              }
            }



            if(isset($_POST['hid_pc']) && !empty($_POST['hid_pc'])){
              $t_previlliage_trans=array(
               "cl"=>$_POST['cl'],
               "bc"=>$_POST['branch'],  
               "trans_type"=>$_POST['type'],
               "trans_no"=>$this->max_no,
               "dr"=>0,
               "card_no"=>$_POST['hid_pc_type'],
               "cr"=>$_POST['hid_pc'],
               "ddate"=>$_POST['date']
              );
            }

          if(isset($_POST['points']) && !empty($_POST['points'])){
               $t_previlliage_trans2=array(
                 "cl"=>$_POST['cl'],
                 "bc"=>$_POST['branch'],  
                 "trans_type"=>$_POST['type'],
                 "trans_no"=>$this->max_no,
                 "dr"=>$_POST['points'],
                 "card_no"=>$_POST['privi_card'],
                 "cr"=>0,
                 "ddate"=>$_POST['date']
                );
            }


          if($_POST['hid'] == "0" || $_POST['hid'] == ""){
            $this->db->insert($this->mtb,  $t_sales_sum);

          if(isset($t_sales_det)){ if(count($t_sales_det)){$this->db->insert_batch("t_sales_det",$t_sales_det);}}
          if(isset($t_sales_additional_item)){if(count($t_sales_additional_item)){$this->db->insert_batch("t_sales_additional_item",$t_sales_additional_item);}}
          if(isset($t_item_movement)){ if(count($t_item_movement)){ $this->db->insert_batch("t_item_movement", $t_item_movement);}}
          if(isset($t_serial_movement_out)){ if(count($t_serial_movement_out)){ $this->db->insert_batch("t_serial_movement_out", $t_serial_movement_out);}}
           
          if($_POST['type']==5){
             $this->load->model('trans_settlement');
             $this->trans_settlement->save_settlement("t_cus_settlement",$_POST['customer'],$_POST['date'],$_POST['type'],$this->max_no,$_POST['type'],$this->max_no,$_POST['net'],"0");
          }

          if(isset($_POST['hid_pc']) && !empty($_POST['hid_pc'])){
            $this->db->insert("t_previlliage_trans",  $t_previlliage_trans);
          }

          if(isset($_POST['points']) && !empty($_POST['points'])){
            $this->db->insert("t_previlliage_trans",  $t_previlliage_trans2);
          } 
          
          }else{

            if($_POST['type']==5){
            $this->load->model('trans_settlement');
            $this->trans_settlement->delete_settlement_sub("t_cus_settlement",$_POST['type'],$this->max_no);
            $this->trans_settlement->save_settlement("t_cus_settlement",$_POST['customer'],$_POST['date'],$_POST['type'],$this->max_no,$_POST['type'],$this->max_no,$_POST['net'],"0");
            } 
            
            $this->db->where('nno',$_POST['hid']);
            $this->db->where("cl", $_POST['cl']);
            $this->db->where("bc", $_POST['branch']);
            $this->db->update($this->mtb, $t_sales_sum);
            $this->set_delete();

            $t_serial=array(
                      "engine_no"=>"",
                      "chassis_no"=>'',
                      "out_doc"=>"",
                      "out_no"=>"",
                      "out_date"=>date("Y-m-d",time()),
                      "available"=>'1'
                      );
           
            $this->db->where("out_no",$this->max_no);
            $this->db->where("out_doc",9);
            $this->db->update("t_serial", $t_serial);

            $this->db->select(array('item','serial_no'));
            $this->db->where("trans_no",$this->max_no);
            $this->db->where("trans_type",9);
            $query=$this->db->get("t_serial_movement_out");
          
            foreach($query->result() as $row){
             $this->db->query("INSERT INTO t_serial_movement SELECT * FROM t_serial_movement_out WHERE item='$row->item' AND serial_no='$row->serial_no'");  
             $this->db->where("item",$row->item);
             $this->db->where("serial_no",$row->serial_no);
             $this->db->delete("t_serial_movement_out");
            }

            $this->db->where("trans_no",$this->max_no);
            $this->db->where("trans_type",9);
            $this->db->delete("t_serial_movement");

            $this->db->where("trans_no",$this->max_no);
            $this->db->where("trans_type",9);
            $this->db->delete("t_serial_movement_out");


          for($x = 0; $x<25; $x++){
            if(isset($_POST['0_'.$x])){
               if($this->check_is_serial_items($_POST['0_'.$x])==1){
                  if($execute==0){
                   for($i = 0; $i < count($wordChunks); $i++){
                      $p=explode("-", $wordChunks[$i]);
                     
                      $t_seriall=array(
                      "engine_no"=>"",
                      "chassis_no"=>'',
                      "out_doc"=>9,
                      "out_no"=>$this->max_no,
                      "out_date"=>$_POST['date'],
                      "available"=>'0'
                      );

                      $this->db->where('serial_no',$p[1]);
                      $this->db->where("item", $p[0]);
                      $this->db->update("t_serial", $t_seriall);

                      $this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='$p[0]' AND serial_no='$p[1]'");

                      $t_serial_movement_out[]=array(
                      "cl"=>$this->sd['cl'],
                      "bc"=>$this->sd['branch'],
                      "trans_type"=>9,
                      "trans_no"=>$this->max_no,
                      "item"=>$p[0],
                      "batch_no"=>$this->get_batch_serial_wise($p[0],$p[1]),
                      "serial_no"=>$p[1],
                      "qty_in"=>0,
                      "qty_out"=>1,
                      "cost"=>"",
                      "store_code"=>$_POST['stores'],
                      "computer"=>$this->input->ip_address(),
                      "oc"=>$this->sd['oc'],
                      );

                      $this->db->where("item",$p[0]);
                      $this->db->where("serial_no",$p[1]);
                      $this->db->delete("t_serial_movement");

                    }

                  $execute=1;
                  }//end serial for loop
              } //end execute
          }
      }
           if(isset($t_sales_det)){ if(count($t_sales_det)){$this->db->insert_batch("t_sales_det",$t_sales_det);}}
           if(isset($t_sales_additional_item)){ if(count($t_sales_additional_item)){$this->db->insert_batch("t_sales_additional_item",$t_sales_additional_item);}}
           if(isset($t_serial_movement_out)){ if(count($t_serial_movement_out)){ $this->db->insert_batch("t_serial_movement_out", $t_serial_movement_out);}}
           if(isset($t_item_movement)){ if(count($t_item_movement)){ $this->db->insert_batch("t_item_movement", $t_item_movement);}}
        

            if(isset($_POST['hid_pc']) && !empty($_POST['hid_pc'])){  
                  $this->db->where('trans_no',$_POST['hid']);
                  $this->db->where("cl", $_POST['cl']);
                  $this->db->where("bc", $_POST['branch']);
                  $this->db->update("t_previlliage_trans", $t_previlliage_trans);
               }   

            if(isset($_POST['points']) && !empty($_POST['points'])){
                  $this->db->where('trans_no',$_POST['hid']);
                  $this->db->where("cl", $_POST['cl']);
                  $this->db->where("bc", $_POST['branch']);
                  $this->db->update("t_previlliage_trans", $t_previlliage_trans2);
            }
     }        

          echo $this->db->trans_complete();
        }else{
          echo "0";
      }

 }


    private function set_delete(){
      $this->db->where("nno", $this->max_no);
      $this->db->where("cl", $_POST['cl']);
      $this->db->where("bc", $_POST['branch']);
      $this->db->delete("t_sales_det");

      $this->db->where("nno", $this->max_no);
      $this->db->where("cl", $_POST['cl']);
      $this->db->where("bc", $_POST['branch']);
      $this->db->delete("t_sales_additional_item");

      $this->db->where("cl", $_POST['cl']);
      $this->db->where("bc", $_POST['branch']);
      $this->db->where("trans_code", 9);
      $this->db->where("trans_no", $_POST['hid']);
      $this->db->delete("t_item_movement");
   }



    public function account_update(){
             $this->db->where("trans_no",$this->max_no);
             $this->db->where("trans_code", 9);
             $this->db->where("cl", $this->sd['cl']);
             $this->db->where("bc", $this->sd['branch']);
             $this->db->delete("t_account_trans");

              $config = array(
                "ddate" => $_POST['date'],
                "trans_code"=>$_POST['type'],
                "trans_no"=>$this->max_no,
                "op_acc"=>0,
                "reconcile"=>0,
                "cheque_no"=>0,
                "narration"=>"",
                "ref_no" => $_POST['ref_no']
             );

             $des = "Invoice : ".$_POST['customer'];
             $this->load->model('account');
             $this->account->set_data($config);
            
             $this->account->set_value2($des, $_POST['net'], "dr", $_POST['customer'] );



             if($_POST['load_opt']==0){
                 $this->db->select(array('acc_code'));
                 $this->db->where('code','sales');
                 $acc_code=$this->db->get('m_default_account')->first_row()->acc_code;
                 $this->account->set_value2($des, $_POST['net'], "cr", $acc_code);  
             }else{
                  $this->account->set_value2($des, $_POST['hid_cash'], "cr", "R001");
                  $this->account->set_value2($des, $_POST['hid_cheque_issue'], "cr", "R002");
                  $this->account->set_value2($des, $_POST['hid_cheque_recieve'], "cr", "R003");
                  $this->account->set_value2($des, $_POST['hid_credit_card'], "cr", "R004");
                  $this->account->set_value2($des, $_POST['hid_credit_note'], "cr", "R005");
                  $this->account->set_value2($des, $_POST['hid_debit_note'], "cr", "R006");
                  $this->account->set_value2($des, $_POST['hid_bank_debit'], "cr", "R007");
                  $this->account->set_value2($des, $_POST['hid_discount'], "cr", "R008");
                  $this->account->set_value2($des, $_POST['hid_advance'], "cr", "R009");
                  $this->account->set_value2($des, $_POST['hid_gv'], "cr", "R010");
                  $this->account->set_value2($des, $_POST['hid_credit'], "cr", "R011");
                  $this->account->set_value2($des, $_POST['hid_pc'], "cr", "R012");
             }

           $query=$this->db->query("
             SELECT  (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
             FROM `t_account_trans` t
             LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
             WHERE  t.`cl`='".$this->sd['cl']."'  AND t.`bc`='".$this->sd['branch']."'  AND t.`trans_code`='".$_POST['type']."'  AND t.`trans_no` ='".$_POST['id']."' AND 
             IFNULL( a.`control_acc`,'')=''"); 

             if($query->row()->ok=="0"){
                 $this->db->where("trans_no",  $this->max_no);
                 $this->db->where("trans_code", 9);
                 $this->db->where("cl", $this->sd['cl']);
                 $this->db->where("bc", $this->sd['branch']);
                 $this->db->delete("t_account_trans");
               return "0";
             }else{
               return "1";
             }
    }  


   
    
    public function check_code(){
  	$this->db->where('code', $_POST['code']);
  	$this->db->limit(1);
  	
  	echo $this->db->get($this->mtb)->num_rows;
    }
    
    public function load(){

            $this->db->select(array(
                't_sales_sum.cl' ,
                't_sales_sum.bc' ,
                't_sales_sum.nno' ,
                't_sales_sum.type' ,
                't_sales_sum.sub_no' ,
                't_sales_sum.ddate' ,
                't_sales_sum.ref_no' ,
                't_sales_sum.cus_id' ,
                't_sales_sum.so_no' ,
                't_sales_sum.category' ,
                't_sales_sum.memo' ,
                't_sales_sum.store' ,
                't_sales_sum.rep' ,
                't_sales_sum.gross_amount' ,
                't_sales_sum.group_no' ,
                't_sales_sum.additional' ,
                't_sales_sum.net_amount' ,
                't_sales_sum.oc' ,
                't_sales_sum.action_date' ,
                't_sales_sum.post' ,
                't_sales_sum.post_by' ,
                't_sales_sum.previlliage_card_no' ,
                't_sales_sum.previlliage_point_add' ,
                't_sales_sum.pay_cash' ,
                't_sales_sum.pay_issue_chq',
                't_sales_sum.pay_receive_chq',
                't_sales_sum.pay_ccard' ,
                't_sales_sum.pay_cnote' ,
                't_sales_sum.pay_bank_debit' ,
                't_sales_sum.pay_advance' ,
                't_sales_sum.pay_gift_voucher' ,
                't_sales_sum.pay_credit' ,
                't_sales_sum.pay_privi_card' ,
                'm_customer.name',
                'm_customer.address1',
                'm_customer.address2',
                'm_customer.address3',
                'm_employee.name as rep_name',

              ));

            $this->db->from('t_sales_sum');
            $this->db->join('m_customer','m_customer.code=t_sales_sum.cus_id');
            $this->db->join('m_employee','m_employee.code=t_sales_sum.rep');

            $this->db->where('t_sales_sum.cl',$this->sd['cl'] );
            $this->db->where('t_sales_sum.bc',$this->sd['branch'] );
            $this->db->where('t_sales_sum.nno',$_POST['id']);

            $query=$this->db->get();

                  $x=0;

                 if($query->num_rows()>0){
                  $a['sum']=$query->result();
                     }else{
                    $x=2;
                  }


          $this->db->select(array(
              't_sales_det.code',
              't_sales_det.qty',
              't_sales_det.discountp',
              't_sales_det.discount',
              't_sales_det.price',
              't_sales_det.amount',
              't_sales_det.foc',
              't_sales_det.batch_no',
              't_sales_det.warranty',
              'm_item.description as item_des',
              'm_item.model'
              ));
       
       $this->db->from('t_sales_det');
       $this->db->join('m_item','m_item.code=t_sales_det.code');
       $this->db->where('t_sales_det.cl',$this->sd['cl'] );
       $this->db->where('t_sales_det.bc',$this->sd['branch'] );
       $this->db->where('t_sales_det.nno',$_POST['id']);
       $query=$this->db->get();

       if($query->num_rows()>0){
                $a['det']=$query->result();
                   }else{
                  $x=2;
                }


         $this->db->select(array(
                't_sales_additional_item.type as sales_type',
                't_sales_additional_item.rate_p',
                't_sales_additional_item.amount',
                'r_additional_item.description' 
                ));
         
         $this->db->from('t_sales_additional_item');
         $this->db->join('r_additional_item','r_additional_item.code=t_sales_additional_item.type');
         $this->db->where('t_sales_additional_item.cl',$this->sd['cl'] );
         $this->db->where('t_sales_additional_item.bc',$this->sd['branch'] );
         $this->db->where('t_sales_additional_item.nno',$_POST['id']);
         $query=$this->db->get();

         if($query->num_rows()>0){
                  $a['add']=$query->result();
            }else{
                 $a['add']=2;
            }

        $this->db->select(array('t_serial.item','t_serial.serial_no'));
        $this->db->from('t_serial');
        $this->db->join('t_sales_sum','t_serial.out_no=t_sales_sum.nno');
        $this->db->where('t_serial.out_doc',9);
        $this->db->where('t_serial.out_no',$_POST['id']);
        $this->db->where('t_sales_sum.cl',$this->sd['cl']);
        $this->db->where('t_sales_sum.bc',$this->sd['branch']);
        $query=$this->db->get();



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
    	$p = $this->user_permissions->get_permission($this->mod, array('is_delete'));
    	
    	if($p->is_delete){
    	    $this->db->where('code', $_POST['code']);
    	    $this->db->limit(1);
    	    
    	    echo $this->db->delete($this->mtb);
    	}else{
    	    echo 2;
    	}
        }




  public function get_next_no(){
    if(isset($_POST['hid'])){
            if($_POST['hid'] == "0" || $_POST['hid'] == ""){      
            $field="nno";
            $this->db->select_max($field);
            $this->db->where("cl",$this->sd['cl']);
            $this->db->where("bc",$this->sd['branch']);    
            return $this->db->get("t_sales_sum")->first_row()->$field+1;
          }else{
            return $_POST['hid'];  
          }
        }else{
            $field="nno";
            $this->db->select_max($field);
            $this->db->where("cl",$this->sd['cl']);
            $this->db->where("bc",$this->sd['branch']);    
            return $this->db->get("t_sales_sum")->first_row()->$field+1;
    }   
  }
   

   public function is_batch_item(){
    $this->db->select(array("batch_no","qty"));
    $this->db->where("item",$_POST['code']); 
    $this->db->where("store_code",$_POST['store']);
    $this->db->where("qty >","0"); 
    $query=$this->db->get("qry_current_stock");

    if($query->num_rows()==1){
            foreach($query->result() as $row){
                echo $row->batch_no."-".$row->qty;
            }
         }else if($query->num_rows() > 0){
                echo "1";
         }else{
                echo "0";
        }
  }

   public function batch_item(){
   
         $this->db->where("item",$_POST['search']);
         $this->db->where("store_code",$_POST['stores']);
         $this->db->where("qty >","0"); 
         $query = $this->db->get('qry_current_stock');
        
       $a = "<table id='batch_item_list' style='width : 100%' >";
        
       $a .= "<thead><tr>";
            $a .= "<th class='tb_head_th'>Batch No</th>";
            $a .= "<th class='tb_head_th'>Available Quantity</th>";
            $a .= "<th class='tb_head_th'>Cost</th>";
            
            
         
        $a .= "</thead></tr>";
            $a .= "<tr class='cl'>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                   
                   
                    
                $a .= "</tr>";
            foreach($query->result() as $r){
                $a .= "<tr class='cl'>";
                    $a .= "<td>".$r->batch_no."</td>";
                    $a .= "<td>".$r->qty."</td>";
                    $a .= "<td>".$r->cost."</td>";
                                      
                    
                $a .= "</tr>";
            }
        $a .= "</table>";
        
        echo $a;
    }

  function get_batch_qty(){
    $this->db->select(array('qty'));
    $this->db->where("batch_no",$this->input->post("batch_no"));
    $this->db->where("store_code",$this->input->post('store'));
    $this->db->where("item",$this->input->post('code'));
    $query=$this->db->get("qry_current_stock");

    foreach ($query->result() as $row) {
      echo $row->qty;
    }

  }

  function get_sub_no(){
        $field="sub_no";
        $this->db->select_max($field);
        $this->db->where("type",$this->input->post('type'));
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);    
        echo $this->db->get("t_sales_sum")->first_row()->$field+1;
  }


   public function item_list_all(){
    
    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}      
       // $sql = "SELECT * FROM m_item  WHERE description LIKE '$_POST[search]%' OR code LIKE '$_POST[search]%' OR max_price LIKE '$_POST[search]%' AND inactive='1' LIMIT 25";
       
        $sql ="SELECT m_item.code, m_item.`description`,m_item.`model`,m_item.`max_price` FROM m_item JOIN qry_current_stock ON
   m_item.`code`=qry_current_stock.`item` WHERE qry_current_stock.`store_code`='$_POST[stores]' AND (m_item.`description` LIKE '%$_POST[search]%' OR 
   m_item.model LIKE '$_POST[search]%' OR m_item.`max_price` LIKE '$_POST[search]%') LIMIT 25";

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
      

      $this->db->where("code",$_POST['sales_type']);
      $query= $this->db->get('t_trans_code'); 
      if ($query->num_rows() > 0){
          foreach ($query->result() as $row){
            $r_detail['r_type']= $row->description;       
            }
          } 

      $r_detail['type']=$_POST['type'];        
      $r_detail['dt']=$_POST['dt'];
      $r_detail['qno']=$_POST['qno'];

      $r_detail['page']=$_POST['page'];
      $r_detail['header']=$_POST['header'];
      $r_detail['orientation']=$_POST['orientation'];

      $this->db->select(array('code','name','address1','address2','address3'));
      $this->db->where("code",$_POST['cus_id']);
      $r_detail['customer']=$this->db->get('m_customer')->result();

      $this->db->select(array('name'));
      $this->db->where("code",$_POST['salesp_id']);
      $query=$this->db->get('m_employee');
      
      foreach ($query->result() as $row){
        $r_detail['employee']= $row->name;
      }


      $this->db->select(array('t_sales_det.code','t_sales_det.qty','t_sales_det.discount','t_sales_det.price','t_sales_det.cost','m_item.description','m_item.model'));
      $this->db->from('t_sales_det');
      $this->db->join('m_item','m_item.code=t_sales_det.code');
      $this->db->where('t_sales_det.cl',$this->sd['cl'] );
      $this->db->where('t_sales_det.bc',$this->sd['branch']);
      $this->db->where('t_sales_det.nno',$_POST['qno']);
      $r_detail['items']=$this->db->get()->result();


      $this->db->select(array('gross_amount','net_amount'));
      $this->db->where('t_sales_sum.cl',$this->sd['cl'] );
      $this->db->where('t_sales_sum.bc',$this->sd['branch']);
      $this->db->where('t_sales_sum.nno',$_POST['qno']);
      $r_detail['amount']=$this->db->get('t_sales_sum')->result();

      $this->db->select(array('t_sales_additional_item.type','t_sales_additional_item.amount','r_additional_item.description','r_additional_item.is_add'));
      $this->db->from('t_sales_additional_item');
      $this->db->join('r_additional_item','t_sales_additional_item.type=r_additional_item.code');
      $this->db->where('t_sales_additional_item.cl',$this->sd['cl'] );
      $this->db->where('t_sales_additional_item.bc',$this->sd['branch']);
      $this->db->where('t_sales_additional_item.nno',$_POST['qno']);
      $r_detail['additional']=$this->db->get()->result();


      $this->db->select_sum("discount");
      $this->db->where('cl',$this->sd['cl'] );
      $this->db->where('bc',$this->sd['branch']);
      $this->db->where('nno',$_POST['qno']);
      $r_detail['discount']=$this->db->get('t_sales_det')->result();

      $this->db->select(array('loginName'));
      $this->db->where('cCode',$this->sd['oc']);
      $r_detail['user']=$this->db->get('users')->result();



      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }


   public function get_payment_option(){
     $this->db->where("code",$_POST['code']);
     $data['result']=$this->db->get("r_payment_option")->result();
     echo json_encode($data);
   }

   public function get_points(){
         $query=$this->db->query("
            SELECT t_previlliage_trans.card_no,(SUM(t_previlliage_trans.dr)-SUM(t_previlliage_trans.cr)) AS points FROM t_previlliage_trans 
            JOIN t_privilege_card ON t_previlliage_trans.card_no=t_privilege_card.card_no 
            WHERE t_privilege_card.customer_id='".$_POST['customer']."'
            GROUP BY card_no
          ");


        $data['points_res']=$query->first_row();
         
         echo json_encode($data);
   }

      public function get_points2(){
         $query=$this->db->query("
            SELECT card_no,(SUM(dr)-SUM(cr)) AS points FROM t_previlliage_trans 
            WHERE card_no='".$_POST['type']."'
            GROUP BY card_no
          ");


        $data['points_res']=$query->first_row();
         echo json_encode($data);
     }


      public function get_points3(){
         $query=$this->db->query("
           SELECT card_no,(SUM(dr)-SUM(cr)) AS points FROM t_previlliage_trans WHERE card_no='".$_POST['card_no']."' 
           AND trans_type='".$_POST['trans_type']."' AND trans_no<>'".$_POST['trans_no']."' GROUP BY card_no;
          ");
        $data['points_res']=$query->first_row();
        echo json_encode($data);
     }

     public function check_pv_no(){
        $this->db->select(array("card_no"));
        $this->db->where('card_no', $_POST['privi_card']);
        $this->db->limit(1);
        echo $this->db->get("t_privilege_card")->num_rows;
     }

     public function get_department_pv_rate(){
      $this->db->select(array("pv_card_rate"));
      $this->db->from("r_department");
      $this->db->join("m_item","r_department.code=m_item.department");
      $this->db->where("m_item.code",$this->input->post('code'));
      echo $this->db->get()->first_row()->pv_card_rate;
     }



    public function check_is_serial_item(){
      $this->db->select(array('serial_no'));
      $this->db->where("code",$this->input->post('code'));
      $this->db->limit(1);
      echo  $this->db->get("m_item")->first_row()->serial_no;
    }


    public function check_is_serial_items($code){
      $this->db->select(array('serial_no'));
      $this->db->where("code",$code);
      $this->db->limit(1);
      $query=$this->db->get('m_item');
      if($query->num_rows()>0){
        foreach($query->result() as $row){
          return $row->serial_no;    
        }
      }
      
    }




    public function is_serial_available(){

      if(!isset($_POST['nno'])){
          $this->db->select(array('available'));
          $this->db->where("serial_no",$_POST['serial']);
          $this->db->where("item",$_POST['item']);
          $query=$this->db->get("t_serial");
          if($query->num_rows()>0){
            echo $query->first_row()->available;
          }else{
            echo $query->first_row()->available;
          }

      }else{
          $result=0;

          $this->db->where("serial_no",$_POST['serial']);
          $this->db->where("item",$_POST['item']);
          $this->db->where("trans_no",$_POST['nno']);
          //$this->db->where("trans_type",$_POST['type']);
          $query=$this->db->get("t_serial_movement_out")->num_rows();
          

          if($query==0){
            $this->db->select(array('available'));
            $this->db->where("serial_no",$_POST['serial']);
            $this->db->where("item",$_POST['item']);
            $qry=$this->db->get("t_serial");
            if($qry->num_rows()>0){
              $result=$qry->first_row()->available;
            }else{
              $result=$qry->first_row()->available;
            }
          }else{
              $result=1;
          }

          echo $result;
      }      

    }




    public function is_serial_entered($trans_no,$item,$serial){
      $this->db->select(array('available'));
      $this->db->where("serial_no",$serial);
      $this->db->where("item",$item);
      $query=$this->db->get("t_serial");

      if($query->num_rows()>0){
        return 1;
      }else{
        return 0;
      }
    }


    public function check_last_serial(){
      $this->db->select("serial_no");
      $this->db->where("item",$_POST['item']);
      $this->db->order_by("auto_num", "desc");
      $this->db->limit(1);
      $query=$this->db->get("t_serial");

      if($query->num_rows()>0){
        echo $query->first_row()->serial_no;
          }else{
         echo 0;
      }
    }


    public function get_batch_serial_wise($item,$serial){
      $this->db->select("batch");
      $this->db->where("item",$item);
      $this->db->where("serial_no",$serial);
      return $this->db->get('t_serial')->first_row()->batch; 
    }


    public function serial_item(){
          $item=$this->input->post('item');
          $store=$this->input->post('stores');
          $cl=$this->sd['cl'];
          $bc=$this->sd['branch'];
          $search=$this->input->post('search');

           $sql="SELECT t_serial.`date`,t_serial.`serial_no`,t_serial.`batch`
                      FROM t_serial 
                      WHERE t_serial.`item`='$item' AND t_serial.`store_code`='$store' AND t_serial.`cl`='$cl' AND t_serial.`bc`='$bc' AND t_serial.`available`='1' 
                      AND t_serial.serial_no LIKE '%$search%'
                      LIMIT 25";
          
             $query = $this->db->query($sql);
             $a = "<table id='serial_item_list' style='width : 100%' >";
             $a .= "<thead><tr>";
                $a .= "<th class='tb_head_th'>Batch No</th>";
                $a .= "<th class='tb_head_th'>Date</th>";
                $a .= "<th class='tb_head_th'>Serial No</th>";
                $a .= "</thead></tr>";

                    $a .= "<tr class='cl'>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "<td>&nbsp;</td>";
                    $a .= "</tr>";

            foreach($query->result() as $r){
                    $a .= "<tr class='cl'>";
                    $a .= "<td>".$r->batch."</td>";
                    $a .= "<td>".$r->date."</td>";
                    $a .= "<td>".$r->serial_no."</td>";
                    $a .= "</tr>";
            }
            $a .= "</table>";
        echo $a;
    }


}