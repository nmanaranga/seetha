<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class t_sales_return_sum extends CI_Model {

	private $sd;
	private $mtb;
	private $max_no;
	private $credit_max_no;
	private $mod = '003';

	function __construct() {
		parent::__construct();
		$this->sd = $this->session->all_userdata();
		$this->load->database($this->sd['db'], true);
		$this->mtb = $this->tables->tb['t_sales_return_sum'];
		$this->load->model('user_permissions');
	}

	public function base_details() {
		$a['nno'] = $this->utility->get_max_no('t_sales_return_sum', 'nno');
		$this->load->model('m_stores');
		$a['stores'] = $this->m_stores->select();
		$a["crn_no"] = $this->get_credit_max_no();
		return $a;
	}

	public function get_credit_max_no() {
		if (isset($_POST['hid'])) {
			if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
				$field = "nno";
				$this->db->select_max($field);
				$this->db->where("cl", $this->sd['cl']);
				$this->db->where("bc", $this->sd['branch']);
				return $this->db->get("t_credit_note")->first_row()->$field + 1;
			}else{
                //return $_POST['crn_no'];
				$field = "nno";
				$this->db->select_max($field);
				$this->db->where("cl", $this->sd['cl']);
				$this->db->where("bc", $this->sd['branch']);
				return $this->db->get("t_credit_note")->first_row()->$field + 1;
			}
		}else{
			$field = "nno";
			$this->db->select_max($field);
			$this->db->where("cl", $this->sd['cl']);
			$this->db->where("bc", $this->sd['branch']);
			return $this->db->get("t_credit_note")->first_row()->$field + 1;
		}
	}

	public function validation(){
		$this->max_no = $this->utility->get_max_no("t_sales_return_sum", "nno");

		$status=1;

		$check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_sales_return_sum');
		if($check_is_delete!=1){
			return "Sales return already deleted.";
		}
		$check_customer_validation=$this->validation->check_is_customer($_POST['customer']);
		if($check_customer_validation!=1){
			return "Please enter valid customer";
		}
		$check_employee_validation=$this->validation->check_is_employer($_POST['sales_rep']);
		if($check_employee_validation!=1){
			return "Please enter valid sales rep";
		}
		$serial_validation_status=$this->validation->serial_update('0_','1_',"all_serial_");
		if($serial_validation_status!=1){
			return $serial_validation_status;
		}
		$check_zero_value=$this->validation->empty_net_value($_POST['net_amount']);
		if($check_zero_value!=1){
			return $check_zero_value;
		} 
		$chk_zero_qty=$this->validation->empty_qty('0_','1_');
		if($chk_zero_qty!=1){
			return $chk_zero_qty;
		}



		return $status;
	}

	public function validation2(){
		$status=1;

    /*$check_batch_quantity_validation=$this->utility->batch_update3('0_','bt_','1_');
    if($check_batch_quantity_validation!=1){ 
      return $check_batch_quantity_validation;
  }*/
  $check_return_qty_validation=$this->utility->check_return_qty('0_','1_',$_POST['inv_no'],$_POST['type']);
  if($check_return_qty_validation!=1){ 
  	return $check_return_qty_validation;
  }
  $check_item_with_invoice=$this->utility->check_item_with_invoice($_POST['inv_no'],'0_',$_POST['type']);
  if($check_item_with_invoice!=1){
  	return $check_item_with_invoice;
  }
  $check_item_price_validation=$this->utility->check_item_price('0_',$_POST['inv_no'],$_POST['type'],'2_','bt_');
  if($check_item_price_validation!=1){  
  	return $check_item_price_validation;
  } 


    /*if (isset($_POST['inv_no'])) {
      $check_inv_with_customer_status=$this->validation->check_inv_customer($_POST['customer'],$_POST['inv_no'],$_POST['type']);
      if($check_inv_with_customer_status != 1) {
        return $check_inv_with_customer_status;
      } 
  }*/

    /*$check_invoice_serial_status=$this->validation->check_invoice_serial($_POST['inv_no'],$_POST['type']);
    if($check_invoice_serial_status != 1) {
      return $check_invoice_serial_status;
  }  */

  $check_sale_is_cancel=$this->validation->sale_is_cancel($_POST['inv_no'],$_POST['type']);
  if($check_sale_is_cancel != 1) {
  	return $check_sale_is_cancel;
  }  



  return $status;
}

