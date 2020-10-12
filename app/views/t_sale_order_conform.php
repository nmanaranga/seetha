<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_sale_order_conform extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_items;
    private $tb_cus;
    private $tb_branch;
    private $tb_curstock;
    private $tb_users;
    private $h = 140;
    private $w = 216;
    
    function __construct(){
	parent::__construct();
		$this->sd = $this->session->all_userdata();
		$this->load->database($this->sd['db'], true);
		
		$this->tb_sum = $this->tables->tb['t_sales_order_sum'];
		$this->tb_det = $this->tables->tb['t_sales_order_det'];
		$this->tb_items = $this->tables->tb['m_items'];
		$this->tb_cus = $this->tables->tb['m_customer'];
		$this->tb_branch = $this->tables->tb['s_branches'];
		$this->tb_curstock = $this->tables->tb['qry_cur_stock'];
		$this->tb_users=$this->tables->tb['s_users'];
                $this->tb_trance = $this->tables->tb['t_item_movement'];
		
    }
    
    public function base_details(){
		$this->load->model('m_customer');
		$this->load->model('m_sales_ref');
		$this->load->model('m_stores');
		$this->load->model('m_area');
		$this->load->model('m_option_setup');
	
		$a['grid']=$this->m_option_setup->get_grid();
		
		$a['cus'] = $this->m_customer->select('fielter');
		$a['ref'] = $this->m_sales_ref->select();
		$a['max_no'] = $this->max_no();
                $a['max_no2'] = $this->max_no2();
		$a['stores'] = $this->m_stores->select();
		$a['area'] = $this->m_area->select('name', 'width : 300px;');
		$a['sd'] = $this->sd;
                $a['o'] = $this->loder->get_option_data();
		
		return $a;
    }
    
    private function max_no(){
		$this->db->where("bc", $this->sd['bc']);
		$this->db->select_max("no");
		
		return $this->db->get($this->tb_sum)->first_row()->no+1;
    }
    
    private function max_no2(){
		$this->db->where("bc", $this->sd['bc']);
		$this->db->select_max("d_note_no");
		
		return $this->db->get($this->tb_sum)->first_row()->d_note_no+1;
    }
    
    public function save(){
		$this->db->trans_start();
		
		$this->load->model('m_option_setup');
		$g['grid']=$this->m_option_setup->get_grid();
		
		$y=$g['grid']->value;
		
                 if (!isset($_POST['vat_rate']))
                {
                   $_POST['vat_rate']='0';
                }
                if (!isset($_POST['vat_amount']))
                {
                   $_POST['vat_amount']='0';
                }
                
                
		$a = array(
			"date"=>$_POST['date'],
			"ref_no"=>$_POST['ref_no'],
                        "d_note_no"=>$_POST['d_note_no'],
			"memo"=>$_POST['memo'],
			"customer"=>$_POST['customer'],
			"sales_ref"=>$_POST['sales_ref'],
			"discount"=>$_POST['discount'],
			"balance"=>$_POST['balance'],
			"response"=>$_POST['is_reject'],
			"is_approve"=>$this->sd['oc'],
                        "vat_rate"=>$_POST['vat_rate'],
                        "vat_amount"=>$_POST['vat_amount'],
			"approve_time"=>time()
		);
		
		$sa = array(
			"sales_ref"=>$_POST['sales_ref']
		);
		
		if(isset($_POST['stores'])){ $a["stores"] = $_POST['stores']; $sa["stores"] = $_POST['stores']; }
		
		$this->session->set_userdata($sa);
		
		if($_POST['hid'] == "" || $_POST['hid'] == 0 ){
			$a["no"] = $this->max_no();
                        $a["d_note_no"] = $this->max_no2();
			$this->db->insert($this->tb_sum, $a);
			$lid = $this->db->insert_id();
		}else{
			$this->db->where("id", $_POST['hid']);
			$this->db->limit(1);
			$this->db->update($this->tb_sum, $a);
			$lid = $_POST['hid']; $a['no'] = $_POST['id'];
			$this->set_delete();
		}
                
                if($_POST['is_reject']=='0'){
                    
                    $data=array(
                                "d_note_no"=>0,
                                    );
                        $this->db->where("id", $_POST['id']);
			$this->db->limit(1);
			$this->db->update($this->tb_sum, $data);
                    
                }
		
		$a1 = $a2 = $a3 = array();
		$avg_price=$net_amount = 0; $cheque_no = array(); $dis = $_POST['discount'];
		for($i=0; $i<$y; $i++){
			if($_POST['h_'.$i] != "" && $_POST['h_'.$i] != "0"){
				$a1[] = array(
					"id"=>$lid,
					"item_code"=>$_POST['h_'.$i],
					"cost"=>$_POST['2_'.$i],
					"quantity"=>$_POST['1_'.$i],
					"original_qty"=>$_POST['11_'.$i],
					"discount"=>$_POST['3_'.$i],
					"discount_pre"=>$_POST['4_'.$i]
                                        );
                                
                $sd="SELECT purchase_price,max_sales FROM m_items WHERE `code`='".$_POST['h_'.$i]."'";
   
                $qsd=$this->db->query($sd);
                $s=$qsd->first_row();
                $pp=$s->purchase_price;
                $ms=$s->max_sales;
                
                $sql="SELECT avg_price FROM m_items WHERE `code`='".$_POST['h_'.$i]."'";
                $q=$this->db->query($sql);
                $r=$q->first_row();
                $avg_price=$r->avg_price;
                                
                  //  foreach($this->get_batch_stock($_POST['h_'.$i], $_POST['1_'.$i]) as $st){
		    $a2[] = array(
			"id"=>$lid,
			"trance_id"=>$a['no'],
			"trance_type"=>'SALES_ORDER',
			"item_code"=>$_POST['h_'.$i],
			"out_quantity"=>$_POST['1_'.$i],
			"date"=>$_POST['date'],
			"bc"=>$this->sd['bc'],
			"stores"=>$_POST['stores'],
			"ref_no"=>$_POST['ref_no'],
			"batch_no"=>'0',
                        "sal_price"=>$ms,
                        "pur_price"=>$pp,   
                        "avg_price"=>$avg_price,
                        "description"=>$_POST['customer']
		    );
		//}                     
			}
		}

                if(count($a1)){ $this->db->insert_batch($this->tb_det, $a1); }
		if(count($a2)){ $this->db->insert_batch($this->tb_trance, $a2);	}
                
		$this->db->trans_complete();
		
		//redirect(base_url()."?action=t_sales&print=".$lid);
		echo $lid;
	}
	
        
