
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class t_privilege_card extends CI_Model{
    
    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
    $this->load->database($this->sd['db'], true);
	$this->mtb = $this->tables->tb['t_privilege_card'];
    $this->m_customer = $this->tables->tb['m_customer'];
    $this->t_sales_sum=$this->tables->tb['t_sales_sum'];
    $this->t_previlliage_trans=$this->tables->tb['t_previlliage_trans'];
    $this->t_privilege_card=$this->tables->tb['t_privilege_card']; 
    }
    
    public function base_details(){        
	$a['table_data'] = $this->data_table();
	
	return $a;
    }
    
    
     public function PDF_report(){
          
          $this->db->select(array('name','address','tp','fax','email'));
          $this->db->where("cl",$this->sd['cl']);
          $this->db->where("bc",$this->sd['branch']);
          $r_detail['branch']=$this->db->get('m_branch')->result();
          $r_detail['page']=$_POST['page'];
          $r_detail['type']=$_POST['type'];  
          $r_detail['card_no']=$_POST['card_no1'];

           $this->db->select('*');
           $this->db->from($this->m_customer);
           $this->db->join($this->t_sales_sum , $this->m_customer.'.code='.$this->t_sales_sum.'.cus_id');
           $this->db->where('previlliage_card_no', $_POST['card_no1']); 
           $this->db->limit(1);
           $query = $this->db->get();
          if ($query->num_rows() > 0){
              foreach ($query->result() as $row){
                $r_detail['customer']= $query->result();       
                }
              } 

          //get_point_history

              $r_detail['b']=$this->db->select_sum('dr' ,'sdr')
             ->where('card_no',$_POST['card_no1'])
             ->get($this->t_previlliage_trans)->result();

             $r_detail['c']=$this->db->select_sum('cr' ,'scr')
             ->where('card_no',$_POST['card_no1'])
             ->get($this->t_previlliage_trans)->result();

             $query= $this->db->select()
             ->where('card_no',$_POST['card_no1'])
             ->get($this->t_previlliage_trans);                  
                               
             if($query->num_rows() > 0){
                    $r_detail['a']=$query->result();
                    }
                    else{
                        $r_detail['a']=2;
                        }

           //get_invoice_history    

            $this->db->select(array('nno','net_amount','ddate'));
            $this->db->where("previlliage_card_no",$this->input->post('code'));
            $this->db->limit(5);
            $query=$this->db->get($this->t_sales_sum);
            if($query->num_rows() > 0){
                $r_detail['ai']=$query->result();
            }else{
                $r_detail['ai']=2;
            }   



            $this->load->view($_POST['by'].'_'.'pdf',$r_detail);

     }
}