public function validation3(){
	$status=1;

	$validation_status  = $this->validation();
	$validation_status2 = $this->validation2();

	if ($validation_status == 1) {
		if ($_POST['inv_no'] == "" || $_POST['inv_no'] == 0){
			$status=1;
		}else{
			if($validation_status2 == 1){
				$status=1;
			}else{
				$status=$validation_status2;
			}        
		}          
	}else{
		$status=$validation_status;
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
		$this->credit_max_no = $this->get_credit_max_no();
		$validation_status = $this->validation3();

		if ($validation_status == 1) {
			$t_sales_return_sum = array(
				"cl"         => $this->sd['cl'],
				"bc"         => $this->sd['branch'],
				"nno"        => $this->max_no,
				"ddate"      => $_POST['date'],
				"ref_no"     => $_POST['ref_no'],
				"sales_type" => $_POST['type'],
				"cus_id" => $_POST['customer'],
				"inv_no" => $_POST['inv_no'],
				"crn_no" => $this->credit_max_no,
				"memo"   => $_POST['memo'],
				"store"  => $_POST['stores'],
				"rep"    => $_POST['sales_rep'],
				"gross_amount" => $_POST['total'],
				"discount" => $_POST['discount'],
				"net_amount" => $_POST['net_amount'],
				"oc" => $this->sd['oc'],
				"addi_add" =>$_POST['additional_add'],
				"addi_deduct" => $_POST['additional_deduct'],
				"is_approve"=> "0"
			);

			$t_credit_note = array(
				"cl" => $this->sd['cl'],
				"bc" => $this->sd['branch'],
				"nno" => $this->credit_max_no,
				"ddate" => $_POST['date'],
				"ref_no" => $_POST['ref_no'],
				"memo" => "SALES RETURN [" . $this->max_no . "]",
				"is_customer" => 1,
				"code" => $_POST['customer'],
				"acc_code" => $this->utility->get_default_acc('STOCK_ACC'),
				"amount" => $_POST['net_amount'],
				"oc" => $this->sd['oc'],
				"post" => "",
				"post_by" => "",
				"post_date" => "",
				"is_cancel" => 0,
				"ref_trans_no" => $this->max_no,
				"ref_trans_code" => 8,
				"balance"=>$_POST['net_amount']
			);

			$subs="";

			for ($x = 0; $x < 25; $x++) {
				if (isset($_POST['0_' . $x])) {
					if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
						if(isset($_POST['subcode_'.$x])){
							$subs = $_POST['subcode_'.$x];
							if($_POST['subcode_'.$x]!="0" && $_POST['subcode_'.$x]!=""){

								$sub_items = (explode(",",$subs));
								$arr_length= sizeof($sub_items);

								for($y = 0; $y<$arr_length; $y++){
									$item_sub = (explode("-",$sub_items[$y]));
									$sub_qty = (int)$_POST['1_'.$x] * (int)$item_sub[1];

									$t_sub_item_movement[] = array(
										"cl" => $this->sd['cl'],
										"bc" => $this->sd['branch'],
										"item" => $_POST['0_' . $x],
										"sub_item"=>$item_sub[0],
										"trans_code" => 8,
										"trans_no" => $this->max_no,
										"ddate" => $_POST['date'],
										"qty_in" =>$sub_qty,
										"qty_out" => 0,
										"store_code" => $_POST['stores'],
										"avg_price" => $this->utility->get_cost_price($_POST['0_' . $x]),
										"batch_no" =>  $_POST['bt_' . $x],
										"sales_price" => $_POST['2_' . $x],
										"last_sales_price" => $this->get_sales_min_price($_POST['inv_no'],$_POST['0_' . $x],$_POST['type']),
										"cost" =>$this->get_sales_purchase_price($_POST['inv_no'],$_POST['0_' . $x],$_POST['type'])
									);
								}
							}
						}



						$t_sales_return_det[] = array(
							"cl" => $this->sd['cl'],
							"bc" => $this->sd['branch'],
							"nno" => $this->max_no,
							"code" => $_POST['0_' . $x],
							"qty" => $_POST['1_' . $x],
							"discountp" => $_POST['3_' . $x],
							"discount" => $_POST['4_' . $x],
							"price" => $_POST['2_' . $x],
							"cost" => $this->get_sales_purchase_price($_POST['inv_no'],$_POST['0_' . $x],$_POST['type']),
							"batch_no" => $_POST['bt_' . $x],
							"amount" => $_POST['5_' . $x],
							"min_price"=>$this->get_sales_min_price($_POST['inv_no'],$_POST['0_' . $x],$_POST['type']),
							"reason"=>$_POST['ret_'.$x],
							"is_free"=>$_POST['is_free_'.$x],

						);
					}
				}   
			}
			for ($x = 0; $x < 15; $x++) {
				if (isset($_POST['000_' . $x], $_POST['111_' . $x], $_POST['222_' . $x])) {
					if ($_POST['000_' . $x] != "" && $_POST['111_' . $x] != "" && $_POST['222_' . $x] != "") {
						$t_srn_ret_additional_item[] = array(
							"cl" => $this->sd['cl'],
							"bc" => $this->sd['branch'],
							"nno" => $this->max_no,
							"type" => $_POST['000_' . $x],
							"rate_p" => $_POST['111_' . $x],
							"amount" => $_POST['222_' . $x]
						);
					}
				}
			}

			$approve = array(
				"is_approve"=> "1"
			);

			if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
				if($this->user_permissions->is_add('t_sales_return_sum') || $this->user_permissions->is_add('t_sales_return_sum_without_invoice')){

					$this->db->insert('t_sales_return_sum', $t_sales_return_sum);             
					if (count($t_sales_return_det)) {
						$this->db->insert_batch("t_sales_return_det", $t_sales_return_det);
					}
					if (isset($t_srn_ret_additional_item)) {
						if (count($t_srn_ret_additional_item)) {
							$this->db->insert_batch("t_srn_ret_additional_item", $t_srn_ret_additional_item);
						}
					}
					$this->db->where("trans_no", $this->max_no);
					$this->db->where("trans_code", 8);
					$this->db->where("cl", $this->sd['cl']);
					$this->db->where("bc", $this->sd['branch']);
					$this->db->delete("t_serial_trans");

					$this->save_tempory_serials();

					$this->utility->save_logger("SAVE",8,$this->max_no,$this->mod);

					echo $this->db->trans_commit()."@".$this->max_no;
				}else{
					$this->db->trans_commit();
					echo "No permission to save records";
				}
			}else{

				if($_POST['approve']=="2"){
					if($this->user_permissions->is_approve('t_pur_ret_sum')){                                      
						$account_update=$this->account_update(0);
						if($account_update==1){

							$this->account_update(1);

							$this->set_delete();
							$this->remove_tempory_serials();

							$this->db->where("cl", $this->sd['cl']);
							$this->db->where("bc", $this->sd['branch']);
							$this->db->where('nno', $_POST['hid']);
							$this->db->update('t_sales_return_sum', $t_sales_return_sum);    

							$this->db->where("cl", $this->sd['cl']);
							$this->db->where("bc", $this->sd['branch']);
							$this->db->where('nno', $_POST['hid']);
							$this->db->update('t_sales_return_sum', $approve);


							if (count($t_sales_return_det)) {
								$this->db->insert_batch("t_sales_return_det", $t_sales_return_det);
							}  

							if (isset($t_srn_ret_additional_item)) {
								if (count($t_srn_ret_additional_item)) {
									$this->db->insert_batch("t_srn_ret_additional_item", $t_srn_ret_additional_item);
								}
							} 

							if($_POST["df_is_serial"]=='1'){
								$this->save_serial();     
							}

							$this->load->model('trans_settlement');
							$this->trans_settlement->save_settlement("t_credit_note_trans", $_POST['customer'], $_POST['date'], 17, $this->credit_max_no, 8, $this->max_no, $_POST['net_amount'], "0");
							$this->db->insert('t_credit_note', $t_credit_note);

							for($x = 0; $x < 25; $x++){
								if(isset($_POST['0_'.$x])){
									if($_POST['0_'.$x] != "" || !empty($_POST['0_'.$x])){
										$this->trans_settlement->save_item_movement('t_item_movement',$_POST['0_'.$x],'8',$this->max_no,$_POST['date'],$_POST['1_' . $x],0,$_POST['stores'],$this->utility->get_cost_price($_POST['0_' . $x]),$_POST['bt_'.$x],$this->get_sales_min_price($_POST['inv_no'],$_POST['0_' . $x],$_POST['type']),$_POST['2_'.$x],$this->get_sales_purchase_price($_POST['inv_no'],$_POST['0_' . $x],$_POST['type']),001);
									}
								}
							}

							if(isset($t_sub_item_movement)){if(count($t_sub_item_movement)){$this->db->insert_batch("t_item_movement_sub",$t_sub_item_movement);}}

							$this->update_sales_return_quantity();
							$this->utility->update_credit_note_balance($_POST['customer']);

							$this->utility->save_logger("APPROVE",8,$this->max_no,$this->mod); 
							echo $this->db->trans_commit()."@".$this->max_no;;
						}else{
							return "Invalid account entries";
						}
					}else{
						echo "No permission to approve records";
						$this->db->trans_commit();
					}

				}else{

					if($this->user_permissions->is_edit('t_sales_return_sum') || $this->user_permissions->is_edit('t_sales_return_sum_without_invoice')){
						$this->db->where("cl", $this->sd['cl']);
						$this->db->where("bc", $this->sd['branch']);
						$this->db->where('nno', $_POST['hid']);
						$this->db->update('t_sales_return_sum', $t_sales_return_sum);        

						$this->set_delete();
						$this->remove_tempory_serials();


						if (count($t_sales_return_det)) {
							$this->db->insert_batch("t_sales_return_det", $t_sales_return_det);
						}  

						if (isset($t_srn_ret_additional_item)) {
							if (count($t_srn_ret_additional_item)) {
								$this->db->insert_batch("t_srn_ret_additional_item", $t_srn_ret_additional_item);
							}
						} 
						$this->db->where("trans_no", $this->max_no);
						$this->db->where("trans_code", 8);
						$this->db->where("cl", $this->sd['cl']);
						$this->db->where("bc", $this->sd['branch']);
						$this->db->delete("t_serial_trans");

						$this->save_tempory_serials();

						$this->utility->save_logger("EDIT",8,$this->max_no,$this->mod);
						echo $this->db->trans_commit()."@".$this->max_no;

					}else{
						echo "No permission to edit records";
						$this->db->trans_commit();
					}
				}
				
				
			}
		}else{
			echo $validation_status;
			$this->db->trans_commit();
		}
	}catch(Exception $e){ 
		$this->db->trans_rollback();
		echo $e->getMessage()."Operation fail please contact admin".$e; 
	} 
}


