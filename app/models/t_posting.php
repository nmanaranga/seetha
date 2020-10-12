<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_posting extends CI_Model {
    
    private $sd;
    private $tb;
    private $tb_branch;
    private $tb_oc;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb = array(
	    $this->tables->tb['t_open_stock_sum'],
	    $this->tables->tb['t_stock_adj_sum'],
	    $this->tables->tb['t_purchse_order_sum'],
	    $this->tables->tb['t_purchse_sum'],
	    $this->tables->tb['t_purchse_return_sum'],
	    $this->tables->tb['t_supplier_receipt_sum'],
	    $this->tables->tb['t_supplier_settle_sum'],
	    $this->tables->tb['t_sales_sum'],
	    $this->tables->tb['t_sales_return_sum'],
	    $this->tables->tb['t_customer_receipt_sum'],
	    $this->tables->tb['t_customer_settle_sum'],
	    $this->tables->tb['t_damage_free_issu_sum']
	);
	$this->tb_branch = $this->tables->tb['s_branches'];
	$this->tb_oc = $this->tables->tb['a_users'];
    }
    
    public function base_details(){
	
	$a['table_data'] = $this->data_table();
	
	return $a;
    }
    
    private function make_table_data($table){
        $this->db->select(array('id', 'no', 'name', 'date', $table.'.bc', 'ref_no', 'discription'));
	$this->db->join($this->tb_branch, $this->tb_branch.".code = ".$table.".bc", "INNER");
	$this->db->join($this->tb_oc, $this->tb_oc.".cCode = ".$table.".oc", "INNER");
	$this->db->where('posting', 0);
	$this->db->where('is_cancel', 0);
        $query = $this->db->get($table);
        
	if($table == "t_open_stock_sum"){
		$head = "<div class='heading'>Open Stock</div>";
	    }elseif($table == "t_purchse_order_sum"){
		$head = "<div class='heading'>Purchse Order</div>";
	    }elseif($table == "t_stock_adj_sum"){
		$head = "<div class='heading'>Stock Adjustment</div>";
	    }elseif($table == "t_purchse_sum"){
		$head = "<div class='heading'>Purchse</div>";
	    }elseif($table == "t_purchse_return_sum"){
		$head = "<div class='heading'>Purchse Return</div>";
	    }elseif($table == "t_supplier_receipt_sum"){
		$head = "<div class='heading'>Supplier Voucher</div>";
	    }elseif($table == "t_supplier_settle_sum"){
		$head = "<div class='heading'>Supplier Settlement</div>";
	    }elseif($table == "t_sales_sum"){
		$head = "<div class='heading'>Sales</div>";
	    }elseif($table == "t_sales_return_sum"){
		$head = "<div class='heading'>Sales Return</div>";
	    }elseif($table == "t_customer_receipt_sum"){
		$head = "<div class='heading'>Customer Receipt</div>";
	    }elseif($table == "t_customer_settle_sum"){
		$head = "<div class='heading'>Customer Settlement</div>";
	    }elseif($table == "t_damage_free_issu_sum"){
		$head = "<div class='heading'>Damage & Free Issue</div>";
	    }
	
	$str = $head."<table style='width: 100%;'>
		    <tr>
			<td style='text-align: center; width: 80px;'>No</td>
			<td style='text-align: center; width: 100px;'>Ref. No</td>
			<td style='text-align: center; width: 150px;'>Branch</td>
			<td style='text-align: center; width: 100px;'>Date</td>
			<td>User</td>
			<td style='text-align: center; width: 100px;'>Action</td>
		    </tr>
		</table><div class='posting_div'>";
	
	if($query->num_rows){
	    foreach($query->result() as $r){
		if($r->ref_no = "Reference No"){ $r->ref_no = ""; }
		$str .="
		    <form action='index.php/main/save/t_posting/".$r->id."/".$table."' method='post'>
			<table style='width: 100%;'>
			    <tr>
				<td style='width: 80px;'>".$r->no."</td>
				<td style='width: 100px;'>".$r->ref_no."</td>
				<td style='width: 150px;'>".$r->name."</td>
				<td style='width: 100px;'>".$r->date."</td>
				<td>".$r->discription."</td>
				<td style='text-align: right;'><button>Post</button></td>
			    </tr>
			</table>
		    </form>
		";
	    }
	    $str .= "</div>";
	}else{
	    $str = "";
	}
        
        return $str;
    }
    
    public function data_table(){
	$str = "";
	foreach($this->tb as $t){	    
	    $str .= $this->make_table_data($t);
	}
	
	return $str;
    }
    
    public function save(){
	$this->db->where('id', $this->uri->segment(4));
	$this->db->limit(1);
	$this->db->update($this->uri->segment(5), array("posting"=>$this->sd['oc']));
	
	redirect(base_url()."?action=t_posting");
    }
}