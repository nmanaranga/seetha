<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_bin_card extends CI_Model {
    
    private $tb_items;
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
	
        $this->tb_items = $this->tables->tb['m_items'];
        $this->tb_move = $this->tables->tb['t_item_movement'];
    }
    
    public function base_details(){
        // $this->load->model('m_stores');
        
        // $a['report'] = $this->report();
        // $a['stores'] = $this->m_stores->select("des", "width : 130px;");
        // //$this->load->model('r_stock');
        // // $a['rep'] = $this->r_stock->select();
        // $a['title'] = "Items";
        
        // return $a;
    }
    
    // public function report(){
        
        
    //    $heigh=$weight=0; 
    //     if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}
    //     if(! isset($this->sd['to'])){ $this->sd['to'] = date("Y-m-d");}
    //     if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
    //     //if(isset($this->sd['paper'])){
    //       //  if($this->sd['paper'] = "l"){
    //         //    $this->h = 279;
    //           //  $this->w = 216;
    //         //}
    //     //}
    //     //if(! isset($this->sd['paper'])){ $this->sd['paper'] = 0; }
        
    //      //$sql="SELECT height,weight FROM m_page_setup WHERE bc='".$this->sd['bc']."' AND category='".$this->sd['paper']."'";
       
    //    //echo $sql;
    //    //$qry=$this->db->query($sql);
    //    //$r=$qry->first_row();
    //    //if (empty($r->height))
    //    //{
    //        $heigh = 279;
    //        $weight = 216;
    //    //}
    //    // else {
           
       
    //    //$heigh=$r->height;
    //    //$weight=$r->weight;
        
    //     //}
        
        
    //     if(! isset($this->sd['stores'])){ $this->sd['stores'] = 0; }
    //     if(! isset($this->sd['item_code'])){ $this->sd['item_code'] = ""; }
    //     if(! isset($this->sd['sort'])){ $this->sd['sort'] = 0; }
    //     if(! isset($this->sd['paper'])){ $this->sd['paper'] = 0; }
        
    //     if($this->sd['stores'] != "0"){
    //         $where = "AND `stores` = '".$this->sd['stores']."'";
    //     }else{
    //         $where = "";
    //     }
        
    //      if($this->sd['sort'] != "0"){
    //         $group = " ORDER BY '".$this->sd['sort']."' ";
    //     }
    //     else
    //     {
    //         $group="";
    //     }    
        
        
    //     $sql = "SELECT
    //                 '0' AS trance_id,
    //                 'Before ".$this->sd['from']."' AS trance_type,
    //                 '' AS date,
    //                 (SUM(in_quantity) - SUM(out_quantity)) AS quantity,
    //                 IFNULL(description,'') AS description 
    //               FROM t_item_movement
    //               WHERE item_code = '".$this->sd['item_code']."' ".$where." 
    //                   AND `date` < '".$this->sd['from']."'UNION SELECT
    //                                                    trance_id,
    //                                                    trance_type,
    //                                                    date,
    //                                                    (SUM(in_quantity) - SUM(out_quantity)) AS quantity,
    //                                                    IFNULL(description,'') AS description 
    //                                                  FROM t_item_movement
    //                                                  WHERE item_code = '".$this->sd['item_code']."' ".$where." 
    //                                                      AND `date` BETWEEN '".$this->sd['from']."'
    //                                                      AND '".$this->sd['to']."'
    //                     GROUP BY trance_id, trance_type $group";
        
       
        
    //     //echo $sql;

    //     $query = $this->db->query($sql);
        
    //     $this->db->where('code', $this->sd['item_code']);
    //     $this->db->limit(1);
    //     $que = $this->db->get($this->tb_items);
    //     if($que->num_rows){
    //         $que = $que->first_row();
    //         if($query->num_rows){
    //             $result = $query->result();
    //             $res = array();$bal = 0;
    //             foreach($result as $rr){
    //                 $bal += $rr->quantity;
                    
    //                 if($rr->trance_type == "SALES"){
    //                     $rr->trance_type = "SALES";
    //                 }elseif($rr->trance_type == "OPEN"){
    //                     $rr->trance_type = "OPEN RECORD";
    //                 }elseif($rr->trance_type == "PUR_RET"){
    //                     $rr->trance_type = "PURCHASE RETURN";
    //                 }elseif($rr->trance_type == "PUR"){
    //                     $rr->trance_type = "PURCHASE";
    //                 }elseif($rr->trance_type == "SALES_RET"){
    //                     $rr->trance_type = "SALES RETURN";
    //                 }elseif($rr->trance_type == "STOCK_ADJ"){
    //                     $rr->trance_type = "STOCK ADJUSTMENT";
    //                 }elseif($rr->trance_type == "DMG_FREE_ISSU"){
    //                     $rr->trance_type = "DAMAGE OR FREE ISSUE";
    //                 }elseif($rr->trance_type == "TRANS"){
    //                     $rr->trance_type = "TRANSFER";
    //                 }elseif($rr->trance_type == "LOAD"){
    //                     $rr->trance_type = "LOADING";
    //                 }
                    
    //                 $r = new stdClass();
    //                 $r->date = $rr->date;
    //                 $r->description = $rr->description;
    //                 $r->transe_id = $rr->trance_id;
    //                 $r->transe_code = $rr->trance_type;
    //                 $r->qun = number_format($rr->quantity, 2, '.', ',');
    //                 $r->balance = number_format($bal, 2, '.', ',');
                    
    //                 $res[] = $r;
    //             }
                
    //             $date = array("data"=>"Date", "style"=>"text-align: left;", "chalign"=>"text-align: left;");
    //             $des = array("data"=>"Description", "style"=>"text-align: left;", "chalign"=>"text-align: left;");
    //             $tid = array("data"=>"Trance ID", "style"=>"text-align: left;   width:100px", "chalign"=>"text-align: left;");
    //             $tcode = array("data"=>"Trance Code","style"=>"text-align: left;  width:150px", "chalign"=>"text-align: left;");
    //             $qun = array("data"=>"Quantity", "style"=>"text-align: right; width:100px", "chalign"=>"text-align: right;");
    //             $balance= array("data"=>"Balance", "style"=>"text-align: right;  width:100px", "chalign"=>"text-align: right;");
                
    //             $heading = array($date,$des, $tid, $tcode, $qun, $balance);
                
    //             $date = array("data"=>"date", "total"=>false, "format"=>"text");
    //             $des  = array("data"=>"description", "total"=>false, "format"=>"text");
    //             $tid  = array("data"=>"transe_id", "total"=>false, "format"=>"text");
    //             $tcode  = array("data"=>"transe_code", "total"=>false, "format"=>"text");
    //             $qun  = array("data"=>"qun", "total"=>false, "format"=>"text");
    //             $balance  = array("data"=>"balance", "total"=>false, "format"=>"text");
                
    //             $field = array($date,$des,$tid, $tcode, $qun, $balance);
                
    //             $page_rec = 30;
                
    //             $header  = array("data"=>$this->useclass->r_header($que->description." (".$que->code.")"), "style"=>"");
    //             $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
    //             $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
                
    //             $data = array(
    //                               "dbtem"=>$this->useclass->report_style(),
    //                               "data"=>$res,
    //                               "field"=>$field,
    //                               "heading"=>$heading,
    //                               "page_rec"=>$page_rec,
    //                               "height"=>$heigh,
    //                               "width"=>$weight,
    //                               "header"=>30,
    //                               "header_txt"=>$header,
    //                               "footer_txt"=>$footer,
    //                               "page_no"=>$page_no,
    //                               "header_of"=>false
    //                               );
    //             //print_r($data); exit;
    //             $this->load->library('greport', $data);
                
    //             $resu = $this->greport->_print();
    //         }else{
    //             $resu = "<span style='color:red;'>There is no data to load.</span>";
    //         }
    //     }else{
    //         $resu = "<span style='color:red;'>Wrong Item ".$this->sd['item_code'].".</span>";
    //     }
        
    //     return $resu;
    // }
}
?>