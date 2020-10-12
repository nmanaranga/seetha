<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_supplier_list extends CI_Model {
    
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
	
        $this->tb_cus = $this->tables->tb['m_supplier'];
    }
    
    public function base_details(){
        
        // $a['report'] = $this->report();
        // $a['title'] = "Supplier List";
        
        // return $a;
    }
    
 //    public function report()
	
	// {
        
 //        if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}
 //        if(! isset($this->sd['to'])){ $this->sd['to'] = date("Y-m-d");}
 //        if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
 //        if(isset($this->sd['paper']))
	// 	{
 //            if($this->sd['paper'] = "l")
	// 		{
 //                $this->h = 279;
 //                $this->w = 216;
 //            }
 //        }
 //        if(! isset($this->sd['root'])){ $this->sd['root'] = 0; }
 //        $this->db->select(array(
 //                            $this->tb_cus.".code",
 //                            "CONCAT(name, ' (', full_name, ')') AS name",
 //                            "CONCAT(address01, ', ', address02, ', ', ', ', address03) AS address",
 //                            "CONCAT(phone01, ', ', phone02) AS tp",
 //                            "phone03 AS fax"
 //                        ));
 //        $query = $this->db->get($this->tb_cus);
        
 //        if($query->num_rows){
 //            $result = $query->result();
            
 //            $heading = array();
 //            $heading[] = array("data"=>"Name", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
 //            $heading[] = array("data"=>"Code", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:60px");
 //            $heading[] = array("data"=>"Address","style"=>"text-align: left; ", "chalign"=>"text-align: left;");
 //            $heading[] = array("data"=>"Phone No", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
 //            $heading[] = array("data"=>"Fax", "style"=>"text-align: left;", "chalign"=>"text-align: left; width:80px");
            
 //            $field = array();
 //            $field[]  = array("data"=>"name", "total"=>false, "format"=>"text");
 //            $field[]  = array("data"=>"code", "total"=>false, "format"=>"text");
 //            $field[]  = array("data"=>"address", "total"=>false, "format"=>"text");
 //            $field[]  = array("data"=>"tp", "total"=>false, "format"=>"text");
 //            $field[]  = array("data"=>"fax", "total"=>false, "format"=>"text");
            
            
 //            $page_rec = 30;
            
 //            $header  = array("data"=>$this->useclass->r_header("Supplier List"), "style"=>"");
 //            $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
 //            $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
 //            $data = array(
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
 //            //print_r($data); exit;
 //            $this->load->library('greport', $data);
            
 //            $resu = $this->greport->_print();
 //        }else{
 //            $resu = "<span style='color:red;'>There is no data to load.</span>";
 //        }
        
 //        return $resu;
 //    }
}
?>