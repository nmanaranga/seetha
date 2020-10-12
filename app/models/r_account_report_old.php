<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_account_report extends CI_Model {
    
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
    	$a['stores']=$this->get_stores();
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
		$this->db->select(array('bc','name'));
		$query = $this->db->get('m_branch');

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


  public function get_stores(){
		$this->db->select(array('code','description'));
		$query = $this->db->get('m_stores');

		$s = "<select name='store' id='store' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.'-'.$r->description."</option>";
	    }
        $s .= "</select>";
        
        return $s;

    }

    public function get_account(){   

    if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
     
      $sql = "SELECT m.`code`,IFNULL(c.`nic`,'') AS nic,m.`description` FROM m_account m
              LEFT JOIN m_customer c ON c.`code` = m.`code` 
              WHERE m.code LIKE '%$_POST[search]%' OR nic LIKE '%$_POST[search]%'
              OR description LIKE '%$_POST[search]%' 
              LIMIT 25";
     
      $query = $this->db->query($sql);
      $a  = "<table id='item_list' style='width : 100%' >";
      $a .= "<thead><tr>";
      $a .= "<th class='tb_head_th'>Code</th>";
      $a .= "<th class='tb_head_th' colspan='2'>Description</th>";
      $a .= "<th class='tb_head_th'>NIC</th>";
      $a .= "</thead></tr><tr class='cl'><td>&nbsp;</td><td colspan='2'>&nbsp;</td><td>&nbsp;</td></tr>";

    foreach($query->result() as $r){
      $a .= "<tr class='cl'>";
      $a .= "<td>".$r->code."</td>";
      $a .= "<td colspan='2'>".$r->description."</td>";
      $a .= "<td>".$r->nic."</td>";
      $a .= "</tr>";
    }
      $a .= "</table>";
      echo $a;
  
    }    


    public function account_type(){
        $this->db->like('code', $_GET['q']);
        $this->db->or_like('heading', $_GET['q']);
        $query = $this->db->select(array('code','heading'))->get("m_account_type");
        $abc = "";
        foreach($query->result() as $r){
            $abc .= $r->code."|".$r->heading;
        $abc .= "\n";
        }
        echo $abc;
    } 


    public function trans_type(){
        $this->db->like('code', $_GET['q']);
        $this->db->or_like('description', $_GET['q']);
        $query = $this->db->select(array('code','description'))->get("t_trans_code");
        $abc = "";
        foreach($query->result() as $r){
            $abc .= $r->code."|".ucwords(strtolower($r->description));
        $abc .= "\n";
        }
        echo $abc;
    }


    public function auto_com(){
        $this->db->like('code', $_GET['q']);
        $this->db->or_like('description', $_GET['q']);
        $query = $this->db->select(array('code','description'))->get('m_account');
        $abc = "";
            foreach($query->result() as $r){
                $abc .= $r->code."|".$r->description;
                $abc .= "\n";
            }
        echo $abc;
    }  
    
    public function acc_cat(){
        $this->db->like('code', $_GET['q']);
        $this->db->or_like('description', $_GET['q']);
        $query = $this->db->select(array('code','description'))->get('m_account_category');
        $abc = "";
            foreach($query->result() as $r){
                $abc .= $r->code."|".$r->description;
                $abc .= "\n";
            }
        echo $abc;
    }  
    
    public function cntrl_acc(){
        $this->db->like('code', $_GET['q']);
        $this->db->or_like('description', $_GET['q']);
        $this->db->where('is_control_acc','1');
        $query = $this->db->select(array('code','description'))->get('m_account');
        $abc = "";
            foreach($query->result() as $r){
                $abc .= $r->code."|".$r->description;
                $abc .= "\n";
            }
        echo $abc;
    } 


    public function get_account_det(){
        $this->db->select(array('m_account.code','m_account.description','m_account_type.heading as heading','m_account_type.code as type'));
        $this->db->where('m_account.code',$_POST['code']);
        $this->db->from('m_account');
        $this->db->join('m_account_type','m_account_type.code=m_account.type');
        $this->db->join('m_account_category','m_account_category.code=m_account.category');
        $query=$this->db->get();
          if($query->num_rows()>0){
            $a['acc']=$query->result();
          }else{
            $a['acc']=2;
          }
        echo json_encode($a);
    }

    

	public function PDF_report(){


        // $row_count=(int)$_POST['row_count'];
        // for($x=0;$x<$row_count;$x++){
        //     if(isset($_POST['n_'.$x])){
        //        $v_print_multi_acc=array(
        //         "cl"=>$this->sd['cl'],
        //         "bc"=>$this->sd['branch'],
        //         "oc"=>$this->sd['oc'],
        //         "acc"=>$_POST['2_'.$x]
        //         );
        //         $this->db->insert('v_print_multi_acc',$v_print_multi_acc);   
        //     }
        // }

		// $this->db->select(array('acc'));
		// $this->db->where("cl",$this->sd['cl']);
		// $this->db->where("bc",$this->sd['branch']);
		// $this->db->where("oc",$this->sd['oc']);

        $cluster=$_POST['cluster'];
        $branch=$_POST['branch'];

        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];

        $this->db->select(array('name','address','tp','fax','email'));
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $r_detail['branch']=$this->db->get('m_branch')->result();

        $this->db->where('code',$_POST['acc_code']);

        $query=$this->db->get('m_account');
        foreach($query->result() as $row){
            $r_detail['account_det']=$row->description;
        }

        $sql="SELECT SUM(dr_amount-cr_amount) AS op, SUM(dr_amount) AS dr, SUM(cr_amount) AS cr FROM t_account_trans WHERE acc_code='$_POST[acc_code]' AND ddate<'$_POST[from]'";
        if(!empty($cluster)){
            $sql.=" AND `cl` = '$cluster'";
        }if(!empty($branch)){
            $sql.=" AND `bc` = '$branch'";
        }

        //$r_detail['op']=$this->db->query($sql)->first_row()->op;
        $r_detail['op']=$this->db->query($sql)->result();


        $sqll="SELECT t_account_trans.*,t_trans_code.`description` AS det,t_account_trans.description AS description, t_trans_code.`code` as t_code 
                FROM t_account_trans LEFT JOIN t_trans_code ON t_trans_code.`code`= t_account_trans.`trans_code` 
                WHERE ddate!='' 
               AND ddate BETWEEN '$_POST[from]' AND '$_POST[to]'
               ";
        if(!empty($cluster)){
            $sqll.=" AND `cl` = '$cluster'";
        }
        if(!empty($branch)){
            $sqll.=" AND `bc` = '$branch'";
        }  
        if($_POST['acc_code']!=""){
            $sqll.=" AND acc_code='$_POST[acc_code]'";
        }     
        $sqll.=" ORDER BY ddate,auto_no";
        $r_detail['all_acc_det']=$this->db->query($sqll)->result();

		$cl=$this->sd['cl'];
		$bc=$this->sd['branch'];
        $r_detail['acc_code']=$_POST['acc_code'];
        $r_detail['page']='A4';
        $r_detail['orientation']='P';  
        $r_detail['dfrom']=$_POST['from'];
        $r_detail['dto']=$_POST['to'];


               
        if($this->db->query($sqll)->num_rows()>0){
			$this->load->view('r_account_report_pdf',$r_detail);
		}else{
		 	echo "<script>alert('No Data');window.close()</script>";
		}
	}



}	
?>