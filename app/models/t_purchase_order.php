<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_purchase_order extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_items;
    private $tb_branch;
    private $tb_supplier;
    private $h = 297;
    private $w = 210;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
	$this->tb_sum = $this->tables->tb['t_purchse_order_sum'];
	$this->tb_det = $this->tables->tb['t_purchse_order_det'];
	$this->tb_trance = $this->tables->tb['t_purchse_order_trance'];
	$this->tb_items = $this->tables->tb['m_items'];
	$this->tb_branch = $this->tables->tb['s_branches'];
	$this->tb_supplier = $this->tables->tb['m_supplier'];
	$this->tb_supplier_trans = $this->tables->tb['t_supplier_transaction'];
    }
    
    public function base_details(){
	$this->load->model('m_supplier');
	
	$a['sup'] = $this->m_supplier->select();
	$a['max_no'] = $this->max_no();
	
	return $a;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['bc']);
	$this->db->select_max("no");
	
	return $this->db->get($this->tb_sum)->first_row()->no+1;
    }
    
    public function check_is_purchase()
    {
                $sql="SELECT SUM(`in_quantity`) AS `in_quantity`,SUM(`out_quantity`) AS out_quantity,`out_no`
                        FROM `t_purchse_order_trance`
                        WHERE  `in_no`=".$_POST['id']."
                        GROUP BY `in_no`";
        
                // echo $sql;exit;
                
                
        $qry=$this->db->query($sql);
        $r=$qry->first_row();
        if(!empty($r->in_quantity))
        {
            if($r->out_quantity>0)
            {
                echo $r->out_no;
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

    public function save(){
	$this->db->trans_start();
	
        $no='';
        
        
	$a = array(
	    "date"=>$_POST['date'],
	    "ref_no"=>$_POST['ref_no'],
	    "memo"=>$_POST['memo'],
	    "supplier"=>$_POST['supplier'],
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
	    $lid = $_POST['hid'];
	    $this->set_delete();
	}
	
       // $no=$a["no"];

	$sql = "INSERT INTO `".$this->tb_det."` (`id`, `item_code`, `cost`, `quantity`) VALUES ";
	$sql2 = "INSERT INTO `".$this->tb_trance."` (`trance_id`, `trance_type`, `item_code`, `in_quantity`, `date`,in_no,out_no) VALUES ";
	
        $a = $a2 =$a3= array();
	
        for($i=0; $i<25; $i++){
            if($_POST['h_'.$i] != "" && $_POST['h_'.$i] != "0"){
                $a[] = "(".$lid.", '".$_POST['h_'.$i]."', '".$_POST['2_'.$i]."', '".$_POST['1_'.$i]."')";
                $a2[] = "(".$lid.", 'PO', '".$_POST['h_'.$i]."', '".$_POST['1_'.$i]."', '".$_POST['date']."','".$_POST['id']."','".$_POST['id']."')";
            }
        }
        
	if(count($a)){
	    $sql .= join(", ", $a);
	    $this->db->query($sql);
	}
	
	if(count($a2)){
	    $sql2 .= join(", ", $a2);
	    $this->db->query($sql2);
	}
	
	$this->db->trans_complete();
	
	redirect(base_url()."?action=t_purchase_order&print=".$lid);
    }
    
    public function load(){
	$this->db->select(array('id', 'date', 'supplier', 'is_cancel', 'ref_no', 'memo', 'posting', 'name', 'full_name'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
        $this->db->limit(1);
	$this->db->join($this->tb_supplier, $this->tb_supplier.".code = ".$this->tb_sum.".supplier", "INNER");
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){
	    $this->db->select(array('quantity', $this->tb_det.'.cost', $this->tb_det.'.item_code', $this->tb_items.'.description', $this->tb_items.'.is_measure'));
	    $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
	}
        
        echo json_encode($a);
    }
    public function load_return(){
	$this->db->select(array('id', 'date', 'supplier', 'is_cancel', 'ref_no', 'memo', 'posting', 'name', 'full_name'));
        $this->db->where("no", $_POST['id']);
        $this->db->where("bc", $this->sd['bc']);
        $this->db->limit(1);
	$this->db->join($this->tb_supplier, $this->tb_supplier.".code = ".$this->tb_sum.".supplier", "INNER");
        $a['sum'] = $this->db->get($this->tb_sum)->first_row();
        
	if(isset($a["sum"]->id)){
	    $this->db->select(array('quantity', $this->tb_det.'.cost', $this->tb_det.'.item_code', $this->tb_items.'.description', $this->tb_items.'.is_measure'));
	    $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".item_code", "INNER");
	    $this->db->where($this->tb_det.".id", $a["sum"]->id);
	    $a['det'] = $this->db->get($this->tb_det)->result();
            
                 $sql="SELECT
                      SUM(`t_purchse_order_trance`.`in_quantity`)-SUM(`t_purchse_order_trance`.`out_quantity`) AS bal
                      FROM
                          `t_purchse_order_trance`
                          INNER JOIN `m_items` 
                              ON (`t_purchse_order_trance`.`item_code` = `m_items`.`code`)   
                          
                              WHERE `in_no`='".$_POST['id']."'
                              GROUP BY `in_no`, `m_items`.`code`";

          $query=$this->db->query($sql);
          $r=$query->result();
          $a['tran']=$r;
            
            
	}
        
        echo json_encode($a);
    }
        
    public function delete(){
	$a = true;
        
        $this->db->where("trance_id", $_POST['id']);
        $this->db->where("trance_type", "PO");
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
	
	$this->db->where('trance_id', $_POST['hid']);
	$this->db->where('trance_type', 'PO');
	$this->db->delete($this->tb_trance);
    }
    
    public function print_view(){
        $this->load->library('useclass');
	$a['title'] = 'Purchase Order';
        
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
		</table><br>";
            
            $page_rec = 30;
            
            $header  = array("data"=>$this->useclass->r_header("Purchase Order : ".$result2->no." | Branch : ".$result2->name." (".$result2->bc.")<br /> Date : ".$result2->date), "style"=>"");
            //$footer  = array("data"=>"<div style='text-align: right; font-size: 12px;'>.....................<br />Signature</div><br /><br /><br /><br /><br /><hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
            $footer  = array("data"=>"<div style='text-align: right; font-size: 12px;'>".$sig."</div><hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
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
    
    public function select(){
        //$query = $this->db->query($sql);
        
        $s = "<select name='po_no' id='po_no'>";
        $s .= "<option value='0'>---</option>";
        //foreach($query->result() as $r){
        //    $s .= "<option value='".$r->no."'>".$r->no."</option>";
        //}
        $s .= "</select>";
        
        return $s;
    }
}