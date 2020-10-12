<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_sales_target_group extends CI_Model {

  private $sd;
  private $mtb;


  function __construct(){
    parent::__construct();

    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->load->model('user_permissions');    
    $this->mtb = $this->tables->tb['t_sales_target'];

  }

  public function base_details(){
    $a['table_data'] = "adasdasd";
    return $a;
  }


public function PDF_report(){

    $this->db->select(array('discription'));
    $this->db->where("cCode",$this->sd['oc']);
    $r_detail['occ']=$this->db->get('users')->result();

    $this->db->select(array('bc','name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch']=$this->db->get('m_branch')->result();

     
 $sql12 = $this->db->query("SELECT 
  CONCAT( t.bc,' -  ',mb.name )as bcName
  ,t.bc as bc
  ,t.mmonth as mmonth 
  ,SUM(t.target) as target 
  ,SUM(t.achivement) as achivement
FROM t_sales_target_det t 
LEFT JOIN m_branch mb ON t.bc=mb.bc
WHERE t.mmonth='".$_POST['txtmMonth']."'

GROUP BY t.bc ,t.mmonth
ORDER BY t.bc ASC , t.mmonth ASC ");


  $sql1x= $this->db->query("SELECT IFNULL( SUM(sd.`target`),0)  as tar ,
    IFNULL( SUM(sd.`achivement`),0) as arc
    FROM `t_sales_target_det` sd 
    WHERE `mmonth`<'".$_POST['txtmMonth']."'");

  if($sql1x->num_rows() > 0){
    foreach($sql1x->result() as $r){
      $r_detail['cumlTar'] =number_format($r->tar,2);  
      $r_detail['cumlArch'] =number_format($r->arc,2); 
      $r_detail['cumlVar'] =number_format((float)$r->arc - (float)$r->tar ,2) ; 
      $r_detail['cumlVarPre'] =$r->arc < 1 ? 0.00 : number_format( ( (float)$r->arc - (float)$r->tar / (float)$r->arc ) * 100 ,2); 
    }     
  }


 // ".$_POST['from']."' 


    $r_detail['salesTrget']=$sql12->result() ;

    $r_detail['dd']=$_POST['dd'];
   
    $r_detail['month']=$_POST['txtmMonth'];
    
    $r_detail['page']=$_POST['page'];
    $r_detail['header']=$_POST['header'];
    $r_detail['orientation']=$_POST['orientation'];


      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    
  }


}