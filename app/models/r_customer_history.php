<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_customer_history extends CI_Model {
    
    private $tb_cus;
    private $tb_move;
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
        $this->tb_move = $this->tables->tb['t_item_movement'];
    }
    
    public function base_details(){
        // $this->load->model('m_stores');
        
        // $a['report'] = $this->report();
        // $a['stores'] = $this->m_stores->select("des", "width : 130px;");
        // $a['title'] = "Items";
        
        // return $a;
    }
    
//     public function report(){
//         if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}
//         if(! isset($this->sd['to'])){ $this->sd['to'] = date("Y-m-d");}
//         if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
//         if(isset($this->sd['paper'])){
//             if($this->sd['paper'] = "l"){
//                 $this->h = 279;
//                 $this->w = 216;
//             }
//         }
        
//         $adv=0;
        
//         if(! isset($this->sd['customer'])){ $this->sd['customer'] = ""; }
        
//         /*$sql = "SELECT 
// 		date,
// 		cr_trnce_code,
// 		cr_trnce_no,
// 		dr_trnce_no,
// 		dr_trnce_code,
// 		dr_amount,
// 		cr_amount,
// 		a.amount AS opbal ,
//                 b.adv_bal AS adv_bal
// 	      FROM
// 		t_customer_acc_trance  CROSS JOIN (SELECT 	  
// 				(SUM(dr_amount) - SUM(cr_amount)) AS amount
			      
// 			      FROM
// 				t_customer_acc_trance 
// 			      WHERE customer = '".$this->sd['customer']."' 
// 				AND `date` <'".$this->sd['from']."' 
// 			      ) a
//                               INNER JOIN (SELECT (SUM(`dr_trance_amount`)-SUM(`cr_trance_amount`))
//                               AS adv_bal FROM `t_advance_pay_trance`
//                               WHERE `customer` = '".$this->sd['customer']."'  AND `date` BETWEEN '".$this->sd['from']."' 
//                                 AND '".$this->sd['to']."' ) AS b   

// 	    WHERE customer = '".$this->sd['customer']."' 
// 		AND `date` BETWEEN '".$this->sd['from']."' 
// 		AND '".$this->sd['to']."' 
// 		ORDER BY `date` ASC ";*/
        
//         /*$sql="SELECT IFNULL(`date`,'') AS `date`
//                 ,m_customer.`code`,
//                 IFNULL(cr_trnce_code,'') AS cr_trnce_code,
//                 IFNULL(cr_trnce_no,'') AS cr_trnce_no,
//                 IFNULL(dr_trnce_no,'') AS dr_trnce_no,
//                 IFNULL(dr_trnce_code,'') AS dr_trnce_code,
//                 IFNULL(dr_amount,'') AS dr_amount,
//                 IFNULL(cr_amount,'') AS cr_amount,
//                 IFNULL(a.amount,'') AS opbal,
//                 b.adv_bal AS adv_bal 
//               FROM
//                 `m_customer` 
//                 LEFT JOIN (SELECT * FROM t_customer_acc_trance
//               WHERE `date` BETWEEN '".$this->sd['from']."' 
//                                 AND '".$this->sd['to']."') acct 
//                   ON m_customer.`code` = acct.`customer` 
//                 CROSS JOIN 
//                   (SELECT 
//                     (SUM(dr_amount) - SUM(cr_amount)) AS amount 
//                   FROM
//                     t_customer_acc_trance 
//                   WHERE customer = '".$this->sd['customer']."' 
//                     AND `date` < '".$this->sd['from']."') a  
//                          LEFT JOIN (SELECT (SUM(`dr_trance_amount`)-SUM(`cr_trance_amount`))                                             AS adv_bal,customer FROM `t_advance_pay_trance`
//                                             WHERE `customer` = '".$this->sd['customer']."'  AND `date` BETWEEN '".$this->sd['from']."' 
//                                 AND '".$this->sd['to']."') AS b   ON b.customer=m_customer.`code`      
//               WHERE m_customer.`code` = '".$this->sd['customer']."'";*/
        
//         $sql="SELECT 
//   data_list.*,
//     IFNULL(opening.balance,0) AS opbal,
//   `code`,
//   `name`

