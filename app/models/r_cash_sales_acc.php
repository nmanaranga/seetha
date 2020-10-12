
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class r_cash_sales_acc extends CI_Model{
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
      parent::__construct();
      $this->sd = $this->session->all_userdata();
      $this->load->database($this->sd['db'], true);
   
    }
    
    public function base_details(){
      return "";
    }
    

     public function PDF_report(){
          
          $this->db->select(array('name','address','tp','fax','email'));
          $this->db->where("cl",$this->sd['cl']);
          $this->db->where("bc",$this->sd['branch']);
          $r_detail['branch']=$this->db->get('m_branch')->result();
          $r_detail['page']='A4';
          $r_detail['orientation']='P';  
          $r_detail['dfrom']=$_POST['from'];
          $r_detail['dto']=$_POST['to'];
               

       
          $query = "SELECT * FROM t_account_trans WHERE trans_code='4' AND cl='C1' AND bc='B01' AND ddate BETWEEN '2014-09-10' AND '2014-10-14'";

          $r_detail['cash_sales']=$this->db->query($query);

        

          if($this->db->query($query)->num_rows()>0){
            $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
          }else{
            echo "<script>alert('No Data');window.close();</script>";
          }
     }
}