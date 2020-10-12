<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_stock_in_hand extends CI_Model {
    
    private $tb_items;
    private $tb_storse;
    private $tb_department;
    private $sd;
    private $w = 297;
    private $h = 210;
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('useclass');
        
        $this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
        $this->tb_items = $this->tables->tb['m_items'];
        $this->tb_storse = $this->tables->tb['m_stores'];
	$this->tb_department = $this->tables->tb['m_department'];
    }
    
    public function base_details(){
        $this->load->model('m_stores');
	$this->load->model('m_department');
	$this->load->model('m_main_cat');
	$this->load->model('m_sub_cat');
        
        $a['report'] = $this->report();
        $a['stores'] = $this->m_stores->select("des");
	$a['department'] = $this->m_department->select();
	$a['main_cat'] = $this->m_main_cat->select("des");
	$a['sub_cat'] = $this->m_sub_cat->select("des");
        $a['title'] = "Stock";
        
        return $a;
    }
    
    public function report(){
        if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}
        if(! isset($this->sd['to'])){ $this->sd['to'] = date("Y-m-d");}
        if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
        if(isset($this->sd['paper'])){
            if($this->sd['paper'] = "l"){
                $this->h = 216;
                $this->w = 279;
            }
        }
        
        if(! isset($this->sd['stores'])){ $this->sd['stores'] = 0; }
        if(! isset($this->sd['type'])){ $this->sd['type'] = "sum"; }
        
	$sto = $this->db->get($this->tb_storse)->result();
	//echo $this->sd['wd'];
	
	
	if($this->sd['wd']!=0){
	  // echo 'vvvvvv';
        $sql = "SELECT
		    code,
		    description,
		    department,
		    main_cat,
		    IFNULL(aa.qty, 0) AS qty,
		    stores
		  FROM m_items
		    
		    inner JOIN (SELECT
				       (SUM(in_quantity) - SUM(out_quantity)) AS qty,
				       item_code,
				       stores
				     FROM t_item_movement
				     WHERE `date` <= '".$this->sd['from']."'
				     GROUP BY item_code, stores) AS aa
		      ON (aa.item_code = m_items.code)
		      
		      WHERE m_items.main_cat='".$this->sd['to']."' OR m_items.department='".$this->sd['wd']."' OR m_items.sub_cat='".$this->sd['type']."' 
		      ORDER BY item_code, stores";
        
        $query = $this->db->query($sql);
        }
	else{
	    // echo 'dfsd';
	    $sql = "SELECT
		    department,
		    main_cat,
		    code,
		    description,
		    IFNULL(aa.qty, 0) AS qty,
		    stores
		  FROM m_items
		    
		    inner JOIN (SELECT
				       (SUM(in_quantity) - SUM(out_quantity)) AS qty,
				       item_code,
				       stores
				     FROM t_item_movement
				     WHERE `date` <= '".$this->sd['from']."'
				     GROUP BY item_code, stores) AS aa
		      ON (aa.item_code = m_items.code)
		      
		      ORDER BY item_code, stores";
	    
	    $query = $this->db->query($sql);
	}
	
	
	
        if($query->num_rows){
            $result = $query->result();
            
	    $code = ""; $res = array(); $total = 0;
	    foreach($result as $r){
		if($code == "" || $code != $r->code){
		    if($code != ""){
			$std->total = $total; $total = 0;
			$res[] = $std;
		    }
		    $std = new stdClass;
		    $std->code = $r->code;
		    $std->description = $r->description;
		    
		    foreach($sto as $s){
			if($s->code == $r->stores){
			    $std->{$s->code} = $r->qty;
			    $total += $r->qty;
			}else{
			    if(! isset($std->{$s->code})){
				$std->{$s->code} = 0;
			    }
			}
		    }
		    
		    $code = $r->code;
		}else{
		    foreach($sto as $s){
			if($s->code == $r->stores){
			    $std->{$s->code} = $r->qty;
			    $total += $r->qty;
			}else{
			    if(! isset($std->{$s->code})){
				$std->{$s->code} = 0;
			    }
			}
		    }
		}
	    }
	    $std->total = $total;
	    $res[] = $std;
            
	    $heading = $field = array();
	    
            $heading[] = array("data"=>"Code", "style"=>"text-align: left; width : 100px;", "chalign"=>"text-align: left;");
            $heading[] = array("data"=>"Item Name", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
	    $heading[] = array("data"=>"Department", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
	    $heading[] = array("data"=>"Main Cat", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
	    
            $field[] = array("data"=>"code", "total"=>false, "format"=>"text");
            $field[]  = array("data"=>"description", "total"=>false, "format"=>"text");
	    $field[]  = array("data"=>"code", "total"=>false, "format"=>"text");
	    $field[]  = array("data"=>"code", "total"=>false, "format"=>"text");
	    
	    foreach($sto as $s){
		$heading[] = array("data"=>$s->description, "style"=>"text-align: right; width : 80px;", "chalign"=>"text-align: right;");
		$field[]  = array("data"=>$s->code, "total"=>false, "format"=>"amount");
	    }
	    
            $heading[] = array("data"=>"Total", "style"=>"text-align: right;", "chalign"=>"text-align: right; font-weight : bold;");
            
            $field[]  = array("data"=>"total", "total"=>false, "format"=>"amount");
            
            $page_rec = 20;
            
            $header  = array("data"=>$this->useclass->r_header("Stock In Hand | Date : ".$this->sd['from']), "style"=>"");
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