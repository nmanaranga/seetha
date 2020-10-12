 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_sales_return extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_acc_trance;
    private $tb_items;
    private $tb_cus;
    private $tb_branch;
    private $h = 297;
    private $w = 210;
	
	private $a2;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_sales_return_sum'];
	$this->tb_det = $this->tables->tb['t_sales_return_det'];
	$this->tb_trance = $this->tables->tb['t_item_movement'];
	$this->tb_acc_trance = $this->tables->tb['t_customer_acc_trance'];
	$this->tb_items = $this->tables->tb['m_items'];
	$this->tb_cus = $this->tables->tb['m_customer'];
	$this->tb_branch = $this->tables->tb['s_branches'];
  $this->tb_sales_trance = $this->tables->tb['t_sales_trance'];
    }
    
    public function base_details(){
	$this->load->model('m_customer');
	$this->load->model('m_sales_ref');
	$this->load->model('m_stores');
	
	$a['cus'] = $this->m_customer->select();
	$a['ref'] = $this->m_sales_ref->select();
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

	if(! isset($_POST['stores'])){ $_POST['stores'] = 0; }
	if(! isset($_POST['ref_no'])){ $_POST['ref_no'] = 0; }

	$this->db->trans_start();
	
	$a = array(
	    "date"=>$_POST['date'],
	    "ref_no"=>$_POST['ref_no'],
	    "customer"=>$_POST['customer'],
	    "sales_ref"=>$_POST['sales_ref'],
	    "invoice_no"=>$_POST['invoice_no'],
	    "trance_type"=>$_POST['sale'],
	    "stores"=>$_POST['stores'],
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
        $trance_type='';
	$avg_price=$stock_val=$net_amount = 0;
        for($i=0; $i<40; $i++){
            if($_POST['d0_'.$i] != "" && $_POST['d0_'.$i] != "0"){
		$a_det[] = array(
		    "id"=>$lid,
		    "item_code"=>$_POST['d0_'.$i],
		    "cost"=>$_POST['d4_'.$i],
		    "quantity"=>$_POST['d3_'.$i]
		);

                
        $a7[] = array(
		    "cus_id"=>$_POST['customer'],
		    "date"=>$_POST['date'],
		    "item_code"=>$_POST['d0_'.$i],
		    "in_no"=>$_POST['invoice_no'],
		    "in_qty"=>'0',
		    "out_no"=>$a['no'],
		    "out_qty"=>$_POST['d3_'.$i],
		    "trance_code"=>'SALE_RET',
		    "trance_no"=>$lid,
		    "pay_type"=>$_POST['sale'],
		    "bc"=>$this->sd['bc']);
		
                $sv=$this->get_batch_stock($_POST['d0_'.$i],$_POST['d3_'.$i],$lid,$a['no'],$_POST['date'],$_POST['stores'],$_POST['ref_no'],$_POST['customer'],$_POST['sale'],$_POST['invoice_no']);
                $sv=$sv['sv'];
                $stock_val  +=$sv;
		
                $this->insert_subitem_batch_stock($_POST['d0_'.$i],$lid,$a["no"],$_POST['date'],$_POST['stores'],$this->sd['bc'],$_POST['ref_no'],$_POST['d3_'.$i],$_POST['customer'],$a['no'],$_POST['sale'],$_POST['invoice_no']);
                
                $sql="SELECT avg_price FROM m_items WHERE `code`='".$_POST['d0_'.$i]."'";
                $q=$this->db->query($sql);
                $r=$q->first_row();
                $avg_price=$r->avg_price;
                
		$net_amount += $_POST['d4_'.$i]*$_POST['d3_'.$i];
	    }
            
            
        }

	if(count($a_det)){ $this->db->insert_batch($this->tb_det, $a_det); }
        if(count($a7)){ $this->db->insert_batch($this->tb_sales_trance, $a7);}
        
        
            if($_POST['sale']=='0')
             {
                 $trance_type='SALES';
             }
             else
             {
                 $trance_type='CR_SALES';
             } 

	if((int)$_POST['invoice_no'] > 0){
	    $a_acc = array(
		"id"=>$lid,
		"module"=>'SALES_RET',
		"customer"=>$_POST['customer'],
		"dr_trnce_code"=>$trance_type,
		"dr_trnce_no"=>$_POST['invoice_no'],
		"cr_trnce_code"=>"SALES_RET",
		"cr_trnce_no"=>$a["no"],
		"cr_amount"=>$net_amount,
		"bc"=>$this->sd['bc'],
		"oc"=>$this->sd['oc'],
		"date"=>$_POST['date']
	    );
	    
	    $this->db->insert($this->tb_acc_trance, $a_acc);
	}else{
	    $a_acc = array(
		"id"=>$lid,
		"module"=>'SALES_RET',
		"customer"=>$_POST['customer'],
		"dr_trnce_code"=>"SALES_RET",
		"dr_trnce_no"=>$a["no"],
		"cr_trnce_code"=>"SALES_RET",
		"cr_trnce_no"=>$a["no"],
		"cr_amount"=>$net_amount,
		"bc"=>$this->sd['bc'],
		"oc"=>$this->sd['oc'],
		"date"=>$_POST['date']
	    );
	    
	    $this->db->insert($this->tb_acc_trance, $a_acc);
	}
        
        
	////Account Section ---------------------------------------------------------------------//
	$config = array(
	    "id" => $lid,
	    "no" => $a['no'],
	    "type" => "SALES_RET",
	    "date" => $_POST['date'],
	    "ref_no" => $_POST['ref_no']
	);
	
	$this->load->model('account');
	$this->account->set_data($config);
	
	$des = "Sale Customer : ".$_POST['customer'];
	
	$this->account->set_value($des, $net_amount, "cr", "debtor_control");
	$this->account->set_value($des, $net_amount, "dr", "sales_return");
        $this->account->set_value($des, $stock_val, "dr", "stock");
        $this->account->set_value($des, $stock_val, "cr", "cost_of_sales");
	
	$this->account->send();
	$this->db->trans_complete();
	////End---------------------------------------------------------------------------------//
	

        redirect(base_url()."?action=t_sales_return&print=".$lid);
    }
    
         private function get_batch_stock($item,$qty,$lid,$no,$date,$store,$ref_no,$customer,$sales_type,$invoice_no)
        {
            $trance_type=$pur_price='';
            $sv=0;
            
            if($sales_type=='0')
            {
                $trance_type='SALES';
            }
            else
            {
                $trance_type='CR_SALES';
            }    
            
            $sql="SELECT
                        `item_code`
                      , `out_quantity` AS qty
                      , `batch_no`
                      , `pur_price`
                  FROM
                     `t_item_movement`
                    WHERE `item_code`='$item' AND `trance_type`='$trance_type' AND `trance_id`='$invoice_no' 
                    GROUP BY batch_no,item_code
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
                                    "trance_type"=>'SALES_RET',
                                    "item_code"=>$item,
                                    "out_quantity"=>0,
                                    "in_quantity"=>$rr->qty,
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
                                    "trance_type"=>'SALES_RET',
                                    "item_code"=>$item,
                                    "out_quantity"=>0,
                                    "in_quantity"=>$qty,
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
    
    
    public function insert_subitem_batch_stock($item_code,$lid,$trance_no,$date,$store,$bc,$ref_no,$item_qty,$customer,$no,$sales_type,$invoice_no)
    {
           $trance_type='';
           $a2=array ();
        
            if($sales_type=='0')
            {
                $trance_type='SALES';
            }
            else
            {
                $trance_type='CR_SALES';
            }    

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

               
               $sql="SELECT
                        `item_code`
                      , `out_quantity` AS qty
                      , `batch_no`
                      , `pur_price`
                  FROM
                     `t_item_movement`
                    WHERE `item_code`='".$r->sub_item_code."' AND `trance_type`='$trance_type' AND `trance_id`='$invoice_no' 
                    GROUP BY batch_no,item_code
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
                                    "trance_type"=>'SALES_RET',
                                    "item_code"=>$sub,
                                    "in_quantity"=>$rr->qty,
                                    "out_quantity"=>0,
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
                                    "trance_type"=>'SALES_RET',
                                    "item_code"=>$sub,
                                    "in_quantity"=>$qt,
                                    "out_quantity"=>0,
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
	$this->db->select(array('id', 'date', 'customer', 'name', 'outlet_name', 'sales_ref', 'is_cancel', 'ref_no',  'stores', 'invoice_no','trance_type'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
	$this->db->join($this->tb_cus, $this->tb_cus.".code = ".$this->tb_sum.".customer", "INNER");
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
        $s='';
        
//	$sqlo="SELECT `in_no` FROM `t_sales_trance`
//                WHERE `trance_code`='SALE_RET' AND pay_type='".$a['sum']->trance_type."' 
//                GROUP BY `in_no`";
//        $query=$this->db->query($sqlo);
  
        $s= "<select id='invoice_no' name='invoice_no'>";
        //foreach($query->result() as $r){
            $s .= "<option title=".$a["sum"]->invoice_no." value=".$a["sum"]->invoice_no.">".$a["sum"]->invoice_no."</option>";
        //}
        $s .="<select>";
        
        $a["inv"]= $s;
        
        if(isset($a["sum"]->id)){
	    $this->db->select(array('quantity', $this->tb_det.'.cost', $this->tb_det.'.item_code', $this->tb_items.'.description', $this->tb_items.'.is_measure'));
	    $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
	}
	
        
        echo json_encode($a);
    }
    
    public function delete(){
	$a = true;
        
        $this->db->where("id", $_POST['id']);
        $this->db->where("trance_type", "SALES_RET");
        if(! $this->db->delete($this->tb_trance)){
            $a = false;
        }
        
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'SALES_RET');
	if(! $this->db->delete($this->tb_acc_trance)){
	    $a = false;
	}
        
        $this->db->where('trance_no', $_POST['id']);
	$this->db->where('trance_code', 'SALE_RET');
	if(! $this->db->delete($this->tb_sales_trance)){
           $a = false;
	}
        
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
	$this->load->model('account');
	$this->account->delete($_POST['id'], "SALES_RET");
	
	$this->load->database("default", true);
	
        echo $a;
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('module', 'SALES_RET');
	$this->db->delete($this->tb_acc_trance);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('trance_type', 'SALES_RET');
	$this->db->delete($this->tb_trance);
        
	$this->db->where('trance_no', $_POST['hid']);
	$this->db->where('trance_code', 'SALE_RET');
	$this->db->delete($this->tb_sales_trance);
	
	$this->load->model('account');
	$this->account->delete($_POST['hid'], "SALES_RET");
	
	$this->load->database("default", true);
	
    }
    
    public function select(){
	
       
        
        if($_POST['sales']=='0')
        {
        $sql="SELECT
                `t_cash_sales_sum`.`no`	
                ,SUM(`t_sales_trance`.`in_qty`-`t_sales_trance`.`out_qty`) AS qty
            FROM
               `t_sales_trance`
                INNER JOIN `t_cash_sales_sum` 
                    ON (`t_sales_trance`.`in_no` = `t_cash_sales_sum`.`no`)
                INNER JOIN `t_cash_sales_det` 
                    ON (`t_cash_sales_det`.`id` = `t_cash_sales_sum`.`id` AND  `t_sales_trance`.`item_code`=`t_cash_sales_det`.`item_code`)
            WHERE `t_sales_trance`.`cus_id`='".$_POST['cus']."' AND `t_sales_trance`.`pay_type`='0'      
            GROUP BY `t_sales_trance`.`cus_id`,`in_no`,`pay_type`  
            HAVING qty>0";
        
        //echo $sql;exit;
        
        }
        else
        {
	    $sql="SELECT
                `t_sales_sum`.`no`	
                ,SUM(`t_sales_trance`.`in_qty`-`t_sales_trance`.`out_qty`) AS qty
            FROM
               `t_sales_trance`
                INNER JOIN `t_sales_sum` 
                    ON (`t_sales_trance`.`in_no` = `t_sales_sum`.`no`)
                INNER JOIN `t_sales_det` 
                    ON (`t_sales_det`.`id` = `t_sales_sum`.`id` AND  `t_sales_trance`.`item_code`=`t_sales_det`.`item_code`)
            WHERE `t_sales_trance`.`cus_id`='".$_POST['cus']."' AND `t_sales_trance`.`pay_type`='1'      
            GROUP BY `t_sales_trance`.`cus_id`,`in_no`,`pay_type`   
            HAVING qty>0";
        }

        //echo $sql;exit;
        
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
	$a['title'] = 'Sales Return';
        
        /*               $sql="SELECT
    `m_page_setup`.`height`
    , `m_page_setup`.`weight`
    , `m_page_setup_module`.`module`
FROM
    `m_page_setup_module`
    INNER JOIN `m_page_setup` 
        ON (`m_page_setup_module`.`category` = `m_page_setup`.`category`)
        WHERE `m_page_setup_module`.`module`='027'";
         
        $qry=$this->db->query($sql);
        $r=$qry->first_row();
        
        if(empty($r->height))
        {
            $h=297;
            $w=210;
        }
        else {
            $h=$r->height;
            $w=$r->weight;
        }*/

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
            
            $header  = array("data"=>$this->useclass->r_header("Sales Return : ".$result2->no." | Branch : ".$result2->name." (".$result2->bc.")<br /> Date : ".$result2->date), "style"=>"");
            $footer  = array("data"=>"<div style='text-align: right; font-size: 12px;'>.....................<br />Signature</div><br /><br /><br /><br /><br /><hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
            $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
            $data = array(
                        "dbtem"=>$this->useclass->report_style(),
                        "data"=>$result,
                        "field"=>$field,
                        "heading"=>$heading,
                        "page_rec"=>$page_rec,
                        "height"=>$h,
                        "width"=>$w,
                        "header"=>35,
                        "footer"=>25,
                        "header_txt"=>$header,
                        "footer_txt"=>$footer,
                        "page_no"=>$page_no,
                        "header_of"=>false
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