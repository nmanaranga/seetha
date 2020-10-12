<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_sales_ad extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_cheque;
    private $tb_items;
    private $tb_bank;
    private $tb_cus;
    private $tb_bank_branch;
    private $tb_branch;
    private $tb_acc_trance;
    private $h = 140;
    private $w = 216;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_advance_pay_sum'];
	$this->tb_det = $this->tables->tb['t_advance_pay_det'];
	$this->tb_trance = $this->tables->tb['t_advance_pay_trance'];
	$this->tb_cheque = $this->tables->tb['t_customer_cheques'];
	$this->tb_items = $this->tables->tb['m_items'];
	$this->tb_bank = $this->tables->tb['m_banks'];
	$this->tb_bank_branch = $this->tables->tb['m_bank_branch'];
	$this->tb_cus = $this->tables->tb['m_customer'];
	$this->tb_branch = $this->tables->tb['s_branches'];
	$this->tb_acc_trance=$this->tables->tb['t_customer_acc_trance'];
    }
    
    public function base_details(){
	$a['max_no'] = $this->max_no();
	
	return $a;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['bc']);
	$this->db->select_max("no");
	
	return $this->db->get($this->tb_sum)->first_row()->no+1;
    }
    
    public function save(){
	$this->db->trans_start();
        $total=0;
	if($_POST['cash']=='Cash Payment'){$_POST['cash']=0.00;}

	$a = array(
	    "date"=>$_POST['date'],
	    "ref_no"=>$_POST['ref_no'],
	    "customer"=>$_POST['customer'],
	    "cheque_amount"=>$_POST['cheque'],
	    "cash_amount"=>$_POST['cash'],
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc']
	);
	
	$total = $_POST['cheque']+$_POST['cash'];
	
	if($_POST['hid'] == "" || $_POST['hid'] == 0){
	    $a["no"] = $this->max_no();
	    $this->db->insert($this->tb_sum, $a);
	    $lid = $this->db->insert_id();
	}else{
	    $this->db->where("id", $_POST['hid']);
	    $this->db->limit(1);
	    $this->db->update($this->tb_sum, $a);
	    $lid = $_POST['hid']; $a["no"] = $_POST['id'];
	    $this->set_delete();
	}
	
        $a3=$a_det = $a_acc = $a_cheq = $cheque_no = array();
	
        for($i=0; $i<25; $i++){
	    
	    if($i<10){
		if($_POST['qn_'.$i] != "" && $_POST['qn_'.$i] != "0" && (double)$_POST['q4_'.$i] > 0){
		    
		    $a_det[] = array(
			"id"=>$lid,
			"b_branch"=>$_POST['qbh_'.$i],
			"b_bank"=>$_POST['qbbh_'.$i],
			"cheque_no"=>$_POST['q1_'.$i],
			"acc_no"=>$_POST['q2_'.$i],
			"r_date"=>$_POST['q4_'.$i],
			"cheque_amount"=>$_POST['q3_'.$i]
			
		    );
	    
		    $a3[] = array(
			"id"=>$lid,
			"module"=>"ADPAY",
			"cheque_no"=>$_POST['q1_'.$i],
			"account_no"=>$_POST['q2_'.$i],
			"cheque_amount"=>$_POST['q3_'.$i],
			"bank"=>$_POST['qbbh_'.$i],
			"bank_branch"=>$_POST['qbh_'.$i],
			"realize_date"=>$_POST['q4_'.$i]
		    );
		    
		    $cheque_no[] = $_POST['q1_'.$i];
		
                    
		}
	    }
        }
	$total = $_POST['cheque']+$_POST['cash'];

	    $a_acc[] = array(
		"id"=>$lid,
                "module"=>"ADPAY",
		"customer"=>$_POST['customer'],
		"dr_trance_code"=>'ADPAY',
		"dr_trance_no"=>$a["no"],
		"dr_trance_amount"=>0,
		"cr_trance_code"=>'ADPAY',
		"cr_trance_no"=>$a["no"],
		"cr_trance_amount"=>$total,
		"bc"=>$this->sd['bc'],
		"oc"=>$this->sd['oc'],
		"date"=>$_POST['date']
	    );

	if(count($a_det)){$this->db->insert_batch($this->tb_det, $a_det);}
	
         if(count($a3)){ $this->db->insert_batch($this->tb_cheque, $a3);	}
        
	if(count($a_acc)){$this->db->insert_batch($this->tb_trance, $a_acc);}
	
	////Account Section ---------------------------------------------------------------------//
	$config = array(
	    "id" => $lid,
	    "no" => $a['no'],
	    "type" => "ADPAY",
	    "date" => $_POST['date'],
	    "ref_no" => $_POST['ref_no']
	);
	
	$this->load->model('account');
	$this->account->set_data($config);
	
	$des = "Sale Customer : ".$_POST['customer'];
	
	$this->account->set_value($des, $_POST['cheque']+$_POST['cash'], "cr", "advance");
	
        $this->account->set_value($des, $_POST['cash'], "dr", "cash_in_hand");
	
	if((double)$_POST['cheque'] > 0){
	    $this->account->set_value($des, $_POST['cheque'], "dr", "cheque_in_hand", join(", ", $cheque_no));
	}
	
        if((double)$_POST['cheque'] != 0.00){
	    for($i=0; $i<10; $i++){

		if($_POST['qbbh_'.$i] != '' && (double)$_POST['q3_'.$i] > 0){
		    $this->account->set_cheque('t', $_POST['qbbh_'.$i], $_POST['qbh_'.$i], $_POST['q2_'.$i], $_POST['q1_'.$i], $_POST['q4_'.$i], $_POST['q3_'.$i]);
		    $this->account->set_cheque('trance', $_POST['qbbh_'.$i], $_POST['qbh_'.$i], $_POST['q2_'.$i], $_POST['q1_'.$i], $_POST['q4_'.$i], $_POST['q3_'.$i]);
		}
                }
         }
        
	$this->account->send();
    
	////End---------------------------------------------------------------------------------//
	$this->db->trans_complete();
	
	redirect(base_url()."?action=t_sales_ad&print=".$lid);
    }
    
    public function load(){
	$this->db->select(array('id', 'no', 'date', 'ref_no', 'cheque_amount', 'cash_amount', 'customer', 'bc','oc','name','is_cancel'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
        $this->db->limit(1);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
		
	if(isset($a["sum"]->id)){
            
            $sql="SELECT
                `t_advance_pay_det`.`b_bank`
                ,  `m_banks`.`bank_name`
                , `t_advance_pay_det`.`b_branch`
                , `m_bank_branch`.`Description`
                , `t_advance_pay_det`.`cheque_no`
                , `t_advance_pay_det`.`acc_no`
                , `t_advance_pay_det`.`cheque_amount`
                , `t_advance_pay_det`.`r_date`
            FROM
                `m_banks`
                INNER JOIN `t_advance_pay_det` 
                    ON (`m_banks`.`code` = `t_advance_pay_det`.`b_bank`)
                INNER JOIN `m_bank_branch` 
                    ON (`t_advance_pay_det`.`b_branch` = `m_bank_branch`.`BranchID`)
             WHERE id='".$a["sum"]->id."'       ";
            
            $qry=$this->db->query($sql);
            $a['det']=$qry->result();
            
//	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
//	    $a['det'] = $this->db->get($this->tb_det)->result();
//	    $this->db->select(array('cheque_no', 'acc_no', 'cheque_amount', 'b_branch', 'r_date'));
//	    $this->db->where("id", $a["sum"]->id);
	    
	}
        
        echo json_encode($a);
    }
    
    public function delete(){
	$a = true;
        
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'ADPAY');
        if(! $this->db->delete($this->tb_trance)){
            $a = false;
        }
	
	$this->db->where("id", $_POST['id']);
	$this->db->where("module", "ADPAY");
	if(! $this->db->update($this->tb_cheque, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
	
        $this->db->where("no", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
	$this->load->model('account');
	$this->account->delete($_POST['id'], "ADPAY", $this->sd['db_code']);
	$this->load->database($this->sd['db'], true);
	
        echo $a;
    }
    
    public function is_refund()
    {
        $sql="SELECT
                `is_refund`
            FROM
               `t_advance_pay_sum`
            WHERE `id`='".$_POST['id']."' AND bc='".$this->sd['bc']."'";
        $qry=$this->db->query($sql);
        $q=$qry->first_row();
        
        if(empty($q->is_refund) || $q->is_refund=='0')
        {
            echo '0';
        }
        else
        {
            echo '1';
        }    
        
    }        
    
    
    public function set_delete(){
        
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'ADPAY');
	$this->db->delete($this->tb_trance);
	
	$this->db->where("id", $_POST['hid']);
	$this->db->where("module", 'ADPAY');
	$this->db->delete($this->tb_cheque);
	
	$this->load->model('account');
	$this->account->delete($_POST['hid'], "ADPAY");
	$this->load->database("default", true);
	
    }
    
    public function balance(){
	$this->db->select(array("dr_trance_no AS no", "SUM(cr_trance_amount) AS total", "SUM(cr_trance_amount - dr_trance_amount) AS balance"));
	$this->db->where($this->tb_trance.".customer", $_POST['code']);
	$this->db->where($this->tb_trance.".bc", $this->sd['bc']);
	$this->db->having('balance >', 0); 
	$this->db->group_by(array("cr_trance_code", "cr_trance_no","customer"));
	
	echo json_encode($this->db->get($this->tb_trance)->result());
    }
    
    public function print_view(){
        $this->load->library('useclass');
	$a['title'] = 'Sales Advance';
        
	$this->db->select();
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
        $this->db->where($this->tb_sum.'.id', $_GET['id']);
        $query = $this->db->get($this->tb_sum);
        
        if(1){
            $this->db->select(array("no", "bc", "date", $this->tb_cus.".name", "is_cancel",$this->tb_det.".cheque_amount","cash_amount"));
            $this->db->where($this->tb_det.".id", $_GET['id']);
            $this->db->limit(1);
	    $this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
	    $this->db->join($this->tb_det, $this->tb_det.".id = ".$this->tb_sum.".id", "INNER");
	    $this->db->join($this->tb_branch, $this->tb_branch.".code = ".$this->tb_sum.".bc", "INNER");
            $query2 = $this->db->get($this->tb_sum);
            
	    
            $result = $query2->result();
           $result2 = $query->first_row();
	    
	      
	   //print_r($query->first_row());exit;
	    
	    $a['cancel'] = $result2->is_cancel;
            
	    
	    $page_end = "<div style='text-align : right; padding-top : 25px;'>...........................<br />Signature&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
            
	    //foreach($result as $r){
//            $r = new stdClass();
//            $r->cheque_amount = array("data"=>"Cheque Amount", "style"=>"text-align: left; font-weight: bold; ");
//            $r->cash_amount = array("data"=>$result2->cheque_amount, "style"=>"text-align: center; font-weight: bold; ");
//	    //}
//            //$r->total = array("data"=>$t, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
//            
//            //array_push($result, $r);
//            $result[] = $r;
//            
//            
//	    $r2 = new stdClass();
//            $r2->cheque_amount = array("data"=>"Cash Amount", "style"=>"text-align: left; font-weight: bold; border-bottom: 2px solid #000;");
//            $r2->cash_amount = array("data"=>$result2->cash_amount, "style"=>"text-align: center; font-weight: bold; border-bottom: 2px solid #000;");
//	    
//	    //array_push($result,$r2);
//	     $result[] = $r2;
            
            $code = array("data"=>"Cash Amount", "style"=>"text-align: left;", "chalign"=>"text-align: left;");
            $name = array("data"=>"Cheque Amount","style"=>"text-align: right;", "chalign"=>"text-align: right;");
       
            //$heading = array($code, $name);//, $discount, $total
             
            $code = array("data"=>"cheque_amount", "total"=>false, "format"=>"text");
            $name  = array("data"=>"cash_amount", "total"=>false, "format"=>"text");
            
            //$field = array($code, $name);//, $discount, $total


	   $heading=array();
	   $field=array();

	    $inv_info = "<table style='width : 100%; font-size : 12px;' border='0'>

                <tr>
                    <td width='150px'>Customer</td>
		    <td>:".$result2->name."</td>
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>   
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>   
                    <td></td>  
		    <td align='right'>Reciept Date:</td>
		    <td align='right'> ".$result2->date."</td>
		</tr>
		<tr>
                    <td width='150px'>Cash Amount</td>
		    <td>:".$result2->cash_amount."</td>
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>   
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>    
                    <td></td>   
                    <td></td>  
		    <td align='right'>Cheque Amount:</td>
		    <td align='right'> ".$result2->cheque_amount."</td>
		</tr>
                
              

	    </table>";
            
            $page_rec = 9;
            
            //$header  = array("data"=>$this->useclass->r_header("Sales Advance Reciept : ".$result2->no." | Customer : ".$result2->customer." <br /> Date : ".$result2->date."<hr />",$inv_info), "style"=>"");
            $header  = array("data"=>$this->useclass->r_header("Sales Advance Reciept : ".$result2->no." <hr />", $inv_info."<hr/>"), "style"=>"font-weight: normal; font-family: Courier;");
	    $footer  = array("data"=>"<hr />Softmaster (pvt) LTD. - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
            $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
            $data = array(
                        "dbtem"=>$this->useclass->report_style(),
                        "data"=>$result,
                        "field"=>$field,
                        "heading"=>$heading,
                        "page_rec"=>$page_rec,
                        "height"=>$this->h,
                        "width"=>$this->w,
                        "header"=>35,
                        "footer"=>10,
                        "header_txt"=>$header,
                        "footer_txt"=>$footer,
                        "page_no"=>$page_no,
                        "header_of"=>false,
			"page_end"=>$page_end
                        );
            //print_r($data); exit;
            $this->load->library('greport', $data);
                
            $a['view'] = $this->greport->_print();
        }
	else{
            $a['view'] = "No Record";
        }
        
        return $a;
    }
}