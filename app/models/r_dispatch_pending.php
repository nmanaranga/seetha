<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_dispatch_pending extends CI_Model {
    
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
        
  //       $sql = "SELECT `item_code`,`description`,in_no,SUM(`in_qty`) AS in_qty,SUM(`out_qty`) AS out_qty,
  //               SUM(`in_qty`)-SUM(`out_qty`) AS bal,`date`
  //               FROM `t_dispatch_trance` INNER JOIN `m_items` ON (`m_items`.`code`=`t_dispatch_trance`.`item_code`)
  //               WHERE `date` BETWEEN '".$this->sd['from']."' AND '".$this->sd['to']."'
  //               GROUP BY `in_no`,`item_code`
  //               HAVING bal>0";
                
       
  //       $query = $this->db->query($sql);
        
  //       if($query->num_rows){
  //           $result = $query->result();
            
            
  //               $t1 = 0;
  //               $t2 = 0;
  //               $t3 = 0;
  //               $st1 = 0;
  //               $st2 = 0;
  //               $st3 = 0;
		// $t= 0;
		// $res = array();
		// $no = '';
		// $date = '';
  //               $trance='';
		
  //               foreach($result as $r){
                    
                
		// if ($no != $r->in_no) {

  //                   if ($no != '') {
  //                       $rec = new stdClass;
  //                       $rec->in_no = "";
  //                       $rec->date = "";
  //                       $rec->item_code = "";
  //                       $rec->description = array("data" => "Total", "style" => "font-weight: bold; text-align: right;");
  //                       $rec->in_qty = array("data"=>number_format($st1, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
  //                       $rec->out_qty = array("data"=>number_format($st2, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
  //                       $rec->bal = array("data"=>number_format($st3, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
  //                       $res[] = $rec;
			
		// 	$st1 = 0;
		// 	$st2 = 0;
		// 	$st3 = 0;
  //                       //$res[] = $rec;
  //                   }
  //                   $rec = new stdClass;
  //                   $rec->in_no = $r->in_no;
  //                   $rec->date = $r->date;
  //                   $rec->item_code = '';
  //                   $rec->description = '';
  //                   $rec->in_qty = array("data" => 0, "style" => "color:#F0FFFF; text-align: right;");
  //                   $rec->out_qty = array("data" => 0, "style" => "color:#F0FFFF; text-align: right;");
  //                   $rec->bal = array("data" => 0, "style" => "color:#F0FFFF; text-align: right;");
  //                   $res[] = $rec;
  //               }
                
  //               $st1 += $r->in_qty;
  //               $st2 += $r->out_qty;
  //               $st2 += $r->bal;
  //               $no = $r->in_no;

                
  //               $rec = new stdClass;
  //               $rec->in_no = "";
  //               $rec->date = "";
  //               $rec->item_code = $r->item_code;
  //               $rec->description = $r->description;
  //               $rec->in_qty = $r->in_qty;
		// $rec->out_qty = $r->out_qty;
  //               $rec->bal = $r->bal;
		
  //               $res[] = $rec;
  //               $t1 += $r->in_qty;
  //               $t2 += $r->out_qty;
  //               $t3 += $r->bal;
		
		// }
		
		// $rec = new stdClass;
		// $rec->in_no = "";
		// $rec->date = "";
		// $rec->item_code = "";
		// $rec->description = array("data" => "Total", "style" => "font-weight: bold; text-align: right;");
		// $rec->in_qty = array("data"=>number_format($st1, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
		// $rec->out_qty = array("data"=>number_format($st2, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
		// $rec->bal = array("data"=>number_format($st3, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
		// $res[] = $rec;
                
                
                
                
		// $rec = new stdClass;
		// $rec->in_no = "";
		// $rec->date = "";
		// $rec->item_code = "";
		// $rec->description = array("data" => "Grand Total", "style" => "font-weight: bold; text-align: right;");
		// $rec->in_qty = array("data"=>number_format($t1, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
		// $rec->out_qty = array("data"=>number_format($t2, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
		// $rec->bal = array("data"=>number_format($t3, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
		// $res[] = $rec;



		// //$rec = new stdClass;
		// //$rec->item_code = "";
		// //$rec->description = "";
		// //$rec->date = array("data" => "Total", "style" => "font-weight: bold; text-align: right;");
		// //$rec->qty = array("data"=>number_format($st, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
		// //$res[] = $rec;
	    
	    
	    
	    
		// $no = array("data"=>"Dis. No", "style"=>"text-align: right; ", "chalign"=>"text-align: right;width:60px");
		// $date = array("data"=>"Date", "style"=>"text-align: right; ", "chalign"=>"text-align: right;width:50px");
		// $code = array("data"=>"Item Code", "style"=>"text-align: right; ", "chalign"=>"text-align: right;width:50px");
  //               $name = array("data"=>"Name", "style"=>"text-align: left; ", "chalign"=>"text-align: left");
  //               $in_qty = array("data"=>"Qty Dis", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:80px");
		// $out_qty = array("data"=>"Qty Return", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:80px");
		// $bal = array("data"=>"Balance", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:80px");
                
  //               $heading = array($no,$date,$code,$name,$in_qty,$out_qty,$bal);
                
  //               $no = array("data"=>"in_no", "total"=>false, "format"=>"text");
  //               $date = array("data"=>"date", "total"=>false, "format"=>"text");
  //               $code = array("data"=>"item_code", "total"=>false, "format"=>"text");
		// $name  = array("data"=>"description", "total"=>false, "format"=>"text");
		// $in_qty  = array("data"=>"in_qty", "total"=>false, "format"=>"text");
		// $out_qty  = array("data"=>"out_qty", "total"=>false, "format"=>"text");
		// $bal  = array("data"=>"bal", "total"=>false, "format"=>"text");
		
                
  //               $field = array($no,$date,$code,$name,$in_qty,$out_qty,$bal);

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
                        
  //           $header  = array("data"=>$this->useclass->r_header("Pending Dispatch Report  - ".$this->sd['from']." To ".$this->sd['to']), "style"=>"");
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