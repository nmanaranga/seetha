<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_cheque_print extends CI_Model {
    
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
    	$this->load->model('m_stores');
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

	public function PDF_report(){
		$this->db->select(array('name','address','tp','fax','email'));
		$this->db->where("cl",$this->sd['cl']);
		$this->db->where("bc",$this->sd['branch']);
		$r_detail['branch']=$this->db->get('m_branch')->result();

		$this->db->select(array('code','description'));
		$this->db->where("cl",$this->sd['cl']);
		$this->db->where("bc",$this->sd['branch']);
		$this->db->where("code",$_POST['stores']);
		$r_detail['store_des']=$this->db->get('m_stores')->row()->description;

		$cl=$this->sd['cl'];
		$bc=$this->sd['branch'];

		$r_detail['store_code']=$_POST['stores'];	
		$r_detail['type']=$_POST['type'];        
		$r_detail['dd']=$_POST['dd'];
		$r_detail['qno']=$_POST['qno'];

        $r_detail['from']=$_POST['from'];
        $r_detail['to']=$_POST['to'];

     

		$r_detail['page']=$_POST['page'];
		$r_detail['header']=$_POST['header'];
		$r_detail['orientation']=$_POST['orientation'];

        if($_POST['cheque_list'] =="2"){

            $r_detail['r_type']="Printed";

    		$sql="SELECT * from chq_printed where cl='".$_POST['cluster']."' AND bc='".$_POST['branch']."'
                    AND bank_date >= '".$_POST['from']."' AND bank_date <= '".$_POST['to']."'";
        }else{

            $r_detail['r_type']="Pending";

            $sql = "SELECT  v.nno as voucher_no,
                    v.`payment` as amount,
                    c.cheque_no as chq_no,
                    c.bank as acc_no,
                    c.description as acc_name,
                    v.acc_code as payee_id,
                    s.name as payee_name                  
            FROM t_voucher_sum v
            JOIN (SELECT * FROM `opt_issue_cheque_det` ic 
                  WHERE cl='".$_POST['cluster']."' AND bc='".$_POST['branch']."') c 
            ON c.cl = v.cl 
            AND c.bc = v.`bc` 
            AND c.trans_no = v.`nno`
            JOIN m_supplier s ON s.code = v.`acc_code`  
            WHERE c.`is_chq_print` ='0' 
              AND v.cl='".$_POST['cluster']."'
              AND v.bc='".$_POST['branch']."'
              AND v.ddate between '".$_POST['from']."' and '".$_POST['to']."'";
        }


		$r_detail['chq_det']=$this->db->query($sql)->result();	


        if($this->db->query($sql)->num_rows()>0){
			$this->load->view($_POST['by'].'_'.'pdf',$r_detail);
		}else{
			echo "<script>alert('No Data');window.close();</script>";
		}
	}



}	
?>