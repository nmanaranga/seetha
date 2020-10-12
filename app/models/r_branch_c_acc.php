<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class r_branch_c_acc extends CI_Model {
    
    private $sd;
    private $tb_sum;
    private $mtb;
    private $mod = '003';
    private $tb_det;
    private $max_no;
    
    function __construct(){
	parent::__construct();
	
	$this->sd = $this->session->all_userdata();
  $this->load->database($this->sd['db'], true);
	$this->mtb = $this->tables->tb['m_items'];
  $this->load->model('user_permissions');
    }
    
    public function base_details(){
	//$a['id'] = $this->utility->get_max_no("t_bank_reconcil_sum", "nno"); 
	//return $a;
    }
    
    
	
	public function save(){
    $this->db->trans_begin();
    error_reporting(E_ALL); 
    function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
        throw new Exception($errMsg); 
    }
    set_error_handler('exceptionThrower'); 
    try { 

    for($x = 0; $x<$_POST['hid_count']; $x++){    
          $r_branch_c_acc[]= array(
              "cl"          =>$this->sd['cl'],
              "bc"          =>$this->sd['branch'],
              "acc_code"    =>$_POST['acc_'.$x],
              "ref_cl"      =>$_POST['cl_'.$x],
              "ref_bc"      =>$_POST['bc_'.$x],
              "oc"          =>$this->sd['oc'], 
            );
      }


      if($this->user_permissions->is_add('r_branch_c_acc')){
        $this->set_delete();
        if (isset($r_branch_c_acc)){
          if (count($r_branch_c_acc)){
              $this->db->insert_batch("r_branch_current_acc", $r_branch_c_acc);
          }
        }
          echo $this->db->trans_commit();
        }else{
          echo "No permission to save records";
          $this->db->trans_commit();
        }  
               
    }catch(Exception $e){ 
        $this->db->trans_rollback();
        echo $e->getMessage()."Operation fail please contact admin"; 
    } 
  }  

   
public function set_delete(){
  $sql="DELETE FROM r_branch_current_acc";
  $this->db->query($sql);
}
    
    

public function is_saved(){
  $sql="SELECT * FROM r_branch_current_acc ";
  $query=$this->db->query($sql);
  if($query->num_rows()>0){
    $a=1;
  }else{
    $a=2;
  }
  echo $a;
}

public function load_saved_data(){

  $sql="SELECT r.`acc_code`, a.description,ref_bc 
        FROM r_branch_current_acc r
        JOIN m_account a ON a.code = r.`acc_code`
        ORDER BY ref_bc asc
        ";

  $query=$this->db->query($sql);
  if($query->num_rows()>0){
    $a['det']= $query->result();
  }else{
    $a=2;
  }
  echo json_encode($a);
}

    public function load_grid(){
      $sql="SELECT 
          cl,
          c.`description`,
          b.`bc`,
          b.`name` 
        FROM
          m_branch b 
          JOIN m_cluster c 
            ON c.`code` = b.`cl` 
         ORDER BY bc asc";
           
      $query=$this->db->query($sql);
      if($query->num_rows()>0){
        $a['det']=$query->result();
      }else{
        $a=2;
      }
      echo json_encode($a);
    }



public function PDF_report(){
  //var_dump($_POST['by']);
 //exit();
   
   $this->db->select(array(
      'name',
      'address',
      'tp',
      'fax',
      'email'
    ));
    $this->db->where("cl", $this->sd['cl']);
    $this->db->where("bc", $this->sd['branch']);
    $r_detail['branch'] = $this->db->get('m_branch')->result();
    
    $session_array       = array(
      $this->sd['cl'],
      $this->sd['branch'],

    );

    $this->db->select(array(
      'loginName'
    ));
    $this->db->where('cCode', $this->sd['oc']);
    $r_detail['user'] = $this->db->get('users')->result();

    $cl = $this->sd['cl'];
    $bc = $this->sd['branch'];

    $r_detail['session'] = $session_array;
    $r_detail['page']        = $_POST['page'];
    $r_detail['header']      = $_POST['header'];
    $r_detail['orientation'] = $_POST['orientation'];      
     

    $sql="SELECT 
            acc_code,
            ref_cl,
            ref_bc,
            mc.description,
            mb.name,
            ma.`description`AS acc_name
          FROM
            r_branch_current_acc ca
          LEFT JOIN m_cluster mc ON mc.code = ref_cl 
          LEFT JOIN m_branch mb  ON mb.bc =ref_bc 
          LEFT JOIN m_account ma ON ma.code = acc_code 
          WHERE ca.bc='$bc'
          ORDER BY ref_cl";

    $sum = $this->db->query($sql);            
    $r_detail['sum'] = $sum->result(); 

    
      // $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    if($sum->num_rows()>0){
        $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
            
    }else{
        echo "<script>alert('No data found');close();</script>";
    }
                
  }
}

  
