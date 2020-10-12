<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_internal_trans_receipt extends CI_Model {

	private $sd;
	private $mtb;
	private $max_no;
	private $mod = '003';

	public $interest=Array();

	function __construct(){
		parent::__construct();

		$this->sd = $this->session->all_userdata();
		$this->load->database($this->sd['db'], true);
		$this->load->model("t_payment_option");
		$this->load->model('user_permissions');

	}

	public function base_details(){
		$this->load->model("utility");
		$a['max_no']=$this->utility->get_max_no("t_int_trans_receipt_sum","nno");
		$a['type']='TRANSFER_RECEIPT'; 
		return $a;
	}

	public function PDF_report(){
		$this->db->select(array('name','address','tp','fax','email'));
		$this->db->where("cl",$this->sd['cl']);
		$this->db->where("bc",$this->sd['branch']);
		$r_detail['branch']=$this->db->get('m_branch')->result();

		$invoice_number= $this->utility->invoice_format($_POST['qno']);
		$session_array = array(
			$this->sd['cl'],
			$this->sd['branch'],
			$invoice_number
		);
		$r_detail['session'] = $session_array;
		$r_detail['duplicate'] = $_POST['is_duplicate'];

		$this->db->where("code",$_POST['sales_type']);
		$query= $this->db->get('t_trans_code'); 
		if ($query->num_rows() > 0){
			foreach ($query->result() as $row){
				$r_detail['r_type']= $row->description;       
			}
		} 

		$sql="SELECT s.* , a.description FROM t_int_trans_receipt_sum s
		JOIN m_account a ON a.code = s.acc_code
		WHERE cl='".$this->sd['cl']."'
		AND bc='".$this->sd['branch']."'
		AND nno='".$_POST['qno']."'";
		$query=$this->db->query($sql);
		$r_detail['items']=$query->result();

		$operator = $query->row()->oc;
		$payment  = $query->row()->payment;
		$date     = $query->row()->ddate;
		$acc      = $query->row()->acc_code;
		$acc_name = $query->row()->description;
		$bal      = (float)$query->row()->balance -  (float)$query->row()->payment;

		$sql_chq="SELECT cheque_date,
		m_bank.description AS bank,
		m_bank_branch.description AS branch,
		account_no,
		cheque_no,
		opt_receive_cheque_det.amount 
		FROM opt_receive_cheque_det 
		JOIN m_bank ON m_bank.code = opt_receive_cheque_det.`bank` 
		JOIN m_bank_branch ON m_bank_branch.code = opt_receive_cheque_det.`branch` 
		WHERE trans_code = '108' 
		AND trans_no = '".$_POST['qno']."' 
		AND opt_receive_cheque_det.cl = '".$this->sd['cl']."' 
		AND opt_receive_cheque_det.bc = '".$this->sd['branch']."'";
		$query = $this->db->query($sql_chq);
		$r_detail['cheque'] = $this->db->query($sql_chq)->result();


		$r_detail['dt']=$date;
		$r_detail['qno']=$_POST['qno'];
		$r_detail['num']=$payment;
		$r_detail['acc_name']=$acc_name;
		$r_detail['acc_id']=$acc;
		$r_detail['bal']=$bal;


		$num =$payment;
		$this->utility->num_in_letter($num);
		$r_detail['rec']=convertNum($num);;
		$r_detail['page']=$_POST['page'];
		$r_detail['header']=$_POST['header'];
		$r_detail['orientation']=$_POST['orientation'];

		$this->db->select(array('loginName'));
		$this->db->where('cCode',$operator);
		$r_detail['user']=$this->db->get('s_users')->result();

		$this->load->view($_POST['by'].'_'.'pdf',$r_detail);
	}

	public function validation(){
		$status=1;

		$this->max_no=$this->utility->get_max_no("t_int_trans_receipt_sum","nno");

		$check_is_delete=$this->validation->check_is_cancel($this->max_no,'t_int_trans_receipt_sum');
		if($check_is_delete!=1){
			return "Receipt already deleted";
		}

		$payment_option_validation = $this->validation->payment_option_calculation();                                             
		if($payment_option_validation != 1){
			return $payment_option_validation;
		}

		$check_zero_value=$this->validation->empty_net_value($_POST['net_val']);
		if($check_zero_value!=1){
			return $check_zero_value;
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
				$_POST['type']=108;
				$_POST['acc_codes']=$_POST['acc_code'];   

				$t_int_trans_receipt_sum=array(
					"cl"                 =>$this->sd['cl'],
					"bc"                 =>$this->sd['branch'],
					"nno"                =>$this->max_no,
					"ddate"              =>$_POST['date'],
					"ref_no"             =>$_POST['ref_no'],
					"acc_code"           =>$_POST['acc_code'],
					"payment"            =>$_POST['net'],
					"memo"               =>$_POST['memo'],
					"pay_cash"           =>$_POST['hid_cash'],
					"pay_issue_chq"      =>$_POST['hid_cheque_issue'],
					"pay_receive_chq"    =>$_POST['hid_cheque_recieve'],
					"pay_ccard"          =>$_POST['hid_credit_card'],
					"pay_cnote"          =>$_POST['hid_credit_note'],
					"pay_dnote"          =>$_POST['hid_debit_note'],
					"pay_bank_debit"     =>$_POST['hid_bank_debit'],
					"pay_discount"       =>$_POST['hid_discount'],
					"pay_advance"        =>$_POST['hid_advance'],
					"pay_credit"         =>$_POST['hid_credit'],
					"oc"                 =>$this->sd['oc'],
					"settle_balance"     =>$_POST['balance2'],
					"settle_amount"      =>$_POST['net_val'],
					"balance"            =>$_POST['balance'],
					"receipt_balance"    =>$_POST['balance2'],
				);

				for($x = 0; $x<100; $x++){
					if(isset($_POST['bc0_'.$x],$_POST['4_'.$x],$_POST['5_'.$x],$_POST['6_'.$x])){
						if($_POST['bc0_'.$x] != "" && $_POST['4_'.$x] != "" && $_POST['5_'.$x] != "" && $_POST['6_'.$x]!= ""){
							$t_int_trans_receipt_sum_temp[]=array(
								"cl"          =>$this->sd['cl'],
								"bc"          =>$this->sd['branch'],
								"to_cl"       =>$_POST['refcl_'.$x],
								"to_bc"       =>$_POST['refbc_'.$x],
								"nno"         =>$this->max_no,
								"trans_code"  =>$_POST['trans_code'.$x],
								"trans_no"    =>$_POST['2_'.$x],
								"sub_no"      =>$_POST['2r_'.$x],
								"date"        =>$_POST['3_'.$x],
								"description" =>$_POST['descrip_'.$x],
								"amount"      =>$_POST['4_'.$x],
								"balance"     =>$_POST['5_'.$x],
								"payment"     =>$_POST['6_'.$x],
								"order_num"   =>$x,
							); 
						}
					}
				}

				if($_POST['hid'] == "0" || $_POST['hid'] == ""){
					if($this->user_permissions->is_add('t_internal_trans_receipt')){
						$account_update=$this->account_update(0);
						if($account_update==1){
							$this->db->insert('t_int_trans_receipt_sum',  $t_int_trans_receipt_sum);
							if(count($t_int_trans_receipt_sum_temp)){$this->db->insert_batch("t_int_trans_receipt_det",$t_int_trans_receipt_sum_temp);}

							$this->load->model('trans_settlement');
							for($x = 0; $x<100; $x++){
								if(isset($_POST['bc0_'.$x],$_POST['4_'.$x],$_POST['5_'.$x],$_POST['6_'.$x])){
									if($_POST['bc0_'.$x] != "" && $_POST['4_'.$x] != "" && $_POST['5_'.$x] != "" && $_POST['6_'.$x]!= ""){  

										if($_POST['trans_code'.$x]=="42"){
											$this->trans_settlement->save_iternal_transfer_trans("t_internal_transfer_trans", 
												$_POST['cl0_'.$x],
												$_POST['bc0_'.$x],
												$_POST['refcl_'.$x],
												$_POST['refbc_'.$x],
												$_POST['acc_code'], 
												$_POST['3_'.$x], 
												$_POST['trans_code'.$x], 
												$_POST['2_'.$x],  
												108, 
												$this->max_no, 
												"0", 
												$_POST['6_'.$x],
												0,0);
										}else if($_POST['trans_code'.$x]=="18"){
											$this->trans_settlement->save_settlement_multi("t_debit_note_trans",
												$_POST['acc_code'],
												$_POST['date'],
												$_POST['trans_code'.$x],
												$_POST['2_'.$x],
												108,
												$this->max_no,
												"0",
												$_POST['6_'.$x],
												$_POST['cl0_'.$x],
												$_POST['bc0_'.$x]);   
										}   
									}
								}
							}

							/* customer over payment save */
      // $get_balance=(double)$_POST['balance2'];
      // if($get_balance>0){
      //   $this->trans_settlement->save_settlement("t_cus_settlement",$_POST['customer'],$_POST['date'],16,$this->max_no,16,$this->max_no,$get_balance,"0");  
      // }

							$this->account_update(1);

							$this->load->model('t_payment_option');
							$this->t_payment_option->save_payment_option($this->max_no,108);

							$this->utility->save_logger("SAVE",108,$this->max_no,$this->mod);
							echo $this->db->trans_commit()."@".$this->max_no;
						}else{
							echo "Invalid account entries";
							$this->db->trans_commit();
						}
					}else{
						$this->db->trans_commit();
						echo "No permission to save records";
					}  
				}else{
					if($this->user_permissions->is_edit('t_internal_trans_receipt')){
						$account_update=$this->account_update(0);
						if($account_update==1){

							$this->set_delete();
							$this->load->model('trans_settlement');

							$this->trans_settlement->delete_settlement_sub("t_internal_transfer_trans","108",$this->max_no);   

							for($x = 0; $x<100; $x++){
								if(isset($_POST['bc0_'.$x],$_POST['4_'.$x],$_POST['5_'.$x],$_POST['6_'.$x])){
									if($_POST['bc0_'.$x] != "" && $_POST['4_'.$x] != "" && $_POST['5_'.$x] != "" && $_POST['6_'.$x]!= ""){  

										if($_POST['trans_code'.$x]=="42"){
											$this->trans_settlement->save_iternal_transfer_trans("t_internal_transfer_trans", 
												$_POST['cl0_'.$x],
												$_POST['bc0_'.$x],
												$_POST['refcl_'.$x],
												$_POST['refbc_'.$x],
												$_POST['acc_code'], 
												$_POST['3_'.$x], 
												$_POST['trans_code'.$x], 
												$_POST['2_'.$x],  
												108, 
												$this->max_no, 
												"0", 
												$_POST['6_'.$x],
												0,0);
										}else if($_POST['trans_code'.$x]=="18"){
											$this->trans_settlement->save_settlement_multi("t_debit_note_trans",
												$_POST['acc_code'],
												$_POST['date'],
												$_POST['trans_code'.$x],
												$_POST['2_'.$x],
												108,
												$this->max_no,
												"0",
												$_POST['6_'.$x],
												$_POST['cl0_'.$x],
												$_POST['bc0_'.$x]);   
										}       
									}
								}
							}

							$this->load->model('t_payment_option');
							$this->t_payment_option->delete_all_payments_opt(108,$this->max_no);


							$this->t_payment_option->save_payment_option($this->max_no,108);

							$this->db->where('cl',$this->sd['cl']);
							$this->db->where('bc',$this->sd['branch']);
							$this->db->where('nno',$_POST['hid']);
							$this->db->update('t_int_trans_receipt_sum', $t_int_trans_receipt_sum);

							if(count($t_int_trans_receipt_sum_temp)){$this->db->insert_batch("t_int_trans_receipt_det",$t_int_trans_receipt_sum_temp);}

							$this->account_update(1);

							$this->utility->save_logger("EDIT",108,$this->max_no,$this->mod);
							echo $this->db->trans_commit();

						}else{
							echo "Invalid account entries";
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
			echo $e->getMessage()." - Operation fail please contact admin"; 
		} 
	}

	public function account_update($condition){

		$this->db->where("trans_no", $this->max_no);
		$this->db->where("trans_code", 108);
		$this->db->where("cl", $this->sd['cl']);
		$this->db->where("bc", $this->sd['branch']);
		$this->db->delete("t_check_double_entry");

		$sql="SELECT description FROM m_account
		WHERE `code`='".$_POST['acc_code']."' ";

		$acc_name=$this->db->query($sql)->first_row()->description;

		if($_POST['hid']=="0"||$_POST['hid']==""){

		}else{
			if($condition=="1"){
				$this->db->where('cl',$this->sd['cl']);
				$this->db->where('bc',$this->sd['branch']);
				$this->db->where('trans_code',108);
				$this->db->where('trans_no',$this->max_no);
				$this->db->delete('t_account_trans');
			}
		}

		$config = array(
			"ddate" => $_POST['date'],
			"trans_code"=>108,
			"trans_no"=>$this->max_no,
			"op_acc"=>0,
			"reconcile"=>0,
			"cheque_no"=>0,
			"narration"=>"",
			"ref_no" => $_POST['ref_no']
		);

		$des = $_POST['memo'];
		$this->load->model('account');
		$this->account->set_data($config);

		$total_amount=(double)$_POST['net'];

		$this->account->set_value2($des, $total_amount, "cr", $_POST['acc_code'],$condition);

		if(isset($_POST['cash']) && !empty($_POST['cash']) && $_POST['cash']>0){
			$acc_code = $this->utility->get_default_acc('CASH_IN_HAND');
			$this->account->set_value2($acc_name, $_POST['cash'], "dr", $acc_code,$condition);    
		}

		if(isset($_POST['cheque_recieve']) && !empty($_POST['cheque_recieve']) && $_POST['cheque_recieve']>0){
			$acc_code = $this->utility->get_default_acc('CHEQUE_IN_HAND');
			$this->account->set_value2($acc_name, $_POST['cheque_recieve'], "dr", $acc_code,$condition);    
		}

		if(isset($_POST['pdchq']) && !empty($_POST['pdchq']) && $_POST['pdchq']>0){
			$acc_code = $this->utility->get_default_acc('CHEQUE_IN_HAND');
			$this->account->set_value2($acc_name, $_POST['pdchq'], "dr", $acc_code,$condition);    
		}


  /*if(isset($_POST['credit_note']) && !empty($_POST['credit_note']) && $_POST['credit_note'] >0){
    $acc_code = $this->utility->get_default_acc('CASH_IN_HAND');
    $this->account->set_value2($des, $_POST['credit_note'], "dr", $acc_code,$condition);    
}*/

if($condition==0){
	$query = $this->db->query("
		SELECT (IFNULL( SUM( t.`dr_amount`),0) = IFNULL(SUM(t.`cr_amount`),0)) AS ok 
		FROM `t_check_double_entry` t
		LEFT JOIN `m_account` a ON t.`acc_code` = a.`code`
		WHERE  t.`cl`='" . $this->sd['cl'] . "'  AND t.`bc`='" . $this->sd['branch'] . "'  AND t.`trans_code`='108'  AND t.`trans_no` ='" . $this->max_no . "' AND 
		a.`is_control_acc`='0'");

	if($query->row()->ok=="0"){
		$this->db->where("trans_no", $_POST['hid']);
		$this->db->where("trans_code", 108);
		$this->db->where("cl", $this->sd['cl']);
		$this->db->where("bc", $this->sd['branch']);
		$this->db->delete("t_check_double_entry");
		return "0";
	}else{
		return "1";
	}
}
}  

private function set_delete(){
	$this->db->where("sub_trans_code",108);
	$this->db->where('sub_trans_no',$_POST['hid']);
	$this->db->where('cl',$this->sd['cl']);
	$this->db->where('bc',$this->sd['branch']);
	$this->db->delete("t_internal_transfer_trans");

	$this->db->where('cl',$this->sd['cl']);
	$this->db->where('bc',$this->sd['branch']);
	$this->db->where('nno',$_POST['hid']);
	$this->db->delete("t_int_trans_receipt_det");

}


public function check_code(){
	$this->db->where('code', $_POST['code']);
	$this->db->limit(1);
	echo $this->db->get($this->mtb)->num_rows;
}


public function load(){
	$sql="SELECT s.cl,
	s.bc,
	s.`nno`,
	s.`ref_no`,
	s.`memo`,
	s.`acc_code`,
	a.`description` AS acc_des,
	s.`ddate`,
	s.`amount`,
	s.`balance`,
	s.`payment`,
	s.`pay_cash`,
	s.`pay_receive_chq`,
	s.`pay_ccard`,
	s.`settle_amount`,
	s.`settle_balance`,
	s.`receipt_balance`
	FROM `t_int_trans_receipt_sum` s
	JOIN m_account a ON a.`code` = s.`acc_code`
	WHERE cl='".$this->sd['cl']."' 
	AND bc='".$this->sd['branch']."' 
	AND nno='".$_POST['id']."'";

	$query=$this->db->query($sql);

	$x=0;
	if($query->num_rows()>0){
		$a['sum']=$query->result();
	}else{
		$x=2;
	}

	$sql_det="SELECT cl,bc,to_cl,
	to_bc,
	trans_code,
	trans_no,
	`date`,
	description,
	amount,
	balance,
	payment,
	sub_no
	FROM `t_int_trans_receipt_det` d
	WHERE cl='".$this->sd['cl']."' 
	AND bc='".$this->sd['branch']."' 
	AND nno='".$_POST['id']."'";

	$query_det = $this->db->query($sql_det);

	if($query_det->num_rows()>0){
		$a['det']=$query_det->result();
	}else{
		$x=2;
	}

	if($x==0){
		echo json_encode($a);
	}else{
		echo json_encode($x);
	}

}


public function account_update_delete(){
	$sql="INSERT INTO t_account_trans (cl,bc,ddate,trans_code,trans_no
	, `acc_code`
	,description,cr_amount,dr_amount, op_acc,reconcile,oc,ref_no,cheque_no
	,`is_control_acc`,`narration`)
	SELECT * FROM (SELECT cl,bc,ddate,trans_code,trans_no
	, `acc_code`
	,'Canceled Transaction',dr_amount AS cr_amount,cr_amount AS dr_amount, op_acc,reconcile,oc,ref_no,cheque_no
	,`is_control_acc`,`narration` FROM t_account_trans
	WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' 
	AND trans_code='108' AND trans_no='".$_POST['trans_no']."' ) t";

	$result = $this->db->query($sql);
}  

public function delete(){
	$this->db->trans_begin();
	error_reporting(E_ALL); 
	function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
		throw new Exception($errFile); 
	}
	set_error_handler('exceptionThrower'); 
	try { 
		if($this->user_permissions->is_delete('t_internal_trans_receipt')){

			$this->db->where('cl',$this->sd['cl']);
			$this->db->where('bc',$this->sd['branch']);
			$this->db->where('sub_trans_no',$_POST['trans_no']);
			$this->db->where('sub_trans_code',108);
			$this->db->delete('t_internal_transfer_trans');

			$data=array('is_cancel'=>'1');
			$this->db->where('cl',$this->sd['cl']);
			$this->db->where('bc',$this->sd['branch']);
			$this->db->where('nno',$_POST['trans_no']);
			$this->db->update('t_int_trans_receipt_sum',$data);

			$this->account_update_delete();

			$this->db->query("INSERT INTO t_cheque_received_cancel SELECT * FROM t_cheque_received WHERE cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' AND trans_code='108' AND trans_no ='".$_POST['trans_no']."'");
			$this->db->where("cl",$this->sd['cl']);
			$this->db->where("bc",$this->sd['branch']);
			$this->db->where("trans_code",108);
			$this->db->where("trans_no",$_POST['trans_no']);
			$this->db->delete("t_cheque_received");   

			$this->utility->save_logger("CANCEL",108,$_POST['trans_no'],$this->mod);
			echo $this->db->trans_commit();
		}else{
			$this->db->trans_commit();
			echo "No permission to delete records";
		}  
	}catch(Exception $e){ 
		$this->db->trans_rollback();
		echo $e->getMessage()." - Operation fail please contact admin"; 
	}  
}



public function get_next_no(){
	if(isset($_POST['hid'])){
		if($_POST['hid'] == "0" || $_POST['hid'] == ""){      
			$field="nno";
			$this->db->select_max($field);
			$this->db->where("cl",$this->sd['cl']);
			$this->db->where("bc",$this->sd['branch']);    
			return $this->db->get($this->mtb)->first_row()->$field+1;
		}else{
			return $_POST['hid'];  
		}
	}else{
		$field="nno";
		$this->db->select_max($field);
		$this->db->where("cl",$this->sd['cl']);
		$this->db->where("bc",$this->sd['branch']);    
		return $this->db->get($this->mtb)->first_row()->$field+1;
	}
}


public function load_branch_details(){

	$acc_code=$_POST['acc_id'];
	$cl=$this->sd['cl'];
	$bc=$this->sd['branch'];

	$sql="SELECT sub_cl,sub_bc,cl,bc,trans_no,trans_no,sub_no,trans_code,ddate,'Internal Transfer Outstanding' AS des, SUM(dr) AS amount , SUM(dr-cr) AS  balance,trans_mode
	FROM `t_internal_transfer_trans` 
	WHERE acc_code='$acc_code' AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' 
	GROUP BY cl,bc,acc_code,trans_code,trans_no
	HAVING balance > 0
	UNION ALL
	SELECT sub_cl,sub_bc,cl,bc,trans_no,trans_no,0 as sub_no,trans_code,ddate,'Internal Transfer Debit Note' AS des, SUM(dr) AS amount , SUM(dr-cr) AS  balance,0 as trans_mode
	FROM `t_debit_note_trans` 
	WHERE acc_code='$acc_code' AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."' 
	GROUP BY cl,bc,acc_code,trans_code,trans_no
	HAVING balance > 0"; 
	$query=$this->db->query($sql);   

	if($query->num_rows()>0){
		$a['det']=$query->result();
		echo json_encode($a);
	}else{
		echo json_encode($a['det']=2);
	}
}



function load_branch_balance(){
	$acc_code=$_POST['acc_id']; 
	$cl=$this->sd['cl'];
	$bc=$this->sd['branch'];
	$sql="SELECT SUM(dr)-SUM(cr) AS balance 
	FROM `t_internal_transfer_trans` 
	WHERE acc_code='$acc_code' AND cl='".$this->sd['cl']."' AND bc='".$this->sd['branch']."'";       
	$query=$this->db->query($sql);
	echo $this->db->query($sql)->row()->balance;
} 

public function get_payment_option(){
	$this->db->where("code",$_POST['code']);
	$data['result']=$this->db->get("r_payment_option")->result();
	echo json_encode($data);
} 


public function get_max_no(){
	$field="nno";
	$this->db->select_max($field);
	$this->db->where("cl",$this->sd['cl']);
	$this->db->where("bc",$this->sd['branch']);    
	echo $this->db->get($this->mtb)->first_row()->$field+1;
}

public function branch_accounts(){
	if ($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}

	$sql="SELECT b.cl, b.`name`,a.`acc_code` ,aa.`description` FROM r_branch_current_acc a
	JOIN m_branch b ON b.`bc` = a.`ref_bc`
	JOIN m_account aa ON aa.`code` = a.`acc_code`
	WHERE (a.`acc_code` LIKE '%$_POST[search]%' 
	OR aa.`description` LIKE '%$_POST[search]%')
	ORDER BY b.`cl`";
	$query=$this->db->query($sql);

	$a = "<table id='item_list' style='width : 100%' >";
	$a .= "<thead><tr>";
	$a .= "<th class='tb_head_th'>Cluster</th>";
	$a .= "<th class='tb_head_th'>Branch Name</th>";
	$a .= "<th class='tb_head_th'>Account</th>";
	$a .= "<th class='tb_head_th'>Description</th>";
	$a .= "</thead></tr>";
	$a .= "<tr class='cl'>";
	$a .= "<td>&nbsp;</td>";
	$a .= "<td>&nbsp;</td>";
	$a .= "<td>&nbsp;</td>";
	$a .= "<td>&nbsp;</td>";
	$a .= "</tr>";
	foreach ($query->result() as $r) {
		$a .= "<tr class='cl'>";
		$a .= "<td>" . $r->cl . "</td>";
		$a .= "<td>" . $r->name . "</td>";
		$a .= "<td>" . $r->acc_code . "</td>";
		$a .= "<td>" . $r->description . "</td>";
		$a .= "</tr>";
	}
	$a .= "</table>";

	echo $a;
}

}