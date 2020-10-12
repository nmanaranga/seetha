<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_purchase_itemwise extends CI_Model {
    
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
	
        $this->tb_cus = $this->tables->tb['m_customer'];
        $this->tb_root = $this->tables->tb['m_root'];
        $this->tb_area = $this->tables->tb['m_area'];
    }
    
    public function base_details(){
        // $this->load->model('m_root');
        // $this->load->model('m_area');
        // $this->load->model('m_sales_ref');
        
        // $a['report'] = $this->report();
        // $a['title'] = "Items";
        // $a['root'] = $this->m_root->select("des");
        // $a['area'] = $this->m_area->select("name");
        // $a['ref'] = $this->m_sales_ref->select();
        // return $a;
    }
    
  //   public function report(){
  //       if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}
  //       if(! isset($this->sd['to'])){ $this->sd['to'] = date("Y-m-d");}
  //       if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
  //       if(isset($this->sd['paper'])){
  //           if($this->sd['paper'] = "l"){
  //               $this->h = 279;
  //               $this->w = 216;
  //           }
  //       }
  //       if(! isset($this->sd['root'])){ $this->sd['root'] = 0; }
  //       if(! isset($this->sd['area'])){ $this->sd['area'] = 0; }
  //       $sql = "SELECT
  //               `t_purchse_sum`.`no`
  //               , `t_purchse_sum`.`date`
  //               , `t_purchse_det`.`item_code`
  //               , `m_items`.`description`
  //               , SUM(`t_purchse_det`.`quantity`) AS qty
  //           FROM
  //               `t_purchse_det`
  //               INNER JOIN `t_purchse_sum` 
  //                   ON (`t_purchse_det`.`id` = `t_purchse_sum`.`id`)
  //               INNER JOIN `m_items` 
  //                   ON (`t_purchse_det`.`item_code` = `m_items`.`code`)
  //                   WHERE `date` BETWEEN '".$this->sd['from']."' AND '".$this->sd['to']."'
  //                   GROUP BY item_code,`no`";
                
       
  //       $query = $this->db->query($sql);
        
  //       if($query->num_rows){
  //           $result = $query->result();
            
            
  //               $st = 0;
		// $t= 0;
		// $res = array();
		// $item = '';
		
  //               foreach($result as $r){
                    
                
		// if ($item != $r->item_code) {

  //                   if ($item != '') {
  //                       $rec = new stdClass;
  //                       $rec->item_code = "";
  //                       $rec->description = "";
  //                       $rec->no = "";
  //                       $rec->date = array("data" => "Sub Total", "style" => "font-weight: bold; text-align: right;");
  //                       $rec->qty = array("data"=>number_format($t, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
			
		// 	$t = 0;
  //                       $res[] = $rec;
  //                   }
  //                   $rec = new stdClass;
  //                   $rec->item_code = $r->item_code;
  //                   $rec->description = $r->description;
  //                   $rec->no = '';
  //                   $rec->date = '';
  //                   $rec->qty = array("data" => 0, "style" => "color:#F0FFFF; text-align: right;");
  //                   $res[] = $rec;
  //               }

  //               $item = $r->item_code;

  //               $t += $r->qty;
  //               $rec = new stdClass;
  //               $rec->item_code = "";
  //               $rec->description = "";
		// $rec->no = $r->no;
		// $rec->date = $r->date;
  //               $rec->qty = $r->qty;
		
  //               $res[] = $rec;
  //               $st += $r->qty;
		
		// }
		
		// $rec = new stdClass;
		// $rec->item_code = "";
		// $rec->description = "";
		// $rec->no = "";
		// $rec->date = array("data" => "Sub Total", "style" => "font-weight: bold; text-align: right;");
		// $rec->qty = array("data"=>number_format($t, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
		// $res[] = $rec;



		// $rec = new stdClass;
		// $rec->item_code = "";
		// $rec->description = "";
		// $rec->no = "";
		// $rec->date = array("data" => "Total", "style" => "font-weight: bold; text-align: right;");
		// $rec->qty = array("data"=>number_format($st, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
		// $res[] = $rec;
	    
	    
	    
	    
		// $code = array("data"=>"Item Code", "style"=>"text-align: left; ", "chalign"=>"text-align: left;width:70px");
  //               $name = array("data"=>"Name", "style"=>"text-align: left; ", "chalign"=>"text-align: left");
  //               $no = array("data"=>"No", "style"=>"text-align: left; ", "chalign"=>"text-align: left");
  //               $date = array("data"=>"Date", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
		// $qty = array("data"=>"Qty", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:30px");
                
  //               $heading = array($code,$name,$no,$date,$qty);
                
  //               $code = array("data"=>"item_code", "total"=>false, "format"=>"text");
		// $name  = array("data"=>"description", "total"=>false, "format"=>"text");
		// $no = array("data"=>"no", "total"=>false, "format"=>"text");
		// $date  = array("data"=>"date", "total"=>false, "format"=>"text");
		// $qty  = array("data"=>"qty", "total"=>false, "format"=>"text");
		
                
  //               $field = array($code,$name,$no,$date,$qty);

  //           //$name = array("data"=>"Name", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //           //$code = array("data"=>"Code", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:60px");
  //           //$address = array("data"=>"Address","style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //           //$total = array("data"=>"Balance", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:80px");
            
  //           //$heading = array($code, $name, $address, $total);//$inv_no, , $cus, $sale
            
            
  //           //$name  = array("data"=>"name", "total"=>false, "format"=>"text");
  //           //$code  = array("data"=>"code", "total"=>false, "format"=>"text");
  //           //$address  = array("data"=>"address", "total"=>false, "format"=>"text");
  //           //$total  = array("data"=>"total", "total"=>false, "format"=>"amount");
            
  //           //$field = array($code, $name, $address, $total);//$inv_no, , $cus, $sale
            
  //           $page_rec = 30;
                        
  //           $header  = array("data"=>$this->useclass->r_header("GRN Itemwise Summery report  - ".$this->sd['from']." To ".$this->sd['to']), "style"=>"");
  //           $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
  //           $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
  //           $data = array(
  //                             "dbtem"=>$this->useclass->report_style(),
  //                             "data"=>$res,
  //                             "field"=>$field,
  //                             "heading"=>$heading,
  //                             "page_rec"=>$page_rec,
  //                             "height"=>$this->h,
  //                             "width"=>$this->w,
  //                             "header"=>30,
  //                             "header_txt"=>$header,
  //                             "footer_txt"=>$footer,
  //                             "page_no"=>$page_no,
  //                             "header_of"=>false
  //                             );
  //           //print_r($data); exit;
  //           $this->load->library('greport', $data);
            
  //           $resu = $this->greport->_print();
  //       }else{
  //           $resu = "<span style='color:red;'>There is no data to load.</span>";
  //       }
        
  //       return $resu;
  //   }
}
?>