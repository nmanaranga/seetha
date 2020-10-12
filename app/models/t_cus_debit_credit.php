<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_cus_debit_credit extends CI_Model {
    
    private $sd;
    private $mtb;
    private $tb_acc_trance;
    private $tb_cus;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	
	$this->mtb = $this->tables->tb['t_customer_debit_credit_note'];
	$this->tb_acc_trance = $this->tables->tb['t_customer_acc_trance'];
	$this->tb_cus = $this->tables->tb['m_customer'];
    }
    
    public function base_details(){
	$a['accounts'] = "<select></select>";
	$a['max_no'] = $this->max_no();
	
	return $a;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['bc']);
	$this->db->select_max("no");
	
	return $this->db->get($this->mtb)->first_row()->no+1;
    }
    
    public function auto_com(){
	$acc = $this->load->database("account", true);
	
	$acc->like("description", $_GET['q']);
	$acc->or_like("code", $_GET['q']);
	$acc->limit(25);
	
	foreach($acc->get($this->mtb)->result() as $r){
	    echo $r->code."|".$r->description."\n";
	}
    }
    
    public function save(){
	$a = array(
	    "type"=>$_POST['type'],
	    "amount"=>$_POST['amount'],
	    "custmer"=>$_POST['customer'],
	    "date"=>$_POST['date'],
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc']
	);
	
	if($_POST['hid'] == "" || $_POST['hid'] == 0){
	    $a["no"] = $this->max_no();
	    $this->db->insert($this->mtb, $a);
	    $lid = $this->db->insert_id();
	}else{
	    $this->db->where("id", $_POST['hid']);
	    $this->db->limit(1);
	    $this->db->update($this->mtb, $a);
	    $lid = $_POST['hid']; $a['no'] = $_POST['id'];
	    $this->set_delete();
	}
	
	if($_POST['type'] == 1){
	    $cr_amount = 0; $dr_amount = $_POST['amount'];
	    $type = "DEBIT_NOTE";
	}elseif($_POST['type'] == 2){
	    $cr_amount = $_POST['amount']; $dr_amount = 0;
	    $type = "CREDIT_NOTE";
	}
	
	$a1 = array(
	    "id"=>$lid,
	    "module"=>'D_C_NOTE',
	    "customer"=>$_POST['customer'],
	    "dr_trnce_code"=>$type,
	    "dr_trnce_no"=>$a['no'],
	    "cr_trnce_code"=>$type,
	    "cr_trnce_no"=>$a['no'],
	    "cr_amount"=>$cr_amount,
	    "dr_amount"=>$dr_amount,
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc'],
	    "date"=>$_POST['date']
	);
	
	$this->db->insert($this->tb_acc_trance, $a1);
	
	redirect(base_url()."?action=t_cus_debit_credit");
    }
    
    public function load(){
	$this->db->select(array('no', 'date', 'name', 'outlet_name', 'custmer', 'amount', 'type','is_cancel'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->mtb.".custmer", "INNER");
	
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->mtb)->first_row();
	
	echo json_encode($a);
    }
    
    public function delete(){
	$a = true;
	
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'D_C_NOTE');
	if(! $this->db->delete($this->tb_acc_trance)){
	    $a = false;
	}
        
        $this->db->where("no", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->mtb, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
	
        echo $a;
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'D_C_NOTE');
	$this->db->delete($this->tb_acc_trance);
    }
    
    
    public function print_view(){
	

        $this->load->library('useclass');
	$a['title'] = 'Customer Debit Credit Note';
	$a['cancel'] = '1';
        
        $this->db->select(array('no', 'date', 'name', 'outlet_name', 'custmer', 'amount', 'type','is_cancel'));
        $this->db->where('no', $_GET['id']);
        $this->db->where('bc', $this->sd['bc']);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->mtb.".custmer", "INNER");
	
        $this->db->limit(1);
        $query = $this->db->get($this->mtb);

        
        if ($query->num_rows) {
            $result = $query->result();
         
            
            $no = array("data" => "No", "style" => "width : 50px;text-align:right", "chalign" => "text-align: right;");
            $cus = array("data" => "Customer", "style" => "width : 400px;text-align: left;", "chalign" => "text-align: left;");
            $date = array("data" => "Date", "style" => "text-align: right", "chalign" => "text-align: right;");
	    $amount = array("data" => "Amount", "style" => "text-align: right", "chalign" => "text-align: right;");


            $heading = array($date,$no,$cus,$amount);


            $no = array("data" => "no", "total" => false, "format" => "text");
            $cus = array("data" => "name", "total" => false, "format" => "text");
	    $date = array("data" => "date", "total" => false, "format" => "text");
            $amount = array("data" => "amount", "total" => false, "format" => "amount");


            $field = array($date,$no,$cus,$amount);

	    
	    $sig = "<table style='width: 80%; font-size : 12px; font-weight : normal; font-family : Courier;' >
		<tr>
		    <td style='text-align : center;'>.....................</td>
		    <td style='text-align : center;'>.....................</td>
		    <td style='text-align : center;'>.....................</td>
		</tr><tr>
		    <td style='text-align : center;'>Prepared By</td>
		    <td style='text-align : center;'>Branch Manager</td>
		    <td style='text-align : center;'>Accountant</td>
		</tr>
	    </table>";	    
	    
            $page_rec = 25;
                                       
            
            $header = array("data"=>$this->useclass->r_header("CUSTOMER DEBIT CREDIT NOTE <hr />"), "style"=>"font-weight: normal; font-family: Courier;");
            $footer  = array("data"=>"<div style='text-align: left; font-size: 12px;'>".$sig."</div><hr />Soft-Master Technologies (pvt) LTD / 0812-204130, 0773-889082/3. / ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
            $page_no = array("style" => "font-size: 11px; font-family: Arial;", "vertical" => "buttom", "horizontal" => "right");

            $data = array(
                "dbtem" => $this->useclass->report_style(),
                "data" => $result,
                "field" => $field,
                "heading" => $heading,
                "page_rec" => $page_rec,
                "height" => 90,
                "width" => 180,
                //"height" => 155,
                //"width" => 210,
                "header" => 30,
                "header_txt" => $header,
                "footer_txt" => $footer,
                "page_no" => $page_no,
                "header_of" => false
                
            );

            $this->load->library('greport', $data);
                
            $a['view'] = $this->greport->_print();
        }else{
            $a['view'] = "No Record";
        }
        
        return $a;
    }    
    
}