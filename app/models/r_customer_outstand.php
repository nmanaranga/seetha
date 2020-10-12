<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_customer_outstand extends CI_Model {
    
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
  //                   `m_root`.`description` AS root,
  //                   `m_customer`.`code`,
  //                   CONCAT(outlet_name, ' (', NAME, ')') AS `name`,
  //                   CONCAT(address01, ', ', address02, ', ', ', ', address03) AS address,
  //                   IFNULL(bal.bal, 0) as total,
  //                   m_area.description as area,
  //                   dr_trnce_no,
  //                   bal.sales_ref,
  //                   bal.nm
                    
  //                 FROM (`".$this->tb_cus."`)
  //                   INNER JOIN `m_root`
  //                     ON `m_root`.`code` = `".$this->tb_cus."`.`root`
  //                   INNER JOIN `m_area`
  //                     ON `m_root`.`area` = `m_area`.`code`
  //                   INNER JOIN (SELECT
  //                                 (SUM(dr_amount - cr_amount)) AS bal,
  //                                 t_customer_acc_trance.customer,dr_trnce_no,`sales_ref` ,
  //                                 `m_sales_ref`.`name` AS nm
  //                               FROM t_customer_acc_trance
  //                               INNER JOIN `t_sales_sum` ON (`t_sales_sum`.`no`= t_customer_acc_trance.`dr_trnce_no`)
  //                               INNER JOIN `m_sales_ref` ON (`m_sales_ref`.`code`=t_sales_sum.sales_ref)
  //                               GROUP BY customer,dr_trnce_no    
  //                           HAVING bal>0) AS bal
  //                     ON (bal.customer = ".$this->tb_cus.".code)";
        
  //       //echo $sql;exit;
        
  //       if($this->sd['area'] != 0){
  //           $sql .= " WHERE area = '".$this->sd['area']."'";
  //           if($this->sd['root'] != 0){
  //               $sql .= " AND ".$this->tb_cus.".root = '".$this->sd['root']."'";
  //           }
            
  //       }
  //           if($this->sd['ref'] != 0){
  //               $sql .= " AND bal.sales_ref = '".$this->sd['ref']."'";
  //           }
        
        
  //       $query = $this->db->query($sql);
        
  //       if($query->num_rows){
  //           $result = $query->result();
            
            
  //                           $st = 0;
		// $t= 0;
		// $res = array();
		// $cus = '';
		
  //               foreach($result as $r){
                    
                
		// if ($cus != $r->code) {

  //                   if ($cus != '') {
  //                       $rec = new stdClass;
  //                       $rec->code = "";
  //                       $rec->name = "";
  //                       $rec->address = "";
  //                       $rec->root = "";
  //                       $rec->dr_trnce_no = array("data" => "Sub Total", "style" => "font-weight: bold; text-align: right;");
  //                       $rec->total = array("data"=>number_format($t, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
			
		// 	$t = 0;
  //                       $res[] = $rec;
  //                   }
  //                   $rec = new stdClass;
  //                   $rec->code = $r->code;
  //                   $rec->name = $r->name;
  //                   $rec->address = $r->address;
		//     $rec->root = $r->root;
  //                   $rec->dr_trnce_no = '';
  //                   $rec->total = array("data" => 0, "style" => "color:#F0FFFF; text-align: right;");
  //                   $res[] = $rec;
  //               }

  //               $cus = $r->code;

  //               $t += $r->total;
  //               $rec = new stdClass;
  //               $rec->code = "";
  //               $rec->name = "";
  //               $rec->address = "";
  //               $rec->root = "";
		// $rec->dr_trnce_no = $r->dr_trnce_no;
  //               $rec->total = $r->total;
		
  //               $res[] = $rec;
  //               $st += $r->total;
		
		// }
		
		// $rec = new stdClass;
		// $rec->code = "";
		// $rec->name = "";
		// $rec->address = "";
		// $rec->root = "";
		// $rec->dr_trnce_no = array("data" => "Sub Total", "style" => "font-weight: bold; text-align: right;");
		// $rec->total = array("data"=>number_format($t, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
		// $res[] = $rec;



		// $rec = new stdClass;
		// $rec->code = "";
		// $rec->name = "";
		// $rec->address = "";
		// $rec->root = "";
		// $rec->dr_trnce_no = array("data" => "Total", "style" => "font-weight: bold; text-align: right;");
		// $rec->total = array("data"=>number_format($st, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
		// $res[] = $rec;
	    
	    
	    
	    
		// $code = array("data"=>"Code", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //               $name = array("data"=>"Name", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:200px");
  //               $Address = array("data"=>"Address","style"=>"text-align: center; ", "chalign"=>"text-align: left; width:100px");
  //               $root = array("data"=>"Root", "style"=>"text-align: center; ", "chalign"=>"text-align: left; width:300px");
  //               $dr_no = array("data"=>"Invoice No", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
		// $tot = array("data"=>"Total", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:30px");
                
  //               $heading = array($code,$name,$Address,$root,$dr_no,$tot);
                
  //               $code = array("data"=>"code", "total"=>false, "format"=>"text");
		// $name  = array("data"=>"name", "total"=>false, "format"=>"text");
		// $Address  = array("data"=>"address", "total"=>false, "format"=>"text");
		// $root  = array("data"=>"root", "total"=>false, "format"=>"text");
		// $dr_no  = array("data"=>"dr_trnce_no", "total"=>false, "format"=>"text");
		// $tot  = array("data"=>"total", "total"=>false, "format"=>"text");
		
                
  //               $field = array($code,$name,$Address,$root,$dr_no,$tot);

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
            
  //           if($this->sd['area'] == "0"){
  //               $name = "Customer Outstand List All";
  //           }else{
  //               $name = "Customer Outstand List | ".$result[0]->area." (".$this->sd['area'].") Area & All Routes";
  //               if($this->sd['root'] != "0"){
  //                   $name = "Customer Outstand List | Area : ".$result[0]->area." (".$this->sd['area'].") | Route : ".$result[0]->root." (".$this->sd['root'].")";
  //               }
  //           }
  //           if($this->sd['ref'] != 0){
                
  //                $name = "Customer Outstand List | ".$result[0]->nm." (".$this->sd['ref'].") ";
  //           }
            
            
  //           $header  = array("data"=>$this->useclass->r_header($name), "style"=>"");
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