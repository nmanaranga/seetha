<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_sales_reports extends CI_Model {

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
    	//$a['store_list']=$this->m_stores->select3();
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


public function get_stores_cl(){
  $this->db->select(array('code','description'));
  $this->db->where("cl",$_POST['cl']);
  $this->db->where("bc",$_POST['bc']);
  $query = $this->db->get('m_stores');

  $s = "<select name='store' id='store' >";
  $s .= "<option value='0'>---</option>";
  foreach($query->result() as $r){
    $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.'-'.$r->description."</option>";
}
$s .= "</select>";

echo $s;

}


public function get_stores_bc(){
  $this->db->select(array('code','description'));
  $this->db->where("bc",$_POST['bc']);
  $query = $this->db->get('m_stores');

  $s = "<select name='store' id='store' >";
  $s .= "<option value='0'>---</option>";
  foreach($query->result() as $r){
    $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.'-'.$r->description."</option>";
}
$s .= "</select>";

echo $s;

}


public function get_stores_default(){
  $this->db->select(array('code','description'));
  $this->db->where("bc",$_POST['bc']);
  $query = $this->db->get('m_stores');

  $s = "<select name='store' id='store' >";
  $s .= "<option value='0'>---</option>";
  foreach($query->result() as $r){
    $s .= "<option title='".$r->description."' value='".$r->code."'>".$r->code.'-'.$r->description."</option>";
}
$s .= "</select>";

echo $s;

}



}	
?>