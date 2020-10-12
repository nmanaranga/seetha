<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_purchase extends CI_Model {
    
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
	
	$this->tb_sum = $this->tables->tb['t_purchse_sum'];
	$this->tb_det = $this->tables->tb['t_purchse_det'];
	$this->tb_trance = $this->tables->tb['t_item_movement'];
	$this->tb_po_trance = $this->tables->tb['t_purchse_order_trance'];
	$this->tb_acc_trance = $this->tables->tb['t_supplier_acc_trance'];
	$this->t_purchse_trance = $this->tables->tb['t_purchse_trance'];
	$this->tb_items = $this->tables->tb['m_items'];
	$this->tb_supplier = $this->tables->tb['m_supplier'];
	$this->tb_cost_log = $this->tables->tb['t_cost_log'];
	$this->tb_branch = $this->tables->tb['s_branches'];
        $this->tb_batch = $this->tables->tb['t_batch'];
	
	
    }
    
    public function base_details(){
	$this->load->model('m_supplier');
	$this->load->model('t_purchase_order');
	$this->load->model('m_stores');
	
	
	$a['sup'] = $this->m_supplier->select();
	$a['max_no'] = $this->max_no();
	$a['po_no'] = $this->t_purchase_order->select();
	$a['stores'] = $this->m_stores->select();
	$a['sd'] = $this->sd;
	
	return $a;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['bc']);
	$this->db->select_max("no");
	
	return $this->db->get($this->tb_sum)->first_row()->no+1;
    }
    
    /*private function calculate_average_price($item_code,$grn_qty,$grn_val)
    {
        $c_avg_price=$avg_price=$current_stock=$current_stock_value=$grn_value=$current_avg_price_val=$current_avg_price=0;
           
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
        
       
        
        $c_avg_price=number_format($current_avg_price_val, 2, '.', '');
        
        
        
        //$a = array('avg_price' => $current_avg_price);

        $sq2="UPDATE m_items SET avg_price='$c_avg_price' WHERE code='".$item_code."' LIMIT 1";
        $this->db->query($sq2);
       
        //echo $current_avg_price;exit;
        
        return $current_avg_price;

    }*/
    
        private function calculate_average_price($item_code,$grn_qty,$grn_val,$date)
    {
        $avg_price=$current_stock=$current_stock_value=$grn_value=$current_avg_price_val=$current_avg_price=0;
           
        $sq="SELECT `avg_price` FROM `m_items` WHERE `code`='$item_code'";
        $q=$this->db->query($sq);
        $r = $q->first_row();
        $avg_price = $r->avg_price;
        
        $sq1="SELECT ifnull(SUM(in_quantity-out_quantity),0) AS cs FROM t_item_movement WHERE item_code='$item_code' AND `date`< '$date' LIMIT 1";
        $q1=$this->db->query($sq1);       
        $r1 = $q1->first_row();
        $current_stock = $r1->cs;   
   
        $current_stock_value=$avg_price*$current_stock;        
        $grn_value=$grn_qty*$grn_val;    

       // echo $item_code.'---->'.($grn_qty.'****'.$current_stock).'~~';
        
 
        $current_avg_price_val=($current_stock_value+$grn_value)/($grn_qty+$current_stock);  
        
        
        $current_avg_price=number_format($current_avg_price_val, 2, '.', '');        
       

        $sq2="UPDATE m_items SET avg_price='$current_avg_price_val' WHERE code='".$item_code."' LIMIT 1";
        $this->db->query($sq2);       
           
        return $current_avg_price;
    } 
    
    public function check_is_payment_voucher()
    {
        $sql="SELECT SUM(`dr_amount`) AS dr_amount,SUM(`cr_amount`) AS `cr_amount`,cr_trnce_no
                FROM `t_supplier_acc_trance`
                WHERE `supplier`='".$_POST['sup']."' AND `dr_trnce_no`=".$_POST['id']."
                GROUP BY `dr_trnce_no`";
        
        $qry=$this->db->query($sql);
        $r=$qry->first_row();
        if(!empty($r->dr_amount))
        {
            if($r->cr_amount>0)
            {
                echo $r->cr_trnce_no;
            }
            else
            {
                echo '0';
            }    
        } 
        else
        {
            echo '0';
        }    
 
    }

    public function load_subitem(){
           $balance=0;$a= array();
        $sql="SELECT
                `item_code`
                , `in_no`
                , SUM(`in_quantity`)-SUM(`out_quantity`) balance
                , out_no
            FROM
                `t_purchse_order_trance`
               WHERE `in_no`='".$_POST['id']."'
                 GROUP BY `in_no`";
          
        //echo $sql;exit;
        
        $query=$this->db->query($sql);
        $r=$query->first_row();
        $a['a']=$r->balance;
           
 
       echo json_encode($a);
    }
    
            public function check_is_used_batch()
    {
        $sql="SELECT * FROM `t_batch` WHERE id='$_POST[id]' AND trans_code='PUR' GROUP BY `batch_code`";
        //echo $sql;exit;
        
        $qry=$this->db->query($sql);
        $r=$qry->first_row();
        $batch=$r->batch_code;
        
        $sqlo="SELECT COUNT(*) AS cc FROM `t_item_movement` WHERE bc='".$this->sd['bc']."' AND `batch_no`='$batch'
                AND (`trance_type`='SALES' OR `trance_type`='SALES_ORDER')";
        
        //echo $sqlo;exit;
        
        $qryo=$this->db->query($sqlo);
        $rr=$qryo->first_row();
        
        if($rr->cc>0)
        {
            echo '1';
          
        }
        else
        {
            echo '0';
        }    
  
    }
    
    
    public function save(){
	$sa = array(
	    "stores"=>$_POST['stores']
	);
	
	$this->session->set_userdata($sa);
	
	$this->db->trans_start();

	if($_POST['discount'] == 0){ $_POST['discount'] = 0.00; }
	
        
	
	$a = array(
	    "date"=>$_POST['date'],
	    "ref_no"=>$_POST['ref_no'],
	    "memo"=>$_POST['memo'],
	    "supplier"=>$_POST['supplier'],
	    "discount"=>$_POST['discount'],
	    "stores"=>$_POST['stores'],
	    "invoice_no"=>$_POST['invoice_no'],
	    "po_no"=>$_POST['pono'],
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
        
	$pur_trance=$a_tranc=$a_det = $a_move = array();
	$net_amount = 0;
        $avg_price=0;
        $stock_val=0;
        
        for($i=0; $i<25; $i++){
            if($_POST['h_'.$i] != "" && $_POST['h_'.$i] != "0"){

		if($_POST['3_'.$i] == ""){ $_POST['3_'.$i] = 0.00; }
		if($_POST['1_'.$i] == ""){ $_POST['1_'.$i] = 0.00; }
		if($_POST['4_'.$i] == ""){ $_POST['4_'.$i] = 0.00; }
		if($_POST['5_'.$i] == ""){ $_POST['5_'.$i] = 0.00; }
		
		$a_det[] = array(
		    "id"=>$lid,
		    "item_code"=>$_POST['h_'.$i],
		    "cost"=>$_POST['2_'.$i],
		    "quantity"=>$_POST['1_'.$i],
		    "discount"=>$_POST['3_'.$i],
		    "discount_pre"=>$_POST['4_'.$i],
		    "foc"=>$_POST['5_'.$i]
		);
		
                 $no=$a["no"];
                
                 $a_tranc[] = array(
		    "trance_id"=>$lid,
		    "trance_type"=>'PUR',
		    "item_code"=>$_POST['h_'.$i],
		    "in_no"=>$_POST['pono'],
		    "in_quantity"=>0,
		    "out_no"=>$no,
		    "out_quantity"=>$_POST['1_'.$i],
		    "date"=>$_POST['date']
		);
                 
                 $pur_trance[] = array(
		    "trance_id"=>$lid,
		    "trance_type"=>'PUR',
                    "sup_id"=>$_POST['supplier'],
		    "item_code"=>$_POST['h_'.$i],
		    "in_no"=>$no,
		    "in_quantity"=>$_POST['1_'.$i],
		    "out_no"=>$no,
		    "out_quantity"=>0,
		    "date"=>$_POST['date']
		);
   
                $avg_price=$this->calculate_average_price($_POST['h_'.$i],$_POST['1_'.$i], ($_POST['2_'.$i] - $_POST['3_'.$i]),$_POST['date']);   

                //$cost = $this->cost_cal($lid, $_POST['h_'.$i], $_POST['1_'.$i], ($_POST['2_'.$i] - $_POST['3_'.$i]));
                //$this->db->where('code', $_POST['h_'.$i]);
                //$this->db->limit(1);
                //$this->db->update($this->tb_items, array("purchase_price"=>$cost));
		
		$in=0;
		    if($_POST['5_'.$i]>0){ $in=$_POST['5_'.$i];}
		    else{  $in=$_POST['1_'.$i];}
		
                $find_batch_data=$this->batch($lid,$_POST['h_'.$i],$_POST['1_'.$i],$_POST['2_'.$i],$_POST['3_'.$i]);
                $batch=$find_batch_data['batch_code'];
                $pur_price=$find_batch_data['pur_price'];
                $stock_val  +=($_POST['1_'.$i]*$pur_price);
                
                         
                
                $sv=$this->insert_subitem($_POST['h_'.$i],$lid,$a["no"],$_POST['date'],$_POST['stores'],$this->sd['bc'],$_POST['ref_no'],$_POST['1_'.$i],$_POST['supplier']);
                $sto_val=$sv['sv'];
             
                $a_move[] = array(
		    		    
		    "id"=>$lid,
		    "trance_id"=>$a['no'],
		    "trance_type"=>'PUR',
		    "item_code"=>$_POST['h_'.$i],
		    "in_quantity"=>$in,
		    "date"=>$_POST['date'],
		    "stores"=>$_POST['stores'],
		    "bc"=>$this->sd['bc'],
		    "pur_price"=>$pur_price,
		    "sal_price"=>$_POST['2_'.$i],
		    "ref_no"=>$_POST['ref_no'],
		    "avg_price"=>$avg_price,
                    "batch_no"=>$batch,
		    "is_subitem"=>0,
                    "description"=>$_POST['supplier']
		);

		$net_amount += ($_POST['2_'.$i]*$_POST['1_'.$i]) - $_POST['3_'.$i];
		
            }
        
        } 
	$net_amount -= $_POST['discount'];
        
	
        
        
	if(count($a_det)){ $this->db->insert_batch($this->tb_det, $a_det); }
        
	if(count($a_tranc)){ $this->db->insert_batch($this->tb_po_trance, $a_tranc); }
        
	if(count($pur_trance)){ $this->db->insert_batch($this->t_purchse_trance, $pur_trance); }
	
	if(count($a_move)){ $this->db->insert_batch($this->tb_trance, $a_move); }
	
	//if(count($a_move_foc)){ $this->db->insert_batch($this->tb_trance, $a_move_foc); }
	
	$a2 = array(
	    "id"=>$lid,
	    "module"=>'PUR',
	    "supplier"=>$_POST['supplier'],
	    "dr_trnce_code"=>"PURCHASE",
	    "dr_trnce_no"=>$a['no'],
	    "cr_trnce_code"=>"PURCHASE",
	    "cr_trnce_no"=>$a['no'],
	    "dr_amount"=>$net_amount,
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc'],
	    "date"=>$_POST['date']
	);
	
	$this->db->insert($this->tb_acc_trance, $a2);
	////Account Section ---------------------------------------------------------------------//
	
	$config = array(
	    "id" => $lid,
	    "no" => $a['no'],
	    "type" => "PURCHASE",
	    "date" => $_POST['date'],
	    "ref_no" => $_POST['ref_no']
	);
	
        $stock_val=$stock_val-$_POST['discount'];
        
        
        
	$this->load->model('account');
	$this->account->set_data($config);
	
	$des = "Sale Supplier : ".$_POST['supplier'];

	$this->account->set_value($des, $net_amount, "cr", "creditor_control");
	$this->account->set_value($des, $net_amount, "dr", "purchase");
        
	$this->account->set_value($des, $stock_val, "dr", "stock");
	$this->account->set_value($des, $stock_val, "cr", "cost_of_sales");
	
	$this->account->send();
	
	////End---------------------------------------------------------------------------------//
	$this->db->trans_complete();
	
	redirect(base_url()."?action=t_purchase&print=".$lid);
    }
  
    
    
    
 public function batch($id,$item_code,$qty,$pur_price,$discount)
    {
        $batch='';
        
        $sql="SELECT IFNULL(MAX(`batch_code`),0)+1 AS max_batch FROM `t_batch` WHERE bc='".$this->sd['bc']."' AND `item_code`='$item_code'";
        $qry=$this->db->query($sql);
        $r=$qry->first_row();
        $batch=$r->max_batch;
        
        $b = array(
		"id"=>$id,
		"trans_code"=>'PUR',
		"item_code"=>$item_code,
		"batch_code"=>$batch,
		"pur_price"=>($pur_price-($discount/$qty)),
		"bc"=>$this->sd['bc'],
                "is_sub_item"=>'0'
	);
        
        $this->db->insert($this->tb_batch, $b);

        return array("batch_code" => $batch,"pur_price"=>($pur_price-($discount/$qty)));
        
    } 
    
        public function load_return(){
	$this->db->select(array('id', 'no', 'date', 'name', 'supplier', 'is_cancel','stores'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
	$this->db->join($this->tb_supplier, $this->tb_supplier.".code = ".$this->tb_sum.".supplier", "INNER");
	
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){

            
            $sql="SELECT 
                    SUM(
                      `t_purchse_trance`.`in_quantity`) - SUM(`t_purchse_trance`.`out_quantity`)
                     AS quantity,
                    `t_purchse_det`.`cost`,
                    `t_purchse_trance`.`item_code`,
                    `t_purchse_det`.`discount`,
                   `t_purchse_det`.`discount_pre`,
                    `m_items`.`description`,
                    `m_items`.`is_measure`,
                   `t_purchse_det`.`foc` 
                  FROM
                    `t_purchse_trance`
                    INNER JOIN `t_purchse_sum`
                      ON (
                        `t_purchse_trance`.`in_no` = `t_purchse_sum`.`no`
                      ) 
                    INNER JOIN `t_purchse_det`
                      ON (
                        `t_purchse_det`.`id` = `t_purchse_sum`.`id` 
                        AND `t_purchse_det`.`item_code` = `t_purchse_trance`.`item_code`
                      ) 
                    INNER JOIN `m_items` 
                      ON `t_purchse_trance`.`item_code` = `m_items`.`code` 
                  WHERE `in_no`= '".$a["sum"]->no."'
                  GROUP BY `t_purchse_trance`.`item_code`,
                    `t_purchse_trance`.`sup_id`
                  HAVING quantity > 0 ";
            
            //echo $sql;exit;
            
            $qry=$this->db->query($sql);
            $a['det'] = $qry->result();
	    
	    
	}
	
        echo json_encode($a);
    }
    
    
    public function insert_subitem($item_code,$lid,$trance_no,$date,$store,$bc,$ref_no,$item_qty,$supplier){
        
        $sv=$qt=0;
        $batch='';
         
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

               $sql="SELECT IFNULL(MAX(`batch_code`),0)+1 AS max_batch FROM `t_batch` WHERE bc='".$this->sd['bc']."' AND `item_code`='".$r->sub_item_code."'";
               
               $qry=$this->db->query($sql);
               $rr=$qry->first_row();
               $batch=$rr->max_batch;
    
               $qty=$r->qty;
               $pp=$r->purchase_price;
               $ap=$r->avg_price;
               $sub=$r->sub_item_code;
               
               $b = array(
                        "id"=>$lid,
                        "trans_code"=>'PUR',
                        "item_code"=>$r->sub_item_code,
                        "batch_code"=>$batch,
                        "pur_price"=>$pp,
                        "bc"=>$this->sd['bc'],
                        "is_sub_item"=>'1'
                );
               
               
               $qt=$item_qty*$qty;
               
               $sql1="INSERT INTO t_item_movement SET id=$lid,trance_id='$trance_no',
               trance_type='PUR',item_code='$sub',in_quantity='$qt',date='$date',
               stores='$store',bc='$bc',pur_price='$pp',sal_price='0', ref_no='0',avg_price='$ap',
               is_subitem='1',batch_no='$batch',description='$supplier'";

              // echo $sql1;exit;
               
               
               $this->db->query($sql1);
               
               $this->db->insert($this->tb_batch, $b);    
               
               $sv+=$qt*$pp;
               
               }
           }
           
           return array("sv"=>$sv);
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
	$this->db->select(array('id', 'date', 'supplier', 'name', 'full_name', 'is_cancel', 'ref_no', 'memo', 'discount', 'stores', 'invoice_no', 'posting','po_no'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
        $this->db->limit(1);
	$this->db->join($this->tb_supplier, $this->tb_supplier.".code = ".$this->tb_sum.".supplier", "INNER");
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){
	    $this->db->select(array('quantity', $this->tb_det.'.cost', $this->tb_det.'.discount', $this->tb_det.'.discount_pre', $this->tb_det.'.item_code',$this->tb_det.'.foc', $this->tb_items.'.description', $this->tb_items.'.is_measure'));
	    $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
	}
        
        echo json_encode($a);
    }
    
       public function load_return_qty(){
	$this->db->select(array('id', 'date', 'supplier', 'name', 'full_name', 'is_cancel', 'ref_no', 'memo', 'discount', 'po_no', 'stores', 'posting'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
        $this->db->limit(1);
	$this->db->join($this->tb_supplier, $this->tb_supplier.".code = ".$this->tb_sum.".supplier", "INNER");
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
        $this->db->select(array('quantity', $this->tb_det.'.cost', $this->tb_det.'.discount', $this->tb_det.'.item_code', $this->tb_items.'.description', $this->tb_items.'.is_measure'));
	$this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
        $this->db->where($this->tb_det.".id", $a["sum"]->id);
        $a['det'] = $this->db->get($this->tb_det)->result();
        
        $sql="SELECT
                      SUM(`t_purchse_trance`.`in_quantity`)-SUM(`t_purchse_trance`.`out_quantity`) AS bal
                      FROM
                          `t_purchse_trance`
                          INNER JOIN `m_items` 
                              ON (`t_purchse_trance`.`item_code` = `m_items`.`code`)   
                          
                              WHERE `in_no`='".$_POST['id']."'
                              GROUP BY `in_no`, `m_items`.`code`";

          $query=$this->db->query($sql);
          $r=$query->result();
          $a['tran']=$r;

        
        echo json_encode($a);
    }
    
    public function delete(){
	$a = true;
        
        $this->db->where("id", $_POST['id']);
        $this->db->where("trance_type", "PUR");
        if(! $this->db->delete($this->tb_trance)){
            $a = false;
        }
        
        $this->db->where('trance_id', $_POST['id']);
	$this->db->where('trance_type', 'PUR');
        if(! $this->db->delete($this->tb_po_trance)){
            $a = false;
        }
        	
        $this->db->where('trance_id', $_POST['id']);
	$this->db->where('trance_type', 'PUR');
        if(! $this->db->delete($this->t_purchse_trance)){
            $a = false;
        }
        	
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'PUR');
	if(! $this->db->delete($this->tb_acc_trance)){
	    $a = false;
	}
	
	$this->db->where('id', $_POST['id']);
	$this->db->where('module', 'PUR');
	if(! $this->db->delete($this->tb_cost_log)){
	    $a = false;
	}else{
	    $this->cost_max_no();
	}
        
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
        $this->db->where('id', $_POST['id']);
	$this->db->where('trans_code', 'PUR');
	if(! $this->db->delete($this->tb_batch)){
	    $a = false;
	}
 
	$this->load->model('account');
	$this->account->delete($_POST['id'], "PURCHASE");
	
	$this->load->database("default", true);
	
        echo $a;
    }
    
    public function set_delete(){
        
    $this->db->where('trance_id', $_POST['hid']);
	$this->db->where('trance_type', 'PUR');
	$this->db->delete($this->tb_po_trance);
        
        $this->db->where('trance_id', $_POST['hid']);
	$this->db->where('trance_type', 'PUR');
	$this->db->delete($this->t_purchse_trance);

        
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
        
	$this->db->where('id', $_POST['hid']);
	$this->db->where('trans_code', 'PUR');
	$this->db->delete($this->tb_batch);
        
	$this->load->model('account');
	$this->account->delete($_POST['hid'], "PURCHASE");
	
	$this->load->database("default", true);
    }
    
    public function print_view(){
        $this->load->library('useclass');
	$a['title'] = 'Purchase';
        
        $this->db->select(array(
            'code',
            'description',
            'quantity',
            $this->tb_det.'.cost',
            $this->tb_det.'.discount',
            '(quantity * '.$this->tb_det.'.cost)- '.$this->tb_det.'.discount AS total'
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
            $r->discount = array("data"=>0, "style"=>"color : #FFF; border : none;");
            $r->total = array("data"=>$t, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
            
            array_push($result, $r);
            
            $code = array("data"=>"Item Code", "style"=>"text-align: left; width:80px", "chalign"=>"text-align: left;");
            $name = array("data"=>"Item Name","style"=>"text-align: left;", "chalign"=>"text-align: left;");
            $qun = array("data"=>"Quantity", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $cost = array("data"=>"Cost", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $discount = array("data"=>"Discount", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $total = array("data"=>"Total", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
            
            $heading = array($code, $name, $qun, $cost,$discount, $total);//, $discount
            
            $code = array("data"=>"code", "total"=>false, "format"=>"text");
            $name  = array("data"=>"description", "total"=>false, "format"=>"text");
            $qun  = array("data"=>"quantity", "total"=>false, "format"=>"number");
            $cost  = array("data"=>"cost", "total"=>false, "format"=>"amount");
            $discount  = array("data"=>"discount", "total"=>false, "format"=>"amount");
            $total  = array("data"=>"total", "total"=>false, "format"=>"amount");
            
            $field = array($code, $name, $qun, $cost,$discount, $total);//, $discount
	    
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
            
            $page_rec = 30;
            
            $header  = array("data"=>$this->useclass->r_header("Good Recieve Note : ".$result2->no." | Branch : ".$result2->name." (".$result2->bc.")<br /> Date : ".$result2->date), "style"=>"");
            $footer  = array("data"=>"<div style='text-align: right; font-size: 12px;'>$sig</div><br /><br /><br /><br /><br /><hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
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
            //print_r($data); exit;
            $this->load->library('greport', $data);
                
            $a['view'] = $this->greport->_print();
        }else{
            $a['view'] = "No Purchase Order";
        }
        
        return $a;
    }
}