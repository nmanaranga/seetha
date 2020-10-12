<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_purchase_return extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_acc_trance;
    private $tb_items;
    private $tb_supplier;
    private $tb_branch;
    private $h = 297;
    private $w = 210;
    
	private  $pur_trance;
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_purchse_return_sum'];
	$this->tb_det = $this->tables->tb['t_purchse_return_det'];
	$this->tb_trance = $this->tables->tb['t_item_movement'];
	$this->tb_acc_trance = $this->tables->tb['t_supplier_acc_trance'];
	$this->tb_items = $this->tables->tb['m_items'];
	$this->tb_supplier = $this->tables->tb['m_supplier'];
	$this->tb_branch = $this->tables->tb['s_branches'];
        $this->t_purchse_trance = $this->tables->tb['t_purchse_trance'];
    }
    
    public function base_details(){
	$this->load->model('m_stores');
	
	$a['max_no'] = $this->max_no();
	$a['stores'] = $this->m_stores->select();
	$a['sd'] = $this->sd;
	
	
	return $a;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['bc']);
	$this->db->select_max("no");
	
	return $this->db->get($this->tb_sum)->first_row()->no+1;
    }
    
    public function save(){
	$sa = array(
	    "stores"=>$_POST['stores']
	);
	
	$this->session->set_userdata($sa);
	
	$this->db->trans_start();
	
	$a = array(
	    "date"=>$_POST['date'],
	    "ref_no"=>$_POST['ref_no'],
	    "memo"=>$_POST['memo'],
	    "supplier"=>$_POST['supplier'],
	    "stores"=>$_POST['stores'],
	    "purchase_no"=>$_POST['invoice_no'],
	    "discount"=>$_POST['discount'],
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
	
        $a_det = $a_move = array();
	$net_amount = 0;
        $stock_val=0;
        for($i=0; $i<25; $i++){
            if($_POST['h_'.$i] != "" && $_POST['h_'.$i] != "0"){
		$a_det[] = array(
		    "id"=>$lid,
		    "item_code"=>$_POST['h_'.$i],
		    "cost"=>$_POST['2_'.$i],
		    "quantity"=>$_POST['1_'.$i],
		    "discount"=>$_POST['3_'.$i]
                   
		);
		
                $no=$a["no"];
                

		
                    $pur_trance[] = array(
		    "trance_id"=>$lid,
		    "trance_type"=>'PUR_RET',
		    "item_code"=>$_POST['h_'.$i],
		    "in_no"=>$_POST['invoice_no'],
		    "out_no"=>$no,
		    "in_quantity"=>0,
		    "out_quantity"=>$_POST['1_'.$i],
		    "out_no"=>$no,
                    "sup_id"=>$_POST['supplier'],    
		    "date"=>$_POST['date']
		);
                
                $sv=$this->get_batch_stock($_POST['h_'.$i],$_POST['1_'.$i],$lid,$a['no'],$_POST['date'],$_POST['stores'],$_POST['ref_no'],$_POST['supplier'],$_POST['invoice_no']);
                $sv=$sv['sv'];
                $stock_val  +=$sv;    

		$net_amount += ($_POST['2_'.$i]*$_POST['1_'.$i]) - $_POST['3_'.$i];
                
                $this->insert_subitem($_POST['h_'.$i],$lid,$a["no"],$_POST['date'],$_POST['stores'],$this->sd['bc'],$_POST['ref_no'],$_POST['1_'.$i],$_POST['supplier'],$_POST['invoice_no']);
	    }
        }
	
	$net_amount -= $_POST['discount'];
        
	if(count($a_det)){ $this->db->insert_batch($this->tb_det, $a_det); }
	
        if(count($pur_trance)){ $this->db->insert_batch($this->t_purchse_trance, $pur_trance); }
        
	if(count($a_move)){ $this->db->insert_batch($this->tb_trance, $a_move); }
	
	if((int)$_POST['invoice_no'] > 0){
	    $a2 = array(
		"id"=>$lid,
		"module"=>'PUR_RET',
		"supplier"=>$_POST['supplier'],
		"dr_trnce_code"=>"PURCHASE",
		"dr_trnce_no"=>$_POST['invoice_no'],
		"cr_trnce_code"=>"PURCHASE_RET",
		"cr_trnce_no"=>$a['no'],
		"cr_amount"=>$net_amount,
		"bc"=>$this->sd['bc'],
		"oc"=>$this->sd['oc'],
		"date"=>$_POST['date']
	    );
	    
	    $this->db->insert($this->tb_acc_trance, $a2);
	}else{
	    $a2 = array(
		"id"=>$lid,
		"module"=>'PUR_RET',
		"supplier"=>$_POST['supplier'],
		"dr_trnce_code"=>"PURCHASE_RET",
		"dr_trnce_no"=>$a['no'],
		"cr_trnce_code"=>"PURCHASE_RET",
		"cr_trnce_no"=>$a['no'],
		"cr_amount"=>$net_amount,
		"bc"=>$this->sd['bc'],
		"oc"=>$this->sd['oc'],
		"date"=>$_POST['date']
	    );
	    
	    $this->db->insert($this->tb_acc_trance, $a2);
	}
	
	$this->db->trans_complete();
	////Account Section ---------------------------------------------------------------------//
	$config = array(
	    "id" => $lid,
	    "no" => $a['no'],
	    "type" => "PURCHASE_RET",
	    "date" => $_POST['date'],
	    "ref_no" => $_POST['ref_no']
	);
	
	$this->load->model('account');
	$this->account->set_data($config);
	
	$des = "Supplier : ".$_POST['supplier'];
	
	$this->account->set_value($des, $net_amount, "dr", "creditor_control");
	$this->account->set_value($des, $net_amount, "cr", "purchase_return");
        
        $this->account->set_value($des, $stock_val, "cr", "stock");
	$this->account->set_value($des, $stock_val, "dr", "cost_of_sales");
        

	$this->account->send();
	
	////End---------------------------------------------------------------------------------//
	redirect(base_url()."?action=t_purchase_return&print=".$lid);
    }
    
      private function get_batch_stock($item,$qty,$lid,$no,$date,$store,$ref_no,$supplier,$invoice_no)
     {
          $trance_type=$pur_price='';
          $sv=0;
          
          $sql="SELECT
                    `item_code`
                  , `out_quantity` AS qty
                  , `batch_no`
                  , `pur_price`
              FROM
                 `t_item_movement`
                WHERE `item_code`='$item' AND `trance_type`='PUR' AND `trance_id`='$invoice_no' 
                ORDER BY batch_no ASC";
          
           $qry=$this->db->query($sql);
           $rr=$qry->first_row();
           
           
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
          
                $a_move[] = array(
		    "id"=>$lid,
		    "trance_id"=>$no,
		    "trance_type"=>'PUR_RET',
		    "item_code"=>$item,
		    "out_quantity"=>$qty,
		    "date"=>$date,
		    "stores"=>$store,
		    "bc"=>$this->sd['bc'],
		    "ref_no"=>$ref_no,
                    "batch_no"=>$rr->batch_no,
                    "sal_price"=>$ms,
                    "pur_price"=>$pur_price,   
                    "avg_price"=>$avg_price,
                    "description"=>$supplier
		);
                
                if(count($a_move)){ $this->db->insert_batch($this->tb_trance, $a_move); }
                
                $sv=$sv+($pur_price*$qty);
                return array("sv" => $sv);
          
      }
    
    
    
      public function insert_subitem($item_code,$lid,$trance_no,$date,$store,$bc,$ref_no,$item_qty,$supplier,$invoice_no)
    {
        
        $qty=$qt=0;
        $trance_type=$pur_price='';
        $sv=0;
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
          
          $sql="SELECT
                    `item_code`
                  , `out_quantity` AS qty
                  , `batch_no`
                  , `pur_price`
              FROM
                 `t_item_movement`
                WHERE `item_code`='$item_code' AND `trance_type`='PUR' AND `trance_id`='$invoice_no' 
                ORDER BY batch_no ASC";
          
            $qry=$this->db->query($sql);
            $rr=$qry->first_row();
           
           
                $sd="SELECT purchase_price,max_sales FROM m_items WHERE `code`='$item_code'";
                $qsd=$this->db->query($sd);
                $s=$qsd->first_row();
                $ms=$s->max_sales;


                $sql="SELECT avg_price FROM m_items WHERE `code`='$item_code'";
                $q=$this->db->query($sql);
                $r=$q->first_row();
                $avg_price=$r->avg_price;

                $sqlo="SELECT `pur_price` FROM `t_batch` WHERE `item_code`='$item_code' AND `batch_code`='$rr->batch_no' AND bc='".$this->sd['bc']."' GROUP BY batch_code";
                $qryo=$this->db->query($sqlo);
                $o=$qryo->first_row();
                $pur_price=$o->pur_price;
          
                $qt=$item_qty*$qty;
                
                $a_move[] = array(
		    "id"=>$lid,
		    "trance_id"=>$no,
		    "trance_type"=>'PUR_RET',
		    "item_code"=>$item_code,
		    "out_quantity"=>$item_qty,
		    "date"=>$date,
		    "stores"=>$store,
		    "bc"=>$this->sd['bc'],
		    "ref_no"=>$ref_no,
                    "batch_no"=>$rr->batch_no,
                    "sal_price"=>$ms,
                    "pur_price"=>$pur_price,   
                    "avg_price"=>$avg_price,
                    "description"=>$supplier
		);
                
                if(count($a_move)){ $this->db->insert_batch($this->tb_trance, $a_move); }
               }
           }
           
     
    } 
 
    public function load(){
	$this->db->select(array('id', 'date', 'supplier', 'name', 'full_name', 'is_cancel', 'ref_no', 'memo', 'discount', 'purchase_no', 'stores', 'posting'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
        $this->db->limit(1);
	$this->db->join($this->tb_supplier, $this->tb_supplier.".code = ".$this->tb_sum.".supplier", "INNER");
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
//        $sqlo="SELECT `in_no` FROM `t_purchse_trance`
//                WHERE `trance_code`='PUR_RET'  
//                GROUP BY `in_no`";
//        $query=$this->db->query($sqlo);
        
        $s= "<select id='invoice_no' name='invoice_no'>";
        //foreach($query->result() as $r){
           $s .= "<option title=".$a["sum"]->purchase_no." value=".$a["sum"]->purchase_no.">".$a["sum"]->purchase_no."</option>";
        //}
        $s .="<select>";
        
        $a["inv"]= $s;
        
        
        $this->db->select(array('quantity', $this->tb_det.'.cost', $this->tb_det.'.discount', $this->tb_det.'.item_code', $this->tb_items.'.description', $this->tb_items.'.is_measure'));
	$this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
        $this->db->where($this->tb_det.".id", $a["sum"]->id);
        $a['det'] = $this->db->get($this->tb_det)->result();
        
        echo json_encode($a);
    }
    
 
    
    public function load_subitem(){
        $balance=0;$a= array();
        $sql="SELECT
                `item_code`
                , `in_no`
                , SUM(`in_quantity`)-SUM(`out_quantity`) balance
                , SUM(out_quantity) as out_quantity
            FROM
                `t_purchse_trance`
               WHERE `in_no`='".$_POST['id']."'
                 GROUP BY `in_no`";
          
        //echo $sql;exit;
        
        $query=$this->db->query($sql);
        $r=$query->first_row();
        if(empty($r->out_quantity))
        {
            $a['a']='0';
        }
        else{
        $a['a']=$r->out_quantity;
        }   
 
       echo json_encode($a);
    }
    
    
    public function delete(){
	$a = true;
        
        $this->db->where("id", $_POST['id']);
        $this->db->where("trance_type", "PUR_RET");
        if(! $this->db->delete($this->tb_trance)){
            $a = false;
        }
        
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'PUR_RET');
	if(! $this->db->delete($this->tb_acc_trance)){
	    $a = false;
	}
        
	$this->db->where('trance_id', $_POST['id']);
	$this->db->where('trance_type', 'PUR_RET');
	if(! $this->db->delete($this->t_purchse_trance)){
	    $a = false;
	}
        
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
	$this->load->model('account');
	$this->account->delete($_POST['id'], "PURCHASE_RET");
	
	$this->load->database("default", true);
	
        echo $a;
    }
    
    public function load_return(){
           $balance=0;$a= array();
        $sql="SELECT
                `item_code`
                , `in_no`
                , SUM(`in_quantity`)-SUM(`out_quantity`) balance
                , out_no
            FROM
                `t_purchse_trance`
               WHERE `in_no`='".$_POST['id']."'
                 GROUP BY `in_no`";
          
        //echo $sql;exit;
        
        $query=$this->db->query($sql);
        $r=$query->first_row();
        $a['a']=$r->balance;
           
 
       echo json_encode($a);
    }
    
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'PUR_RET');
	$this->db->delete($this->tb_acc_trance);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('trance_type', 'PUR_RET');
	$this->db->delete($this->tb_trance);
	
        $this->db->where('trance_id', $_POST['hid']);
	$this->db->where('trance_type', 'PUR_RET');
	$this->db->delete($this->t_purchse_trance);

	$this->load->model('account');
	$this->account->delete($_POST['hid'], "PURCHASE_RET");
	
	$this->load->database("default", true);
    }
    
    public function select(){
        
        $sql="SELECT

                `t_purchse_sum`.`no`	
                ,SUM(`t_purchse_trance`.`in_quantity`-`t_purchse_trance`.`out_quantity`) AS qty
            FROM
               `t_purchse_trance`
                INNER JOIN `t_purchse_sum` 
                    ON (`t_purchse_trance`.`in_no` = `t_purchse_sum`.`no`)
                INNER JOIN `t_purchse_det` 
                    ON (`t_purchse_det`.`id` = `t_purchse_sum`.`id` AND  `t_purchse_trance`.`item_code`=`t_purchse_det`.`item_code`)
            WHERE `t_purchse_trance`.`sup_id`='".$_POST['sup']."' AND t_purchse_sum.is_cancel=0
            GROUP BY `t_purchse_trance`.`sup_id`,`in_no`
            HAVING qty>0";
        
        
        $query = $this->db->query($sql);
        $result = $query->result();
        echo "<select id='invoice_no' name='invoice_no'>";
        echo "<option value='0' id='advance_0'>---</option>";
        $x=1;
        foreach($result as $r){
            echo "<option value='".$r->no."' id='invoice_".$x++."' title='".$r->no."'>".$r->no."</option>";
        }
        echo "</select>";   
        
        
    }

    public function print_view(){
        $this->load->library('useclass');
	$a['title'] = 'Purchase Return';
        
        $this->db->select(array(
            'code',
            'description',
            'quantity',
            $this->tb_det.'.cost',
            '(quantity * '.$this->tb_det.'.cost) AS total'
        ));
        $this->db->where($this->tb_det.'.id', $_GET['id']);
        $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
        $query = $this->db->get($this->tb_det);
        
        if($query->num_rows){
            $this->db->select(array("no", "bc", "date", "name", "is_cancel"));
            $this->db->where('id', $_GET['id']);
            $this->db->limit(1);
	    $this->db->join($this->tb_branch, $this->tb_branch.".code = ".$this->tb_sum.".bc", "INNER");
            $query2 = $this->db->get($this->tb_sum);
            
            $result = $query->result();
            $result2 = $query2->first_row();
	    $a['cancel'] = $result2->is_cancel;
            
            $t = $ct = 0;
            foreach($result as $rr){
                $t += $rr->total;
                $ct += $rr->quantity;
            }
            
            $r = new stdClass();
            $r->code = "";
            $r->description = "";
            $r->quantity = array("data"=>$ct, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            $r->cost = array("data"=>0, "style"=>"color : #FFF; border : none;");
            $r->total = array("data"=>$t, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            
            array_push($result, $r);
            
            $code = array("data"=>"Item Code", "style"=>"text-align: left; width:80px", "chalign"=>"text-align: left;");
            $name = array("data"=>"Item Name","style"=>"text-align: left;", "chalign"=>"text-align: left;");
            $qun = array("data"=>"Quantity", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $cost = array("data"=>"Cost", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            //$discount = array("data"=>"Discount(%)", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $total = array("data"=>"Total", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
            
            $heading = array($code, $name, $qun, $cost, $total);//, $discount
            
            $code = array("data"=>"code", "total"=>false, "format"=>"text");
            $name  = array("data"=>"description", "total"=>false, "format"=>"text");
            $qun  = array("data"=>"quantity", "total"=>false, "format"=>"number");
            $cost  = array("data"=>"cost", "total"=>false, "format"=>"amount");
            $discount  = array("data"=>"discount", "total"=>false, "format"=>"amount");
            $total  = array("data"=>"total", "total"=>false, "format"=>"amount");
            
            $field = array($code, $name, $qun, $cost, $total);//, $discount
            
            $page_rec = 30;
            
            $header  = array("data"=>$this->useclass->r_header("Purchase Return : ".$result2->no." | Branch : ".$result2->name." (".$result2->bc.")<br /> Date : ".$result2->date), "style"=>"");
            $footer  = array("data"=>"<div style='text-align: right; font-size: 12px;'>.....................<br />Signature</div><br /><br /><br /><br /><br /><hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
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
                        "footer"=>25,
                        "header_txt"=>$header,
                        "footer_txt"=>$footer,
                        "page_no"=>$page_no,
                        "header_of"=>false
                        );
            
            $this->load->library('greport', $data);
                
            $a['view'] = $this->greport->_print();
        }else{
            $a['view'] = "No Purchase Order";
        }
        
        return $a;
    }
}