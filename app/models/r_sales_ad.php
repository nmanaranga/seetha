 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_sales_ad extends CI_Model {
    
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
        // $a['title'] = "Advance Payment Report";
        
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
  //           $sql = "SELECT
  //                       t_advance_pay_sum.no,
		// 	m_customer.code,
  //                       m_customer.name AS full_name,
  //                       t_advance_pay_sum.cheque_amount,
		// 	t_advance_pay_sum.cash_amount,
		// 	t_advance_pay_sum.cheque_amount+t_advance_pay_sum.cash_amount AS tot,
		// 	advance_balance.AdvanceBalance AS balance,
		// 	t_advance_pay_sum.date
                        
  //                     FROM t_advance_pay_sum
  //                       INNER JOIN m_customer
  //                         ON (m_customer.code = t_advance_pay_sum.customer)
		// 	INNER JOIN advance_balance
  //                         ON (t_advance_pay_sum.customer = advance_balance.customer)                     
                        
  //                     WHERE t_advance_pay_sum.date BETWEEN '".$this->sd['from']."'
  //                         AND '".$this->sd['to']."'
  //                         AND t_advance_pay_sum.is_cancel = 0
  //                         AND bc = '".$this->sd['bc']."'
  //                      ORDER BY t_advance_pay_sum.id";
  //       }else{
            
	 //    $sql = "SELECT
  //                       t_advance_pay_sum.no,
  //                       m_customer.name AS full_name,
		// 	m_customer.code,
  //                       t_advance_pay_sum.cheque_amount,
		// 	t_advance_pay_sum.cash_amount,
		// 	t_advance_pay_sum.cheque_amount+t_advance_pay_sum.cash_amount AS tot,
		// 	t_advance_pay_sum.date
                        
  //                     FROM t_advance_pay_sum
  //                       INNER JOIN m_customer
  //                         ON (m_customer.code = t_advance_pay_sum.customer)
                                                
                        
  //                     WHERE t_advance_pay_sum.date BETWEEN '".$this->sd['from']."'
  //                         AND '".$this->sd['to']."'
  //                         AND t_advance_pay_sum.is_cancel = 0
  //                         AND bc = '".$this->sd['bc']."'
  //                      ORDER BY t_advance_pay_sum.id";
	    
  //       }
        
  //       //echo $sql;exit;
        
  //       $query = $this->db->query($sql);
        
  //       if($query->num_rows){
  //           if($this->sd['type'] == 'sum'){
  //               $result = $query->result();
  //               $t = 0;
                
  //               $inv_no = array("data"=>"Rec No", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:80px");
		// $code = array("data"=>"Cus Code", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //               $job_no = array("data"=>"Cus Name", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
		// $tot = array("data"=>"Total", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
		// $bal = array("data"=>"Balance", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
                
  //               $heading = array($inv_no,$code, $job_no,$tot,$bal);
                
  //               $inv_no = array("data"=>"no", "total"=>false, "format"=>"text");
		// $code  = array("data"=>"code", "total"=>false, "format"=>"text");
  //               $job_no  = array("data"=>"full_name", "total"=>false, "format"=>"text");
		// $tot  = array("data"=>"tot", "total"=>false, "format"=>"amount");
		// $bal  = array("data"=>"balance", "total"=>false, "format"=>"amount");
                
  //               $field = array($inv_no,$code, $job_no,$tot,$bal);
                
  //               $page_rec = 30;
                
  //               $header  = array("data"=>$this->useclass->r_header("Advance Payment Summary Report - ".$this->sd['from']." To ".$this->sd['to']), "style"=>"");
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
		// $code = array("data"=>"Cus Code", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //               $job_no = array("data"=>"Customer", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
		// $date=array("data"=>"Date", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
  //               $vehi = array("data"=>"Cash Amount","style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
  //               $cus = array("data"=>"Cheque Amount", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
  //               $tot = array("data"=>"Total", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:100px");
                
  //               $heading = array($inv_no,$code, $job_no,$date, $vehi, $cus,$tot);
                
  //               $inv_no = array("data"=>"no", "total"=>false, "format"=>"text");
		// $code  = array("data"=>"code", "total"=>false, "format"=>"text");
  //               $job_no  = array("data"=>"full_name", "total"=>false, "format"=>"text");
		// $date=array("data"=>"date", "total"=>false, "format"=>"text");
  //               $vehi  = array("data"=>"cheque_amount", "total"=>false, "format"=>"text");
  //               $cus  = array("data"=>"cash_amount", "total"=>false, "format"=>"text");
  //               $tot  = array("data"=>"tot", "total"=>false, "format"=>"amount");
                
  //               $field = array($inv_no,$code, $job_no,$date, $vehi, $cus,$tot);
                
  //               $page_rec = 28;
                
  //               $header  = array("data"=>$this->useclass->r_header("Advance Payment Detail Report - ".$this->sd['from']." To ".$this->sd['to']), "style"=>"");
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
  //                             "header"=>40,
  //                             "header_txt"=>$header,
  //                             "footer_txt"=>$footer,
  //                             "page_no"=>$page_no,
  //                             "header_of"=>false
  //                             );
  //               //print_r($data); exit;
  //               $this->load->library('greport', $data);
                
  //               $resu = $this->greport->_print();
  //           }
  //       }else{
  //           $resu = "<span style='color:red;'>There is no data to load.</span>";
  //       }
        
  //       return $resu;
  //   }
}
?>