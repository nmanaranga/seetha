<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_trading_report extends CI_Model {
    
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


	public function PDF_report()
	{
		 //-----------------------------------------------------------------------------------------------------------

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
        $r_detail['orientation']="L";
        $r_detail['dfrom']=$_POST['from'];
        $r_detail['dto']=$_POST['to'];
        $r_detail['trans_code']=$_POST['t_type'];
        $r_detail['trans_code_des']=$_POST['t_type_des'];
        $r_detail['trans_no_from']=$_POST['t_range_from'];
        $r_detail['trans_no_to']=$_POST['t_range_to'];
        $cluster=$_POST['cluster'];
        $branch=$_POST['branch'];

        if(!empty($cluster)){
            $cluster = " AND s.cl = '".$_POST['cluster']."' ";
        }else{
            $cluster = "";
        }

        if(!empty($branch)){
            $branch = " AND s.bc = '".$_POST['branch']."' ";
        }else{
            $branch = "";
        }

        if(!empty($cluster)){
            $cluster2 = " AND m.cl = '".$_POST['cluster']."' ";
        }else{
            $cluster2 = "";
        }

        if(!empty($branch)){
            $branch2 = " AND m.bc = '".$_POST['branch']."' ";
        }else{
            $branch2 = "";
        }

        $sql=" SELECT  d.`code` ,
                       d.`description`,
                       IFNULL(c.cAmount,0) AS CashSales,
                       IFNULL(c.discount_total,0) AS CashDis,
                       (IFNULL(c.cAmount,0) - IFNULL(c.discount_total,0)) AS TotCash,
                       IFNULL(cr.crAmount,0) AS CreditSales,
                       IFNULL( Cr.discount_total,0) AS CreditDis,
                       (IFNULL(cr.crAmount,0) - IFNULL(Cr.discount_total,0)) AS TotCredit 
                FROM `r_department` d
                LEFT JOIN (
                SELECT i.`department` , sum(d.`price` * d.`qty`) AS cAmount , sum(d.`discount_total`) as  discount_total
                FROM `t_cash_sales_sum` s
                INNER JOIN `t_cash_sales_det` d ON s.`cl`= d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
                INNER JOIN `m_item` i ON d.`code`=i.`code`
                WHERE s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' $cluster $branch
                GROUP BY  i.`department` ) AS c ON d.`code`=c.department
              LEFT JOIN (
                SELECT i.`department` , SUM(d.`amount`) AS crAmount , sum(d.`discount_total`) as discount_total  
                FROM `t_credit_sales_sum` s
                INNER JOIN `t_credit_sales_det` d ON s.`cl`= d.`cl` AND s.`bc`=d.`bc` AND s.`nno`=d.`nno`
                INNER JOIN `m_item` i ON d.`code`=i.`code`
                WHERE s.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'  $cluster $branch
                GROUP BY  i.`department` ) AS cr ON d.`code`=cr.department  ";

        $r_detail['trading']=$this->db->query($sql)->result();

        $sql="SELECT  d.`code`,
                      d.`description`,
                      IFNULL(o.OPB,0)AS OPB,
                      IFNULL(p.Purchase,0) AS Purchase, 
                      IFNULL(c.Closing,0)AS Closing
              FROM `r_department` d
              LEFT JOIN (SELECT i.`department`  , SUM( m.`qty_in` * m.`cost`)-SUM( m.`qty_out` * m.`cost` ) AS OPB
                FROM `t_item_movement` m
                LEFT JOIN `m_item` i ON m.`item` = i.`code`
                WHERE m.`ddate`< '".$_POST['to']."' $cluster2 $branch2 GROUP BY i.`department` ) AS o ON d.`code`=o.department
              LEFT JOIN (SELECT i.`department`  , SUM( m.`qty_in` * m.`cost`)-SUM( m.`qty_out` * m.`cost` ) AS Purchase
              FROM `t_item_movement` m
              LEFT JOIN `m_item` i ON m.`item` = i.`code`
              WHERE m.`ddate` BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' $cluster2 $branch2 AND `trans_code`=3 GROUP BY i.`department`) AS P ON d.`code`=p.department
              LEFT JOIN (SELECT i.`department`  , SUM( m.`qty_in` * m.`cost`)-SUM( m.`qty_out` * m.`cost` ) AS Closing
              FROM `t_item_movement` m
              LEFT JOIN `m_item` i ON m.`item` = i.`code` WHERE m.`ddate`<= '".$_POST['to']."' $cluster2 $branch2 GROUP BY i.`department`) AS C  ON d.`code`=c.department";

        $r_detail['opening']=$this->db->query($sql)->result();

        if($this->db->query($sql)->num_rows()>0){
            $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
        }else{
            echo "<script>alert('No Data');window.close();</script>";
        }

	}
}	
?>