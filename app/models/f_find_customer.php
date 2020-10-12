<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class f_find_customer extends CI_Model {
    
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
    public function item_list_all(){
        $cl=$this->sd['cl'];
        $bc=$this->sd['branch'];
        $selct_date=$_POST['date'];
        if($_POST['search'] == 'Key Word: code, name'){$_POST['search'] = "";}
        $sql = "SELECT 
                      c.code,
                      c.nic,
                      c.NAME,
                      c.address1,
                      c.tp,
                      c.mobile,
                      ar.`description` AS AREA,
                      IFNULL(t.acc_bal, 0) AS acc_bal,
                      IFNULL(cp.pd_value, 0) AS pd_value,
                      IFNULL(cr.rtn_value, 0) AS rtn_value 
                    FROM m_customer c 
                      JOIN m_account a 
                        ON a.`code` = c.`code` 
                      JOIN r_area ar 
                        ON ar.`code` = c.`area` 
                      LEFT JOIN 
                        (SELECT SUM(amount) AS pd_value,received_from_acc 
                        FROM t_cheque_received 
                        WHERE (STATUS = 'P') 
                          AND (ddate <= '$selct_date') 
                        GROUP BY received_from_acc) cp 
                        ON cp.received_from_acc = c.`code` 
                      LEFT JOIN 
                        (SELECT SUM(amount) AS rtn_value,received_from_acc 
                        FROM t_cheque_received 
                        WHERE (STATUS = 'R') 
                          AND (ddate <= '$selct_date') 
                        GROUP BY received_from_acc) cr 
                        ON cr.received_from_acc = c.`code` 
                      LEFT JOIN 
                        (SELECT acc_code,SUM(dr_amount) - SUM(cr_amount) AS acc_bal 
                        FROM t_account_trans 
                        WHERE (ddate <= '$selct_date') 
                        GROUP BY acc_code) t 
                        ON t.acc_code = c.`code` 
                        WHERE 
                          (c.`code` LIKE '%$_POST[search]%' 
                           OR c.nic LIKE '%$_POST[search]%' 
                           OR c.NAME LIKE '%$_POST[search]%' 
                           OR c.address1 LIKE '%$_POST[search]%' 
                           ) AND ar.`description` LIKE '%$_POST[area]%' LIMIT 50 ";

        $query = $this->db->query($sql);
        
        $a = "<table id='item_list' style='width : 100%' border='0'>";
        
         $x=0;
            foreach($query->result() as $r){
                $a .= "<tr class='cl'>";
                    $a .= "<td style='width:80px;'><input type='text' readonly='readonly' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' value='".$r->code."' title='".$r->code."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:100px'><input type='text' readonly='readonly' class='g_input_txt' id='n_".$x."' name='n_".$x."' value='".$r->nic."' title='".$r->nic."' style='border:dotted 1px #ccc; background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:150px'><input type='text' readonly='readonly' class='g_input_txt ' id='1_".$x."' name='1_".$x."' value='".$r->NAME."' title='".$r->NAME."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:250px'> <input type='text' readonly='readonly' class='g_input_txt' id='2_".$x."' name='2_".$x."' value='".$r->address1."' title='".$r->address1."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:80px'><input type='text' readonly='readonly' class='g_input_num' id='3_".$x."' name='3_".$x."' value='".$r->acc_bal."' title='".$r->acc_bal."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:80px'><input type='text' readonly='readonly' class='g_input_num ' id='4_".$x."' name='4_".$x."' value='".$r->pd_value."' title='".$r->pd_value."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:80px'><input type='text' readonly='readonly' class='g_input_num' id='5_".$x."' name='5_".$x."' value='".$r->rtn_value."' title='".$r->rtn_value."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:80px'><input type='text' readonly='readonly' class='g_input_num ' id='6_".$x."' name='6_".$x."' value='".$r->AREA."' title='".$r->AREA."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "<td style='width:80px'><input type='text' readonly='readonly' class='g_input_num ' id='7_".$x."' name='7_".$x."' value='".$r->tp."' title='".$r->tp."' style='border:dotted 1px #ccc;background-color: #f9f9ec; width:100%; cursor:pointer;'/></td>";
                    $a .= "</tr>";
              $x++;
            }
        $a .= "</table>";
        
        echo $a;
    }



    public function PDF_report(){

        $r_detail['type']=$_POST['type'];        
        $r_detail['dt']=$_POST['dt'];
        $r_detail['qno']=$_POST['qno'];

        $r_detail['page']=$_POST['page'];
        $r_detail['header']=$_POST['header'];
        $r_detail['orientation']=$_POST['orientation'];
        $r_detail['title']="Customer Balance  ";
        $r_detail['all_det']=$_POST;
        
        $this->db->select(array('name','address','tp','fax','email'));
        $this->db->where("cl",$this->sd['cl']);
        $this->db->where("bc",$this->sd['branch']);
        $r_detail['branch']=$this->db->get('m_branch')->result();


        $this->load->view($_POST['by'].'_'.'pdf',$r_detail);
    }

}
