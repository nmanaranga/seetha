<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_free_issue_sales extends CI_Model {

    private $sd;    
    private $mod = '003';

    function __construct() {
        parent::__construct();
        $this->sd = $this->session->all_userdata();
        $this->load->database($this->sd['db'], true);
        $this->load->model('user_permissions');  
        $this->max_no = $this->utility->get_max_no("m_item_free_sales", "nno");      
    }

    public function base_details() {        
        $a['maxno'] = $this->utility->get_max_no("m_item_free_sales", "nno");
        return $a;
    }  

   

    public function save(){
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errMsg); 
        }
        set_error_handler('exceptionThrower'); 
        try {

            $sum = array(   
                "cl"     => $this->sd["cl"],
                "bc"     => $this->sd["branch"],                           
                "nno"    => $this->max_no,
                "code"   => $_POST['code'],
                "name"   => $_POST['c_name'],
                "dfrom"  => $_POST['date_from'],   
                "dto"    => $_POST['date_to'], 
                "date"   => $_POST['date'], 
                "oc"     => $this->sd["oc"],                                                       
            );



            for ($x = 0; $x < 25; $x++) {
                if (isset($_POST['00_' . $x], $_POST['33_' . $x] )) {
                    if ($_POST['00_' . $x] != "" && $_POST['33_' . $x] != "") {
                        $det[] = array(     
                            "cl"      => $this->sd["cl"],
                            "bc"      => $this->sd["branch"],                            
                            "nno"     => $this->max_no,
                            "po_item" => $_POST['0_'.$x],
                            "po_qty"  => $_POST['3_'.$x],
                            "foc_item"=> $_POST['00_'.$x],   
                            "foc_qty" => $_POST['33_'.$x],                                                        
                        );
                    }
                }
            }

            if ($_POST['hid'] == "0" || $_POST['hid'] == "") {
                if($this->user_permissions->is_add('m_free_issue_sales')){

                    $this->db->insert("m_item_free_sales", $sum);

                    if (isset($det)) {
                        if (count($det)) {
                            $this->db->insert_batch("m_item_free_sales_det", $det);
                        }
                    }
                    $this->utility->save_logger("SAVE",55,$this->max_no,$this->mod);
                    echo $this->db->trans_commit();
                }else{
                    $this->db->trans_commit();
                    echo "No permission to save records";
                }

            }else{
                if($this->user_permissions->is_edit('m_free_issue_sales')){

                    $this->db->where("nno", $this->max_no);
                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->delete("m_item_free_sales_det");

                    if (isset($det)) {
                        if (count($det)) {
                            $this->db->insert_batch("m_item_free_sales_det", $det);
                        }
                    }

                    $this->db->where("cl", $this->sd['cl']);
                    $this->db->where("bc", $this->sd['branch']);
                    $this->db->where("nno", $this->max_no);
                    $this->db->update("m_item_free_sales", $sum);

                    $this->utility->save_logger("EDIT",55,$this->max_no,$this->mod);
                    echo $this->db->trans_commit();
                }else{
                    $this->db->trans_commit();
                    echo "No permission to edit records";
                }
            }
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo "Operation fail please contact admin"; 
        }   

    }

    public function delete() {
        $this->db->trans_begin();
        error_reporting(E_ALL); 
        function exceptionThrower($type, $errMsg, $errFile, $errLine) { 
            throw new Exception($errLine); 
        }
        set_error_handler('exceptionThrower'); 
        try {
            if($this->user_permissions->is_delete('m_free_issue_sales')){
         
                  $data=array('is_cancel'=>'1');
                  $this->db->where('cl',$this->sd['cl']);
                  $this->db->where('bc',$this->sd['branch']);
                  $this->db->where('nno',$_POST['id']);
                  $this->db->update('m_item_free_sales',$data);
                 
                  $this->utility->save_logger("CANCEL",55,$_POST['id'],$this->mod);
                  echo $this->db->trans_commit();
                }else{
                    $this->db->trans_commit();
                    echo "No permission to delete records";
                }  
        }catch(Exception $e){ 
            $this->db->trans_rollback();
            echo $e->getMessage()."Operation fail please contact admin"; 
        } 
    }
    
    public function load(){
        $bc = $this->sd['branch'];
        $cl = $this->sd['cl'];
        $nno = $_POST['no'];   

        $sql="SELECT * FROM m_item_free_sales WHERE cl='$cl' AND bc='$bc' AND nno='$nno'";
        $query=$this->db->query($sql);

        $x = 0;

        if ($query->num_rows() > 0) {
            $a['sum'] = $query->result();
        } else {
            $x = 2;
        }


        $sql2="SELECT c.description as f_des,
                        c.model as f_mod,
                        d.description as i_des,
                        d.model as i_mod,
                        cl,
                        bc,
                        po_qty,
                        foc_qty,
                        po_item,
                        foc_item
               FROM m_item_free_sales_det 
               LEFT JOIN m_item d on d.code = m_item_free_sales_det.po_item
               LEFT JOIN m_item c on c.code = m_item_free_sales_det.foc_item
               WHERE cl='$cl' AND bc='$bc' AND nno='$nno'";
        $query2=$this->db->query($sql2);

        if ($query2->num_rows() > 0) {
            $a['det'] = $query2->result();
        } else {
            $x = 2;
        }


        if ($x == 0) {
            echo json_encode($a);
        } else {
            echo json_encode($x);
        }
    }

    public function foc_sale_list(){
    
        if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}               

        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
       
         $sql="SELECT * FROM m_item_free_sales WHERE cl='$cl' AND bc='$bc'  
                ORDER BY nno asc             
               limit 25";    
     
        $query=$this->db->query($sql);
             $a = "<table id='item_list' style='width : 100%' >";
            $a .= "<thead><tr>";
            $a .= "<th class='tb_head_th'>No</th>";
            $a .= "<th class='tb_head_th'>Code</th>";
            $a .= "<th class='tb_head_th'>Description</th>";

         

            $a .= "</thead></tr>";
            $a .= "<tr class='cl'>";
            $a .= "<td>&nbsp;</td>";
            $a .= "<td>&nbsp;</td>";
            $a .= "<td>&nbsp;</td>";
            $a .= "</tr>";
            
            foreach($query->result() as $r){
                    $a .= "<tr class='cl'>";
                    $a .= "<td>".$r->nno."</td>";
                    $a .= "<td>".$r->code."</td>";
                    $a .= "<td>".$r->name."</td>";                   
                $a .= "</tr>";
            }
        $a .= "</table>";
        echo $a;
    }






}
