<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class account extends CI_Model {
    
    private $sd;
    private $tb_trance;
    private $tb_defult;
    private $tb_accounts;
    
    private $set = array();
    private $id;
    private $no;
    private $type;
    private $date;
    private $ref_no;

    private $trans_code;
    private $trans_no;
    private $op_acc;
    private $cheque_no;
    private $narration;
    private $reconcile;


    
    function __construct(){
		parent::__construct();
		
		$this->sd = $this->session->all_userdata();
		$this->load->database($this->sd['db'], true);
		$this->tb_trance = $this->tables->tb['t_account_trans'];
		$this->tb_defult = $this->tables->tb['m_defult_account'];
		$this->tb_accounts = $this->tables->tb['m_account'];
	    $this->tb_cheque_trance = $this->tables->tb['t_cheques_trans'];
	    $this->tb_cheques_issued = $this->tables->tb['t_cheques_issued'];
		$this->tb_cheque = $this->tables->tb['t_cheques'];
	    $this->tb_check_double_entry = $this->tables->tb['t_check_double_entry'];
    }
    
    public function set_val_cd($description, $amount, $type = "dr", $acc_type, $cheque_no = ""){
		$a = array();	
		$a['id'] = $this->id;
		$a['Trans_No'] = $this->no;
		$a['Trans_Code'] = $this->type;
		$a['dDate'] = $this->date;
		$a['Acc_Code'] = $acc_type;
		$a['Description'] = $description;
		
		if($type == "dr"){
		    $a['Dr_Amount'] = $amount;
		    $a['Cr_Amount'] = 0;
		}elseif($type == "cr"){
		    $a['Dr_Amount'] = 0;
		    $a['Cr_Amount'] = $amount;
	        }
		
		$a['BC'] = $this->sd['bc'];
		$a['OC'] = $this->sd['oc'];
		$a['ref_no'] = $this->ref_no;
		$a['cheque_no'] = $cheque_no;	
	        
	        $db = $this->load->database("account", true);
	        $this->db->insert($this->tb_trance, $a);
	        $this->innerLoop($acc_type,$a);        
    }
    
   private function innerLoop($accCode,$a){
		 $sql="select control_acc from m_account where code='".$accCode."' limit 1";
   		 $q = $this->db->query($sql);
         if($q->num_rows() > 0){
		    $row = $q->row();
		    $con_ac= $row->control_acc;
	     	$a['acc_code']=$con_ac;   
        if($con_ac !=''){    
            $this->db->insert($this->tb_trance, $a);
            $this->innerLoop($con_ac,$a);
            }
        }
    	$db = $this->load->database("default", true);    
    }
    
   private function innerLoopTemp($accCode,$a){
		 $sql="select control_acc from m_account where code='".$accCode."' limit 1";
   		 $q = $this->db->query($sql);
         if($q->num_rows() > 0){
		    $row = $q->row();
		    $con_ac= $row->control_acc;
	     	$a['acc_code']=$con_ac;   
        if($con_ac !=''){    
            $this->db->insert($this->tb_check_double_entry, $a);
            $this->innerLoopTemp($con_ac,$a);
            }
        }
    	$db = $this->load->database("default", true);    
    }
    
    public function set_value($description, $amount, $type = "dr", $acc_type, $cheque_no = ""){
			$this->db->select($acc_type);
			$this->db->limit(1);
			$acc_code = $this->db->get($this->tb_defult)->first_row()->{$acc_type};
			$a = array();
			
			$a['ddate']= $this->date ;
			$a["trans_code"]=$this->trans_code;
            $a["trans_no"]= $this->trans_no;
            $a["op_acc"]=$this->op_acc;
            $a["reconcile"]=$this->reconcile;
            $a["cheque_no"]=$this->cheque_no;
            $a["narration"]=$this->narration;
 			$a["ref_no"] = $this->ref_no;
			$a['acc_code'] = $acc_code;
			$a['description'] = $description;
			$a["cl"] = $this->sd['cl'];
			$a['bc'] = $this->sd['branch'];
			$a['oc'] = $this->sd['oc'];
		
			if($type == "dr"){
			    $a['dr_amount'] = $amount;
			    $a['cr_amount'] = 0;
			}elseif($type == "cr"){
			    $a['dr_amount'] = 0;
			    $a['cr_amount'] = $amount;
			}

			$this->set[] = $a;
			$this->db->insert($this->tb_trance, $a);
            $this->innerLoop($acc_code,$a);
    }

        public function set_value4($description, $amount, $type = "dr", $acc_code, $condition, $sub_no, $cheque_no = ""){


     	$table;

     	if($condition==1){
     		$table=$this->tb_trance;
     	}else if($condition==0){
     		$table=$this->tb_check_double_entry;
     	}

    	$a = array();
		$a['ddate']= $this->date ;
		$a["trans_code"]=$this->trans_code;
        $a["trans_no"]= $this->trans_no;
        $a["op_acc"]=$this->op_acc;
        $a["reconcile"]=$this->reconcile;
        $a["cheque_no"]=$this->cheque_no;
        $a["narration"]=$this->narration;
		$a["ref_no"] = $this->ref_no;
		$a['acc_code'] = $acc_code;
		$a['sub_no'] = $sub_no;
		$a['description'] = $description;
		$a["cl"] = $this->sd['cl'];
		$a['bc'] = $this->sd['branch'];
		$a['oc'] = $this->sd['oc'];
	
			if($type == "dr"){
			    $a['dr_amount'] = $amount;
			    $a['cr_amount'] = 0;
			}elseif($type == "cr"){
			    $a['dr_amount'] = 0;
			    $a['cr_amount'] = $amount;
			}

        $this->set[] = $a;
        $this->db->insert($table, $a);
        if($condition==1){
        	$this->innerLoop($acc_code,$a); 
    	}
        // echo "--".$condition;      
	
    }
    
     public function set_value_internal($cl,$bc,$description, $amount, $type = "dr", $acc_code, $condition,$cheque_no = ""){
     	//var_dump($acc_code);
     	$table;
     	if($condition==1){
     		$table=$this->tb_trance;
     	}else if($condition==0){
     		$table=$this->tb_check_double_entry;
     	}
    	$a = array();
		$a['ddate']= $this->date ;
		$a["trans_code"]=$this->trans_code;
        $a["trans_no"]= $this->trans_no;
        $a["op_acc"]=$this->op_acc;
        $a["reconcile"]=$this->reconcile;
        $a["cheque_no"]=$this->cheque_no;
        $a["narration"]=$this->narration;
		$a["ref_no"] = $this->ref_no;
		$a['acc_code'] = $acc_code;
		$a['description'] = $description;
		$a["cl"] = $cl;
		$a['bc'] = $bc;
		$a["ref_cl"] = $this->sd['cl'];
		$a['ref_bc'] = $this->sd['branch'];
		$a['oc'] = $this->sd['oc'];
		if($type == "dr"){
		    $a['dr_amount'] = $amount;
		    $a['cr_amount'] = 0;
		}elseif($type == "cr"){
		    $a['dr_amount'] = 0;
		    $a['cr_amount'] = $amount;
		}
        $this->set[] = $a;
        $this->db->insert($table, $a);
        if($condition==1){
        	$this->innerLoop($acc_code,$a); 
    	} 
    }
    
     public function set_value2($description, $amount, $type = "dr", $acc_code, $condition,$cheque_no = ""){


     	$table;

     	if($condition==1){
     		$table=$this->tb_trance;
     	}else if($condition==0){
     		$table=$this->tb_check_double_entry;
     	}

    	$a = array();
		$a['ddate']= $this->date ;
		$a["trans_code"]=$this->trans_code;
        $a["trans_no"]= $this->trans_no;
        $a["op_acc"]=$this->op_acc;
        $a["reconcile"]=$this->reconcile;
        $a["cheque_no"]=$this->cheque_no;
        $a["narration"]=$this->narration;
		$a["ref_no"] = $this->ref_no;
		$a['acc_code'] = $acc_code;
		$a['description'] = $description;
		$a["cl"] = $this->sd['cl'];
		$a['bc'] = $this->sd['branch'];
		$a['oc'] = $this->sd['oc'];
	
			if($type == "dr"){
			    $a['dr_amount'] = $amount;
			    $a['cr_amount'] = 0;
			}elseif($type == "cr"){
			    $a['dr_amount'] = 0;
			    $a['cr_amount'] = $amount;
			}

        $this->set[] = $a;
        $this->db->insert($table, $a);
        if($condition==1){
        	$this->innerLoop($acc_code,$a); 
    	}
        // echo "--".$condition;      
	
    }

    public function set_value22($date, $description, $amount, $type = "dr", $acc_code, $condition,$cheque_no = ""){


     	$table;

     	if($condition==1){
     		$table=$this->tb_trance;
     	}else if($condition==0){
     		$table=$this->tb_check_double_entry;
     	}

    	$a = array();
		$a['ddate']= $date ;
		$a["trans_code"]=$this->trans_code;
        $a["trans_no"]= $this->trans_no;
        $a["op_acc"]=$this->op_acc;
        $a["reconcile"]=$this->reconcile;
        $a["cheque_no"]=$this->cheque_no;
        $a["narration"]=$this->narration;
		$a["ref_no"] = $this->ref_no;
		$a['acc_code'] = $acc_code;
		$a['description'] = $description;
		$a["cl"] = $this->sd['cl'];
		$a['bc'] = $this->sd['branch'];
		$a['oc'] = $this->sd['oc'];
	
			if($type == "dr"){
			    $a['dr_amount'] = $amount;
			    $a['cr_amount'] = 0;
			}elseif($type == "cr"){
			    $a['dr_amount'] = 0;
			    $a['cr_amount'] = $amount;
			}

        $this->set[] = $a;
        $this->db->insert($table, $a);
        if($condition==1){
        	$this->innerLoop($acc_code,$a); 
    	}
        // echo "--".$condition;      
	
    }
    
    public function set_value3($op_acc, $cheque, $description, $amount, $type = "dr", $acc_code, $condition,$cheque_no = ""){


     	$table;

     	if($condition==1){
     		$table=$this->tb_trance;
     	}else if($condition==0){
     		$table=$this->tb_check_double_entry;
     	}

    	$a = array();
		$a['ddate']= $this->date ;
		$a["trans_code"]=$this->trans_code;
        $a["trans_no"]= $this->trans_no;
        $a["op_acc"]=$op_acc;
        $a["reconcile"]=$this->reconcile;
        $a["cheque_no"]=$cheque;
        $a["narration"]=$this->narration;
		$a["ref_no"] = $this->ref_no;
		$a['acc_code'] = $acc_code;
		$a['description'] = $description;
		$a["cl"] = $this->sd['cl'];
		$a['bc'] = $this->sd['branch'];
		$a['oc'] = $this->sd['oc'];
	
			if($type == "dr"){
			    $a['dr_amount'] = $amount;
			    $a['cr_amount'] = 0;
			}elseif($type == "cr"){
			    $a['dr_amount'] = 0;
			    $a['cr_amount'] = $amount;
			}

        $this->set[] = $a;
        $this->db->insert($table, $a);
        if($condition==1){
        	$this->innerLoop($acc_code,$a); 
    	} 
        // echo "--".$condition;      
	
    }
    
   //   public function set_value2_temp($description, $amount, $type = "dr", $acc_code, $cheque_no = ""){
   //      	$a = array();
			
			// $a['ddate']= $this->date ;
			// $a["trans_code"]=$this->trans_code;
   //          $a["trans_no"]= $this->trans_no;
   //          $a["op_acc"]=$this->op_acc;
   //          $a["reconcile"]=$this->reconcile;
   //          $a["cheque_no"]=$this->cheque_no;
   //          $a["narration"]=$this->narration;
 		// 	$a["ref_no"] = $this->ref_no;
			// $a['acc_code'] = $acc_code;
			// $a['description'] = $description;
			// $a["cl"] = $this->sd['cl'];
			// $a['bc'] = $this->sd['branch'];
			// $a['oc'] = $this->sd['oc'];

	
			// if($type == "dr"){
			//     $a['dr_amount'] = $amount;
			//     $a['cr_amount'] = 0;
			// }elseif($type == "cr"){
			//     $a['dr_amount'] = 0;
			//     $a['cr_amount'] = $amount;
			// }
   //      $this->set[] = $a;
   //      $this->db->insert($this->tb_check_double_entry, $a);
   //      $this->innerLoopTemp($acc_code,$a);        
	
   //  }
    
 //    public function set_cheque($tb, $bank, $bank_branch, $acc_no, $chq_no, $date, $amount){
	// $a = array();
	// if($tb == "t"){
	//     $a['id'] = $this->id;
	//     $a['Trans_No'] = $this->no;
	//     $a['Trans_Code'] = $this->type;
	//     $a['dDate'] = $this->date;
	//     $a['Bank_Code'] = $bank;
	//     $a['BranchCode'] = $bank_branch;
	//     $a['Account_No'] = $acc_no;
	//     $a['Cheque_No'] = $chq_no;
	//     $a['Realise_Date'] = $date;
	//     $a['Amount'] = $amount;
	//     $a['Status'] = "P";
	    
	//     $a['bc'] = $this->sd['bc'];
	//     $a['oc'] = $this->sd['oc'];
	    
	    
	    
	//     $db = $this->load->database("account", true);
	//     $this->db->insert($this->tb_cheque, $a);

	// }
	// elseif($tb == "trance"){
 //            $a['id'] = $this->id;
	//     $a['Trans_No'] = $this->no;
	//     $a['Trans_Code'] = $this->type;
	//     $a['dDate'] = $this->date;
	    
	//     $a['Bank'] = $bank;
	//     $a['Branch'] = $bank_branch;
	//     $a['Account_No'] = $acc_no;
	//     $a['Chq_No'] = $chq_no;
	//     $a['amount'] = $amount;
	//     $a['Status'] = "P";
	    
	//     $a['bc'] = $this->sd['bc'];
	//     $a['oc'] = $this->sd['oc'];
	//     $a['refno'] = $this->ref_no;
	    
	    
	    
	//   //  $db = $this->load->database("account", true);
	//     $this->db->insert($this->tb_cheque_trance, $a);
	// }
 //       elseif($tb == "issue"){
 //         $a['rec_id'] = $this->id;  
 //         $a['Trans_No'] = $this->no;
 //         $a['Status'] = "P";
 //         $a['BankID'] = $bank;
 //         $a['Cheque_No'] = $chq_no;
 //         $a['Realize_Date'] = $date;
 //         $a['Amount'] = $amount;
 //         $a['bc'] = $this->sd['bc'];
	// 	 $a['oc'] = $this->sd['oc'];
	// 	 $a['Trans_Type'] = $this->type;
         
 //         $db = $this->load->database("account", true);
	//  $this->db->insert($this->tb_cheques_issued, $a);
           
 //       }
        
	
	
 //    }
    
 //    public function delete($id, $trance_code, $tid = 0){
 //        $db = $this->load->database("account", true);
	
	// $db->where('id', $id);
 //        $db->where('Trans_Code', $trance_code);
	// $db->delete($this->tb_cheque_trance);
	
 //        $db->where('id', $id);
 //        $db->where('Trans_Code', $trance_code);
	// $db->delete($this->tb_trance);
	
	// $db->where('id', $id);
 //        $db->where('Trans_Code', $trance_code);
	// $db->delete($this->tb_cheque);
        
	// $db->where('rec_id', $id);
 //        $db->where('Trans_Type', $trance_code);
	// $db->delete($this->tb_cheques_issued);
 //    }
    
public function send(){
    if(count($this->set)){
    $db->insert_batch($this->tb_trance, $this->set);
    $this->set = array();
}
	        
//	if(count($this->chq_trance)){
//	    
//	    $db = $this->load->database("account", true);
//	    $this->db->insert($this->tb_cheque_trance, $this->chq_trance);
//	    $this->chq_trance = array();
//	}
//	
//	if(count($this->chq_t)){
//	    
//	    $db = $this->load->database("account", true);
//	    $this->db->insert($this->tb_cheque, $this->chq_t);
//	    $this->chq_t = array();
//	    
//	}

}
    
 //    public function set_data($d){
	// if(isset($d['id'])){ $this->id = $d['id']; }else{ echo "ID Not Set /model/account on line 74."; exit; }
	// if(isset($d['no'])){ $this->no = $d['no']; }else{ echo "No Not Set /model/account on line 75."; exit; }
	// if(isset($d['type'])){ $this->type = $d['type']; }else{ echo "Type Not Set /model/account on line 76."; exit; }
	// if(isset($d['date'])){ $this->date = $d['date']; }else{ echo "Date Not Set /model/account on line 77."; exit; }
	// if(isset($d['ref_no'])){ $this->ref_no = $d['ref_no']; }else{ echo "Ref. No Not Set /model/account on line 78."; exit; }
 //    }

	public function set_data($d){
		if(isset($d['ddate'])){ $this->date = $d['ddate']; }else{ echo "Date Not Set /model/account "; exit; }
		if(isset($d['trans_code'])){ $this->trans_code = $d['trans_code']; }else{ echo "Trans Code Not Set /model/account "; exit; }
		if(isset($d['trans_no'])){ $this->trans_no = $d['trans_no']; }else{ echo "Trans No Not Set /model/account "; exit; }
		if(isset($d['op_acc'])){ $this->op_acc = $d['op_acc']; }else{ echo "OP ACC Not Set /model/account "; exit; }
		if(isset($d['reconcile'])){ $this->reconcile = $d['reconcile']; }else{ echo "Reconcile Not Set /model/account "; exit; }
		if(isset($d['cheque_no'])){ $this->cheque_no = $d['cheque_no']; }else{ echo "Cheque No Not Set /model/account "; exit; }
		if(isset($d['narration'])){ $this->narration = $d['narration']; }else{ echo "Narration Not Set /model/account"; exit; }
		if(isset($d['ref_no'])){ $this->ref_no = $d['ref_no']; }else{ echo "Ref No Not Set /model/account "; exit; }
		
    }


}