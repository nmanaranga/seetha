<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_sales_credit extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_acc_trance;
    private $tb_items;
    private $tb_cheque;
    private $tb_bank;
    private $tb_bank_branch;
    private $tb_cus;
    private $tb_branch;
    private $tb_adv_trance;
    private $h = 140;
    private $w = 216;
	
	  private $a2;
	  private $a_ad;
	  private $advance;
    
    function __construct(){
	parent::__construct();
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_sales_sum'];
	$this->tb_det = $this->tables->tb['t_sales_det'];
	$this->tb_trance = $this->tables->tb['t_item_movement'];
	$this->tb_acc_trance = $this->tables->tb['t_customer_acc_trance'];
	$this->tb_items = $this->tables->tb['m_items'];
	$this->tb_cheque = $this->tables->tb['t_customer_cheques'];
	$this->tb_bank = $this->tables->tb['m_banks'];
	$this->tb_bank_branch = $this->tables->tb['m_bank_branch'];
	$this->tb_cus = $this->tables->tb['m_customer'];
	$this->tb_branch = $this->tables->tb['s_branches'];
	$this->tb_adv_trance = $this->tables->tb['t_advance_pay_trance'];
        $this->tb_sales_trance = $this->tables->tb['t_sales_trance'];
        $this->tb_advance = $this->tables->tb['t_advance'];
    }
    
    public function base_details(){
	$this->load->model('m_customer');
	$this->load->model('m_sales_ref');
	$this->load->model('t_purchase_order');
	$this->load->model('m_stores');
	$this->load->model('m_area');
	
	$a['cus'] = $this->m_customer->select('fielter');
	$a['ref'] = $this->m_sales_ref->select();
	$a['max_no'] = $this->max_no();
	$a['po_no'] = $this->t_purchase_order->select();
	$a['stores'] = $this->m_stores->select();
	$a['area'] = $this->m_area->select('name', 'width : 300px;');
	$a['sd'] = $this->sd;
	
	return $a;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['bc']);
	$this->db->select_max("no");
	
	return $this->db->get($this->tb_sum)->first_row()->no+1;
    }
    
    public function check_cheque_no(){
	$this->db->where('cheque_no', $_POST['c_no']);
	$this->db->where('account_no', $_POST['a_no']);
	$this->db->limit(1);
	
	echo $this->db->get($this->tb_cheque)->num_rows;	
    }
    
    public function is_return()
    {
        $sql="SELECT * FROM `t_sales_return_sum` WHERE `invoice_no`='$_POST[id]' AND bc='".$this->sd['bc']."' AND trance_type='1'";
	//echo $sql;exit;
        
        $qry=$this->db->query($sql);
        $r=$qry->first_row();
        
        if(!empty($r->no))
        {
            echo '1';
        }
        else {
            echo '0';
        }
        
    }
    
    
    public function save(){
	$this->load->database("default", true);
	$this->db->trans_start();
	
	if($_POST['cash'] == "Amount"){ $_POST['cash'] = 0; }
	if($_POST['credit'] == "Cheque Payment"){ $_POST['credit'] = 0; }
	if($_POST['cheque'] == "Cheque"){ $_POST['cheque'] = 0; }
	
	 if($_POST['discount'] == 0){ $_POST['discount'] = 0.00; }
	 if($_POST['discount'] == ""){ $_POST['discount'] = 0.00; }
	 $payment = $_POST['advance'] + $_POST['cheque'];	

	$a = array(
	    "date"=>$_POST['date'],
	    "ref_no"=>$_POST['ref_no'],
	    "memo"=>$_POST['memo'],
	    "customer"=>$_POST['customer'],
	    "sales_ref"=>$_POST['sales_ref'],
	    "discount"=>$_POST['discount'],
	    "balance"=>$_POST['balance'],
	    "pay_amount"=>$_POST['cash'],
	    "advance"=>$_POST['advance'],
	    "credit"=>$_POST['credit'],
	    "cheque"=>$_POST['cheque'],
            "is_request"=>$_POST['po'],
            "feed_back"=>0,
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc']
	);
	
	$sa = array(
	    "sales_ref"=>$_POST['sales_ref']
	);
	
	if(isset($_POST['stores'])){ $a["stores"] = $_POST['stores']; $sa["stores"] = $_POST['stores']; }
	
	$this->session->set_userdata($sa);
	
	if($_POST['hid'] == "" || $_POST['hid'] == 0){
	    $a["no"] = $this->max_no();
	    $this->db->insert($this->tb_sum, $a);
	    $lid = $this->db->insert_id();
	}else{
	    $this->db->where("id", $_POST['hid']);
	    $this->db->limit(1);
	    $this->db->update($this->tb_sum, $a);
	    $lid = $_POST['hid']; $a['no'] = $_POST['id'];
	    $this->set_delete();
	}
	if($_POST['po']==0){
        if((int)$a["no"] > 0)
        {
                    $this->db->where('no', $a["no"])
                    ->where('bc', $this->sd['bc'])
                    ->update($this->tb_sum, array("show_confrom_alert"=>1));
        }
        }
        $advance=$a_ad=$a1 = $a2 = $a3 = array();
	$avg_price=$stock_val=$net_amount = 0; $cheque_no = array(); $dis = $_POST['discount'];
        for($i=0; $i<25; $i++){
	    if($i < 10){
		if($_POST['qbh_'.$i] != 0 && (double)$_POST['q3_'.$i] > 0){
		    $a3[] = array(
			"id"=>$lid,
			"module"=>"CR_SALES",
			"cheque_no"=>$_POST['q1_'.$i],
			"account_no"=>$_POST['q2_'.$i],
			"cheque_amount"=>$_POST['q3_'.$i],
			"bank"=>$_POST['qbh_'.$i],
			"bank_branch"=>$_POST['qbbh_'.$i],
			"realize_date"=>$_POST['q4_'.$i]
		    );
		    
		    $cheque_no[] = $_POST['q1_'.$i];
		}
		
		if($_POST['p4_'.$i] != "" && $_POST['p4_'.$i] > 0){
		    
		    $a_ad[] = array(
		    	"id"=>$lid,	
                        "module"=>"CR_SALES",
			"customer"=>$_POST['customer'],
			"dr_trance_code"=>'CR_SALE',
			"dr_trance_no"=>$a['no'],
			"dr_trance_amount"=>$_POST['p4_'.$i],
			"cr_trance_code"=>'ADPAY',
			"cr_trance_no"=>$_POST['p1_'.$i],
			"cr_trance_amount"=>0,
			"bc"=>$this->sd['bc'],
			"oc"=>$this->sd['oc'],
			"date"=>$_POST['date']
		    );
		
                    $advance[]=array(
                    "id"=>$lid,
                    "trans_code"=>'CR_SALES',
                    "receipt_no"=>$_POST['p1_'.$i],   
                    "total"=>$_POST['p2_'.$i],   
                    "balance"=>$_POST['p3_'.$i],   
                    "settle_amount"=>$_POST['p4_'.$i]       
                    );
                    
                    
		}
		
	    }
	    
            if($_POST['3_'.$i] == ""){ $_POST['3_'.$i] = 0.00; }
            if($_POST['4_'.$i] == ""){ $_POST['4_'.$i] = 0.00; }
	    if($_POST['5_'.$i] == ""){ $_POST['5_'.$i]= 0.00;}
	    
	    
            
            if($_POST['h_'.$i] != "" && $_POST['h_'.$i] != "0"){
		$a1[] = array(
		    "id"=>$lid,
		    "item_code"=>$_POST['h_'.$i],
		    "cost"=>$_POST['2_'.$i],
		    "quantity"=>$_POST['1_'.$i],
		    "discount"=>$_POST['3_'.$i],
		    "discount_pre"=>$_POST['4_'.$i],
		    "foc"=>$_POST['5_'.$i]
		);
		
                    $a7[] = array(
		    "cus_id"=>$_POST['customer'],
		    "date"=>$_POST['date'],
		    "item_code"=>$_POST['h_'.$i],
		    "in_no"=>$a['no'],
		    "in_qty"=>$_POST['1_'.$i],
		    "out_no"=>'0',
		    "out_qty"=>'0',
		    "trance_code"=>'CR_SALE',
		    "trance_no"=>$lid,
		    "pay_type"=>'1', //paytype=0 -> credit sales
		    "bc"=>$this->sd['bc']
		   
		);
                
                
                
		$sql="SELECT avg_price FROM m_items WHERE `code`='".$_POST['h_'.$i]."'";
                $q=$this->db->query($sql);
                $r=$q->first_row();
                $avg_price=$r->avg_price;
                
                 if($_POST['po']==0){
		$net_amount += ($_POST['2_'.$i]*$_POST['1_'.$i]) - $_POST['3_'.$i];
		$dis += $_POST['3_'.$i];
		
		$sv=$this->get_batch_stock($_POST['h_'.$i],$_POST['1_'.$i],$lid,$a['no'],$_POST['date'],$_POST['stores'],$_POST['ref_no'],$_POST['customer'],$_POST['3_'.$i]);
                
                $sv=$sv['sv'];
                $stock_val  +=$sv;
                
		$this->insert_subitem_batch_stock($_POST['h_'.$i],$lid,$a["no"],$_POST['date'],$_POST['stores'],$this->sd['bc'],$_POST['ref_no'],$_POST['1_'.$i],$_POST['customer'],$a['no']);
                 }
                }

        }
	
	$net_amount -= $_POST['discount'];
        
	if(count($a1)){ $this->db->insert_batch($this->tb_det, $a1); }

	if(count($a3)){ $this->db->insert_batch($this->tb_cheque, $a3);	}
	if($_POST['po']==0){
	if(count($a_ad)){ $this->db->insert_batch($this->tb_adv_trance, $a_ad);}
        
        if(count($a7)){ $this->db->insert_batch($this->tb_sales_trance, $a7);}
        }
        if(count($advance)){$this->db->insert_batch($this->tb_advance, $advance);}
	
	$a4 = array(
	    "id"=>$lid,
	    "module"=>'CR_SALES',
	    "customer"=>$_POST['customer'],
	    "dr_trnce_code"=>"CR_SALES",
	    "dr_trnce_no"=>$a['no'],
	    "cr_trnce_code"=>"CR_SALES",
	    "cr_trnce_no"=>$a['no'],
	    "dr_amount"=>$net_amount,
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc'],
	    "date"=>$_POST['date']
	);
	if($_POST['po']==0){
	$this->db->insert($this->tb_acc_trance, $a4);
        }
        

        
	if($payment > 0){
	    $a5 = array(
		"id"=>$lid,
		"module"=>'CR_SALES',
		"customer"=>$_POST['customer'],
		"dr_trnce_code"=>"CR_SALES",
		"dr_trnce_no"=>$a['no'],
		"cr_trnce_code"=>"CR_SALES",
		"cr_trnce_no"=>$a['no'],
		"cr_amount"=>$payment,
		"bc"=>$this->sd['bc'],
		"oc"=>$this->sd['oc'],
		"date"=>$_POST['date']
	    );
	    if($_POST['po']==0){
	   $this->db->insert($this->tb_acc_trance, $a5);
            }
	}
        if($_POST['po']==0){
        $stock_val=$stock_val-$_POST['discount'];
	//
	////Account Section ---------------------------------------------------------------------//
	$config = array(
	    "id" => $lid,
	    "no" => $a['no'],
	    "type" => "CR_SALES",
	    "date" => $_POST['date'],
	    "ref_no" => $_POST['ref_no']
	);
	
	$this->load->model('account');
	$this->account->set_data($config);
	
	$des = "Sale Customer : ".$_POST['customer'];
	
	$this->account->set_value($des, $net_amount, "dr", "debtor_control");
	$this->account->set_value($des, $net_amount, "cr", "sales");
        $this->account->set_value($des, $stock_val, "dr", "stock");
        $this->account->set_value($des, $stock_val, "cr", "cost_of_sales");
	if($payment>0)
        {    
	$this->account->set_value($des, $payment, "cr", "debtor_control");
        }

	if((double)$_POST['cheque'] > 0){
	    $this->account->set_value($des, $_POST['cheque'], "dr", "cheque_in_hand", join(", ", $cheque_no));
	}
	if((double)($_POST['advance']) > 0){ $this->account->set_value($des, ($_POST['advance']), "dr", "advance"); }
        
//        if((double)$_POST['amt'] > 0){
//	if((double)($_POST['amt']) > 0){ $this->account->set_value($des, ($_POST['amt']), "dr", "cash_in_hand"); }
//        }

	
        if((double)$_POST['cheque'] != 0.00){
	    for($i=0; $i<10; $i++){

		if($_POST['qbh_'.$i] != '' && (double)$_POST['q3_'.$i] > 0){
		    $this->account->set_cheque('t', $_POST['qbh_'.$i], $_POST['qbbh_'.$i], $_POST['q2_'.$i], $_POST['q1_'.$i], $_POST['q4_'.$i], $_POST['q3_'.$i]);
		    $this->account->set_cheque('trance', $_POST['qbh_'.$i], $_POST['qbbh_'.$i], $_POST['q2_'.$i], $_POST['q1_'.$i], $_POST['q4_'.$i], $_POST['q3_'.$i]);
		}
                }
         }
        
        
        $this->account->send();
	
	////End---------------------------------------------------------------------------------//
        }
        
	$this->db->trans_complete();
	if($_POST['po']==0){
	redirect(base_url()."?action=t_sales_credit&print=".$lid);
        }
        else
        {
        redirect(base_url()."?action=t_sales_credit");
        }    
        
    }
    
    
   private function get_batch_stock($item,$qty,$lid,$no,$date,$store,$ref_no,$customer,$discount)
        {
            $pur_price='';
            $sv=0;
            $a1 = $a2 = $a3 = $a_ad=array();
            
            $sql="SELECT `item_code`,SUM(in_quantity) - SUM(out_quantity) AS qty,batch_no,`pur_price`
                    FROM `t_item_movement`
                    WHERE `item_code`='$item'
                    GROUP BY batch_no,item_code
                    HAVING qty>0
                    ORDER BY batch_no ASC";
            
            $qry=$this->db->query($sql);
            
            foreach ($qry->result() AS $rr)
            {
                if($qty > 0)
                    {
                        $sd="SELECT purchase_price,max_sales FROM m_items WHERE `code`='$item'";
                        $qsd=$this->db->query($sd);
                        $s=$qsd->first_row();
                        $ms=$s->max_sales;
    
                
                        $sql="SELECT avg_price FROM m_items WHERE `code`='$item'";
                        $q=$this->db->query($sql);
                        $r=$q->first_row();
                        $avg_price=$r->avg_price;
                        
                        $sqlo="SELECT `pur_price` FROM `t_batch` WHERE `item_code`='$item' AND `batch_code`='$rr->batch_no' AND bc='".$this->sd['bc']."' GROUP BY batch_code";
                        $qryo=$this->db->query($sqlo);
                        $o=$qryo->first_row();
                        $pur_price=$o->pur_price;

                        if($qty > $rr->qty)
                            {

                            $a2[] = array(
                                    "id"=>$lid,
                                    "trance_id"=>$no,
                                    "trance_type"=>'CR_SALES',
                                    "item_code"=>$item,
                                    "out_quantity"=>$rr->qty,
                                    "date"=>$date,
                                    "bc"=>$this->sd['bc'],
                                    "stores"=>$store,
                                    "ref_no"=>$ref_no,
                                    "batch_no"=>$rr->batch_no,
                                    "sal_price"=>$ms,
                                    "pur_price"=>$pur_price,   
                                    "avg_price"=>$avg_price,
                                    "description"=>$customer
                             );
         
                             $sv=$sv+($pur_price*$rr->qty);
                            
                             $qty -= $rr->qty;
                             
                             
                            }  
                           else
                            {
        
                            $a2[] = array(
                                    "id"=>$lid,
                                    "trance_id"=>$no,
                                    "trance_type"=>'CR_SALES',
                                    "item_code"=>$item,
                                    "out_quantity"=>$qty,
                                    "date"=>$date,
                                    "bc"=>$this->sd['bc'],
                                    "stores"=>$store,
                                    "ref_no"=>$ref_no,
                                    "batch_no"=>$rr->batch_no,
                                    "sal_price"=>$ms,
                                    "pur_price"=>$pur_price,   
                                    "avg_price"=>$avg_price,
                                    "description"=>$customer
                             );
                            
                             $sv=$sv+($pur_price*$qty);
                             
                             $qty = 0;
                            
                             }
                             
                            }   
                          
                             
                            
           
                    }
                    if(count($a2)){ $this->db->insert_batch($this->tb_trance, $a2);}
                    
                    return array("sv" => $sv);

        }   
    
    
    public function insert_subitem_batch_stock($item_code,$lid,$trance_no,$date,$store,$bc,$ref_no,$item_qty,$customer,$no)
    {
        $a2=array ();

        $sql="SELECT
               `sub_item_code`
               , `qty`
               , `purchase_price`
               , `avg_price`
           FROM
               `m_sub_item_list`
           INNER JOIN `m_items` ON (`m_items`.`code`=`m_sub_item_list`.`sub_item_code`)   
           WHERE `main_item`='$item_code' AND foc='0'";

         $query=$this->db->query($sql);
         $result=$query->result();

           foreach ($result as $r)
           {
               if(!empty($r->sub_item_code))
               {
               $qty=$r->qty;
               $pp=$r->purchase_price;
               $ap=$r->avg_price;
               $sub=$r->sub_item_code;

               $qt=$item_qty*$qty;
               
               
               $sql="SELECT `item_code`,SUM(in_quantity) - SUM(out_quantity) AS qty,batch_no,`pur_price`
                    FROM `t_item_movement`
                    WHERE `item_code`='".$r->sub_item_code."'
                    GROUP BY batch_no,item_code
                    HAVING qty>0
                    ORDER BY batch_no ASC";
 
            $qry=$this->db->query($sql);
            
            foreach ($qry->result() AS $rr)
            {
                
                if($qt > 0)
                    {
                        $sd="SELECT purchase_price,max_sales FROM m_items WHERE `code`='$sub'";
                        $qsd=$this->db->query($sd);
                        $s=$qsd->first_row();
                        $ms=$s->max_sales;
    
                
                        $sql="SELECT avg_price FROM m_items WHERE `code`='$sub'";
                        $q=$this->db->query($sql);
                        $r=$q->first_row();
                        $avg_price=$r->avg_price;
                        
                        $sqlo="SELECT `pur_price` FROM `t_batch` WHERE `item_code`='$sub' AND `batch_code`='$rr->batch_no' AND bc='".$this->sd['bc']."' GROUP BY batch_code";
                        $qryo=$this->db->query($sqlo);
                        $o=$qryo->first_row();
                        $pur_price=$o->pur_price;
 
                        if($qt > $rr->qty)
                            {

                            $a2[] = array(
                                    "id"=>$lid,
                                    "trance_id"=>$no,
                                    "trance_type"=>'CR_SALES',
                                    "item_code"=>$sub,
                                    "out_quantity"=>$rr->qty,
                                    "date"=>$date,
                                    "bc"=>$this->sd['bc'],
                                    "stores"=>$store,
                                    "ref_no"=>$ref_no,
                                    "batch_no"=>$rr->batch_no,
                                    "sal_price"=>$ms,
                                    "pur_price"=>$pur_price,   
                                    "avg_price"=>$avg_price,
                                    "description"=>$customer
                             );
         
                             $qt -= $rr->qty;
                            }  
                           else
                            {
                        
                            $a2[] = array(
                                    "id"=>$lid,
                                    "trance_id"=>$no,
                                    "trance_type"=>'CR_SALES',
                                    "item_code"=>$sub,
                                    "out_quantity"=>$qt,
                                    "date"=>$date,
                                    "bc"=>$this->sd['bc'],
                                    "stores"=>$store,
                                    "ref_no"=>$ref_no,
                                    "batch_no"=>$rr->batch_no,
                                    "sal_price"=>$ms,
                                    "pur_price"=>$pur_price,   
                                    "avg_price"=>$avg_price,
                                    "description"=>$customer
                             );
                                $qt = 0;
                             }
                             
                            }   
        
                    }

               }
           }
           
           if(count($a2)){ $this->db->insert_batch($this->tb_trance, $a2);	}
     
    }     
    
       

    public function load(){
	$this->db->select(array('id','no', 'date', 'name', 'outlet_name', 'customer', 'sales_ref', 'is_cancel','pay_amount', 'advance', 'credit', 'cheque', 'ref_no', 'memo', 'discount', 'balance', 'stores', 'so_no', 'r_margin', 'c_margin', 'posting','is_request','is_reject','is_approve','feed_back'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
	
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
	
	
	   $this->db->select(array('id','trans_code','receipt_no','total','balance','settle_amount'));
	    $this->db->where($this->tb_advance.".trans_code" ,  'CR_SALES');
	    $this->db->where($this->tb_advance.".id" ,  $a["sum"]->id);
	    $a['adv2'] = $this->db->get($this->tb_advance)->result();
        
	if(isset($a["sum"]->id)){
	    $this->db->select(array('quantity', $this->tb_det.'.cost', $this->tb_det.'.item_code', $this->tb_det.'.discount', $this->tb_det.'.discount_pre', $this->tb_items.'.description',$this->tb_items.'.special_discount', $this->tb_items.'.is_measure'));
	    $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
	    
	    $this->db->select(array('cheque_no', 'account_no', 'cheque_amount', $this->tb_cheque.'.bank', 'bank_branch', 'realize_date', 'description', 'bank_name'));
	    $this->db->join($this->tb_bank, $this->tb_bank.".code = ".$this->tb_cheque.".bank_branch", "INNER");
	    $this->db->join($this->tb_bank_branch, $this->tb_bank_branch.".BranchID = ".$this->tb_cheque.".bank", "INNER");
	    $this->db->where($this->tb_cheque.".id", $a["sum"]->id);
	    $this->db->where($this->tb_cheque.".module", 'CR_SALES');
	    $a['chq'] = $this->db->get($this->tb_cheque)->result();
	    
	    $this->load->model('m_customer');
	    $a['levels'] = $this->m_customer->get_levels($a['sum']->customer);
	    
	   /* $this->db->select(array('cr_trance_no','dr_trance_amount'));
	    $this->db->where($this->tb_adv_trance.".dr_trance_code" ,  'CR_SALE');
	    $this->db->where($this->tb_adv_trance.".dr_trance_no" ,  $a["sum"]->no);
	    $a['adv2'] = $this->db->get($this->tb_adv_trance)->result();
	    
	    if(isset($a['adv1']->dr_trance_amount)){
	    $this->db->select(array("SUM(cr_trance_amount) AS advance"));
	    $this->db->where($this->tb_adv_trance.".dr_trance_no",$a["adv1"]->cr_trance_no);
	    $a['adv3'] = $this->db->get($this->tb_adv_trance)->result();
	    }
	    else{
		$a['adv3']="";
	    }*/
	    
	    
	}
	
        echo json_encode($a);
    }
    
    public function load_return(){
	$this->db->select(array('id', 'date', 'name', 'outlet_name', 'customer', 'sales_ref', 'is_cancel','pay_amount', 'advance', 'credit', 'cheque', 'ref_no', 'memo', 'discount', 'balance', 'stores', 'so_no', 'r_margin', 'c_margin', 'posting','no'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
	
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){
	    /*$this->db->select(array('quantity', $this->tb_det.'.cost', $this->tb_det.'.item_code', $this->tb_det.'.discount', $this->tb_det.'.discount_pre', $this->tb_items.'.description', $this->tb_items.'.is_measure'));
	    $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();*/
            
            $sql="SELECT 
                    SUM(
                      `t_sales_trance`.`in_qty`) - SUM(`t_sales_trance`.`out_qty`)
                     AS quantity,
                    `t_sales_det`.`cost`,
                    `t_sales_trance`.`item_code`,
                    `t_sales_det`.`discount`,
                    `t_sales_det`.`discount_pre`,
                    `m_items`.`description`,
                    `m_items`.`is_measure`,
                    `t_sales_det`.`foc` 
                  FROM
                    `t_sales_trance` 
                    INNER JOIN `t_sales_sum`
                      ON (
                        `t_sales_trance`.`in_no` = `t_sales_sum`.`no`
                      ) 
                    INNER JOIN `t_sales_det`
                      ON (
                        `t_sales_det`.`id` = `t_sales_sum`.`id` 
                        AND `t_sales_det`.`item_code` = `t_sales_trance`.`item_code`
                      ) 
                    INNER JOIN `m_items` 
                      ON t_sales_trance.`item_code` = `m_items`.`code` 
                  WHERE `in_no`= '".$a["sum"]->no."' AND `pay_type`='1'
                  GROUP BY `t_sales_trance`.`item_code`,
                    `t_sales_trance`.`cus_id`,`pay_type`
                  HAVING quantity > 0 ";
            
            $qry=$this->db->query($sql);
            $a['det'] = $qry->result();
            

	    $this->load->model('m_customer');
	    $a['levels'] = $this->m_customer->get_levels($a['sum']->customer);
	}
	
        echo json_encode($a);
    }
    
    public function delete(){
	$a = true;
        
        $this->db->where("id", $_POST['id']);
        $this->db->where("trance_type", "CR_SALES");
        if(! $this->db->delete($this->tb_trance)){
            $a = false;
        }
	
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'CR_SALES');
	if(! $this->db->delete($this->tb_acc_trance)){
	    $a = false;
	}
        
	$this->db->where("id", $_POST['id']);
	$this->db->where("module", "CR_SALES");
	if(! $this->db->update($this->tb_cheque, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
        $this->db->where('id', $_POST['id']);
        $this->db->where('module', 'CR_SALES'); 
	if(!$this->db->delete($this->tb_adv_trance)){
           $a = false; 
        }
        
        $this->db->where("id", $_POST['id']);
	$this->db->where("trans_code", "CR_SALES");
	if(! $this->db->update($this->tb_advance, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
        $this->db->where('trance_no', $_POST['id']);
	$this->db->where('trance_code', 'CR_SALE');
	$this->db->where('pay_type', '1');
	if(! $this->db->delete($this->tb_sales_trance)){
            $a = false;
        }
        
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
	$this->load->model('account');
	$this->account->delete($_POST['id'], "CR_SALES");
	
	$this->load->database("default", true);
	
        echo $a;
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'CR_SALES');
	$this->db->delete($this->tb_acc_trance);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('trance_type', 'CR_SALES');
	$this->db->delete($this->tb_trance);
	
	$this->db->where("id", $_POST['hid']);
	$this->db->where("module", 'CR_SALES');
	$this->db->delete($this->tb_cheque);
	
        $this->db->where("id", $_POST['hid']);
	$this->db->where("trans_code", 'CR_SALES');
	$this->db->delete($this->tb_advance);
        
        $this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'CR_SALES');        
	$this->db->delete($this->tb_adv_trance);
        
        $this->db->where('trance_no', $_POST['hid']);
	$this->db->where('trance_code', 'CR_SALE');
	$this->db->where('pay_type', '1');
	$this->db->delete($this->tb_sales_trance);
        
	$this->load->model('account');
	$this->account->delete($_POST['hid'], "CR_SALES");
	
	$this->load->database("default", true);
    }
    
    public function print_view(){
        $this->load->library('useclass');
	$a['title'] = 'Sales';
        
        $this->db->select(array(
            'code',
            'description',
            'quantity',
            $this->tb_det.'.cost',
	    'discount',
            '(quantity * '.$this->tb_det.'.cost) AS total'
        ));
        $this->db->where($this->tb_det.'.id', $_GET['id']);
        $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
        $query = $this->db->get($this->tb_det);
        
        if($query->num_rows){
            $this->db->select(array("no", "date", "name", "outlet_name", "is_cancel", "discount","advance","cheque"));
            $this->db->where('id', $_GET['id']);
            $this->db->limit(1);
	    $this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
            $query2 = $this->db->get($this->tb_sum);
            
            $result = $query->result();
            $result2 = $query2->first_row();
	    
	    $a['cancel'] = $result2->is_cancel;
            
            $t = $ct = $dt = 0;
            foreach($result as $rr){
                $t += $rr->total;
                $ct += $rr->quantity;
		$dt += $rr->discount;
            }
	    
            $code = array("data"=>"Item Code", "style"=>"text-align: left; width:80px", "chalign"=>"text-align: left;");
            $name = array("data"=>"Item Name","style"=>"text-align: left;", "chalign"=>"text-align: left;");
            $qun = array("data"=>"Qty", "style"=>"text-align: right; width:80px ", "chalign"=>"text-align: right;");
            $cost = array("data"=>"Price", "style"=>"text-align: right; width:80px ", "chalign"=>"text-align: right;");
            $discount = array("data"=>"Dis(%)", "style"=>"text-align: right; width:80px ", "chalign"=>"text-align: right;");
            $total = array("data"=>"Total", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
            
            $heading = array($code, $name, $qun, $cost, $discount, $total);//
            
            $code = array("data"=>"code", "total"=>false, "format"=>"text");
            $name  = array("data"=>"description", "total"=>false, "format"=>"text");
            $qun  = array("data"=>"quantity", "total"=>false, "format"=>"number");
            $cost  = array("data"=>"cost", "total"=>false, "format"=>"amount");
            $discount  = array("data"=>"discount", "total"=>false, "format"=>"amount");
            $total  = array("data"=>"total", "total"=>false, "format"=>"amount");
            
            $field = array($code, $name, $qun, $cost, $discount, $total);//
            
            $page_rec = 6;
            
	    $inv_info = "<table style='width : 100%; font-size : 12px;' border='0'>
		<tr>
		    <td style='width:100px;'>Invoice No</td>
		    <td>: ".$result2->no."</td>
		    <td rowspan='2' style='width:100px;' valign='top'>Customer</td>
		    <td rowspan='2' style='width:250px;' valign='top'>: ".$result2->outlet_name." (".$result2->name.")</td>
		</tr><tr>
		    <td>Date</td>
		    <td>: ".$result2->date."</td>
		</tr>
	    </table>";
	    
	    $page_end = "<br /><table style='width : 100%; font-size : 12px; font-weight : bold; font-family : Courier;' border='0'>
		<tr>
		    <td style='text-align: right;'>Total Amount :</td>
		    <td style='width:100px; text-align: right;'>".number_format($t, 2, '.', ',')."</td>
		</tr>
		<tr>
		    <td style='text-align: right;'>Discount :</td>
		    <td style='text-align: right;'>".number_format($result2->discount, 2, '.', ',')."</td>
		</tr>
		<tr>
		    <td style='text-align: right;'>Item Discount :</td>
		    <td style='text-align: right;'>".number_format($dt, 2, '.', ',')."</td>
		</tr>
		<tr>
		    <td style='text-align: right;'>Paid Amount :</td>
		    <td style='text-align: right;'>".number_format($result2->advance+$result2->cheque, 2, '.', ',')."</td>
		</tr>
		<tr>
		    <td style='text-align: right;'>Net Amount :</td>
		    <td style='text-align: right; border-top: 1px solid #000; border-bottom: 2px solid #000;'>".number_format(($t - ($result2->discount + $dt+$result2->advance+$result2->cheque)), 2, '.', ',')."</td>
		</tr>
	    </table>";
	    
	    $sig = "<table style='width: 80%; font-size : 12px; font-weight : normal; font-family : Courier;' >
		<tr>
		    <td style='text-align : center;'>.....................</td>
		    <td style='text-align : center;'>.....................</td>
		    <td style='text-align : center;'>.....................</td>
		</tr><tr>
		    <td style='text-align : center;'>Cashier</td>
		    <td style='text-align : center;'>Manager</td>
		    <td style='text-align : center;'>Customer</td>
		</tr>
	    </table>";
	    
            $header  = array("data"=>$this->useclass->r_header("CREDIT INVOICE <hr />", $inv_info), "style"=>"font-weight: normal; font-family: Courier;");
            $footer  = array("data"=>"<div style='text-align: right; font-size: 12px;'>".$sig."</div><hr />Soft-Master Technologies (pvt) LTD / 0812-204130, 0773-889082/3. / ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
            $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
            $data = array(
                        "dbtem"=>$this->useclass->report_style(),
                        "data"=>$result,
                        "field"=>$field,
                        "heading"=>$heading,
                        "page_rec"=>$page_rec,
                        "height"=>$this->h,
                        "width"=>$this->w,
                        "header"=>45,
                        "footer"=>20,
                        "header_txt"=>$header,
                        "footer_txt"=>$footer,
                        "page_no"=>$page_no,
                        "header_of"=>false,
			"page_end"=>$page_end
                        );
            //print_r($data); exit;
            $this->load->library('greport', $data);
                
            $a['view'] = $this->greport->_print();
        }else{
            $a['view'] = "No Record";
        }
        
        return $a;
    }
}