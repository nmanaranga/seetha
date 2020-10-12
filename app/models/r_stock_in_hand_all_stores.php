<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_stock_in_hand_all_stores extends CI_Model {
    
    private $tb_items;
    private $tb_storse;
    private $tb_department;
    private $sd;
    private $w = 297;
    private $h = 210;
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('useclass');
        
        $this->sd = $this->session->all_userdata();
		$this->load->database($this->sd['db'], true);
	
        $this->tb_items = $this->tables->tb['m_items'];
        $this->tb_storse = $this->tables->tb['m_stores'];
    }
    
    public function base_details(){

    	$this->load->model('m_branch');
    	$a['branch']=$this->get_branch_name();
    	return $a;
	}

	public function PDF_report(){

        $cluster=$_POST['cluster'];
        $branch=$_POST['branch'];     
        $item=$_POST['item'];

        $r_detail['cluster']=$cluster;  
        $r_detail['Selbranch']=$branch; 
       // $r_detail['$item']=$item;          
             
        $this->db->select(array('name','address','tp','fax','email'));
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $r_detail['branch']=$this->db->get('m_branch')->result();


   $sqll="SELECT 
              qcs.`cl`,
              qcs.`bc`,
              qcs.`item`,
              qcs.`batch_no`,
              qcs.`store_code`,
              qcs.`qty`,
              SUM(qcs.`qty`) AS su_qty,
              qcs.`cost`,
              qcs.`min_price`,
              qcs.`max_price`,
              qcs.`description`,
              mc.`description` AS cl_name,
              mbr.`name` AS br_name ,
              ms.`description` AS Sto_name
            FROM
              `qry_current_stock` qcs 
              INNER JOIN `m_cluster` mc 
                ON (mc.`code` = qcs.`cl`) 
              INNER JOIN `m_branch` mbr 
                ON (mbr.`bc` = qcs.`bc`)
              INNER JOIN `m_stores` ms 
                ON (ms.`code` = qcs.`store_code`) 
            WHERE item='$item'
            GROUP BY qcs.`store_code` ";

        $r_detail['r_st_i_h']=$this->db->query($sqll)->result();
       


        $r_detail['page']='A4';
        $r_detail['orientation']='P';  
        $r_detail['dfrom']=$_POST['from'];
        $r_detail['dto']=$_POST['to'];


               
        if($this->db->query($sqll)->num_rows()>0){
			$this->load->view($_POST['by'] . '_' . 'pdf',$r_detail);
		}else{
		 	echo "<script>alert('No Data');window.close()</script>";
		}


	}



}

