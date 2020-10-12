<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_cus_settlement extends CI_Model {
    
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
        $this->load->model('m_stores');
        
        $a['report'] = $this->report();
        $a['stores'] = $this->m_stores->select("des", "width : 130px;");
        $a['title'] = "Items";
        
        return $a;
    }
    
    public function report(){
        if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}
        if(! isset($this->sd['to'])){ $this->sd['to'] = date("Y-m-d");}
        if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
        if(isset($this->sd['paper'])){
            if($this->sd['paper'] = "l"){
                $this->h = 279;
                $this->w = 216;
            }
        }
        
        if(! isset($this->sd['customer'])){ $this->sd['customer'] = ""; }
        
        $sql = "SELECT
                    '' AS `date`,
                    'Before ".$this->sd['from']."'   AS dr_trnce_code,
                    ''                  AS dr_trnce_no,
                    (SUM(dr_amount) - SUM(cr_amount)) AS amount
                  FROM t_customer_acc_trance
                  WHERE customer = '".$this->sd['customer']."'
                      AND `date` < '".$this->sd['from']."'UNION SELECT
                                                       `date`,
                                                       dr_trnce_code,
                                                       dr_trnce_no,
                                                       (SUM(dr_amount) - SUM(cr_amount)) AS amount
                                                     FROM t_customer_acc_trance
                                                     WHERE customer = '".$this->sd['customer']."'
                                                         AND `date` >= '".$this->sd['from']."'
                  GROUP BY dr_trnce_code,dr_trnce_no ORDER BY `date` ASC";
        
        $query = $this->db->query($sql);
        
        $this->db->where('code', $this->sd['customer']);
        $this->db->limit(1);
        $que = $this->db->get($this->tb_cus);
        if($que->num_rows){
            $que = $que->first_row();
            if($query->num_rows){
                $result = $query->result();
                $res = array();$bal = 0;
                foreach($result as $rr){
                    $bal += $rr->amount;
                    
                    if($rr->dr_trnce_code == "SALES_RET"){
                        $rr->dr_trnce_code = "SALES RETURN";
                    }
                    
                    $r = new stdClass();
                    $r->date = $rr->date;
                    $r->transe_id = $rr->dr_trnce_no;
                    $r->transe_code = $rr->dr_trnce_code;
                    $r->qun = number_format($rr->amount, 2, '.', ',');
                    $r->balance = number_format($bal, 2, '.', ',');
                    
                    $res[] = $r;
                }
                
                $date = array("data"=>"Date", "style"=>"text-align: left;", "chalign"=>"text-align: left;");
                $tid = array("data"=>"Trance ID", "style"=>"text-align: left;   width:100px", "chalign"=>"text-align: left;");
                $tcode = array("data"=>"Trance Code","style"=>"text-align: left;  width:150px", "chalign"=>"text-align: left;");
                $qun = array("data"=>"Amount", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
                $balance= array("data"=>"Balance", "style"=>"text-align: right;  width:100px", "chalign"=>"text-align: right;");
                
                $heading = array($date, $tid, $tcode, $qun, $balance);
                
                $date = array("data"=>"date", "total"=>false, "format"=>"text");
                $tid  = array("data"=>"transe_id", "total"=>false, "format"=>"text");
                $tcode  = array("data"=>"transe_code", "total"=>false, "format"=>"text");
                $qun  = array("data"=>"qun", "total"=>false, "format"=>"text");
                $balance  = array("data"=>"balance", "total"=>false, "format"=>"text");
                
                $field = array($date, $tid, $tcode, $qun, $balance);
                
                $page_rec = 30;
                
                $header  = array("data"=>$this->useclass->r_header("Customer History : ".$que->outlet_name." (".$que->code.")"), "style"=>"");
                $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
                $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
                
                $data = array(
                                  "dbtem"=>$this->useclass->report_style(),
                                  "data"=>$res,
                                  "field"=>$field,
                                  "heading"=>$heading,
                                  "page_rec"=>$page_rec,
                                  "height"=>$this->h,
                                  "width"=>$this->w,
                                  "header"=>30,
                                  "header_txt"=>$header,
                                  "footer_txt"=>$footer,
                                  "page_no"=>$page_no,
                                  "header_of"=>false
                                  );
                //print_r($data); exit;
                $this->load->library('greport', $data);
                
                $resu = $this->greport->_print();
            }else{
                $resu = "<span style='color:red;'>There is no data to load.</span>";
            }
        }else{
            $resu = "<span style='color:red;'>Wrong Customer ".$this->sd['customer'].".</span>";
        }
        
        return $resu;
    }
}
?>