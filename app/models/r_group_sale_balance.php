<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_group_sale_balance extends CI_Model {
    
    private $sd;
    private $mtb;
    private $mod = '003';
    private $max_no;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
	$this->load->database($this->sd['db'], true);
	$this->load->model('user_permissions');
  $this->tb_items = $this->tables->tb['m_items'];
        

    }
    
    public function base_details(){

      $this->load->model('m_branch');
      $a['cluster']=$this->get_cluster_name();
      $a['branch']=$this->get_branch_name();
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
         
      $r_detail['no']=$_POST['nno'];
      $r_detail['page']=$_POST['page'];
      $r_detail['header']=$_POST['header'];
      $r_detail['orientation']=$_POST['orientation'];
      $cluster=$_POST['cluster'];
      $branch=$_POST['branch'];
      $from=$_POST['from'];
      $to=$_POST['to'];
      $code=$_POST['code'];

      $this->db->select(array('name','address','tp','fax','email'));
      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$this->sd['branch']);
      $r_detail['branch']=$this->db->get('m_branch')->result();

      $this->db->select(array('name','address','tp','fax','email'));
      $this->db->where("cl",$this->sd['cl']);
      $this->db->where("bc",$r_detail['ship_to_bc']);
      $r_detail['ship_branch']=$this->db->get('m_branch')->result();

      if($_POST['cluster'] != "" && $_POST['branch'] != "" && $_POST['code'] != "" ){
      $sql="SELECT 
              a.item,
              a.QtyIn,
              a.QtyOut,
              a.Balance,
              i.`description`,
              i.`category`,
              a.`dddate`
              FROM 
              (SELECT 
              i.`item`,
              SUM(i.`qty_in`) AS QtyIn,
              SUM(i.`qty_out`) AS QtyOut,
              SUM(i.`qty_in`) - SUM(i.`qty_out`) AS Balance,
              i.`ddate` AS dddate
              FROM
              `t_item_movement` i 
              WHERE i.`cl` = '$cluster' 
              AND i.`bc` = '$branch' 
              AND i.`group_sale_id` = '$code' 
              AND i.ddate BETWEEN '$from' AND '$to' 
              GROUP BY i.`item`) AS a 
              LEFT JOIN `m_item` AS i 
              ON a.item = i.`code` ";

      
      $query=$this->db->query($sql);
      if($query->num_rows>0){
        $r_detail['det']=$query->result();
        $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
      
      }else{
        $r_detail['det']=2;
      }
    }else{
      echo "<script>alert('No data found ');close();</script>";
    }
  }


  }