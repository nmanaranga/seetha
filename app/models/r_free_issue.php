<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_free_issue extends CI_Model {
    
    private $mtb = "";
    private $sd;
    private $w = 210;
    private $h = 297;
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('useclass');
        
        $this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	
    }
    
    public function base_details(){
        // $a['report'] = $this->report();
        // $a['title'] = "Free Issue Details";
        
        // return $a;
    }
    
 //    public function report(){
        
 //        if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}
 //        if(! isset($this->sd['to'])){ $this->sd['to'] = date("Y-m-d");}
 //        if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
 //        if(isset($this->sd['paper'])){
 //            if($this->sd['paper'] = "l"){
 //                $this->h = 279;
 //                $this->w = 216;
 //            }
 //        }
 //        //if(! isset($this->sd['type'])){ $this->sd['type'] = "sum";}
 //        //if($this->sd['type'] == 'sum'){
 //            $sql =" SELECT 
	// 		`m_items`.`description`
	// 		, `t_purchse_det`.`item_code`
	// 		, `t_purchse_det`.`foc`
	// 		, `t_purchse_sum`.`date`
	// 	    FROM
	// 		`t_purchse_det`
	// 		INNER JOIN `m_items` 
	// 		    ON (`t_purchse_det`.`item_code` = `m_items`.`code`)
	// 		INNER JOIN `t_purchse_sum` 
	// 		    ON (`t_purchse_det`.`id` = `t_purchse_sum`.`id`)
			    
	// 		    WHERE `t_purchse_det`.`foc` <> 0.00
	// 		    AND `t_purchse_sum`.`date` BETWEEN '".$this->sd['from']."' AND '".$this->sd['to']."'
	// 		    AND bc = '".$this->sd['bc']."'
	// 		    ORDER BY item_code";
	    
        
 //        $query = $this->db->query($sql);
        
 //        if($query->num_rows){
 //            //if($this->sd['type'] == 'sum'){
 //                $result = $query->result();
                
                
 //                $icode = array("data"=>"Item Code", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:80px");
 //                $iname = array("data"=>"Item Name", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
 //                $date = array("data"=>"Date", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
 //                $qty = array("data"=>"Free Issue Qty", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:150px");
                
 //                $heading = array($icode, $iname, $date, $qty);
                
 //                $icode = array("data"=>"item_code", "total"=>false, "format"=>"text");
 //                $iname  = array("data"=>"description", "total"=>false, "format"=>"text");
 //                $date  = array("data"=>"date", "total"=>false, "format"=>"text");
 //                $qty  = array("data"=>"foc", "total"=>false, "format"=>"text");
                                
 //                $field = array($icode, $iname, $date, $qty);
                
 //                $page_rec = 30;
                
 //                $header  = array("data"=>$this->useclass->r_header("Free Issue Details Report - ".$this->sd['from']." To ".$this->sd['to']), "style"=>"");
 //                $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
 //                $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
                
 //                $data = array(
 //                              "dbtem"=>$this->useclass->report_style(),
 //                              "data"=>$result,
 //                              "field"=>$field,
 //                              "heading"=>$heading,
 //                              "page_rec"=>$page_rec,
 //                              "height"=>$this->h,
 //                              "width"=>$this->w,
 //                              "header"=>30,
 //                              "header_txt"=>$header,
 //                              "footer_txt"=>$footer,
 //                              "page_no"=>$page_no,
 //                              "header_of"=>false
 //                              );
 //                //print_r($data); exit;
 //                $this->load->library('greport', $data);
                
 //                $resu = $this->greport->_print();

 //        }
	// else{
 //            $resu = "<span style='color:red;'>There is no data to load.</span>";
 //        }
        
 //        return $resu;
 //    }
}
?>