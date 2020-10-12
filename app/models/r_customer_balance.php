<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_customer_balance extends CI_Model {
    
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
  //       $sql = "SELECT
  //                   `m_root`.`description` AS root,
  //                   `m_customer`.`code`,
  //                   CONCAT(outlet_name, ' (', NAME, ')') AS `name`,
  //                   CONCAT(address01, ', ', address02, ', ', ', ', address03) AS address,
  //                   IFNULL(bal.bal, 0)+IFNULL(b.adv_bal,0) as total,
  //                   m_area.description as area
                    
  //                 FROM (`".$this->tb_cus."`)
  //                   INNER JOIN `m_root`
  //                     ON `m_root`.`code` = `".$this->tb_cus."`.`root`
  //                   INNER JOIN `m_area`
  //                     ON `m_root`.`area` = `m_area`.`code`
  //                   LEFT OUTER JOIN (SELECT
  //                                 (SUM(dr_amount - cr_amount)) AS bal,
  //                                 customer
  //                               FROM t_customer_acc_trance
  //                               GROUP BY customer) AS bal
  //                     ON (bal.customer = ".$this->tb_cus.".code)
  //                LEFT JOIN 
  //   (SELECT customer,
  //     (
  //       SUM(`dr_trance_amount`) - SUM(`cr_trance_amount`)
  //     )*-1 AS adv_bal 
  //   FROM
  //     `t_advance_pay_trance` 
  //   GROUP BY customer) AS b ON  m_customer.code=b.customer         
  //                 HAVING total>0";
        
  //       //echo $sql;exit;
        
  //       if($this->sd['area'] != 0){
  //           $sql .= " WHERE area = '".$this->sd['area']."'";
  //           if($this->sd['root'] != 0){
  //               $sql .= " AND ".$this->tb_cus.".root = '".$this->sd['root']."'";
  //           }
  //       }
  //       $tot=0;
  //       $query = $this->db->query($sql);
        
  //       if($query->num_rows){
  //       $result = $query->result();
        
  //           foreach($result as $rr){
            
  //            $r = new stdClass();
		    
  //                   $r->code = $rr->code;
  //                   $r->name = $rr->name;
  //                   $r->address = $rr->address;  
  //                   $r->total = number_format($rr->total, 2, '.', ',');
                
                    
  //             $res[] = $r;
              
  //             $tot=$tot+$rr->total;
  //            }
        
  //             $r = new stdClass();
                
  //               $r->code = "";
  //               $r->name = "";
		// $r->address = array("data"=>"Total", "style"=>"text-align: right; font-weight: bold; border: none;font-weight: bold;");
  //               $r->total = array("data"=>number_format($tot, 2, '.', ','), "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;border-top : 1px solid #000;");
     
  //           array_push($res, $r);
             
             
  //           $name = array("data"=>"Name", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //           $code = array("data"=>"Code", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
  //           $address = array("data"=>"Address","style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //           $total = array("data"=>"Balance", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:80px");
            
  //           $heading = array($code, $name, $address, $total);//$inv_no, , $cus, $sale
            
            
  //           $name  = array("data"=>"name", "total"=>false, "format"=>"text");
  //           $code  = array("data"=>"code", "total"=>false, "format"=>"text");
  //           $address  = array("data"=>"address", "total"=>false, "format"=>"text");
  //           $total  = array("data"=>"total", "total"=>false, "format"=>"text");
            
  //           $field = array($code, $name, $address, $total);//$inv_no, , $cus, $sale
            
  //           $page_rec = 30;
            
  //           if($this->sd['area'] == "0"){
  //               $name = "Customer Balance List All";
  //           }else{
  //               $name = "Customer Balance List | ".$result[0]->area." (".$this->sd['area'].") Area & All Routes";
  //               if($this->sd['root'] != "0"){
  //                   $name = "Customer Balance List | Area : ".$result[0]->area." (".$this->sd['area'].") | Route : ".$result[0]->root." (".$this->sd['root'].")";
  //               }
  //           }
            
  //           $header  = array("data"=>$this->useclass->r_header($name.'|As at-'.$this->sd['from']), "style"=>"");
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