public function update_sales_return_quantity(){

	$inv_no=$_POST['inv_no'];
      //updating return quantiy

	if($_POST['type']=='4'){

		$sql="UPDATE `t_cash_sales_det` d, 
		(SELECT s.cl,s.bc,inv_no, d.`code`, SUM(qty) AS qty FROM `t_sales_return_det` d INNER JOIN `t_sales_return_sum` s ON d.`nno`=s.`nno`
		AND d.`cl`=s.`cl` AND d.`bc`=s.`bc` WHERE inv_no='$inv_no' AND d.cl='".$this->sd['cl']."' AND d.bc='".$this->sd['branch']."' GROUP BY inv_no, d.`code`) t
		SET d.`return_qty`=t.qty
		WHERE (d.`nno`=t.inv_no)  AND (d.`code`=t.code) AND (d.`cl`=t.cl) AND (d.`bc`=t.bc)";
		$this->db->query($sql);      

	}else if($_POST['type']=='5'){

		$sql="UPDATE `t_credit_sales_det` d, 
		(SELECT s.cl,s.bc,inv_no, d.`code`, SUM(qty) AS qty FROM `t_sales_return_det` d INNER JOIN `t_sales_return_sum` s ON d.`nno`=s.`nno`
		AND d.`cl`=s.`cl` AND d.`bc`=s.`bc` WHERE inv_no='$inv_no' AND d.cl='".$this->sd['cl']."' AND d.bc='".$this->sd['branch']."' GROUP BY inv_no, d.`code`) t
		SET d.`return_qty`=t.qty
		WHERE (d.`nno`=t.inv_no)  AND (d.`code`=t.code) AND (d.`cl`=t.cl) AND (d.`bc`=t.bc)";
		$this->db->query($sql);
	}
}

public function save_tempory_serials(){
	for($x =0; $x<25; $x++){
		if(isset($_POST['0_'.$x])){
			if($_POST['0_'.$x] != "" || !empty($_POST['0_' . $x])){
				if($this->check_is_serial_items($_POST['0_'.$x])==1){
					$serial = $_POST['all_serial_'.$x];
					$pp=explode(",",$serial);

					for($t=0; $t<count($pp); $t++){
						$p = explode("-",$pp[$t]);  

						$t_serial_trans[]=array(
							"cl"=>$this->sd['cl'],
							"bc"=>$this->sd['branch'],
							"trans_code"=>8,
							"trans_no"=>$this->max_no,
							"item_code"=>$_POST['0_'.$x],
							"serial_no"=>$p[0],
							"qty"=>1,
							"computer"=>$this->input->ip_address(),
							"oc"=>$this->sd['oc'],
						); 
					}                  
				}
			}
		}
	}
	if(isset($t_serial_trans)){if(count($t_serial_trans)){  $this->db->insert_batch("t_serial_trans", $t_serial_trans);}}
}

public function remove_tempory_serials(){
	$this->db->where("trans_no", $this->max_no);
	$this->db->where("trans_code", 8);
	$this->db->where("cl", $this->sd['cl']);
	$this->db->where("bc", $this->sd['branch']);
	$this->db->delete("t_serial_trans");
}


