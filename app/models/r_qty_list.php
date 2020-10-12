<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_qty_list extends CI_Model {
    
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
        
        // $a['report'] = $this->report();
        // $a['title'] = "Items";
        // $a['root'] = $this->m_root->select("des");
        // $a['area'] = $this->m_area->select("name");
        
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
  //       if(! isset($this->sd['sale'])){ $this->sd['sale'] = 0; }
            
       
        
  //         if($this->sd['sale']=='cash')
  //         {
  //       $sql = "SELECT
  //               `t_cash_sales_sum`.`no`
  //               , `t_cash_sales_sum`.`date`
  //               , `t_cash_sales_sum`.`cash`
  //               , t_cash_sales_det.`quantity`
  //           FROM
  //               `t_cash_sales_det`
  //               INNER JOIN `t_cash_sales_sum` 
  //                   ON (`t_cash_sales_det`.`id` = `t_cash_sales_sum`.`id`)
  //                   WHERE `t_cash_sales_sum`.`date` BETWEEN '".$this->sd['from']."' AND '".$this->sd['to']."'
  //                   GROUP BY  `t_cash_sales_sum`.`no`";
        
  //         }
  //         else if($this->sd['sale']=='credit')
  //         {
  //                $sql = "SELECT
  //               `t_sales_sum`.`no`
  //               , `t_sales_sum`.`date`
  //               , `t_sales_det`.`cost` as cash
  //               , t_sales_det.`quantity`
  //           FROM
  //               `t_sales_det`
  //               INNER JOIN `t_sales_sum` 
  //                   ON (`t_sales_det`.`id` = `t_sales_sum`.`id`)
  //                   WHERE `t_sales_sum`.`date` BETWEEN '".$this->sd['from']."' AND '".$this->sd['to']."'
  //                   GROUP BY  `t_sales_sum`.`no`";
              
              
              
  //         }    
        
  //       $query = $this->db->query($sql);
        
  //       if($query->num_rows){
  //           $result = $query->result();
            
  //            $s= $t = 0;
  //               foreach($result as $rr){
  //                   $t += $rr->quantity;
  //                   $s += $rr->cash;
  //               }
                
  //               $r = new stdClass();
  //               $r->date = "";
		// $r->no = array("data"=>"Total", "style"=>"text-align: right; font-weight: bold; border: none;");
  //               $r->cash =  array("data"=>number_format($s, 2, '.', ','), "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
  //               $r->quantity = array("data"=>number_format($t, 2, '.', ','), "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
                
  //               array_push($result, $r);

  //           $name = array("data"=>"Invoice No", "style"=>"text-align: right; ", "chalign"=>"text-align: right;");
  //           $code = array("data"=>"Date", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:80px");
  //           $address = array("data"=>"Total","style"=>"text-align: right; ", "chalign"=>"text-align: right;width:200px");
  //           $total = array("data"=>"Qty", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:200px");
            
  //           $heading = array($code, $name, $address, $total);//$inv_no, , $cus, $sale
            
            
  //           $name  = array("data"=>"no", "total"=>false, "format"=>"text");
  //           $code  = array("data"=>"date", "total"=>false, "format"=>"text");
  //           $address  = array("data"=>"cash", "total"=>false, "format"=>"text");
  //           $total  = array("data"=>"quantity", "total"=>false, "format"=>"amount");
            
  //           $field = array($code, $name, $address, $total);//$inv_no, , $cus, $sale
            
  //           $page_rec = 30;
            
           
  //           $header  = array("data"=>$this->useclass->r_header("Quantity List Report  - ".$this->sd['from']." To ".$this->sd['to']."|".$this->sd['sale'].' '."sales"), "style"=>"");
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