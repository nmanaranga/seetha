<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_transaction_list_voucher extends CI_Model 
{
    
    private $sd;
    private $w = 210;
    private $h = 297;
    
	private $mtb;
    private $tb_client;
    private $tb_branch;
   
    function __construct()
	{
        parent::__construct();
        
        
        $this->sd = $this->session->all_userdata();
        $this->load->database();
        $this->load->library('useclass');
        $this->load->database($this->sd['db'], true);
    
        
		// $this->mtb = $this->tables->tb['t_lo_loan'];
		// $this->tb_client = $this->tables->tb['m_client'];
		// $this->tb_branch = $this->tables->tb['s_branches'];
    }
    
    public function base_details()
	{
    		
        $this->load->model('m_branch');
        $a['cluster']=$this->get_cluster_name();
        $a['branch']=$this->get_branch_name();
       
        $a['d_cl']=$this->sd['cl'];
        $a['d_bc']=$this->sd['branch'];
        return $a;
        
        
    }


    public function get_cluster_name(){
        $sql="  SELECT `code`,description 
                FROM m_cluster m
                JOIN u_branch_to_user u ON u.cl = m.code
                WHERE user_id='".$this->sd['oc']."'
                GROUP BY m.code";
        $query=$this->db->query($sql);

        $s = "<select name='cluster' id='cluster' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.'-'.$r->description."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }

  public function get_branch_name(){
        $sql="  SELECT m.`bc`,name 
                FROM m_branch m
                JOIN u_branch_to_user u ON u.bc = m.bc
                WHERE user_id='".$this->sd['oc']."'
                GROUP BY m.bc";
        $query=$this->db->query($sql);

        $s = "<select name='branch' id='branch' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc.'-'.$r->name."</option>";
        }
        $s .= "</select>";
        
        return $s;

    }


    public function get_branch_name2(){
        $sql="  SELECT m.`bc`,name 
                FROM m_branch m
                JOIN u_branch_to_user u ON u.bc = m.bc
                WHERE user_id='".$this->sd['oc']."' AND m.cl='".$_POST['cl']."'
                GROUP BY m.bc";
        $query=$this->db->query($sql);

        $s = "<select name='branch' id='branch' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc.'-'.$r->name."</option>";
        }
        $s .= "</select>";
        
        echo $s;
    }

     public function get_branch_name3(){
        $sql="  SELECT m.`bc`,name 
                FROM m_branch m
                JOIN u_branch_to_user u ON u.bc = m.bc
                WHERE user_id='".$this->sd['oc']."'
                GROUP BY m.bc";
        $query=$this->db->query($sql);

        $s = "<select name='branch' id='branch' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc.'-'.$r->name."</option>";
        }
        $s .= "</select>";
        
        echo $s;
    }

	
	public function PDF_report()
	{
        var_dump("expression");
        $usrnfo=" SELECT `name`,`address01`,`address02`,`address03`,`phone01`,`phone02`,`phone03` FROM `s_company`";	        
	    $usrfo=$this->db->query($usrnfo);
	    $r_detail['info']=$usrfo->result();
	
	 
			$r_detail['type']=$_POST['type'];
			$r_detail['page']=$_POST['page'];
			$r_detail['header']=$_POST['header'];
			$r_detail['orientation']=$_POST['orientation'];
	        $r_detail['r_type']="Opening Loan List";
			$r_detail['to']=$_POST['to'];
			$r_detail['from']=$_POST['from'];
			
			$qry="SELECT 
  t_lo_loan.`no`,
  t_lo_loan.`loan_date`,
  t_lo_loan.client_no,
  c.f_name as fn,c.m_name as mn, c.`l_name` as ln,
  t_lo_loan.loan_type,
  t_lo_loan.loan_no,
  t_lo_loan.interest_rate,
  t_lo_loan.no_of_instalments,
  t_lo_loan.loan_amount,
 
   t_lo_loan.paybl_amount,
    t_lo_loan.paid_amount,
  t_lo_loan.paybl_amount-t_lo_loan.paid_amount AS balance
  
FROM
  t_lo_loan  INNER JOIN `m_lo_client` c ON t_lo_loan.client_no=c.`nic_no`

					WHERE `date` BETWEEN '" . $_POST['from'] . "' AND '" . $_POST['to'] . "' AND t_lo_loan.loan_category='Opening' AND t_lo_loan.bc= '".$this->sd['bc']."'";
					
			
			if($_POST['client_no']!="" && $_POST['client_no']!="---" && $_POST['client_no']!="0")
			{ 
				$qry.="AND t_lo_loan.client_no =  '".$_POST['client_no']."'";
			}

            $qry .="ORDER BY t_lo_loan.`loan_no`";
/*
			if($_POST['group']!="" && $_POST['group']!="---" && $_POST['group']!="0")
			{ 
				
				$qry.="AND `m_member`.`group_code` =  '".$_POST['group']."'";
			}

			if($_POST['center']!="" && $_POST['center']!="---" && $_POST['center']!="0")
			{ 
				
				$qry.="AND `m_member`.`center_code` =  '".$_POST['center']."'";
			}

			if($_POST['bc']!="" && $_POST['bc']!="---" && $_POST['bc']!="0")
			{ 
				
				$qry.="AND `t_loan_application_sum`.`bc` =  '".$_POST['bc']."'";
			}

			if($_POST['loan_type']!="" && $_POST['loan_type']!="---" && $_POST['loan_type']!="0")
			{ 
				
				$qry.="AND `t_loan_application_sum`.`loan_type` =  '".$_POST['loan_type']."'";
			}		
			*/
			
	        $data=$this->db->query($qry);  
			if($data->num_rows()>0)
			{
				$r_detail['r_data']=$data->result();
				$this->load->view($_POST['by'].'_'.'pdf',$r_detail);
		    }
			else
			{
				echo "<script>alert('No data found');close();</script>";
		    } 
		 
    }

   

}
?>