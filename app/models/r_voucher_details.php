<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_voucher_details extends CI_Model {
    
    private $tb_items;
    private $tb_main_cat;
    private $tb_sum;
    private $sd;
    private $w = 210;
    private $h = 297;
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('useclass');
        
        $this->sd = $this->session->all_userdata();
	   $this->load->database($this->sd['db'], true);
	   $this->tb_sum = $this->tables->tb['t_supplier_receipt_sum'];
       
    }
    
    public function base_details(){
        // $this->load->model('m_stores');
        
        // $a['report'] = $this->report();
        // $a['title'] = "Voucher Details";
        
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
        
  //       if(! isset($this->sd['stores'])){ $this->sd['stores'] = 0; }
  //       if(! isset($this->sd['type'])){ $this->sd['type'] = "sum"; }
        
  //       if($this->sd['stores'] != "0"){
  //           $where = "WHERE `stores` = '".$this->sd['stores']."'";
  //       }else{
  //           $where = "";
  //       }
        
        
  //       $sql="SELECT
  //               `t_supplier_receipt_det`.`purchase_no`
  //               , `t_supplier_receipt_det`.`paid`
  //               , `t_supplier_receipt_det`.`balance`
  //               , `t_supplier_receipt_det`.`total`
  //               , `t_supplier_receipt_sum`.`date`
		// , `t_supplier_receipt_sum`.`supplier`
		// , `t_supplier_receipt_sum`.`no`
  //               , `m_supplier`.`name`
    
  //           FROM
  //                   `t_supplier_receipt_det`
  //           INNER JOIN `t_supplier_receipt_sum` 
  //           ON (`t_supplier_receipt_det`.`id` = `t_supplier_receipt_sum`.`id`)
  //           INNER JOIN `m_supplier` 
  //           ON (`t_supplier_receipt_sum`.`supplier` = `m_supplier`.`code`)


  //           WHERE is_cancel='0' AND `t_supplier_receipt_sum`.`date` BETWEEN '".$this->sd['from']."'
		//       AND '".$this->sd['to']."'";
  //       //echo $sql; exit;
  //       $query = $this->db->query($sql);
        
  //       if($query->num_rows){
  //           $result = $query->result();
  //           $res = array();
  //           $t=0;
  //           foreach($result as $r){
  //               $t += $r->paid;
                
  //           }
            
  //           $r = new stdClass();
                
  //               $r->no = "";
  //               $r->purchase_no = "";
  //               $r->name = array("data"=>"Total", "style"=>"text-align: right; font-weight: bold; border: none;font-weight: bold;");
  //               $r->paid = array("data"=>number_format($t, 2, '.', ','), "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;border-top : 1px solid #000;");
	    
  //           array_push($result, $r);
	    
	    
	    
  //           $vno = array("data"=>"Voucher No", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //           $pno = array("data"=>"Purchase No", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //           $sup = array("data"=>"Supplier", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //           $paid = array("data"=>"Paid", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width : 80px;");
  //           //$heading[] = array("data"=>"Total", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");

  //           $heading = array($vno, $pno, $sup, $paid);
            
  //           $vno = array("data"=>"no", "total"=>false, "format"=>"text");
  //           $pno = array("data"=>"purchase_no", "total"=>false, "format"=>"text");
  //           $sup = array("data"=>"name", "total"=>false, "format"=>"text");
  //           $paid  = array("data"=>"paid", "total"=>false, "format"=>"text", "style"=>"text-align: right; ");
  //           //$field[]  = array("data"=>"tot", "total"=>false, "format"=>"text", "limit"=>30);
  //           $field = array($vno, $pno, $sup, $paid);
            
  //           $page_rec = 30;
            
  //           $header  = array("data"=>$this->useclass->r_header("VOUCHER DETAILS","Date From ".$this->sd['from']." To ".$this->sd['to'].""), "style"=>"");
  //           $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
  //           $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
  //           $data = array(
  //                             "dbtem"=>$this->useclass->report_style(),
  //                             "data"=>$result,
  //                             "field"=>$field,
  //                             "heading"=>$heading,
  //                             "page_rec"=>$page_rec,
  //                             "height"=>$this->h,
  //                             "width"=>$this->w,
  //                             "header"=>37,
  //                             "header_txt"=>$header,
  //                             "footer_txt"=>$footer,
  //                             "page_no"=>$page_no,
  //                             "header_of"=>true
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