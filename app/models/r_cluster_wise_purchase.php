<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class r_cluster_wise_purchase extends CI_Model{    
  private $sd;
  private $mod = '003';

  function __construct(){
    parent::__construct();

    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
  }

  public function base_details(){
    $a['table_data'] = "a";
    return $a;
  }


public function PDF_report($RepTyp=""){
    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch']=$this->db->get('m_branch')->result();

    $this->db->select(array('code','description'));
    $this->db->where("code",$_POST['cluster']);
    $r_detail['SelCluster']=$this->db->get('m_cluster')->result();

     $this->db->select(array('code','name'));
    $this->db->where("code",$_POST['supplier']);
    $r_detail['SelSupplier']=$this->db->get('m_supplier')->result();

    $r_detail['page']='A4';
    $r_detail['orientation']='L';  
    $r_detail['dfrom']=$_POST['from'];
    $r_detail['dto']=$_POST['to'];

    $r_detail['']=$_POST['cluster'];

    $query=$this->db->query("SELECT 
      gs.ddate as ddate,
      gs.nno as grn_no ,
      gs.inv_no as invoice_no ,
      itm.code itm_code,
      itm.model itm_model,
      itm.description itm_des,
      gd.qty,
      gd.price as cost_prize,
      gd.min_price as last_prize,
      gd.max_price AS sales_prize,
      (gd.qty * gd.price) as total
      FROM t_grn_det gd
      left join t_grn_sum gs on gd.nno=gs.nno and gd.cl=gs.cl AND gd.bc=gs.bc
      left join m_item itm on gd.code=itm.code
      left join m_supplier sp on gs.supp_id=sp.code
      WHERE gd.cl='".$_POST['cluster']."'
      AND gs.ddate between '".$_POST['from']."'
       AND '".$_POST['to']."'
      AND sp.code='".$_POST['supplier']."'
      order by gs.ddate ASC");

    $r_detail['purchase']=$query->result();


    if($query->num_rows()>0){
     $exTy=($RepTyp=="")?'pdf':'excel';
        $this->load->view($_POST['by'].'_'.$exTy,$r_detail);
    }else{
        echo "<script>alert('No Data');window.close();</script>";
    }
}

public function Excel_report(){
    $this->PDF_report("Excel");
}
}   

