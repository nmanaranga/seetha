<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class trans_payment_option extends CI_Model {
    
  private $sd;
  private $max_no; 
  function __construct(){
  	parent::__construct();
  	$this->sd = $this->session->all_userdata();
  	$this->load->database($this->sd['db'], true);
	}



	public function save_credit_card($trans_code,$trans_no,$card_type,$card_no,$amount){

		$data=array(
			"cl"=>$this->sd['cl'],
			"bc"=>$this->sd['branch'],
			"trans_code"=>$trans_code,
			"trans_no"=>$trans_no,
			"card_type"=>$card_type,
			"card_no"=>$card_no,
			"amount"=>$amount
			);

		$this->db->insert("opt_credit_card_det",$data);
	  
	}


  public function save_credit_note($trans_code,$trans_no,$cn_no,$ddate,$amount,$balance,$settled){
    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "cn_no"=>$cn_no,
      "ddate"=>$ddate,
      "amount"=>$amount,
      "balance"=>$balance,
      "settled"=>$settled
      );

    $this->db->insert("opt_credit_note_det",$data);
  }



  public function save_debit_note($trans_code,$trans_no,$dn_no,$date,$amount,$balance,$settled){

    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "dn_no"=>$dn_no,
      "ddate"=>$date,
      "amount"=>$amount,
      "balance"=>$balance,
      "settled"=>$settled
      );

    $this->db->insert("opt_debit_note_det",$data);
    
  }

  public function save_bank_debit($trans_code,$trans_no,$code,$name,$amount){

    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "code"=>$code,
      "name"=>$name,
      "amount"=>$amount
      );

    $this->db->insert("opt_bank_debit_det",$data);
    
  }


  public function save_gift_voucher($trans_code,$trans_no,$vou_no,$issued_date,$amount){

    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "vou_no"=>$vou_no,
      "issued_date"=>$issued_date,
      "amount"=>$amount
      );

    $this->db->insert("opt_gift_voucher_det",$data);
    
  }



  public function save_advance($trans_code,$trans_no,$advance_no,$ddate,$amount,$balance,$settle){

    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "advance_no"=>$advance_no,
      "ddate"=>$ddate,
      "amount"=>$amount,
      "balance"=>$balance,
      "settle"=>$settle
      );

    $this->db->insert("opt_advance_pay_det",$data);
  }

  public function save_issue_cheque($trans_code,$trans_no,$bank,$description,$cheque_no,$amount,$cheque_date){

    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "bank"=>$bank,
      "description"=>$description,
      "cheque_no"=>$cheque_no,
      "amount"=>$amount,
      "cheque_date"=>$cheque_date
      );

    $this->db->insert("opt_issue_cheque_det",$data);
    
  }


  public function save_priviledge_card($trans_code,$trans_no,$card_no,$available,$redeem){

    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "card_no"=>$card_no,
      "available"=>$available,
      "redeem"=>$redeem
      );

    $this->db->insert("opt_privilege_card_det",$data);
    
  }



  public function save_receive_cheque($trans_code,$trans_no,$bank,$branch,$account_no,$cheque_no,$amount,$cheque_date){

    $data=array(
      "cl"=>$this->sd['cl'],
      "bc"=>$this->sd['branch'],
      "trans_code"=>$trans_code,
      "trans_no"=>$trans_no,
      "bank"=>$bank,
      "branch"=>$branch,
      "account_no"=>$account_no,
      "cheque_no"=>$cheque_no,
      "amount"=>$amount,
      "cheque_date"=>$cheque_date
      );

    $this->db->insert("opt_receive_cheque_det",$data);
    
  }



	public function delete_all_payments_opt($trans_code,$trans_no){

			$this->db->where("cl",$this->sd['cl']);
			$this->db->where("bc",$this->sd['branch']);
			$this->db->where("trans_code",$trans_code);
			$this->db->where("trans_no",$trans_no);
			$this->db->delete("opt_credit_card_det");

      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $this->db->where("trans_code",$trans_code);
      $this->db->where("trans_no",$trans_no);
      $this->db->delete("opt_credit_note_det");

      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $this->db->where("trans_code",$trans_code);
      $this->db->where("trans_no",$trans_no);
      $this->db->delete("opt_debit_note_det");

      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $this->db->where("trans_code",$trans_code);
      $this->db->where("trans_no",$trans_no);
      $this->db->delete("opt_bank_debit_det");

      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $this->db->where("trans_code",$trans_code);
      $this->db->where("trans_no",$trans_no);
      $this->db->delete("opt_gift_voucher_det");

      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $this->db->where("trans_code",$trans_code);
      $this->db->where("trans_no",$trans_no);
      $this->db->delete("opt_advance_pay_det");

      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $this->db->where("trans_code",$trans_code);
      $this->db->where("trans_no",$trans_no);
      $this->db->delete("opt_issue_cheque_det");

      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $this->db->where("trans_code",$trans_code);
      $this->db->where("trans_no",$trans_no);
      $this->db->delete("opt_privilege_card_det");

      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $this->db->where("trans_code",$trans_code);
      $this->db->where("trans_no",$trans_no);
      $this->db->delete("opt_receive_cheque_det");

	}


  public function save_payment_option($max_no){
      $this->max_no=$max_no;
      $this->load->model('trans_payment_option');
      for($x=0;$x<10;$x++){
          if(isset($_POST['type1_'.$x]) && !empty($_POST['type1_'.$x])){ 
             if(isset($_POST['no1_'.$x]) && !empty($_POST['no1_'.$x])){ 
               if(isset($_POST['amount1_'.$x]) && !empty($_POST['amount1_'.$x])){
                 $this->save_credit_card(5,$this->max_no,$_POST['type1_'.$x],$_POST['no1_'.$x],$_POST['amount1_'.$x]);                                        
               }
             } 
          }

          if(isset($_POST['no2_'.$x]) && !empty($_POST['no2_'.$x])){ 
             if(isset($_POST['amount2_'.$x]) && !empty($_POST['amount2_'.$x])){ 
               if(isset($_POST['balance2_'.$x]) && !empty($_POST['balance2_'.$x])){
                  if(isset($_POST['settle2_'.$x]) && !empty($_POST['settle2_'.$x])){
                   $this->save_credit_note(5,$this->max_no,$_POST['no2_'.$x],$_POST['date2_'.$x],$_POST['amount2_'.$x],$_POST['balance2_'.$x],$_POST['settle2_'.$x]);                                      
                  }
                }
             } 
          }

          if(isset($_POST['no3_'.$x]) && !empty($_POST['no3_'.$x])){ 
             if(isset($_POST['amount3_'.$x]) && !empty($_POST['amount3_'.$x])){ 
               if(isset($_POST['balance3_'.$x]) && !empty($_POST['balance3_'.$x])){
                  if(isset($_POST['settle3_'.$x]) && !empty($_POST['settle3_'.$x])){
                   $this->save_debit_note(5,$this->max_no,$_POST['no3_'.$x],$_POST['date3_'.$x],$_POST['amount3_'.$x],$_POST['balance3_'.$x],$_POST['settle3_'.$x]);                                      
                  }
                }
             } 
          }

          if(isset($_POST['code4_'.$x]) && !empty($_POST['code4_'.$x])){ 
             if(isset($_POST['amount4_'.$x]) && !empty($_POST['amount4_'.$x])){ 
              $this->save_bank_debit(5,$this->max_no,$_POST['code4_'.$x],$_POST['name4_'.$x],$_POST['amount4_'.$x]);
             } 
          }

          if(isset($_POST['type5_'.$x]) && !empty($_POST['type5_'.$x])){ 
              if(isset($_POST['no5_'.$x]) && !empty($_POST['no5_'.$x])){ 
                if(isset($_POST['amount5_'.$x]) && !empty($_POST['amount5_'.$x])){
                 $this->save_gift_voucher(5,$this->max_no,$_POST['type5_'.$x],$_POST['no5_'.$x],$_POST['amount5_'.$x]);                                        
                }
             } 
          }

          if(isset($_POST['no6_'.$x]) && !empty($_POST['no6_'.$x])){ 
             if(isset($_POST['amount6_'.$x]) && !empty($_POST['amount6_'.$x])){ 
               if(isset($_POST['balance6_'.$x]) && !empty($_POST['balance6_'.$x])){
                  if(isset($_POST['cdate6_'.$x]) && !empty($_POST['cdate6_'.$x])){
                   $this->save_advance(5,$this->max_no,$_POST['no6_'.$x],$_POST['date6_'.$x],$_POST['amount6_'.$x],$_POST['balance6_'.$x],$_POST['cdate6_'.$x]);
                  }
                }
             } 
          }

          if(isset($_POST['bank7_'.$x]) && !empty($_POST['bank7_'.$x])){ 
             if(isset($_POST['chqu7_'.$x]) && !empty($_POST['chqu7_'.$x])){ 
               if(isset($_POST['amount7_'.$x]) && !empty($_POST['amount7_'.$x])){
                  if(isset($_POST['cdate7_'.$x]) && !empty($_POST['cdate7_'.$x])){
                      $this->save_issue_cheque(5,$this->max_no,$_POST['bank7_'.$x],$_POST['des7_'.$x],$_POST['chqu7_'.$x],$_POST['amount7_'.$x],$_POST['cdate7_'.$x]);
                  }
                }
             } 
          }

          if(isset($_POST['type8_'.$x]) && !empty($_POST['type8_'.$x])){ 
              if(isset($_POST['no8_'.$x]) && !empty($_POST['no8_'.$x])){ 
                if(isset($_POST['amount8_'.$x]) && !empty($_POST['amount8_'.$x])){
                 $this->save_priviledge_card(5,$this->max_no,$_POST['type8_'.$x],$_POST['no8_'.$x],$_POST['amount8'.$x]); 
                }
             } 
          }

          if(isset($_POST['bank9_'.$x]) && !empty($_POST['bank9_'.$x])){ 
             if(isset($_POST['branch9_'.$x]) && !empty($_POST['branch9_'.$x])){ 
               if(isset($_POST['acc9_'.$x]) && !empty($_POST['acc9_'.$x])){
                  if(isset($_POST['cheque9_'.$x]) && !empty($_POST['cheque9_'.$x])){
                    if(isset($_POST['amount9_'.$x]) && !empty($_POST['amount9_'.$x])){
                      $this->save_receive_cheque(5,$this->max_no,$_POST['bank9_'.$x],$_POST['branch9_'.$x],$_POST['acc9_'.$x],$_POST['cheque9_'.$x],$_POST['amount9_'.$x],$_POST['cheque9_'.$x]);
                    }
                  }  
                }
             } 
          }
      }
    }




}
