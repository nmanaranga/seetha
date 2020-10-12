<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_stock extends CI_Model {
    
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
	
        $this->tb_items = $this->tables->tb['m_items'];
        $this->tb_main_cat = $this->tables->tb['m_main_cat'];
        $this->tb_sub_cat = $this->tables->tb['m_sub_cat'];
    }
    
    public function base_details(){
        // $this->load->model('m_stores');
        
        // $a['report'] = $this->report();
        // $a['stores'] = $this->m_stores->select("des");
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
        
//         if(! isset($this->sd['stores'])){ $this->sd['stores'] = 0; }
//         if(! isset($this->sd['type'])){ $this->sd['type'] = "sum"; }
        
//         if($this->sd['stores'] != "0"){
//             $where = "AND `stores` = '".$this->sd['stores']."'";
//         }else{
//             $where = "";
//         }
        
//         if($this->sd['from'] != "0"){
            
//              $date=" WHERE  tim.date <= '".$this->sd['from']."' ";
//         }
//         else{
// 	    $date="";
// 	}
        
// //        $sql = "SELECT
// //                    `m_main_category`.`description` AS main_cat,
// //                    `m_sub_category`.`description`  AS sub_cat,
// //                    `m_items`.`code`,
// //                    `m_items`.`description` AS item_name,
// //                    IFNULL(`qun`.`pur_price`,0) AS `price`,
// //                    IFNULL(`qun`.`qun`, 0) AS `qun`,
// //                    IFNULL((`qun`.`qun` * `qun`.`pur_price`), 0) AS `value` 
// //                  FROM (`m_items`)
// //                    INNER JOIN `m_main_category`
// //                      ON `m_main_category`.`code` = `m_items`.`main_cat`
// //                    LEFT OUTER JOIN `m_sub_category`
// //                      ON `m_sub_category`.`code` = `m_items`.`sub_cat`
// //                    LEFT OUTER JOIN (SELECT
// //                                       (SUM(in_quantity) - SUM(out_quantity)) AS qun,
// //                                       item_code,
// //                                        `pur_price` 
// //                                     FROM t_item_movement WHERE `is_subitem`=0 ".$where." GROUP BY item_code,batch_no) AS qun
// //                      ON (qun.item_code = m_items.code)";
        
//         $sql="SELECT 
//                       mmc.`code` AS main_cat_code,
//                       mmc.`description` AS main_cat,
//                       msc.`code` AS sub_cat_code,
//                       msc.`description` AS sub_cat,
//                       tim.item_code AS code,
//                       mi.`description` AS item_name,
//                       SUM(`Qty`) AS qun,
//                       SUM(`value`) AS `value`,
//                       stores,
//                       `date` 
//                     FROM
//                       (SELECT 
//                         trance_type,
//                         trance_id,
//                         item_code,
//                         in_quantity AS Qty,
//                         SUM(in_quantity * pur_price) AS `value`,
//                         `stores`,
//                         `date` 
//                       FROM
//                         t_item_movement 
//                       WHERE in_quantity > 0 AND t_item_movement.is_subitem='0'
//                       GROUP BY trance_id,
//                         item_code,
//                         `batch_no`,
//                         `date` 
//                       UNION
//                       ALL 
//                       SELECT 
//                         trance_type,
//                         trance_id,
//                         item_code,
//                         (out_quantity * - 1) AS Qty,
//                         (SUM(out_quantity * pur_price) * - 1) AS VALUE,
//                         `stores`,
//                         `date` 
//                       FROM
//                         t_item_movement 
//                       WHERE out_quantity > 0 AND t_item_movement.is_subitem='0'
//                       GROUP BY trance_id,
//                         item_code,
//                         `batch_no`,
//                         `date`) tim 
//                       INNER JOIN `m_items` mi 
//                         ON tim.item_code = mi.`code` 
//                       INNER JOIN `m_main_category` mmc 
//                         ON mi.`main_cat` = mmc.`code` 
//                       INNER JOIN `m_sub_category` msc 
//                         ON mi.`sub_cat` = msc.`code` 
//                     ".$where.$date." 
//                     GROUP BY tim.item_code 
//                     ORDER BY tim.item_code "; 
        
        
//         //echo $sql;exit;
        
//         $query = $this->db->query($sql);
        
//         if($query->num_rows){
//             $result = $query->result();
            
            
//            // if($this->sd['type'] == "det"){
//                 $st = 0;
// 		$t= 0;
// 		$res = array();
// 		$cat = '';
// 		$cat2 = '';
		
// 		              foreach($result as $r){
//                     //$t += $r->value;
                    
//                     //******************************//
//                     if ($cat != $r->main_cat||$cat2 != $r->sub_cat) {

//                     if ($cat != ''|| $cat2 !='') {
//                         $rec = new stdClass;
//                         $rec->main_cat = "";
//                         $rec->sub_cat = "";
//                         $rec->code = "";
//                         $rec->item_name = "";
//                         //$rec->price = "";
//                         $rec->qun = array("data" => "Sub Total", "style" => "font-weight: bold; text-align: right;");
//                         $rec->value = array("data"=>number_format($t, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
			
// 			$t = 0;
//                         $res[] = $rec;
//                     }
//                     $rec = new stdClass;
//                     $rec->main_cat = $r->main_cat;
//                     $rec->sub_cat = $r->sub_cat;
//                     $rec->code = "";
//                     $rec->item_name = "";
// 		    //$rec->price = '';
//                     $rec->qun = '';
//                     $rec->value = array("data" => 0, "style" => "color:#F0FFFF; text-align: right;");
//                     $res[] = $rec;
//                 }

//                 $cat = $r->main_cat;
// 		$cat2 = $r->sub_cat;
//                 $t += $r->value;
//                 $rec = new stdClass;
//                 $rec->main_cat = "";
//                 $rec->sub_cat = "";
//                 $rec->code = $r->code;
//                 $rec->item_name = $r->item_name;
//                 //$rec->price = $r->price;
// 		$rec->qun = $r->qun;
//                 $rec->value = number_format($r->value,2, '.', ',');
		
//                 $res[] = $rec;
//                 $st += $r->value;
//                     //******************************//
//                 }
//                 $rec = new stdClass;
// 		$rec->main_cat = "";
// 		$rec->sub_cat = "";
// 		$rec->code = "";
// 		$rec->item_name = "";
// 		//$rec->price = "";
// 		$rec->qun = array("data" => "Sub Total", "style" => "font-weight: bold; text-align: right;");
// 		$rec->value = array("data"=>number_format($t, 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
// 		$res[] = $rec;

                

// 		$rec = new stdClass;
// 		$rec->main_cat = "";
// 		$rec->sub_cat = "";
// 		$rec->code = "";
// 		$rec->item_name = "";
// 		//$rec->price = "";
// 		$rec->qun = array("data" => "Total", "style" => "font-weight: bold; text-align: right;");
// 		$rec->value = array("data"=>number_format(($st), 2, '.', ','), "style" => "font-weight: bold; text-align: right; border-bottom: 2px solid #000;");
// 		$res[] = $rec;
                       
            
//                 $name = array("data"=>"Main Category", "style"=>"text-align: left; ", "chalign"=>"text-align: left;width:50px");
//                 $sub = array("data"=>"Sub Category", "style"=>"text-align: left; ", "chalign"=>"text-align: left; width:100px");
//                 $code = array("data"=>"Code","style"=>"text-align: left; ", "chalign"=>"text-align: left; width:60px");
//                 $item = array("data"=>"Item", "style"=>"text-align: left; ", "chalign"=>"text-align: left;");
//                 $pri = array("data"=>"Price", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:40px");
// 		$qun = array("data"=>"Qty", "style"=>"text-align: right; ", "chalign"=>"text-align: right; width:40px");
//                 $value = array("data"=>"Value", "style"=>"text-align: right;", "chalign"=>"text-align: right; width:40px");
                
//                 $heading = array($name,$sub, $code, $item, $qun,$value);
                
//                 $inv_no = array("data"=>"main_cat", "total"=>false, "format"=>"text");
// 		$job_no  = array("data"=>"sub_cat", "total"=>false, "format"=>"text");
// 		$vehi  = array("data"=>"code", "total"=>false, "format"=>"text");
// 		$cus  = array("data"=>"item_name", "total"=>false, "format"=>"text");
// 		$total  = array("data"=>"price", "total"=>false, "format"=>"text");
// 		$sale  = array("data"=>"qun", "total"=>false, "format"=>"text");
// 		$value  = array("data"=>"value", "total"=>false, "format"=>"text");
                
//                 $field = array($inv_no, $job_no, $vehi, $cus, $sale,$value);
	    
	    
	    
// 	   // }
	    
	    
//             $page_rec = 18;
            
//             //if($this->sd['type'] == "det"){
//             //    $name = "Price List";
//             //}else{
//             //    $name = "Item List";
//             //}
//             $name="Stock Report(Valuation)";
	    
//             $header  = array("data"=>$this->useclass->r_header($name), "style"=>"");
//             $footer  = array("data"=>"<hr />Report generate on - ".date("Y-m-d H:i:s"), "style"=>"font-size: 11px; font-family: Courier;");
//             $page_no = array("style"=>"font-size: 11px; font-family: Courier;", "vertical"=>"buttom", "horizontal"=>"right");
            
//             $data = array(
//                               "dbtem"=>$this->useclass->report_style(),
//                               "data"=>$res,
//                               "field"=>$field,
//                               "heading"=>$heading,
//                               "page_rec"=>$page_rec,
//                               "height"=>$this->h,
//                               "width"=>$this->w,
//                               "header"=>30,
//                               "header_txt"=>$header,
//                               "footer_txt"=>$footer,
//                               "page_no"=>$page_no,
//                               "header_of"=>true
//                               );
//             //print_r($data); exit;
//             $this->load->library('greport', $data);
            
//             $resu = $this->greport->_print();
//         }else{
//             $resu = "<span style='color:red;'>There is no data to load.</span>";
//         }
        
//         return $resu;
//     }
}
?>