private function get_batch_stock($item, $qty){
	$q = $this->db->select(array('SUM(in_quantity) - SUM(out_quantity) AS qty', 'batch_no'))
		->group_by('batch_no')
		->having('qty >', 0)
		->where('item_code', $item)
		->order_by('batch_no', 'ASC')
		->get($this->tb_trance);
	$res = array();
	foreach($q->result() as $r){
	    if($qty > 0){
		if($qty > $r->qty){
		    $std = new stdClass;
		    
		    $std->qty = $r->qty;
		    $std->batch = $r->batch_no;
		    
		    $res[] = $std; $qty -= $r->qty;
		}else{
		    $std = new stdClass;
		    
		    $std->qty = $qty;
		    $std->batch = $r->batch_no;
		    
		    $res[] = $std; $qty = 0;
		    break;
		}
	    }
	}
	
	return $res;
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
		$this->db->select(array('id', 'no', 'd_note_no','date', 'name', 'outlet_name', 'customer', 'sales_ref', 'is_cancel', 'ref_no', 'memo', 'discount', 'balance', 'stores', 'posting','vat_rate','unpost','unpost_sales_no'));
		$this->db->where("id", $_POST['id']);
		$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
		
		$this->db->limit(1);
		$a['sum'] = $this->db->get($this->tb_sum)->first_row();
		
		if(isset($a["sum"]->id)){
			$this->db->select(array('original_qty','quantity', $this->tb_det.'.cost', $this->tb_det.'.item_code', $this->tb_det.'.discount', $this->tb_det.'.discount_pre', $this->tb_items.'.description', $this->tb_items.'.is_measure'));
			$this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
			$this->db->where($this->tb_det.".id", $a["sum"]->id);
			$a['det'] = $this->db->get($this->tb_det)->result();
		}
		
		echo json_encode($a);
		
    }
    
    	public function load_d_note_no(){	    
	       
		$this->db->select(array('id', 'no', 'd_note_no','date', 'name', 'outlet_name', 'customer', 'sales_ref', 'is_cancel', 'ref_no', 'memo', 'discount', 'balance', 'stores', 'posting','vat_rate','unpost','unpost_sales_no'));
		$this->db->where("d_note_no", $_POST['d_note_no']);
		$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
		
		$this->db->limit(1);
		$a['sum'] = $this->db->get($this->tb_sum)->first_row();
		
		if(isset($a["sum"]->id)){
			$this->db->select(array('original_qty','quantity', $this->tb_det.'.cost', $this->tb_det.'.item_code', $this->tb_det.'.discount', $this->tb_det.'.discount_pre', $this->tb_items.'.description', $this->tb_items.'.is_measure'));
			$this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
			$this->db->where($this->tb_det.".id", $a["sum"]->id);
			$a['det'] = $this->db->get($this->tb_det)->result();
		}
		
		echo json_encode($a);
    }

    
    public function delete(){
		$a = true;
        
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
		$this->db->where('trance_type', 'SALES_ORDER');
		$this->db->delete($this->tb_trance);
                
                
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
            '(quantity * '.$this->tb_det.'.cost) - discount AS total',
	    'Current_Stock AS stock'
        ));
        $this->db->where($this->tb_det.'.id', $_GET['id']);
        $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	$this->db->join($this->tb_curstock, $this->tb_curstock.".Item_Code = ".$this->tb_det.".item_code", "INNER");
        $query = $this->db->get($this->tb_det);
        
        if($query->num_rows){
            $this->db->select(array("no", $this->tb_sum.".bc","date", "name", "outlet_name", "is_cancel", "discount","loginName","memo","ref_no",$this->tb_sum.".action_date"));
            $this->db->where('id', $_GET['id']);
            $this->db->limit(1);
	    $this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
	    $this->db->join($this->tb_users, $this->tb_users.".cCode = ".$this->tb_sum.".oc", "INNER");
            $query2 = $this->db->get($this->tb_sum);
	    
	    $this->db->select("loginName");
	    $this->db->where('cCode',$this->sd['oc']);
	    $qry=$this->db->get($this->tb_users);
            
            $result = $query->result();
            $result2 = $query2->first_row();
	    $result3 = $qry->first_row();
	    
	    $a['cancel'] = $result2->is_cancel;
	    $oc=$result2->loginName;
	    $time=$result2->action_date;
            $oc2=$result3->loginName;
            
            $tot=$t = $ct = $dt = 0;
            foreach($result as $rr){
                $t += $rr->total;
                $ct += $rr->quantity;
		$dt += $rr->discount;
		$tot +=1;
            }
	    
            $code = array("data"=>"Item Code", "style"=>"text-align: left; width:80px", "chalign"=>"text-align: left;");
            $name = array("data"=>"Item Name","style"=>"text-align: left;", "chalign"=>"text-align: left;");
	    $stock = array("data"=>"Available Qty", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $qun = array("data"=>"Qty", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $cost = array("data"=>"Price", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
           // $discount = array("data"=>"Discount", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
           // $total = array("data"=>"Total", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
            
            $heading = array($code, $name, $stock, $qun, $cost);//
            
            $code = array("data"=>"code", "total"=>false, "format"=>"text");
            $name  = array("data"=>"description", "total"=>false, "format"=>"text");
	    $stock  = array("data"=>"stock", "total"=>false, "format"=>"number");
            $qun  = array("data"=>"quantity", "total"=>false, "format"=>"number");
            $cost  = array("data"=>"cost", "total"=>false, "format"=>"amount");
           // $discount  = array("data"=>"discount", "total"=>false, "format"=>"amount");
           // $total  = array("data"=>"total", "total"=>false, "format"=>"amount");
            
            $field = array($code, $name,$stock, $qun, $cost);//
            
            $page_rec = 6;
            
	    $inv_info = "<table style='width : 100%; font-size : 12px;' border='0'>
		<tr>
		    <td style='width:100px;'>Invoice No</td>
		    <td>: ".$result2->no."</td>
		    <td>Customer</td>
		    <td>: ".$result2->outlet_name." (".$result2->name.")</td>
		</tr><tr>
		    <td>Date</td>
		    <td>: ".$result2->date."</td>
		     <td >Memo</td>
		    <td>: ".$result2->memo."</td>
		</tr>
		<tr>
		<td>Ref.no</td>
		<td>: ".$result2->ref_no."</td></tr>
	    </table>";
	    
	    $page_end = "<br /><br/><table style='width : 100%; font-size : 12px; font-weight : bold; font-family : Courier;' border='0'>
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
	    </table>
	    <br /><br/>
		<table style='width : 80%; font-size : 12px; font-weight : bold; font-family : Courier;' border='0'>
		
		<tr>
		    <td style='text-align: left;width:20%'>Number of Items </td>
		    <td style='width:20%; text-align: left;'>:".$tot."</td>
		
		    <td style='text-align: left;width:20%'>Number of Quantity </td>
		    <td style='width:20%; text-align: left;'>:".$ct."</td>
		</tr>
		
		<tr>
		    <td style='text-align: left;width:20%'>Entry By </td>
		    <td style='width:20%; text-align: left;'>:".$oc."</td>
		
		    <td style='text-align: left;width:20%'>Entry time </td>
		    <td style='width:20%; text-align: left;'>:".$time."</td>
		</tr>
		
		<tr>
		    <td style='text-align: left;'>Print By </td>
		    <td style='width:100px; text-align: left;'>:".$oc2."</td>
		
		    <td style='text-align: left;'>Print time </td>
		    <td style='width:100px; text-align: left;'>:".date("Y-m-d H:i:s")."</td>
		</tr>
	    </table>";
	    
	    $sig = "<br/><table style='width: 20%; font-size : 12px; font-weight : normal; font-family : Courier;' >
		<tr>
		    <td style='text-align : center;'>.....................</td>
		    <!--<td style='text-align : center;'>.....................</td>-->
		    <!--<td style='text-align : center;'>.....................</td>-->
		</tr><tr>
		    <td style='text-align : center;'>Signature</td>
		    <!--<td style='text-align : center;'>Manager</td>-->
		    <!--<td style='text-align : center;'>Customer</td>-->
		</tr>
	    </table>";
	    
            $header  = array("data"=>$this->useclass->r_header("Pre-Order <hr />", $inv_info), "style"=>"font-weight: normal; font-family: Courier;");
            $footer  = array("data"=>"<div style='text-align: left; font-size: 12px;'>".$sig."<br/><br/></div><hr />Soft-Master Technologies (pvt) LTD / 0812-204130, 0773-889082/3. / ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
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
    
    public function print_view_accept(){
	
        $this->load->library('useclass');
		$a['title'] = 'Sales';
        
        $this->db->select(array(
            'code',
            'description',
            'quantity',
            //$this->tb_det.'.cost',
	    'discount',
            '(quantity * '.$this->tb_det.'.cost) - discount AS total'
	    
        ));
        $this->db->where($this->tb_det.'.id', $_GET['id']);
        $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	
        $query = $this->db->get($this->tb_det);
        
        if($query->num_rows){
            $this->db->select(array("no", "date","d_note_no", "name", "outlet_name", "is_cancel", "discount"));
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
            $discount = array("data"=>"Discount", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $total = array("data"=>"Total", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
            
            $heading = array($code, $name, $qun, $discount, $total);//
            
            $code = array("data"=>"code", "total"=>false, "format"=>"text");
            $name  = array("data"=>"description", "total"=>false, "format"=>"text");
            $qun  = array("data"=>"quantity", "total"=>false, "format"=>"number");
            $discount  = array("data"=>"discount", "total"=>false, "format"=>"amount");
            $total  = array("data"=>"total", "total"=>false, "format"=>"amount");
            
            $field = array($code, $name,$qun, $discount, $total);//
            
            $page_rec = 6;
            
	    $inv_info = "<table style='width : 100%; font-size : 12px;' border='0'>
		<tr>
		   <!-- <td style='width:100px;'>Invoice No</td>-->
		   <!-- <td>: ".$result2->no."</td>-->
		    <td >Customer</td>
		    <td >: ".$result2->outlet_name." (".$result2->name.")</td>
		</tr><tr>
		    <td>Deliver note no</td>
		    <td>: ".$result2->d_note_no."</td>
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
		    <td style='text-align : center;'>Cashier</td>
		    <td style='text-align : center;'>Manager</td>
		    <td style='text-align : center;'>Customer</td>
		</tr>
	    </table>";
	    
            $header  = array("data"=>$this->useclass->r_header("Deliver Note <hr />", $inv_info), "style"=>"font-weight: normal; font-family: Courier;");
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