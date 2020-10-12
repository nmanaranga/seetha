<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_branch_supervisor extends CI_Model {

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
    $a['table_data'] = $this->data_table();
    $a['lbranch']=$this->get_branch_name();
    $a['lemployee']=$this->get_employee_name();
    return $a;
  }

  public function load(){
   echo $this->data_table();  
 }

     public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $bcCode = array("data"=>"Branch ", "style"=>"width: 50px; cursor : pointer;", "onclick"=>"set_short(1)");
        $empCode = array("data"=>"Employee ", "style"=>"width: 50px; cursor : pointer;", "onclick"=>"set_short(1)");
    
        $this->table->set_heading($bcCode, $empCode);
        
        $Q = $this->db->query("select 
          b.bc as bcCode 
          ,b.name as bcName 
          ,IFNULL(e.code,' - ') as empCode
          ,IFNULL(e.name,' - ') as empName
FROM m_branch b LEFT JOIN m_employee e on b.supervisor=e.auto_no");

        

        
       if ($Q->num_rows() > 0){            
        foreach($Q->result() as $R){
           
            $bcCode = array("data"=>$R->bcCode.' - '.$R->bcName, "style"=>"text-align: left; width: 108px; ", "value"=>"code");
            $empCode = array("data"=>$R->empCode.' - '.$R->empName, "style"=>"text-align: left; width: 108px; ", "value"=>"code");
        
            $this->table->add_row($bcCode,$empCode);
          }
        }
        
        
        return $this->table->generate();
    }

 


 public function save(){

  $supervisorUP = array(
    'supervisor' => $_POST['employee'] 
    );

  $this->db->where('bc', $_POST['branch']);
  $this->db->update('m_branch', $supervisorUP );
  echo "1";
}



public function get_branch_name(){
    $this->db->select(array('bc','name'));
    $query = $this->db->get('m_branch');

    $s = "<select style='width:250px' name='branch' id='branch' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->bc."'>".$r->bc.'-'.$r->name."</option>";
      }
        $s .= "</select>";
        
        return $s;

    }

      public function get_employee_name(){
        $this->db->select(array('auto_no','code','name'));
        $query = $this->db->get('m_employee');

        $s = "<select style='width:250px' name='employee' id='employee' >";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option   value='".$r->auto_no."'>".$r->code.'-'.$r->name."</option>";
        }
        $s .= "</select>";
        
        return $s;

    }










}




//--------------------cash Sales--------------
// SELECT tcash.ddate , SUM(tcash.net_amount) 
// FROM t_cash_sales_sum tcash
// WHERE tcash.is_cancel='0' AND tcash.cl='SC' AND tcash.bc='AM'
// AND tcash.ddate BETWEEN '2016-01-01' AND '2016-01-31'
// GROUP BY tcash.ddate
// ORDER BY tcash.ddate


//--------------------credit Sales--------------
// SELECT tcredit.ddate , SUM(tcredit.net_amount) 
// FROM t_credit_sales_sum tcredit
// WHERE tcredit.is_cancel='0' AND tcredit.cl='SC' AND tcredit.bc='AM'
// AND tcredit.ddate BETWEEN '2016-01-01' AND '2016-01-31'
// GROUP BY tcredit.ddate
// ORDER BY tcredit.ddate ASC



//--------------------pos Sales--------------
// SELECT tpos.ddate , SUM(tpos.net_amount) 
// FROM t_pos_sales_sum tpos
// WHERE tpos.is_cancel='0' AND tpos.cl='SH' AND tpos.bc='PD'
// AND tpos.ddate BETWEEN '2016-01-01' AND '2016-01-31'
// GROUP BY tpos.ddate
// ORDER BY tpos.ddate ASC


//-------------------- Sales return--------------
// SELECT treturn.ddate , SUM(treturn.net_amount) 
// FROM t_sales_return_sum treturn
// WHERE treturn.is_cancel='0' AND treturn.cl='SH' AND treturn.bc='PD'
// AND treturn.ddate BETWEEN '2016-01-01' AND '2016-01-31'
// GROUP BY treturn.ddate
// ORDER BY treturn.ddate ASC





















