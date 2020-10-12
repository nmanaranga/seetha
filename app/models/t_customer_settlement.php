<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_customer_settlement extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_cus;
    private $h = 140;
    private $w = 216;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_customer_settle_sum'];
	$this->tb_det = $this->tables->tb['t_customer_settle_det'];
	$this->tb_trance = $this->tables->tb['t_customer_acc_trance'];
	$this->tb_cus = $this->tables->tb['m_customer'];
    }
    
    public function base_details(){
	$this->load->model('m_customer');
	$this->load->model('m_area');
	
	$a['cus'] = $this->m_customer->select('fielter');
	$a['max_no'] = $this->max_no();
	$a['area'] = $this->m_area->select('name', 'width : 300px;');
	$a['sd'] = $this->sd;
	
	return $a;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['branch']);
	$this->db->select_max("no");
	
	return $this->db->get($this->tb_sum)->first_row()->no+1;
    }
    
    public function save(){
	$this->db->trans_start();
	
	$a = array(
	    "date"=>$_POST['date'],
	    "ref_no"=>$_POST['ref_no'],
	    "memo"=>$_POST['memo'],
	    "customer"=>$_POST['customer'],
	    "dbalance"=>$_POST['dbalance'],
	    "cbalance"=>$_POST['cbalance'],
	    "type_pay"=>$_POST['type_pay'],
	    "no_pay"=>$_POST['no_pay'],
	    "total_pay"=>$_POST['total_pay'],
	    "paid_pay"=>$_POST['paid_pay'],
	    "balance_pay"=>$_POST['balance_pay'],
	    "sett_pay"=>$_POST['sett_pay'],
	    "bc"=>$this->sd['branch'],
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
	    $lid = $_POST['hid'];$a["no"] = $_POST['id'];
	    $this->set_delete();
	}
	
	$sql_sett = array();
	$sql_sett[] = array(
	    "id"=>$lid,
	    "module"=>"SETTLEMENT",
	    "customer"=>$_POST['customer'],
	    "dr_trnce_code"=>$_POST['type_pay'],
	    "dr_trnce_no"=>$_POST['no_pay'],
	    "cr_trnce_code"=>"SETTLEMENT",
	    "cr_trnce_no"=>$a["no"],
	    "dr_amount"=>$_POST['sett_pay'],
	    "cr_amount"=>0,
	    "bc"=>$this->sd['branch'],
	    "oc"=>$this->sd['oc'],
	    "date"=>$_POST['date']
	);
	
	$sql_det = array();
        for($i=0; $i<25; $i++){
            if((double)$_POST['d5_'.$i] > 0){
                $sql_det[] = array(
		    "id"=>$lid,
		    "trance_type"=>$_POST['d0_'.$i],
		    "trance_no"=>$_POST['d1_'.$i],
		    "total"=>$_POST['d2_'.$i],
		    "paid"=>$_POST['d3_'.$i],
		    "balance"=>$_POST['d4_'.$i],
		    "settle"=>$_POST['d5_'.$i]
		);
		
		$sql_sett[] = array(
		    "id"=>$lid,
		    "module"=>"SETTLEMENT",
		    "customer"=>$_POST['customer'],
		    "dr_trnce_code"=>$_POST['d0_'.$i],
		    "dr_trnce_no"=>$_POST['d1_'.$i],
		    "cr_trnce_code"=>"SETTLEMENT",
		    "cr_trnce_no"=>$a["no"],
		    "dr_amount"=>0,
		    "cr_amount"=>$_POST['d5_'.$i],
		    "bc"=>$this->sd['branch'],
		    "oc"=>$this->sd['oc'],
		    "date"=>$_POST['date']
		);
	    }
        }
	
	if(count($sql_det)){ $this->db->insert_batch($this->tb_det, $sql_det); }
	if(count($sql_sett)){ $this->db->insert_batch($this->tb_trance, $sql_sett); }
	
	$this->db->trans_complete();
	
	redirect(base_url()."?action=t_customer_settlement");//&print=.$lid
    }
    
    public function load(){
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['branch']);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
	}
        
        echo json_encode($a);
    }
    
    public function delete(){
	$a = true;
        
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'SETTLEMENT');
        if(! $this->db->delete($this->tb_trance)){
            $a = false;
        }
	
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
        echo $a;
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'SETTLEMENT');
	$this->db->delete($this->tb_trance);
    }
    
    public function print_view(){
        $this->load->library('useclass');
	$a['title'] = 'Customer Receipt';
        
        $this->db->where($this->tb_det.'.id', $_GET['id']);
        $query = $this->db->get($this->tb_det);
        
        if($query->num_rows){
            $this->db->where('id', $_GET['id']);
            $this->db->limit(1);
            $query2 = $this->db->get($this->tb_sum);
            
            $result = $query->result();
            $result2 = $query2->first_row();
	    
	    $a['cancel'] = $result2->is_cancel;
            
            $bt = $ct = $tt = 0;
            foreach($result as $rr){
                $bt += $rr->balance;
                $ct += $rr->paid;
                $tt += $rr->total;
            }
	    
	    $this->db->select(array($this->tb_bank.'.bank_name', $this->tb_bank_branch.".description", "cheque_no", "account_no", "cheque_amount", "realize_date"));
	    $this->db->where("id", $_GET['id']);
	    $this->db->join($this->tb_bank, $this->tb_bank.".code = ".$this->tb_cheque.".bank", "INNER");
	    $this->db->join($this->tb_bank_branch, $this->tb_bank_branch.".code = ".$this->tb_cheque.".bank_branch", "INNER");
	    $this->db->limit(1);
	    $query3 = $this->db->get($this->tb_cheque);
	    
	    $page_end = "<br />";
	    if($query3->num_rows){
		$result3 = $query3->first_row();
		
		$page_end .= "<table border='0'>";
		    $page_end .= "<tr>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier;'>Realize Date,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier;'>Bank,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier;'>Branch,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier;'>Cheque No,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier;'>Account No,</td>
			<td style='font-weight: bold; font-size : 11px; font-family : Courier;'>Amount,</td>
		    </tr>";
		    $page_end .= "<tr>
			<td style='font-size : 11px; font-family : Courier;'>".$result3->realize_date."&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td style='font-size : 11px; font-family : Courier;'>".$result3->bank_name."&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td style='font-size : 11px; font-family : Courier;'>".$result3->description."&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td style='font-size : 11px; font-family : Courier;'>".$result3->cheque_no."&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td style='font-size : 11px; font-family : Courier;'>".$result3->account_no."&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td style='font-size : 11px; font-family : Courier;'>".$result3->cheque_amount."&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    </tr>";
		$page_end .= "</table>";
	    }
	    
	    $page_end .= "<div style='text-align : right; padding-top : 25px;'>...........................<br />Signature&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
            
            $r = new stdClass();
            $r->purchase_no = array("data"=>"Total", "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            $r->total = array("data"=>$tt, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            $r->balance = array("data"=>$bt, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            $r->paid = array("data"=>$ct, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            //$r->total = array("data"=>$t, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            
            array_push($result, $r);
            
            $code = array("data"=>"Purchase No", "style"=>"text-align: left;", "chalign"=>"text-align: left;");
            $name = array("data"=>"Total Amount","style"=>"text-align: right;", "chalign"=>"text-align: right;");
            $qun = array("data"=>"Current Balance", "style"=>"text-align: right;", "chalign"=>"text-align: right;");
            $cost = array("data"=>"Payment", "style"=>"text-align: right;", "chalign"=>"text-align: right;");
            //$discount = array("data"=>"Discount(%)", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            //$total = array("data"=>"Total", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
            
            $heading = array($code, $name, $qun, $cost);//, $discount, $total
            
            $code = array("data"=>"purchase_no", "total"=>false, "format"=>"text");
            $name  = array("data"=>"total", "total"=>false, "format"=>"amount");
            $qun  = array("data"=>"balance", "total"=>false, "format"=>"amount");
            $cost  = array("data"=>"paid", "total"=>false, "format"=>"amount");
            //$discount  = array("data"=>"discount", "total"=>false, "format"=>"amount");
            //$total  = array("data"=>"total", "total"=>false, "format"=>"amount");
            
            $field = array($code, $name, $qun, $cost);//, $discount, $total
            
            $page_rec = 9;
            
            $header  = array("data"=>$this->useclass->r_header("Customer Receipt - ".$_GET['id']." | Date : ".$result2->date), "style"=>"");
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
                        "header"=>30,
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
        }else{
            $a['view'] = "No Record";
        }
        
        return $a;
    }
}