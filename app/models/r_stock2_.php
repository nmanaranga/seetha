<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_stock2 extends CI_Model {
    
    private $tb_items;
    private $tb_main_cat;
    private $tb_sub_cat;
    private $sd;
    private $w = 210;
    private $h = 297;
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('useclass');
        
        $this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
        $this->tb_items = $this->tables->tb['m_items'];
        $this->tb_main_cat = $this->tables->tb['m_main_cat'];
        $this->tb_sub_cat = $this->tables->tb['m_sub_cat'];
    }
    
    public function base_details(){
        $this->load->model('m_stores');
        
        $a['report'] = $this->report();
        $a['stores'] = $this->m_stores->select("des");
        $a['title'] = "Items";
        
        return $a;
    }
    
    public function report(){
        if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}
        if(! isset($this->sd['to'])){ $this->sd['to'] = date("Y-m-d");}
        if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
        if(isset($this->sd['paper'])){
            if($this->sd['paper'] = "l"){
                $this->h = 279;
                $this->w = 216;
            }
        }
        
        if(! isset($this->sd['stores'])){ $this->sd['stores'] = 0; }
        if(! isset($this->sd['type'])){ $this->sd['type'] = "sum"; }
        
        if($this->sd['stores'] != "0"){
            $where = "WHERE `stores` = '".$this->sd['stores']."'";
        }else{
            $where = "";
        }
        
        $sql = "SELECT
                    `m_main_category`.`description` AS main_cat,
                    `m_sub_category`.`description`  AS sub_cat,
                    `m_items`.`code`,
                    `m_items`.`description` AS item_name,
                    `purchase_price` AS `price`,
                    IFNULL(`qun`.`qun`, 0) AS `qun`,
                    IFNULL((`qun`.`qun` * `purchase_price`), 0) AS `value`
                  FROM (`m_items`)
                    INNER JOIN `m_main_category`
                      ON `m_main_category`.`code` = `m_items`.`main_cat`
                    LEFT OUTER JOIN `m_sub_category`
                      ON `m_sub_category`.`code` = `m_items`.`sub_cat`
                    LEFT OUTER JOIN (SELECT
                                       (SUM(in_quantity) - SUM(out_quantity)) AS qun,
                                       item_code
                                     FROM t_item_movement ".$where." GROUP BY item_code) AS qun
                      ON (qun.item_code = m_items.code)";
         
        $query = $this->db->query($sql);
        
        if($query->num_rows){
            $result = $query->result();
            
            
            if($this->sd['type'] == "det"){
               
                $st = 0;
		$t= 0;
		$res = array();
		$cat = '';
		$cat2 = '';
		
		foreach($result as $r){
                    
                
		if ($cat != $r->main_cat||$cat2 != $r->sub_cat) {

                    if ($cat != ''|| $cat2 !='') {
                        $rec = new stdClass;
                        $rec->main_cat = "";
                        $rec->sub_cat = "";
                        $rec->code = "";
                        $rec->item_name = array("data" => "Sub Total", "style" => "font-weight: bold; text-align: right;");
                        $rec->qun = array("data"=>number_format($t, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
                        
			
			$t = 0;
                        $res[] = $rec;
                    }
                    $rec = new stdClass;
                    $rec->main_cat = $r->main_cat;
                    $rec->sub_cat = $r->sub_cat;
                    $rec->code = "";
                    $rec->item_name = "";
		    $rec->qun = array("data" => 0, "style" => "color:#F0FFFF; text-align: right;");
                    
                    $res[] = $rec;
                }

                $cat = $r->main_cat;
		$cat2 = $r->sub_cat;
                $t += $r->qun;
                $rec = new stdClass;
                $rec->main_cat = "";
                $rec->sub_cat = "";
                $rec->code = $r->code;
                $rec->item_name = $r->item_name;
                $rec->qun = $r->qun;
                
		
                $res[] = $rec;
                $st += $r->qun;
		
		}
		
		$rec = new stdClass;
		$rec->main_cat = "";
		$rec->sub_cat = "";
		$rec->code = "";
		$rec->item_name = array("data" => "Sub Total", "style" => "font-weight: bold; text-align: right;");
		$rec->qun = array("data"=>number_format($t, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
		
		$res[] = $rec;



		$rec = new stdClass;
		$rec->main_cat = "";
		$rec->sub_cat = "";
		$rec->code = "";
		$rec->item_name = array("data" => "Total", "style" => "font-weight: bold; text-align: right;");
		$rec->qun = array("data"=>number_format($st, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
		
		$res[] = $rec;
	    
	    
	    
	    
		$name = array("data"=>"Main Category", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
                $sub = array("data"=>"Sub Category", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:200px");
                $code = array("data"=>"Code","style"=>"text-align: center; ", "chalign"=>"text-align: left; width:100px");
                $item = array("data"=>"Item", "style"=>"text-align: center; ", "chalign"=>"text-align: left; width:300px");
                $qun = array("data"=>"Qty", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:30px");
                
                
                $heading = array($name,$sub, $code, $item, $qun);
                
                $inv_no = array("data"=>"main_cat", "total"=>false, "format"=>"text");
		$job_no  = array("data"=>"sub_cat", "total"=>false, "format"=>"text");
		$vehi  = array("data"=>"code", "total"=>false, "format"=>"text");
		$cus  = array("data"=>"item_name", "total"=>false, "format"=>"text");
		$sale  = array("data"=>"qun", "total"=>false, "format"=>"text");
		
                
                $field = array($inv_no, $job_no, $vehi, $cus, $sale);
	    
	    
	    
	    }
	    
	    
            $page_rec = 17;
            
            //if($this->sd['type'] == "det"){
            //    $name = "Price List";
            //}else{
            //    $name = "Item List";
            //}
            $name="Item List";
	    
            $header  = array("data"=>$this->useclass->r_header($name), "style"=>"");
            $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
            $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
            $data = array(
                              "dbtem"=>$this->useclass->report_style(),
                              "data"=>$res,
                              "field"=>$field,
                              "heading"=>$heading,
                              "page_rec"=>$page_rec,
                              "height"=>$this->h,
                              "width"=>$this->w,
                              "header"=>30,
                              "header_txt"=>$header,
                              "footer_txt"=>$footer,
                              "page_no"=>$page_no,
                              "header_of"=>true
                              );
            //print_r($data); exit;
            $this->load->library('greport', $data);
            
            $resu = $this->greport->_print();
        }else{
            $resu = "<span style='color:red;'>There is no data to load.</span>";
        }
        
        return $resu;
    }
}
?>