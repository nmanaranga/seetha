<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
class reports extends CI_Controller {
	
    private $session_data;
    
	function __construct(){
		parent::__construct();
                $this->load->helper("url");
		$this->load->helper("form");
		$this->load->database();
		$this->load->library('session');
                $this->session_data = $this->session->all_userdata();
		$this->load->library('pdf');
	}



	function generate(){

        $this->load->model($_POST['by']);
		$this->{$_POST['by']}->PDF_report();

    }
	
// 	function generate(){
            
//     //echo "vcfcg".$_POST['type']." ";
            
// 		$usrnfo=" SELECT `name`,`address01`,`address02`,`address03`,`phone01`,`phone02`,`phone03` FROM `s_company`";
//                 //echo $usrnfo;exit;
                
// 		$usrfo=$this->db->query($usrnfo);
// 		$r_detail['info']=$usrfo->result();
                

//         if($_POST['by']=="r_stock"){        
 
            
//             if(! isset($this->sd['from'])){ $this->sd['from'] = date("Y-m-d");}

// 	    if(! isset($this->sd['wd'])){ $this->sd['wd'] = 1;}
            
// 	    $r_detail['r_type']="r_stock";    
	    
//             if($_POST['val']!='sum')
//             {
// 	    $r_detail['type']=$_POST['format'];
//             }
//             else {
//             $r_detail['type']='111';    
//             }

// 	    $r_detail['page']=$_POST['page'];
	    
// 	    $r_detail['header']=$_POST['headr'];
	    
// 	    $r_detail['orientation']=$_POST['orientation'];
	    
// 	    $r_detail['from']= $_POST['from_date'];
            
//             $r_detail['main_cat']=$_POST['main_cat'];
            
//             $r_detail['val']=$_POST['val'];
            
//             $r_detail['filter']='All';
            

           
	    
// 	    if(! isset($_POST['stores'])){ $_POST['stores'] = 0; }
// 	    if(! isset($_POST['cat'])){ $_POST['cat'] = 0; }
// 	    if(! isset($_POST['val'])){ $_POST['val'] = "det"; }

            
// 	if($this->sd['from'] != "0"){

// 		$date=" WHERE `date` <'".$_POST['from_date']."' ";
		
// 		//if($_POST['stores'] != "0"){
// 		$where = "AND `stores` = '".$_POST['stores']."'";
		
//                 if($_POST['stores'] != "0"){
//                 $r_detail['filter']="Store-".$_POST['stores'];
//                 }
                
                
//                 if($_POST['cat'] != "0"){
//                 $where2 = " AND `m_items`.`main_cat` = '".$_POST['cat']."'";
//                 }else{
//                     $where2 = "";
//                 }
// 	      // }
	    
// 	    //else{
// 		$where = "";
// 		    if($_POST['main_cat'] != '0'){
// 		    $where2 = "AND `m_items`.`main_cat` = '".$_POST['main_cat']."'";
                    
//                     $r_detail['filter'].="/ Main Category-".$_POST['main_cat'];
// 		}else{
// 		    $where2 = "";
// 		}
                
               
                
// 	    }
// 	//}
	
// 	else{
// 	    $date="";
// 	}

//         $qry = "SELECT
//                     `m_main_category`.`description` AS main_cat,
//                     `m_sub_category`.`description`  AS sub_cat,
//                     `m_items`.`code`,
//                     `m_items`.`description`         AS item_name,
//                     `purchase_price`                AS `price`,
//                     IFNULL(`qun`.`qun`, 0) AS `qun`,
//                     IFNULL((`qun`.`qun` * `purchase_price`), 0) AS `value`
//                   FROM (`m_items`)
//                     INNER JOIN `m_main_category`
//                       ON `m_main_category`.`code` = `m_items`.`main_cat`
//                     INNER JOIN `m_sub_category`
//                       ON `m_sub_category`.`code` = `m_items`.`sub_cat`
//                     INNER JOIN (SELECT
//                                        (SUM(in_quantity) - SUM(out_quantity)) AS qun,
//                                        item_code,stores,date
//                                      FROM t_item_movement  INNER JOIN m_items ON m_items.`code`=t_item_movement.`item_code` ".$where.$date." GROUP BY item_code) AS qun
//                       ON (qun.item_code = m_items.code)
// 		      ".$where2." ";
//        //echo $qry;exit;
	 
	 
// 	    $data=$this->db->query($qry);		
// 		if($data->num_rows()>0){
// 			$r_detail['r_data']=$data->result();
// 			//print_r($r_detail);exit;
                        
// 				$this->load->view('gen_report',$r_detail);
                                
                               
		
// 		}else{
		    
		    
// 			echo "<script>alert('No data found in ".$year.'-'.$month." ');close();</script>";
// 		}
//         }
         
        
//         if($_POST['by']=="r_bin_card"){
	    
// 	    $r_detail['r_type']="r_bin_card";    
	    
// 	    $r_detail['type']=$_POST['format'];
	    
// 	    $r_detail['page']=$_POST['page'];
	    
// 	    $r_detail['header']=$_POST['headr'];
	    
// 	    $r_detail['orientation']=$_POST['orientation'];
	    
// 	    $r_detail['from']= $_POST['from_date'];
	    
// 	    $r_detail['to']= $_POST['to_date'];
	    
// 	    $r_detail['item']= $_POST['item_code'];
	    
// 	    $r_detail['itemid']= $_POST['item_id'];
// 	    //print_r($r_detail);exit;
 
 
// 	    if($_POST['stores'] != "0"){
// 		$where = "AND `stores` = '".$_POST['stores']."'";
// 	    }else{
// 		$where = "";
// 	    }
	    
// 	     if($this->sd['sort'] != "0"){
// 		$group = " ORDER BY '".$this->sd['sort']."' ";
// 	    }
// 	    else
// 	    {
// 		$group="";
// 	    }    
	    
	    
// 	    $sql = "SELECT
// 			'0' AS trance_id,
// 			'Before ".$_POST['from_date']."' AS trance_type,
// 			'' AS date,
// 			(SUM(in_quantity) - SUM(out_quantity)) AS quantity,
//                         IFNULL(description,'') AS description 
// 		      FROM t_item_movement
// 		      WHERE item_code = '".$_POST['item_id']."' ".$where." 
// 			  AND `date` < '".$_POST['from_date']."'UNION SELECT
// 							   trance_id,
// 							   trance_type,
// 							   date,
// 							   (SUM(in_quantity) - SUM(out_quantity)) AS quantity,
//                                                            IFNULL(description,'') AS description 
// 							 FROM t_item_movement
// 							 WHERE item_code = '".$_POST['item_id']."' ".$where." 
// 							     AND `date` BETWEEN '".$_POST['from_date']."'
// 							     AND '".$_POST['to_date']."'
// 			    GROUP BY trance_id, trance_type $group";
// 	    //echo $sql;exit;
	    
// 	    $query = $this->db->query($sql);
// 	    $r_detail['r_res']=$query->result();
	    
// 	    if($query->num_rows()>0){
// 			$r_detail['r_data']=$query->result();
// 			//print_r($r_detail);exit;
                        
// 			     $result = $query->result();
// 		    $bal = 0;
// 		    foreach($result as $rr){
// 			$bal += $rr->quantity;
// 			$r_detail['balance']=$bal;
			
// 			if($rr->trance_type == "SALES"){
// 			    $r_detail['trance']="SALES";
// 			}elseif($rr->trance_type == "OPEN"){
// 			    $r_detail['trance']="OPEN RECORD";
// 			}elseif($rr->trance_type == "PUR_RET"){
// 			    $r_detail['trance']="PURCHASE RETURN";
// 			}elseif($rr->trance_type == "PUR"){
// 			    $r_detail['trance']="PURCHASE";
// 			}elseif($rr->trance_type == "SALES_RET"){
// 			    $r_detail['trance']= "SALES RETURN";
// 			}elseif($rr->trance_type == "STOCK_ADJ"){
// 			    $r_detail['trance']="STOCK ADJUSTMENT";
// 			}elseif($rr->trance_type == "DMG_FREE_ISSU"){
// 			    $r_detail['trance']="DAMAGE OR FREE ISSUE";
// 			}elseif($rr->trance_type == "TRANS"){
// 			    $r_detail['trance']="TRANSFER";
// 			}elseif($rr->trance_type == "LOAD"){
// 			    $r_detail['trance']="LOADING";
// 			}
// 		    }
			
// 				$this->load->view('gen_report',$r_detail);
                                
                               
		
// 		}
		
// 		else{
		    
		    
// 			echo "<script>alert('No data found ');close();</script>";
// 		}
        
	
// 	}
	
// 	if($_POST['by']=="r_stock_sum"){
 

            
// 	    $r_detail['r_type']="r_stock_sum";
	    
	    
            
// 	    $r_detail['sys']=$_POST['sys'];

//                 $r_detail['type']=$_POST['format'];
          
            
            
// 	    $r_detail['page']=$_POST['page'];
	    
// 	    $r_detail['header']=$_POST['headr'];
	    
// 	    $r_detail['orientation']=$_POST['orientation'];
	    
// 	    $r_detail['from']= $_POST['from_date'];
            
//             $r_detail['filter']='All';
	    
 
// 	/*if($_POST['from_date'] != "0" || $_POST['from_date']!=" "){
	    
// 	    $date=" WHERE `date`< '".$_POST['from_date']."' ";
	    
           
            
            
// 	   if($_POST['stores'] != "0" || $_POST['stores'] !=""){
                
//             $where = "AND `stores` = '".$_POST['stores']."'";
            
// 	    $r_detail['filter']="Store-".$_POST['stores'];
//            }
           
           
// 		if($_POST['main_cat'] != "0"){
// 		$where2 = " AND `m_items`.`main_cat` = '".$_POST['main_cat']."'";
// 		}else{
// 		    $where2 = "";
// 		}
                
//                 //if($_POST['stores'] != "0"){
                
//                // }
// 	   // }
	    
// 	   // else{
// 		$where = "";
		
// 		    if($_POST['main_cat'] != "0"){
// 		    $where2 = "AND `m_items`.`main_cat` = '".$_POST['main_cat']."'";
//                     $r_detail['filter'].="/ Main Category-".$_POST['main_cat'];
// 		}else{
// 		    $where2 = "";
// 		}
// 	    //}
// 	}
	
// 	else{
// 	    $date="";
// 	}*/
        
// 	if($_POST['from_date'] != "0"){
	    
// 	    $date=" WHERE `date`< '".$_POST['from_date']."' ";
	    
// 	    if($_POST['stores'] != "0"){
//             $where = "AND `stores` = '".$_POST['stores']."'";
//             $r_detail['filter']="Store-".$_POST['stores'];
	    
// 		if($_POST['main_cat'] != "0"){
// 		$where2 = " AND `m_items`.`main_cat` = '".$_POST['main_cat']."'";
//                 $r_detail['filter'].="/ Main Category-".$_POST['main_cat'];
// 		}else{
// 		    $where2 = "";
// 		}
// 	    }
	    
// 	    else{
// 		$where = "";
		
// 		    if($_POST['main_cat'] != "0"){
// 		    $where2 = "AND `m_items`.`main_cat` = '".$_POST['main_cat']."'";
//                     $r_detail['filter'].="/ Main Category-".$_POST['main_cat'];
// 		}else{
// 		    $where2 = "";
// 		}
// 	    }
// 	}
	
// 	else{
// 	    $date="";
// 	}
            
            
	
//         $qry = "SELECT
//                     `m_main_category`.`description` AS main_cat,
//                     `m_sub_category`.`description`  AS sub_cat,
//                     `m_items`.`code`,
//                     `m_items`.`description`         AS item_name,
//                     `purchase_price`                AS `price`,
//                     IFNULL(`qun`.`qun`, 0) AS `qun`,
//                     IFNULL((`qun`.`qun` * `purchase_price`), 0) AS `value`
//                   FROM (`m_items`)
//                     INNER JOIN `m_main_category`
//                       ON `m_main_category`.`code` = `m_items`.`main_cat`
//                     LEFT OUTER JOIN `m_sub_category`
//                       ON `m_sub_category`.`code` = `m_items`.`sub_cat`
//                     LEFT OUTER JOIN (SELECT
//                                        (SUM(in_quantity) - SUM(out_quantity)) AS qun,
//                                        item_code,stores,date
//                                      FROM t_item_movement  INNER JOIN m_items ON m_items.`code`=t_item_movement.`item_code` ".$where.$date." 
//                                          GROUP BY item_code) AS qun
//                       ON (qun.item_code = m_items.code)
// 		      ".$where2." ";
		      
// //echo $qry;exit;
        
        
// 	$data=$this->db->query($qry);		
// 		if($data->num_rows()>0){
// 			$r_detail['r_data']=$data->result();
// 			//print_r($r_detail);exit;
                        
// 				$this->load->view('gen_report',$r_detail);
                                
                               
		
// 		}else{
		    
		    
// 			echo "<script>alert('No data found ');close();</script>";
// 		}
	
	
	
// 	}
	
// 	if($_POST['by']=="r_stock_qty"){
	    
// 	   $r_detail['r_type']="r_stock_qty";
	    
// 	    $r_detail['type']=$_POST['format'];
		
// 	    $r_detail['page']=$_POST['page'];
	    
// 	    $r_detail['header']=$_POST['headr'];
	    
// 	    $r_detail['orientation']=$_POST['orientation'];
	    
// 	    $r_detail['from']= $_POST['from_date'];
	    
// 	    //print_r($r_detail);exit;
// 	    $qry = "SELECT
//                 `t_sales_order_sum`.`no`
//               , `t_sales_order_det`.`item_code`
//               , `m_items`.`description`
//               , `t_sales_order_det`.`quantity`
//               , `t_sales_order_det`.`original_qty`

// 	      FROM
//               `t_sales_order_det`
//               INNER JOIN `t_sales_order_sum` 
//                   ON (`t_sales_order_det`.`id` = `t_sales_order_sum`.`id`)
//               INNER JOIN `m_items` 
//                   ON (`t_sales_order_det`.`item_code` = `m_items`.`code`)
//                   WHERE `date` BETWEEN '".$_POST['from_date']."' AND '".$_POST['to_date']."'";
// 	    //echo $qry;exit;
// 		$data=$this->db->query($qry);		
// 		if($data->num_rows()>0){
// 			$r_detail['r_data']=$data->result();
// 			//print_r($r_detail);exit;
                        
// 				$this->load->view('gen_report',$r_detail);
                                
                               
		
// 		}else{
		    
		    
// 			echo "<script>alert('No data found ');close();</script>";
// 		}
	    
	    
// 	}
	
//        if($_POST['by']=="r_cost_sales"){

//             $r_detail['r_type']="r_cost_sales";    
	    
// 	    $r_detail['type']=$_POST['format'];
	    
// 	    $r_detail['page']=$_POST['page'];
	    
// 	    $r_detail['header']=$_POST['headr'];
	    
// 	    $r_detail['orientation']=$_POST['orientation'];
	    
// 	    $r_detail['from']= $_POST['from_date'];
	    
// 	    $r_detail['to']= $_POST['to_date'];   
           
          
//             $qry="SELECT
//       `t_item_movement`.`sales_no`
//     , `t_item_movement`.`item_code`
//     , `m_items`.`description`
//     , `t_item_movement`.`out_quantity`
//     ,  `t_sales_det`.`cost` as sal_price
//     ,  (`t_item_movement`.`out_quantity`* `t_sales_det`.`cost`) AS tot
//     , `t_item_movement`.`avg_price`
//     ,  (`t_item_movement`.`out_quantity`* `t_item_movement`.`avg_price`) AS cost
//     ,  `t_sales_sum`.`discount`
// FROM
//    `t_item_movement`
//     INNER JOIN `m_items` 
//         ON (`t_item_movement`.`item_code` = `m_items`.`code`)
//     INNER JOIN `t_sales_sum` ON `t_sales_sum`.`no`= `t_item_movement`.`sales_no` 
//     INNER JOIN  `t_sales_det` ON `t_sales_det`.`id`=`t_sales_sum`.`id` AND `t_sales_det`.`item_code`= t_item_movement.`item_code`
//           WHERE (`trance_type`='SALES_ORDER' OR `trance_type`='SALES') 
//           AND `t_item_movement`.`date` BETWEEN  '".$_POST['from_date']."' AND '".$_POST['to_date']."'
//           ORDER BY `t_item_movement`.`sales_no`";
           
 
            
//             $data=$this->db->query($qry);		
//             if($data->num_rows()>0){
//                 $r_detail['r_data']=$data->result();
//                 //print_r($r_detail);exit;

//                         $this->load->view('gen_report',$r_detail);



//             }else{


//                 echo "<script>alert('No data found ');close();</script>";
//             }  
            
            
//        }
        
        

		
//     }
	
	
	
}