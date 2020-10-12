<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_subitem_list  extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $tb_det;
    private $tb_trance;
    private $tb_items;
    private $tb_cost_log;
    private $tb_branch;
    private $h = 297;
    private $w = 210;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	$this->tb_det = $this->tables->tb['m_sub_item_list'];
	$this->tb_items = $this->tables->tb['m_items'];
	
    }
    
    public function base_details(){
	$this->load->model('m_items');
	
	//$a['max_no'] = $this->max_no();
	$a['stores'] = $this->m_items->select();
	$a['sd'] = $this->sd;
	
	return $a;
    }
    
    private function max_no(){
	$this->db->where("bc", $this->sd['bc']);
	$this->db->select_max("no");
	
	return $this->db->get($this->tb_sum)->first_row()->no+1;
    }
    
    public function save()
    {
	//print_r($_POST); exit;
	$this->db->trans_start();
        
       $this->db->where('main_item', $_POST['items']);
       $this->db->delete($this->tb_det);
        
        $a_det = array();
	
        for($i=0; $i<25; $i++){
            if($_POST['h_'.$i] != "" && $_POST['h_'.$i] != "0"){
                $a_det[] = array(
		    "main_item"=>$_POST['items'],
		    "sub_item_code"=>$_POST['h_'.$i],
		    "qty"=>$_POST['1_'.$i],
		    "qty_carton"=>$_POST['2_'.$i],
		    "foc"=>$this->input->post('is_active'.$i)
                    
		);
				
            }
        }
        
	if(count($a_det)){ $this->db->insert_batch($this->tb_det, $a_det); }
        redirect(base_url()."?action=m_subitem_list");
	$this->db->trans_complete();

    }
    
    public function cost_max_no(){
        $this->db->select_max('auto_id');
        $query = $this->db->get($this->tb_cost_log);
        $max_no = $query->first_row()->auto_id+1;
        
        $this->db->query("ALTER TABLE `".$this->tb_cost_log."` AUTO_INCREMENT=".$max_no);
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
            "module"=>'OPRN',
            "item_code"=>$item_id,
            "cost"=>$cost2
        );
        
        $this->db->insert($this->tb_cost_log, $a);
        
        return $cost2;
    }
    
    public function load(){

	    $this->db->select(array('main_item', $this->tb_det.'.sub_item_code', $this->tb_det.'.qty', $this->tb_items.'.description', $this->tb_det.'.qty_carton',$this->tb_det.'.foc'));
	    $this->db->join($this->tb_items, $this->tb_items.".code = ".$this->tb_det.".sub_item_code", "INNER");
	    $this->db->where($this->tb_det.".main_item", $_POST['id']);
	    $a['det'] = $this->db->get($this->tb_det)->result();
	
	
        echo json_encode($a);
    }
    
    
    public function delete(){
	$a = true;
        
        $this->db->where("main_item", $_POST['id']);
        if(! $this->db->delete($this->tb_det)){
            $a = false;
        }
        echo $a;
    }
    
    public function print_view(){
        $this->load->library('useclass');
	$a['title'] = 'Opening Stock';
        
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
            
            $header  = array("data"=>$this->useclass->r_header("Opening Stock : ".$result2->no." | Branch : ".$result2->name." (".$result2->bc.")<br /> Date : ".$result2->date), "style"=>"");
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
            //print_r($data); exit;
            $this->load->library('greport', $data);
                
            $a['view'] = $this->greport->_print();
        }else{
            $a['view'] = "No Record";
        }
        
        return $a;
    }
}