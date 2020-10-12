<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_transaction_list_purchase extends CI_Model 
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
        
        // $this->mtb = $this->tables->tb['t_lo_loan'];
        // $this->tb_client = $this->tables->tb['m_client'];
        // $this->tb_branch = $this->tables->tb['s_branches'];
    }
    
    public function base_details()
    {

        $a['cluster']=$this->get_cluster_name();
        $a['supplier']=$this->get_supplier_name();
        /*
        $this->load->model('m_client_application'); 
        $this->load->model('m_loan_type');
        $a['bc'] = $this->get_branch();
        $a['loan_type'] = $this->m_loan_type->select();
        $a['client_no'] = $this-> m_client_application->select();
          */
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

    public function get_supplier_name(){
        $sql="  SELECT code,name
        FROM m_supplier
        ORDER BY code asc";
        $query=$this->db->query($sql);

        $s = "<select name='supplier' id='supplier' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code.'-'.$r->name."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }

    public function PDF_report()
    {

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

        WHERE `date` BETWEEN '" . $_POST['from'] . "' AND '" . $_POST['to'] . "' AND t_lo_loan.loan_category='Opening' AND t_lo_loan.bc= '".$POST['bc']."'";


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

        public function get_branch()
        {

          $q = $this->db->select(array('code', 'name'))
          ->where('code', $this->sd['bc'])
          ->get($this->tb_branch);

          $s = "<select name='bc' id='bc'>";
        //$s .= "<option value='0'>---</option>";
          foreach($q->result() as $r)
          {
            $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code."-".$r->name."</option>";
        }
        $s .= "</select>";

        return $s;
    }
























    public function get_loanNo()
    {
       $q = $this->db->select(array('loan_no'))
       ->where('bc', $this->sd['bc'])
       ->get('t_loan_sum');

       $s = "<select name='loan_no' id='loan_no'>";
       $s .= "<option value='0'>---</option>";
       foreach($q->result() as $r)
       {
        $s .= "<option title='".$r->loan_no."' value='".$r->loan_no."'>".$r->loan_no."</option>";
    }
    $s .= "</select>";

    return $s;
}

public function get_all_branch()
{

  $q = $this->db->select(array('code', 'name', ))

  ->get($this->tb_branch);

  $s = "<select name='bc' id='bc'>";
        //$s .= "<option value='0'>---</option>";
  foreach($q->result() as $r)
  {
            //echo $r->num_rows() ;
    $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code."-".$r->name."</option>";
}
$s .= "</select>";

      // $a['d'] = $s ;
echo json_encode($s);

}

public function set_group()
{

        //echo $_POST['center'];
       // $query = $this->db->where("center_code", $_POST['center'])->get($this->tb_group);

    /*  $query ="SELECT 
                    `m_group`.`code`, 
                    `m_group`.`description`
FROM 
                     `m_group`
WHERE
m_group.center_code =  'KT001'
";
        
        $s = "<select name='group' id='group'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."</option>";
        }
        $s .= "</select>";
        
        return $s;*/

        $qry = $this->db

        ->select(array("code","description"))
        ->where("center_code",$_POST['center'])
           // ->where("mem_no","KT000001")
            //->where("is_approved",1)
        ->get($this->tb_group);

        $op="<select name='group' id='group'>";
        $op .="<option value='0'>---</option>"; 
        foreach($qry->result() as $r){

            $op .="<option title='".$r->description."'value='".$r->code."'>".$r->code."-".$r->description."</option>"; 

        }
        

        echo $op;
    }

    public function select_center(){
        $query = $this->db->where("bc", $this->sd['bc'])->get($this->tb_center);
        
        $s = "<select name='center' id='center'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code."</option>";
        }
        $s .= "</select>";
        
        return $s;
    }

}
?>