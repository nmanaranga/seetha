<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_item_promotions extends CI_Model {

    private $sd;
    private $mtb;
    
    private $mod = '003';
    
    function __construct(){
        parent::__construct();

        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->load->model('user_permissions');

    }
    
    public function base_details(){
        $this->load->model('m_items');

        $a['table_data'] = $this->data_table();
        $a['max_no']=$this->max_no();
        return $a;
    }
    
    public function data_table(){
        $this->load->library('table');
        $this->load->library('useclass');
        
        $this->table->set_template($this->useclass->grid_style());
        
        $code = array("data"=>"No", "style"=>"width: 50px;");
        $from = array("data"=>"From Date", "style"=>"width: 60px;");
        $to = array("data"=>"To Date", "style"=>"width: 50px;");
        $action = array("data"=>"Action", "style"=>"width: 60px;");
        
        $this->table->set_heading($code,$from,$to,$action);
        
        $this->db->select(array('code', 'nno', 'date_from', 'date_to'));
        $this->db->group_by('nno');
        $this->db->limit(10);
        $query = $this->db->get('m_item_promotions');
        
        foreach($query->result() as $r){
            $but = "<img src='".base_url()."img/edit.gif' onclick='set_edit(\"".$r->nno."\")' title='Edit' />&nbsp;&nbsp;";
            if($this->user_permissions->is_delete('m_item_promotions')){$but .= "<img src='".base_url()."img/delete.gif' onclick='set_delete(\"".$r->nno."\")' title='Delete' />";}
            
            $no = array("data"=>$r->nno);
            $from = array("data"=>$r->date_from);
            $to = array("data"=>$r->date_to);
            $action = array("data"=>$but, "style"=>"text-align: center;");

            $this->table->add_row($no, $from,$to, $action);
        }

        return $this->table->generate();
    }
    
    public function get_data_table(){
        echo $this->data_table();
    }
    
    public function save(){

        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg."==".$errFile."==".$errLine); 
        }
        set_error_handler('exceptionThrower'); 
        try {

            if(!isset($_POST['inactive'])){$_POST['inactive']=0;}else{$_POST['inactive']=1;}


            for($x = 0; $x<12; $x++){
                if(isset($_POST['0_'.$x])){
                    if($_POST['0_'.$x] != "" && $_POST['n_'.$x] != "" ){
                        $a[]=array(
                            "cl"=>$this->sd['cl'],
                            "bc"=>$this->sd['branch'],
                            "code"=>$_POST['0_'.$x],
                            "nno"=>$_POST['nno'],
                            "ddate"=>$_POST['ddate'],
                            "description"=>$_POST['n_'.$x],
                            "promo_type"=>$_POST['p_'.$x],
                            "note"=>$_POST['1_'.$x],
                            "date_from"=>$_POST['date_from'],
                            "date_to"=>$_POST['date_to'],
                            "oc"=>$this->sd['oc']
                        );
                    }
                }
            }   

            if($_POST['code_'] == "0" || $_POST['code_'] == ""){
                if($this->user_permissions->is_add('m_item_promotions')){
                    unset($_POST['code_']);
                    if(count($a)){ $this->db->insert_batch("m_item_promotions", $a );}
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to save records";
                    $this->db->trans_commit();
                }  
            }else{
                if($this->user_permissions->is_edit('m_item_promotions')){
                    $this->set_delete();
                    unset($_POST['code_']);
                    if(count($a)){ $this->db->insert_batch("m_item_promotions", $a );}
                    echo $this->db->trans_commit();
                }else{
                    echo "No permission to edit records";
                    $this->db->trans_commit();
                }
            }

        } catch ( Exception $e ) { 
            $this->db->trans_rollback();
            echo $e->getMessage(). "Operation fail please contact admin"; 
        }  

    }



    public function check_code(){
        $this->db->where('nno', $_POST['nno']);
        $this->db->limit(1);  
        echo $this->db->get('m_item_promotions')->num_rows;
    }

    public function load(){
        $this->db->select(array(
            'm_item.code',
            'm_item.description',
            'm_item_promotions.nno',
            'm_item_promotions.ddate',
            'm_item_promotions.description',
            'm_item_promotions.date_from',
            'm_item_promotions.date_to',
            'm_item_promotions.inactive',
            'm_item_promotions.promo_type',
            'm_item_promotions.note'

        ));
        $this->db->from("m_item");
        $this->db->join("m_item_promotions","m_item.code=m_item_promotions.code");
        $this->db->where("nno", $_POST['nno']);
        $query = $this->db->get();
        $a['c'] = $query->result(); 



        echo json_encode($a);
    }

    public function delete(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try { 
            if($this->user_permissions->is_delete('m_item_promotions')){
                $this->db->where('nno', $_POST['nno']);
                $this->db->delete("m_item_promotions_det");

                $this->db->where('nno', $_POST['nno']);
                $this->db->limit(1);
                $this->db->delete('m_item_promotions');
                echo $this->db->trans_commit();
            }else{
                echo "No permission to delete records";
                $this->db->trans_commit();
            }
        } catch ( Exception $e ) { 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        }
    }

    private function set_delete(){
        $this->db->where("nno", $_POST['code_']);
        $this->db->delete("m_item_promotions_det");
    } 




    public function select(){
        $query = $this->db->get('m_item_promotions');

        $s = "<select name='sales_ref' id='sales_ref'>";
        $s .= "<option value='0'>---</option>";
        foreach($query->result() as $r){
            $s .= "<option title='".$r->name."' value='".$r->code."'>".$r->code." | ".$r->name."</option>";
        }
        $s .= "</select>";

        return $s;
    }

    public function max_no(){
        $this->db->select_max("nno");
        return $this->db->get('m_item_promotions')->first_row()->nno+1;
    }




}