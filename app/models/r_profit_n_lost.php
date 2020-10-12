<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_profit_n_lost extends CI_Model {

	private $tb_items;
	private $tb_storse;
	private $tb_department;
	private $sd;
	private $w = 297;
	private $h = 210;

	function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->library('useclass');

		$this->sd = $this->session->all_userdata();
		$this->load->database($this->sd['db'], true);

		$this->tb_items = $this->tables->tb['m_items'];
		$this->tb_storse = $this->tables->tb['m_stores'];
	}

	public function base_details()
	{
		$this->load->model('m_stores');
		$a['store_list']=$this->m_stores->select3();
		$this->load->model('m_branch');
		$a['branch']=$this->get_branch_name();
		return $a;
	}


	public function get_branch_name()
	{
		$this->db->select('name');
		$this->db->where('bc',$this->sd['branch']);
		return $this->db->get('m_branch')->row()->name;
	}


	public function PDF_report($RepTyp="")
	{
		$this->db->select(array('name','address','tp','fax','email'));
		$this->db->where("cl",$this->sd['cl']);
		$this->db->where("bc",$this->sd['branch']);
		$r_detail['branch']=$this->db->get('m_branch')->result();


		$cl=$this->sd['cl'];
		$bc=$this->sd['branch'];

		$r_detail['store_code']=$_POST['stores'];	
		$r_detail['type']=$_POST['type'];        
		$r_detail['dd']=$_POST['dd'];
		$r_detail['qno']=$_POST['qno'];

		$r_detail['page']=$_POST['page'];
		$r_detail['header']=$_POST['header'];
		$r_detail['orientation']=$_POST['orientation'];
		$r_detail['dfrom']=$_POST['from'];
		$r_detail['dto']=$_POST['to'];
		$r_detail['trans_code']=$_POST['t_type'];
		$r_detail['trans_code_des']=$_POST['t_type_des'];
		$r_detail['trans_no_from']=$_POST['t_range_from'];
		$r_detail['trans_no_to']=$_POST['t_range_to'];
		$cluster=$_POST['cluster'];
		$branch=$_POST['branch'];

		if(!empty($cluster))
		{
			$cl="And cl = '$cluster'";
		}else{
			$cl="";
		}
		if(!empty($branch))
		{
			$bc="And bc = '$branch'";
		}else{
			$bc="";
		} 

		$sql="	SELECT  CASE mat.rtype WHEN 1 THEN 'INCOME' WHEN 2 THEN 'EXPENSES' END AS acc_type , mat.heading, ma.code, ma.description, act.balance 
		,'300' AS myOrder
		FROM m_account ma
		JOIN m_account_type mat ON mat.`code`=ma.category 
		JOIN	(SELECT acc_code, SUM(cr_amount)-SUM(dr_amount)  AS balance  FROM t_account_trans
		WHERE (ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."')  $cl $bc
		AND acc_code IN (SELECT acc_code FROM `m_default_account` WHERE (CODE IN('CREDIT_SALES','CASH_SALES','REV_INTERNAL_SALE'))) GROUP BY acc_code) act ON act.acc_code=ma.`code`
		AND (mat.`report`=2) AND (ma.`is_control_acc`=0) and mat.rtype=1

		union all

		SELECT  CASE mat.rtype WHEN 1 THEN 'INCOME' WHEN 2 THEN 'EXPENSES' END AS acc_type , mat.heading, ma.code, ma.description, act.balance 
		,'400' AS myOrder
		FROM m_account ma
		JOIN m_account_type mat ON mat.`code`=ma.category 
		JOIN	(SELECT acc_code, SUM(cr_amount)-SUM(dr_amount)  AS balance  FROM t_account_trans
		WHERE (ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."')  $cl $bc
		AND acc_code NOT IN (SELECT acc_code FROM `m_default_account` WHERE (CODE IN('COST_OF_SALES','PURCHASE','CREDIT_SALES','CASH_SALES','REV_INTERNAL_SALE'))) GROUP BY acc_code) act ON act.acc_code=ma.`code`
		AND (mat.`report`=2) AND (ma.`is_control_acc`=0) and mat.rtype=1

		union all

		SELECT  CASE mat.rtype WHEN 1 THEN 'INCOME' WHEN 2 THEN 'EXPENSES' END AS acc_type , mat.heading, ma.code, ma.description, act.balance 
		,'500' AS myOrder
		FROM m_account ma
		JOIN m_account_type mat ON mat.`code`=ma.category 
		JOIN	(SELECT acc_code, SUM(dr_amount)-SUM(cr_amount)  AS balance  FROM t_account_trans
		WHERE (ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."')  $cl $bc
		AND acc_code NOT IN (SELECT acc_code FROM `m_default_account` WHERE (CODE IN('COST_OF_SALES','PURCHASE'))) GROUP BY acc_code) act ON act.acc_code=ma.`code`
		AND (mat.`report`=2) AND (ma.`is_control_acc`=0) and mat.rtype=2					 

		UNION ALL 
		SELECT  'INCOME' AS acc_type ,'Cost of Sales' AS heading , '500000003' AS `Code`,'Opening Stock' AS Description, 
		IFNULL( SUM( (IFNULL(i.qty,0) * e.purchase_price)),0) AS balance ,'7001' AS myOrder
		FROM `m_item` `m` LEFT JOIN (SELECT item, batch_no, cost, store_code, 
		SUM(qty_in) - SUM(qty_out) AS qty, MIN(ddate) AS p_date 
		FROM t_item_movement WHERE ddate <'".$_POST['from']."' $cl $bc
		GROUP BY item, batch_no, store_code) i ON ((i.`item` = m.`code`)) 
		JOIN (SELECT item,batch_no,purchase_price FROM t_item_batch GROUP BY item,batch_no,purchase_price) e ON e.item = m.code 
		AND e.batch_no=i.batch_no 
		WHERE IFNULL(i.qty,0)>0 
		UNION ALL		
		SELECT  'INCOME' AS acc_type ,'Cost of Sales' AS heading , '500000002' AS `Code`,'Purchase' AS Description, 
		IFNULL( SUM( (IFNULL(i.qty,0) * e.purchase_price)),0) AS balance ,'702' AS myOrder
		FROM `m_item` `m` LEFT JOIN (SELECT item, batch_no, cost, store_code, 
		SUM(qty_in)  AS qty, MIN(ddate) AS p_date 
		FROM t_item_movement WHERE ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'  $cl $bc
		AND `trans_code` IN ('3','121')
		GROUP BY item, batch_no, store_code) i ON ((i.`item` = m.`code`)) 
		JOIN (SELECT item,batch_no,purchase_price FROM t_item_batch GROUP BY item,batch_no,purchase_price) e ON e.item = m.code 
		AND e.batch_no=i.batch_no 
		WHERE IFNULL(i.qty,0)>0 			
		UNION ALL		
		SELECT  'INCOME' AS acc_type ,'Cost of Sales' AS heading , '500000004' AS `Code`,'Closing Stock' AS Description, 
		IFNULL( SUM( (IFNULL(i.qty,0) * e.purchase_price)),0)*-1 AS balance ,'704' AS myOrder
		FROM `m_item` `m` LEFT JOIN (SELECT item, batch_no, cost, store_code, 
		SUM(qty_in) - SUM(qty_out) AS qty, MIN(ddate) AS p_date 
		FROM t_item_movement WHERE ddate <='".$_POST['to']."' $cl $bc
		GROUP BY item, batch_no, store_code) i ON ((i.`item` = m.`code`)) 
		JOIN (SELECT item,batch_no,purchase_price FROM t_item_batch GROUP BY item,batch_no,purchase_price) e ON e.item = m.code 
		AND e.batch_no=i.batch_no 
		WHERE IFNULL(i.qty,0)>0 ";

// ,'DISCOUNT_ON_PURCHASE' Removed from 2,3 union all block

// Remove to get Stock value from Item Movement
/*
					SELECT  'INCOME' AS acc_type ,'Cost of Sales' AS heading , '500000003' AS `Code`,'Opening Stock' AS Description, (SUM(cr_amount)-SUM(dr_amount))*-1   AS balance  
						,'7001' AS myOrder
						FROM t_account_trans
						WHERE (ddate < '".$_POST['from']."') $cl $bc
						AND acc_code IN (SELECT acc_code FROM `m_default_account` WHERE (CODE ='STOCK_ACC'))
						GROUP BY acc_code

				UNION ALL
				
					SELECT 'INCOME' AS acc_type ,'Cost of Sales' AS heading , '500000002' AS `Code`,'Purchase' AS Description,(SUM(cr_amount)-SUM(dr_amount))*-1  AS balance  
						,'702' AS myOrder
						FROM t_account_trans
						WHERE ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' $cl $bc
						AND acc_code IN (SELECT acc_code FROM `m_default_account` WHERE (CODE ='PURCHASE'))
						GROUP BY acc_code		
				UNION ALL
					SELECT 'INCOME' AS acc_type ,'Cost of Sales' AS heading , '500000002' AS `Code`,'Discount on Purchase' AS Description,(SUM(dr_amount)-SUM(cr_amount))*-1  AS balance  
						,'703' AS myOrder
						FROM t_account_trans
						WHERE ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' $cl $bc
						AND acc_code IN (SELECT acc_code FROM `m_default_account` WHERE (CODE ='DISCOUNT_ON_PURCHASE'))
						GROUP BY acc_code								
				UNION ALL
					SELECT 'INCOME' AS acc_type ,'Cost of Sales' AS heading , '500000004' AS `Code`,'Closing Stock' AS Description, (SUM(cr_amount)-SUM(dr_amount))  AS balance  
						,'704' AS myOrder
						FROM t_account_trans
						WHERE (ddate <= '".$_POST['to']."')  $cl $bc
						AND acc_code IN (SELECT acc_code FROM `m_default_account` WHERE (CODE ='STOCK_ACC'))
						GROUP BY acc_code			
					ORDER BY  heading,myOrder ,acc_type  
*/

/*
		$sql="SELECT  CASE mat.rtype WHEN 1 THEN 'INCOME' WHEN 2 THEN 'EXPENSES' END AS acc_type
					, mat.heading
					, ma.code
					, ma.description
					, act.balance FROM m_account ma
					JOIN m_account_type mat ON mat.`code`=ma.category
					JOIN	(
						SELECT acc_code, SUM(cr_amount)-SUM(dr_amount)  AS balance  FROM t_account_trans
						WHERE (ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."') $cl $bc
						AND acc_code NOT IN (SELECT acc_code FROM `m_default_account` WHERE (CODE ='COST_OF_SALES'))
						GROUP BY acc_code
					UNION ALL
						SELECT '500000003' AS acc_code, SUM(dr_amount)-SUM(cr_amount)  AS balance  FROM t_account_trans
						WHERE (ddate < '".$_POST['from']."') $cl $bc
						AND acc_code IN (SELECT acc_code FROM `m_default_account` WHERE (CODE ='COST_OF_SALES'))
						GROUP BY acc_code		
					UNION ALL
						SELECT '500000002' AS acc_code, SUM(dr_amount)-SUM(cr_amount)  AS balance  FROM t_account_trans
						WHERE ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'  $cl $bc
						AND acc_code IN (SELECT acc_code FROM `m_default_account` WHERE (CODE ='COST_OF_SALES'))
						GROUP BY acc_code		
					UNION ALL
						SELECT '500000004' AS acc_code, SUM(cr_amount)-SUM(dr_amount)  AS balance  FROM t_account_trans
						WHERE (ddate < '".$_POST['to']."') $cl $bc
						AND acc_code IN (SELECT acc_code FROM `m_default_account` WHERE (CODE ='COST_OF_SALES'))
						GROUP BY acc_code			
					) act ON act.acc_code=ma.`code`
					AND (mat.`report`=2) AND (ma.`is_control_acc`=0) order by mat.heading ";
*/	

					$r_detail['pr_lo']=$this->db->query($sql)->result();	

					if($this->db->query($sql)->num_rows()>0)
					{
						$exTy=($RepTyp=="")?'pdf':'excel';
						$this->load->view($_POST['by'].'_'.$exTy,$r_detail);

					}
					else
					{
						echo "<script>alert('No Data');window.close();</script>";
					}
				}

				public function Excel_report()
				{
					$this->PDF_report("Excel");
				}
			}	
			?>