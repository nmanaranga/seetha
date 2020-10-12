<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_stock_adjustment extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_items;
    private $h = 297;
    private $w = 210;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_stock_adj_sum'];
	$this->tb_det = $this->tables->tb['t_stock_adj_det'];
	$this->tb_trance = $this->tables->tb['t_item_movement'];
	$this->tb_items = $this->tables->tb['m_items'];
        $this->tb_batch = $this->tables->tb['t_batch'];
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
	$this->db->trans_start();
	
	$a = array(
	    "date"=>$_POST['date'],
	    "ref_no"=>$_POST['ref_no'],
	    "memo"=>$_POST['memo'],
	    "stores"=>$_POST['stores'],
	    "bc"=>$this->sd['bc'],
	    "oc"=>$this->sd['oc']
	);
	
	$sa = array(
	    "stores"=>$_POST['stores']
	);
	
	$this->session->set_userdata($sa);
	
	if($_POST['hid'] == "" || $_POST['hid'] == 0){
	    $a["no"] = $this->max_no();
	    $this->db->insert($this->tb_sum, $a);
	    $lid = $this->db->insert_id();
	}else{
	    $this->db->where("id", $_POST['hid']);
	    $this->db->limit(1);
	    $this->db->update($this->tb_sum, $a);
	    $lid = $_POST['hid']; $a["no"] = $_POST['id'];
	    $this->set_delete();
	}
	$svdr=$svcr=$stock_val_cr=$stock_val_dr=0;
        $a_det = $a_move = array();
	
        for($i=0; $i<25; $i++){
            if($_POST['h_'.$i] != "" && $_POST['h_'.$i] != "0"){
                
                if($_POST['3_'.$i] == ""){ $_POST['3_'.$i] = 0.00; }
		if($_POST['1_'.$i] == ""){ $_POST['1_'.$i] = 0.00; }
		if($_POST['4_'.$i] == ""){ $_POST['4_'.$i] = 0.00; }
		if($_POST['5_'.$i] == ""){ $_POST['5_'.$i] = 0.00; }

                $a_det[] = array(
		    "id"=>$lid,
		    "item_code"=>$_POST['h_'.$i],
		    "system"=>$_POST['1_'.$i],
		    "actual"=>$_POST['2_'.$i],
		    "balance"=>$_POST['3_'.$i],
		    "purchase"=>$_POST['4_'.$i],
		    "value"=>number_format($_POST['5_'.$i], 2, '.', '')
		);
		
		//print_r($a_det);exit;		
		
		if((double)$_POST['3_'.$i] > 0){
		    $in = (double)$_POST['3_'.$i]; $out = 0;
		}else{
		    $in = 0; $out = (double)$_POST['3_'.$i] * -1;
		}
		
		if((double)$_POST['4_'.$i] > 0){
		    $in = (double)$_POST['4_'.$i]; $out = 0;
		}else{
		    $in = 0; $out = (double)$_POST['4_'.$i] * -1;
		}

               
                if((double)$_POST['1_'.$i]<=0)
                {
                $this->batch($lid,$_POST['h_'.$i],$_POST['3_'.$i],$_POST['4_'.$i]);
                $this->insert_subitem($_POST['h_'.$i],$lid,$a["no"],$_POST['date'],$_POST['stores'],$this->sd['bc'],$_POST['ref_no'],$_POST['3_'.$i]);
                }    
                
                if((double)$_POST['3_'.$i]<0)
                {    
                $svcr=$this->get_batch_stock($_POST['h_'.$i],$_POST['3_'.$i],$lid,$a['no'],$_POST['date'],$_POST['stores'],$_POST['ref_no']);
                $svcr=$svcr['svcr'];
                $stock_val_cr  +=$svcr;
                }
                else {
                $svdr=$this->get_batch_stock2($_POST['h_'.$i],$_POST['3_'.$i],$lid,$a['no'],$_POST['date'],$_POST['stores'],$_POST['ref_no']);
                $svdr=$svdr['svdr'];
                $stock_val_dr  +=$svdr;    
                }
                
                if((double)$_POST['3_'.$i]<0)
                { 
		$this->insert_subitem_batch_stock($_POST['h_'.$i],$lid,$a["no"],$_POST['date'],$_POST['stores'],$this->sd['bc'],$_POST['ref_no'],$_POST['3_'.$i],$a['no']);
                }
                else
                {
                $this->insert_subitem_batch_stock2($_POST['h_'.$i],$lid,$a["no"],$_POST['date'],$_POST['stores'],$this->sd['bc'],$_POST['ref_no'],$_POST['3_'.$i],$a['no']);    
                }    
            }
        }
        
	if(count($a_det)){ $this->db->insert_batch($this->tb_det, $a_det); }
	
	//if(count($a_move)){ $this->db->insert_batch($this->tb_trance, $a_move); }
	
        
        
        $config = array(
	    "id" => $lid,
	    "no" => $a['no'],
	    "type" => "STC_AJD",
	    "date" => $_POST['date'],
	    "ref_no" => $_POST['ref_no']
	);
        
        $this->load->model('account');
	$this->account->set_data($config);
	    
	    $des = "Store : ".$_POST['stores'];

            $this->account->set_value($des, $stock_val_cr, "cr", "stock");
            $this->account->set_value($des, $stock_val_dr, "dr", "stock");
        
        $this->account->send();

	$this->db->trans_complete();
	
	redirect(base_url()."?action=t_stock_adjustment&print=".$lid);
    }

    
        public function batch($id,$item_code,$qty,$pur_price)
        {
        $batch='';
        $a2=array();
        
        
        $sql="SELECT IFNULL(MAX(`batch_code`),0)+1 AS max_batch FROM `t_batch` WHERE bc='".$this->sd['bc']."' AND `item_code`='$item_code'";
       
        //echo $sql;exit;
        
        $qry=$this->db->query($sql);
        $r=$qry->first_row();
        $batch=$r->max_batch;
        
        $b = array(
		"id"=>$id,
		"trans_code"=>'ST_AD',
		"item_code"=>$item_code,
		"batch_code"=>$batch,
		"pur_price"=>$pur_price,
		"bc"=>$this->sd['bc'],
                "is_sub_item"=>'0'
	);
        
        $this->db->insert($this->tb_batch, $b);

        return;
        
        } 
        
        
       public function insert_subitem($item_code,$lid,$trance_no,$date,$store,$bc,$ref_no,$item_qty){
        
        $sv=$qt=0;
        $batch='';
        $b=array();
        
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
               
//               $b[] = array(
//                        "id"=>$lid,
//                        "trans_code"=>'ST_AD',
//                        "item_code"=>$r->sub_item_code,
//                        "batch_code"=>$batch,
//                        "pur_price"=>$pp,
//                        "bc"=>$this->sd['bc'],
//                        "is_sub_item"=>'1'
//                );
               
               $sql="INSERT INTO $this->tb_batch SET id='$lid',
                     trans_code='ST_AD',item_code='$r->sub_item_code',batch_code='$batch',pur_price='$pp',bc='".$this->sd['bc']."' 
                     is_sub_item='1'";
               
               $this->db->query($sql);

               }
           }
          
           
           return;
    } 
    
       private function get_batch_stock($item,$qty,$lid,$no,$date,$store,$ref_no)
        {
            $pur_price='';
            $svcr=0;
            $a2=array();
  
            
            if($qty<0)
            {    
            $sql="SELECT `item_code`,SUM(in_quantity) - SUM(out_quantity) AS qty,batch_no,`pur_price`
                    FROM `t_item_movement`
                    WHERE `item_code`='$item'
                    GROUP BY batch_no,item_code
                    HAVING qty>0
                    ORDER BY batch_no ASC";
            
            $qty=$qty*-1;          

            $qry=$this->db->query($sql);
            
            
            
            
            foreach ($qry->result() AS $rr)
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
                                    "trance_type"=>'STOCK_ADJ',
                                    "item_code"=>$item,
                                    "out_quantity"=>$rr->qty,
                                    "date"=>$date,
                                    "bc"=>$this->sd['bc'],
                                    "stores"=>$store,
                                    "ref_no"=>$ref_no,
                                    "batch_no"=>$rr->batch_no,
                                    "sal_price"=>$ms,
                                    "pur_price"=>$pur_price,   
                                    "avg_price"=>$avg_price,
                                    "description"=>''
                             );
         
                             $svcr=$svcr+($pur_price*$rr->qty);
                            
                             $qty -= $rr->qty;
                             
                             
                            }  
                           else
                            {
        
                            $a2[] = array(
                                    "id"=>$lid,
                                    "trance_id"=>$no,
                                    "trance_type"=>'STOCK_ADJ',
                                    "item_code"=>$item,
                                    "out_quantity"=>$qty,
                                    "date"=>$date,
                                    "bc"=>$this->sd['bc'],
                                    "stores"=>$store,
                                    "ref_no"=>$ref_no,
                                    "batch_no"=>$rr->batch_no,
                                    "sal_price"=>$ms,
                                    "pur_price"=>$pur_price,   
                                    "avg_price"=>$avg_price,
                                    "description"=>''
                             );
                            
                             $svcr=$svcr+($pur_price*$qty);
                             
                             $qty = 0;
                            
                             }
                             
                            }   

                    }
            
                    if(count($a2)){ $this->db->insert_batch($this->tb_trance, $a2);}
                    
                    return array("svcr" => $svcr);

        } 
    
    private function get_batch_stock2($item,$qty,$lid,$no,$date,$store,$ref_no)
    {
            $pur_price='';
            $svdr=0;
            $a2=array();
        
        
                $sql="SELECT `item_code`,MAX(batch_code) as batch_code,`pur_price`
                    FROM `t_batch`
                    WHERE `item_code`='$item' 
                    GROUP BY item_code
                    ORDER BY batch_code ASC LIMIT 1";    

             $qry=$this->db->query($sql);  
             $rr=$qry->first_row();
              
             $sqlo="SELECT `pur_price` FROM `t_batch` WHERE `item_code`='$item' AND `batch_code`='$rr->batch_code' AND bc='".$this->sd['bc']."' GROUP BY batch_code";
                        $qryo=$this->db->query($sqlo);
                        $o=$qryo->first_row();
                        $pur_price=$o->pur_price;
                        
             $sql="SELECT avg_price FROM m_items WHERE `code`='$item'";
                        $q=$this->db->query($sql);
                        $r=$q->first_row();
                        $avg_price=$r->avg_price; 
                        
             $sd="SELECT purchase_price,max_sales FROM m_items WHERE `code`='$item'";
                        $qsd=$this->db->query($sd);
                        $s=$qsd->first_row();
                        $ms=$s->max_sales;           
             
             $a2[] = array(
                    "id"=>$lid,
                    "trance_id"=>$no,
                    "trance_type"=>'STOCK_ADJ',
                    "item_code"=>$item,
                    "in_quantity"=>$qty,
                    "date"=>$date,
                    "bc"=>$this->sd['bc'],
                    "stores"=>$store,
                    "ref_no"=>$ref_no,
                    "batch_no"=>$rr->batch_code,
                    "sal_price"=>$ms,
                    "pur_price"=>$pur_price,   
                    "avg_price"=>$avg_price,
                    "description"=>''
             );

             $svdr=$svdr+($pur_price*$qty);
             $qty=0;
             if(count($a2)){ $this->db->insert_batch($this->tb_trance, $a2);}
                    
             return array("svdr" => $svdr);
        
    }
        
        public function insert_subitem_batch_stock($item_code,$lid,$trance_no,$date,$store,$bc,$ref_no,$item_qty,$no)
    {
	$a2=array();
        
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

               $qt=$item_qty*$qty*-1;
               
               
               $sql="SELECT `item_code`,SUM(in_quantity) - SUM(out_quantity) AS qty,batch_no,`pur_price`
                    FROM `t_item_movement`
                    WHERE `item_code`='".$r->sub_item_code."'
                    GROUP BY batch_no,item_code
                    HAVING qty>0
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
                                    "trance_type"=>'STOCK_ADJ',
                                    "item_code"=>$sub,
                                    "out_quantity"=>$rr->qty,
                                    "date"=>$date,
                                    "bc"=>$this->sd['bc'],
                                    "stores"=>$store,
                                    "ref_no"=>$ref_no,
                                    "batch_no"=>$rr->batch_no,
                                    "sal_price"=>$ms,
                                    "pur_price"=>$pur_price,   
                                    "avg_price"=>$avg_price,
                                    "description"=>''
                             );
         
                             $qt -= $rr->qty;
                            }  
                           else
                            {
                        
                            $a2[] = array(
                                    "id"=>$lid,
                                    "trance_id"=>$no,
                                    "trance_type"=>'STOCK_ADJ',
                                    "item_code"=>$sub,
                                    "out_quantity"=>$qt,
                                    "date"=>$date,
                                    "bc"=>$this->sd['bc'],
                                    "stores"=>$store,
                                    "ref_no"=>$ref_no,
                                    "batch_no"=>$rr->batch_no,
                                    "sal_price"=>$ms,
                                    "pur_price"=>$pur_price,   
                                    "avg_price"=>$avg_price,
                                    "description"=>''
                             );
                                $qt = 0;
                             }
                             
                            }   
        
                    }

               }
           }
           
           if(count($a2)){ $this->db->insert_batch($this->tb_trance, $a2);	}
     
    } 
        public function insert_subitem_batch_stock2($item_code,$lid,$trance_no,$date,$store,$bc,$ref_no,$item_qty,$no)
    {
	$a2=array();
        
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
               
               
               $sql="SELECT `item_code`,MAX(batch_no) AS batch_no,`pur_price`
                    FROM `t_item_movement`
                    WHERE `item_code`='".$r->sub_item_code."'
                    GROUP BY item_code
                    HAVING qty>0
                    ORDER BY batch_no ASC";
 
            $qry=$this->db->query($sql);
            $rr=$qry->first_row();
            
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
 
             

                            $a2[] = array(
                                    "id"=>$lid,
                                    "trance_id"=>$no,
                                    "trance_type"=>'STOCK_ADJ',
                                    "item_code"=>$sub,
                                    "in_quantity"=>$rr->qty,
                                    "date"=>$date,
                                    "bc"=>$this->sd['bc'],
                                    "stores"=>$store,
                                    "ref_no"=>$ref_no,
                                    "batch_no"=>$rr->batch_no,
                                    "sal_price"=>$ms,
                                    "pur_price"=>$pur_price,   
                                    "avg_price"=>$avg_price,
                                    "description"=>''
                             );

               }
           }
           
           if(count($a2)){ $this->db->insert_batch($this->tb_trance, $a2);	}
     
    } 
    
    public function load(){
	$this->db->select(array('id', 'date', 'is_cancel', 'ref_no', 'memo', 'stores', 'posting'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
        $this->db->limit(1);
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){
	    $this->db->select(array('system', 'balance', $this->tb_det.'.actual', $this->tb_det.'.item_code', $this->tb_det.'.purchase',$this->tb_det.'.value',$this->tb_items.'.description', $this->tb_items.'.is_measure'));
	    $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
	}
	
        echo json_encode($a);
    }
    
    public function delete(){
	$a = true;
        
        $this->db->where("id", $_POST['id']);
        $this->db->where("trance_type", "STOCK_ADJ");
        if(! $this->db->delete($this->tb_trance)){
            $a = false;
        }
        
        $this->db->where("id", $_POST['id']);
        $this->db->limit(1);
        if(! $this->db->update($this->tb_sum, array("is_cancel"=>$this->sd['oc']))){
            $a = false;
        }
        
        $this->db->where('id', $_POST['id']);
	$this->db->where('trans_code', 'ST_AD');
	$this->db->delete($this->tb_batch);
        
        $this->load->model('account');
	$this->account->delete($_POST['id'], "STC_AJD", $this->sd['db_code']);
	$this->load->database($this->sd['db'], true);
        
        echo $a;
    }
    
    public function set_delete(){
	$this->db->where('id', $_POST['hid']);
	$this->db->delete($this->tb_det);
	
	$this->db->where('id', $_POST['hid']);
	$this->db->where('trance_type', 'STOCK_ADJ');
	$this->db->delete($this->tb_trance);
        
        $this->db->where('id', $_POST['hid']);
	$this->db->where('trans_code', 'ST_AD');
	$this->db->delete($this->tb_batch);
        
        $this->load->model('account');
	$this->account->delete($_POST['hid'], "STC_AJD", $this->sd['db_code']);
	$this->load->database($this->sd['db'], true);
        
    }
    
    public function print_view(){
        $this->load->library('useclass');
	$a['title'] = 'Stock Adjustment';
        
        $this->db->select(array(
            'code',
            'description',
	    'system',
	    'balance',
	    'value',
	    'actual'
            
            //'(quantity * '.$this->tb_det.'.cost) AS total'
        ));
        $this->db->where($this->tb_det.'.id', $_GET['id']);
        $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
        $query = $this->db->get($this->tb_det);
        
        if($query->num_rows){
            $this->db->where('id', $_GET['id']);
            $this->db->limit(1);
            $query2 = $this->db->get($this->tb_sum);
            
            $result = $query->result();
            $result2 = $query2->first_row();
            
            $a['cancel'] = $result2->is_cancel;
           
            
            $code = array("data"=>"Item Code", "style"=>"text-align: left; width:80px", "chalign"=>"text-align: left;");
            $name = array("data"=>"Item Name","style"=>"text-align: left;", "chalign"=>"text-align: left;");
            $qun = array("data"=>"System Stock", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $cost = array("data"=>"Actual Stock", "style"=>"text-align: right; width:100px ", "chalign"=>"text-align: right;");
            $total = array("data"=>"Difference", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
	    $total = array("data"=>"Value", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
            
            $heading = array($code, $name, $qun, $cost, $total);//, $discount
            
            $code = array("data"=>"code", "total"=>false, "format"=>"text");
            $name  = array("data"=>"description", "total"=>false, "format"=>"text");
            $qun  = array("data"=>"system", "total"=>false, "format"=>"number");
            $cost  = array("data"=>"actual", "total"=>false, "format"=>"number");
            $total  = array("data"=>"balance", "total"=>false, "format"=>"number");
            
            $field = array($code, $name, $qun, $cost, $total);//, $discount
            
            $page_rec = 30;
            
            $header  = array("data"=>$this->useclass->r_header("Stock Adjustment - ".$_GET['id']." | Date : ".$result2->date), "style"=>"");
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
                        "header"=>30,
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