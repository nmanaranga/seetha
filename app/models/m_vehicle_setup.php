<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_vehicle_setup extends CI_Model {

    private $sd;
    private $mtb;
    private $mod = '004';

    function __construct() {
        parent::__construct();
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->mtb = $this->tables->tb['m_vehicle_setup'];
        $this->load->model('user_permissions');
    }

    public function base_details() {
        $this->load->model('m_vehicle_setup');
        $a['table_data'] = $this->data_table();

        return $a;
    }

 public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"Code", "style"=>"width: 100px; ");
        $name = array("data"=>"description", "style"=>"");
        $action = array("data"=>"Action", "style"=>"width: 100px;"); 
        
        $this->table->set_heading($code, $name,$action);
        
        $this->db->select(array('code', 'description'));
        $this->db->limit(10);
        $query = $this->db->get($this->mtb);
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->code."\")' title='Edit' />&nbsp;&nbsp;";
            $but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->code."\")' title='Delete' />";
            
            $code = array("data"=>$r->code);
            $name = array("data"=>$this->useclass->limit_text($r->description, 25));
            $action = array("data"=>$but, "style"=>"text-align: center;");
        
            $this->table->add_row($code, $name,$action);
        }
         
        return $this->table->generate();
    }
    
    public function get_data_table() {
        echo $this->data_table();
        //load();
    }


   

    public function save() {
       
        $this->db->trans_begin();
        error_reporting(E_ALL);

        function exceptionThrower($type, $errMsg, $errFile, $errLine) {
            throw new Exception($errMsg);
        }


        try {
            $_POST['code'] = strtoupper($_POST['code']);

            if ($_POST['code_'] == "0" || $_POST['code_'] == "") {

               
               if($this->user_permissions->is_add('m_vehicle_setup')){ 
                    unset($_POST['code_']);
		            $data=array(
                        "cl"=>$_POST['cl'],
                        "bc"=>$_POST['bc'],
                        "code"=>$_POST['code'],
                        "description"=>$_POST['description'],
                        "driver"=>$_POST['driver'],
                        "stores"=>$_POST['stores'] 
                    );
		
                    $this->db->insert($this->mtb, $data);

                    echo $this->db->trans_commit();

                }else{
                   echo "No permission to save records";
                   $this->db->trans_commit();
               }
            }else{
                if($this->user_permissions->is_edit('m_vehicle_setup')){
                    $this->db->where("code", $_POST['code_']);
                     $data=array(
                            "cl"=>$_POST['cl'],
                            "bc"=>$_POST['bc'],
                            "code"=>$_POST['code'],
                            "description"=>$_POST['description'],
                            "driver"=>$_POST['driver'],
                            "stores"=>$_POST['stores']    
                        );

			         $this->db->update($this->mtb, $data);
                     unset($_POST['code_']);

                    echo $this->db->trans_commit();
                }else{
                   echo "No permission to edit records";
                   $this->db->trans_commit();
               }
            }
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            echo $e->getMessage()." Operation fail please contact admin";
        }
    }

    public function check_code() {
        $this->db->where('code', $_POST['code']);
        $this->db->limit(1);

        echo $this->db->get($this->mtb)->num_rows;
    }

    /*public function load(){
    $this->db->where('code', $_POST['code']);
    $this->db->limit(1);
    
    echo json_encode($this->db->get($this->mtb)->first_row());
    }
*/

   
   public function delete(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {
            $this->db->where('code', $_POST['code']);
            $this->db->limit(1);
            $this->db->delete($this->mtb);
            echo $this->db->trans_commit();
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        }
    }
    
   
     public function auto_com(){
        $this->db->like('code', $_GET['q']);
        $this->db->or_like('description', $_GET['q']);
        $query = $this->db->select(array('code', 'description'))->get('m_vehicle_setup');

        if ($query->num_rows() > 0) {
            $this->db->like('code', $_GET['q']);
            $this->db->or_like('description', $_GET['q']);
            $query2 = $this->db->select(array('code', 'description'))->get('m_vehicle_setup');
        }else{
            $query2 = $this->db->select(array('code', 'description'))->get('m_vehicle_setup');
        }
        
        $abc = "";
            foreach($query2->result() as $r){
                $abc .= $r->code."|".$r->description;
            $abc .= "\n";
            }
        
        echo $abc;
        }  
        
    public function load(){


            $sql="SELECT m_vehicle_setup.`cl`,
                    m_vehicle_setup.`bc`,
                    m_vehicle_setup.`code`,
                    m_vehicle_setup.`description`, 
                    m_vehicle_setup.`driver`,
                    m_branch.`name` AS bc_name,
                    m_cluster.`description` AS cl_name,
                    m_employee.`name` AS driver_name,
                    m_vehicle_setup.stores,
                    m_stores.`description` AS stores_name
                    FROM m_vehicle_setup
                    JOIN m_branch ON 
                    m_vehicle_setup.`bc`=m_branch.`bc` 
                    JOIN m_cluster ON
                    m_vehicle_setup.`cl`=m_cluster.`code`
                    JOIN m_employee ON
                    m_vehicle_setup.`driver`=m_employee.`code`
                    JOIN m_stores ON
                    m_vehicle_setup.`stores`=m_stores.`code`
            WHERE `m_vehicle_setup`.`code`='".$_POST['code']."'";

        $qry=$this->db->query($sql);

        //if($qry->num_rows()>0){
            $b=$qry->result();
            
        //}else{
            //$b=2;
      // }
        echo json_encode($b);
    }

    public function driver_category(){
        $sql="SELECT 
                m_employee.`code`,
                m_employee.`category`,
                def_option_sales.`def_driver`,
                m_employee.`name`
                FROM m_employee
                JOIN def_option_sales ON
                m_employee.`category`=def_option_sales.def_driver
                HAVING m_employee.`category`  =def_option_sales.`def_driver`";
        $query=$this->db->query($sql);

        if ($query->num_rows() > 0) {

        $a = "<table id='item_list' style='width : 100%' >";
        $a .= "<th class='tb_head_th'>Code</th>";
        $a .= "<th class='tb_head_th'>Name</th>";
        $a .= "</thead></tr>";

        $a .= "<td>&nbsp;</td>";
        $a .= "<td>&nbsp;</td>"; 
        $a .= "</tr>";

         foreach ($query->result() as $r) {
            $a .= "<tr class='cl'>";
            $a .= "<td>" . $r->code . "</td>";
            $a .= "<td>" . $r->name . "</td>";
            $a .= "</tr>";
         }

          $a .= "</table>";


            } else {
                $a = 2;
            }
            echo $a;
            
    }

}
