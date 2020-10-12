<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_ad_refund extends CI_Model {
    
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
        // $a['title'] = "Advance Refund Report";
        
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
  //       if(! isset($this->sd['type'])){ $this->sd['type'] = "sum";}
  //       if($this->sd['type'] == 'sum'){

  //   $sql="SELECT `no`,`date`,`customer`,`advance_pay_no`,`amount`,`balance`,m_customer.name AS full_name

	 //    FROM `t_advance_refund`
	 //    INNER JOIN m_customer
  //                         ON (m_customer.code = t_advance_refund.customer)

	 //    WHERE DATE BETWEEN '".$this->sd['from']."' AND '".$this->sd['to']."'";

  //       }else{
            
	 //    $sql = "";
	    
  //       }
        
  //       $query = $this->db->query($sql);
        
  //       if($query->num_rows){
  //           if($this->sd['type'] == 'sum'){
  //               $result = $query->result();
  //               $t = 0;
  //               //foreach($result as $rr){
  //               //    $t += $rr->cost;
  //               //}
                
  //               //$r = new stdClass();
  //               //$r->no = "";
  //               //$r->full_name = "";
  //               //$r->cheque_amount = "";
  //               //$r->cash_amount = array("data"=>"Total", "style"=>"text-align: right; font-weight: bold; border: none;");
  //               ////$r->cheque_amount = array("data"=>$t, "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;");
  //               //
  //               //array_push($result, $r);
                
  //               $inv_no = array("data"=>"Rec No", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:80px");
		// $ad = array("data"=>"Advance Rec No", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
		// $date = array("data"=>"Date", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:80px");
		// $code = array("data"=>"Cus Code", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
		// $name = array("data"=>"Cus Name", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
		// $tot = array("data"=>"Total", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
		// $bal = array("data"=>"Balance", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
                
  //               $heading = array($inv_no,$ad,$date,$code, $name,$tot,$bal);
                
  //               $inv_no = array("data"=>"no", "total"=>false, "format"=>"text");
		// $job_no  = array("data"=>"advance_pay_no", "total"=>false, "format"=>"text");
		// $date  = array("data"=>"date", "total"=>false, "format"=>"text");
		// $code  = array("data"=>"customer", "total"=>false, "format"=>"text");
		// $name  = array("data"=>"full_name", "total"=>false, "format"=>"text");
		// $tot  = array("data"=>"amount", "total"=>false, "format"=>"amount");
		// $bal  = array("data"=>"Refund", "total"=>false, "format"=>"amount");
                
  //               $field = array($inv_no,$job_no,$date,$code,$name, $tot,$bal);
                
  //               $page_rec = 30;
                
  //               $header  = array("data"=>$this->useclass->r_header("Advance Refund Report - ".$this->sd['from']." To ".$this->sd['to']), "style"=>"");
  //               $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
  //               $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
                
  //               $data = array(
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
  //               //print_r($data); exit;
  //               $this->load->library('greport', $data);
                
  //               $resu = $this->greport->_print();
  //           }else{
                
  //           }
  //       }else{
  //           $resu = "<span style='color:red;'>There is no data to load.</span>";
  //       }
        
  //       return $resu;
  //   }
}
?>