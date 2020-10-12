<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_ad_refund extends CI_Model {
    
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
    private $tb_subitem;
    private $tb_rep;
    private $tb_store;
    private $tb_adv_trance;
    private $tb_adv_ref;
    private $h = 140;
    private $w = 216;
    
    function __construct(){
	parent::__construct();
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_advance_refund'];
	$this->tb_sum_ad = $this->tables->tb['t_advance_pay_sum'];
	$this->tb_det = $this->tables->tb['t_advance_refund_det'];
	$this->tb_cus = $this->tables->tb['m_customer'];
	$this->tb_adv_trance = $this->tables->tb['t_advance_pay_trance'];
	
	
    }
    
    public function base_details(){
	
        
	$a['max_no'] = $this->max_no();
	
	$a['sd'] = $this->sd;
	
	
	return $a;
    }
    
    
    public function select(){
        
        $sql="SELECT
                `t_advance_pay_sum`.`no`	
                ,SUM(`t_advance_pay_trance`.`cr_trance_amount`-`t_advance_pay_trance`.`dr_trance_amount`) AS balance
            FROM
               `t_advance_pay_trance`
                INNER JOIN `t_advance_pay_sum`
                    ON (`t_advance_pay_trance`.`cr_trance_no` = `t_advance_pay_sum`.`no`)
                INNER JOIN `t_advance_pay_det`
                    ON (`t_advance_pay_det`.`id` = `t_advance_pay_sum`.`id`)
            WHERE `t_advance_pay_trance`.`customer`='".$_POST['cus']."' 
            GROUP BY `t_advance_pay_trance`.`customer`,`cr_trance_no`
            HAVING balance>0";
        
        
        $query = $this->db->query($sql);
        $result = $query->result();
        echo "<select id='advance' name='advance'>";
        echo "<option value='0' id='advance_0'>---</option>";
        $x=1;
        foreach($result as $r){
            echo "<option value='".$r->no."' id='advance_".$x++."' title='".$r->ref_no."'>".$r->no."</option>";
        }
        echo "</select>";

    }
    
    public function select2(){
	        
    $sql="SELECT
                `t_advance_pay_sum`.`no`
                , `t_advance_pay_sum`.cash_amount+`t_advance_pay_sum`.cheque_amount AS amount
                ,SUM(`t_advance_pay_trance`.`cr_trance_amount`-`t_advance_pay_trance`.`dr_trance_amount`) AS AdvanceBalance
            FROM
               `t_advance_pay_trance`
                INNER JOIN `t_advance_pay_sum`
                    ON (`t_advance_pay_trance`.`cr_trance_no` = `t_advance_pay_sum`.`no`)
                INNER JOIN `t_advance_pay_det`
                    ON (`t_advance_pay_det`.`id` = `t_advance_pay_sum`.`id`)
            WHERE `t_advance_pay_trance`.`cr_trance_no`='".$_POST['no']."'
            GROUP BY `t_advance_pay_trance`.`customer`,`cr_trance_no`
            HAVING AdvanceBalance>0";    
        
        
    
    $query=$this->db->query($sql);
    $a['ad'] = $query->first_row();
    
             echo json_encode($a);
    
	
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
	
	//echo $this->db->get($this->tb_cheque)->num_rows;	
    }
    
    public function save(){

        $this->db->trans_start();
        
        $a["no"]='';
        
        $a = array(
	    "date"=>$_POST['date'],
	    "ref_no"=>$_POST['ref_no'],
	    "customer"=>$_POST['customer'],
	    "advance_pay_no"=>$_POST['advance'],
	    "amount"=>$_POST['amount'],
	    "balance"=>$_POST['ad_balance'],
	    "cash"=>$_POST['cash_amount'],
	    "cheque"=>$_POST['cheque'],
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc']
	    
	);
       
         
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

         $cheque_no=$a = $a2 = $a_det =array();
        
        for($i=0; $i<10; $i++){
		if($_POST['qbbh1_'.$i] != "0" && (double)$_POST['q31_'.$i] > 0){
		    $a_det[] = array(
			"id"=>$lid,
			"b_branch"=>$_POST['qbbh1_'.$i],
			"b_bank"=>$_POST['qbbh1_'.$i],
			"cheque_no"=>$_POST['q11_'.$i],
			"acc_no"=>$_POST['q21_'.$i],
			"r_date"=>$_POST['q41_'.$i],
			"cheque_amount"=>$_POST['q31_'.$i]
			
		    );
                    $cheque_no[] = $_POST['q11_'.$i];

            }      
        }

       $a2 = array(
            "id"=>$lid,
	    "module"=>"AD_REFUND",
	    "customer"=>$_POST['customer'],
	    "date"=>$_POST['date'],
	    "dr_trance_code"=>'AD_REFUND',
	    "dr_trance_no"=>$_POST['id'],
	    "dr_trance_amount"=>$_POST['total'] ,
	    "cr_trance_code"=>'ADPAY',
	    "cr_trance_no"=>$_POST['advance'],
	    "cr_trance_amount"=> 0,
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc']
	    
	);
        
        
       $this->db->insert($this->tb_adv_trance,$a2);
       if(count($a_det)){$this->db->insert_batch($this->tb_det, $a_det);}
	    
        
       $config = array(
	    "id" => $lid,
	    "no" =>$_POST['id'],
	    "type" => "ADRE",
	    "date" => $_POST['date'],
	    "ref_no" => $_POST['ref_no']
	);
	
	$this->load->model('account');
	$this->account->set_data($config);
	    
	    $des = "Sale Customer : ".$_POST['customer'];

	    if((double)$_POST['ad_balance'] > 0){ $this->account->set_value($des, $_POST['total'], "dr", "advance"); }
            
            if($_POST['cash_amount']!=0.00)
            {    
	    if((double)$_POST['ad_balance'] > 0){ $this->account->set_value($des, $_POST['cash_amount'], "cr", "cash_in_hand"); }
            }
            if((double)$_POST['cheque'] > 0){
	    $this->account->set_value($des, $_POST['cheque'], "cr", "issued_cheque", join(", ", $cheque_no));
            }
            if((double)$_POST['cheque'] != 0.00){
	    for($i=0; $i<10; $i++){

		if($_POST['qbbh1_'.$i] != '' && (double)$_POST['q31_'.$i] > 0){
                    
		    $this->account->set_cheque('issue', $_POST['qbbh1_'.$i], $_POST['qbbh1_'.$i], $_POST['q21_'.$i], $_POST['q11_'.$i], $_POST['q41_'.$i], $_POST['q31_'.$i]);
		}
	      }
            }
            
	    
	    $this->account->send();
            
	    $this->db->trans_complete();
	
	redirect(base_url()."?action=t_ad_refund&print=".$lid);

    }
    
    public function check_ref(){
	$q = $this->db
		->where('ref_no', $_POST['ref'])
		->limit(1)
		->get($this->tb_sum);
	
	$a['row'] = $q->num_rows;
	if($q->num_rows){$a['no'] = $q->row()->no;}
	
	echo json_encode($a);
    }
    
    public function load(){
        
        $sql="SELECT db_name FROM db WHERE `code`=1";
        $qry=$this->db->query($sql);
        $distribution=$qry->first_row();
        $distribution=$distribution->db_name;
        
        
        $sql="SELECT db_name FROM db WHERE `code`=2";
        $qry = $this->db->query($sql);
        $account=$qry->first_row();
        $account=$account->db_name;
        
	$this->db->select(array('id','date','ref_no','customer','advance_pay_no','amount','balance','name','cash','cheque'));
	$this->db->join($this->tb_cus,$this->tb_cus.".code=".$this->tb_sum.".customer");
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
        if(isset($a["sum"]->id)){
	    
            $sqlc="SELECT
                `t_advance_refund_det`.`cheque_no`,
                `t_advance_refund_det`.`acc_no`,
                `t_advance_refund_det`.`cheque_amount`,
                `t_advance_refund_det`.b_bank,
                `t_advance_refund_det`.`r_date`,
                 b_branch,
                 m_accounts.`description`
            FROM
                $distribution.`t_advance_refund_det`
            INNER JOIN $account.`m_accounts` ON 
            `t_advance_refund_det`.b_bank= m_accounts.`code`
            WHERE t_advance_refund_det.id='".$a["sum"]->id."'";
            
            $qry=$this->db->query($sqlc);
            $a['cheque']=$qry->result();
 
        }
        
        
        
        $s='';
        
        $sqlo="SELECT
                `dr_trance_no`
            FROM
                `t_advance_pay_trance`
            WHERE `dr_trance_code`='ADPAY'
            GROUP BY `dr_trance_no`";
        
        $query=$this->db->query($sqlo);
        
        $s= "<select id='advance' name='advance'>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->dr_trance_no."' value='".$r->dr_trance_no."'>".$r->dr_trance_no."</option>";
        }
        $s .="<select>";
        
        $a["inv"]= $s;
        
	
        echo json_encode($a);
    }
    
    public function load_subitem(){
        
	    $this->db->select(array($this->tb_subitem.'.sub_item_code', $this->tb_items.'.description', $this->tb_subitem.'.foc',$this->tb_items.'.cost_price'));
	    $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_subitem.".sub_item_code", "INNER");
	    $this->db->where("main_item", $_POST['id']);
	    $a['det'] = $this->db->get($this->tb_subitem)->result();

        echo json_encode($a);
    }
    
    
    
    
    public function delete(){
	$a = true;

	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'AD_REFUND');
	if(! $this->db->delete($this->tb_acc_trance)){
	    $a = false;
	}

        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
	$this->load->model('account');
	$this->account->delete($_POST['id'], "ADRE", $this->sd['db_code']);
	
	$this->load->database($this->sd['db'], true);
	
        echo $a;
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'AD_REFUND');
	$this->db->delete($this->tb_acc_trance);
	
	$this->load->model('account');
	$this->account->delete($_POST['hid'], "ADRE", $this->sd['db_code']);
	
	$this->load->database($this->sd['db'], true);
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
            '(quantity * '.$this->tb_det.'.cost) - discount AS total'
        ));
        $this->db->where($this->tb_det.'.id', $_GET['id']);
        $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
        $query = $this->db->get($this->tb_det);
        
        if($query->num_rows){
            $this->db->select(array("no", "date", "name", "outlet_name", "is_cancel", "discount"));
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
            $qun = array("data"=>"Qty", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $cost = array("data"=>"Price", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $discount = array("data"=>"Dis", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
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
		</tr><tr>
		    <td style='text-align: right;'>Discount :</td>
		    <td style='text-align: right;'>".number_format($result2->discount, 2, '.', ',')."</td>
		</tr><tr>
		    <td style='text-align: right;'>Item Discount :</td>
		    <td style='text-align: right;'>".number_format($dt, 2, '.', ',')."</td>
		</tr><tr>
		    <td style='text-align: right;'>Net Amount :</td>
		    <td style='text-align: right; border-top: 1px solid #000; border-bottom: 2px solid #000;'>".number_format(($t - ($result2->discount + $dt)), 2, '.', ',')."</td>
		</tr>
	    </table>";
	    
	    $sig = "<table style='width: 80%; font-size : 12px; font-weight : normal; font-family : Courier;' >
		<tr>
		    <td style='text-align : center;'>.....................</td>
		    <td style='text-align : center;'>.....................</td>
		    <td style='text-align : center;'>.....................</td>
		</tr><tr>
		    <td style='text-align : center;'>Authorized by</td>
		    <td style='text-align : center;'>Branch Manager</td>
		    <td style='text-align : center;'>Customer</td>
		</tr>
	    </table>";
	    
            $header  = array("data"=>$this->useclass->r_header("CASH INVOICE <hr />", $inv_info), "style"=>"font-weight: normal; font-family: Courier;");
            $footer  = array("data"=>"<div style='text-align: left; font-size: 12px;'>".$sig."</div><hr />Soft-Master Technologies (pvt) LTD / 0812-204130, 0773-889082/3. / ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
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
                    