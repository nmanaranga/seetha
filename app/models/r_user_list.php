<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_user_list extends CI_Model 
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



    public function PDF_report()
    {

        $usrnfo=" SELECT `name`,`address01`,`address02`,`address03`,`phone01`,`phone02`,`phone03` FROM `s_company`";	        
        $usrfo=$this->db->query($usrnfo);
        $r_detail['info']=$usrfo->result();


        $r_detail['type']="r_user_list";
        $r_detail['page']=$_POST['page'];
        $r_detail['header']=$_POST['header'];
        $r_detail['orientation']="P";
        $r_detail['r_type']="Opening Loan List";
        $r_detail['to']=$_POST['to'];
        $r_detail['from']=$_POST['from'];

        if(!empty($_POST['cluster'])){
            $clus="AND ui.cl='".$_POST['cluster']."'";
        }else{
           $clus=""; 
        }

         if(!empty($_POST['branch'])){
            $branch="AND ui.bc='".$_POST['branch']."'";
        }else{
           $branch=""; 
        }

        $qry="SELECT s.`loginName`,s.`discription`,ui.`cl`,ui.`bc`,mc.`description` AS clus_name,mb.`name` AS branch_name
        FROM s_users s
        JOIN u_branch_to_user ui ON ui.`user_id`=s.`cCode`
        JOIN m_cluster mc ON mc.`code` =ui.`cl`
        JOIN m_branch mb ON mb.`bc`=ui.`bc`
        WHERE s.`cCode` NOT IN(1)
        $branch $clus
        ORDER BY s.`cCode`,ui.`cl`,ui.`bc`";


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



}
?>