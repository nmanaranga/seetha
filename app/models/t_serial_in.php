<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_serial_in extends CI_Model {
    
  private $sd;
  private $max_no; 
  private $trans_code;

  function __construct(){
  	parent::__construct();
  	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
    $this->mtb = $this->tables->tb['t_grn_sum'];
    $this->tb_po_trans= $this->tables->tb['t_po_trans'];
	}

 
     public function check_is_serial_item(){
      $this->db->select(array('serial_no'));
      $this->db->where("code",$this->input->post('code'));
      $this->db->limit(1);
      echo  $this->db->get("m_item")->first_row()->serial_no;
    }


    // public function check_is_serial_items($code){
    //   $this->db->select(array('serial_no'));
    //   $this->db->where("code",$code);
    //   $this->db->limit(1);
    //   return $this->db->get("m_item")->first_row()->serial_no;
    // }


    public function is_serial_available(){
      $this->db->select(array('available'));
      $this->db->where('cl',$this->sd['cl']);
      $this->db->where('bc',$this->sd['branch']);  
      $this->db->where("serial_no",$_POST['serial']);
      $this->db->where("item",$_POST['item']);
      $this->db->where("trans_no <>",$_POST['nno']);
      $query=$this->db->get("t_serial");

      if($query->num_rows()>0){
        echo "1";
      }else{
        echo "0";
      }
    }


    public function is_serial_entered($trans_no,$item,$serial){
            $this->db->select(array('available'));
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);  
            $this->db->where("serial_no",$serial);
            $this->db->where("item",$item);
            $query=$this->db->get("t_serial");

            if($query->num_rows()>0){
              return 1;
            }else{
              return 0;
            }
    }


    public function check_last_serial(){
            $this->db->select("serial_no");
            $this->db->where('cl',$this->sd['cl']);
            $this->db->where('bc',$this->sd['branch']);  
            $this->db->where("item",$_POST['item']);
            $this->db->order_by("auto_num", "desc");
            $this->db->limit(1);
            $query=$this->db->get("t_serial");

            if($query->num_rows()>0){
              echo $query->first_row()->serial_no;
                }else{
               echo 0;
            }
    }
    













 

}
