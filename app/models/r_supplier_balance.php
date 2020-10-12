<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_supplier_balance extends CI_Model {
    
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
        // $a['title'] = "Supplier Balance";
        
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
        
        
  //       /*$sql = "SELECT
  //                   `code`,
  //                   CONCAT(name, ' (', `full_name`, ')') AS `name`,
  //                   CONCAT(address01, ', ', address02, ', ', ', ', address03) AS address,
  //                   IFNULL(bal.bal, 0) as total
  //                 FROM (`".$this->tb_cus."`)
  //                   LEFT OUTER JOIN (SELECT
  //                                 (SUM(dr_amount - cr_amount)) AS bal,
  //                                 supplier
  //                               FROM t_supplier_acc_trance
  //                               GROUP BY supplier) AS bal
  //                     ON (bal.supplier = ".$this->tb_cus.".code)";*/
        
        
  //       $sql="SELECT 
  //               `date`,
  //               `code`,`name`,
  //               CONCAT(`address01`,',',`address02`,',',`address03`) AS address,
  //               SUM(dr_amount)-SUM(cr_amount) AS total
  //             FROM
  //               t_supplier_acc_trance 
  //             INNER JOIN `m_supplier` ON (`m_supplier`.`code`=t_supplier_acc_trance.`supplier`)  
  //               WHERE  `date` <= '".$this->sd['from']."' 
                
  //             GROUP BY supplier   
  //             HAVING total>0  
  //             ORDER BY `date` ASC";
        
  //         $tot=0;
  //       $query = $this->db->query($sql);
  //       $result = $query->result();
      
  //       if($query->num_rows){
            
            
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
  //           $code = array("data"=>"Code", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:60px");
  //           $address = array("data"=>"Address","style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //           $total = array("data"=>"Balance", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:80px");
            
  //           $heading = array($code, $name, $address, $total);//$inv_no, , $cus, $sale
            
            
  //           $name  = array("data"=>"name", "total"=>false, "format"=>"text");
  //           $code  = array("data"=>"code", "total"=>false, "format"=>"text");
  //           $address  = array("data"=>"address", "total"=>false, "format"=>"text");
  //           $total  = array("data"=>"total", "total"=>false, "format"=>"text");
            
  //           $field = array($code, $name, $address, $total);//$inv_no, , $cus, $sale
            
  //           $page_rec = 30;
            
  //           $header  = array("data"=>$this->useclass->r_header("Supplier Balance-As at :".$this->sd['from'].""), "style"=>"");
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