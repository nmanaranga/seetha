<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_dispatch_note01 extends CI_Model {
    
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
    private $h = 140;
    private $w = 216;
    
    function __construct(){
	parent::__construct();
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_dispatch_sum'];
	$this->tb_det = $this->tables->tb['t_dispatch_det'];
	$this->tb_trance = $this->tables->tb['t_item_movement'];
	$this->tb_acc_trance = $this->tables->tb['t_customer_acc_trance'];
	$this->tb_items = $this->tables->tb['m_items'];
	$this->tb_cheque = $this->tables->tb['t_customer_cheques'];
	$this->tb_bank = $this->tables->tb['m_banks'];
	$this->tb_bank_branch = $this->tables->tb['m_bank_branch'];
	$this->tb_cus = $this->tables->tb['m_customer'];
	$this->tb_branch = $this->tables->tb['s_branches'];
	$this->tb_subitem = $this->tables->tb['m_sub_item_list'];
	$this->tb_rep = $this->tables->tb['m_sales_ref'];
	$this->tb_store = $this->tables->tb['m_stores'];
	$this->t_dispatch_trance = $this->tables->tb['t_dispatch_trance'];
        
    }
    
    public function base_details(){
	//$this->load->model('m_customer');
	//$this->load->model('m_sales_ref');
	//$this->load->model('t_purchase_order');
	//$this->load->model('m_stores');
///	$this->load->model('m_area');
	//$this->load->model('m_person');
        
	//$this->load->model('s_options');
	//$a['person'] = $this->m_person->select();
	//$a['person1'] = $this->m_person->select1();
	//$a['person2'] = $this->m_person->select2();
	//$a['cus'] = $this->m_customer->select('fielter');
	//$a['ref'] = $this->m_sales_ref->select();
	//$a['max_no'] = $this->max_no();
	//$a['po_no'] = $this->t_purchase_order->select();
	//$a['stores'] = $this->m_stores->select();
	//$a['area'] = $this->m_area->select('name', 'width : 300px;');
	//$a['sd'] = $this->sd;
	//$a['p_mr'] = $this->s_options->get_option('is_check_profit_margin');
	
	//return $a;
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
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc']
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
		    "trance_type"=>'DISPATCH',
		    "item_code"=>$_POST['h_'.$i],
		    "out_quantity"=>$_POST['1_'.$i],
		    "date"=>$_POST['date'],
		    "bc"=>$this->sd['bc'],
		    "stores"=>$_POST['stores'],
		    "ref_no"=>$_POST['ref_no']
		);
                
		$a3[] = array(
		    "id"=>$lid,
		    "trance_id"=>$a['no'],
		    "trance_type"=>'DISPATCH',
		    "item_code"=>$_POST['h_'.$i],
		    "in_quantity"=>$_POST['1_'.$i],
		    "date"=>$_POST['date'],
		    "bc"=>$this->sd['bc'],
		    "stores"=>$_POST['customer'],
		    "ref_no"=>$_POST['ref_no']
		);
		
		$a4[] = array(
		    "cus_id"=>$_POST['customer'],
		    "date"=>$_POST['date'],
		    "item_code"=>$_POST['h_'.$i],
		    "in_no"=>$a['no'],
		    "in_qty"=>$_POST['1_'.$i],
		    "out_no"=>$a['no'],
		    "out_qty"=>'0',
		    "trance_code"=>'DISPATCH',
		    "trance_no"=>$lid,
		    "bc"=>$this->sd['bc']
		   
		);
		
                $net_amount += $_POST['1_'.$i];
	    }
        }
	 
        
        
	if(count($a1)){ $this->db->insert_batch($this->tb_det, $a1); }
	if(count($a2)){ $this->db->insert_batch($this->tb_trance, $a2);	}
	if(count($a3)){ $this->db->insert_batch($this->tb_trance, $a3);	}
        
        //$this->insert_subitem($_POST['h_'.$i],$lid,$a["no"],$_POST['date'],$_POST['stores'],$this->sd['bc'],$_POST['ref_no'],$_POST['1_'.$i]);
        
        //$this->insert_subitem($_POST['h_'.$i],$lid,$a["no"],$_POST['date'],$_POST['stores'],$this->sd['bc'],$_POST['ref_no'],$_POST['1_'.$i]);
        
	if(count($a4)){ $this->db->insert_batch($this->t_dispatch_trance, $a4);	}

	
	$this->db->trans_complete();
	
	//redirect(base_url()."?action=t_dispatch_note&print=".$lid);
        
        //redirect(base_url()."?action=t_dispatch_note");
	echo $lid;
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
	$this->db->select(array('id', 'no', 'date', 'name', 'outlet_name', 'customer','marketing_codi','transport_codi','driver','address','reson','stores','is_cancel'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
	
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){
	    $this->db->select(array('qty', $this->tb_det.'.qtyc', $this->tb_det.'.item_code', $this->tb_det.'.cartoon', $this->tb_items.'.description',$this->tb_det.'.current_stock'));
	    $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
	}
	
        echo json_encode($a);
    }
    
    public function load_return(){
	$this->db->select(array('id', 'no', 'date', 'name', 'outlet_name', 'customer','marketing_codi','transport_codi','driver','address','reson','stores','is_cancel'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
	
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){
	    $this->db->select(array('qty', $this->tb_det.'.qtyc', $this->tb_det.'.item_code', $this->tb_det.'.cartoon', $this->tb_items.'.description',$this->tb_det.'.current_stock'));
	    $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
            
            $sql="SELECT
                        SUM(`t_dispatch_trance`.`in_qty`)-SUM(`t_dispatch_trance`.`out_qty`) AS bal
                        ,b.cs
                        FROM
                            `t_dispatch_trance`
                            INNER JOIN `m_items` 
                                ON (`t_dispatch_trance`.`item_code` = `m_items`.`code`)   
                            INNER JOIN (SELECT SUM(`in_quantity`)-SUM(`out_quantity`) AS cs,item_code FROM `t_item_movement` GROUP BY item_code) AS b ON 
                            (b.`item_code`=t_dispatch_trance.`item_code`)
                                WHERE `in_no`='".$_POST['id']."'
                                GROUP BY `in_no`, `m_items`.`code`";
            
            $query=$this->db->query($sql);
            $r=$query->result();
            $a['tran']=$r;
            
	}
	
        echo json_encode($a);
    }

   public function load_subitem(){
           $balance=0;$a= array();
        $sql="SELECT
                `item_code`
                , `in_no`
                , SUM(`in_qty`)-SUM(`out_qty`) balance
                , SUM(out_qty) as out_qty
            FROM
                `t_dispatch_trance`
               WHERE `in_no`='".$_POST['id']."'
                 GROUP BY `in_no`";
           
        $query=$this->db->query($sql);
        $r=$query->first_row();
        $a['a']=$r->out_qty;
           
 
       echo json_encode($a);
    }
    
    
    
    public function get_current_stock(){
        
	   $this->db->select(array('SUM('.$this->tb_trance.'.in_quantity) - SUM('.$this->tb_trance.'.out_quantity) as cs'));
           $this->db->where("item_code", $_POST['id']);
           
	    $a['det'] = $this->db->get($this->tb_trance)->result();

        echo json_encode($a);
    }
    
    
    
    
    public function delete(){
	$a = true;
        
        $this->db->where("id", $_POST['id']);
        $this->db->where("trance_type", "DISPATCH");
        if(! $this->db->delete($this->tb_trance)){
            $a = false;
        }
        
        $this->db->where("trance_no", $_POST['id']);
        $this->db->where("trance_code", "DISPATCH");
        if(! $this->db->delete($this->t_dispatch_trance)){
            $a = false;
        }
	        
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        	
        echo $a;
    }
    
   /* public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'SALES');
	$this->db->delete($this->tb_acc_trance);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('trance_type', 'SALES');
	$this->db->delete($this->tb_trance);
	
	$this->db->where("id", $_POST['hid']);
	$this->db->where("module", 'SALES');
	$this->db->delete($this->tb_cheque);
	
	$this->load->model('account');
	$this->account->delete($_POST['hid'], "SALES", $this->sd['db_code']);
	
	$this->load->database($this->sd['db'], true);
    }*/
    
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
	    
            $header  = array("data"=>$this->useclass->r_header("DISPATCH NOTE <hr />", $inv_info), "style"=>"font-weight: normal; font-family: Courier;");
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
    
    public function print_delivery_note(){
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
            $cost = array("data"=>"Price", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $discount = array("data"=>"Dis", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
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
	    
            $header  = array("data"=>$this->useclass->r_header("DISPATCH NOTE <hr />", $inv_info), "style"=>"font-weight: normal; font-family: Courier;");
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