// FROM
//   `m_customer` c 
//   LEFT OUTER JOIN 
//     (SELECT 
//        `date`,
//       `customer`,
//       `cr_trnce_code`,
//       `cr_trnce_no`,
//       `dr_trnce_code`,
//       `dr_trnce_no`,
//       `dr_amount`,
//       `cr_amount`,
//       '' AS adv_bal
//     FROM
//       (SELECT 
// 	`date`,
//         `customer`,
//         `cr_trnce_code`,
//         `cr_trnce_no`,
//         `dr_trnce_code`,
//         `dr_trnce_no`,
//         `dr_amount`,
//         `cr_amount`,
//         '' AS adv_bal
//       FROM
//         t_customer_acc_trance 
//       UNION
//       ALL 
//       SELECT 
//         `date`,
//         `customer`,
//         `cr_trance_code`,
//         `cr_trance_no`,
//         `dr_trance_code`,
//         `dr_trance_no`,
//         `dr_trance_amount`,
//         `cr_trance_amount` ,
//         '' AS adv_bal
//       FROM
//         `t_advance_pay_trance`) d 
//     WHERE d.`date` BETWEEN '".$this->sd['from']."' 
//       AND '".$this->sd['to']."' 
//     ORDER BY d.`date`) data_list 
//     ON c.`code` = data_list.customer 
//   LEFT OUTER JOIN 
//     (SELECT 
//       customer,
//       IFNULL((SUM(`dr_amount`) - SUM(`cr_amount`)),0) AS balance 
//     FROM
//       (SELECT 
//         `customer`,
//         `date`,
//         `dr_trnce_code`,
//         `dr_trnce_no`,
//         `cr_trnce_code`,
//         `cr_trnce_no`,
//         `dr_amount`,
//         `cr_amount` 
//       FROM
//         t_customer_acc_trance 
//       UNION
//       ALL 
//       SELECT 
//         `customer`,
//         `date`,
//         `dr_trance_code`,
//         `dr_trance_no`,
//         `cr_trance_code`,
//         `cr_trance_no`,
//         `dr_trance_amount`,
//         `cr_trance_amount` 
//       FROM
//         `t_advance_pay_trance`) a 
//     WHERE a.`date` < '".$this->sd['from']."') opening 
//     ON c.`code` = opening.customer 
// WHERE c.`code` = '".$this->sd['customer']."'  
//  ";
        
        
        
        
        
//        //echo $sql;exit;
        
//       $query = $this->db->query($sql);
	
// 	$qry = $query->first_row();
	
        
// 	if($query->num_rows()){
// 		    $a=$qry->opbal;
//                     $adv=$qry->adv_bal;
// 		}
	
	
//         $this->db->where('code', $this->sd['customer']);
//         $this->db->limit(1);
//         $que = $this->db->get($this->tb_cus);
//         if($que->num_rows){
//             $que = $que->first_row();
            

// 	    if($query->num_rows()){
//                 $result = $query->result();
//                 $res = array();$bal = 0;
                
            
//             $ad_dr=$ad_cr=$b=$c=$t=$to=$b=$cr=$to1=0; 
             
//             $tot_cr=0;
//             $tot_dr=0;
            
            
            
// 	    if($a>0)
// 	    {
// 		$b=$a;
// 	    }
// 	    else{
// 		$c=$a;
// 	    }
	    
// 	    $tot_cr=$c;
//             $tot_dr=$b;
            
            
            
            
// 		$qry = new stdClass();
//                 $qry->date = "";
//                 $qry->transe_id = array("data"=>"Opening Balance", "style"=>"text-align: right; font-weight: bold; border: none;font-weight: bold;");
//                 $qry->transe_code = "";
// 		$qry->inv = "";
// 		$qry->transe = "";
//                 $qry->qun = "";
//                 $qry->dr_amount = array("data"=>number_format($b, 2, '.', ','), "style"=>"text-align: right; ");
//                 $qry->cr_amount = array("data"=>number_format($c, 2, '.', ','), "style"=>"text-align: right; ");
//                 $qry->balance = "";
	   
// 		$res[]=$qry;
		
//                 foreach($result as $rr){
                    
//                    $bal += ($rr->dr_amount)-($rr->cr_amount);
//                    $to += $bal;
                    
                    
//                    $tot_dr+=$rr->dr_amount;
//                    $tot_cr+=$rr->cr_amount;
                    
//                    $to1= $tot_dr- $tot_cr;

//                     if($rr->cr_trnce_code == "SALES_RET"){
//                         $rr->cr_trnce_code = "SALES RETURN";
//                     }
                    
//                     $r = new stdClass();
		    
//                     $r->date = $rr->date;
//                     $r->transe_id = $rr->cr_trnce_no;
//                     $r->transe_code = $rr->cr_trnce_code;
// 		    $r->inv = $rr->dr_trnce_code;
// 		    $r->transe = $rr->dr_trnce_no;
//                     $r->qun = number_format($rr->opbal, 2, '.', ',');
//                     $r->dr_amount = number_format($rr->dr_amount, 2, '.', ',');
//                     $r->cr_amount = number_format($rr->cr_amount, 2, '.', ',');
//                     $r->balance = number_format($bal, 2, '.', ',');
                    