public function account_update($condition) {

	$this->db->where("trans_no", $this->max_no);
	$this->db->where("trans_code", 8);
	$this->db->where("cl", $this->sd['cl']);
	$this->db->where("bc", $this->sd['branch']);
	$this->db->delete("t_check_double_entry");


	if($_POST['hid']=="0"||$_POST['hid']==""){
	}else{
		if($condition=="1"){
			$sql="SELECT is_approve FROM t_sales_return_sum WHERE cl='".$this->sd['cl']."' 
			AND bc='".$this->sd['branch']."' AND nno='".$this->max_no."' LIMIT 1";
			$query=$this->db->query($sql);
			if($query->first_row()->is_approve!=0){
				$this->db->where("trans_no",  $this->max_no);
				$this->db->where("trans_code", 8);
				$this->db->where("cl", $this->sd['cl']);
				$this->db->where("bc", $this->sd['branch']);
				$this->db->delete("t_account_trans");
			}
		}
	}


	$config = array(
		"ddate" => $_POST['date'],
		"trans_code" => 8,
		"trans_no" => $this->max_no,
		"op_acc" => 0,
		"reconcile" => 0,
		"cheque_no" => 0,
		"narration" => "",
		"ref_no" => $_POST['ref_no']
	);

	if($_POST['type']=="4"){
		$tp_des="Cash Sale - ";
	}else{
		$tp_des="Credit Sale - ";
	}

	$sql_cus="SELECT name FROM m_customer WHERE code='".$_POST['customer']."' LIMIT 1";
	$cus = $this->db->query($sql_cus)->row()->name;

	$des = $tp_des.$_POST['inv_no']." - ".$cus;

	$this->load->model('account');
	$this->account->set_data($config);

	$total_discount=$_POST['discount'];
	$total_amount=$_POST['net_amount'];

	if($total_discount>0){
		$acc_code = $this->utility->get_default_acc('SALES_DISCOUNT');
		$this->account->set_value2("Sales Discount", $total_discount, "cr", $acc_code,$condition);
	}    
	$this->account->set_value2("Sales Return - ". $this->max_no, $total_amount, "cr", $_POST['customer'],$condition);

  $srn_amount = $_POST['net_amount']+$_POST['discount']-$_POST['addi_amount'] ; //-$_POST['n_vat_amount'];
//var_dump($total_amount." gfdg ".$srn_amount);
  $acc_code=$this->utility->get_default_acc('SALES_RETURN');
  $this->account->set_value2($des, $srn_amount, "dr", $acc_code,$condition);
/*
  if($_POST['n_vat_amount']>0 ){
    $vat_rec=$this->utility->get_default_acc('VAT_PAYBLE'); 
    $this->account->set_value2($tp_des."SALES_RETURN - ".$this->max_no , $_POST['n_vat_amount'], "dr", $vat_rec,$condition);
  }
*/
  for ($x = 0; $x < 25; $x++){
  	if (isset($_POST['000_' . $x]) && isset($_POST['222_' . $x])){
  		if (!empty($_POST['000_' . $x]) && !empty($_POST['222_' . $x])) {
  			$sql="SELECT * FROM r_additional_item WHERE code='".$_POST['000_' . $x]."'";
  			$query=$this->db->query($sql);
  			$con = $query->first_row()->is_add;
  			$dess= $query->first_row()->description;
  			$acc_code = $query->first_row()->account;
  			$is_reverse= $query->first_row()->is_reverse;
  			if($is_reverse!="0"){
  				if($con == "1") {
  					$this->account->set_value2($dess, $_POST['222_' . $x], "dr", $acc_code,$condition);
  				}elseif ($con == "0") {
  					$this->account->set_value2($dess, $_POST['222_' . $x], "cr", $acc_code,$condition);
  				}
  			}else{
  				if($con == "1") {
  					$this->account->set_value2("Sales Return Additional Chargers- ". $this->max_no, $_POST['222_' . $x], "dr", $_POST['customer'],$condition);
  				}else{
  					$this->account->set_value2("Sales Return Additional Chargers- ". $this->max_no, $_POST['222_' . $x], "cr", $_POST['customer'],$condition);
  				}
  			}
  		}
  	}
  }

  $total_item_cost=0;
  for($x=0;$x<25;$x++){
  	if(isset($_POST['0_' . $x])){
  		$total_item_cost=$total_item_cost+(($_POST['1_' . $x])*(double)$this->utility->get_cost_batch_price($_POST['0_'.$x],$_POST['bt_'.$x]));
  	}
  }    
  $acc_code=$this->utility->get_default_acc('STOCK_ACC');
  $this->account->set_value2($des, $total_item_cost, "dr", $acc_code,$condition);

  $acc_code=$this->utility->get_default_acc('COST_OF_SALES');
  $this->account->set_value2($des, $total_item_cost, "cr", $acc_code,$condition);



  if($condition==0){
  	$query = $this->db->query("
  		SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
  		FROM `t_check_double_entry` t
  		LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
  		WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='8'  AND t.`trans_no` ='" . $this->max_no . "' AND 
  		a.`is_control_acc`='0'");

  	if($query->row()->ok == "0") {
  		$this->db->where("trans_no", $this->max_no);
  		$this->db->where("trans_code",8);
  		$this->db->where("cl", $this->sd['cl']);
  		$this->db->where("bc", $this->sd['branch']);
  		$this->db->delete("t_check_double_entry");

  		return "0";
  	} else {
  		return "1";
  	}
  }
}

public function save_serial(){

	if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
		for ($x = 0; $x < 25; $x++) {
			if (isset($_POST['0_' . $x])) {
				if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
					if ($this->check_is_serial_items($_POST['0_'.$x]) == 1) {
						$serial = $_POST['all_serial_'.$x];
						$p=explode(",",$serial);
						for($i=0; $i<count($p); $i++){
							if ($_POST['hid'] == "0" || $_POST['hid'] == "") {

								$this->db->query("INSERT INTO t_serial_movement SELECT * FROM t_serial_movement_out WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."' ");  

								$t_serial_movement= array(
									"cl" => $this->sd['cl'],
									"bc" => $this->sd['branch'],
									"trans_type" => 8,
									"trans_no" => $this->max_no,
									"item" => $_POST['0_'.$x],
									"batch_no" => $_POST['bt_'.$x],
									"serial_no" => $p[$i],
									"qty_in" => 1,
									"qty_out" => 0,
									"cost" => $_POST['2_'.$x],
									"store_code" => $_POST['stores'],
									"computer" => $this->input->ip_address(),
									"oc" => $this->sd['oc'],
								);

								if(isset($t_serial_movement)) {
									$this->db->insert("t_serial_movement", $t_serial_movement);
								}

								$t_serial = array(
                    // "other_no1" => isset($p[2])?$p[2]:'',
                    // "other_no2" => isset($p[3])?$p[3]:'',
									"out_doc" => "",
									"out_no" => "",
									"out_date" => date("Y-m-d", time()),
									"available" => '1',
									"store_code" => $_POST['stores']
								);

								$this->db->where('cl',$this->sd['cl']);
								$this->db->where('bc',$this->sd['branch']);
								$this->db->where("item", $_POST['0_'.$x]);
								$this->db->where("serial_no", $p[$i]);
								$this->db->update("t_serial", $t_serial);


								$this->db->where('cl',$this->sd['cl']);
								$this->db->where('bc',$this->sd['branch']);
								$this->db->where("item", $_POST['0_'.$x]);
								$this->db->where("serial_no", $p[$i]);
								$this->db->delete("t_serial_movement_out");
							}  
						}
					}
				}        
			}
		}    
	}else{

		for ($x = 0; $x < 25; $x++) {
			if (isset($_POST['0_' . $x])) {
				if ($_POST['0_' . $x] != "" || !empty($_POST['0_' . $x])) {
					if ($this->check_is_serial_items($_POST['0_'.$x]) == 1) {
						$serial = $_POST['all_serial_'.$x];
						$p=explode(",",$serial);
						for($i=0; $i<count($p); $i++){

							$this->db->query("INSERT INTO t_serial_movement SELECT * FROM t_serial_movement_out WHERE item='".$_POST['0_'.$x]."' AND serial_no='".$p[$i]."' ");  

							$t_serial_movement= array(
								"cl" => $this->sd['cl'],
								"bc" => $this->sd['branch'],
								"trans_type" => 8,
								"trans_no" => $this->max_no,
								"item" => $_POST['0_'.$x],
								"batch_no" => $_POST['bt_'.$x],
								"serial_no" => $p[$i],
								"qty_in" => 1,
								"qty_out" => 0,
								"cost" => $_POST['2_'.$x],
								"store_code" => $_POST['stores'],
								"computer" => $this->input->ip_address(),
								"oc" => $this->sd['oc'],
							);

							if(isset($t_serial_movement)) {
								$this->db->insert("t_serial_movement", $t_serial_movement);
							}

							$t_serial = array(
                    // "other_no1" => isset($p[2])?$p[2]:'',
                    // "other_no2" => isset($p[3])?$p[3]:'',
								"out_doc" => "",
								"out_no" => "",
								"out_date" => date("Y-m-d", time()),
								"available" => '1',
								"store_code" => $_POST['stores']
							);

							$this->db->where('cl',$this->sd['cl']);
							$this->db->where('bc',$this->sd['branch']);
							$this->db->where("item", $_POST['0_'.$x]);
							$this->db->where("serial_no", $p[$i]);
							$this->db->update("t_serial", $t_serial);


							$this->db->where('cl',$this->sd['cl']);
							$this->db->where('bc',$this->sd['branch']);
							$this->db->where("item", $_POST['0_'.$x]);
							$this->db->where("serial_no", $p[$i]);
							$this->db->delete("t_serial_movement_out");

						}
					}
				}        
			}
		}   
	}
}


public function check_code() {
	$this->db->where('code', $_POST['code']);
	$this->db->limit(1);
	echo $this->db->get($this->mtb)->num_rows;
}

