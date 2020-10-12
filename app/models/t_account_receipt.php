<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_account_receipt extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_cheque;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	$this->tb_sum = $this->tables->tb['t_receipt_gl'];
	$this->tb_cheque = $this->tables->tb['t_cheque_recgl'];
	$this->tb_bank = $this->tables->tb['t_bank_recgl'];
	$this->tb_receivable = $this->tables->tb['t_receivable_recgl'];
    $this->tb_receivable_transe = $this->tables->tb['t_receivable_invoice_transe'];
    $this->max_no=$this->utility->get_max_no("t_receipt_gl","nno");
    $this->load->model('user_permissions');
    }
    
    public function base_details(){
	$a['max_no'] = $this->max_no();
	
	return $a;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['bc']);
	$this->db->select_max("nno");
	
	return $this->db->get($this->tb_sum)->first_row()->nno+1;
    }
    
    public function save(){
	$this->db->trans_start();
	
	if($_POST['advance']=='0'){$_POST['advance']=0.00;}
	if($_POST['amt']=='0'){$_POST['amt']=0.00;}
	//if($_POST['settle']=='Amount'){$_POST['settle']=0.00;}
	if($_POST['cheque']=='0'){$_POST['cheque']=0.00;}
	

	$a = array(
	    "ddate"=>$_POST['date'],
	    "ref_no"=>$_POST['ref_no'],
	    "customer_account"=>$_POST['account'],
	    "description"=>$_POST['description'],
	    "narration"=>$_POST['narration'],
        "cash_amount"=>$_POST['amt'],
	    //"cash_account"=>$_POST['cash_account'],
	    "cheque_amount"=>$_POST['cheque'],
	    //"bank_amount"=>$_POST['bank'],
	    "advance_amount"=>$_POST['advance'],
	    "total"=>$_POST['total'],
	    "payable_grid"=>$_POST['payable_grid'],
	    "bc"=>$this->sd['bc'],
	    "cl"=>$this->sd['cl'],
	    "oc"=>$this->sd['oc']
	);
	
	$total = $_POST['cheque']+$_POST['amt'];
	
	if($_POST['hid'] == "" || $_POST['hid'] == 0){
		if($this->user_permissions->is_add('t_account_receipt')){
		    $a["nno"] = $this->max_no();
		    $this->db->insert($this->tb_sum, $a);
		    $lid = $this->db->insert_id();
		}else{
		    echo "No permission to save records";
		}    
	}else{
		if($this->user_permissions->is_edit('t_account_receipt')){
		    $this->db->where("nno", $_POST['hid']);
		    $this->db->limit(1);
		    $this->db->update($this->tb_sum, $a);
		    $lid = $_POST['hid']; $a["nno"] = $_POST['id'];
		    $this->set_delete();
		}else{
		    echo "No permission to edit records";
		} 
	}
	
        $paytranse=$bank=$a_cheque=$a_rec= array();
	
        for($i=0; $i<25; $i++){
		if($_POST['3_'.$i] != "0" && $_POST['4_'.$i] != "0" && $_POST['4_'.$i] != "" && (double)$_POST['3_'.$i] > 0){
                    
		$a_rec[] = array(
			"nno"=>$a["nno"],
			"receivable_no"=>$_POST['4_'.$i],
			"last_pay_date"=>$_POST['0_'.$i],
			"paid_amount"=>$_POST['1_'.$i],
			"balance_paid"=>$_POST['2_'.$i],
			"paid"=>$_POST['3_'.$i],
            "bc"=>$this->sd['bc'],
            "cl"=>$this->sd['cl'],
			"auto_num"=>$i  
		    );
                    
                 $paytranse[]=array(
                    "no" => $a["nno"],
                    "account_code" => $_POST['account'],
                    "date"=> $_POST['date'],
                    "dr_transe_code"=>'23',
                    "dr_transe_no"=>$_POST['4_'.$i],
                    "dr_amount"=>0,
                    "cr_transe_code"=>'25',
                    "cr_transe_no"=>$a["nno"],
                    "cr_amount"=>$_POST['total'],
                    "bc"=>$this->sd['branch'],
                    "cl"=>$this->sd['cl'],
                );
		}
            }	
            
            for($i=0; $i<25; $i++){    
                
		if($_POST['q3_'.$i] != "" && (double)$_POST['q3_'.$i] > 0){

		    $a_cheque[] = array(
                        "nno"=>$a["nno"],
                        "account_code"=>$_POST['q2_'.$i],
			"cheque_no"=>$_POST['q1_'.$i],
			"realized_date"=>$_POST['q4_'.$i],
			"amount"=>$$_POST['q3_'.$i],
			"bank_code"=>$$_POST['qbbh_'.$i],
			"branch_code"=>$$_POST['qbh_'.$i],
			"auto_num"=>$i,
                        "bc"=>$this->sd['bc'],
                        "cl"=>$this->sd['cl'],
                        
		    );
                }     
                    
                if($_POST['qbbh2_'.$i] != "" && (double)$_POST['q52_'.$i] > 0){
                    
                    $bank[]=array(
                        "nno"=>$a["n54no"],
                        "bank_code"=>$_POST['qbbh2_'.$i],
                        "slip_no"=>$_POST['q12_'.$i],   
                        "realized_date"=>$_POST['q22_'.$i],   
                        "amount"=>$_POST['q52_'.$i],   
                        "auto_num"=>$i,
                        "bc"=>$this->sd['bc'],
                        "cl"=>$this->sd['cl'],    
                    );
		    }

        }


	if(count($a_rec)){$this->db->insert_batch($this->tb_receivable, $a_rec);}
	
	if(count($a_cheque)){$this->db->insert_batch($this->tb_cheque, $a_cheque);}
	
	if(count($bank)){$this->db->insert_batch($this->tb_bank, $bank);}
	
	if(count($paytranse)){$this->db->insert_batch($this->tb_receivable_transe, $paytranse);}
        

        
        
	////Account Section ---------------------------------------------------------------------//
	/*$config = array(
	    "id" => $lid,
	    "cid" => $this->sd['db_code'],
	    "no" => $a['no'],
	    "type" => "RECEIPT",
	    "date" => $_POST['date'],
	    "ref_no" => $_POST['ref_no']
	);
	
	$this->load->model('account');
	$this->account->set_data($config);
	
	$des = "Receipt Customer : ".$_POST['customer'];
	
	$this->account->set_value($des, $_POST['cheque']+$_POST['amt']+$_POST['advance'], "cr", "debtor_control");
        
	if((double)$_POST['amt'] > 0){ $this->account->set_value($des, $_POST['amt'], "dr", "cash_in_hand"); }
	if((double)$_POST['advance'] > 0){ $this->account->set_value($des, $_POST['advance'], "dr", "advance"); }
	if((double)$_POST['cheque'] > 0){ $this->account->set_value($des, $_POST['cheque'], "dr", "cheque_in_hand", join(", ", $cheque_no)); }
	
       
        
	if((double)$_POST['cheque'] != 0.00){
	    for($i=0; $i<10; $i++){

		if($_POST['qbh_'.$i] != '' && (double)$_POST['q3_'.$i] > 0){
		    $this->account->set_cheque('t', $_POST['qbh_'.$i], $_POST['qbbh_'.$i], $_POST['q2_'.$i], $_POST['q1_'.$i], $_POST['q4_'.$i], $_POST['q3_'.$i]);
		    $this->account->set_cheque('trance', $_POST['qbh_'.$i], $_POST['qbbh_'.$i], $_POST['q2_'.$i], $_POST['q1_'.$i], $_POST['q4_'.$i], $_POST['q3_'.$i]);
		}
	    }
	}
	
	$this->account->send();
	
	////End---------------------------------------------------------------------------------//*/
	$this->db->trans_complete();
	
	redirect(base_url()."?action=t_customer_receipt&print=".$lid);
    }
    
    public function load_receivable_invoice_data()
    {
        $sql="SELECT
                `dr_transe_no`
                ,`date`
                ,SUM(`cr_amount`) AS cr_amount
                ,SUM(`dr_amount`-`cr_amount`) AS balance
                ,`account_code`
                FROM
                `t_receivable_invoice_transe`
                WHERE `account_code`='".$_POST['cus_acc']."'
                GROUP BY `dr_transe_no`,`dr_transe_code`,`account_code` HAVING balance>0";
        $query=$this->db->query($sql);
        if($query->num_rows>0)
        {
            $a['rec']=$query->result();
        }        
        else
        {
            $a['rec']='0';
        }  
        
        echo json_encode($a);
    }



    public function load(){
	$this->db->select(array('id', 'no', 'date', 'ref_no', 'memo', 'cheque_amount', 'cash_amount', 'balance', 'customer', 'name', 'outlet_name', 'is_cancel', 'posting','advance_settlement'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
        $this->db->limit(1);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
	    
	    $this->db->select(array('cheque_no', 'account_no', 'cheque_amount', $this->tb_cheque.'.bank', 'bank_branch', 'realize_date', 'description', 'bank_name'));
	    $this->db->where("id", $a["sum"]->id);
	    $this->db->where('module', 'RECEIPT');
	    $this->db->join($this->tb_bank, $this->tb_bank.".code = ".$this->tb_cheque.".bank_branch", "INNER");
	    $this->db->join($this->tb_bank_branch, $this->tb_bank_branch.".BranchID = ".$this->tb_cheque.".bank", "INNER");
	    $a['cheque'] = $this->db->get($this->tb_cheque)->result();
	}
        
            $this->db->select(array('id','trans_code','receipt_no','total','balance','settle_amount'));
	    $this->db->where($this->tb_advance.".trans_code" ,  'RECEIPT');
	    $this->db->where($this->tb_advance.".id" ,  $a["sum"]->id);
	    $a['adv2'] = $this->db->get($this->tb_advance)->result();
        
        echo json_encode($a);
    }
    
    public function check_cheque_no(){
	$this->db->where('cheque_no', $_POST['c_no']);
	$this->db->where('account_code', $_POST['a_no']);
	$this->db->where('is_cancel', 0);
	$this->db->limit(1);
	echo $this->db->get($this->tb_cheque)->num_rows;	
    }
    
    public function delete(){
		
    	if($this->user_permissions->is_delete('t_account_receipt')){
    		
			$a = true;
		        
			$this->db->where('id', $_POST['id']);
			$this->db->where('module', 'RECEIPT');
		        if(! $this->db->delete($this->tb_trance)){
		            $a = false;
		        }
		        
			$this->db->where("id", $_POST['id']);
			$this->db->where("trans_code", "RECEIPT");
			if(! $this->db->update($this->tb_advance, array("is_cancel"=>$this->sd['oc']))){
		            $a = false;
		        }
			
		        $this->db->where("id", $_POST['id']);
			$this->db->where("module", "RECEIPT");
			if(! $this->db->update($this->tb_cheque, array("is_cancel"=>$this->sd['oc']))){
		            $a = false;
		        }
		        
		        $this->db->where('id', $_POST['id']);
		        $this->db->where('module', 'RECEIPT'); 
			if(!$this->db->delete($this->tb_adv_trance)){
		           $a = false; 
		        }
		                
		        $this->db->where("id", $_POST['id']);
		        $this->db->limit(1);
		        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
		            $a = false;
		        }
		        
			$this->load->model('account');
			$this->account->delete($_POST['id'], "RECEIPT", $this->sd['db_code']);
			$this->load->database("tdmc", true);
			
		        echo $a;

        }else{
			echo "No permission to delete records";
		}
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'RECEIPT');
	$this->db->delete($this->tb_trance);
	
        $this->db->where('id', $_POST['hid']);
	$this->db->where('trans_code', 'RECEIPT');
        $this->db->delete($this->tb_advance);

        $this->db->where("id", $_POST['hid']);
	$this->db->where("module", 'RECEIPT');
	$this->db->delete($this->tb_cheque);
        
	$this->db->where("id", $_POST['hid']);
	$this->db->where("module", 'RECEIPT');
	$this->db->delete($this->tb_cheque);
	
        $this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'RECEIPT');        
	$this->db->delete($this->tb_adv_trance);
        
	$this->load->model('account');
	$this->account->delete($_POST['hid'], "RECEIPT", $this->sd['db_code']);
	
	$this->load->database($this->sd['db'], true);
	
    }

}