<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_sup_settlement_bal extends CI_Model{

  private $sd;
  private $mtb;

  private $mod = '003';

  function __construct(){
    parent::__construct();

    $this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);

  }

  public function base_details(){

  }


  public function PDF_report(){

    $this->db->select(array('name','address','tp','fax','email'));
    $this->db->where("cl",$this->sd['cl']);
    $this->db->where("bc",$this->sd['branch']);
    $r_detail['branch']=$this->db->get('m_branch')->result();
    $r_detail['page']='A4';
    $r_detail['orientation']='P';  
    $r_detail['card_no']=$_POST['card_no1'];
    $r_detail['dfrom']=$_POST['from'];
    $r_detail['dto']=$_POST['to'];

    $cluster=$_POST['cluster'];
    $branch=$_POST['branch'];
    $acc=$_POST['acc_code'];
    $no_range_frm=$_POST['t_range_from'];
    $no_range_to=$_POST['t_range_to'];

    $r_detail['cluster1']=$cluster;
    $r_detail['branch1']=$branch;
    $r_detail['acc']=$_POST['acc_code'];
    $r_detail['acc_des']=$_POST['acc_code_des'];

    

    $sql="SELECT cl,bc,ddate,acc_code,m_supplier.`name`,trans_code,trans_no,sub_trans_code,t_trans_code.`description`,sub_trans_no,dr,cr 
    FROM `t_debit_note_trans` 
    JOIN m_supplier ON m_supplier.`code`=t_debit_note_trans.`acc_code`
    JOIN t_trans_code ON t_trans_code.`code`=t_debit_note_trans.`sub_trans_code`
    WHERE t_debit_note_trans.cl='$cluster' AND t_debit_note_trans.bc='$branch' AND t_debit_note_trans.ddate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'
    ";

    if($_POST['debit_note']!=""){
      $sql.=" AND t_debit_note_trans.trans_no='".$_POST['debit_note']."'";
    }
    $sql.=" ORDER BY trans_code,trans_no,acc_code,ddate";

    $query=$this->db->query($sql);
    $r_detail['sum']=$query->result();

    if($query->num_rows()>0)
    {

      $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }
    else
    {
      echo "<script>alert('No Data');window.close();</script>";
    }


  }
}