private function set_delete() {
	$this->db->where("nno",$this->max_no);
	$this->db->where("cl", $this->sd['cl']);
	$this->db->where("bc", $this->sd['branch']);
	$this->db->delete("t_sales_return_det");

	$this->db->where("nno",$this->max_no);
	$this->db->where("cl", $this->sd['cl']);
	$this->db->where("bc", $this->sd['branch']);
	$this->db->delete("t_srn_ret_additional_item");

	$this->load->model('trans_settlement');
	$this->trans_settlement->delete_item_movement('t_item_movement',8,$this->max_no);

	$this->db->where("bc", $this->sd['branch']);
	$this->db->where("cl", $this->sd['cl']);
	$this->db->where("trans_no", $this->max_no);
	$this->db->where("trans_code", 8);
	$this->db->delete("t_item_movement_sub");

      /*$this->db->where("bc", $this->sd['branch']);
      $this->db->where("cl", $this->sd['cl']);
      $this->db->where("nno", $this->max_no);
      $this->db->delete("t_credit_note");*/
  }

  public function load() {
  	$this->db->select(array(
  		't_sales_return_sum.nno',
  		't_sales_return_sum.ddate',
  		't_sales_return_sum.ref_no',
  		't_sales_return_sum.cus_id',
  		't_sales_return_sum.inv_no',
  		't_sales_return_sum.memo',
  		't_sales_return_sum.store',
  		't_sales_return_sum.sales_type',
  		't_sales_return_sum.rep',
  		't_sales_return_sum.gross_amount',
  		't_sales_return_sum.discount',
  		't_sales_return_sum.net_amount',
  		't_sales_return_sum.crn_no',
  		't_sales_return_sum.is_cancel',
  		't_sales_return_sum.return_type',
  		't_sales_return_sum.is_approve', 
  		'm_customer.name as customer_name',
  		'm_employee.name as rep_name',
  		't_sales_return_sum.addi_add',
  		't_sales_return_sum.addi_deduct'  
  	));

  	$this->db->from('t_sales_return_sum');
  	$this->db->join('m_customer', 'm_customer.code=t_sales_return_sum.cus_id');
  	$this->db->join('m_employee', 'm_employee.code=t_sales_return_sum.rep');
  	$this->db->join('m_stores', 'm_stores.code=t_sales_return_sum.store');

  	$this->db->where('t_sales_return_sum.cl', $this->sd['cl']);
  	$this->db->where('t_sales_return_sum.bc', $this->sd['branch']);
  	$this->db->where('t_sales_return_sum.nno', $_POST['id']);

  	$query = $this->db->get();
  	$x = 0;
  	$app = 0;  

  	if ($query->num_rows() > 0) {
  		$a['sum'] = $query->result();
  		$app= $query->row()->is_approve;
  	} else {
  		$x = 2;
  	}

  	$this->db->select(array(
  		't_sales_return_det.code',
  		't_sales_return_det.qty',
  		't_sales_return_det.discountp',
  		't_sales_return_det.discount',
  		't_sales_return_det.batch_no',
  		't_sales_return_det.price',
  		't_sales_return_det.is_free',
  		't_sales_return_det.amount',
  		'r_return_reason.description',
  		't_sales_return_det.reason',
  		'm_item.description as item_des'
  	));

  	$this->db->from('t_sales_return_det');
  	$this->db->join('m_item', 'm_item.code=t_sales_return_det.code');
  	$this->db->join('r_return_reason','r_return_reason.code=t_sales_return_det.reason');
  	$this->db->where('t_sales_return_det.cl', $this->sd['cl']);
  	$this->db->where('t_sales_return_det.bc', $this->sd['branch']);
  	$this->db->where('t_sales_return_det.nno', $_POST['id']);
      //$this->db->where('r_return_reason.type', 2);
  	$this->db->order_by('t_sales_return_det.auto_num', 'asc');
  	$query = $this->db->get();

  	if($query->num_rows() > 0){
  		$a['det'] = $query->result();
  	} else {
  		$x = 2;
  	}

  	$this->db->select(array(
  		't_srn_ret_additional_item.type',
  		't_srn_ret_additional_item.rate_p',
  		't_srn_ret_additional_item.amount',
  		'r_additional_item.description',
  		'r_additional_item.is_add'
  	));

  	$this->db->from('t_srn_ret_additional_item');
  	$this->db->join('r_additional_item', 'r_additional_item.code=t_srn_ret_additional_item.type');
  	$this->db->where('t_srn_ret_additional_item.cl', $this->sd['cl']);
  	$this->db->where('t_srn_ret_additional_item.bc', $this->sd['branch']);
  	$this->db->where('t_srn_ret_additional_item.nno', $_POST['id']);
  	$query = $this->db->get();

  	if ($query->num_rows() > 0) {
  		$a['add'] = $query->result();
  	} else {
  		$a['add'] = 2;
  	}


  	if($app!=0){
  		$this->db->select(array('t_serial_movement.item', 't_serial_movement.serial_no'));
  		$this->db->from('t_serial_movement');
  		$this->db->where('t_serial_movement.trans_no', $_POST['id']);
  		$this->db->where('t_serial_movement.trans_type', 8);
  		$this->db->where('t_serial_movement.cl', $this->sd['cl']);
  		$this->db->where('t_serial_movement.bc', $this->sd['branch']);
  		$this->db->group_by("t_serial_movement.serial_no");

  		$query = $this->db->get();
  	}else{
  		$sql="SELECT item_code AS item , serial_no, '' AS other_no1 , '' AS other_no2
  		FROM t_serial_trans
  		WHERE cl='".$this->sd['cl']."' 
  		AND bc='".$this->sd['branch']."' 
  		AND trans_code='8' 
  		AND trans_no='".$_POST['id']."'
  		GROUP BY item_code,serial_no";
  		$query=$this->db->query($sql);
  	}


  	if ($query->num_rows() > 0) {
  		$a['serial'] = $query->result();
  	}else{
  		$a['serial'] = 2;
  	}

  	if ($x == 0) {
  		echo json_encode($a);
  	} else {
  		echo json_encode($x);
  	}
  }

  public function delete() {
  	$this->db->trans_begin();
  	error_reporting(E_ALL); 
  	function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
  		throw new Exception($errMsg."==".$errFile."==".$errLine); 
  	}
  	set_error_handler('exceptionThrower'); 
  	try { 

  		if($this->user_permissions->is_delete('t_sales_return_sum') || $this->user_permissions->is_delete('t_sales_return_sum_without_invoice')){

  			$bc=$this->sd['branch'];
  			$cl=$this->sd['cl'];
  			$trans_no=$_POST['trans_no'];
  			$status=$this->trans_cancellation->sales_return_update_status();     

  			if($status=="OK"){

  				$this->db->where('cl',$this->sd['cl']);
  				$this->db->where('bc',$this->sd['branch']);
  				$this->db->where('trans_code','8');
  				$this->db->where('trans_no',$trans_no);
  				$this->db->delete('t_account_trans');

  				$this->load->model('trans_settlement');
  				$this->trans_settlement->delete_item_movement('t_item_movement',8,$trans_no);

  				$this->db->where('cl',$cl);
  				$this->db->where('bc',$bc);
  				$this->db->where('trans_code','8');
  				$this->db->where('trans_no',$trans_no);
  				$this->db->delete('t_item_movement_sub');

  				$this->db->select(array('inv_no','sales_type'));
  				$this->db->where('cl',$this->sd['cl']);
  				$this->db->where('bc',$this->sd['branch']);
  				$this->db->where('nno',$trans_no);
  				$inv_no=$this->db->get('t_sales_return_sum')->row()->inv_no;
  				$sales_type=$this->db->get('t_sales_return_sum')->row()->sales_type;

  				$this->db->select(array('item','serial_no'));
  				$this->db->where('cl',$this->sd['cl']);
  				$this->db->where('bc',$this->sd['branch']);
  				$this->db->where('trans_type',8);
  				$this->db->where('trans_no',$trans_no);
  				$query=$this->db->get('t_serial_movement');

  				$t_serial = array(
  					"out_doc" => $sales_type,
  					"out_no" => $inv_no,
  					"out_date" => date("Y-m-d", time()),
  					"available" => '0'
  				);


  				foreach($query->result() as $row){
  					$this->db->query("INSERT INTO t_serial_movement_out SELECT * FROM t_serial_movement WHERE item='$row->item' AND serial_no='$row->serial_no'");  

  					$this->db->where('cl',$this->sd['cl']);
  					$this->db->where('bc',$this->sd['branch']);
  					$this->db->where("item", $row->item);
  					$this->db->where("serial_no", $row->serial_no);
  					$this->db->update("t_serial", $t_serial);

  					$this->db->where('cl',$this->sd['cl']);
  					$this->db->where('bc',$this->sd['branch']);
  					$this->db->where("item", $row->item);
  					$this->db->where("serial_no", $row->serial_no);
  					$this->db->delete("t_serial_movement");
  				}

  				$this->db->where('cl',$this->sd['cl']);
  				$this->db->where('bc',$this->sd['branch']);
  				$this->db->where('trans_type',8);
  				$this->db->where('trans_no',$trans_no);
  				$this->db->delete("t_serial_movement_out");


  				$data=array('is_cancel'=>'1');
  				$this->db->where('cl',$this->sd['cl']);
  				$this->db->where('bc',$this->sd['branch']);
  				$this->db->where('nno',$trans_no);
  				$this->db->update('t_sales_return_sum',$data);

  				$this->db->where("trans_no", $trans_no);
  				$this->db->where("trans_code", 8);
  				$this->db->where("cl", $this->sd['cl']);
  				$this->db->where("bc", $this->sd['branch']);
  				$this->db->delete("t_serial_trans");


  				$sql="UPDATE t_credit_note SET is_cancel='1' WHERE cl='$cl' AND bc='$bc' AND  nno IN (SELECT crn_no FROM t_sales_return_sum WHERE  cl='$cl' AND bc='$bc' AND nno='$trans_no')";
  				$this->db->query($sql);

  				$sql="DELETE  FROM t_credit_note_trans  WHERE cl='$cl' AND bc='$bc' AND  trans_no IN (SELECT crn_no FROM t_sales_return_sum WHERE  cl='$cl' AND bc='$bc' AND nno='$trans_no')";
  				$this->db->query($sql);

  				$sql="SELECT cus_id FROM t_sales_return_sum WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND nno='".$_POST['trans_no']."'";
  				$cus_id=$this->db->query($sql)->first_row()->cus_id;
  				$this->utility->update_credit_note_balance($cus_id);

  				$this->utility->save_logger("CANCEL",8,$trans_no,$this->mod);

  				echo $this->db->trans_commit();
  			}else{
  				echo $status;
  				$this->db->trans_commit();
  			}
  		}else{

  			$this->db->trans_commit();
  			echo "No permission to delete records";
  		}

  	}catch(Exception $e){ 
  		$this->db->trans_rollback();
  		echo $e->getMessage()."Operation fail please contact admin"; 
  	}
  }

  public function select() {
  	$query = $this->db->get($this->mtb);
  	$s = "<select name='sales_ref' id='sales_ref'>";
  	$s .= "<option value='0'>---</option>";
  	foreach ($query->result() as $r) {
  		$s .= "<option title='" . $r->name . "' value='" . $r->code . "'>" . $r->code . " | " . $r->name . "</option>";
  	}
  	$s .= "</select>";
  	return $s;
  }


  public function item_list_all() {
  	if ($_POST['search'] == 'Key Word: code, name') {
  		$_POST['search'] = "";
  	}
  	$sql = "SELECT * FROM m_item  WHERE (description LIKE '%$_POST[search]%' OR code LIKE '%$_POST[search]%') AND inactive='0' LIMIT 25";
  	$query = $this->db->query($sql);
  	$a = "<table id='item_list' style='width : 100%' >";
  	$a .= "<thead><tr>";
  	$a .= "<th class='tb_head_th'>Code</th>";
  	$a .= "<th class='tb_head_th'>Item Name</th>";
  	$a .= "<th class='tb_head_th'>Price</th>";
  	$a .= "</thead></tr>";
  	$a .= "<tr class='cl'>";
  	$a .= "<td>&nbsp;</td>";
  	$a .= "<td>&nbsp;</td>";
  	$a .= "<td>&nbsp;</td>";
  	$a .= "</tr>";

  	foreach ($query->result() as $r) {
  		$a .= "<tr class='cl'>";
  		$a .= "<td>" . $r->code . "</td>";
  		$a .= "<td>" . $r->description . "</td>";
  		$a .= "<td>" . $r->max_price . "</td>";
  		$a .= "</tr>";
  	}
  	$a .= "</table>";
  	echo $a;
  }

  public function get_item() {
  	$this->db->select(array('code', 'description', 'max_price'));
  	$this->db->where("code", $this->input->post('code'));
  	$this->db->limit(1);
  	$query = $this->db->get('m_item');
  	if ($query->num_rows() > 0) {
  		$data['a'] = $query->result();
  	} else {
  		$data['a'] = 2;
  	}

  	echo json_encode($data);
  }

  public function check_invoice_no() {
  	$type=$_POST['type'];
  	if($type=='4'){
  		$tbl="t_cash_sales_sum";
  	}else{
  		$tbl="t_credit_sales_sum";
  	}

  	$this->db->where("cl", $this->sd['cl']);
  	$this->db->where("bc", $this->sd['branch']);
  	$this->db->where("cus_id", $_POST['cus_id']);
  	$this->db->where("type", $_POST['type']);
  	$this->db->where("nno", $_POST['inv_no']);
  	echo $this->db->get($tbl)->num_rows();
  }

  public function get_crn_no() {
  	$field = "nno";
  	$this->db->select_max($field);
  	$this->db->where("cl", $this->sd['cl']);
  	$this->db->where("bc", $this->sd['branch']);
  	return $this->db->get("t_credit_note")->first_row()->$field + 1;
  }

  public function check_is_serial_items($code) {
  	$this->db->select(array('serial_no'));
  	$this->db->where("code", $code);
  	$this->db->limit(1);
  	return $this->db->get("m_item")->first_row()->serial_no;
  }

  public function get_invoice() {
  	$code = $_POST['code'];
  	$cl=$this->sd['cl'];
  	$bc=$this->sd['branch'];

  	$sql = "SELECT `t_cash_sales_sum`.`cus_id`, 
  	`m_customer`.`name` as cus_name,
  	`t_cash_sales_sum`.`store`,
  	`t_cash_sales_sum`.`rep`,
  	`m_employee`.`name` as rep_name,
  	`t_cash_sales_det`.`code` as iCode,
  	`m_item`.`description` as iName,
  	`t_cash_sales_det`.`qty`,
  	`t_cash_sales_det`.`price`,
  	`t_cash_sales_det`.`discountp`,
  	`t_cash_sales_det`.`discount`,
  	`t_cash_sales_det`.`amount`,
  	`t_cash_sales_det`.`return_qty`,
  	`t_cash_sales_det`.`batch_no`,
  	`t_cash_sales_det`.`is_free`

  	FROM `t_cash_sales_sum` JOIN `t_cash_sales_det` ON `t_cash_sales_sum`.`nno` = `t_cash_sales_det`.`nno` 
  	AND `t_cash_sales_sum`.`cl` = `t_cash_sales_det`.`cl`
  	AND `t_cash_sales_sum`.`bc` = `t_cash_sales_det`.`bc`
  	JOIN `m_customer` ON `t_cash_sales_sum`.`cus_id` = `m_customer`.`code` 
  	JOIN `m_employee` ON `t_cash_sales_sum`.`rep` = `m_employee`.`code` 
  	JOIN `m_item` ON `t_cash_sales_det`.`code` = `m_item`.`code` 
  	WHERE `t_cash_sales_sum`.`nno` = '$code' AND `t_cash_sales_sum`.`cl` = '$cl'  AND `t_cash_sales_sum`.`bc` = '$bc'
  	LIMIT 25 ";

  	$query = $this->db->query($sql);
  	if ($query->num_rows() > 0) {
  		$a['det'] = $query->result();
  	} else {
  		$a['det'] = 2;
  	}

  	$this->db->select(array('t_serial.item', 't_serial.serial_no','t_serial.other_no1','t_serial.other_no2'));
  	$this->db->from('t_serial');
  	$this->db->join('t_cash_sales_sum', 't_serial.out_no=t_cash_sales_sum.nno');
  	$this->db->where('t_serial.out_doc', 4);
  	$this->db->where('t_serial.out_no', "$code");
  	$this->db->where('t_cash_sales_sum.cl', $cl);
  	$this->db->where('t_cash_sales_sum.bc', $bc);
  	$query1 = $this->db->get();

  	if ($query1->num_rows() > 0) {
  		$a['serial'] = $query1->result();
  	} else {
  		$a['serial'] = 2;
  	}

  	$sql_add="SELECT  i.type,
  	a.description,
  	i.`rate_p`,
  	i.`amount`,
  	a.is_add 
  	FROM `t_cash_sales_additional_item` i
  	JOIN r_additional_item a ON a.code = i.`type`
  	where i.cl='".$this->sd['cl']."' and i.bc='".$this->sd['branch']."' and i.nno='$code' 
  	GROUP BY i.cl,i.bc,i.`nno`,i.`type`";
  	$query_add = $this->db->query($sql_add);
  	if($query_add->num_rows()>0){
  		$a['addi']=$query_add->result();
  	}else{
  		$a['addi']=2;
  	}

  	$sql2 = "SELECT sum(`t_cash_sales_det`.`qty` - `t_cash_sales_det`.`return_qty`) as return_qty
  	FROM `t_cash_sales_det` 
  	WHERE `t_cash_sales_det`.`nno` = '$code' AND `t_cash_sales_det`.`cl` = '$cl'  AND `t_cash_sales_det`.`bc` = '$bc'
  	GROUP BY `t_cash_sales_det`.code
  	LIMIT 25 ";

  	$query2 = $this->db->query($sql2);

  	if ($query2->num_rows() > 0) {
  		$a['max'] = $query2->result();
  	} else {
  		$a['max'] = 2;
  	}
  	$a['status']=1; 

  	$check_sale_is_cancel=$this->validation->sale_is_cancel($_POST['code'],$_POST['type']);

  	if($check_sale_is_cancel == 1) {
  		$a['status']=1; 
  	}else{
  		$a['status']=$check_sale_is_cancel;
  	}  
  	echo json_encode($a);
  }

  public function get_invoice1() {
  	$code = $_POST['code'];
  	$sql = "SELECT `t_credit_sales_sum`.`cus_id`, 
  	`m_customer`.`name` AS cus_name,
  	`t_credit_sales_sum`.`rep`,
  	`t_credit_sales_sum`.`store`,
  	`m_employee`.`name` AS rep_name,
  	`t_credit_sales_det`.`code` AS iCode,
  	`m_item`.`description` AS iName,
  	`t_credit_sales_det`.`qty`, 
  	`t_credit_sales_det`.`price`,
  	`t_credit_sales_det`.`discountp`,
  	`t_credit_sales_det`.`discount`, 
  	`t_credit_sales_det`.`amount`,
  	`t_credit_sales_det`.`return_qty`,
  	`t_credit_sales_det`.`batch_no`,
  	`t_credit_sales_det`.`is_free`


  	FROM `t_credit_sales_sum` 
  	LEFT JOIN `t_credit_sales_det` ON `t_credit_sales_sum`.`nno` = `t_credit_sales_det`.`nno` 
  	AND `t_credit_sales_sum`.`cl` = `t_credit_sales_det`.`cl` 
  	AND `t_credit_sales_sum`.`bc` = `t_credit_sales_det`.`bc` 
  	LEFT JOIN `m_customer` ON `t_credit_sales_sum`.`cus_id` = `m_customer`.`code` 
  	LEFT JOIN `m_employee` ON `t_credit_sales_sum`.`rep` = `m_employee`.`code` 
  	LEFT JOIN `m_item` ON `t_credit_sales_det`.`code` = `m_item`.`code`
  	WHERE `t_credit_sales_sum`.`nno` = '$code' AND `t_credit_sales_sum`.`cl` = '".$this->sd['cl']."'  AND `t_credit_sales_sum`.`bc` = '".$this->sd['branch']."'
  	LIMIT 25 ";

  	$query = $this->db->query($sql);
  	if ($query->num_rows() > 0) {
  		$a['det'] = $query->result();
  	} else {
  		$a['det'] = 2;
  	}

  	$this->db->select(array('t_serial.item', 't_serial.serial_no'));
  	$this->db->from('t_serial');
  	$this->db->join('t_credit_sales_sum', 't_serial.out_no=t_credit_sales_sum.nno');
  	$this->db->where('t_serial.out_doc', 5);
  	$this->db->where('t_serial.out_no', "$code");
  	$this->db->where('t_credit_sales_sum.cl', $this->sd['cl']);
  	$this->db->where('t_credit_sales_sum.bc', $this->sd['branch']);
  	$query1 = $this->db->get();

  	if ($query1->num_rows() > 0) {
  		$a['serial'] = $query1->result();
  	} else {
  		$a['serial'] = 2;
  	}

  	$sql_add="SELECT  i.type,
  	a.description,
  	i.`rate_p`,
  	i.`amount`,
  	a.is_add 
  	FROM `t_credit_sales_additional_item` i
  	JOIN r_additional_item a ON a.code = i.`type`
  	where i.cl='".$this->sd['cl']."' and i.bc='".$this->sd['branch']."' and i.nno='$code' 
  	GROUP BY i.cl,i.bc,i.`nno`,i.`type`";
  	$query_add = $this->db->query($sql_add);
  	if($query_add->num_rows()>0){
  		$a['addi']=$query_add->result();
  	}else{
  		$a['addi']=2;
  	}

  	$sql2 = "SELECT sum(`t_credit_sales_det`.`qty` - `t_credit_sales_det`.`return_qty`) as return_qty
  	FROM `t_credit_sales_det` 
  	WHERE `t_credit_sales_det`.`nno` = '$code' AND `t_credit_sales_det`.`cl` = '".$this->sd['cl']."'  AND `t_credit_sales_det`.`bc` = '".$this->sd['branch']."'
  	GROUP BY `t_credit_sales_det`.code
  	ORDER BY auto_num
  	LIMIT 25 ";

  	$query2 = $this->db->query($sql2);

  	if ($query2->num_rows() > 0) {
  		$a['max'] = $query2->result();
  	} else {
  		$a['max'] = 2;
  	}

  	$a['status']=1; 


  	$check_sale_is_cancel=$this->validation->sale_is_cancel($_POST['code'],$_POST['type']);
  	if($check_sale_is_cancel == 1) {
  		$a['status']=1; 
  	}else{
  		$a['status']=$check_sale_is_cancel;
  	}  
  	echo json_encode($a);
  }

  public function PDF_report() {
  	$this->db->select(array('name', 'address', 'tp', 'fax', 'email'));
  	$this->db->where("cl", $this->sd['cl']);
  	$this->db->where("bc", $this->sd['branch']);
  	$r_detail['branch'] = $this->db->get('m_branch')->result();

  	$this->db->select(array('gross_amount', 'net_amount', 'inv_no', 'sales_type','addi_add','addi_deduct','memo'));
  	$this->db->where('t_sales_return_sum.cl', $this->sd['cl']);
  	$this->db->where('t_sales_return_sum.bc', $this->sd['branch']);
  	$this->db->where('t_sales_return_sum.nno', $_POST['qno']);
  	$r_detail['amount'] = $this->db->get('t_sales_return_sum')->result();
  	$inv=$s_type=0;
  	foreach($r_detail['amount'] as $r){
  		$inv    = $r->inv_no;
  		$s_type = $r->sales_type;
  	}

  	$r_detail['duplicate'] = $_POST['is_duplicate'];

  	$invnum= $this->utility->invoice_format($inv);
  	$invoice_number= $this->utility->invoice_format($_POST['qno']);
  	$session_array = array(
  		$this->sd['cl'],
  		$this->sd['branch'],
  		$invoice_number
  	);
  	$r_detail['session'] = $session_array;
  	$r_detail['invnum'] =$invnum;

  	if($s_type=="5"){
  		$r_detail['s_typ'] = "CR";
  	}else if($s_type=="4"){
  		$r_detail['s_typ'] = "CA";
  	}

  	$this->db->where("code", $_POST['sales_type']);
  	$query = $this->db->get('t_trans_code');
  	if ($query->num_rows() > 0) {
  		foreach ($query->result() as $row) {
  			$r_detail['r_type'] = $row->description;
  		}
  	}

  	$r_detail['type'] = $_POST['type'];
  	$r_detail['dt'] = $_POST['dt'];
  	$r_detail['qno'] = $_POST['qno'];
  	$r_detail['memo'] = $_POST['memo'];

  	$r_detail['page'] = $_POST['page'];
  	$r_detail['header'] = $_POST['header'];
  	$r_detail['orientation'] = $_POST['orientation'];

  	$this->db->select(array('nic as code', 'name', 'address1', 'address2', 'address3'));
  	$this->db->where("code", $_POST['cus_id']);
  	$r_detail['customer'] = $this->db->get('m_customer')->result();

  	$this->db->select(array('name'));
  	$this->db->where("code", $_POST['salesp_id']);
  	$query = $this->db->get('m_employee');

  	foreach ($query->result() as $row) {
  		$r_detail['employee'] = $row->name;
  	}

  	$sql="SELECT `t_sales_return_det`.`code`, 
  	`t_sales_return_det`.`qty`, 
  	`t_sales_return_det`.`discount`, 
  	`t_sales_return_det`.`price`, 
  	`t_sales_return_det`.`amount`, 
  	`m_item`.`description`, 
  	`m_item`.`model`,
  	c.`cc` AS sub_item,
  	c.`deee` AS des,
  	c.qty *  t_sales_return_det.qty AS sub_qty
  	FROM (`t_sales_return_det`) 
  	JOIN `m_item` ON `m_item`.`code`=`t_sales_return_det`.`code` 
  	LEFT JOIN `m_item_sub` ON `m_item_sub`.`item_code` = `m_item`.`code` 
  	LEFT JOIN `r_sub_item` ON `r_sub_item`.`code` = `m_item_sub`.`sub_item` 
  	LEFT JOIN (SELECT t_sales_return_det.`code`, 
  	r_sub_item.`description` AS deee, 
  	r_sub_item.`code` AS cc,
  	r_sub_item.`qty` AS qty,
  	t_item_movement_sub.cl,
  	t_item_movement_sub.bc,
  	t_item_movement_sub.item,
  	t_item_movement_sub.`sub_item` 
  	FROM t_item_movement_sub 
  	LEFT JOIN t_sales_return_det ON t_sales_return_det.`code` = t_item_movement_sub.`item`  
  	AND t_sales_return_det.`cl` = t_item_movement_sub.`cl` AND t_sales_return_det.`bc`=t_item_movement_sub.`bc`
  	JOIN r_sub_item ON t_item_movement_sub.`sub_item` = r_sub_item.`code`
  	WHERE t_item_movement_sub.batch_no = t_sales_return_det.`batch_no` AND `t_sales_return_det`.`cl` = '".$this->sd['cl']."' 
  	AND `t_sales_return_det`.`bc` = '".$this->sd['branch']."' AND `t_sales_return_det`.`nno` = '".$_POST['qno']."'  )c ON c.code = t_sales_return_det.`code`
  	WHERE `t_sales_return_det`.`cl` = '".$this->sd['cl']."' 
  	AND `t_sales_return_det`.`bc` = '".$this->sd['branch']."'
  	AND `t_sales_return_det`.`nno` = '".$_POST['qno']."' 
  	GROUP BY c.cc ,t_sales_return_det.`code`
  	ORDER BY `t_sales_return_det`.`auto_num` ASC 
  	";

  	$query = $this->db->query($sql);
  	$r_detail['items'] = $this->db->query($sql)->result();

  	$this->db->SELECT(array('serial_no','item'));
  	$this->db->FROM('t_serial_movement');
  	$this->db->WHERE('t_serial_movement.cl', $this->sd['cl']);
  	$this->db->WHERE('t_serial_movement.bc', $this->sd['branch']);
  	$this->db->WHERE('t_serial_movement.trans_type','8');
  	$this->db->WHERE('t_serial_movement.trans_no',$_POST['qno']);
  	$r_detail['serial'] = $this->db->get()->result();

  	$fee="SELECT sum(price*qty) AS free FROM t_sales_return_det WHERE is_free='1' AND nno = '".$_POST['qno']."'  AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";
  	$r_detail['free_tot']=$this->db->query($fee)->result(); 

  	$this->db->select("discount");
  	$this->db->where('cl', $this->sd['cl']);
  	$this->db->where('bc', $this->sd['branch']);
  	$this->db->where('nno', $_POST['qno']);
  	$r_detail['discount'] = $this->db->get('t_sales_return_sum')->result();

  	$this->db->select(array('gross_amount', 'net_amount','addi_add','addi_deduct'));
  	$this->db->where('t_sales_return_sum.cl', $this->sd['cl']);
  	$this->db->where('t_sales_return_sum.bc', $this->sd['branch']);
  	$this->db->where('t_sales_return_sum.nno', $_POST['qno']);
  	$r_detail['amount'] = $this->db->get('t_sales_return_sum')->result();

  	$this->db->select(array('t_sales_return_sum.oc', 's_users.discription', 'action_date'));
  	$this->db->from('t_sales_return_sum');
  	$this->db->join('s_users', 't_sales_return_sum.oc=s_users.cCode');
  	$this->db->where('t_sales_return_sum.cl', $this->sd['cl']);
  	$this->db->where('t_sales_return_sum.bc', $this->sd['branch']);
  	$this->db->where('t_sales_return_sum.nno', $_POST['qno']);
  	$r_detail['user'] = $this->db->get()->result();

  	$sql="SELECT is_approve FROM t_sales_return_sum
  	WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' 
  	AND nno='". $_POST['qno']."'";
  	$approve=$this->db->query($sql)->row()->is_approve;
        // if($approve==1){
  	$this->load->view($_POST['by'] . '_' . 'pdf', $r_detail);
        // }else{
        //     echo "<script>alert('Transaction Not Approved For Get Print');window.close();</script>";
        // }
  }


  public function get_sales_purchase_price($trans_no,$item,$type){
  	$this->db->select(array('cost'));
  	$this->db->where('cl',$this->sd['cl']);
  	$this->db->where('bc',$this->sd['branch']);
  	$this->db->where('nno',$trans_no);
  	$this->db->where('code',$item);
  	if($type==4){
  		return $this->db->get('t_cash_sales_det')->row()->cost;
  	}else{
  		return $this->db->get('t_credit_sales_det')->row()->cost;
  	}

  }

  public function get_sales_min_price($trans_no,$item,$type){
  	$this->db->select(array('min_price'));
  	$this->db->where('cl',$this->sd['cl']);
  	$this->db->where('bc',$this->sd['branch']);
  	$this->db->where('nno',$trans_no);
  	$this->db->where('code',$item);

  	if($type==4){
  		return $this->db->get('t_cash_sales_det')->row()->min_price;
  	}else{
  		return $this->db->get('t_credit_sales_det')->row()->min_price;
  	}
  }

  public function get_free_item(){
  	$item=$_POST["item_code"];
  	$buy_date=$_POST["b_date"];

  	$sql_select="SELECT 
  	MIFD.`item`
  	FROM m_item_free AS MIF
  	JOIN m_item_free_det AS MIFD 
  	ON (MIF.`nno`=MIFD.`nno`) 
  	WHERE `code`='$item' AND `date_from`<='$buy_date' AND `date_to`>='$buy_date';";


  	$query = $this->db->query($sql_select);
  	$det['free'] = $query->result();

  	echo json_encode($det);
  }  
}