//                     $res[] = $r;
//                 }
               
                
//                 $tot_dr=$tot_dr;
//                 $tot_cr=$tot_cr;
                
//                 $to1=$tot_dr-$tot_cr;
                
//             $r = new stdClass();
                
//                 $r->date = "";
//                 $r->transe_id = "";
//                 $r->transe_code = "";
// 		$r->inv = "";
// 		$r->transe = array("data"=>"Total", "style"=>"text-align: right; font-weight: bold; border: none;font-weight: bold;");
//                 $r->qun = "";
//                 $r->dr_amount = array("data"=>number_format($tot_dr, 2, '.', ','), "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;border-top : 1px solid #000;");
//                 $r->cr_amount =array("data"=>number_format( $tot_cr, 2, '.', ','), "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;border-top : 1px solid #000;");
//                 $r->balance = array("data"=>number_format($to1, 2, '.', ','), "style"=>"text-align: right; font-weight: bold; border-bottom: 2px solid #000;border-top : 1px solid #000;");
	    
                
                
//             array_push($res, $r);
                
             
//                 $date = array("data"=>"Date", "style"=>"text-align: left;width:100px", "chalign"=>"text-align: left;");
//                 $tcode = array("data"=>"Trans Code","style"=>"text-align: left;  ", "chalign"=>"text-align: left;");
// 		$tid = array("data"=>"Trans ID", "style"=>"text-align: left;   ", "chalign"=>"text-align: left;");
//                 $inv = array("data"=>"Invoice Type","style"=>"text-align: left;  ", "chalign"=>"text-align: left;");
// 		$code = array("data"=>"Invoice No","style"=>"text-align: left;  ", "chalign"=>"text-align: left;");
//                 //$qun = array("data"=>"Amount", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
//                 $qun1 = array("data"=>"Dr Amount", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
//                 $qun2 = array("data"=>"Cr Amount", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
//                 $balance= array("data"=>"Balance", "style"=>"text-align: right;  width:100px", "chalign"=>"text-align: right;");
                
//                 $heading = array($date, $tcode,$tid, $inv,$code, $qun1,$qun2, $balance);
                
//                 $date = array("data"=>"date", "total"=>false, "format"=>"text");
//                 $tid  = array("data"=>"transe_id", "total"=>false, "format"=>"text");
//                 $tcode  = array("data"=>"transe_code", "total"=>false, "format"=>"text");
// 		$inv  = array("data"=>"inv", "total"=>false, "format"=>"text");
// 		$code  = array("data"=>"transe", "total"=>false, "format"=>"text");
//                 //$qun  = array("data"=>"amount", "total"=>false, "format"=>"text");
//                 $qun1  = array("data"=>"dr_amount", "total"=>false, "format"=>"text");
//                 $qun2  = array("data"=>"cr_amount", "total"=>false, "format"=>"text");
//                 $balance  = array("data"=>"balance", "total"=>false, "format"=>"text");
                
//                 $field = array($date, $tcode,$tid, $inv,$code,$qun1,$qun2, $balance);
                
//                 $page_rec = 30;
                
//                 $header  = array("data"=>$this->useclass->r_header("CUSTOMER HISTORY:- ".$this->sd['from']." To ".$this->sd['to'].'~~'.$que->outlet_name." (".$que->code.")"), "style"=>"");
//                 $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
//                 $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
                
//                 $data = array(
//                                   "dbtem"=>$this->useclass->report_style(),
//                                   "data"=>$res,
//                                   "field"=>$field,
//                                   "heading"=>$heading,
//                                   "page_rec"=>$page_rec,
//                                   "height"=>$this->h,
//                                   "width"=>$this->w,
//                                   "header"=>30,
//                                   "header_txt"=>$header,
//                                   "footer_txt"=>$footer,
//                                   "page_no"=>$page_no,
//                                   "header_of"=>false
//                                   );
//                 //print_r($data); exit;
//                 $this->load->library('greport', $data);
                
//                 $resu = $this->greport->_print();
//             }else{
//                 $resu = "<span style='color:red;'>There is no data to load.</span>";
//             }
//         }else{
//             $resu = "<span style='color:red;'>Wrong Customer ".$this->sd['customer'].".</span>";
//         }
        
//         return $resu;
//     }
}
?>