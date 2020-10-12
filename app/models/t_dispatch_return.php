<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_dispatch_return extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_po_trance;
    private $tb_acc_trance;
    private $tb_items;
    private $tb_supplier;
    private $tb_cost_log;
    private $tb_branch;
    private $h = 297;
    private $w = 210;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_dispatch_return_sum'];
	$this->tb_det = $this->tables->tb['t_dispatch_return_det'];
	$this->tb_trance = $this->tables->tb['t_item_movement'];
	$this->tb_po_trance = $this->tables->tb['t_purchse_order_trance'];
	$this->tb_acc_trance = $this->tables->tb['t_supplier_acc_trance'];
	$this->tb_items = $this->tables->tb['m_items'];
	$this->tb_supplier = $this->tables->tb['m_supplier'];
	$this->tb_customer = $this->tables->tb['m_customer'];
	$this->tb_cost_log = $this->tables->tb['t_cost_log'];
	$this->tb_branch = $this->tables->tb['s_branches'];
        $this->tb_cus = $this->tables->tb['m_customer'];
        $this->tb_store = $this->tables->tb['m_stores'];
        $this->t_dispatch_trance = $this->tables->tb['t_dispatch_trance'];
	
	
    }
    
    public function base_details(){
	$this->load->model('m_supplier');
	$this->load->model('t_purchase_order');
	$this->load->model('m_stores');
        $this->load->model('m_person');
	
	$a['sup'] = $this->m_supplier->select();
	$a['max_no'] = $this->max_no();
	$a['po_no'] = $this->t_purchase_order->select();
	$a['stores'] = $this->m_stores->select();
	$a['sd'] = $this->sd;
        $a['person'] = $this->m_person->select();
	$a['person1'] = $this->m_person->select1();
	$a['person2'] = $this->m_person->select2();
	
	return $a;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['bc']);
	$this->db->select_max("no");
	
	return $this->db->get($this->tb_sum)->first_row()->no+1;
    }
    
    private function calculate_average_price($item_code,$grn_qty,$grn_val)
    {
        $avg_price=$current_stock=$current_stock_value=$grn_value=$current_avg_price_val=$current_avg_price=0;
           
        $sq="SELECT `avg_price` FROM `m_items` WHERE `code`='$item_code'";
        $q=$this->db->query($sq);
        $r = $q->first_row();
        $avg_price = $r->avg_price;
        
        $sq1="SELECT SUM(`in_quantity`-`out_quantity`) AS cs FROM `t_item_movement` WHERE `item_code`='$item_code' GROUP BY `item_code`";
        $q1=$this->db->query($sq1);
        $r1 = $q1->first_row();
        $current_stock = $r1->cs;
        
        $current_stock_value=$avg_price*$current_stock;
        
        $grn_value=$grn_qty*$grn_val;
        
        $current_avg_price_val=($current_stock_value+$grn_value)/($grn_qty+$current_stock);   
        
       
        
        $current_avg_price=number_format($current_avg_price_val, 2, '.', '');
        
        
        //$a = array('avg_price' => $current_avg_price);

        $sq2="UPDATE m_items SET avg_price='$current_avg_price_val' WHERE code='".$item_code."' LIMIT 1";
        $this->db->query($sq2);
       
        //echo $current_avg_price;exit;
        
        return $current_avg_price;

    }       

    public function save(){
	$this->db->trans_start();
	        
	$a = array(
	    "date"=>$_POST['date'],
	    "ref_no"=>$_POST['ref_no'],
	    "customer"=>$_POST['customer'],
	    "marketing_codi"=>$_POST['mar_codi'],
	    "transport_codi"=>$_POST['trans_codi'],
	    "driver"=>$_POST['driver'],
	    "reson"=>$_POST['reson'],
	    "address"=>$_POST['address'],
	    "dispatch_no"=>$_POST['pono'],
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc']
	);
		
	if(isset($_POST['stores'])){ $a["stores"] = $_POST['stores']; $sa["stores"] = $_POST['stores']; }
	
	
	$this->session->set_userdata($sa);
	
	//if($_POST['hid'] == "" || $_POST['hid'] == 0){
	    $a["no"] = $this->max_no();
	    $this->db->insert($this->tb_sum, $a);
	    $lid = $this->db->insert_id();
	//}else{
	//    $this->db->where("id", $_POST['hid']);
	//    $this->db->limit(1);
	//    $this->db->update($this->tb_sum, $a);
	//    $lid = $_POST['hid']; $a['no'] = $_POST['id'];
	 //   $this->set_delete();
	//}
	
        $a1 = $a2 =$a3= array();
	$net_amount = 0; $cheque_no = array(); 
        for($i=0; $i<25; $i++){

    
            if($_POST['h_'.$i] != "" && $_POST['h_'.$i] != "0"){
		
		if($_POST['3_'.$i] == ""){ $_POST['3_'.$i] = 0.00; }
		if($_POST['1_'.$i] == ""){ $_POST['1_'.$i] = 0.00; }
		if($_POST['4_'.$i] == ""){ $_POST['4_'.$i] = 0.00; }

                
		$a1[] = array(
		    "id"=>$lid,
		    "item_code"=>$_POST['h_'.$i],
		    "qty"=>$_POST['1_'.$i],
		    "qtyc"=>$_POST['2_'.$i],
		    "current_stock"=>$_POST['4_'.$i],
		    "cartoon"=>$_POST['3_'.$i]
       
		);
		
		$a2[] = array(
		    "id"=>$lid,
		    "trance_id"=>$a['no'],
		    "trance_type"=>'DISPATCH_RE',
		    "item_code"=>$_POST['h_'.$i],
		    "in_quantity"=>$_POST['1_'.$i],
		    "date"=>$_POST['date'],
		    "bc"=>$this->sd['bc'],
		    "stores"=>$_POST['stores'],
		    "ref_no"=>$_POST['ref_no']
		);
                
		$a3[] = array(
		    "id"=>$lid,
		    "trance_id"=>$a['no'],
		    "trance_type"=>'DISPATCH_RE',
		    "item_code"=>$_POST['h_'.$i],
		    "out_quantity"=>$_POST['1_'.$i],
		    "date"=>$_POST['date'],
		    "bc"=>$this->sd['bc'],
		    "stores"=>$_POST['customer'],
		    "ref_no"=>$_POST['ref_no']
		);
		
               $a4[] = array(
		    "cus_id"=>$_POST['customer'],
		    "date"=>$_POST['date'],
		    "item_code"=>$_POST['h_'.$i],
		    "in_no"=>$_POST['pono'],
		    "in_qty"=>'0',
		    "out_no"=>$a['no'],
		    "out_qty"=>$_POST['1_'.$i],
		    "trance_code"=>'DISPATCH_RE',
		    "trance_no"=>$lid,
		    "bc"=>$this->sd['bc']
		   
		);
                
                $net_amount += $_POST['1_'.$i];
	    }
        }
	        
	if(count($a1)){ $this->db->insert_batch($this->tb_det, $a1); }
	if(count($a2)){ $this->db->insert_batch($this->tb_trance, $a2);	}
	if(count($a3)){ $this->db->insert_batch($this->tb_trance, $a3);	}
        if(count($a4)){ $this->db->insert_batch($this->t_dispatch_trance, $a4);	}
	
	$this->db->trans_complete();
	
	//redirect(base_url()."?action=t_dispatch_note&print=".$lid);
        
        //redirect(base_url()."?action=t_dispatch_note");
	echo $lid;
    }
    
       public function load_subitem(){
           $balance=0;$a= array();
        $sql="SELECT
                `item_code`
                , `in_no`
                , SUM(`in_qty`)-SUM(`out_qty`) balance
            FROM
                `t_dispatch_trance`
               WHERE `in_no`='".$_POST['id']."'
                 GROUP BY `in_no`";
           
        $query=$this->db->query($sql);
        $r=$query->first_row();
        $a['a']=$r->balance;
           
 
       echo json_encode($a);
    }
    
    
    
    
    private function cost_cal($id, $item_id, $quantity, $cost){
        $sql = "SELECT
                    IFNULL(cost.cost, 0) AS `cost`,
                    IFNULL((SUM(`in_quantity`)-SUM(`out_quantity`)), 0) AS `quantity`
                  FROM `".$this->tb_trance."`
                    LEFT OUTER JOIN (SELECT
                                       item_code,
                                       cost
                                     FROM `".$this->tb_cost_log."`
                                     WHERE item_code = '".$item_id."'
                                     ORDER BY auto_id DESC LIMIT 1) AS cost
                      ON (cost.item_code = `".$this->tb_trance."`.item_code)
                  WHERE `".$this->tb_trance."`.item_code = '".$item_id."'";
	
        $bc = $this->db->query($sql);
        $row = $bc->num_rows;
        $bc = $bc->first_row();
        
        if($bc->quantity < 0){
            $bc->quantity = 0;
        }
        
        $tq = $bc->quantity + $quantity;
        
        $tc = ($bc->cost * $bc->quantity) + ($quantity * $cost);
        $cost2 = round(($tc/$tq), 2);
        
        $a = array(
            "id"=>$id,
            "module"=>'PUR',
            "item_code"=>$item_id,
            "cost"=>$cost2
        );
        
      //  $this->db->insert($this->tb_cost_log, $a);
        
        return $cost2;
    }
    
    public function cost_max_no(){
        $this->db->select_max('auto_id');
        $query = $this->db->get($this->tb_cost_log);
        $max_no = $query->first_row()->auto_id+1;
        
        $this->db->query("ALTER TABLE `".$this->tb_cost_log."` AUTO_INCREMENT=".$max_no);
    }
    
    public function load(){
	$this->db->select(array('id', 'date', 'customer', 'name', 'is_cancel', 'ref_no','address','stores','marketing_codi','transport_codi','driver','dispatch_no'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
        $this->db->limit(1);
	$this->db->join($this->tb_customer, $this->tb_customer.".code = ".$this->tb_sum.".customer", "INNER");
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){
	    $this->db->select(array('current_stock', $this->tb_det.'.qty', $this->tb_det.'.qtyc', $this->tb_det.'.cartoon', $this->tb_det.'.item_code', $this->tb_items.'.description'));
	    $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
	}
        
        
        echo json_encode($a);
    }
    
    public function delete(){
	$a = true;
        
        $this->db->where("id", $_POST['id']);
        $this->db->where("trance_type", "DISPATCH_RE");
        if(! $this->db->delete($this->t_item_movement)){
            $a = false;
        }
	
	$this->db->where('id', $_POST['id']);
	$this->db->where('trance_code', 'DISPATCH_RE');
	if(! $this->db->delete($this->t_dispatch_trance)){
	    $a = false;
	}
        
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        	
	$this->load->database("default", true);
	
        echo $a;
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'PUR');
	$this->db->delete($this->tb_acc_trance);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'PUR');
	$this->db->delete($this->tb_cost_log);
	$this->cost_max_no();
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('trance_type', 'PUR');
	$this->db->delete($this->tb_trance);
	
	$this->load->model('account');
	$this->account->delete($_POST['hid'], "PURCHASE");
	
	$this->load->database("default", true);
    }
    
    public function print_view(){
        $this->load->library('useclass');
		$a['title'] = 'Dispatch Note';
        
        $this->db->select(array(
            'code',
            'description',
            'current_stock',
            $this->tb_det.'.qty',
	    'qtyc',
            'cartoon'
        ));
        $this->db->where($this->tb_det.'.id', $_GET['id']);
        $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	$this->db->order_by("code", "asc");
        $query = $this->db->get($this->tb_det);
	
	
        
        if($query->num_rows){
            $this->db->select(array("no", "date", "name", "outlet_name", "is_cancel"));
            $this->db->where('id', $_GET['id']);
	    $this->db->limit(1);
	    $this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
	    $this->db->join($this->tb_store, $this->tb_store.".code = ".$this->tb_sum.".stores", "INNER");
            $query2 = $this->db->get($this->tb_sum);
            
            
    
	    
	    $result = $query->result();
            $result2 = $query2->first_row();
	   
	    
	    $a['cancel'] = $result2->is_cancel;
  
            $code = array("data"=>"Item Code", "style"=>"text-align: left; width:80px", "chalign"=>"text-align: left;");
            $name = array("data"=>"Item Name","style"=>"text-align: left;", "chalign"=>"text-align: left;");
            $qun = array("data"=>"Qty", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $cost = array("data"=>"QtyC", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $discount = array("data"=>"Cartoon", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $total = array("data"=>"Total", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
            
            $heading = array($code, $name, $qun, $cost, $discount);//
            
            $code = array("data"=>"code", "total"=>false, "format"=>"text");
            $name  = array("data"=>"description", "total"=>false, "format"=>"text");
            $qun  = array("data"=>"qty", "total"=>false, "format"=>"number");
            $cost  = array("data"=>"qtyc", "total"=>false, "format"=>"amount");
            $discount  = array("data"=>"cartoon", "total"=>false, "format"=>"amount");
         
            
            $field = array($code, $name, $qun, $cost, $discount);//
            
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
	
	    </table>
		<br /><table style='width : 80%; font-size : 12px; font-weight : bold; font-family : Courier;' border='0'>
	    

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
	    
            $header  = array("data"=>$this->useclass->r_header("DISPATCH RETURN<hr />", $inv_info), "style"=>"font-weight: normal; font-family: Courier;");
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